@extends('admin.layouts.master')

@section('title', 'Bank Reconciliations')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Bank Reconciliations</h2>
        <a href="{{ route('admin.bank-reconciliations.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            New Reconciliation
        </a>
    </div>

    <div class="panel mt-6">
        <div class="table-responsive">
            <table class="table-hover">
                <thead>
                    <tr>
                        <th>Statement Date</th>
                        <th>Account</th>
                        <th class="text-right">Statement Balance</th>
                        <th class="text-right">System Balance</th>
                        <th class="text-right">Difference</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reconciliations as $rec)
                        <tr>
                            <td>{{ $rec->statement_date->format('M d, Y') }}</td>
                            <td class="font-bold text-primary">{{ $rec->account->account_name }}</td>
                            <td class="text-right font-mono">{{ number_format($rec->statement_balance, 2) }}</td>
                            <td class="text-right font-mono">{{ number_format($rec->system_balance, 2) }}</td>
                            <td class="text-right font-mono {{ $rec->difference == 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($rec->difference, 2) }}
                            </td>
                            <td>
                                @if($rec->status == 'closed')
                                    <span class="badge badge-outline-success font-black uppercase text-[10px]">Closed</span>
                                @else
                                    <span class="badge badge-outline-warning font-black uppercase text-[10px]">Open (Draft)</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.bank-reconciliations.show', $rec) }}" class="btn btn-sm btn-outline-primary">
                                        {{ $rec->status == 'closed' ? 'View' : 'Continue' }}
                                    </a>
                                    @if($rec->status != 'closed')
                                        <form action="{{ route('admin.bank-reconciliations.destroy', $rec) }}" method="POST" onsubmit="return confirm('Delete this draft?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-400 py-12">
                                No bank reconciliations found. Start by clicking "New Reconciliation".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $reconciliations->links() }}
        </div>
    </div>
@endsection
