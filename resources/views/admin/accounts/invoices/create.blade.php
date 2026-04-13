@extends('admin.layouts.master')

@section('title', 'Create Student Invoice')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select2.css') }}">
    <style>
        .nice-select {
            width: 100%;
            height: 42px !important;
            display: flex !important;
            align-items: center !important;
        }

        .nice-select .current {
            line-height: normal !important;
            display: flex !important;
            align-items: center !important;
            height: 100% !important;
        }

        .nice-select .list {
            width: 100%;
        }

        /* Fix double arrow issue */
        .nice-select {
            background-image: none !important;
        }

        .form-select {
            background-image: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Generate Student Invoice</h2>
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <form action="{{ route('admin.invoices.store') }}" method="POST" x-data="invoiceForm()"
        @submit.prevent="submitInvoice">
        @csrf

        <div class="panel mt-6">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="form-group">
                    <label for="application_search">Search Application <span class="text-danger">*</span></label>
                    <select name="application_search" id="application_search" class="form-select"
                        x-model="selectedApplication" @change="onApplicationSelect" required>
                        <option value="">Select Application</option>
                        @foreach ($applications as $app)
                            <option value="{{ $app->id }}">{{ $app->application_id }} - {{ $app->student->first_name }}
                                {{ $app->student->last_name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="application_id" :value="selectedApplication" />
                    <span class="text-xs text-white-dark mt-1">Invoice will be generated for the selected application</span>
                </div>

                <div class="form-group">
                    <label for="student_id">Student</label>
                    <select name="student_id" id="student_id" class="form-select bg-gray-100 dark:bg-black/20"
                        x-model="selectedStudentId" disabled>
                        <option value="">Select Application First</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}
                                ({{ $student->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="invoice_number">Invoice # (Auto-generated if empty)</label>
                    <input type="text" name="invoice_number" id="invoice_number" class="form-input"
                        placeholder="INV-{{ date('Y') }}-XXXX" />
                </div>
            </div>

            {{-- Auto-populated Application Details --}}
            <div x-show="applicationDetails" class="mt-5 p-4 bg-primary/5 rounded-lg border border-primary/20" x-cloak>
                <h6 class="text-sm font-bold text-primary uppercase mb-3">Application Details</h6>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                    <div><span class="text-white-dark">University:</span> <span class="font-semibold"
                            x-text="applicationDetails?.university || '-'"></span></div>
                    <div><span class="text-white-dark">Course:</span> <span class="font-semibold"
                            x-text="applicationDetails?.course || '-'"></span></div>
                    <div><span class="text-white-dark">Intake:</span> <span class="font-semibold"
                            x-text="applicationDetails?.intake || '-'"></span></div>
                    <div><span class="text-white-dark">Application ID:</span> <span class="font-semibold text-primary"
                            x-text="applicationDetails?.application_id || '-'"></span></div>
                    {{-- <div><span class="text-white-dark">Tuition Fee:</span> <span class="font-semibold"
                            x-text="applicationDetails?.tuition_fee || '-'"></span></div>
                    <div><span class="text-white-dark">Service Charge:</span> <span class="font-semibold"
                            x-text="applicationDetails?.service_charge || '-'"></span></div> --}}
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 mt-5">
                <div class="form-group">
                    <label for="date">Issue Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" id="date" class="form-input" required
                        value="{{ date('Y-m-d') }}" />
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date <span class="text-danger">*</span></label>
                    <input type="date" name="due_date" id="due_date" class="form-input" required
                        value="{{ date('Y-m-d', strtotime('+7 days')) }}" />
                </div>
            </div>

            <div class="mt-5">
                <label for="notes">Internal Notes</label>
                <textarea name="notes" id="notes" class="form-textarea" rows="2"
                    placeholder="Public notes appearing on invoice..."></textarea>
            </div>
        </div>

        @if ($errors->any())
            <div class="mt-4 p-4 border border-danger bg-danger/5 text-danger rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($errors->has('msg'))
            <div
                class="mt-4 p-4 border border-danger bg-danger/5 text-danger rounded flex items-center gap-3 animate-shake">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="font-bold">{{ $errors->first('msg') }}</span>
            </div>
        @endif

        <!-- Dynamic Invoice Items -->
        <div class="panel mt-6">
            <h5 class="mb-5 text-lg font-black uppercase text-primary tracking-widest border-b pb-3">Line Item Details</h5>

            <div class="table-responsive">
                <table class="w-full">
                    <thead>
                        <tr class="bg-primary/5">
                            <th class="p-3 text-left w-1/4 uppercase text-[10px] tracking-widest">Revenue Head</th>
                            <th class="p-3 text-left uppercase text-[10px] tracking-widest">Service Description</th>
                            <th class="p-3 text-right w-24 uppercase text-[10px] tracking-widest">Qty</th>
                            <th class="p-3 text-right w-32 uppercase text-[10px] tracking-widest">Amount</th>
                            <th class="p-3 text-right w-40 uppercase text-[10px] tracking-widest text-primary">Subtotal
                            </th>
                            <th class="p-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="border-b transition-all hover:bg-white-light/10">
                                <td class="p-2">
                                    <select :name="`items[${index}][chart_of_account_id]`"
                                        class="form-select text-xs font-bold" x-model="item.chart_of_account_id" required>
                                        <option value="">Select Ledger</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->code }} -
                                                {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-2">
                                    <input type="text" :name="`items[${index}][description]`"
                                        class="form-input text-xs" x-model="item.description"
                                        placeholder="Brief details about service..." required />
                                </td>
                                <td class="p-2">
                                    <input type="number" :name="`items[${index}][quantity]`" step="1"
                                        class="form-input text-right text-xs" x-model.number="item.quantity"
                                        @input="calculateTotals" required min="1" />
                                </td>
                                <td class="p-2">
                                    <input type="number" :name="`items[${index}][unit_price]`" step="0.01"
                                        class="form-input text-right text-xs font-mono font-bold"
                                        x-model.number="item.unit_price" @input="calculateTotals" required />
                                </td>
                                <td class="p-2 text-right font-black font-mono text-primary"
                                    x-text="formatCurrency(item.unit_price * item.quantity)"></td>
                                <td class="p-2 text-center text-danger">
                                    <button type="button" @click="removeItem(index)"
                                        class="hover:text-danger/70 transition-all" x-show="items.length > 1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10" opacity="0.2" />
                                            <path d="M15 9l-6 6M9 9l6 6" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="bg-primary/5">
                        <tr class="font-black text-2xl border-t-4 border-primary">
                            <td colspan="4"
                                class="p-5 text-right uppercase text-[10px] tracking-[4px] text-white-dark">
                                Grand Invoice Total:</td>
                            <td class="p-5 text-right text-primary font-mono tracking-tighter"
                                x-text="formatCurrency(grandTotal)"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-8 flex flex-wrap justify-between items-center gap-4">
                <button type="button" @click="addItem"
                    class="btn btn-outline-primary flex items-center gap-2 text-xs font-bold uppercase transition-transform hover:scale-105 active:scale-95">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="3">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    New Item
                </button>

                <div class="flex gap-4">
                    <button type="reset" @click="window.location.reload()"
                        class="btn btn-outline-danger uppercase text-[10px] font-bold">Reset</button>
                    <button type="submit"
                        class="btn btn-primary px-16 uppercase text-xs font-black shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]">
                        Print Invoice
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/nice-select2.js') }}"></script>
    <script>
        function invoiceForm() {
            return {
                items: [{
                    chart_of_account_id: '',
                    description: '',
                    quantity: 1,
                    unit_price: 0
                }],
                grandTotal: 0,
                selectedApplication: '{{ $selectedApplication ? $selectedApplication->id : '' }}',
                selectedStudentId: '{{ $selectedApplication ? $selectedApplication->student_id : '' }}',
                applicationDetails: {!! json_encode(
                    $selectedApplication
                        ? [
                            'application_id' => $selectedApplication->application_id,
                            'university' => $selectedApplication->university->name ?? '',
                            'course' => $selectedApplication->course->name ?? '',
                            'intake' => $selectedApplication->intake->intake_name ?? '',
                            'tuition_fee' => number_format($selectedApplication->tuition_fee ?? 0, 2, '.', ''),
                            'service_charge' => number_format($selectedApplication->service_charge ?? 0, 2, '.', ''),
                            'id' => $selectedApplication->id,
                        ]
                        : null,
                ) !!},

                init() {
                    this.initNiceSelect();
                    if (this.selectedApplication) {
                        this.onApplicationSelect();
                    }
                },

                initNiceSelect() {
                    const app = this;
                    setTimeout(() => {
                        const el = document.getElementById('application_search');
                        if (el) {
                            NiceSelect.bind(el, {
                                searchable: true,
                                placeholder: 'Search by Application ID or Student Name...'
                            });
                        }
                    }, 100);
                },

                onApplicationSelect() {
                    if (!this.selectedApplication) {
                        this.applicationDetails = null;
                        return;
                    }

                    fetch(`/dashboard/applications/${this.selectedApplication}/invoice-data`)
                        .then(res => res.json())
                        .then(data => {
                            this.applicationDetails = {
                                application_id: data.application_id,
                                university: data.university,
                                course: data.course,
                                intake: data.intake,
                                tuition_fee: data.tuition_fee,
                                service_charge: data.service_charge,
                                id: data.id
                            };
                            this.selectedStudentId = data.student_id;

                            // Auto-populate invoice items
                            this.items = [];
                            if (parseFloat(data.tuition_fee) > 0) {
                                this.items.push({
                                    chart_of_account_id: '',
                                    description: `Tuition Fee - ${data.course} (${data.university})`,
                                    quantity: 1,
                                    unit_price: parseFloat(data.tuition_fee)
                                });
                            }
                            if (parseFloat(data.service_charge) > 0) {
                                this.items.push({
                                    chart_of_account_id: '',
                                    description: `Service Charge - ${data.application_id}`,
                                    quantity: 1,
                                    unit_price: parseFloat(data.service_charge)
                                });
                            }
                            if (this.items.length === 0) {
                                this.items.push({
                                    chart_of_account_id: '',
                                    description: '',
                                    quantity: 1,
                                    unit_price: 0
                                });
                            }
                            this.calculateTotals();
                        });
                },

                addItem() {
                    this.items.push({
                        chart_of_account_id: '',
                        description: '',
                        quantity: 1,
                        unit_price: 0
                    });
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                    this.calculateTotals();
                },

                calculateTotals() {
                    this.grandTotal = this.items.reduce((sum, item) => sum + (parseFloat(item.quantity) * parseFloat(item
                        .unit_price) || 0), 0);
                },

                formatCurrency(val) {
                    return new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(val);
                },

                submitInvoice() {
                    this.calculateTotals();
                    if (this.grandTotal <= 0) {
                        return;
                    }
                    document.querySelector('form').submit();
                }
            }
        }
    </script>
@endpush
