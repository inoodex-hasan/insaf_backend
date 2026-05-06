@extends('admin.layouts.master')

@section('title', 'Create Application')

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
    <div>
        <h2 class="text-xl font-semibold uppercase">Create New Application</h2>

        <div class="panel mt-6">
            <form action="{{ route('admin.applications.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-group">
                        <label for="student_id">Select Student</label>
                        <select name="student_id" id="student_id" class="form-select" required>
                            <option value="">Select Student</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" {{ (old('student_id') == $student->id || $selected_student == $student->id) ? 'selected' : '' }}>
                                    {{ $student->first_name }} {{ $student->last_name }} - ({{ $student->phone }})
                                </option>
                            @endforeach
                        </select>
                        @error('student_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="country_id">Select Country</label>
                        <select name="country_id" id="country_id" class="form-select">
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="university_id">Select University</label>
                        <select name="university_id" id="university_id" class="form-select" >
                            <option value="">Select University</option>
                            @foreach ($universities as $university)
                                <option value="{{ $university->id }}" {{ old('university_id') == $university->id ? 'selected' : '' }}>
                                    {{ $university->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('university_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="course_id">Select Course</label>
                        <select name="course_id" id="course_id" class="form-select" >
                            <option value="">Select Course</option>
                        </select>
                        @error('course_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="course_intake_id">Select Intake</label>
                        <select name="course_intake_id" id="course_intake_id" class="form-select" >
                            <option value="">Select Intake</option>
                        </select>
                        @error('course_intake_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="form-group">
                            <label for="tuition_fee">Tuition Fee</label>
                            <input type="number" name="tuition_fee" id="tuition_fee" class="form-input"
                                value="{{ old('tuition_fee') }}" required>
                            @error('tuition_fee') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div> -->

                    <!-- <div class="form-group">
                            <label for="total_fee">Total Fee <span class="text-danger">*</span></label>
                            <input type="number" name="total_fee" id="total_fee" step="0.01" class="form-input" value="{{ old('total_fee', 0) }}" required>
                            @error('total_fee') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div> -->

                    <div class="form-group">
                        <label for="status">Application Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            @foreach (['pending', 'ready_for_apply', 'applied', 'under_review', 'offer_issued', 'conditional_offer', 'unconditional_offer', 'rejected', 'withdrawn', 'visa_processing', 'enrolled'] as $status)
                                <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes" class="form-input" rows="3"
                            placeholder="Additional information...">{{ old('notes') }}</textarea>
                        @error('notes') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-danger">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Application</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/nice-select2.js') }}"></script>
<!-- <script>
document.addEventListener('DOMContentLoaded', function () {

    const studentSelect = document.getElementById('student_id');
    const countrySelect = document.getElementById('country_id');
    const universitySelect = document.getElementById('university_id');
    const courseSelect = document.getElementById('course_id');
    const intakeSelect = document.getElementById('course_intake_id');
    const tuitionFeeInput = document.getElementById('tuition_fee');

    // Safety check
    if (!studentSelect || !countrySelect || !universitySelect || !courseSelect || !intakeSelect) {
        console.error('Missing required select elements');
        return;
    }

    // NiceSelect init (safe)
    if (typeof NiceSelect !== 'undefined') {
        NiceSelect.bind(studentSelect, {
            searchable: true,
            placeholder: 'Select Student (Search by Name, Phone or Email)'
        });
    }

    // ----------------------
    // SAFE FETCH
    // ----------------------
    function safeFetch(url) {
        return fetch(url, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(res => {
            if (!res.ok) throw new Error('HTTP Error ' + res.status);
            return res.json();
        });
    }

    function clearDropdown(select, placeholder) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
    }

    function toggleFields(disabled) {
        [countrySelect, universitySelect, courseSelect, intakeSelect].forEach(el => {
            el.disabled = disabled;
        });
    }

    // =====================
    // STUDENT CHANGE
    // =====================
    studentSelect.addEventListener('change', function () {

        const studentId = this.value;

        toggleFields(false);

        if (!studentId) {
            countrySelect.value = '';
            clearDropdown(universitySelect, 'Select University');
            clearDropdown(courseSelect, 'Select Course');
            clearDropdown(intakeSelect, 'Select Intake');
            tuitionFeeInput.value = '';
            return;
        }

        safeFetch(`{{ route('admin.applications.get-student-details') }}?student_id=${studentId}`)
            .then(data => {

                if (!data.country_id) return;

                countrySelect.value = data.country_id;

                return safeFetch(`{{ route('admin.applications.get-universities') }}?country_id=${data.country_id}`)
                    .then(universities => {

                        clearDropdown(universitySelect, 'Select University');

                        universities.forEach(u => {
                            const option = document.createElement('option');
                            option.value = u.id;
                            option.textContent = u.name;
                            universitySelect.appendChild(option);
                        });

                        if (!data.university_id) return;

                        universitySelect.value = data.university_id;

                        return safeFetch(`{{ route('admin.applications.get-courses') }}?university_id=${data.university_id}`)
                            .then(courses => {

                                clearDropdown(courseSelect, 'Select Course');

                                courses.forEach(c => {
                                    const option = document.createElement('option');
                                    option.value = c.id;
                                    option.textContent = c.name;

                                    // SAFE tuition fee handling
                                    option.dataset.tuitionFee = c.tuition_fee ?? '';

                                    courseSelect.appendChild(option);
                                });

                                if (!data.course_id) return;

                                courseSelect.value = data.course_id;

                                // trigger intake load
                                courseSelect.dispatchEvent(new Event('change'));

                                return safeFetch(`{{ route('admin.applications.get-intakes') }}?course_id=${data.course_id}`)
                                    .then(intakes => {

                                        clearDropdown(intakeSelect, 'Select Intake');

                                        intakes.forEach(i => {
                                            const option = document.createElement('option');
                                            option.value = i.id;
                                            option.textContent = i.intake_name;
                                            intakeSelect.appendChild(option);
                                        });

                                        if (data.course_intake_id) {
                                            intakeSelect.value = data.course_intake_id;
                                        }

                                        toggleFields(true);
                                    });
                            });
                    });
            })
            .catch(err => console.error('Student load error:', err));
    });

    // =====================
    // COUNTRY CHANGE
    // =====================
    countrySelect.addEventListener('change', function () {

        const countryId = this.value;

        clearDropdown(universitySelect, 'Select University');
        clearDropdown(courseSelect, 'Select Course');
        clearDropdown(intakeSelect, 'Select Intake');
        tuitionFeeInput.value = '';

        if (!countryId) return;

        safeFetch(`{{ route('admin.applications.get-universities') }}?country_id=${countryId}`)
            .then(data => {

                data.forEach(u => {
                    const option = document.createElement('option');
                    option.value = u.id;
                    option.textContent = u.name;
                    universitySelect.appendChild(option);
                });

            })
            .catch(err => console.error('University load error:', err));
    });

    // =====================
    // UNIVERSITY CHANGE
    // =====================
    universitySelect.addEventListener('change', function () {

        const universityId = this.value;

        clearDropdown(courseSelect, 'Select Course');
        clearDropdown(intakeSelect, 'Select Intake');
        tuitionFeeInput.value = '';

        if (!universityId) return;

        safeFetch(`{{ route('admin.applications.get-courses') }}?university_id=${universityId}`)
            .then(data => {

                data.forEach(c => {
                    const option = document.createElement('option');
                    option.value = c.id;
                    option.textContent = c.name;
                    option.dataset.tuitionFee = c.tuition_fee ?? '';
                    courseSelect.appendChild(option);
                });

            })
            .catch(err => console.error('Course load error:', err));
    });

    // =====================
    // COURSE CHANGE
    // =====================
    courseSelect.addEventListener('change', function () {

        const courseId = this.value;

        clearDropdown(intakeSelect, 'Select Intake');

        const selected = this.options[this.selectedIndex];

const fee = selected?.dataset?.tuitionFee;

tuitionFeeInput.value =
    fee === undefined || fee === null || fee === "null"
        ? '0'
        : fee;

        if (!courseId) return;

        safeFetch(`{{ route('admin.applications.get-intakes') }}?course_id=${courseId}`)
            .then(data => {

                data.forEach(i => {
                    const option = document.createElement('option');
                    option.value = i.id;
                    option.textContent = i.intake_name;
                    intakeSelect.appendChild(option);
                });

            })
            .catch(err => console.error('Intake load error:', err));
    });

    // =====================
    // AUTO TRIGGER
    // =====================
    if (studentSelect.value) {
        studentSelect.dispatchEvent(new Event('change'));
    }

});
</script> -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const studentSelect = document.getElementById('student_id');

            // Initialize NiceSelect2
            const niceSelect = NiceSelect.bind(studentSelect, {
                searchable: true,
                placeholder: 'Select Student (Search by Name, Phone or Email)'
            });

            const countrySelect = document.getElementById('country_id');
            const universitySelect = document.getElementById('university_id');
            const courseSelect = document.getElementById('course_id');
            const intakeSelect = document.getElementById('course_intake_id');
            const tuitionFeeInput = document.getElementById('tuition_fee');
            const bdtAmountInput = document.getElementById('bdt_amount');
            // const totalFeeInput = document.getElementById('total_fee');

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

            studentSelect.addEventListener('change', function () {
                const studentId = this.value;

                // Re-enable to allow clearing and fetching
                toggleAcademicFields(false);

                if (studentId) {
                    fetch(`{{ route('admin.applications.get-student-details') }}?student_id=${studentId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.country_id) {
                                countrySelect.value = data.country_id;

                                // Load universities then set university
                                fetch(`{{ route('admin.applications.get-universities') }}?country_id=${data.country_id}`)
                                    .then(response => response.json())
                                    .then(universities => {
                                        universitySelect.innerHTML = '<option value="">Select University</option>';
                                        universities.forEach(university => {
                                            const option = document.createElement('option');
                                            option.value = university.id;
                                            option.textContent = university.name;
                                            universitySelect.appendChild(option);
                                        });

                                        if (data.university_id) {
                                            universitySelect.value = data.university_id;

                                            // Load courses
                                            fetch(`{{ route('admin.applications.get-courses') }}?university_id=${data.university_id}`)
                                                .then(response => response.json())
                                                .then(courses => {
                                                    courseSelect.innerHTML = '<option value="">Select Course</option>';
                                                    courses.forEach(course => {
                                                        const option = document.createElement('option');
                                                        option.value = course.id;
                                                        option.textContent = course.name;
                                                        option.dataset.tuitionFee = course.tuition_fee;
                                                        // option.dataset.currency = course.currency;
                                                        option.dataset.exchangeRate = course.exchange_rate;
                                                        courseSelect.appendChild(option);
                                                    });

                                                    if (data.course_id) {
                                                        courseSelect.value = data.course_id;
                                                        const selectedOption = courseSelect.options[courseSelect.selectedIndex];
                                                        // if (selectedOption && selectedOption.dataset.tuitionFee) {
                                                        //     // tuitionFeeInput.value = selectedOption.dataset.tuitionFee;
                                                        //     // totalFeeInput.value = selectedOption.dataset.tuitionFee;
                                                        // }

                                                        // Load intakes
                                                        fetch(`{{ route('admin.applications.get-intakes') }}?course_id=${data.course_id}`)
                                                            .then(response => response.json())
                                                            .then(intakes => {
                                                                intakeSelect.innerHTML = '<option value="">Select Intake</option>';
                                                                intakes.forEach(intake => {
                                                                    const option = document.createElement('option');
                                                                    option.value = intake.id;
                                                                    option.textContent = intake.intake_name;
                                                                    intakeSelect.appendChild(option);
                                                                });

                                                                if (data.course_intake_id) {
                                                                    intakeSelect.value = data.course_intake_id;
                                                                }

                                                                // After all fetched and set, make them read-only
                                                                toggleAcademicFields(true);
                                                            });
                                                    } else {
                                                        toggleAcademicFields(true);
                                                    }
                                                });
                                        } else {
                                            toggleAcademicFields(true);
                                        }
                                    });
                            } else {
                                // If student has no country_id, just clear and leave enabled or disable?
                                // The user said "after select student... make them read only"
                                // If no data, maybe stay enabled to pick manually? Or disable as empty?
                                // Let's keep them enabled if no data is found so they can pick.
                            }
                        });
                } else {
                    // No student selected, clear all and enable
                    countrySelect.value = '';
                    universitySelect.innerHTML = '<option value="">Select University</option>';
                    courseSelect.innerHTML = '<option value="">Select Course</option>';
                    intakeSelect.innerHTML = '<option value="">Select Intake</option>';
                    toggleAcademicFields(false);
                }
            });

            countrySelect.addEventListener('change', function () {
                const countryId = this.value;
                universitySelect.innerHTML = '<option value="">Select University</option>';
                courseSelect.innerHTML = '<option value="">Select Course</option>';
                intakeSelect.innerHTML = '<option value="">Select Intake</option>';

                if (countryId) {
                    fetch(`{{ route('admin.applications.get-universities') }}?country_id=${countryId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(university => {
                                const option = document.createElement('option');
                                option.value = university.id;
                                option.textContent = university.name;
                                universitySelect.appendChild(option);
                            });
                        });
                }
            });

            universitySelect.addEventListener('change', function () {
                const universityId = this.value;
                courseSelect.innerHTML = '<option value="">Select Course</option>';
                intakeSelect.innerHTML = '<option value="">Select Intake</option>';

                if (universityId) {
                    fetch(`{{ route('admin.applications.get-courses') }}?university_id=${universityId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(course => {
                                const option = document.createElement('option');
                                option.value = course.id;
                                option.textContent = course.name;
                                option.dataset.tuitionFee = course.tuition_fee;
                                // option.dataset.currency = course.currency;
                                option.dataset.exchangeRate = course.exchange_rate;
                                courseSelect.appendChild(option);
                            });
                        });
                }
            });

            courseSelect.addEventListener('change', function () {
                const courseId = this.value;
                intakeSelect.innerHTML = '<option value="">Select Intake</option>';
                tuitionFeeInput.value = '';

                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.dataset.tuitionFee) {
                    tuitionFeeInput.value = selectedOption.dataset.tuitionFee;
                    totalFeeInput.value = selectedOption.dataset.tuitionFee;
                }

                if (courseId) {
                    fetch(`{{ route('admin.applications.get-intakes') }}?course_id=${courseId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(intake => {
                                const option = document.createElement('option');
                                option.value = intake.id;
                                option.textContent = intake.intake_name;
                                intakeSelect.appendChild(option);
                            });
                        });
                }
            });

            // Trigger auto-fill if student is already selected (e.g., from old input)
            if (studentSelect.value) {
                const event = new Event('change');
                studentSelect.dispatchEvent(event);
            }
        });
    </script>
@endpush