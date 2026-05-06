@extends('admin.layouts.master')

@section('title', 'Create Student')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Student</h2>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" id="first_name" class="form-input" required
                        value="{{ old('first_name') }}" />
                    @error('first_name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" id="last_name" class="form-input" required
                        value="{{ old('last_name') }}" />
                    @error('last_name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="father_name">Father's Name</label>
                    <input type="text" name="father_name" id="father_name" class="form-input"
                        value="{{ old('father_name') }}" />
                    @error('father_name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="mother_name">Mother's Name</label>
                    <input type="text" name="mother_name" id="mother_name" class="form-input"
                        value="{{ old('mother_name') }}" />
                    @error('mother_name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone <span class="text-danger">*</span></label>
                    <input type="text" name="phone" id="phone" class="form-input" required value="{{ old('phone') }}" />
                    @error('phone')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="sponsor_phone">Sponsor Phone</label>
                    <input type="text" name="sponsor_phone" id="sponsor_phone" class="form-input" value="{{ old('sponsor_phone') }}" />
                    @error('sponsor_phone')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="passport_number">Passport Number</label>
                    <input type="text" name="passport_number" id="passport_number" class="form-input"
                        value="{{ old('passport_number') }}" />
                    @error('passport_number')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="passport_validity">Passport Validity</label>
                    <input type="date" name="passport_validity" id="passport_validity" class="form-input"
                        value="{{ old('passport_validity') }}" />
                    @error('passport_validity')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" />
                    @error('email')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password <span class="text-danger">*</span></label>
                    <input type="text" name="password" id="password" class="form-input" required />
                    @error('password')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group md:col-span-2">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" class="form-textarea" rows="2">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" name="dob" id="dob" class="form-input" value="{{ old('dob') }}" />
                    @error('dob')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="country_id">Target Country</label>
                    <select name="country_id" id="country_id" class="form-select">
                        <option value="">Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('country_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="university_id">Preferred University</label>
                    <select name="university_id" id="university_id" class="form-select">
                        <option value="">Select University</option>
                        @foreach ($universities as $university)
                            <option value="{{ $university->id }}" data-country-id="{{ $university->country_id }}"
                                {{ old('university_id') == $university->id ? 'selected' : '' }}>
                                {{ $university->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('university_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="course_id">Preferred Course</label>
                    <select name="course_id" id="course_id" class="form-select">
                        <option value="">Select Course</option>
                    </select>
                    @error('course_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="course_intake_id">Preferred Intake</label>
                    <select name="course_intake_id" id="course_intake_id" class="form-select">
                        <option value="">Select Intake</option>
                    </select>
                    @error('course_intake_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div> <!-- <div class="form-group">
                        <label for="ssc_result">SSC Result</label>
                        <input type="text" name="ssc_result" id="ssc_result" class="form-input"
                            value="{{ old('ssc_result') }}" />
                        @error('ssc_result')
                        <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="hsc_result">HSC Result</label>
                        <input type="text" name="hsc_result" id="hsc_result" class="form-input"
                            value="{{ old('hsc_result') }}" />
                        @error('hsc_result')
                        <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="ielts_score">IELTS Score</label>
                        <input type="text" name="ielts_score" id="ielts_score" class="form-input"
                            value="{{ old('ielts_score') }}" />
                        @error('ielts_score')
                        <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div> -->
                {{-- <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-input" value="{{ old('subject') }}" />
                    @error('subject')
                    <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div> --}}
                <!-- <div class="form-group">
                                                                                                            <label for="current_stage">Current Stage <span class="text-danger">*</span></label>
                                                                                                            <select name="current_stage" id="current_stage" class="form-select" required>
                                                                                                                @foreach (['lead', 'counseling', 'payment', 'application', 'offer', 'visa', 'enrolled'] as $stage)
                                                                <option value="{{ $stage }}" {{ old('current_stage', 'lead') == $stage ? 'selected' : '' }}>
                                                                                                                        {{ ucfirst($stage) }}
                                                                                                                    </option>
                                                                @endforeach
                                                                                                            </select>
                                                                                                            @error('current_stage')
                                                                    <span class="text-danger text-sm">{{ $message }}</span>
                                                                @enderror
                                                                                                        </div>
                                                                                                        <div class="form-group">
                                                                                                            <label for="current_status">Application Status <span class="text-danger">*</span></label>
                                                                                                            <select name="current_status" id="current_status" class="form-select" required>
                                                                                                                @foreach (['pending', 'applied', 'rejected', 'withdrawn', 'visa_processing', 'enrolled'] as $status)
                                                                <option value="{{ $status }}" {{ old('current_status') == $status ? 'selected' : '' }}>
                                                                                                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                                                                                    </option>
                                                                @endforeach
                                                                                                            </select>
                                                                                                            @error('current_status')
                                                                    <span class="text-danger text-sm">{{ $message }}</span>
                                                                @enderror
                                                                                                        </div> -->

                <!-- <div class="form-group">
                                                                    <label for="assigned_marketing_id">Assigned Marketing</label>
                                                                    <select name="assigned_marketing_id" id="assigned_marketing_id" class="form-select">
                                                                        <option value="">None</option>
                                                                        @foreach ($users as $user)
                                                                            <option value="{{ $user->id }}" {{ (old('assigned_marketing_id') ?? ($assignedMarketingId ?? null)) == $user->id ? 'selected' : '' }}>
                                                                                {{ $user->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('assigned_marketing_id')
                                                                        <span class="text-danger text-sm">{{ $message }}</span>
                                                                    @enderror
                                                                </div> -->
                {{-- <div class="form-group">
                    <label for="assigned_consultant_id">Assigned Consultant</label>
                    <select name="assigned_consultant_id" id="assigned_consultant_id" class="form-select">
                        <option value="">None</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_consultant_id')==$user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('assigned_consultant_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="assigned_application_id">Assigned Application</label>
                    <select name="assigned_application_id" id="assigned_application_id" class="form-select">
                        <option value="">None</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_application_id')==$user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('assigned_application_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div> --}}

                <div class="form-group md:col-span-2">
                    <label for="documents">Upload Documents</label>
                    <input type="file" name="documents[]" id="documents" class="form-input" multiple />
                    <span class="text-xs text-white-dark">Multiple documents can be uploaded (PDF, DOC, JPG, PNG). Max 10MB
                        per file.</span>
                    @error('documents.*')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group md:col-span-2">
                    <label for="translation_documents">Translation Documents (Optional)</label>
                    <input type="file" name="translation_documents[]" id="translation_documents" class="form-input" multiple />
                    <span class="text-xs text-white-dark">Multiple translation documents can be uploaded (PDF, DOC, JPG, PNG). Max 10MB per file.</span>
                    @error('translation_documents.*')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="reset" class="btn btn-outline-danger">Reset Form</button>
                <button type="submit" class="btn btn-primary px-10">Save Student</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countrySelect = document.getElementById('country_id');
            const universitySelect = document.getElementById('university_id');
            const courseSelect = document.getElementById('course_id');
            const intakeSelect = document.getElementById('course_intake_id');

            // Handle University selection
            universitySelect.addEventListener('change', function() {
                const universityId = this.value;
                const selectedOption = this.options[this.selectedIndex];
                const countryId = selectedOption.getAttribute('data-country-id');

                // Auto-select Country
                if (countryId) {
                    countrySelect.value = countryId;
                }

                // Reset and Load Courses
                courseSelect.innerHTML = '<option value="">Select Course</option>';
                intakeSelect.innerHTML = '<option value="">Select Intake</option>';

                if (universityId) {
                    fetch(`{{ route('admin.applications.get-courses') }}?university_id=${universityId}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            if (data.length === 0) {
                                const option = document.createElement('option');
                                option.value = '';
                                option.textContent = 'No courses available';
                                option.disabled = true;
                                courseSelect.appendChild(option);
                                return;
                            }
                            data.forEach(course => {
                                const option = document.createElement('option');
                                option.value = course.id;
                                option.textContent = course.name;
                                courseSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error loading courses:', error));
                }
            });

            // Handle Course selection
            courseSelect.addEventListener('change', function() {
                const courseId = this.value;
                intakeSelect.innerHTML = '<option value="">Select Intake</option>';

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
                        })
                        .catch(error => console.error('Error loading intakes:', error));
                }
            });

            // Handle Country selection (optional filter for Universities)
            countrySelect.addEventListener('change', function() {
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
                                // Need to keep track of country_id even when filtered
                                option.setAttribute('data-country-id', countryId);
                                universitySelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error loading universities:', error));
                }
            });
        });
    </script>
@endpush