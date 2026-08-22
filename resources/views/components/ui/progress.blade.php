@props([
    'value' => 0,
    'max' => 100,
    'variant' => 'primary', // primary, success, warning, danger, info
    'size' => 'md',       // sm, md, lg
    'showLabel' => false,
    'label' => null
])

@php
    $percentage = min(100, max(0, $max > 0 ? round(($value / $max) * 100) : 0));

    $variants = [
        'primary' => 'bg-brand-primary',
        'success' => 'bg-emerald-500',
        'warning' => 'bg-amber-500',
        'danger' => 'bg-red-500',
        'info' => 'bg-sky-500',
    ];

    $sizes = [
        'sm' => 'h-1.5',
        'md' => 'h-2.5',
        'lg' => 'h-4',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if($showLabel || $label)
        <div class="flex items-center justify-between text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
            <span>{{ $label ?? '' }}</span>
            <span>{{ $percentage }}%</span>
        </div>
    @endif

    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden {{ $sizes[$size] ?? $sizes['md'] }}">
        <div
            class="{{ $variants[$variant] ?? $variants['primary'] }} h-full rounded-full transition-all duration-500 ease-out"
            style="width: {{ $percentage }}%"
            role="progressbar"
            aria-valuenow="{{ $value }}"
            aria-valuemin="0"
            aria-valuemax="{{ $max }}"
        ></div>
    </div>
</div>
