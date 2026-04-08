<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\{Application, OfficeAccount, Payment, Setting, Student, User, JournalEntry, JournalEntryItem, AccountingPeriod, ChartOfAccount};
use App\Services\CommissionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->middleware('can:*accountant');
        $this->commissionService = $commissionService;
    }

    public function index(Request $request)
    {
        $this->authorize('*accountant');

        $query = Payment::with(['student', 'application', 'collector']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas(
                    'student',
                    function ($sq) use ($search) {
                        $sq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    }
                )->orWhere('receipt_number', 'like', "%{$search}%")
                    ->orWhereHas(
                        'application',
                        function ($aq) use ($search) {
                            $aq->where('application_id', 'like', "%{$search}%");
                        }
                    );
            });
        }

        if ($type = $request->get('payment_type')) {
            $query->where('payment_type', $type);
        }

        if ($status = $request->get('payment_status')) {
            $query->where('payment_status', $status);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $this->authorize('*accountant');

        $applications = Application::with('student')->latest()->get();
        $users = User::orderBy('name')->get(['id', 'name']);
        $selected_application_id = $request->get('application_id');
        $accounts = OfficeAccount::where('status', 'active')->get();

        return view('admin.payments.create', compact('applications', 'users', 'selected_application_id', 'accounts'));
    }

    public function store(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $this->validatePayment($request);

        $application = Application::findOrFail($validated['application_id']);
        $validated['student_id'] = $application->student_id;

        try {
            DB::beginTransaction();

            $payment = Payment::create($validated);

            // NEW: Double-Entry Ledger Posting
            if ($validated['payment_status'] === 'completed') {
                $officeAccount = OfficeAccount::find($payment->office_account_id);
                $period = AccountingPeriod::where('is_closed', false)
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->first();

                if ($officeAccount && $officeAccount->chart_of_account_id && $period) {
                    $entry = JournalEntry::create([
                        'period_id' => $period->id,
                        'date' => $payment->payment_date ?? now(),
                        'reference_number' => 'JV-PAY-' . $payment->receipt_number,
                        'note' => 'Automated post for Student Payment: ' . $payment->receipt_number,
                        'status' => 'posted',
                        'created_by' => Auth::id(),
                    ]);

                    $payment->update(['journal_entry_id' => $entry->id]);

                    // Debit Bank/Cash
                    JournalEntryItem::create([
                        'journal_entry_id' => $entry->id,
                        'chart_of_account_id' => $officeAccount->chart_of_account_id,
                        'debit' => $payment->amount,
                        'credit' => 0,
                        'description' => 'Payment received in ' . $officeAccount->account_name,
                    ]);

                    // Credit Student Fees Income (Finding dynamic head)
                    $incomeHead = ChartOfAccount::where('name', 'like', '%Student Fee%')
                        ->orWhere('code', '4001') // Common income code
                        ->first() ?? ChartOfAccount::where('type', 'revenue')->first();

                    if ($incomeHead) {
                        JournalEntryItem::create([
                            'journal_entry_id' => $entry->id,
                            'chart_of_account_id' => $incomeHead->id,
                            'debit' => 0,
                            'credit' => $payment->amount,
                            'description' => 'Student Payment Credit: ' . $application->student->first_name,
                        ]);
                    }
                }
            }

            if ($validated['payment_status'] === 'completed') {
                $this->commissionService->calculateCommissions($payment);
            }

            DB::commit();
            return redirect()
                ->route('admin.payments.index')
                ->with('success', 'Payment recorded and Ledger posted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Posting failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(Payment $payment)
    {
        $this->authorize('*accountant');

        $applications = Application::with('student')->latest()->get();
        $users = User::orderBy('name')->get(['id', 'name']);
        $accounts = OfficeAccount::where('status', 'active')->get();

        return view('admin.payments.edit', compact('payment', 'applications', 'users', 'accounts'));
    }

    public function update(Request $request, Payment $payment)
    {
        $this->authorize('*accountant');

        $validated = $this->validatePayment($request);

        $application = Application::findOrFail($validated['application_id']);
        $validated['student_id'] = $application->student_id;

        $payment->update($validated);

        if ($payment->payment_status === 'completed') {
            $this->commissionService->calculateCommissions($payment);
        }

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('*accountant');

        try {
            DB::beginTransaction();
            if ($payment->journal_entry_id) {
                JournalEntry::find($payment->journal_entry_id)?->delete();
            }
            $payment->delete();
            DB::commit();

            return redirect()
                ->route('admin.payments.index')
                ->with('success', 'Payment and ledger entry deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Deletion failed: ' . $e->getMessage()]);
        }
    }

    public function downloadInvoice(Payment $payment)
    {
        $this->authorize('*accountant');

        $payment->load(['student', 'collector', 'application.university.country', 'application.course', 'application.intake']);
        $settings = Setting::pluck('value', 'key')->all();

        $pdf = Pdf::loadView('admin.payments.invoice', compact('payment', 'settings'));

        $filename = 'Invoice_' . ($payment->receipt_number ?: $payment->id) . '.pdf';

        return $pdf->download($filename);
    }

    private function validatePayment(Request $request): array
    {
        return $request->validate([
            'application_id' => ['required', 'exists:applications,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_type' => ['required', Rule::in(['advance', 'partial', 'final'])],
            'payment_date' => ['nullable', 'date'],
            'receipt_number' => ['nullable', 'string', 'max:50'],
            'payment_status' => ['required', Rule::in(['pending', 'completed'])],
            'office_account_id' => ['nullable', 'exists:office_accounts,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    public function getApplicationBalance(Request $request)
    {
        $request->validate(['application_id' => 'required|exists:applications,id']);

        $application = Application::with('payments')->findOrFail($request->application_id);

        $totalPaid = $application->payments()
            ->whereIn('payment_status', ['pending', 'completed'])
            ->sum('amount');

        return response()->json([
            'total_fee' => $application->total_fee,
            'total_paid' => $totalPaid,
            'balance' => $application->total_fee - $totalPaid
        ]);
    }
}
