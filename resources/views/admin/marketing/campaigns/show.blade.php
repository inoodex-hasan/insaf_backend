@extends('admin.layouts.master')

@section('title', 'Campaign Assets: ' . $campaign->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <h2 class="text-xl font-semibold uppercase">Campaign Dashboard</h2>
            <span class="badge {{ $campaign->boosting_status === 'on' ? 'badge-outline-primary' : 'badge-outline-dark' }} uppercase text-[10px]">
                Boosting {{ $campaign->boosting_status }}
            </span>
        </div>
        <a href="{{ route('admin.marketing.campaigns.index') }}" class="btn btn-outline-primary gap-2 text-xs">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            Back to Campaigns
        </a>
    </div>

    <!-- Campaign Header Info -->
    <div class="panel mt-6 bg-gradient-to-r from-primary/5 to-transparent border-l-4 border-l-primary">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-primary">{{ $campaign->name }}</h1>
                <p class="text-white-dark mt-1 italic italic">{{ $campaign->notes ?: 'No additional notes provided.' }}</p>
            </div>
            <div class="flex flex-col items-end text-xs text-white-dark">
                <span>Created by: <b>{{ $campaign->creator->name ?? 'N/A' }}</b></span>
                <span>Created on: <b>{{ $campaign->created_at->format('M d, Y @ h:i A') }}</b></span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        
        <!-- Video Assets Panel -->
        <div class="panel" x-data="{ adding: false }">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-info">
                        <polygon points="23 7 16 12 23 17 23 7"></polygon>
                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                    </svg>
                    Video Assets
                </h3>
                <button @click="adding = !adding" class="btn btn-sm btn-info gap-2">
                    <span x-text="adding ? 'Cancel' : '+ Add Video'"></span>
                </button>
            </div>

            <!-- Add Video Form -->
            <div x-show="adding" x-collapse class="mb-6 p-4 bg-info/5 rounded border border-info/20">
                <form action="{{ route('admin.marketing.campaigns.store-video', $campaign->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold uppercase mb-1">Video Name / Description</label>
                            <input type="text" name="video_name" class="form-input text-sm" placeholder="e.g. Student Testimonial - Edited Ver 1" required />
                        </div>
                        <div>
                            <label class="text-xs font-bold uppercase mb-1">Production Status</label>
                            <select name="status" class="form-select text-sm">
                                <option value="not_edited">Not Edited</option>
                                <option value="edited">Edited</option>
                                <option value="upload">Upload</option>
                                <option value="ready">Ready</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info w-full mt-2">Save Video Asset</button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table-hover w-full">
                    <thead>
                        <tr class="!bg-transparent">
                            <th>Video Asset</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaign->videos as $video)
                            <tr>
                                <td class="font-semibold text-sm">{{ $video->video_name }}</td>
                                <td>
                                    @php
                                        $vClass = [
                                            'not_edited' => 'badge-outline-danger',
                                            'edited' => 'badge-outline-warning',
                                            'upload' => 'badge-outline-info',
                                            'ready' => 'badge-outline-success',
                                        ][$video->status] ?? 'badge-outline-dark';
                                    @endphp
                                    <span class="badge {{ $vClass }} capitalize text-[10px]">{{ str_replace('_', ' ', $video->status) }}</span>
                                </td>
                                <td class="text-right">
                                    <form action="{{ route('admin.marketing.campaigns.destroy-video', $video->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger hover:text-red-700" onclick="return confirm('Delete this asset?');">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-white-dark text-xs py-10 italic">No video assets recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Poster Assets Panel -->
        <div class="panel" x-data="{ adding: false }">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-success">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    Poster Assets
                </h3>
                <button @click="adding = !adding" class="btn btn-sm btn-success gap-2">
                    <span x-text="adding ? 'Cancel' : '+ Add Poster'"></span>
                </button>
            </div>

            <!-- Add Poster Form -->
            <div x-show="adding" x-collapse class="mb-6 p-4 bg-success/5 rounded border border-success/20">
                <form action="{{ route('admin.marketing.campaigns.store-poster', $campaign->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold uppercase mb-1">Poster Name / Code</label>
                            <input type="text" name="poster_name" class="form-input text-sm" placeholder="e.g. Scholarship Ad - Instagram Post" required />
                        </div>
                        <div>
                            <label class="text-xs font-bold uppercase mb-1">Production Status</label>
                            <select name="status" class="form-select text-sm">
                                <option value="not_ready">Not Ready</option>
                                <option value="ready">Ready</option>
                                <option value="uploaded">Uploaded</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-full mt-2">Save Poster Asset</button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table-hover w-full">
                    <thead>
                        <tr class="!bg-transparent">
                            <th>Poster Asset</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaign->posters as $poster)
                            <tr>
                                <td class="font-semibold text-sm">{{ $poster->poster_name }}</td>
                                <td>
                                    @php
                                        $pClass = [
                                            'not_ready' => 'badge-outline-danger',
                                            'ready' => 'badge-outline-success',
                                            'uploaded' => 'badge-outline-info',
                                        ][$poster->status] ?? 'badge-outline-dark';
                                    @endphp
                                    <span class="badge {{ $pClass }} capitalize text-[10px]">{{ str_replace('_', ' ', $poster->status) }}</span>
                                </td>
                                <td class="text-right">
                                    <form action="{{ route('admin.marketing.campaigns.destroy-poster', $poster->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger hover:text-red-700" onclick="return confirm('Delete this asset?');">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-white-dark text-xs py-10 italic">No posters/images recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
