@extends('admin.layouts.master')

@section('title', 'Generate Salaries')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Generate Salaries</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.salaries.index') }}" class="btn btn-outline-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                Back to Salaries
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.salaries.generate') }}" method="GET" class="flex gap-4 items-center">
                <div>
                    <label for="month" class="block text-sm font-medium">Select Month</label>
                    <input type="month" name="month" value="{{ $month }}" class="form-input" required />
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>

        <form action="{{ route('admin.salaries.bulk-store') }}" method="POST">
            @csrf
            <input type="hidden" name="month" value="{{ $month }}" />
            <div class="mb-4 flex justify-end">
                <button type="button" id="addCustomSalaryRow" class="btn btn-outline-primary">Add Custom Employee</button>
            </div>
            <div class="datatable">
                <div class="overflow-x-auto">
                    <table class="table-hover w-full table-auto">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Basic Salary</th>
                                <th>Bonus</th>
                                <th>Deduction</th>
                                <th>Net Salary</th>
                                <th>Account Number</th>
                                <th>Bank Name</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody id="salaryRowsBody">
                            @forelse($salaries as $index => $salary)
                                <input type="hidden" name="salaries[{{ $index }}][id]"
                                    value="{{ $salary['id'] ?? '' }}" />
                                <input type="hidden" name="salaries[{{ $index }}][user_id]"
                                    value="{{ $salary['user_id'] ?? '' }}" />
                                @if (!empty($salary['user_id']))
                                    <input type="hidden" name="salaries[{{ $index }}][employee_name]"
                                        value="{{ $salary['name'] }}" />
                                @endif
                                @if (!empty($salary['user_id']))
                                    <input type="hidden" name="salaries[{{ $index }}][account_number]"
                                        value="{{ $salary['account_number'] ?? '' }}" />
                                    <input type="hidden" name="salaries[{{ $index }}][bank_name]"
                                        value="{{ $salary['bank_name'] ?? '' }}" />
                                @endif
                                <input type="hidden" name="salaries[{{ $index }}][bank_branch]"
                                    value="{{ $salary['bank_branch'] ?? '' }}" />
                                <input type="hidden" name="salaries[{{ $index }}][routing_number]"
                                    value="{{ $salary['routing_number'] ?? '' }}" />
                                <tr>
                                    <td><input type="text" name="salaries[{{ $index }}][employee_name]"
                                            value="{{ $salary['name'] }}" class="form-input w-52" required /></td>
                                    <td><input type="text" name="salaries[{{ $index }}][designation]"
                                            value="{{ $salary['designation'] }}" class="form-input w-32" /></td>
                                    <td>
                                        <input type="number" step="0.01"
                                            name="salaries[{{ $index }}][basic_salary]"
                                            value="{{ $salary['basic_salary'] }}" class="form-input w-40" required />
                                    </td>
                                    <td>
                                        <input type="number" step="0.01"
                                            name="salaries[{{ $index }}][bonus]"
                                            value="{{ $salary['bonus'] }}" class="form-input w-24" />
                                    </td>
                                    <td>
                                        <input type="number" step="0.01"
                                            name="salaries[{{ $index }}][deduction]"
                                            value="{{ $salary['deduction'] }}" class="form-input w-24" />
                                    </td>
                                    <td class="font-bold text-primary">
                                        <span id="net-{{ $index }}">{{ number_format($salary['net_salary'], 2) }}</span>
                                    </td>
                                    <td>
                                        <input type="text" name="salaries[{{ $index }}][account_number]"
                                            value="{{ $salary['account_number'] ?? '' }}" class="form-input w-40" />
                                    </td>
                                    <td>
                                        <input type="text" name="salaries[{{ $index }}][bank_name]"
                                            value="{{ $salary['bank_name'] ?? '' }}" class="form-input w-40" />
                                    </td>
                                    {{-- <td>
                                        @if ($salary['id'])
                                            <span class="badge badge-outline-success">Exists</span>
                                        @else
                                            <span class="badge badge-outline-warning">New</span>
                                        @endif
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No employees found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if (count($salaries) > 0)
                <div class="mt-4 flex justify-end">
                    <!-- <div class="flex gap-2">
                        <a href="{{ route('admin.salaries.export-pdf', ['month' => $month]) }}"
                            class="btn btn-outline-danger gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14,2 14,8 20,8" />
                                <line x1="16" y1="13" x2="8" y2="13" />
                                <line x1="16" y1="17" x2="8" y2="17" />
                                <polyline points="10,9 9,9 8,9" />
                            </svg>
                            Export to PDF
                        </a>
                    </div> -->
                    <button type="submit" class="btn btn-success">Save All Salaries</button>
                </div>
            @endif
        </form>
    </div>

    <script>
        function calculateNet(index) {
            const basic = parseFloat(document.querySelector(`input[name="salaries[${index}][basic_salary]"]`).value) || 0;
            const bonus = parseFloat(document.querySelector(`input[name="salaries[${index}][bonus]"]`).value) || 0;
            const deduction = parseFloat(document.querySelector(`input[name="salaries[${index}][deduction]"]`).value) || 0;
            const net = basic + bonus - deduction;
            document.getElementById(`net-${index}`).textContent = net.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($salaries as $index => $salary)
                document.querySelector(`input[name="salaries[{{ $index }}][basic_salary]"]`)
                    .addEventListener('input', () => calculateNet({{ $index }}));
                document.querySelector(`input[name="salaries[{{ $index }}][bonus]"]`).addEventListener(
                    'input', () => calculateNet({{ $index }}));
                document.querySelector(`input[name="salaries[{{ $index }}][deduction]"]`)
                    .addEventListener('input', () => calculateNet({{ $index }}));
            @endforeach

            const salaryRowsBody = document.getElementById('salaryRowsBody');
            const addCustomSalaryRowBtn = document.getElementById('addCustomSalaryRow');
            let salaryRowIndex = {{ count($salaries) }};

            addCustomSalaryRowBtn.addEventListener('click', function() {
                const index = salaryRowIndex++;
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <input type="hidden" name="salaries[${index}][id]" value="" />
                    <input type="hidden" name="salaries[${index}][user_id]" value="" />
                    <input type="hidden" name="salaries[${index}][designation]" value="Custom" />
                    <input type="hidden" name="salaries[${index}][bank_branch]" value="" />
                    <input type="hidden" name="salaries[${index}][routing_number]" value="" />
                    <td><input type="text" name="salaries[${index}][employee_name]" class="form-input w-52" placeholder="Employee name" required /></td>
                    <td>Custom</td>
                    <td><input type="number" step="0.01" min="0" name="salaries[${index}][basic_salary]" value="0" class="form-input w-40" required /></td>
                    <td><input type="number" step="0.01" min="0" name="salaries[${index}][bonus]" value="0" class="form-input w-24" /></td>
                    <td><input type="number" step="0.01" min="0" name="salaries[${index}][deduction]" value="0" class="form-input w-24" /></td>
                    <td class="font-bold text-primary"><span id="net-${index}">0.00</span></td>
                    <td><input type="text" name="salaries[${index}][account_number]" class="form-input w-40" placeholder="Account no" /></td>
                    <td><input type="text" name="salaries[${index}][bank_name]" class="form-input w-40" placeholder="Bank name" /></td>
                `;

                salaryRowsBody.appendChild(tr);
                document.querySelector(`input[name="salaries[${index}][basic_salary]"]`)
                    .addEventListener('input', () => calculateNet(index));
                document.querySelector(`input[name="salaries[${index}][bonus]"]`)
                    .addEventListener('input', () => calculateNet(index));
                document.querySelector(`input[name="salaries[${index}][deduction]"]`)
                    .addEventListener('input', () => calculateNet(index));
            });
        });
    </script>
@endsection
