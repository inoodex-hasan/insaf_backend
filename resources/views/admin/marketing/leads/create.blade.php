@extends('admin.layouts.master')

@section('title', 'Data Submit - Marketing')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Data Submit (Primary)</h2>
        <a href="{{ route('admin.marketing.leads.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.marketing.leads.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="student_name">Student Name <span class="text-danger">*</span></label>
                    <input type="text" name="student_name" id="student_name" class="form-input" required
                        value="{{ old('student_name') }}" />
                    @error('student_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone/WhatsApp <span class="text-danger">*</span></label>
                    <input type="text" name="phone" id="phone" class="form-input" required value="{{ old('phone') }}" />
                    @error('phone') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" />
                    @error('email') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="source">Contact Source</label>
                    <select name="source" id="source" class="form-select">
                        <option value="Phone" {{ old('source') == 'Phone' ? 'selected' : '' }}>Phone Call</option>
                        <option value="Message" {{ old('source') == 'Message' ? 'selected' : '' }}>WhatsApp/SMS</option>
                        <option value="Messenger" {{ old('source') == 'Messenger' ? 'selected' : '' }}>FB Messenger</option>
                        <option value="Online Chat" {{ old('source') == 'Online Chat' ? 'selected' : '' }}>Website Chat
                        </option>
                        <option value="Walk-in" {{ old('source') == 'Walk-in' ? 'selected' : '' }}>Walk-in</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="current_education">Current Education</label>
                    <input type="text" name="current_education" id="current_education" class="form-input"
                        value="{{ old('current_education') }}" />
                </div>
                <div class="form-group">
                    <label for="preferred_country">Preferred Country</label>
                    <select name="preferred_country" id="preferred_country" class="form-select">
                        <option value="">Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ old('preferred_country') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="preferred_university">Preferred University</label>
                    <select name="preferred_university" id="preferred_university" class="form-select">
                        <option value="">Select University</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="preferred_course">Preferred Course</label>
                    <select name="preferred_course" id="preferred_course" class="form-select">
                        <option value="">Select Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ old('preferred_course') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const countrySelect = document.getElementById('preferred_country');
                        const universitySelect = document.getElementById('preferred_university');
                        const courseSelect = document.getElementById('preferred_course');

                        countrySelect.addEventListener('change', function () {
                            const countryId = this.value;
                            universitySelect.innerHTML = '<option value="">Loading...</option>';
                            courseSelect.innerHTML = '<option value="">Select Course</option>'; // Reset courses

                            if (countryId) {
                                fetch(`{{ route('admin.marketing.leads.get-universities') }}?country_id=${countryId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        universitySelect.innerHTML = '<option value="">Select University</option>';
                                        data.forEach(university => {
                                            universitySelect.innerHTML += `<option value="${university.id}">${university.name}</option>`;
                                        });
                                    });
                            } else {
                                universitySelect.innerHTML = '<option value="">Select University</option>';
                            }
                        });

                        universitySelect.addEventListener('change', function () {
                            const universityId = this.value;
                            courseSelect.innerHTML = '<option value="">Loading...</option>';

                            if (universityId) {
                                fetch(`{{ route('admin.marketing.leads.get-courses') }}?university_id=${universityId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        courseSelect.innerHTML = '<option value="">Select Course</option>';
                                        data.forEach(course => {
                                            courseSelect.innerHTML += `<option value="${course.id}">${course.name}</option>`;
                                        });
                                    });
                            } else {
                                courseSelect.innerHTML = '<option value="">Select Course</option>';
                            }
                        });
                    });
                </script>
                <div class="form-group">
                    <label for="next_follow_up_at">Next Follow-up Date</label>
                    <input type="date" name="next_follow_up_at" id="next_follow_up_at" class="form-input"
                        value="{{ old('next_follow_up_at') }}" />
                </div>
            </div>

            <div class="form-group mt-5">
                <label for="notes">Notes/Remarks</label>
                <p class="mb-2 text-xs text-white-dark">If you set a follow-up date, this note will be saved in the follow-up history.</p>
                <textarea name="notes" id="notes" class="form-textarea" rows="4">{{ old('notes') }}</textarea>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="reset" class="btn btn-outline-danger">Reset</button>
                <button type="submit" class="btn btn-primary px-10">Save</button>
            </div>
        </form>
    </div>
@endsection
