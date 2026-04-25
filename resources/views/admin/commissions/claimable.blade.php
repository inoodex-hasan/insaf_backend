@extends('admin.layouts.master')

@section('title', 'Claim Commission')

@section('content')
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Applications Available for Commission Claim</h5>
            <a href="{{ route('my-commissions.index') }}" class="btn btn-outline-primary">Back to My Commissions</a>
        </div>

        <div class="mb-5">
            {{-- Filter --}}
            <form method="GET" class="flex flex-col md:flex-row gap-4 mb-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by application ID, student name or email..."
                        class="form-input w-full" />
                </div>
                <div class="w-40 shrink-0">
                    <select name="application_status" class="form-select w-full text-sm">
                        <option value="">Status</option>
                        <option value="pending" {{ request('application_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('application_status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="approved" {{ request('application_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('application_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary whitespace-nowrap">Search</button>
            </form>

            <div class="table-responsive">
                <table class="table-hover">
                    <thead>
                        <tr>
                            <th>Application ID</th>
                            <th>Student</th>
                            <th>University / Course</th>
                            <th>Intake</th>
                            <th>Status</th>
                            <th>Your Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            @php
                                $role = '';
                                if ($application->student->assigned_marketing_id === auth()->id()) {
                                    $role = 'Marketing';
                                } elseif ($application->student->assigned_consultant_id === auth()->id()) {
                                    $role = 'Consultant';
                                } elseif ($application->student->assigned_application_id === auth()->id()) {
                                    $role = 'Application Staff';
                                } elseif ($application->created_by === auth()->id()) {
                                    $role = 'Creator';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <span class="font-medium">{{ $application->application_id }}</span>
                                </td>
                                <td>
                                    <div>{{ $application->student->full_name ?? '-' }}</div>
                                    <div class="text-sm text-gray-500">{{ $application->student->email ?? '-' }}</div>
                                </td>
                                <td>
                                    <div>{{ $application->university->name ?? '-' }}</div>
                                    <div class="text-sm text-gray-500">{{ $application->course->name ?? '-' }}</div>
                                </td>
                                <td>{{ $application->intake->intake_name ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-outline-{{ match($application->status) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'processing' => 'info',
                                        default => 'warning'
                                    } }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-outline-primary">{{ $role }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('my-commissions.create-claim', $application) }}" class="btn btn-sm btn-primary">
                                        Claim Commission
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">
                                    <div class="mb-2">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto text-gray-400">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p>No applications available for commission claim at this time.</p>
                                    <p class="text-sm">You can claim commission on applications where you are assigned as Marketing, Consultant, or Application Staff.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
@endsection
