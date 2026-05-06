@extends('admin.layouts.master')

@section('title', 'Edit Student')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Student</h2>
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
        <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" id="first_name" class="form-input" required
                        value="{{ old('first_name', $student->first_name) }}" />
                    @error('first_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" id="last_name" class="form-input" required
                        value="{{ old('last_name', $student->last_name) }}" />
                    @error('last_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="father_name">Father's Name</label>
                    <input type="text" name="father_name" id="father_name" class="form-input"
                        value="{{ old('father_name', $student->father_name) }}" />
                    @error('father_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="mother_name">Mother's Name</label>
                    <input type="text" name="mother_name" id="mother_name" class="form-input"
                        value="{{ old('mother_name', $student->mother_name) }}" />
                    @error('mother_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone <span class="text-danger">*</span></label>
                    <input type="text" name="phone" id="phone" class="form-input" required
                        value="{{ old('phone', $student->phone) }}" />
                    @error('phone') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="sponsor_phone">Sponsor Phone</label>
                    <input type="text" name="sponsor_phone" id="sponsor_phone" class="form-input"
                        value="{{ old('sponsor_phone', $student->sponsor_phone) }}" />
                    @error('sponsor_phone') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="passport_number">Passport Number</label>
                    <input type="text" name="passport_number" id="passport_number" class="form-input"
                        value="{{ old('passport_number', $student->passport_number) }}" />
                    @error('passport_number') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="passport_validity">Passport Validity</label>
                    <input type="date" name="passport_validity" id="passport_validity" class="form-input"
                        value="{{ old('passport_validity', optional($student->passport_validity)->format('Y-m-d')) }}" />
                    @error('passport_validity') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-input"
                        value="{{ old('email', $student->email) }}" />
                    @error('email') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="text" name="password" id="password" class="form-input" placeholder="Leave blank to keep current" />
                    @error('password') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group md:col-span-2">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" class="form-textarea" rows="2">{{ old('address', $student->address) }}</textarea>
                    @error('address') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" name="dob" id="dob" class="form-input"
                        value="{{ old('dob', optional($student->dob)->format('Y-m-d')) }}" />
                    @error('dob') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="country_id">Target Country</label>
                    <select name="country_id" id="country_id" class="form-select">
                        <option value="">Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id', $student->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('country_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
 
                <div class="form-group">
                    <label for="university_id">Preferred University</label>
                    <select name="university_id" id="university_id" class="form-select">
                        <option value="">Select University</option>
                        @foreach ($universities as $university)
                            <option value="{{ $university->id }}" data-country-id="{{ $university->country_id }}"
                                {{ old('university_id', $student->university_id) == $university->id ? 'selected' : '' }}>
                                {{ $university->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('university_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="course_id">Preferred Course</label>
                    <select name="course_id" id="course_id" class="form-select">
                        <option value="">Select Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}"
                                {{ old('course_id', $student->course_id) == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="course_intake_id">Preferred Intake</label>
                    <select name="course_intake_id" id="course_intake_id" class="form-select">
                        <option value="">Select Intake</option>
                        @foreach ($intakes as $intake)
                            <option value="{{ $intake->id }}"
                                {{ old('course_intake_id', $student->course_intake_id) == $intake->id ? 'selected' : '' }}>
                                {{ $intake->intake_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_intake_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <!-- <div class="form-group">
                    <label for="ssc_result">SSC Result</label>
                    <input type="text" name="ssc_result" id="ssc_result" class="form-input"
                        value="{{ old('ssc_result', $student->ssc_result) }}" />
                    @error('ssc_result') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="hsc_result">HSC Result</label>
                    <input type="text" name="hsc_result" id="hsc_result" class="form-input"
                        value="{{ old('hsc_result', $student->hsc_result) }}" />
                    @error('hsc_result') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="ielts_score">IELTS Score</label>
                    <input type="text" name="ielts_score" id="ielts_score" class="form-input"
                        value="{{ old('ielts_score', $student->ielts_score) }}" />
                    @error('ielts_score') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div> -->
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-input"
                        value="{{ old('subject', $student->subject) }}" />
                    @error('subject') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <!-- <div class="form-group">
                    <label for="current_stage">Current Stage <span class="text-danger">*</span></label>
                    <select name="current_stage" id="current_stage" class="form-select" required>
                        @foreach (['lead', 'counseling', 'payment', 'application', 'offer', 'visa', 'enrolled'] as $stage)
                            <option value="{{ $stage }}"
                                {{ old('current_stage', $student->current_stage) == $stage ? 'selected' : '' }}>
                                {{ ucfirst($stage) }}
                            </option>
                        @endforeach
                    </select>
                    @error('current_stage') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="current_status">Application Status <span class="text-danger">*</span></label>
                    <select name="current_status" id="current_status" class="form-select" required>
                        @foreach (['pending', 'applied', 'rejected', 'withdrawn', 'visa_processing', 'enrolled'] as $status)
                            <option value="{{ $status }}" {{ old('current_status', $student->current_status) == $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('current_status') <span class="text-danger text-sm">{{ $message }}</span> @enderror  
                </div> -->

                <!-- <div class="form-group">
                    <label for="assigned_marketing_id">Assigned Marketing</label>
                    <select name="assigned_marketing_id" id="assigned_marketing_id" class="form-select">
                        <option value="">None</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('assigned_marketing_id', $student->assigned_marketing_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_marketing_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="assigned_consultant_id">Assigned Consultant</label>
                    <select name="assigned_consultant_id" id="assigned_consultant_id" class="form-select">
                        <option value="">None</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('assigned_consultant_id', $student->assigned_consultant_id) == $user->id ? 'selected' : '' }}>
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
                            <option value="{{ $user->id }}"
                                {{ old('assigned_application_id', $student->assigned_application_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_application_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
 -->
                @if($student->documents && count($student->documents) > 0)
                    <div class="form-group md:col-span-2 mt-4">
                        <label>Existing Documents</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($student->documents as $index => $doc)
                                <div class="flex items-center gap-3 p-3 border rounded-lg bg-gray-50 dark:bg-black/20">
                                    <div class="flex-1 overflow-hidden">
                                        <p class="text-sm font-medium truncate" title="{{ $doc['name'] }}">
                                            {{ $doc['name'] }}
                                        </p>
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($doc['path']) }}" target="_blank" class="text-xs text-primary hover:underline">Download</a>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" name="delete_documents[]" value="{{ $index }}" id="del_{{ $index }}" class="form-checkbox text-danger" />
                                        <label for="del_{{ $index }}" class="text-xs text-danger cursor-pointer">Delete</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="form-group md:col-span-2">
                    <label for="documents">Upload New Documents</label>
                    <input type="file" name="documents[]" id="documents" class="form-input" multiple />
                    <span class="text-xs text-white-dark">Multiple documents can be uploaded (PDF, DOC, JPG, PNG). Max 10MB per file.</span>
                    @error('documents.*') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                @if($student->translation_documents && count($student->translation_documents) > 0)
                    <div class="form-group md:col-span-2 mt-4">
                        <label>Existing Translation Documents</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($student->translation_documents as $index => $doc)
                                <div class="flex items-center gap-3 p-3 border rounded-lg bg-gray-50 dark:bg-black/20">
                                    <div class="flex-1 overflow-hidden">
                                        <p class="text-sm font-medium truncate" title="{{ $doc['name'] }}">
                                            {{ $doc['name'] }}
                                        </p>
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($doc['path']) }}" target="_blank" class="text-xs text-secondary hover:underline">Download</a>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" name="delete_translation_documents[]" value="{{ $index }}" id="del_t_{{ $index }}" class="form-checkbox text-danger" />
                                        <label for="del_t_{{ $index }}" class="text-xs text-danger cursor-pointer">Delete</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="form-group md:col-span-2">
                    <label for="translation_documents">Upload New Translation Documents</label>
                    <input type="file" name="translation_documents[]" id="translation_documents" class="form-input" multiple />
                    <span class="text-xs text-white-dark">Multiple translation documents can be uploaded (PDF, DOC, JPG, PNG). Max 10MB per file.</span>
                    @error('translation_documents.*') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="button" onclick="window.location.href='{{ route('admin.students.index') }}'"
                    class="btn btn-outline-danger">Cancel</button>
                <button type="submit" class="btn btn-primary px-10">Update</button>
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
                const countryId = selectedOption ? selectedOption.getAttribute('data-country-id') : null;

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
