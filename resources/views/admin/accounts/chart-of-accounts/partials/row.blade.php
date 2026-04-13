@php
    $bgClass =
        $depth === 0 ? 'bg-white-light/20 dark:bg-dark/10' : ($depth === 1 ? 'bg-white-light/10 dark:bg-dark/5' : '');
    $fontClass =
        $depth === 0
            ? 'font-bold text-primary text-xs uppercase'
            : ($depth === 1
                ? 'font-semibold text-sm'
                : 'text-sm');
@endphp

<tr class="{{ $bgClass }} {{ $fontClass }} group transition-all duration-300">
    <td class="font-mono text-[10px] w-24">{{ $account->code }}</td>
    <td class="flex items-center">
        <!-- Indentation -->
        @for ($i = 0; $i < $depth; $i++)
            <div class="h-4 w-5 border-l border-gray-300 dark:border-gray-600 ltr:ml-2 rtl:mr-2"></div>
        @endfor

        @if ($depth > 0)
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
        @if ($account->is_active)
            <span class="badge badge-outline-success font-bold text-[10px]">Active</span>
        @else
            <span class="badge badge-outline-danger font-bold text-[10px]">Disabled</span>
        @endif
    </td>
    <td class="text-center">
        <div class="flex items-center justify-center gap-2">
            <!-- Edit -->
            <a href="{{ route('admin.chart-of-accounts.edit', $account) }}"
                class="btn btn-sm btn-outline-primary text-[10px]">
                Edit
            </a>

            <!-- Toggle Status -->
            <form action="{{ route('admin.chart-of-accounts.status', $account) }}" method="POST">
                @csrf
                <button type="submit"
                    class="btn btn-sm {{ $account->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} text-[10px]">
                    {{ $account->is_active ? 'Disable' : 'Activate' }}
                </button>
            </form>

            @if (!$account->is_default)
                <!-- Delete -->
                <form action="{{ route('admin.chart-of-accounts.destroy', $account) }}" method="POST"
                    onsubmit="return confirm('Deleting this head is irreversible. Continue?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger text-[10px]">
                        Delete
                    </button>
                </form>
            @else
                <span class="text-[10px] text-blue-500 font-bold px-1 italic">System</span>
            @endif
        </div>
    </td>
</tr>

@foreach ($account->children as $child)
    @include('admin.accounts.chart-of-accounts.partials.row', ['account' => $child, 'depth' => $depth + 1])
@endforeach
