<?php

namespace App\Exports;

use App\Models\Salary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalaryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function collection()
    {
        return Salary::with('user')
            ->where('month', $this->month)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Employee Name',
            'Designation',
            // 'Basic Salary',
            // 'Bonus',
            // 'Deductions',
            'Net Salary',
            // 'Account Number',
            // 'Bank Name',
            // 'Bank Branch',
            // 'IFSC Code',
            // 'Payment Status',
        ];
    }

    public function map($salary): array
    {
        return [
            $salary->employee_name,
            $salary->user->roles->first()->name ?? 'Employee',
            // $salary->basic_salary,
            // $salary->bonus,
            // $salary->tax_deduction + $salary->insurance_deduction + $salary->other_deductions,
            $salary->net_salary,
            // $salary->user->account_number ?? 'N/A',
            // $salary->user->bank_name ?? 'N/A',
            // $salary->user->bank_branch ?? 'N/A',
            // $salary->user->ifsc_code ?? 'N/A',
            // ucfirst($salary->payment_status),
        ];
    }
}