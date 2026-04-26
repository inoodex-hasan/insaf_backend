@extends('admin.layouts.master')

@section('title', 'Documents')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Update Documents</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.marketing.documents.index') }}" class="btn btn-outline-secondary">
                Back
            </a>
            <!-- @if($selectedApplication)
                <a href="{{ route('admin.applications.show', $selectedApplication->id) }}" class="btn btn-outline-info">
                    Back to Application
                </a>
            @endif -->
        </div>
    </div>

    <!-- Application Selector -->
    <div class="panel mt-6">
        <form action="{{ route('admin.marketing.documents.index') }}" method="GET">
            <div class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[300px]">
                    <label class="text-sm font-bold mb-1 block">Change Application</label>
                    <select name="application_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Choose an application --</option>
                        @foreach($applications as $app)
                            <option value="{{ $app->id }}" {{ request('application_id') == $app->id ? 'selected' : '' }}>
                                {{ $app->application_id }} - {{ $app->student->full_name ?? 'Unknown' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    @if($selectedApplication)
        <!-- Document Status Form -->
        <div class="panel mt-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">
                    Application: {{ $selectedApplication->application_id }}
                    <span class="text-sm font-normal text-gray-500">({{ $selectedApplication->student->full_name ?? 'Unknown' }})</span>
                </h3>
            </div>

            <form action="{{ route('admin.marketing.documents.store') }}" method="POST">
                @csrf
                <input type="hidden" name="application_id" value="{{ $selectedApplication->id }}">

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left py-3 px-2">Document Type</th>
                                <th class="text-left py-3 px-2 w-1/3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['sop' => 'SOP', 'cv' => 'CV', 'cl' => 'CL'] as $type => $label)
                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                    <td class="py-4 px-2">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $typeClass = [
                                                    'sop' => 'bg-info/10 text-info',
                                                    'cv' => 'bg-success/10 text-success',
                                                    'cl' => 'bg-warning/10 text-warning',
                                                ][$type];
                                            @endphp
                                            <div class="h-10 w-10 rounded-lg {{ $typeClass }} flex items-center justify-center font-bold text-sm">
                                                {{ $label }}
                                            </div>
                                            <span class="font-semibold">{{ $label }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-2">
                                        <select name="documents[{{ $type }}][status]" class="form-select w-full">
                                            @php
                                                $currentStatus = $documents[$type]->status ?? 'pending';
                                            @endphp
                                            <!-- <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }}>Pending</option> -->
                                            <option value="not_received" {{ $currentStatus == 'not_received' ? 'selected' : '' }}>Not Received</option>
                                            <option value="received" {{ $currentStatus == 'received' ? 'selected' : '' }}>Received</option>
                                            <option value="ready" {{ $currentStatus == 'ready' ? 'selected' : '' }}>Ready</option>
                                            <option value="submitted" {{ $currentStatus == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    @else
        <div class="panel mt-6 text-center py-10 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p>Select an application to manage document statuses</p>
        </div>
    @endif
@endsection
