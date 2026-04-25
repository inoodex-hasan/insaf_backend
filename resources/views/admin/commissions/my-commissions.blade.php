@extends('admin.layouts.master')

@section('title', 'My Commissions')

@section('content')
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">My Commission Dashboard</h5>
            <a href="{{ route('my-commissions.claimable') }}" class="btn btn-primary">Claim Commission</a>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-secondary/10 rounded-md p-4">
                <div class="text-secondary text-sm">Draft / Cancelled</div>
                <div class="text-2xl font-bold">{{ $stats['draft'] }}</div>
            </div>
            <div class="bg-info/10 rounded-md p-4">
                <div class="text-info text-sm">Under Review</div>
                <div class="text-2xl font-bold">{{ $stats['claimed'] + $stats['under_review'] }}</div>
                <div class="text-sm text-gray-500">BDT {{ number_format($stats['total_claimed_amount'], 2) }}</div>
            </div>
            <div class="bg-success/10 rounded-md p-4">
                <div class="text-success text-sm">Approved</div>
                <div class="text-2xl font-bold">{{ $stats['approved'] }}</div>
                <div class="text-sm text-gray-500">BDT {{ number_format($stats['total_approved_amount'], 2) }}</div>
            </div>
            <div class="bg-primary/10 rounded-md p-4">
                <div class="text-primary text-sm">Paid</div>
                <div class="text-2xl font-bold">{{ $stats['paid'] }}</div>
                <div class="text-sm text-gray-500">BDT {{ number_format($stats['total_paid_amount'], 2) }}</div>
            </div>
        </div>

        {{-- Rejected Warning --}}
        @if($stats['rejected'] > 0)
            <div class="bg-danger/10 border border-danger/20 rounded-md p-4 mb-6">
                <div class="flex items-center gap-2 text-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>You have {{ $stats['rejected'] }} rejected claim(s). You can resubmit them with corrections.</span>
                </div>
            </div>
        @endif

        {{-- Filter --}}
        <div class="mb-5">
            <form method="GET" class="flex flex-col md:flex-row gap-4 mb-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by application ID or student name..."
                        class="form-input w-full" />
                </div>
                <div class="w-40 shrink-0">
                    <select name="status" class="form-select w-full text-sm">
                        <option value="">Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="claimed" {{ request('status') === 'claimed' ? 'selected' : '' }}>Claimed</option>
                        <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary whitespace-nowrap">Search</button>
            </form>

            <div class="table-responsive">
                <table class="table-hover">
                    <thead>
                        <tr>
                            <th>Application</th>
                            <th>Student</th>
                            <th>Proposed Amount</th>
                            <th>Final Amount</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissions as $commission)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.applications.show', $commission->application_id) }}" class="text-primary hover:underline">
                                        {{ $commission->application->application_id ?? '-' }}
                                    </a>
                                </td>
                                <td>{{ $commission->application->student->full_name ?? '-' }}</td>
                                <td>BDT {{ number_format($commission->proposed_amount, 2) }}</td>
                                <td>
                                    @if($commission->amount > 0)
                                        BDT {{ number_format($commission->amount, 2) }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-outline-{{ $commission->getStatusBadgeClass() }}">
                                        {{ $commission->getWorkflowStatusLabel() }}
                                    </span>
                                </td>
                                <td>
                                    @if($commission->claimed_at)
                                        {{ $commission->claimed_at->format('M d, Y') }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('my-commissions.show', $commission) }}" class="btn btn-sm btn-info">View</a>
                                        
                                        @if($commission->canBeCancelled())
                                            <form action="{{ route('my-commissions.cancel', $commission) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning"
                                                    onclick="return confirm('Cancel this claim? You can resubmit later.')">Cancel</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-gray-500">
                                    No commission claims found.
                                    <a href="{{ route('my-commissions.claimable') }}" class="text-primary hover:underline">Claim one now</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $commissions->links() }}
            </div>
        </div>
    </div>
@endsection
