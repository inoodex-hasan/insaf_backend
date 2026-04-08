<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\{Expense, ChartOfAccount, OfficeAccount, Salary, User};
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class SalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:*accountant');
    }

    public function index(Request $request)
    {
        $this->authorize('*accountant');

        $query = Salary::with(['user', 'creator']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('month', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('payment_status', $status);
        }

        if ($month = $request->get('month')) {
            $query->where('month', $month);
        }

        $salaries = $query->latest()->paginate(15)->withQueryString();

        // Get summary stats
        $stats = [
            'total_salaries' => Salary::sum('net_salary'),
            'total_paid' => Salary::where('payment_status', 'paid')->sum('paid_amount'),
            'total_pending' => Salary::where('payment_status', 'pending')->sum('net_salary'),
            'total_partial' => Salary::where('payment_status', 'partial')->sum(DB::raw('net_salary - paid_amount')),
        ];

        return view('admin.salaries.index', compact('salaries', 'stats'));
    }

    public function create()
    {
        $this->authorize('*accountant');

        $users = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })->orderBy('name')->get(['id', 'name', 'email']);

        // Generate default month (current month)
        $defaultMonth = now()->format('Y-m');

        return view('admin.salaries.create', compact('users', 'defaultMonth'));
    }

    public function store(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $this->validateSalary($request);

        Salary::create($validated);

        return redirect()
            ->route('admin.salaries.index')
            ->with('success', 'Salary record created successfully.');
    }

    public function show(Salary $salary)
    {
        $this->authorize('*accountant');

        $salary->load(['user', 'creator']);

        return view('admin.salaries.show', compact('salary'));
    }

    public function edit(Salary $salary)
    {
        $this->authorize('*accountant');

        $users = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.salaries.edit', compact('salary', 'users'));
    }

    public function update(Request $request, Salary $salary)
    {
        $this->authorize('*accountant');

        $validated = $this->validateSalary($request);

        $salary->update($validated);

        return redirect()
            ->route('admin.salaries.index')
            ->with('success', 'Salary record updated successfully.');
    }

    public function destroy(Salary $salary)
    {
        $this->authorize('*accountant');

        $salary->delete();

        return redirect()
            ->route('admin.salaries.index')
            ->with('success', 'Salary record deleted successfully.');
    }

    public function markAsPaid(Request $request, Salary $salary)
    {
        $this->authorize('*accountant');

        $validated = $request->validate([
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,bank_transfer,mobile_banking,cheque'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $salary->update([
            'payment_status' => 'paid',
            'paid_amount' => $salary->net_salary,
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'bank_name' => $validated['bank_name'] ?? null,
            'transaction_id' => $validated['transaction_id'] ?? null,
            'notes' => $validated['notes'] ?? $salary->notes,
        ]);

        return redirect()
            ->route('admin.salaries.show', $salary->id)
            ->with('success', 'Salary marked as paid.');
    }

    public function generate(Request $request)
    {
        $this->authorize('*accountant');

        // Get all users as employees (excluding admins)
        $employees = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })->with('roles')->get();

        $month = $request->get('month', now()->format('Y-m'));

        $salaries = [];
        foreach ($employees as $employee) {
            // Check if salary already exists for this month
            $existingSalary = Salary::where('user_id', $employee->id)->where('month', $month)->first();

            if ($existingSalary) {
                // Use existing
                $salaries[] = [
                    'id' => $existingSalary->id,
                    'user_id' => $employee->id,
                    'name' => $employee->name,
                    'designation' => $employee->roles->first()->name ?? 'Employee',
                    'basic_salary' => $existingSalary->basic_salary,
                    'bonus' => $existingSalary->bonus,
                    'deduction' => $existingSalary->tax_deduction + $existingSalary->insurance_deduction + $existingSalary->other_deductions,
                    'net_salary' => $existingSalary->net_salary,
                    'status' => $existingSalary->payment_status,
                    'account_number' => $employee->account_number,
                    'bank_name' => $employee->bank_name,
                    'bank_branch' => $employee->bank_branch,
                    'routing_number' => $employee->routing_number,
                ];
            } else {
                // Use user's saved basic salary
                $designation = $employee->roles->first()->name ?? 'Employee';
                $basic = $employee->basic_salary ?? 0;
                $bonus = 0;
                $deduction = 0;
                $net = $basic + $bonus - $deduction;

                $salaries[] = [
                    'id' => null,
                    'user_id' => $employee->id,
                    'name' => $employee->name,
                    'designation' => $designation,
                    'basic_salary' => $basic,
                    'bonus' => $bonus,
                    'deduction' => $deduction,
                    'net_salary' => $net,
                    'status' => 'pending',
                    'account_number' => $employee->account_number,
                    'bank_name' => $employee->bank_name,
                    'bank_branch' => $employee->bank_branch,
                    'routing_number' => $employee->routing_number,
                ];
            }
        }

        return view('admin.salaries.generate', compact('salaries', 'month'));
    }

    public function bulkStore(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $request->validate([
            'month' => ['required', 'string', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'salaries' => ['required', 'array'],
            'salaries.*.user_id' => ['nullable', 'exists:users,id'],
            'salaries.*.employee_name' => ['required', 'string', 'max:255'],
            'salaries.*.basic_salary' => ['required', 'numeric', 'min:0'],
            'salaries.*.bonus' => ['nullable', 'numeric', 'min:0'],
            'salaries.*.deduction' => ['nullable', 'numeric', 'min:0'],
        ]);

        $created = 0;
        foreach ($validated['salaries'] as $salaryData) {
            // Check if already exists
            $existing = Salary::where('user_id', $salaryData['user_id'])
                ->where('month', $validated['month'])
                ->first();

            if (!$existing) {
                Salary::create([
                    'user_id' => $salaryData['user_id'],
                    'employee_name' => $salaryData['employee_name'],
                    'month' => $validated['month'],
                    'basic_salary' => $salaryData['basic_salary'],
                    'bonus' => $salaryData['bonus'] ?? 0,
                    'overtime_amount' => 0,
                    'allowances' => 0,
                    'tax_deduction' => $salaryData['deduction'] ?? 0,
                    'insurance_deduction' => 0,
                    'other_deductions' => 0,
                    'payment_status' => 'pending',
                ]);
                $created++;
            }
        }

        return redirect()
            ->route('admin.salaries.index')
            ->with('success', "{$created} salary records created successfully.");
    }

    public function bulkUpdateBasicSalary(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $request->validate([
            'employees' => ['required', 'array'],
            'employees.*.user_id' => ['required', 'exists:users,id'],
            'employees.*.basic_salary' => ['required', 'numeric', 'min:0'],
        ]);

        $updated = 0;
        foreach ($validated['employees'] as $employeeData) {
            User::where('id', $employeeData['user_id'])
                ->update(['basic_salary' => $employeeData['basic_salary']]);
            $updated++;
        }

        return redirect()
            ->back()
            ->with('success', "{$updated} employee basic salaries updated successfully.");
    }

    public function bulkUpdateAccountDetails(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $request->validate([
            'employees' => ['required', 'array'],
            'employees.*.user_id' => ['required', 'exists:users,id'],
            'employees.*.account_number' => ['nullable', 'string', 'max:255'],
            'employees.*.bank_name' => ['nullable', 'string', 'max:255'],
            'employees.*.bank_branch' => ['nullable', 'string', 'max:255'],
            'employees.*.routing_number' => ['nullable', 'string', 'max:255'],
        ]);

        $updated = 0;
        foreach ($validated['employees'] as $employeeData) {
            User::where('id', $employeeData['user_id'])
                ->update([
                    'account_number' => $employeeData['account_number'],
                    'bank_name' => $employeeData['bank_name'],
                    'bank_branch' => $employeeData['bank_branch'],
                    'routing_number' => $employeeData['routing_number'],
                ]);
            $updated++;
        }

        return redirect()
            ->back()
            ->with('success', "{$updated} employee account details updated successfully.");
    }

    public function getEmployeeDetails(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $user = User::find($request->user_id);

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'account_number' => $user->account_number,
            'bank_name' => $user->bank_name,
            'bank_branch' => $user->bank_branch,
            'routing_number' => $user->routing_number,
        ]);
    }

    public function checkExistingSalary(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|string',
        ]);

        $existing = Salary::where('user_id', $request->user_id)
            ->where('month', $request->month)
            ->first();

        if ($existing) {
            return response()->json([
                'exists' => true,
                'salary' => $existing,
                'message' => 'Salary record already exists for this employee and month.',
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function bulkPayForm(Request $request)
    {
        $this->authorize('*accountant');

        $salaryIds = $request->get('salary_ids', []);

        // Convert comma-separated string to array if needed
        if (is_string($salaryIds) && !empty($salaryIds)) {
            $salaryIds = array_filter(array_map('intval', explode(',', trim($salaryIds))));
        } elseif (is_array($salaryIds)) {
            $salaryIds = array_filter(array_map('intval', $salaryIds));
        } else {
            $salaryIds = [];
        }

        if (empty($salaryIds)) {
            return redirect()->route('admin.salaries.index')->with('error', 'Please select at least one salary.');
        }

        $salaries = Salary::whereIn('id', $salaryIds)
            ->whereIn('payment_status', ['pending', 'partial'])
            ->get();

        if ($salaries->isEmpty()) {
            return redirect()->route('admin.salaries.index')->with('error', 'No pending or partial salaries selected.');
        }

        $totalAmount = $salaries->sum(function ($salary) {
            return $salary->net_salary - $salary->paid_amount;
        });
        $accounts = OfficeAccount::where('status', 'active')->get();
        $categories = ChartOfAccount::where('is_active', true)
            ->where('type', 'expense')
            ->orderBy('code')
            ->get();

        return view('admin.salaries.bulk-pay', compact('salaries', 'totalAmount', 'accounts', 'categories'));
    }

    public function bulkPay(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $request->validate([
            'salary_ids' => ['required', 'array'],
            'salary_ids.*' => ['exists:salaries,id'],
            'office_account_id' => ['nullable', 'exists:office_accounts,id'],
            'payment_method' => ['required', 'in:cash,bank_transfer,mobile_banking,cheque'],
            'chart_of_account_id' => ['required', 'exists:chart_of_accounts,id'],
            'payment_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $salaries = Salary::whereIn('id', $validated['salary_ids'])
            ->whereIn('payment_status', ['pending', 'partial'])
            ->get();

        if ($salaries->isEmpty()) {
            return back()->with('error', 'No valid salaries found.');
        }

        DB::beginTransaction();
        try {
            foreach ($salaries as $salary) {
                $remainingAmount = $salary->net_salary - $salary->paid_amount;

                // Create expense for salary payment
                $expense = Expense::create([
                    'description' => 'Salary Payment - ' . $salary->employee_name . ' (' . $salary->month . ')',
                    'amount' => $remainingAmount,
                    'expense_date' => $validated['payment_date'],
                    'chart_of_account_id' => $validated['chart_of_account_id'],
                    'payment_method' => $validated['payment_method'],
                    'office_account_id' => $validated['office_account_id'],
                    'salary_id' => $salary->id,
                    'created_by' => auth()->id(),
                    'notes' => $validated['notes'],
                ]);

                // Update salary status
                $salary->paid_amount = $salary->net_salary;
                $salary->payment_status = 'paid';
                $salary->payment_date = $validated['payment_date'];
                $salary->payment_method = $validated['payment_method'];
                $salary->save();
            }

            DB::commit();
            return redirect()->route('admin.salaries.index')->with('success', 'Bulk salary payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing bulk payment: ' . $e->getMessage());
        }
    }

    private function validateSalary(Request $request): array
    {
        return $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'employee_name' => ['required', 'string', 'max:255'],
            'month' => ['required', 'string', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'overtime_amount' => ['nullable', 'numeric', 'min:0'],
            'bonus' => ['nullable', 'numeric', 'min:0'],
            'allowances' => ['nullable', 'numeric', 'min:0'],
            'tax_deduction' => ['nullable', 'numeric', 'min:0'],
            'insurance_deduction' => ['nullable', 'numeric', 'min:0'],
            'other_deductions' => ['nullable', 'numeric', 'min:0'],
            'payment_status' => ['required', 'in:pending,partial,paid'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_date' => ['nullable', 'date'],
            'payment_method' => ['nullable', 'in:cash,bank_transfer,mobile_banking,cheque'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    public function exportExcel(Request $request)
    {
        $this->authorize('*accountant');

        $month = $request->get('month', now()->format('Y-m'));

        return Excel::download(
            new \App\Exports\SalaryExport($month),
            "salaries-{$month}.xlsx"
        );
    }

    public function exportPdf(Request $request)
    {
        $this->authorize('*accountant');

        $month = $request->get('month', now()->format('Y-m'));

        $salaries = Salary::with('user')
            ->where('month', $month)
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.salaries.pdf', compact('salaries', 'month'));

        return $pdf->download("salaries-{$month}.pdf");
    }
}
