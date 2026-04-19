@extends('admin.layouts.master')

@section('title', 'Commission Management')

@section('content')
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Commission Management</h5>
            <a href="{{ route('admin.commissions.create') }}" class="btn btn-primary">Add Commission</a>
        </div>

        <div class="mb-5">
            <form method="GET" class="flex flex-col md:flex-row gap-4 mb-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by employee, student, application..."
                        class="form-input w-full" />
                </div>
                <div class="w-full md:w-40">
                    <select name="status" class="form-select w-full">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
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
                            <th>Employee</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissions as $commission)
                            <tr>
                                <td>{{ $commission->application->application_id ?? '-' }}</td>
                                <td>{{ $commission->application->student->full_name ?? '-' }}</td>
                                <td>{{ $commission->user->name ?? '-' }}</td>
                                <td>BDT {{ number_format($commission->amount, 2) }}</td>
                                <td>
                                    <span class="badge {{ $commission->status === 'paid' ? 'badge-outline-success' : 'badge-outline-warning' }}">
                                        {{ ucfirst($commission->status) }}
                                    </span>
                                </td>
                                <td>{{ $commission->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        <button onclick="toggleStatus({{ $commission->id }}, '{{ $commission->status }}')"
                                            class="btn btn-sm {{ $commission->status === 'pending' ? 'btn-success' : 'btn-warning' }}">
                                            {{ $commission->status === 'pending' ? 'Mark Paid' : 'Mark Pending' }}
                                        </button>
                                        <form action="{{ route('admin.commissions.destroy', $commission) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this commission?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-gray-500">No commissions found.</td>
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
        function toggleStatus(commissionId, currentStatus) {
            const newStatus = currentStatus === 'pending' ? 'paid' : 'pending';

            fetch(`{{ url('dashboard/commissions') }}/${commissionId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        }
    </script>
@endpush
