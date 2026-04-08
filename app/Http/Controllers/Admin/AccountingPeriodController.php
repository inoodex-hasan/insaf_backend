<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountingPeriod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountingPeriodController extends Controller
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
        $periods = AccountingPeriod::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        return view('admin.accounts.periods.index', compact('periods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'remarks' => 'nullable|string',
        ]);

        // Check if period already exists
        $exists = AccountingPeriod::where('year', $validated['year'])
            ->where('month', $validated['month'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['month' => 'This accounting period already exists.'])
                ->withInput();
        }

        AccountingPeriod::create([
            'year' => $validated['year'],
            'month' => $validated['month'],
            'is_closed' => false,
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Accounting period created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountingPeriod $period)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['open', 'closed'])],
            'remarks' => 'nullable|string',
        ]);

        $period->update($validated);

        return redirect()->back()->with('success', 'Accounting period status updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountingPeriod $period)
    {
        // Future: Add check for linked journal entries to prevent deletion of active history
        $period->delete();

        return redirect()->back()->with('success', 'Accounting period removed.');
    }
}
