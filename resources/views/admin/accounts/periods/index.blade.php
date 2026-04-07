@extends('admin.layouts.master')

@section('title', 'Accounting Periods')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Accounting Periods</h2>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mt-6">
        <!-- Add Period Form -->
        <div class="panel">
            <h5 class="mb-5 text-lg font-semibold">Setup New Period</h5>
            <form action="{{ route('admin.accounting-periods.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name">Period Name</label>
                    <input type="text" name="name" id="name" class="form-input" placeholder="e.g. FY 2026-27" value="{{ old('name') }}" required>
                </div>
                <div class="mb-4">
                    <label for="type">Period Type</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="fiscal_year" {{ old('type') == 'fiscal_year' ? 'selected' : '' }}>Fiscal Year</option>
                        <option value="monthly" {{ old('type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ old('type') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                    </select>
                </div>
                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-input" value="{{ old('start_date') }}" required>
                    </div>
                    <div>
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-input" value="{{ old('end_date') }}" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="remarks">Remarks (Optional)</label>
                    <textarea name="remarks" id="remarks" rows="3" class="form-textarea" placeholder="Notes about this period...">{{ old('remarks') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary w-full">Create Period</button>
            </form>
        </div>

        <!-- Periods List -->
        <div class="panel lg:col-span-2">
            <h5 class="mb-5 text-lg font-semibold">Accounting Period Log</h5>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Range</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($periods as $period)
                            <tr>
                                <td class="font-semibold">{{ $period->name }}</td>
                                <td class="text-xs">
                                    {{ $period->start_date->format('M d, Y') }} - {{ $period->end_date->format('M d, Y') }}
                                </td>
                                <td>
                                    <span class="badge badge-outline-primary uppercase text-[10px]">
                                        {{ str_replace('_', ' ', $period->type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($period->status == 'open')
                                        <span class="badge badge-outline-success">Open</span>
                                    @else
                                        <span class="badge badge-outline-danger">Closed</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Toggle Status -->
                                        <form action="{{ route('admin.accounting-periods.update', $period) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="{{ $period->status == 'open' ? 'closed' : 'open' }}">
                                            <button type="submit" class="btn btn-sm {{ $period->status == 'open' ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                {{ $period->status == 'open' ? 'Close' : 'Open' }}
                                            </button>
                                        </form>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.accounting-periods.destroy', $period) }}" method="POST" onsubmit="return confirm('Delete this period?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger hover:opacity-70">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-gray-400 py-8">No accounting periods defined yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
