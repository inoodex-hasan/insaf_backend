@extends('admin.layouts.master')

@section('title', 'Student Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Student Details</h2>
        <div class="flex gap-2">
            {{-- <a href="{{ route('admin.students.pdf', $student->id) }}" class="btn btn-danger gap-2" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="12" y1="18" x2="12" y2="12"></line>
                    <line x1="9" y1="15" x2="15" y2="15"></line>
                </svg>
                Download PDF
            </a> --}}
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to List
            </a>
            @can('*consultant')
                <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Edit Student
                </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Personal Information -->
        <div class="lg:col-span-2 space-y-6">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light text-primary uppercase">Personal Information</h5>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-white-dark mb-1">Full Name</label>
                        <p class="font-semibold">{{ $student->first_name }} {{ $student->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Father's Name</label>
                        <p class="font-semibold">{{ $student->father_name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Mother's Name</label>
                        <p class="font-semibold">{{ $student->mother_name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Email</label>
                        <p class="font-semibold">{{ $student->email ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Passport Number</label>
                        <p class="font-semibold">{{ $student->passport_number ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Passport Validity</label>
                        <p class="font-semibold">
                            {{ $student->passport_validity ? \Carbon\Carbon::parse($student->passport_validity)->format('M d, Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Phone</label>
                        <p class="font-semibold">{{ $student->phone }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Sponsor Phone</label>
                        <p class="font-semibold">{{ $student->sponsor_phone ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Date of Birth</label>
                        <p class="font-semibold">
                            {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('M d, Y') : '-' }}
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-white-dark mb-1">Address</label>
                        <p class="font-semibold whitespace-pre-line">{{ $student->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light text-primary uppercase">Preferred Academic
                        Destination</h5>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-white-dark mb-1">Country</label>
                        <p class="font-semibold">{{ $student->country->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">University</label>
                        <p class="font-semibold">{{ $student->university->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Course</label>
                        <p class="font-semibold">{{ $student->course->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Intake</label>
                        <p class="font-semibold">{{ $student->intake->intake_name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light text-primary uppercase">Documents</h5>
                </div>
                @if ($student->documents && count($student->documents) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach ($student->documents as $doc)
                    <div class="flex items-center gap-3 p-3 border rounded-lg bg-gray-50 dark:bg-black/20">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-sm font-medium truncate" title="{{ $doc['name'] }}">
                                {{ $doc['name'] }}
                            </p>
                            <div class="flex gap-2 mt-1">
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($doc['path']) }}" target="_blank"
                                    class="text-xs text-primary hover:underline font-semibold">View PDF</a>
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($doc['path']) }}" download
                                    class="text-xs text-green-600 hover:underline font-semibold">Download</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-white-dark italic text-sm">No documents uploaded.</p>
                @endif
            </div> --}}

            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light text-primary uppercase">University Applications
                    </h5>
                    <!-- <a href="{{ route('admin.applications.create', ['student_id' => $student->id]) }}"
                        class="btn btn-sm btn-primary">New Application</a> -->
                </div>
                @if ($student->applications->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table-hover w-full table-auto text-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <!-- <th>University</th>
                                    <th>Course</th> -->
                                    <th>Status</th>
                                    <th>Offer Letter</th>
                                    <th>VFS</th>
                                    <th>File Submit</th>
                                    <th>Visa</th>
                                    <!-- <th>Priority</th> -->
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($student->applications as $app)
                                    <tr>
                                        <td class="font-bold">{{ $app->application_id }}</td>
                                        <!-- <td>{{ $app->university->name ?? 'N/A' }}</td>
                                        <td>{{ $app->course->name ?? 'N/A' }}</td> -->
                                        <td>
                                            <span
                                                class="badge badge-outline-primary capitalize text-xs">{{ str_replace('_', ' ', $app->status) }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($app->offer_letter_received)
                                                <span class="badge badge-outline-success text-xs">Yes</span>
                                                @if ($app->offer_letter_received_date)
                                                    <div class="text-xs text-white-dark mt-1">
                                                        {{ $app->offer_letter_received_date->format('M d, Y') }}</div>
                                                @endif
                                            @else
                                                <span class="badge badge-outline-danger text-xs">No</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($app->vfs_appointment)
                                                <span class="badge badge-outline-success text-xs">Yes</span>
                                                @if ($app->vfs_appointment_date)
                                                    <div class="text-xs text-white-dark mt-1">
                                                        {{ $app->vfs_appointment_date->format('M d, Y') }}</div>
                                                @endif
                                            @else
                                                <span class="badge badge-outline-danger text-xs">No</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($app->file_submission)
                                                <span class="badge badge-outline-success text-xs">Yes</span>
                                                @if ($app->file_submission_date)
                                                    <div class="text-xs text-white-dark mt-1">
                                                        {{ $app->file_submission_date->format('M d, Y') }}</div>
                                                @endif
                                            @else
                                                <span class="badge badge-outline-danger text-xs">No</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-outline-{{ $app->visa_status === 'approved' ? 'success' : ($app->visa_status === 'rejected' ? 'danger' : 'warning') }} text-xs">
                                                {{ ucfirst(str_replace('_', ' ', $app->visa_status)) }}
                                            </span>
                                        </td>
                                        <!-- <td class="text-center">
                                            <span
                                                class="badge badge-outline-{{ $app->application_priority === 'vip' ? 'danger' : ($app->application_priority === 'priority' ? 'warning' : 'info') }} text-xs">
                                                {{ ucfirst($app->application_priority) }}
                                            </span>
                                        </td> -->
                                        <!-- <td>
                                            <a href="{{ route('admin.applications.edit', $app->id) }}"
                                                class="text-primary hover:underline">Edit</a>
                                        </td> -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-white-dark italic text-sm">No applications found.</p>
                @endif
            </div>
        </div>

        <!-- System & Assignment Info -->
        <div class="space-y-6">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light text-primary uppercase">Preferred Academic
                        Destination</h5>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-white-dark mb-1">Country</label>
                        <p class="font-semibold">{{ $student->country->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">University</label>
                        <p class="font-semibold">{{ $student->university->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Course</label>
                        <p class="font-semibold">{{ $student->course->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Intake</label>
                        <p class="font-semibold">{{ $student->intake->intake_name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light text-primary uppercase">Documents</h5>
                </div>

                @if ($student->documents && count($student->documents) > 0)
                    <div class="space-y-3">
                        <p class="text-xs font-bold text-white-dark uppercase">General Documents</p>
                        <div class="grid grid-cols-1 gap-4">
                            @foreach ($student->documents as $doc)
                                @php
                                    $path = $doc['path'] ?? null;
                                    $name = $doc['name'] ?? 'Document';
                                    $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                    $previewUrl = $path ? route('preview-file', ['path' => $path]) : null;
                                    $downloadUrl = $path ? route('download-file', ['path' => $path]) : null;
                                @endphp
                                <div class="rounded-lg border border-white-light bg-gray-50 p-3 dark:border-[#1b2e4b] dark:bg-black/20">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold break-words" title="{{ $name }}">{{ $name }}</p>
                                            <p class="text-xs text-white-dark uppercase">{{ $extension ?: 'file' }}</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            @if ($previewUrl)
                                                <a href="{{ $previewUrl }}" target="_blank"
                                                    class="text-xs text-primary hover:underline font-semibold whitespace-nowrap">
                                                    Show
                                                </a>
                                            @endif
                                            @if ($downloadUrl)
                                                <a href="{{ $downloadUrl }}"
                                                    class="text-xs text-success hover:underline font-semibold whitespace-nowrap">
                                                    Download
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($student->translation_documents && count($student->translation_documents) > 0)
                    <div class="space-y-3 mt-4">
                        <p class="text-xs font-bold text-white-dark uppercase">Translation Documents</p>
                        <div class="grid grid-cols-1 gap-4">
                            @foreach ($student->translation_documents as $doc)
                                @php
                                    $path = $doc['path'] ?? null;
                                    $name = $doc['name'] ?? 'Document';
                                    $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                    $previewUrl = $path ? route('preview-file', ['path' => $path]) : null;
                                    $downloadUrl = $path ? route('download-file', ['path' => $path]) : null;
                                @endphp
                                <div class="rounded-lg border border-white-light bg-gray-50 p-3 dark:border-[#1b2e4b] dark:bg-black/20">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold break-words" title="{{ $name }}">{{ $name }}</p>
                                            <p class="text-xs text-white-dark uppercase">{{ $extension ?: 'file' }}</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            @if ($previewUrl)
                                                <a href="{{ $previewUrl }}" target="_blank"
                                                    class="text-xs text-secondary hover:underline font-semibold whitespace-nowrap">
                                                    Show
                                                </a>
                                            @endif
                                            @if ($downloadUrl)
                                                <a href="{{ $downloadUrl }}"
                                                    class="text-xs text-success hover:underline font-semibold whitespace-nowrap">
                                                    Download
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if((!$student->documents || count($student->documents) == 0) && (!$student->translation_documents || count($student->translation_documents) == 0))
                    <p class="text-white-dark italic text-sm">No documents uploaded.</p>
                @endif
            </div>
            @can('*consultant')
                <div class="panel border-danger">
                    <h5 class="font-semibold text-lg mb-5 text-danger">Danger Zone</h5>
                    <p class="text-xs text-white-dark mb-4">Deleting this student will remove all associated data permanently.
                    </p>
                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
                        onsubmit="return confirm('Are you absolutely sure? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-full">Delete Student</button>
                    </form>
                </div>
            @endcan
            {{-- <div class="panel h-full">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light text-primary uppercase">Status & Assignment</h5>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="text-white-dark mb-1">Current Stage</label>
                        <div>
                            <span class="badge badge-outline-primary capitalize text-base">{{ $student->current_stage
                                }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Application Status</label>
                        <div>
                            <span class="badge badge-outline-primary capitalize text-base">{{ str_replace('_', ' ',
                                $student->current_status) }}</span>
                        </div>
                    </div>
                    <hr class="border-white-light dark:border-[#1b2e4b] my-4">
                    <div>
                        <label class="text-white-dark mb-1">Marketing Assignee</label>
                        <p class="font-semibold">{{ $student->marketingAssignee->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Consultant Assignee</label>
                        <p class="font-semibold">{{ $student->consultantAssignee->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <label class="text-white-dark mb-1">Application Assignee</label>
                        <p class="font-semibold">{{ $student->applicationAssignee->name ?? 'Unassigned' }}</p>
                    </div>
                    <hr class="border-white-light dark:border-[#1b2e4b] my-4">
                    <div class="text-xs space-y-1">
                        <p><span class="text-white-dark">Collected By:</span> {{ $student->creator->name ?? 'System' }}
                        </p>
                        <p><span class="text-white-dark">Created At:</span> {{ $student->created_at->format('M d, Y ') }}
                        </p>
                        <p><span class="text-white-dark">Last Updated:</span>
                            {{ $student->updated_at->format('M d, Y ') }}</p>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection
