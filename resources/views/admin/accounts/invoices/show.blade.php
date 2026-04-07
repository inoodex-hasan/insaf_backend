@extends('admin.layouts.master')

@section('title', 'Invoice: ' . $invoice->invoice_number)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 print:hidden">
        <h2 class="text-xl font-semibold uppercase">Invoice Billing Details</h2>
        <div class="flex gap-2 text-xs">
            <button onclick="window.print()" class="btn btn-outline-primary gap-2 font-black uppercase">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 17h2a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2m2 4h6a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2zM17 9V5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v4" />
                </svg>
                Print Voucher
            </button>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary font-black uppercase">Back to List</a>
        </div>
    </div>

    <!-- Premium Invoice Voucher -->
    <div class="panel mt-6 print:border-0 print:shadow-none print:m-0 print:p-0">
        <!-- Logo & Header -->
        <div class="flex flex-col md:flex-row justify-between mb-10 pb-8 border-b-4 border-primary">
            <div class="flex items-center gap-5">
                <div class="p-4 bg-primary text-white rounded-2xl font-black text-4xl uppercase tracking-tighter shadow-lg">INS</div>
                <div>
                    <h4 class="text-3xl font-black uppercase text-primary tracking-tighter">Insaf Education</h4>
                    <p class="text-xs text-white-dark font-black tracking-[4px] uppercase mt-1">Consultancy & Agency</p>
                </div>
            </div>
            <div class="text-right mt-6 md:mt-0 font-bold uppercase text-[9px] tracking-widest text-white-dark flex flex-col justify-end leading-relaxed">
                <p>Road 12, Block B, Nikunja-2, Khilkhet</p>
                <p>Dhaka-1229, Bangladesh</p>
                <p class="text-primary font-black">Email: accounts@insaf.edu.bd</p>
                <p>Phone: +880 1234 567890</p>
            </div>
        </div>

        <!-- Invoice Info Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-10 pb-8 border-b border-white-light dark:border-white-light/10">
            <div class="space-y-6">
                <div>
                    <h5 class="text-xs font-black text-white-dark uppercase tracking-[6px] mb-3">Invoice Bill To:</h5>
                    <h4 class="text-2xl font-black text-dark dark:text-white-light">{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</h4>
                    <p class="text-sm font-mono font-bold text-primary bg-primary/5 inline-block px-3 py-1 rounded mt-2">ID: {{ $invoice->student->id_number }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] text-white-dark font-black uppercase tracking-widest mb-1">Target Institution:</p>
                        <p class="text-sm font-black text-primary">{{ $invoice->university->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-white-dark font-black uppercase tracking-widest mb-1">Destination:</p>
                        <p class="text-xs font-bold">{{ $invoice->university->location ?? 'Global Branch' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-primary/5 p-8 rounded-3xl flex flex-col justify-center items-end relative overflow-hidden">
                <div class="absolute top-0 right-0 p-2 opacity-10">
                    <svg width="120" height="120" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 19h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2zM19 19h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2zM9 19h6a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z" />
                    </svg>
                </div>
                <div class="text-right z-10 w-full">
                    <p class="text-xs font-black text-primary uppercase tracking-[8px] mb-2 opacity-50">Invoice No.</p>
                    <h3 class="text-3xl font-black text-primary tracking-tighter border-b-2 border-primary/20 pb-2 inline-block">{{ $invoice->invoice_number }}</h3>
                </div>
                <div class="grid grid-cols-2 gap-8 mt-8 w-full text-right z-10">
                    <div>
                        <p class="text-[10px] font-black text-white-dark uppercase tracking-widest mb-1">Issue Date</p>
                        <p class="text-sm font-black">{{ $invoice->date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-white-dark uppercase tracking-widest mb-1">Due Date</p>
                        <p class="text-sm font-black text-danger underline decoration-wavy">{{ $invoice->due_date->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Transaction Table -->
        <div class="table-responsive">
            <table class="w-full">
                <thead>
                    <tr class="bg-primary text-white font-black uppercase text-[10px] tracking-widest">
                        <th class="p-5 text-left border-r border-white/10 rounded-tl-xl">Service Particulars</th>
                        <th class="p-5 text-right border-r border-white/10 w-24">Qty</th>
                        <th class="p-5 text-right border-r border-white/10 w-48">Unit Rate</th>
                        <th class="p-5 text-right rounded-tr-xl w-48">Net Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                        <tr class="border-b-2 border-primary/5 hover:bg-primary/5 transition-all">
                            <td class="p-5">
                                <span class="font-black text-sm uppercase text-primary block mb-1">{{ $item->chartOfAccount->name }}</span>
                                <span class="text-xs text-white-dark font-medium leading-relaxed">{{ $item->description }}</span>
                            </td>
                            <td class="p-5 text-right font-black text-dark dark:text-white-light">{{ number_format($item->quantity, 0) }}</td>
                            <td class="p-5 text-right font-mono font-bold text-white-dark">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="p-5 text-right font-black font-mono text-primary text-lg">{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-primary pt-1 shadow-2xl">
                        <td colspan="3" class="p-6 text-right uppercase text-xs font-black tracking-[10px] text-white border-r border-white/20 rounded-bl-3xl">Grand Total Due:</td>
                        <td class="p-6 text-right text-white font-black text-3xl font-mono rounded-br-3xl">
                            <span class="text-xs font-bold text-white/50 mr-3">{{ $invoice->currency->code }}</span>
                            {{ number_format($invoice->total_amount, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer Notes & Signatures -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-16 pb-12">
            <div>
                @if($invoice->notes)
                    <div class="p-6 bg-primary/5 border-l-8 border-primary rounded-r-3xl h-full shadow-inner">
                        <span class="text-[10px] font-black uppercase tracking-[5px] text-primary block mb-3 opacity-60">Payment Advice & Terms:</span>
                        <p class="text-sm font-semibold text-white-dark leading-relaxed italic">"{{ $invoice->notes }}"</p>
                        <div class="mt-6 pt-4 border-t border-primary/10">
                            <p class="text-[9px] text-white-dark font-bold">Please quote invoice number during payment transfer.</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex flex-col justify-between pt-10">
                <div class="grid grid-cols-2 gap-12 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-full h-px bg-white-dark/30 mb-2"></div>
                        <p class="font-black uppercase text-[9px] tracking-widest text-primary">Student Signature</p>
                        <p class="text-[7px] text-white-dark mt-1">Authorized acceptance</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-full h-px bg-primary mb-2 shadow-[0_0_10px_rgba(67,97,238,0.5)]"></div>
                        <p class="font-black uppercase text-[9px] tracking-widest text-primary">Accounts Manager</p>
                        <p class="text-[7px] text-white-dark mt-1">For Insaf Education</p>
                    </div>
                </div>
                
                <div class="mt-12 text-right">
                    <p class="text-[8px] font-black text-white-dark tracking-[8px] uppercase opacity-40">This is a system generated document</p>
                </div>
            </div>
        </div>

        <!-- Decorative Footer -->
        <div class="w-full h-3 bg-gradient-to-r from-primary via-secondary to-primary rounded-full mb-4"></div>
    </div>
@endsection
