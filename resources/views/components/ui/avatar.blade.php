@props([
    'src' => null,
    'name' => 'User',
    'size' => 'md',
    'status' => null
])

@php
    $sizes = [
        'xs' => 'w-6 h-6 text-[10px]',
        'sm' => 'w-8 h-8 text-xs',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-12 h-12 text-base',
        'xl' => 'w-16 h-16 text-lg',
    ];

    $statusSizes = [
        'xs' => 'w-1.5 h-1.5',
        'sm' => 'w-2 h-2',
        'md' => 'w-2.5 h-2.5',
        'lg' => 'w-3 h-3',
        'xl' => 'w-3.5 h-3.5',
    ];

    $statuses = [
        'online' => 'bg-emerald-500',
        'offline' => 'bg-slate-300',
        'busy' => 'bg-red-500',
        'away' => 'bg-amber-500',
    ];

    $initials = collect(explode(' ', $name))
        ->map(fn($part) => mb_substr($part, 0, 1))
        ->take(2)
        ->join('');
@endphp

<div class="relative inline-block shrink-0">
    @if($src)
        <img
            src="{{ $src }}"
            alt="{{ $name }}"
            {{ $attributes->merge(['class' => 'rounded-full object-cover ring-2 ring-white dark:ring-slate-800 ' . ($sizes[$size] ?? $sizes['md'])]) }}
        />
    @else
        <div {{ $attributes->merge(['class' => 'rounded-full bg-brand-50 dark:bg-brand-900/40 text-brand-primary dark:text-brand-300 font-bold flex items-center justify-center ring-2 ring-white dark:ring-slate-800 select-none ' . ($sizes[$size] ?? $sizes['md'])]) }}>
            <span>{{ strtoupper($initials ?: 'U') }}</span>
        </div>
    @endif

    @if($status)
        <span class="absolute bottom-0 end-0 rounded-full ring-2 ring-white dark:ring-slate-900 {{ $statusSizes[$size] ?? $statusSizes['md'] }} {{ $statuses[$status] ?? $statuses['offline'] }}"></span>
    @endif
</div>
