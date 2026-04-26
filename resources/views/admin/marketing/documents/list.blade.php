@extends('admin.layouts.master')

@section('title', 'Documents - All Applications')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Documents</h2>
    </div>

    <!-- Search & Filter -->
    <div class="panel mt-6">
        <form action="{{ route('admin.marketing.documents.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-bold text-gray-500 mb-1 block">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="App ID or Student..." class="form-input w-full" />
            </div>
            <div class="w-32">
                <label class="text-xs font-bold text-gray-500 mb-1 block">SOP</label>
                <select name="sop_status" class="form-select w-full">
                    <option value="">All</option>
                    <option value="not_received" {{ request('sop_status') == 'not_received' ? 'selected' : '' }}>Not Received</option>
                    <option value="received" {{ request('sop_status') == 'received' ? 'selected' : '' }}>Received</option>
                    <option value="ready" {{ request('sop_status') == 'ready' ? 'selected' : '' }}>Ready</option>
                    <option value="submitted" {{ request('sop_status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                </select>
            </div>
            <div class="w-32">
                <label class="text-xs font-bold text-gray-500 mb-1 block">CV</label>
                <select name="cv_status" class="form-select w-full">
                    <option value="">All</option>
                    <option value="not_received" {{ request('cv_status') == 'not_received' ? 'selected' : '' }}>Not Received</option>
                    <option value="received" {{ request('cv_status') == 'received' ? 'selected' : '' }}>Received</option>
                    <option value="ready" {{ request('cv_status') == 'ready' ? 'selected' : '' }}>Ready</option>
                    <option value="submitted" {{ request('cv_status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                </select>
            </div>
            <div class="w-32">
                <label class="text-xs font-bold text-gray-500 mb-1 block">CL</label>
                <select name="cl_status" class="form-select w-full">
                    <option value="">All</option>
                    <option value="not_received" {{ request('cl_status') == 'not_received' ? 'selected' : '' }}>Not Received</option>
                    <option value="received" {{ request('cl_status') == 'received' ? 'selected' : '' }}>Received</option>
                    <option value="ready" {{ request('cl_status') == 'ready' ? 'selected' : '' }}>Ready</option>
                    <option value="submitted" {{ request('cl_status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Search</button>
                @if(request()->hasAny(['search', 'sop_status', 'cv_status', 'cl_status']))
                    <a href="{{ route('admin.marketing.documents.index') }}" class="btn btn-outline-secondary">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Applications List -->
    <div class="panel mt-6">
        <div class="overflow-x-auto">
            <table class="table-hover w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="text-left py-3 px-2">Application ID</th>
                        <th class="text-left py-3 px-2">Student</th>
                        <th class="text-center py-3 px-2">SOP</th>
                        <th class="text-center py-3 px-2">CV</th>
                        <th class="text-center py-3 px-2">CL</th>
                        <th class="text-right py-3 px-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                        @php
                            $docStatus = [];
                            foreach ($app->documents as $doc) {
                                $docStatus[$doc->document_type] = $doc;
                            }
                        @endphp
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="py-3 px-2 font-medium">{{ $app->application_id }}</td>
                            <td class="py-3 px-2">{{ $app->student->full_name ?? 'Unknown' }}</td>
                            <td class="py-3 px-2 text-center">
                                @php
                                    $sop = $docStatus['sop'] ?? null;
                                    $sopClass = $sop ? $sop->getStatusClass() : 'badge-outline-dark';
                                    $sopLabel = $sop ? $sop->getStatusLabel() : 'Not Set';
                                @endphp
                                <span class="badge {{ $sopClass }} text-xs">{{ $sopLabel }}</span>
                            </td>
                            <td class="py-3 px-2 text-center">
                                @php
                                    $cv = $docStatus['cv'] ?? null;
                                    $cvClass = $cv ? $cv->getStatusClass() : 'badge-outline-dark';
                                    $cvLabel = $cv ? $cv->getStatusLabel() : 'Not Set';
                                @endphp
                                <span class="badge {{ $cvClass }} text-xs">{{ $cvLabel }}</span>
                            </td>
                            <td class="py-3 px-2 text-center">
                                @php
                                    $cl = $docStatus['cl'] ?? null;
                                    $clClass = $cl ? $cl->getStatusClass() : 'badge-outline-dark';
                                    $clLabel = $cl ? $cl->getStatusLabel() : 'Not Set';
                                @endphp
                                <span class="badge {{ $clClass }} text-xs">{{ $clLabel }}</span>
                            </td>
                            <td class="py-3 px-2 text-right">
                                <a href="{{ route('admin.marketing.documents.index', ['application_id' => $app->id]) }}" class="btn btn-sm btn-outline-primary">Update</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-500">
                                @if(request()->hasAny(['search', 'sop_status', 'cv_status', 'cl_status']))
                                    No applications match your filters.
                                @else
                                    No applications found.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $applications->links() }}
        </div>
    </div>
@endsection
