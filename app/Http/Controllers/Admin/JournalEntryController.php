<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\ChartOfAccount;
use App\Models\AccountingPeriod;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JournalEntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:*accountant');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entries = JournalEntry::with(['period', 'creator', 'application.student'])
            ->withSum('items as total_amount', 'debit')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.accounts.journal-entries.index', compact('entries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = ChartOfAccount::where('is_active', true)->orderBy('code')->get();
        $periods = AccountingPeriod::where('status', 'open')
            ->orderBy('start_date', 'desc')
            ->get();
        $applications = Application::with('student')->latest()->get();

        return view('admin.accounts.journal-entries.create', compact('accounts', 'periods', 'applications'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'period_id' => 'required|exists:accounting_periods,id',
            'application_id' => 'nullable|exists:applications,id',
            'reference_number' => 'nullable|string|max:50',
            'note' => 'nullable|string',
            'items' => 'required|array|min:2',
            'items.*.chart_of_account_id' => 'required|exists:chart_of_accounts,id',
            'items.*.debit' => 'nullable|numeric|min:0',
            'items.*.credit' => 'nullable|numeric|min:0',
            'items.*.description' => 'required|string|max:255',
        ]);

        $items = collect($request->items);
        $totalDebit = $items->sum(fn($i) => (float) ($i['debit'] ?? 0));
        $totalCredit = $items->sum(fn($i) => (float) ($i['credit'] ?? 0));

        // Strict balance check (allowing for 0.01 precision difference due to float)
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return redirect()->back()
                ->withErrors(['msg' => 'Journal entry is not balanced. Total Debit (' . number_format($totalDebit, 2) . ') must equal Total Credit (' . number_format($totalCredit, 2) . ').'])
                ->withInput();
        }

        if ($totalDebit <= 0) {
            return redirect()->back()->withErrors(['msg' => 'Voucher amount cannot be zero.'])->withInput();
        }

        try {
            DB::beginTransaction();

            $entry = JournalEntry::create([
                'period_id' => $request->period_id,
                'application_id' => $request->application_id,
                'date' => $request->date,
                'reference_number' => $request->reference_number ?? 'JV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2))),
                'note' => $request->note,
                'status' => 'posted',
                'created_by' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                $debit = (float) ($item['debit'] ?? 0);
                $credit = (float) ($item['credit'] ?? 0);

                if ($debit > 0 || $credit > 0) {
                    JournalEntryItem::create([
                        'journal_entry_id' => $entry->id,
                        'chart_of_account_id' => $item['chart_of_account_id'],
                        'debit' => $debit,
                        'credit' => $credit,
                        'description' => $item['description'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.journal-entries.index')->with('success', 'Journal voucher posted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Posting failed: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(JournalEntry $entry)
    {
        $entry->load(['items.chartOfAccount', 'period', 'creator', 'application.student']);
        return view('admin.accounts.journal-entries.show', compact('entry'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JournalEntry $entry)
    {
        // Logic to prevent deletion if reconciled (future)
        $entry->delete();
        return redirect()->back()->with('success', 'Journal entry deleted.');
    }
}
