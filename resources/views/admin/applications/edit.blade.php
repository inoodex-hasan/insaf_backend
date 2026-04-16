@extends('admin.layouts.master')

@section('title', 'Edit Application')

@section('content')
    @php
        $canEdit = auth()->user()->hasRole('consultant');
        $canEditStatus = auth()->user()->hasRole('application');
    @endphp
    <div>
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold uppercase">Edit Application: {{ $application->application_id }}</h2>
        </div>

        <div class="panel mt-6">
            <form action="{{ route('admin.applications.update', $application->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-group">
                        <label for="student_id">Student</label>
                        <select name="student_id" id="student_id" class="form-select bg-gray-100 dark:bg-black/20" disabled>
                            <option value="{{ $application->student_id }}">
                                {{ $application->student->first_name }} {{ $application->student->last_name }}
                            </option>
                        </select>
                        <input type="hidden" name="student_id" value="{{ $application->student_id }}">
                        <span class="text-xs text-white-dark mt-1">Student cannot be changed once application is
                            created.</span>
                    </div>

                    <div class="form-group">
                        <label for="country_id">Country</label>
                        <select name="country_id" id="country_id" class="form-select bg-gray-100 dark:bg-black/20" disabled>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $application->university->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="hidden" name="country_id"
                            value="{{ old('country_id', $application->university->country_id ?? '') }}">
                    </div>



                    <div class="form-group">
                        <label for="university_id">Select University</label>
                        <select name="university_id" id="university_id" class="form-select bg-gray-100 dark:bg-black/20"
                            disabled>
                            @foreach ($universities as $university)
                                <option value="{{ $university->id }}" {{ old('university_id', $application->university_id) == $university->id ? 'selected' : '' }}>
                                    {{ $university->name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="hidden" name="university_id"
                            value="{{ old('university_id', $application->university_id) }}">
                    </div>



                    <div class="form-group">
                        <label for="course_id">Select Course</label>
                        <select name="course_id" id="course_id" class="form-select bg-gray-100 dark:bg-black/20" disabled>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" data-tuition-fee="{{ $course->tuition_fee }}" {{ old('course_id', $application->course_id) == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="hidden" name="course_id" value="{{ old('course_id', $application->course_id) }}">
                    </div>





                    <div class="form-group">
                        <label for="course_intake_id">Select Intake</label>
                        <select name="course_intake_id" id="course_intake_id"
                            class="form-select bg-gray-100 dark:bg-black/20" disabled>
                            @foreach ($intakes as $intake)
                                <option value="{{ $intake->id }}" {{ old('course_intake_id', $application->course_intake_id) == $intake->id ? 'selected' : '' }}>
                                    {{ $intake->intake_name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="hidden" name="course_intake_id"
                            value="{{ old('course_intake_id', $application->course_intake_id) }}">
                    </div>





                    <!-- <div class="grid grid-cols-1 gap-4 sm:grid-cols-1">
                                                                    <div class="form-group">
                                                                        <label for="tuition_fee">Tuition Fee</label>
                                                                        <input type="number" name="tuition_fee" id="tuition_fee"
                                                                            class="form-input {{ !$canEdit ? 'bg-gray-100 dark:bg-black/20' : '' }}"
                                                                            value="{{ old('tuition_fee', $application->tuition_fee) }}" {{ !$canEdit ? 'disabled' : '' }} required>
                                                                        @if (!$canEdit)
                                                                            <input type="hidden" name="tuition_fee"
                                                                                value="{{ old('tuition_fee', $application->tuition_fee) }}">
                                                                        @endif
                                                                        @error('tuition_fee')
                                                                            <span class="text-danger text-sm">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div> -->

                    <!-- <div class="form-group">
                                                                        <label for="total_fee">Total Fee <span class="text-danger">*</span></label>
                                                                        <input type="number" name="total_fee" id="total_fee" step="0.01"
                                                                            class="form-input {{ !$canEdit ? 'bg-gray-100 dark:bg-black/20' : '' }}"
                                                                            value="{{ old('total_fee', $application->total_fee) }}" {{ !$canEdit ? 'disabled' : '' }}
                                                                            required>
                                                                        @if (!$canEdit)
                                                                            <input type="hidden" name="total_fee" value="{{ old('total_fee', $application->total_fee) }}">
                                                                        @endif
                                                                        @error('total_fee')
                                                                            <span class="text-danger text-sm">{{ $message }}</span>
                                                                        @enderror
                                                                    </div> -->

                    <div class="form-group">
                        <label for="status">Application Status</label>
                        <select name="status" id="status"
                            class="form-select {{ !($canEdit || $canEditStatus) ? 'bg-gray-100 dark:bg-black/20' : '' }}" {{ !($canEdit || $canEditStatus) ? 'disabled' : '' }} required>
                            @foreach (['pending', 'ready_for_apply', 'applied', 'under_review', 'offer_issued', 'conditional_offer', 'unconditional_offer', 'rejected', 'withdrawn', 'visa_processing', 'enrolled'] as $status)
                                <option value="{{ $status }}" {{ old('status', $application->status) == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes"
                            class="form-input {{ !$canEdit ? 'bg-gray-100 dark:bg-black/20' : '' }}" rows="3"
                            placeholder="Additional information..." {{ !$canEdit ? 'disabled' : '' }}>{{ old('notes', $application->notes) }}</textarea>
                        @if (!$canEdit)
                            <input type="hidden" name="notes" value="{{ old('notes', $application->notes) }}">
                        @endif
                        @error('notes')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Application Tracking Section --}}
                <div class="mt-8">
                    <h5 class="text-lg font-semibold dark:text-white-light uppercase mb-4">Application Tracking</h5>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        {{-- Checkboxes in 1 line --}}
                        <div class="form-group md:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="hidden" name="offer_letter_received" value="0">
                                        <input type="checkbox" name="offer_letter_received" value="1"
                                            id="offer_letter_received" class="form-checkbox w-5 h-5 text-primary rounded" {{ old('offer_letter_received', $application->offer_letter_received) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium">Offer Letter Received</span>
                                    </label>
                                    <input type="date" name="offer_letter_received_date" id="offer_letter_received_date"
                                        class="form-input mt-2 {{ !$canEdit ? 'bg-gray-100 dark:bg-black/20' : '' }}"
                                        value="{{ old('offer_letter_received_date', $application->offer_letter_received_date?->format('Y-m-d')) }}"
                                        {{ !$canEdit ? 'disabled' : '' }}>
                                </div>

                                <div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="hidden" name="vfs_appointment" value="0">
                                        <input type="checkbox" name="vfs_appointment" value="1" id="vfs_appointment"
                                            class="form-checkbox w-5 h-5 text-primary rounded" {{ old('vfs_appointment', $application->vfs_appointment) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium">VFS Appointment</span>
                                    </label>
                                    <input type="date" name="vfs_appointment_date" id="vfs_appointment_date"
                                        class="form-input mt-2 {{ !$canEdit ? 'bg-gray-100 dark:bg-black/20' : '' }}"
                                        value="{{ old('vfs_appointment_date', $application->vfs_appointment_date?->format('Y-m-d')) }}"
                                        {{ !$canEdit ? 'disabled' : '' }}>
                                </div>

                                <div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="hidden" name="file_submission" value="0">
                                        <input type="checkbox" name="file_submission" value="1" id="file_submission"
                                            class="form-checkbox w-5 h-5 text-primary rounded" {{ old('file_submission', $application->file_submission) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium">File Submission</span>
                                    </label>
                                    <input type="date" name="file_submission_date" id="file_submission_date"
                                        class="form-input mt-2 {{ !$canEdit ? 'bg-gray-100 dark:bg-black/20' : '' }}"
                                        value="{{ old('file_submission_date', $application->file_submission_date?->format('Y-m-d')) }}"
                                        {{ !$canEdit ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>

                        {{-- Visa Status --}}
                        <div class="form-group">
                            <label for="visa_status">Visa Status</label>
                            <select name="visa_status" id="visa_status"
                                class="form-select {{ !$canEdit ? 'bg-gray-100 dark:bg-black/20' : '' }}" {{ !$canEdit ? 'disabled' : '' }}>
                                @foreach (['not_applied', 'pending', 'approved', 'rejected'] as $status)
                                    <option value="{{ $status }}" {{ old('visa_status', $application->visa_status) == $status ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Visa Decision Date --}}
                        <div class="form-group">
                            <label for="visa_decision_date">Visa Decision Date</label>
                            <input type="date" name="visa_decision_date" id="visa_decision_date"
                                class="form-input {{ !$canEdit ? 'bg-gray-100 dark:bg-black/20' : '' }}"
                                value="{{ old('visa_decision_date', $application->visa_decision_date?->format('Y-m-d')) }}"
                                {{ !$canEdit ? 'disabled' : '' }}>
                        </div>

                        {{-- Tuition Fee Status --}}
                        <div class="form-group">
                            <label for="tuition_fee_status">Tuition Fee Status</label>
                            <select name="tuition_fee_status" id="tuition_fee_status"
                                class="form-select {{ !$canEdit ? 'bg-gray-100 dark:bg-black/20' : '' }}" {{ !$canEdit ? 'disabled' : '' }}>
                                @foreach (['pending', 'paid', 'partial'] as $status)
                                    <option value="{{ $status }}" {{ old('tuition_fee_status', $application->tuition_fee_status) == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Service Charge Status --}}
                        <div class="form-group">
                            <label for="service_charge_status">Service Charge Status</label>
                            <select name="service_charge_status" id="service_charge_status"
                                class="form-select {{ !$canEdit ? 'bg-gray-100 dark:bg-black/20' : '' }}" {{ !$canEdit ? 'disabled' : '' }}>
                                @foreach (['pending', 'paid', 'partial'] as $status)
                                    <option value="{{ $status }}" {{ old('service_charge_status', $application->service_charge_status) == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Application Priority --}}
                        <div class="form-group">
                            <label for="application_priority">Application Priority</label>
                            <select name="application_priority" id="application_priority"
                                class="form-select {{ !$canEdit ? 'bg-gray-100 dark:bg-black/20' : '' }}" {{ !$canEdit ? 'disabled' : '' }}>
                                @foreach (['normal', 'priority', 'vip'] as $priority)
                                    <option value="{{ $priority }}" {{ old('application_priority', $application->application_priority) == $priority ? 'selected' : '' }}>
                                        {{ ucfirst($priority) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Final Status --}}
                        <div class="form-group">
                            <label for="final_status">Final Status</label>
                            <select name="final_status" id="final_status"
                                class="form-select {{ !$canEdit ? 'bg-gray-100 dark:bg-black/20' : '' }}" {{ !$canEdit ? 'disabled' : '' }}>
                                @foreach (['pending', 'in_progress', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" {{ old('final_status', $application->final_status) == $status ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Payment Status Checkboxes --}}
                        <div class="form-group md:col-span-2">
                            <h6 class="text-md font-semibold dark:text-white-light mb-3">Payment Tracking</h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="hidden" name="security_deposit_status" value="0">
                                    <input type="checkbox" name="security_deposit_status" value="1"
                                        id="security_deposit_status" class="form-checkbox w-5 h-5 text-primary rounded" {{ old('security_deposit_status', $application->security_deposit_status) ? 'checked' : '' }}>
                                    <span class="text-sm font-medium">Security Deposit</span>
                                </label>

                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="hidden" name="cvu_fee_status" value="0">
                                    <input type="checkbox" name="cvu_fee_status" value="1" id="cvu_fee_status"
                                        class="form-checkbox w-5 h-5 text-primary rounded" {{ old('cvu_fee_status', $application->cvu_fee_status) ? 'checked' : '' }}>
                                    <span class="text-sm font-medium">CVU Fee</span>
                                </label>

                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="hidden" name="admission_fee_status" value="0">
                                    <input type="checkbox" name="admission_fee_status" value="1" id="admission_fee_status"
                                        class="form-checkbox w-5 h-5 text-primary rounded" {{ old('admission_fee_status', $application->admission_fee_status) ? 'checked' : '' }}>
                                    <span class="text-sm font-medium">Admission Fee</span>
                                </label>

                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="hidden" name="final_payment_status" value="0">
                                    <input type="checkbox" name="final_payment_status" value="1" id="final_payment_status"
                                        class="form-checkbox w-5 h-5 text-primary rounded" {{ old('final_payment_status', $application->final_payment_status) ? 'checked' : '' }}>
                                    <span class="text-sm font-medium">Final Payment</span>
                                </label>

                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="hidden" name="emgs_payment_status" value="0">
                                    <input type="checkbox" name="emgs_payment_status" value="1" id="emgs_payment_status"
                                        class="form-checkbox w-5 h-5 text-primary rounded" {{ old('emgs_payment_status', $application->emgs_payment_status) ? 'checked' : '' }}>
                                    <span class="text-sm font-medium">EMGS Payment</span>
                                </label>
                            </div>
                        </div>

                        {{-- Internal Notes --}}
                        <div class="form-group md:col-span-2">
                            <label for="internal_notes">Internal Notes</label>
                            <textarea name="internal_notes" id="internal_notes"
                                class="form-input {{ !$canEdit ? 'bg-gray-100 dark:bg-black/20' : '' }}" rows="3"
                                placeholder="Staff-only notes..." {{ !$canEdit ? 'disabled' : '' }}>{{ old('internal_notes', $application->internal_notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-danger">Cancel</a>
                    @if ($canEdit || $canEditStatus)
                        <button type="submit" class="btn btn-primary">Update Application</button>
                    @endif
                </div>
            </form>
            <div class="panel mt-6">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="text-lg font-semibold dark:text-white-light uppercase">Payment History</h5>
                    <!-- <a href="{{ route('admin.payments.create', ['application_id' => $application->id]) }}" class="btn btn-primary btn-sm">Add Payment</a> -->
                </div>
                <div class="table-responsive">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th>Receipt No</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Collected By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($application->payments as $payment)
                                <tr>
                                    <td>{{ $payment->receipt_number }}</td>
                                    <td>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : '-' }}</td>
                                    <td class="capitalize">{{ $payment->payment_type }}</td>
                                    <td>BDT {{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $payment->payment_status === 'completed' ? 'badge-outline-success' : 'badge-outline-warning' }}">
                                            {{ $payment->payment_status }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->collector->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No payment history found for this application.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Commission Section --}}
            @php
                $totalPaid = $application->payments->where('payment_status', 'completed')->sum('amount');
                $existingCommission = $application->commissions->first();
            @endphp
            <div class="panel mt-6">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="text-lg font-semibold dark:text-white-light uppercase">Commission</h5>
                    @if($existingCommission)
                        <span class="badge {{ $existingCommission->status === 'paid' ? 'badge-outline-success' : 'badge-outline-warning' }}">
                            {{ ucfirst($existingCommission->status) }}
                        </span>
                    @endif
                </div>

                <form action="{{ route('admin.commissions.store', $application) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="form-group">
                            <label for="user_id">Commission To</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">Select Employee</option>
                                @foreach(\App\Models\User::orderBy('name')->get() as $user)
                                    <option value="{{ $user->id }}" {{ $existingCommission && $existingCommission->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="percentage">Commission Percentage (%)</label>
                            <input type="number" name="percentage" id="percentage" step="0.01" min="0" max="100"
                                class="form-input" value="{{ $existingCommission ? $existingCommission->percentage : 0 }}" required />
                        </div>

                        <div class="form-group">
                            <label>Calculated Amount</label>
                            <input type="text" id="calculated_amount" class="form-input bg-gray-100 dark:bg-black/20"
                                value="BDT {{ number_format($totalPaid, 2) }}" readonly />
                            <span class="text-xs text-white-dark mt-1">Based on total paid: BDT {{ number_format($totalPaid, 2) }}</span>
                        </div>

                        <div class="form-group md:col-span-3">
                            <label for="commission_notes">Notes</label>
                            <textarea name="notes" id="commission_notes" class="form-input" rows="2"
                                placeholder="Optional notes...">{{ $existingCommission ? $existingCommission->notes : '' }}</textarea>
                        </div>
                    </div>

                    @if($existingCommission)
                        <div class="mt-4 flex items-center justify-between">
                            <div class="text-sm">
                                <span class="font-semibold">Commission Amount:</span>
                                BDT {{ number_format($existingCommission->amount, 2) }}
                                <span class="ml-2">({{ $existingCommission->percentage }}% of BDT {{ number_format($totalPaid, 2) }})</span>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" onclick="updateCommissionStatus({{ $existingCommission->id }})"
                                    class="btn btn-sm {{ $existingCommission->status === 'pending' ? 'btn-success' : 'btn-warning' }}">
                                    Mark as {{ $existingCommission->status === 'pending' ? 'Paid' : 'Pending' }}
                                </button>
                                <a href="{{ route('admin.commissions.index') }}" class="btn btn-sm btn-outline-info">View All</a>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary">
                            {{ $existingCommission ? 'Update Commission' : 'Set Commission' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
@endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Auto-fill dates when checkboxes are ticked
                function setupAutoDate(checkboxId, dateInputId) {
                    const checkbox = document.getElementById(checkboxId);
                    const dateInput = document.getElementById(dateInputId);

                    if (checkbox && dateInput) {
                        checkbox.addEventListener('change', function () {
                            if (this.checked) {
                                const today = new Date();
                                const yyyy = today.getFullYear();
                                const mm = String(today.getMonth() + 1).padStart(2, '0');
                                const dd = String(today.getDate()).padStart(2, '0');
                                dateInput.value = `${yyyy}-${mm}-${dd}`;
                            } else {
                                dateInput.value = '';
                            }
                        });
                    }
                }

                setupAutoDate('offer_letter_received', 'offer_letter_received_date');
                setupAutoDate('vfs_appointment', 'vfs_appointment_date');
                setupAutoDate('file_submission', 'file_submission_date');

                // Auto-fill visa decision date when status changes to approved/rejected
                const visaStatusSelect = document.getElementById('visa_status');
                const visaDecisionDateInput = document.getElementById('visa_decision_date');

                if (visaStatusSelect && visaDecisionDateInput) {
                    visaStatusSelect.addEventListener('change', function () {
                        if (this.value === 'approved' || this.value === 'rejected') {
                            const today = new Date();
                            const yyyy = today.getFullYear();
                            const mm = String(today.getMonth() + 1).padStart(2, '0');
                            const dd = String(today.getDate()).padStart(2, '0');
                            visaDecisionDateInput.value = `${yyyy}-${mm}-${dd}`;
                        } else {
                            visaDecisionDateInput.value = '';
                        }
                    });
                }

                const countrySelect = document.getElementById('country_id');
                const universitySelect = document.getElementById('university_id');
                const courseSelect = document.getElementById('course_id');
                const intakeSelect = document.getElementById('course_intake_id');
                const tuitionFeeInput = document.getElementById('tuition_fee');
                const currencyInput = document.getElementById('currency');
                const bdtAmountInput = document.getElementById('bdt_amount');
                const totalFeeInput = document.getElementById('total_fee');

                function toggleAcademicFields(disabled) {
                    const fields = [countrySelect, universitySelect, courseSelect, intakeSelect];
                    fields.forEach(select => {
                        select.disabled = disabled;
                        // Manage hidden inputs for disabled fields to ensure submission
                        let hiddenInput = document.getElementById('hidden_' + select.name);
                        if (disabled) {
                            if (!hiddenInput) {
                                hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = select.name;
                                hiddenInput.id = 'hidden_' + select.name;
                                select.after(hiddenInput);
                            }
                            hiddenInput.value = select.value;
                        } else {
                            if (hiddenInput) hiddenInput.remove();
                        }
                    });
                }

                function calculateBDT() {
                    // No longer needed
                }

                // countrySelect.addEventListener('change', function () {
                //     const countryId = this.value;
                //     universitySelect.innerHTML = '<option value="">Select University</option>';
                //     courseSelect.innerHTML = '<option value="">Select Course</option>';
                //     intakeSelect.innerHTML = '<option value="">Select Intake</option>';

                //     if (countryId) {
                //         fetch(`{{ route('admin.applications.get-universities') }}?country_id=${countryId}`)
                //             .then(response => response.json())
                //             .then(data => {
                //                 data.forEach(university => {
                //                     const option = document.createElement('option');
                //                     option.value = university.id;
                //                     option.textContent = university.name;
                //                     universitySelect.appendChild(option);
                //                 });
                //             });
                //     }
                // });

                // universitySelect.addEventListener('change', function () {
                //     const universityId = this.value;
                //     courseSelect.innerHTML = '<option value="">Select Course</option>';
                //     intakeSelect.innerHTML = '<option value="">Select Intake</option>';

                //     if (universityId) {
                //         fetch(`{{ route('admin.applications.get-courses') }}?university_id=${universityId}`)
                //             .then(response => response.json())
                //             .then(data => {
                //                 data.forEach(course => {
                //                     const option = document.createElement('option');
                //                     option.value = course.id;
                //                     option.textContent = course.name;
                //                     option.dataset.tuitionFee = course.tuition_fee;
                //                     courseSelect.appendChild(option);
                //                 });
                //             });
                //     }
                // });

                // courseSelect.addEventListener('change', function () {
                //     const courseId = this.value;
                //     intakeSelect.innerHTML = '<option value="">Select Intake</option>';
                //     tuitionFeeInput.value = '';
                //     currencyInput.value = '';

                //     const selectedOption = this.options[this.selectedIndex];
                //     if (selectedOption && selectedOption.dataset.tuitionFee) {
                //         tuitionFeeInput.value = selectedOption.dataset.tuitionFee;
                //         totalFeeInput.value = selectedOption.dataset.tuitionFee;
                //     }

                //     if (courseId) {
                //         fetch(`{{ route('admin.applications.get-intakes') }}?course_id=${courseId}`)
                //             .then(response => response.json())
                //             .then(data => {
                //                 data.forEach(intake => {
                //                     const option = document.createElement('option');
                //                     option.value = intake.id;
                //                     option.textContent = intake.intake_name;
                //                     intakeSelect.appendChild(option);
                //                 });
                //             });
                //     }
                // });

                // Initial calculation
                // calculateBDT();  // Removed

                // Commission percentage to amount calculator
                const percentageInput = document.getElementById('percentage');
                const calculatedAmountInput = document.getElementById('calculated_amount');
                const totalPaidValue = {{ $totalPaid }};

                if (percentageInput) {
                    percentageInput.addEventListener('input', function() {
                        const pct = parseFloat(this.value) || 0;
                        const amount = (totalPaidValue * pct) / 100;
                        calculatedAmountInput.value = `BDT ${amount.toFixed(2)}`;
                    });
                }
            });

            function updateCommissionStatus(commissionId) {
                const currentStatus = '{{ $existingCommission ? $existingCommission->status : 'pending' }}';
                const newStatus = currentStatus === 'pending' ? 'paid' : 'pending';

                fetch(`{{ url('dashboard/commissions') }}/${commissionId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
            }
        </script>
    @endpush