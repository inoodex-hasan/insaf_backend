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
    <div class="mb-5">
        <form action="{{ route('admin.applications.index') }}" method="GET" class="flex flex-col gap-3 w-full">

            {{-- Row 1: Search + Status + Offer Letter + Visa --}}
            <div style="display: flex; align-items: center; gap: 8px; width: 100%;">
                <div class="relative" style="flex: 2; min-width: 150px;">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search..." class="form-input ltr:pr-11 rtl:pl-11" style="width: 100%;" />
                    <button type="submit"
                        class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>

                <select name="status" class="form-select" style="flex: 1; min-width: 120px;">
                    <option value="">Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="applied" {{ request('status') == 'applied' ? 'selected' : '' }}>Applied</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="withdrawn" {{ request('status') == 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                    <option value="visa_processing" {{ request('status') == 'visa_processing' ? 'selected' : '' }}>Visa Processing</option>
                    <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                </select>

                <select name="offer_letter" class="form-select" style="flex: 1; min-width: 120px;">
                    <option value="">Offer Letter</option>
                    <option value="1" {{ request('offer_letter') == '1' ? 'selected' : '' }}>Received</option>
                    <option value="0" {{ request('offer_letter') == '0' ? 'selected' : '' }}>Not Received</option>
                </select>

                <select name="visa_status" class="form-select" style="flex: 1; min-width: 120px;">
                    <option value="">Visa</option>
                    <option value="not_applied" {{ request('visa_status') == 'not_applied' ? 'selected' : '' }}>Not Applied</option>
                    <option value="pending" {{ request('visa_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('visa_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('visa_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            {{-- Row 2: EMGS Score + Security Deposit + CVU Fee + Filter/Reset buttons --}}
            <div style="display: flex; align-items: center; gap: 8px; width: 100%;">
                <select name="emgs_score" class="form-select" style="flex: 1; min-width: 120px;">
                    <option value="">EMGS Score</option>
                    @foreach([5, 15, 32, 35, 70] as $score)
                        <option value="{{ $score }}" {{ request('emgs_score') == $score ? 'selected' : '' }}>{{ $score }}%</option>
                    @endforeach
                </select>

                <select name="security_deposit" class="form-select" style="flex: 1; min-width: 150px;">
                    <option value="">Security Deposit Fee</option>
                    <option value="1" {{ request('security_deposit') == '1' ? 'selected' : '' }}>Paid</option>
                    <option value="0" {{ request('security_deposit') == '0' ? 'selected' : '' }}>Pending</option>
                </select>

                <select name="cvu_fee" class="form-select" style="flex: 1; min-width: 120px;">
                    <option value="">CVU Fee</option>
                    <option value="1" {{ request('cvu_fee') == '1' ? 'selected' : '' }}>Paid</option>
                    <option value="0" {{ request('cvu_fee') == '0' ? 'selected' : '' }}>Pending</option>
                </select>

                {{-- <select name="final_payment" class="form-select" style="flex: 1; min-width: 120px;">
                    <option value="">Final Pay</option>
                    <option value="1" {{ request('final_payment') == '1' ? 'selected' : '' }}>Paid</option>
                    <option value="0" {{ request('final_payment') == '0' ? 'selected' : '' }}>Pending</option>
                </select> --}}

                {{-- Spacer to push buttons to the right --}}
                <div style="flex: 2;"></div>

                <button type="submit" class="btn btn-primary" style="white-space: nowrap;">Filter</button>
                <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-danger" style="white-space: nowrap;">Reset</a>
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
                        <td>
                            <span
                                class="badge badge-outline-{{ $app->status === 'pending' ? 'warning' : ($app->status === 'approved' ? 'success' : 'danger') }}">
                                {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                            </span>
                        </td>
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
