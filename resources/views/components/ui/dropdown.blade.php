@props([
    'align' => 'right',
    'width' => '48'
])

@php
    $alignmentClasses = match ($align) {
        'left' => 'origin-top-left start-0',
        'top' => 'origin-bottom-start start-0 bottom-full mb-2',
        'right' => 'origin-top-right end-0',
        default => 'origin-top-right end-0',
    };

    $widthClasses = match ($width) {
        '48' => 'w-48',
        '56' => 'w-56',
        '64' => 'w-64',
        'auto' => 'w-auto min-w-[12rem]',
        default => 'w-48',
    };
@endphp

<div class="relative inline-block text-start" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-2 {{ $widthClasses }} rounded-2xl shadow-xl bg-white dark:bg-slate-900 border border-brand-border dark:border-slate-800 py-1.5 focus:outline-none {{ $alignmentClasses }}"
        style="display: none;"
        @click="open = false"
    >
        <div class="text-slate-700 dark:text-slate-200 text-xs font-medium font-inter">
            {{ $content }}
        </div>
    </div>
</div>
