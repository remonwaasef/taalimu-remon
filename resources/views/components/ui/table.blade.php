@props([
    'headers' => [],
    'label' => 'Table',
    'striped' => false,
    'compact' => false,
    'stickyHeader' => false,
    'empty' => false,
    'emptyTitle' => 'No data available',
    'emptyDescription' => 'There are no records to display at this time.',
    'emptyIcon' => 'fas fa-inbox'
])

@php
    $paddingClass = $compact ? 'px-4 py-2.5 text-xs' : 'px-6 py-3.5 text-xs sm:text-sm';
    $thPadding = $compact ? 'px-4 py-2.5' : 'px-6 py-3.5';
@endphp

<div class="w-full overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs relative" role="region" aria-label="{{ $label }}" tabindex="0" data-mobile-cards>
    <table {{ $attributes->merge(['class' => 'w-full text-start text-sm text-slate-600 dark:text-slate-300 border-collapse']) }}>
        @if(count($headers) > 0 || isset($thead))
            <thead class="bg-slate-50/95 dark:bg-slate-800/95 border-b border-slate-200 dark:border-slate-700 text-[11px] uppercase font-bold text-slate-600 dark:text-slate-300 tracking-wider font-inter {{ $stickyHeader ? 'sticky top-0 z-10 backdrop-blur-xs' : '' }}">
                @if(isset($thead))
                    {{ $thead }}
                @else
                    <tr>
                        @foreach($headers as $header)
                            <th scope="col" class="{{ $thPadding }} text-start">{{ $header }}</th>
                        @endforeach
                    </tr>
                @endif
            </thead>
        @endif

        @if($empty)
            <tbody>
                <tr>
                    <td colspan="{{ max(1, count($headers)) }}" class="p-8 text-center">
                        <x-ui.empty-state
                            :title="$emptyTitle"
                            :description="$emptyDescription"
                            :icon="$emptyIcon"
                            size="sm"
                        />
                    </td>
                </tr>
            </tbody>
        @else
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 font-inter {{ $striped ? '[&>tr:nth-child(even)]:bg-slate-50/40 dark:[&>tr:nth-child(even)]:bg-slate-800/30' : '' }} [&>tr]:transition-colors">
                {{ $slot }}
            </tbody>
        @endif

        @if(isset($tfoot))
            <tfoot class="bg-slate-50/80 dark:bg-slate-800/80 border-t border-slate-200 dark:border-slate-800 font-inter">
                {{ $tfoot }}
            </tfoot>
        @endif
    </table>
</div>
