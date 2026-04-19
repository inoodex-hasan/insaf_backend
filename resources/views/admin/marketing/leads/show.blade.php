@extends('admin.layouts.master')

@section('title', 'Lead Details - ' . $lead->student_name)

@section('content')
    <div>
        <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
            <h2 class="text-xl font-semibold uppercase">Lead Details</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.marketing.leads.index') }}" class="btn btn-outline-primary">Back to List</a>
                @can('*marketing')
                    <a href="{{ route('admin.marketing.leads.edit', $lead->id) }}" class="btn btn-primary">Edit Lead</a>
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Student Information -->
            <div class="lg:col-span-2 space-y-6">
                <div class="panel">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg">Student Information</h5>
                        <span
                            class="badge @if ($lead->status == 'pending') badge-outline-warning @elseif($lead->status == 'interested') badge-outline-success @elseif($lead->status == 'forwarded') badge-outline-info @else badge-outline-danger @endif capitalize">
                            {{ $lead->status }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-white-dark mb-1">Full Name</label>
                            <p class="font-semibold text-gray-light">{{ $lead->student_name }}</p>
                        </div>
                        <div>
                            <label class="text-white-dark mb-1">Phone Number</label>
                            <p class="font-semibold text-gray-light">{{ $lead->phone }}</p>
                        </div>
                        <div>
                            <label class="text-white-dark mb-1">Email Address</label>
                            <p class="font-semibold text-gray-light">{{ $lead->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-white-dark mb-1">Current Education</label>
                            <p class="font-semibold text-gray-light">{{ $lead->current_education ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Preferences & Source -->
                <div class="panel">
                    <h5 class="font-semibold text-lg mb-5">Preferences & Source</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-white-dark mb-1">Preferred Country</label>
                            <p class="font-semibold text-gray-light">{{ $lead->country->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-white-dark mb-1">Preferred Course</label>
                            <p class="font-semibold text-gray-light">{{ $lead->course->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-white-dark mb-1">Contact Source</label>
                            <p class="font-semibold text-gray-light">
                                <span class="badge badge-outline-primary">{{ $lead->source }}</span>
                            </p>
                        </div>
                        <div>
                            <label class="text-white-dark mb-1">Follow-up Date</label>
                            @php($followUpTimeline = $lead->follow_up_timeline)
                            @if($followUpTimeline->isNotEmpty())
                                @php($currentFollowUp = $followUpTimeline->last())
                                <p class="font-semibold {{ $currentFollowUp['date']->isPast() ? 'text-danger' : 'text-gray-light' }}">
                                    {{ $currentFollowUp['date']->format('M d, Y') }}
                                </p>
                            @else
                                <p class="font-semibold text-gray-light">N/A</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg">Follow-up History</h5>
                        <span class="text-xs text-white-dark">{{ $followUpTimeline->count() }} record(s)</span>
                    </div>

                    @if($followUpTimeline->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($followUpTimeline->reverse()->values() as $index => $followUp)
                                <div class="rounded-lg border border-white-light p-4 dark:border-[#1b2e4b]">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <p class="font-semibold {{ $followUp['date']->isPast() ? 'text-danger' : 'text-gray-light' }}">
                                                {{ $followUp['date']->format('M d, Y') }}
                                            </p>
                                            <p class="text-xs text-white-dark">
                                                {{ $index === 0 ? 'Latest follow-up' : 'Previous follow-up' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-3 rounded-md bg-black/10 p-3">
                                        <p class="whitespace-pre-wrap text-sm text-gray-light">
                                            {{ filled($followUp['notes']) ? $followUp['notes'] : 'No note saved for this follow-up.' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-lg bg-black/10 p-4">
                            <p class="text-gray-light">No follow-up history available.</p>
                        </div>
                    @endif
                </div>

                <!-- Internal Notes -->
                <div class="panel">
                    <h5 class="font-semibold text-lg mb-5">Internal Notes</h5>
                    <div class="bg-black/10 p-4 rounded-lg min-h-[100px]">
                        <p class="whitespace-pre-wrap text-gray-light">{{ $lead->notes ?? 'No notes available.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Meta Information -->
            <div class="space-y-6">
                <div class="panel">
                    <h5 class="font-semibold text-lg mb-5">System Tracking</h5>
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-white-dark">Collected By:</span>
                            <span class="font-semibold">{{ $lead->creator->name ?? 'System' }}</span>
                        </div>
                        {{-- <div class="flex justify-between">
                            <span class="text-white-dark">Assigned Consultant:</span>
                            <span class="font-semibold">{{ $lead->consultant->name ?? 'Unassigned' }}</span>
                        </div> --}}
                        <hr class="border-white-light dark:border-[#1b2e4b]">
                        <div class="flex justify-between">
                            <span class="text-white-dark">Created At:</span>
                            <span>{{ $lead->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white-dark">Last Updated:</span>
                            <span>{{ $lead->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                @can('*marketing')
                    <div class="panel border-danger">
                        <h5 class="font-semibold text-lg mb-5 text-danger">Danger Zone</h5>
                        <p class="text-xs text-white-dark mb-4">Deleting this lead will remove all associated data permanently.
                        </p>
                        <form action="{{ route('admin.marketing.leads.destroy', $lead->id) }}" method="POST"
                            onsubmit="return confirm('Are you absolutely sure? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-full">Delete Lead</button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
