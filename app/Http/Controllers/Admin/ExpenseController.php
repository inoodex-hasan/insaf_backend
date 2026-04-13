<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\{Expense, ChartOfAccount, OfficeAccount, Salary};
use Barryvdh\DomPDF\Facade\Pdf;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:*accountant');
    }

    public function index(Request $request)
    {
        $this->authorize('*accountant');

        $query = Expense::with(['creator', 'chartOfAccount']);

        // Search filter
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        // Category filter (using chart_of_account)
        if ($category = $request->get('category')) {
            $query->whereHas('chartOfAccount', function ($q) use ($category) {
                $q->where('name', 'like', "%{$category}%");
            });
        }

        // Timeframe filter: daily, monthly, yearly
        if ($timeframe = $request->get('timeframe')) {
            switch ($timeframe) {
                case 'daily':
                    $query->whereDate('expense_date', today());
                    break;

                case 'monthly':
                    $query->whereMonth('expense_date', now()->month)
                        ->whereYear('expense_date', now()->year);
                    break;

                case 'yearly':
                    $query->whereYear('expense_date', now()->year);
                    break;
            }
        }

        $expenses = $query->latest()->paginate(15)->withQueryString();

        // Fetch expense accounts
        $categories = ChartOfAccount::where('is_active', true)
            ->where('type', 'expense')
            ->orderBy('code')
            ->get();

        return view('admin.expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $this->authorize('*accountant');
        $categories = ChartOfAccount::where('is_active', true)
            ->where('type', 'expense')
            ->orderBy('code')
            ->get();
        $accounts = OfficeAccount::where('status', 'active')->get();
        $pendings_salaries = Salary::whereIn('payment_status', ['pending', 'partial'])->get();

        return view('admin.expenses.create', compact('categories', 'accounts', 'pendings_salaries'));
    }

    public function store(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $this->validateExpense($request);

        $expense = Expense::create($validated);

        // Update salary payment status if linked
        if ($expense->salary_id) {
            $salary = Salary::find($expense->salary_id);
            if ($salary) {
                $totalPaid = Expense::where('salary_id', $expense->salary_id)->sum('amount');
                $salary->paid_amount = $totalPaid;

                if ($totalPaid >= $salary->net_salary) {
                    $salary->payment_status = 'paid';
                } elseif ($totalPaid > 0) {
                    $salary->payment_status = 'partial';
                }
                $salary->save();
            }
        }

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        $this->authorize('*accountant');
        $categories = ChartOfAccount::where('is_active', true)
            ->where('type', 'expense')
            ->orderBy('code')
            ->get();
        $accounts = OfficeAccount::where('status', 'active')->get();

        return view('admin.expenses.edit', compact('expense', 'categories', 'accounts'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('*accountant');

        $validated = $this->validateExpense($request);

        $expense->update($validated);

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('*accountant');

        $expense->delete();

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    public function downloadPdf(Expense $expense)
    {
        $this->authorize('*accountant');

        $pdf = Pdf::loadView('admin.expenses.pdf', compact('expense'));
        return $pdf->download('expense-' . $expense->id . '.pdf');
    }

    private function validateExpense(Request $request): array
    {
        return $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
            'chart_of_account_id' => ['required', 'exists:chart_of_accounts,id'],
            'payment_method' => ['required', 'in:cash,bank_transfer,mobile_banking,cheque'],
            'office_account_id' => ['nullable', 'exists:office_accounts,id'],
            'salary_id' => ['nullable', 'exists:salaries,id'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
