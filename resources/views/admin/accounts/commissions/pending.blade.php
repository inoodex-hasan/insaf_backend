@extends('admin.layouts.master')

@section('title', 'Pending Commission Claims')

@section('content')
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Pending Commission Claims for Review</h5>
            <div class="flex gap-2">
                <a href="{{ route('admin.commissions.index') }}" class="btn btn-outline-primary">All Commissions</a>
                <button onclick="bulkApprove()" class="btn btn-success" id="bulkApproveBtn" style="display: none;">
                    Bulk Approve (<span id="selectedCount">0</span>)
                </button>
            </div>
        </div>

        <div class="mb-5">
            {{-- Filters --}}
            <form method="GET" class="mb-4">
                <div class="flex flex-wrap gap-2 items-end">
                    {{-- Search --}}
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                            class="form-input w-full" />
                    </div>

                    {{-- Role Filter --}}
                    <div class="w-[140px]">
                        <select name="role" class="form-select w-full text-sm">
                            <option value="">Roles</option>
                            <option value="marketing" {{ request('role') === 'marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="consultant" {{ request('role') === 'consultant' ? 'selected' : '' }}>Consultant</option>
                            <option value="application" {{ request('role') === 'application' ? 'selected' : '' }}>Application</option>
                            <option value="creator" {{ request('role') === 'creator' ? 'selected' : '' }}>Creator</option>
                        </select>
                    </div>

                    {{-- Status Filter --}}
                    <div class="w-[140px]">
                        <select name="workflow_status" class="form-select w-full text-sm">
                            <option value="">Pending</option>
                            <option value="claimed" {{ request('workflow_status') === 'claimed' ? 'selected' : '' }}>Claimed</option>
                            <option value="under_review" {{ request('workflow_status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div class="w-[130px]">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From"
                            class="form-input w-full text-sm" title="From Date" />
                    </div>

                    {{-- Date To --}}
                    <div class="w-[130px]">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="To"
                            class="form-input w-full text-sm" title="To Date" />
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-primary whitespace-nowrap">Filter</button>
                        <a href="{{ route('admin.commissions.pending') }}" class="btn btn-outline-secondary whitespace-nowrap">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-checkbox">
                            </th>
                            <th>Application</th>
                            <th>Student</th>
                            <th>Employee</th>
                            <th>Role</th>
                            <th>Proposed Amount</th>
                            <!-- <th>Percentage</th> -->
                            <th>Claimed Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissions as $commission)
                            @php
                                $role = '';
                                if ($commission->application->student->assigned_marketing_id === $commission->user_id) {
                                    $role = 'Marketing';
                                } elseif ($commission->application->student->assigned_consultant_id === $commission->user_id) {
                                    $role = 'Consultant';
                                } elseif ($commission->application->student->assigned_application_id === $commission->user_id) {
                                    $role = 'Application';
                                } else {
                                    $role = 'Creator';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <input type="checkbox" name="commission_ids[]" value="{{ $commission->id }}" 
                                        class="form-checkbox commission-checkbox">
                                </td>
                                <td>
                                    <a href="{{ route('admin.applications.show', $commission->application_id) }}" class="text-primary hover:underline">
                                        {{ $commission->application->application_id ?? '-' }}
                                    </a>
                                </td>
                                <td>{{ $commission->application->student->full_name ?? '-' }}</td>
                                <td>{{ $commission->user->name ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-outline-info">{{ $role }}</span>
                                </td>
                                <td class="font-medium text-success">BDT {{ number_format($commission->proposed_amount, 2) }}</td>
                                <!-- <td>{{ $commission->percentage }}%</td> -->
                                <td>{{ $commission->claimed_at?->format('M d, Y') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.commissions.review', $commission) }}" class="btn btn-sm btn-primary">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-8 text-gray-500">
                                    <div class="mb-2">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto text-gray-400">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p>No pending commission claims to review.</p>
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

@push('scripts')
<script>
    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.commission-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkApproveButton();
    });

    // Individual checkboxes
    document.querySelectorAll('.commission-checkbox').forEach(cb => {
        cb.addEventListener('change', updateBulkApproveButton);
    });

    function updateBulkApproveButton() {
        const selected = document.querySelectorAll('.commission-checkbox:checked');
        const btn = document.getElementById('bulkApproveBtn');
        const count = document.getElementById('selectedCount');
        
        count.textContent = selected.length;
        btn.style.display = selected.length > 0 ? 'inline-flex' : 'none';
    }

    function bulkApprove() {
        const selected = document.querySelectorAll('.commission-checkbox:checked');
        const ids = Array.from(selected).map(cb => cb.value);

        if (ids.length === 0) return;

        if (!confirm(`Approve ${ids.length} commission claim(s)? The proposed amounts will be used as final amounts.`)) {
            return;
        }

        fetch('{{ route('admin.commissions.bulk-approve') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ commissions: ids })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            }
        })
        .catch(error => {
            alert('Error approving commissions. Please try again.');
            console.error(error);
        });
    }
</script>
@endpush
