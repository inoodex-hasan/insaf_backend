@extends('admin.layouts.master')

@section('title', 'Journal Entries')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Journal Ledger</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.journal-entries.report', array_merge(request()->all(), ['output' => 'preview'])) }}" target="_blank" class="btn btn-outline-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Preview
            </a>
            <a href="{{ route('admin.journal-entries.report', array_merge(request()->all(), ['output' => 'download'])) }}" class="btn btn-outline-success gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Download
            </a>
            <a href="{{ route('admin.journal-entries.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Create Voucher
            </a>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="mt-4 mb-5">
        <form method="GET" action="{{ route('admin.journal-entries.index') }}" class="flex flex-col md:flex-row flex-wrap gap-4 mb-4">
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input flex-1 min-w-[150px]" title="Start Date">
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input flex-1 min-w-[150px]" title="End Date">
            
            <input type="text" name="reference_number" value="{{ request('reference_number') }}" placeholder="Reference No..." class="form-input flex-1 min-w-[150px]">
            
            <input type="text" name="student_name" value="{{ request('student_name') }}" placeholder="Student Name..." class="form-input flex-1 min-w-[150px]">
            
            <select name="period_id" class="form-select flex-1 min-w-[150px]">
                <option value="">All Periods</option>
                @foreach($periods as $period)
                    <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>{{ $period->name }}</option>
                @endforeach
            </select>
            
            <select name="status" class="form-select flex-1 min-w-[150px]">
                <option value="">All Status</option>
                <option value="posted" {{ request('status') == 'posted' ? 'selected' : '' }}>Posted</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="reversed" {{ request('status') == 'reversed' ? 'selected' : '' }}>Reversed</option>
            </select>
     
            <div class="flex gap-2 whitespace-nowrap md:ml-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.journal-entries.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="panel mt-4">
        <div class="table-responsive">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Transaction Date</th>
                        <th>Reference</th>
                        <th>Student</th>
                        <th>Period</th>
                        <th>Voucher Amount</th>
                        <th>Posted By</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                        <tr>
                            <td class="whitespace-nowrap text-xs font-semibold">{{ $entry->date->format('M d, Y') }}</td>
                            <td class="font-bold underline text-primary">
                                <a
                                    href="{{ route('admin.journal-entries.show', $entry) }}">{{ $entry->reference_number }}</a>
                            </td>
                            <td class="font-xs">
                                @if ($entry->application)
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-black dark:text-white">{{ $entry->application->student->first_name }}
                                            {{ $entry->application->student->last_name }}</span>
                                        <span
                                            class="text-[10px] text-gray-500 uppercase">{{ $entry->application->application_id }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic text-[11px]">General Entry</span>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="badge badge-outline-secondary text-[10px] uppercase">{{ $entry->period->name }}</span>
                            </td>
                            <td class="font-bold">{{ number_format($entry->total_amount, 2) }}</td>
                            <td class="text-xs">{{ $entry->creator->name }}</td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.journal-entries.show', $entry) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                    <form action="{{ route('admin.journal-entries.destroy', $entry) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-400 py-16">
                                <div class="flex flex-col items-center">
                                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1" opacity="0.2">
                                        <path
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2">No journal vouchers recorded yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $entries->links() }}
        </div>
    </div>
@endsection
