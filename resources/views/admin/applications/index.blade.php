@extends('admin.layouts.master')

@section('title', 'Applications')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4">
    <h2 class="text-xl font-semibold uppercase">Applications</h2>
    @if (auth()->check() && auth()->user()->hasRole('consultant'))
        @can(['*consultant'])
            <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
                <a href="{{ route('admin.applications.create') }}" class="btn btn-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    New Application
                </a>
            </div>
        @endcan
    @endif
</div>

<div class="panel mt-6">
    <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
        <form action="{{ route('admin.applications.index') }}" method="GET"
            class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search Application ID or Student Name..." class="form-input ltr:pr-11 rtl:pl-11" />
                <button type="submit"
                    class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" />
                        <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
            <div class="flex gap-2">
                <select name="status" class="form-select w-auto md:w-auto pr-10">
                    <option value="">Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="applied" {{ request('status') == 'applied' ? 'selected' : '' }}>Applied</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="withdrawn" {{ request('status') == 'withdrawn' ? 'selected' : '' }}>Withdrawn
                    </option>
                    <option value="visa_processing" {{ request('status') == 'visa_processing' ? 'selected' : '' }}>Visa
                        Processing</option>
                    <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-danger">Reset</a>
            </div>
        </form>
    </div>

    <div class="datatable">
        <div class="overflow-x-auto">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>Student</th>
                        <th>University & Course</th>
                        <!-- <th>Intake</th> -->
                        <th>Status</th>
                        <!-- <th>Offer Letter</th>
                        <th>VFS</th>
                        <th>File Submit</th>
                        <th>Visa Status</th>  -->
                        <!-- <th>Priority</th> -->
                        <th>Created By</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                    <tr>
                        <td class="font-bold text-primary">{{ $app->application_id }}</td>
                        <td>
                            @if ($app->student)
                                <div class="font-semibold">{{ $app->student->first_name }}
                                    {{ $app->student->last_name }}
                                </div>
                                <div class="text-xs text-white-dark">{{ $app->student->phone }}</div>
                            @else
                                <span class="text-danger italic">N/A</span>
                            @endif
                        </td>
                        <td>
                            <div class="font-semibold">{{ $app->university->name ?? 'N/A' }}</div>
                            <div class="text-xs text-white-dark">{{ $app->course->name ?? 'N/A' }}</div>
                        </td>
                        <!-- <td>{{ $app->intake->intake_name ?? 'N/A' }}</td> -->
                        <!-- <td>
                            <span
                                class="badge badge-outline-primary capitalize">{{ str_replace('_', ' ', $app->status) }}</span>
                        </td> -->
                        <!-- <td class="text-center">
                            @if ($app->offer_letter_received)
                                <span class="badge badge-outline-success">Yes</span>
                            @else
                                <span class="badge badge-outline-danger">No</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($app->vfs_appointment)
                                <span class="badge badge-outline-success">Yes</span>
                            @else
                                <span class="badge badge-outline-danger">No</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($app->file_submission)
                                <span class="badge badge-outline-success">Yes</span>
                            @else
                                <span class="badge badge-outline-danger">No</span>
                            @endif
                        </td> -->
                        <td>
                            <span
                                class="badge badge-outline-{{ $app->status === 'pending' ? 'warning' : ($app->status === 'approved' ? 'success' : 'danger') }}">
                                {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                            </span>
                        </td>
                        <!-- <td>
                            <span
                                class="badge badge-outline-{{ $app->application_priority === 'vip' ? 'danger' : ($app->application_priority === 'priority' ? 'warning' : 'info') }}">
                                {{ ucfirst($app->application_priority) }}
                            </span>
                        </td> -->
                        <td>
                            <div class="text-xs">
                                {{ $app->creator->name ?? 'System' }}
                                <div class="text-white-dark">{{ $app->created_at->format('M d, Y') }}</div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.applications.show', $app) }}"
                                    class="btn btn-sm btn-outline-info">View</a>
                                @canany(['*application'])
                                <!-- <a href="{{ route('admin.applications.download-pdf', $app->id) }}"
                                    class="btn btn-sm btn-outline-success">PDF</a> -->
                                <a href="{{ route('admin.applications.edit', $app->id) }}"
                                    class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.applications.destroy', $app->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this application?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                                @endcanany

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center">No applications found.</td>
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