@extends('admin.layouts.master')

@section('title', 'Student Invoices')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Billing & Invoices</h2>
        <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary gap-2 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Create New Invoice
        </a>
    </div>

    <div class="panel mt-6">
        <div class="table-responsive">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Issue Date</th>
                        <th>Invoice Number</th>
                        <th>Student Name</th>
                        <th>University</th>
                        <th>Total Amount</th>
                        <th>Billing Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td class="whitespace-nowrap text-xs text-white-dark">{{ $invoice->date->format('M d, Y') }}</td>
                            <td class="font-bold text-primary">
                                <a href="{{ route('admin.invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a>
                            </td>
                            <td class="font-semibold text-sm">
                                {{ $invoice->student->first_name }} {{ $invoice->student->last_name }}
                                <span class="block text-[10px] text-white-dark">{{ $invoice->student->id_number }}</span>
                            </td>
                            <td class="text-xs">{{ $invoice->university->name ?? 'N/A' }}</td>
                            <td class="font-black text-dark dark:text-white-light font-mono">{{ number_format($invoice->total_amount, 2) }}</td>
                            <td>
                                @if($invoice->status == 'paid')
                                    <span class="badge badge-outline-success uppercase text-[10px] font-black">Fully Paid</span>
                                @elseif($invoice->status == 'partial')
                                    <span class="badge badge-outline-warning uppercase text-[10px] font-black">Partial Paid</span>
                                @else
                                    <span class="badge badge-outline-danger uppercase text-[10px] font-black">Outstanding</span>
                                @endif
                                <span class="block text-[10px] mt-1 text-white-dark uppercase">Due: {{ $invoice->due_date->format('M d') }}</span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="p-2 bg-info/10 text-info rounded-full hover:bg-info hover:text-white transition-all" title="View Detail">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5" />
                                            <path d="M20.188 10.9348C19.3312 10.0474 16.0333 7 12 7C7.96667 7 4.66885 10.0474 3.81204 10.9348C3.56532 11.1908 3.44196 11.3188 3.44196 11.5C3.44196 11.6812 3.56532 11.8092 3.81204 12.0652C4.66885 12.9526 7.96667 16 12 16C16.0333 16 19.3312 12.9526 20.188 12.0652C20.4347 11.8092 20.558 11.6812 20.558 11.5C20.558 11.3188 20.4347 11.1908 20.188 10.9348Z" stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Deleting an invoice will affect student balances. Continue?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-danger/10 text-danger rounded-full hover:bg-danger hover:text-white transition-all" title="Delete">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18 6L17.1991 18.0129C17.129 19.065 17.0939 19.5911 16.8667 19.99C16.6666 20.3412 16.3648 20.6235 16.0011 20.7998C15.588 21 15.0607 21 14.0062 21H9.99377C8.93927 21 8.41202 21 7.99889 20.7998C7.63517 20.6235 7.33339 20.3412 7.13332 19.99C6.90607 19.5911 6.871 19.065 6.80086 18.0129L6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-400 py-16">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 bg-primary/5 rounded-full mb-3">
                                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.3">
                                            <path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold tracking-widest uppercase">No Invoices Found</p>
                                    <p class="text-xs text-white-dark mt-1">Start by billing a student or university.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $invoices->links() }}
        </div>
    </div>
@endsection
