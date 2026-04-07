@extends('admin.layouts.master')

@section('title', 'Voucher: ' . $entry->reference_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 print:hidden">
        <h2 class="text-xl font-semibold uppercase">Voucher Details</h2>
        <div class="flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-primary gap-2">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polyline points="6 9 6 2 18 2 18 9" />
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                    <rect x="6" y="14" width="12" height="8" />
                </svg>
                Print Voucher
            </button>
            <a href="{{ route('admin.journal-entries.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <div class="panel mt-6 print:border-0 print:shadow-none print:m-0">
        <!-- Voucher Header -->
        <div class="flex flex-col md:flex-row justify-between border-b-2 border-primary/20 pb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-primary text-white rounded font-bold text-xl uppercase tracking-widest">JV</div>
                    <h4 class="text-2xl font-black text-primary">{{ $entry->reference_number }}</h4>
                </div>
                <p class="text-white-dark uppercase text-xs font-bold tracking-tighter">Journal Voucher | Posted Status</p>
            </div>
            <div class="mt-4 md:mt-0 text-right flex flex-col gap-1">
                <p class="text-sm"><span class="font-bold uppercase text-white-dark text-[10px] mr-2">Voucher Date:</span> {{ $entry->date->format('M d, Y') }}</p>
                <p class="text-sm"><span class="font-bold uppercase text-white-dark text-[10px] mr-2">Accounting Period:</span> <span class="badge badge-outline-secondary">{{ $entry->period->name }}</span></p>
                <p class="text-sm"><span class="font-bold uppercase text-white-dark text-[10px] mr-2">Base Currency:</span> <span class="font-mono">{{ $entry->currency->code }}</span></p>
            </div>
        </div>

        <!-- Voucher Body -->
        <div class="mt-8">
            <div class="table-responsive">
                <table class="table-hover">
                    <thead class="bg-primary/5">
                        <tr>
                            <th class="p-4 text-xs font-bold uppercase w-32">Account Code</th>
                            <th class="p-4 text-xs font-bold uppercase">Ledger Head Name</th>
                            <th class="p-4 text-xs font-bold uppercase">Transaction Description</th>
                            <th class="p-4 text-xs font-bold uppercase text-right w-40">Debit</th>
                            <th class="p-4 text-xs font-bold uppercase text-right w-40">Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entry->items as $item)
                            <tr class="border-b dark:border-white-light/10">
                                <td class="p-4 font-mono text-xs">{{ $item->chartOfAccount->code }}</td>
                                <td class="p-4 font-semibold">{{ $item->chartOfAccount->name }}</td>
                                <td class="p-4 italic text-white-dark text-xs">{{ $item->description }}</td>
                                <td class="p-4 text-right font-mono font-bold text-success">{{ $item->debit > 0 ? number_format($item->debit, 2) : '-' }}</td>
                                <td class="p-4 text-right font-mono font-bold text-danger">{{ $item->credit > 0 ? number_format($item->credit, 2) : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-primary/5">
                        <tr class="font-black text-lg border-t-4 border-primary/10">
                            <td colspan="3" class="p-4 text-right uppercase text-xs">Voucher Totals:</td>
                            <td class="p-4 text-right text-primary font-mono">{{ number_format($entry->items->sum('debit'), 2) }}</td>
                            <td class="p-4 text-right text-primary font-mono">{{ number_format($entry->items->sum('credit'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Voucher Narration -->
        @if($entry->note)
            <div class="mt-8 p-6 bg-primary/5 border-l-4 border-primary rounded-r-lg">
                <div class="uppercase text-[10px] font-bold text-primary mb-2 tracking-widest">Voucher Narration / Note:</div>
                <div class="text-sm italic leading-relaxed text-white-dark">{{ $entry->note }}</div>
            </div>
        @endif

        <!-- Voucher Signatures -->
        <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-10 text-center">
            <div class="border-t border-dotted border-white-dark pt-3 flex flex-col">
                <span class="font-bold text-sm">Prepared By</span>
                <span class="text-[10px] text-white-dark mt-1">{{ $entry->creator->name }}</span>
            </div>
            <div class="border-t border-dotted border-white-dark pt-3 flex flex-col">
                <span class="font-bold text-sm">Reviewer</span>
                <span class="text-[10px] text-white-dark mt-1 italic">Internal Audit</span>
            </div>
            <div class="border-t border-dotted border-white-dark pt-3 flex flex-col">
                <span class="font-bold text-sm">Accounts Head</span>
                <span class="text-[10px] text-white-dark mt-1 opacity-20">_______________________</span>
            </div>
            <div class="border-t border-dotted border-white-dark pt-3 flex flex-col">
                <span class="font-bold text-sm">Approved By</span>
                <span class="text-[10px] text-white-dark mt-1 opacity-20">_______________________</span>
            </div>
        </div>
    </div>
@endsection
