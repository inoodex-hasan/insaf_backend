@extends('admin.layouts.master')

@section('title', 'Marketing Videos')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Marketing Videos</h2>
    </div>

    <!-- Add New Video -->
    <div class="panel mt-6">
        <h3 class="text-lg font-bold mb-4">Add New Video</h3>
        <form action="{{ route('admin.marketing.videos.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            <div>
                <input type="text" name="video_name" placeholder="Video name" class="form-input" required />
            </div>
            <div>
                <select name="status" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="not_edited">Not Edited</option>
                    <option value="editing">Editing</option>
                    <option value="ready">Ready</option>
                    <option value="uploaded">Uploaded</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Add Video</button>
            </div>
        </form>
    </div>

    <!-- Filter -->
    <div class="panel mt-6">
        <form action="{{ route('admin.marketing.videos.index') }}" method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search videos..." class="form-input w-full md:w-auto" />
            <select name="status" class="form-select w-full md:w-auto">
                <option value="">Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="not_edited" {{ request('status') == 'not_edited' ? 'selected' : '' }}>Not Edited</option>
                <option value="editing" {{ request('status') == 'editing' ? 'selected' : '' }}>Editing</option>
                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="uploaded" {{ request('status') == 'uploaded' ? 'selected' : '' }}>Uploaded</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.marketing.videos.index') }}" class="btn btn-outline-primary">Reset</a>
        </form>
    </div>

    <!-- Videos Table -->
    <div class="panel mt-6">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Video Name</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($videos as $video)
                            <tr>
                                <td>{{ $video->video_name }}</td>
                                <td>
                                    @php
                                        $vClass = [
                                            'pending' => 'badge-outline-dark',
                                            'not_edited' => 'badge-outline-danger',
                                            'editing' => 'badge-outline-warning',
                                            'ready' => 'badge-outline-success',
                                            'uploaded' => 'badge-outline-primary',
                                        ][$video->status] ?? 'badge-outline-dark';
                                    @endphp
                                    <span class="badge {{ $vClass }} capitalize text-[10px]">{{ str_replace('_', ' ', $video->status) }}</span>
                                </td>
                                <td>{{ $video->created_at->format('M d, Y') }}</td>
                                <td class="flex items-center justify-center gap-2">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        onclick="document.getElementById('edit-modal-{{ $video->id }}').classList.remove('hidden')">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.marketing.videos.destroy', $video) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div id="edit-modal-{{ $video->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                                <div class="panel w-full max-w-md">
                                    <h3 class="text-lg font-bold mb-4">Edit Video</h3>
                                    <form action="{{ route('admin.marketing.videos.update', $video) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="space-y-4">
                                            <div>
                                                <label class="text-sm font-bold">Video Name</label>
                                                <input type="text" name="video_name" value="{{ $video->video_name }}" class="form-input" required />
                                            </div>
                                            <div>
                                                <label class="text-sm font-bold">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="pending" {{ $video->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="not_edited" {{ $video->status == 'not_edited' ? 'selected' : '' }}>Not Edited</option>
                                                    <option value="editing" {{ $video->status == 'editing' ? 'selected' : '' }}>Editing</option>
                                                    <option value="ready" {{ $video->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                                    <option value="uploaded" {{ $video->status == 'uploaded' ? 'selected' : '' }}>Uploaded</option>
                                                </select>
                                            </div>
                                            <div class="flex gap-2">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('edit-modal-{{ $video->id }}').classList.add('hidden')">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-white-dark">No videos found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $videos->links() }}
            </div>
        </div>
    </div>
@endsection
