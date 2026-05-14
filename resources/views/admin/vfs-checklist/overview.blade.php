@extends('admin.layouts.master')

@section('title', 'VFS Overview')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">VFS Overview & Tracking</h2>
    </div>

    <div class="panel mt-6">
        <!-- Filters -->
        <form action="{{ route('admin.vfs-checklist.overview') }}" method="GET" class="mb-6">
            <div class="flex items-center gap-3">
                <div class="flex-1">
                    <input type="text" name="search" placeholder="Search Application ID, Student or Passport..."
                        value="{{ request('search') }}" class="form-input w-full" />
                </div>
                
                <div class="w-48">
                    <select name="vfs_result" class="form-select w-full">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('vfs_result') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="passed" {{ request('vfs_result') == 'passed' ? 'selected' : '' }}>Passed</option>
                        <option value="rejected" {{ request('vfs_result') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary px-6">Filter</button>
                    @if(request()->hasAny(['search', 'vfs_result']))
                        <a href="{{ route('admin.vfs-checklist.overview') }}" class="btn btn-outline-danger">Clear</a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>Student Name</th>
                        <th>Passport</th>
                        <th>University</th>
                        <th class="text-center">VFS Date</th>
                        <!-- <th class="text-center">History</th> -->
                        <th class="text-center">VFS Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                        <tr>
                            <td class="font-semibold">
                                    {{ $app->application_id }}
                                    <div class="text-[10px] text-info font-bold uppercase mt-1">Attempt #{{ $app->total_vfs_attempts }}</div>
                                </td>
                            <td>
                                <div class="font-medium">{{ $app->student->first_name }} {{ $app->student->last_name }}</div>
                                <div class="text-xs text-white-dark">{{ $app->student->email }}</div>
                            </td>
                            <td>{{ $app->student->passport_number ?? 'N/A' }}</td>
                            <td>
                                <div class="font-medium">{{ $app->university?->name ?? 'N/A' }}</div>
                                <div class="text-xs text-info">{{ $app->university?->country?->name }}</div>
                            </td>
                            <td class="text-center">
                                {{ $app->vfs_appointment_date ? $app->vfs_appointment_date->format('M d, Y') : 'Not Set' }}
                            </td>
                            <!-- <td class="text-center">
                                @if($app->history->count() > 0)
                                    <div class="dropdown">
                                        <button type="button" class="badge badge-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                            {{ $app->history->count() }} Previous {{ Illuminate\Support\Str::plural('Attempt', $app->history->count()) }}
                                        </button>
                                        <ul class="dropdown-menu p-3 shadow-lg border-primary min-w-[250px]">
                                            <li class="font-bold border-b mb-2 pb-1">Previous Attempts</li>
                                            @foreach($app->history as $prev)
                                                <li class="text-xs mb-2 pb-2 border-b border-dashed last:border-0">
                                                    <div class="flex justify-between font-semibold">
                                                        <span>{{ $prev->vfs_appointment_date?->format('M d, Y') }}</span>
                                                        <span class="{{ $prev->vfs_result == 'passed' ? 'text-success' : 'text-danger' }} uppercase">{{ $prev->vfs_result }}</span>
                                                    </div>
                                                    <div class="text-white-dark italic">{{ $prev->university?->name }}</div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <span class="text-xs text-white-dark italic">1st Attempt</span>
                                @endif
                            </td> -->
                            <td class="text-center">
                                <span class="badge {{ $app->vfs_result == 'passed' ? 'bg-success' : ($app->vfs_result == 'rejected' ? 'bg-danger' : 'bg-warning') }} uppercase">
                                    {{ $app->vfs_result }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.vfs-checklist.vfs-show', $app->id) }}" class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                    <a href="{{ route('admin.vfs-checklist.vfs-edit', $app->id) }}" class="btn btn-sm btn-outline-warning">
                                        Edit
                                    </a>
                                    <a href="{{ route('admin.vfs-checklist.show', $app->id) }}" class="btn btn-sm btn-outline-info" title="View Checklist">
                                        Checklist
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-white-dark">No VFS records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

        <div class="mt-4">
            {{ $applications->links() }}
        </div>
    </div>
@endsection
