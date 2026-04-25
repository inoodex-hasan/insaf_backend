@extends('admin.layouts.master')

@section('title', 'My Commission Claim Details')

@section('content')
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Commission Claim Details</h5>
            <a href="{{ route('my-commissions.index') }}" class="btn btn-outline-primary">Back to Dashboard</a>
        </div>

        {{-- Status Banner --}}
        <div class="rounded-md p-4 mb-6 {{ match($commission->workflow_status) {
            'draft' => 'bg-secondary/10',
            'claimed' => 'bg-info/10',
            'under_review' => 'bg-warning/10',
            'approved' => 'bg-success/10',
            'rejected' => 'bg-danger/10',
            'paid' => 'bg-primary/10',
            default => 'bg-gray-100'
        } }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-gray-500 text-sm">Current Status:</span>
                    <div class="text-xl font-bold {{ match($commission->workflow_status) {
                        'draft' => 'text-secondary',
                        'claimed' => 'text-info',
                        'under_review' => 'text-warning',
                        'approved' => 'text-success',
                        'rejected' => 'text-danger',
                        'paid' => 'text-primary',
                        default => 'text-gray-700'
                    } }}">
                        {{ $commission->getWorkflowStatusLabel() }}
                    </div>
                </div>
                <span class="badge badge-outline-{{ $commission->getStatusBadgeClass() }} text-lg px-4 py-2">
                    {{ $commission->getWorkflowStatusLabel() }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Left Column --}}
            <div>
                {{-- Application Details --}}
                <div class="bg-gray-50 dark:bg-gray-800 rounded-md p-4 mb-4">
                    <h6 class="font-semibold mb-3">Application Details</h6>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Application ID:</span>
                            <span class="font-medium">{{ $commission->application->application_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Student:</span>
                            <span class="font-medium">{{ $commission->application->student->full_name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">University:</span>
                            <span class="font-medium">{{ $commission->application->university->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Course:</span>
                            <span class="font-medium">{{ $commission->application->course->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tuition Fee:</span>
                            <span class="font-medium">BDT {{ number_format($commission->application->tuition_fee, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Claim Details --}}
                <div class="bg-gray-50 dark:bg-gray-800 rounded-md p-4 mb-4">
                    <h6 class="font-semibold mb-3">Your Claim Details</h6>
                    <div class="space-y-2">
                        <!-- <div class="flex justify-between">
                            <span class="text-gray-500">Proposed Percentage:</span>
                            <span class="font-medium">{{ $commission->percentage }}%</span>
                        </div> -->
                        <div class="flex justify-between">
                            <span class="text-gray-500">Proposed Amount:</span>
                            <span class="font-medium text-success">BDT {{ number_format($commission->proposed_amount, 2) }}</span>
                        </div>
                        @if($commission->amount > 0 && $commission->amount != $commission->proposed_amount)
                            <div class="flex justify-between border-t pt-2 mt-2">
                                <span class="text-gray-500">Final Approved Amount:</span>
                                <span class="font-medium text-success">BDT {{ number_format($commission->amount, 2) }}</span>
                            </div>
                        @elseif($commission->amount > 0)
                            <div class="flex justify-between border-t pt-2 mt-2">
                                <span class="text-gray-500">Final Approved Amount:</span>
                                <span class="font-medium text-success">BDT {{ number_format($commission->amount, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div>
                {{-- Timeline --}}
                <div class="bg-gray-50 dark:bg-gray-800 rounded-md p-4 mb-4">
                    <h6 class="font-semibold mb-3">Claim Timeline</h6>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 rounded-full bg-primary mt-2 shrink-0"></div>
                            <div>
                                <div class="font-medium">Claim Submitted</div>
                                <div class="text-sm text-gray-500">{{ $commission->claimed_at?->format('M d, Y H:i') ?? '-' }}</div>
                            </div>
                        </div>
                        
                        @if($commission->reviewed_at)
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full {{ $commission->isApproved() || $commission->isPaid() ? 'bg-success' : 'bg-danger' }} mt-2 shrink-0"></div>
                                <div>
                                    <div class="font-medium">
                                        {{ $commission->isApproved() || $commission->isPaid() ? 'Approved' : 'Reviewed' }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $commission->reviewed_at?->format('M d, Y H:i') }}</div>
                                    @if($commission->reviewer)
                                        <div class="text-sm text-gray-500">by {{ $commission->reviewer->name }}</div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-gray-300 mt-2 shrink-0"></div>
                                <div class="text-gray-500">Awaiting Review</div>
                            </div>
                        @endif

                        @if($commission->isPaid())
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-primary mt-2 shrink-0"></div>
                                <div>
                                    <div class="font-medium">Paid</div>
                                    <div class="text-sm text-gray-500">Commission has been paid</div>
                                </div>
                            </div>
                        @elseif($commission->isApproved())
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-warning mt-2 shrink-0"></div>
                                <div class="text-warning">Pending Payment</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Review Notes --}}
                @if($commission->review_notes)
                    <div class="bg-info/10 rounded-md p-4 mb-4">
                        <h6 class="font-semibold mb-2">Accountant's Notes</h6>
                        <p class="text-gray-700 dark:text-gray-300">{{ $commission->review_notes }}</p>
                    </div>
                @endif

                {{-- Your Notes --}}
                @if($commission->claim_notes)
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-md p-4">
                        <h6 class="font-semibold mb-2">Your Justification</h6>
                        <p class="text-gray-700 dark:text-gray-300">{{ $commission->claim_notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        @if($commission->canBeCancelled())
            <div class="mt-6 flex items-center gap-4">
                <form action="{{ route('my-commissions.cancel', $commission) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning"
                        onclick="return confirm('Cancel this claim? You can resubmit later.')">
                        Cancel Claim
                    </button>
                </form>
                <span class="text-sm text-gray-500">You can cancel this claim while it's under review.</span>
            </div>
        @endif

        @if($commission->isRejected())
            <div class="mt-6">
                <a href="{{ route('my-commissions.claimable') }}" class="btn btn-primary">
                    Resubmit Claim
                </a>
                <span class="text-sm text-gray-500 ml-2">Your claim was rejected. You can submit a new claim with adjustments.</span>
            </div>
        @endif
    </div>
@endsection
