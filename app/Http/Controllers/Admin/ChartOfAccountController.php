<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChartOfAccountController extends Controller
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
        // Fetch all accounts with their nested children
        $accounts = ChartOfAccount::with('children')
            ->whereNull('parent_id')
            ->orderBy('type')
            ->orderBy('code')
            ->get();

        // Get flat list for parent selection in the form
        $parentAccounts = ChartOfAccount::orderBy('name')->get();

        return view('admin.accounts.chart-of-accounts.index', compact('accounts', 'parentAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'code' => 'required|string|max:20|unique:chart_of_accounts,code',
            'name' => 'required|string|max:100',
            'type' => ['required', Rule::in(['asset', 'liability', 'equity', 'revenue', 'expense'])],
            'is_active' => 'boolean',
        ]);

        ChartOfAccount::create($validated);

        return redirect()->back()->with('success', 'New account added to Chart of Accounts.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChartOfAccount $account)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => ['required', 'string', 'max:20', Rule::unique('chart_of_accounts')->ignore($account->id)],
        ]);

        $account->update($validated);

        return redirect()->back()->with('success', 'Account details updated.');
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(ChartOfAccount $account)
    {
        if ($account->is_default) {
            return redirect()->back()->withErrors(['msg' => 'System default accounts cannot be deactivated.']);
        }

        $account->update(['is_active' => !$account->is_active]);

        return redirect()->back()->with('success', 'Account status toggled.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChartOfAccount $account)
    {
        if ($account->is_default) {
            return redirect()->back()->withErrors(['msg' => 'System default accounts cannot be deleted.']);
        }

        if ($account->children()->exists()) {
            return redirect()->back()->withErrors(['msg' => 'Please remove sub-accounts before deleting this head.']);
        }

        if ($account->journalEntryItems()->exists()) {
            return redirect()->back()->withErrors(['msg' => 'Cannot delete account with existing financial transactions.']);
        }

        $account->delete();

        return redirect()->back()->with('success', 'Account removed from system.');
    }
}
