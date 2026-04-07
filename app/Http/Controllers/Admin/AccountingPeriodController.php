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
        $periods = AccountingPeriod::orderBy('start_date', 'desc')->get();
        return view('admin.accounts.periods.index', compact('periods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => ['required', Rule::in(['fiscal_year', 'monthly', 'quarterly'])],
            'remarks' => 'nullable|string',
        ]);

        // Strict overlap prevention for the same type
        $overlap = AccountingPeriod::where('type', $validated['type'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                          ->where('end_date', '>=', $request->end_date);
                    });
            })->exists();

        if ($overlap) {
            return redirect()->back()
                ->withErrors(['start_date' => 'This period overlaps with an existing period of the same type.'])
                ->withInput();
        }

        AccountingPeriod::create($validated);

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
