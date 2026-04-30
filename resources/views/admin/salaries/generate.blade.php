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

        {{-- Employee Account Details Section --}}
        <div class="mb-6 p-4 bg-blue/5 rounded-lg border border-blue/20">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Employee Account Details</h3>
                <button type="button" id="toggleAccountDetailsForm" class="btn btn-sm btn-outline-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="mr-1">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Edit Account Details
                </button>
            </div>
            <p class="text-sm text-white-dark mb-3">Set or update each employee's bank account details for salary transfers.
            </p>

            <form action="{{ route('admin.salaries.bulk-update-account-details') }}" method="POST" id="accountDetailsForm"
                class="hidden">
                @csrf
                <div class="overflow-x-auto">
                    <table class="table-hover w-full table-auto">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Account Number</th>
                                <th>Bank Name</th>
                                <th>Bank Branch</th>
                                <th>Routing Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salaries as $index => $salary)
                                <input type="hidden" name="employees[{{ $index }}][user_id]"
                                    value="{{ $salary['user_id'] }}" />
                                <tr>
                                    <td class="font-semibold">{{ $salary['name'] }}</td>
                                    <td>
                                        <input type="text" name="employees[{{ $index }}][account_number]"
                                            value="{{ $salary['account_number'] ?? '' }}" class="form-input w-40" />
                                    </td>
                                    <td>
                                        <input type="text" name="employees[{{ $index }}][bank_name]"
                                            value="{{ $salary['bank_name'] ?? '' }}" class="form-input w-40" />
                                    </td>
                                    <td>
                                        <input type="text" name="employees[{{ $index }}][bank_branch]"
                                            value="{{ $salary['bank_branch'] ?? '' }}" class="form-input w-40" />
                                    </td>
                                    <td>
                                        <input type="text" name="employees[{{ $index }}][routing_number]"
                                            value="{{ $salary['routing_number'] ?? '' }}" class="form-input w-32" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="btn btn-primary gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Save All Account Details
                    </button>
                </div>
            </form>
        </div>
        <div class="mb-6 p-4 bg-primary/5 rounded-lg border border-primary/20">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Employee Basic Salaries</h3>
                <button type="button" id="toggleBasicSalaryForm" class="btn btn-sm btn-outline-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="mr-1">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Edit Basic Salaries
                </button>
            </div>
            <p class="text-sm text-white-dark mb-3">Set or update each employee's monthly basic salary. These values will
                auto-fill when generating salaries.</p>

            <form action="{{ route('admin.salaries.bulk-update-basic-salary') }}" method="POST" id="basicSalaryForm"
                class="hidden">
                @csrf
                <div class="overflow-x-auto">
                    <table class="table-hover w-full table-auto">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Role</th>
                                <th>Current Basic Salary</th>
                                <th>New Basic Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salaries as $index => $salary)
                                <input type="hidden" name="employees[{{ $index }}][user_id]"
                                    value="{{ $salary['user_id'] }}" />
                                <tr>
                                    <td class="font-semibold">{{ $salary['name'] }}</td>
                                    <td>{{ $salary['designation'] }}</td>
                                    <td>
                                        @php
                                            $user = \App\Models\User::find($salary['user_id']);
                                        @endphp
                                        <span
                                            class="text-white-dark">{{ number_format($user->basic_salary ?? 0, 2) }}</span>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0"
                                            name="employees[{{ $index }}][basic_salary]"
                                            value="{{ $user->basic_salary ?? 0 }}" class="form-input w-32" required />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="btn btn-primary gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Save All Basic Salaries
                    </button>
                </div>
            </form>
        </div>

        <form action="{{ route('admin.salaries.bulk-store') }}" method="POST">
            @csrf
            <input type="hidden" name="month" value="{{ $month }}" />
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
                        <tbody>
                            @forelse($salaries as $index => $salary)
                                <input type="hidden" name="salaries[{{ $index }}][user_id]"
                                    value="{{ $salary['user_id'] ?? '' }}" />
                                <input type="hidden" name="salaries[{{ $index }}][employee_name]"
                                    value="{{ $salary['name'] }}" />
                                <input type="hidden" name="salaries[{{ $index }}][designation]"
                                    value="{{ $salary['designation'] }}" />
                                <tr>
                                    <td>{{ $salary['name'] }}</td>
                                    <td>{{ $salary['designation'] }}</td>
                                    <td>
                                        @if ($salary['id'])
                                            {{ number_format($salary['basic_salary'], 2) }}
                                            <input type="hidden" name="salaries[{{ $index }}][basic_salary]"
                                                value="{{ $salary['basic_salary'] }}" />
                                        @else
                                            <input type="number" step="0.01"
                                                name="salaries[{{ $index }}][basic_salary]"
                                                value="{{ $salary['basic_salary'] }}" class="form-input w-40" required />
                                        @endif
                                    </td>
                                    <td>
                                        @if ($salary['id'])
                                            {{ number_format($salary['bonus'], 2) }}
                                            <input type="hidden" name="salaries[{{ $index }}][bonus]"
                                                value="{{ $salary['bonus'] }}" />
                                        @else
                                            <input type="number" step="0.01"
                                                name="salaries[{{ $index }}][bonus]"
                                                value="{{ $salary['bonus'] }}" class="form-input w-24" />
                                        @endif
                                    </td>
                                    <td>
                                        @if ($salary['id'])
                                            {{ number_format($salary['deduction'], 2) }}
                                            <input type="hidden" name="salaries[{{ $index }}][deduction]"
                                                value="{{ $salary['deduction'] }}" />
                                        @else
                                            <input type="number" step="0.01"
                                                name="salaries[{{ $index }}][deduction]"
                                                value="{{ $salary['deduction'] }}" class="form-input w-24" />
                                        @endif
                                    </td>
                                    <td class="font-bold text-primary">
                                        @if ($salary['id'])
                                            {{ number_format($salary['net_salary'], 2) }}
                                        @else
                                            <span
                                                id="net-{{ $index }}">{{ number_format($salary['net_salary'], 2) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $salary['account_number'] ?? 'N/A' }}</td>
                                    <td>{{ $salary['bank_name'] ?? 'N/A' }}</td>
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
        // Toggle basic salary form visibility
        document.getElementById('toggleBasicSalaryForm').addEventListener('click', function() {
            const form = document.getElementById('basicSalaryForm');
            form.classList.toggle('hidden');
            this.textContent = form.classList.contains('hidden') ? 'Edit Basic Salaries' : 'Hide Basic Salaries';
        });

        // Toggle account details form visibility
        document.getElementById('toggleAccountDetailsForm').addEventListener('click', function() {
            const form = document.getElementById('accountDetailsForm');
            form.classList.toggle('hidden');
            this.textContent = form.classList.contains('hidden') ? 'Edit Account Details' : 'Hide Account Details';
        });

        function calculateNet(index) {
            const basic = parseFloat(document.querySelector(`input[name="salaries[${index}][basic_salary]"]`).value) || 0;
            const bonus = parseFloat(document.querySelector(`input[name="salaries[${index}][bonus]"]`).value) || 0;
            const deduction = parseFloat(document.querySelector(`input[name="salaries[${index}][deduction]"]`).value) || 0;
            const net = basic + bonus - deduction;
            document.getElementById(`net-${index}`).textContent = net.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($salaries as $index => $salary)
                @if (!$salary['id'])
                    document.querySelector(`input[name="salaries[{{ $index }}][basic_salary]"]`)
                        .addEventListener('input', () => calculateNet({{ $index }}));
                    document.querySelector(`input[name="salaries[{{ $index }}][bonus]"]`).addEventListener(
                        'input', () => calculateNet({{ $index }}));
                    document.querySelector(`input[name="salaries[{{ $index }}][deduction]"]`)
                        .addEventListener('input', () => calculateNet({{ $index }}));
                @endif
            @endforeach
        });
    </script>
@endsection
