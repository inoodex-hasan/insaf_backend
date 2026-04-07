@extends('admin.layouts.master')

@section('title', 'Journal Entries')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Journal Ledger</h2>
        <a href="{{ route('admin.journal-entries.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Post New Voucher
        </a>
    </div>

    <div class="panel mt-6">
        <div class="table-responsive">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Transaction Date</th>
                        <th>JV Reference #</th>
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
                                <a href="{{ route('admin.journal-entries.show', $entry) }}">{{ $entry->reference_number }}</a>
                            </td>
                            <td>
                                <span class="badge badge-outline-secondary text-[10px] uppercase">{{ $entry->period->name }}</span>
                            </td>
                            <td class="font-bold">{{ number_format($entry->total_amount, 2) }}</td>
                            <td class="text-xs">{{ $entry->creator->name }}</td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.journal-entries.show', $entry) }}" class="p-2 bg-info-light dark:bg-info/10 text-info rounded-full hover:opacity-70 transition-all">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.journal-entries.destroy', $entry) }}" method="POST" onsubmit="return confirm('Deleting a Journal Voucher is irreversible. Continue?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-danger-light dark:bg-danger/10 text-danger rounded-full hover:opacity-70 transition-all">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18 6L17.1991 18.0129C17.129 19.065 17.0939 19.5911 16.8667 19.99C16.6666 20.3412 16.3648 20.6235 16.0011 20.7998C15.588 21 15.0607 21 14.0062 21H9.99377C8.93927 21 8.41202 21 7.99889 20.7998C7.63517 20.6235 7.33339 20.3412 7.13332 19.99C6.90607 19.5911 6.871 19.065 6.80086 18.0129L6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-400 py-16">
                                <div class="flex flex-col items-center">
                                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.2">
                                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
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
