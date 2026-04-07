@php
    $bgClass = $depth === 0 ? 'bg-white-light/20 dark:bg-dark/10' : ($depth === 1 ? 'bg-white-light/10 dark:bg-dark/5' : '');
    $fontClass = $depth === 0 ? 'font-bold text-primary text-xs uppercase' : ($depth === 1 ? 'font-semibold text-sm' : 'text-sm');
@endphp

<tr class="{{ $bgClass }} {{ $fontClass }} group transition-all duration-300">
    <td class="font-mono text-[10px] w-24">{{ $account->code }}</td>
    <td class="flex items-center">
        <!-- Indentation -->
        @for($i = 0; $i < $depth; $i++)
            <div class="h-4 w-5 border-l border-gray-300 dark:border-gray-600 ltr:ml-2 rtl:mr-2"></div>
        @endfor

        @if($depth > 0)
            <div class="h-4 w-3 border-b border-gray-300 dark:border-gray-600 ltr:mr-2 rtl:ml-2"></div>
        @endif

        <span class="{{ $account->is_default ? 'text-blue-600 dark:text-blue-400' : '' }}">
            {{ $account->name }}
        </span>
    </td>
    <td>
        <span class="badge badge-outline-secondary text-[10px] uppercase font-bold px-2 whitespace-nowrap">
            {{ $account->type }}
        </span>
    </td>
    <td>
        @if($account->is_active)
            <span class="badge badge-outline-success font-bold text-[10px]">Active</span>
        @else
            <span class="badge badge-outline-danger font-bold text-[10px]">Disabled</span>
        @endif
    </td>
    <td class="text-center">
        <div class="flex items-center justify-center gap-3">
            <form action="{{ route('admin.chart-of-accounts.status', $account) }}" method="POST">
                @csrf
                <button type="submit" class="hover:text-info transition-colors" title="{{ $account->is_active ? 'Deactivate' : 'Activate' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" opacity="0.5" />
                        <path d="M12 7V12L15 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </form>

            @if(!$account->is_default)
                <form action="{{ route('admin.chart-of-accounts.destroy', $account) }}" method="POST" onsubmit="return confirm('Deleting this head is irreversible. Continue?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="hover:text-danger transition-colors text-gray-400 group-hover:text-danger">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6L17.1991 18.0129C17.129 19.065 17.0939 19.5911 16.8667 19.99C16.6666 20.3412 16.3648 20.6235 16.0011 20.7998C15.588 21 15.0607 21 14.0062 21H9.99377C8.93927 21 8.41202 21 7.99889 20.7998C7.63517 20.6235 7.33339 20.3412 7.13332 19.99C6.90607 19.5911 6.871 19.065 6.80086 18.0129L6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M10.1143 3H13.8857C14.1741 3 14.3183 3 14.4373 3.03373C14.8872 3.16116 15.2389 3.51275 15.3663 3.96274C15.4 4.08174 15.4 4.2259 15.4 4.51429V6H8.60001V4.51429C8.60001 4.2259 8.60001 4.08174 8.63271 3.96274C8.76014 3.51275 9.11172 3.16116 9.56171 3.03373C9.68071 3 9.82487 3 10.1143 3Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </form>
            @else
                <span class="text-[10px] text-blue-500 font-bold px-1 italic">System Default</span>
            @endif
        </div>
    </td>
</tr>

@foreach($account->children as $child)
    @include('admin.accounts.chart-of-accounts.partials.row', ['account' => $child, 'depth' => $depth + 1])
@endforeach
