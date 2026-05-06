@extends('admin.layouts.master')

@section('title', 'View Commission')

@section('content')
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Commission Details</h5>
            <a href="{{ route('admin.commissions.index') }}" class="btn btn-outline-primary">Back to Commissions</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-md p-4">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div>
                            <h6 class="font-semibold mb-2">Claim Summary</h6>
                            <p class="text-sm text-gray-500">Commission ID: <span class="font-medium">{{ $commission->id }}</span></p>
                        </div>
                        <span class="badge badge-outline-{{ $commission->getStatusBadgeClass() }} text-sm">
                            {{ $commission->getWorkflowStatusLabel() }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-500 text-sm">Application ID</span>
                            <div class="font-medium">{{ $commission->application->application_id ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Claimed Date</span>
                            <div class="font-medium">{{ $commission->claimed_at?->format('M d, Y H:i') ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Proposed Amount</span>
                            <div class="font-medium">BDT {{ number_format($commission->proposed_amount, 2) }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Final Amount</span>
                            <div class="font-medium">
                                @if($commission->amount > 0)
                                    BDT {{ number_format($commission->amount, 2) }}
                                @else
                                    <span class="text-gray-400">Not set</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-500 text-sm">Status Notes</span>
                            <div class="font-medium">{{ $commission->review_notes ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-800 rounded-md p-4">
                    <h6 class="font-semibold mb-3">Employee Details</h6>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-500 text-sm">Name</span>
                            <div class="font-medium">{{ $commission->user->name ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Email</span>
                            <div class="font-medium">{{ $commission->user->email ?? '-' }}</div>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-500 text-sm">Role in Application</span>
                            @php
                                $role = '';
                                if ($commission->application->student->assigned_marketing_id === $commission->user_id) {
                                    $role = 'Marketing';
                                } elseif ($commission->application->student->assigned_consultant_id === $commission->user_id) {
                                    $role = 'Consultant';
                                } elseif ($commission->application->student->assigned_application_id === $commission->user_id) {
                                    $role = 'Application Staff';
                                } else {
                                    $role = 'Creator';
                                }
                            @endphp
                            <div class="font-medium">{{ $role }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-800 rounded-md p-4">
                    <h6 class="font-semibold mb-3">Application Details</h6>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-500 text-sm">Student</span>
                            <div class="font-medium">{{ $commission->application->student->full_name ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">University</span>
                            <div class="font-medium">{{ $commission->application->university->name ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Course</span>
                            <div class="font-medium">{{ $commission->application->course->name ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                @if($commission->claim_notes)
                    <div class="bg-info/10 rounded-md p-4">
                        <h6 class="font-semibold mb-2">Employee Claim Notes</h6>
                        <p class="text-gray-700 dark:text-gray-300">{{ $commission->claim_notes }}</p>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-1 space-y-4">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-md p-4">
                    <h6 class="font-semibold mb-3">Review Summary</h6>
                    <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                        <div>
                            <span class="text-gray-500">Reviewed By</span>
                            <div class="font-medium">{{ $commission->reviewer?->name ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Reviewed At</span>
                            <div class="font-medium">{{ $commission->reviewed_at?->format('M d, Y H:i') ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Created At</span>
                            <div class="font-medium">{{ $commission->created_at?->format('M d, Y H:i') ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Updated At</span>
                            <div class="font-medium">{{ $commission->updated_at?->format('M d, Y H:i') ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-800 rounded-md p-4">
                    <h6 class="font-semibold mb-3">Current Status</h6>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Status</span>
                            <span class="font-medium">{{ $commission->getWorkflowStatusLabel() }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Final Amount</span>
                            <span class="font-medium">BDT {{ number_format($commission->amount ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Proposed Amount</span>
                            <span class="font-medium">BDT {{ number_format($commission->proposed_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
