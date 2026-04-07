@extends('admin.layouts.master')

@section('title', 'Marketing Campaigns')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Marketing Campaigns</h2>
        @can('*marketing')
            <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
                <a href="{{ route('admin.marketing.campaigns.create') }}" class="btn btn-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    New Campaign
                </a>
            </div>
        @endcan
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.marketing.campaigns.index') }}" method="GET"
                class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search campaign name..." class="form-input ltr:pr-11 rtl:pl-11" />
                    <button type="submit"
                        class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5"
                                opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <div class="flex gap-2">
                    <select name="boosting_status" class="form-select w-auto md:w-auto pr-10">
                        <option value="">Boosting Status</option>
                        <option value="on" {{ request('boosting_status') == 'on' ? 'selected' : '' }}>Boosting ON</option>
                        <option value="off" {{ request('boosting_status') == 'off' ? 'selected' : '' }}>Boosting OFF</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Campaign Name</th>
                            <th>Assets Overview</th>
                            <th>Created By</th>
                            <th>Boosting</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                            <tr>
                                <td>
                                    <div class="font-bold text-lg text-primary">{{ $campaign->name }}</div>
                                    <div class="text-xs text-white-dark">Started: {{ $campaign->created_at->format('M d, Y') }}</div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1 px-2 py-1 bg-info/10 text-info rounded border border-info/20 text-xs font-bold">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                            </svg>
                                            {{ $campaign->videos_count }}
                                        </div>
                                        <div class="flex items-center gap-1 px-2 py-1 bg-success/10 text-success rounded border border-success/20 text-xs font-bold">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                <polyline points="21 15 16 10 5 21"></polyline>
                                            </svg>
                                            {{ $campaign->posters_count }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 uppercase">
                                            {{ substr($campaign->creator->name ?? 'U', 0, 1) }}
                                        </div>
                                        <span class="text-sm">{{ $campaign->creator->name ?? 'System' }}</span>
                                    </div>
                                </td>
                                <td x-data="{ 
                                    status: '{{ $campaign->boosting_status }}',
                                    toggle() {
                                        fetch('{{ route('admin.marketing.campaigns.toggle-boosting', $campaign->id) }}', {
                                            method: 'POST',
                                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                        }).then(res => res.json()).then(data => {
                                            this.status = data.boosting_status;
                                        });
                                    }
                                }">
                                    <label class="relative h-6 w-12 items-center cursor-pointer">
                                        <input type="checkbox" class="peer sr-only" :checked="status === 'on'" @change="toggle()" />
                                        <span class="block h-full w-full rounded-full bg-[#ebedf2] before:absolute before:left-1 before:bottom-1 before:h-4 before:w-4 before:rounded-full before:bg-white before:transition-all before:duration-300 peer-checked:bg-primary peer-checked:before:left-7 dark:bg-dark dark:before:bg-white-dark"></span>
                                    </label>
                                    <span class="text-[10px] font-black uppercase mt-1 block" :class="status === 'on' ? 'text-primary' : 'text-slate-400'">
                                        <span x-text="status === 'on' ? 'Boosting ON' : 'OFF'"></span>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.marketing.campaigns.show', $campaign->id) }}"
                                            class="btn btn-sm btn-outline-info">Manage Assets</a>

                                        <a href="{{ route('admin.marketing.campaigns.edit', $campaign->id) }}"
                                            class="btn btn-sm btn-outline-primary">Edit</a>

                                        <form action="{{ route('admin.marketing.campaigns.destroy', $campaign->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this campaign and all its assets?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10">
                                    <div class="flex flex-col items-center justify-center text-white-dark">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                            <path d="M21 16V8C21 6.89543 20.1046 6 19 6H5C3.89543 6 3 6.89543 3 8V16C3 17.1046 3.89543 18 5 18H19C20.1046 18 21 17.1046 21 16Z" />
                                            <path d="M7 11V13M10 10V14M13 11V13" />
                                        </svg>
                                        <p class="mt-2 font-semibold">No campaigns found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $campaigns->links() }}
            </div>
        </div>
    </div>
@endsection
