@extends('admin.layouts.master')

@section('title', 'Office Accounts')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Office Accounts</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.office-accounts.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Account
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.office-accounts.index') }}" method="GET"
                class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
                <div class="relative w-full md:w-80">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search name, provider, number..." class="form-input ltr:pr-11 rtl:pl-11" />
                    <button type="submit"
                        class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5"
                                opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <div class="flex gap-2">
                    <select name="account_type" class="form-select w-full md:w-40 pr-10">
                        <option value="">Type</option>
                        <option value="bank" {{ request('account_type') == 'bank' ? 'selected' : '' }}>Bank</option>
                        <option value="mfs" {{ request('account_type') == 'mfs' ? 'selected' : '' }}>MFS</option>
                        <option value="cash" {{ request('account_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.office-accounts.index') }}" class="btn btn-outline-danger">Reset</a>
                </div>
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            {{-- <th>Type</th> --}}
                            <th>Bank/Provider Name</th>
                            <th>Account Number</th>
                            <th>Branch</th>
                            {{-- <th>Opening Balance</th> --}}
                            {{-- <th>Total Income</th>
                            <th>Total Expense</th> --}}
                            <th>Current Balance</th>
                            {{-- <th>Status</th> --}}
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $account)
                            <tr>
                                <td class="font-semibold">{{ $account->account_name }}</td>
                                {{-- <td><span class="badge badge-outline-primary uppercase">{{ $account->account_type }}</span> --}}
                                </td>
                                <td>{{ $account->provider_name ?? 'N/A' }}</td>
                                <td class="font-mono">{{ $account->account_number }}</td>
                                <td>{{ $account->branch_name ?? 'N/A' }}</td>
                                {{-- <td class="font-semibold">{{ number_format($account->opening_balance ?? 0, 2) }}</td> --}}
                                @php
                                    $totalCredit = (float) ($account->total_income ?? 0);
                                    $totalDebit = (float) ($account->total_expense ?? 0);
                                    $currentBalance = ($account->opening_balance ?? 0) + $totalDebit - $totalCredit;
                                @endphp
                                {{-- <td class="text-success font-semibold">{{ number_format($income, 2) }}</td>
                                <td class="text-danger font-semibold">{{ number_format($expense, 2) }}</td> --}}
                                <td class="font-semibold">{{ number_format($currentBalance, 2) }}</td>
                                {{-- <td>
                                    @if ($account->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td> --}}
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.office-accounts.edit', $account->id) }}"
                                            class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.office-accounts.destroy', $account->id) }}"
                                            method="POST" onsubmit="return confirm('Delete this account?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No office accounts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $accounts->links() }}
            </div>
        </div>
    </div>
@endsection
