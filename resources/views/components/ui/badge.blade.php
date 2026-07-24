@props([
    'variant' => 'neutral',
    'size' => 'md',
    'dot' => false
])

@php
    $base = "inline-flex items-center font-medium rounded-full transition-colors select-none";
    
    $variants = [
        'neutral' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700',
        'brand' => 'bg-brand-50 text-brand-primary dark:bg-brand-900/30 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/40',
        'success' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40',
        'warning' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40',
        'danger' => 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-300 border border-red-200/60 dark:border-red-800/40',
        'info' => 'bg-sky-50 text-sky-700 dark:bg-sky-950/40 dark:text-sky-300 border border-sky-200/60 dark:border-sky-800/40',
    ];

    $dots = [
        'neutral' => 'bg-slate-400',
        'brand' => 'bg-brand-primary',
        'success' => 'bg-emerald-500',
        'warning' => 'bg-amber-500',
        'danger' => 'bg-red-500',
        'info' => 'bg-sky-500',
    ];

    $sizes = [
        'sm' => 'text-[11px] px-2 py-0.5 gap-1',
        'md' => 'text-xs px-2.5 py-1 gap-1.5',
        'lg' => 'text-sm px-3 py-1 gap-2',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['neutral']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dots[$variant] ?? $dots['neutral'] }}"></span>
    @endif
    <span>{{ $slot }}</span>
</span>
