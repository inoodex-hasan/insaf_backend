@extends('admin.layouts.master')

@section('title', 'Marketing Posters')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Marketing Posters</h2>
    </div>

    <!-- Add New Poster -->
    <div class="panel mt-6">
        <h3 class="text-lg font-bold mb-4">Add New Poster</h3>
        <form action="{{ route('admin.marketing.posters.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            <div>
                <input type="text" name="poster_name" placeholder="Poster name" class="form-input" required />
            </div>
            <div>
                <select name="status" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="not_ready">Not Ready</option>
                    <option value="designing">Designing</option>
                    <option value="ready">Ready</option>
                    <option value="uploaded">Uploaded</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Add Poster</button>
            </div>
        </form>
    </div>

    <!-- Filter -->
    <div class="panel mt-6">
        <form action="{{ route('admin.marketing.posters.index') }}" method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posters..." class="form-input w-full md:w-auto" />
            <select name="status" class="form-select w-full md:w-auto">
                <option value="">Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="not_ready" {{ request('status') == 'not_ready' ? 'selected' : '' }}>Not Ready</option>
                <option value="designing" {{ request('status') == 'designing' ? 'selected' : '' }}>Designing</option>
                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="uploaded" {{ request('status') == 'uploaded' ? 'selected' : '' }}>Uploaded</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.marketing.posters.index') }}" class="btn btn-outline-primary">Reset</a>
        </form>
    </div>

    <!-- Posters Table -->
    <div class="panel mt-6">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Poster Name</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posters as $poster)
                            <tr>
                                <td>{{ $poster->poster_name }}</td>
                                <td>
                                    @php
                                        $pClass = [
                                            'pending' => 'badge-outline-dark',
                                            'not_ready' => 'badge-outline-danger',
                                            'designing' => 'badge-outline-warning',
                                            'ready' => 'badge-outline-success',
                                            'uploaded' => 'badge-outline-primary',
                                        ][$poster->status] ?? 'badge-outline-dark';
                                    @endphp
                                    <span class="badge {{ $pClass }} capitalize text-[10px]">{{ str_replace('_', ' ', $poster->status) }}</span>
                                </td>
                                <td>{{ $poster->created_at->format('M d, Y') }}</td>
                                <td class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.marketing.posters.edit', $poster) }}"
                                        class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.marketing.posters.destroy', $poster) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-white-dark">No posters found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $posters->links() }}
            </div>
        </div>
    </div>
@endsection
