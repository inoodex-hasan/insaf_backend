<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficeAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfficeAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:*accountant');
    }

    public function index(Request $request)
    {
        $this->authorize('*accountant');

        $query = OfficeAccount::with(['creator'])
            ->withSum('incomingTransactions as total_income', 'credit')
            ->withSum('outgoingTransactions as total_expense', 'debit');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('account_name', 'like', "%{$search}%")
                    ->orWhere('provider_name', 'like', "%{$search}%")
                    ->orWhere('account_number', 'like', "%{$search}%");
            });
        }

        if ($type = $request->get('account_type')) {
            $query->where('account_type', $type);
        }

        $accounts = $query->latest()->paginate(15)->withQueryString();

        return view('admin.office-accounts.index', compact('accounts'));
    }

    public function create()
    {
        $this->authorize('*accountant');
        return view('admin.office-accounts.create');
    }

    public function store(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $this->validateAccount($request);

        OfficeAccount::create($validated);

        return redirect()
            ->route('admin.office-accounts.index')
            ->with('success', 'Office account created successfully.');
    }

    public function edit(OfficeAccount $officeAccount)
    {
        $this->authorize('*accountant');
        return view('admin.office-accounts.edit', compact('officeAccount'));
    }

    public function update(Request $request, OfficeAccount $officeAccount)
    {
        $this->authorize('*accountant');

        $validated = $this->validateAccount($request);

        $officeAccount->update($validated);

        return redirect()
            ->route('admin.office-accounts.index')
            ->with('success', 'Office account updated successfully.');
    }

    public function destroy(OfficeAccount $officeAccount)
    {
        $this->authorize('*accountant');

        $officeAccount->delete();

        return redirect()
            ->route('admin.office-accounts.index')
            ->with('success', 'Office account deleted successfully.');
    }

    private function validateAccount(Request $request): array
    {
        return $request->validate([
            'account_name' => ['required', 'string', 'max:255'],
            'account_type' => ['required', Rule::in(['bank', 'mfs', 'cash'])],
            'provider_name' => ['nullable', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:100'],
            'branch_name' => ['nullable', 'string', 'max:255'],
            'opening_balance' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
