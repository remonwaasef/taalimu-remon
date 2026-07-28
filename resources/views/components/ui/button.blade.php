@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'icon' => null,
    'iconRight' => null,
    'loading' => false,
    'disabled' => false,
    'href' => null
])

@php
    $baseClasses = "inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed select-none rounded-xl";
    
    $variants = [
        'primary' => 'btn-primary-global bg-brand-primary text-white hover:bg-brand-600 focus:ring-brand-primary shadow-sm hover:shadow active:scale-[0.99]',
        'secondary' => 'btn-secondary-global bg-slate-100 text-slate-700 hover:bg-slate-200 focus:ring-slate-400 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700',
        'outline' => 'btn-outline-global border border-brand-border text-slate-700 hover:bg-slate-50 focus:ring-brand-primary dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800',
        'ghost' => 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:ring-slate-300 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100',
        'danger' => 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500 shadow-sm hover:shadow active:scale-[0.99]',
        'success' => 'bg-emerald-500 text-white hover:bg-emerald-600 focus:ring-emerald-500 shadow-sm hover:shadow active:scale-[0.99]',
    ];

    $sizes = [
        'sm' => 'text-xs px-3 py-1.5 gap-1.5',
        'md' => 'text-sm px-4 py-2 gap-2',
        'lg' => 'text-base px-5 py-2.5 gap-2.5',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon) <i class="{{ $icon }} text-current"></i> @endif
        <span>{{ $slot }}</span>
        @if($iconRight) <i class="{{ $iconRight }} text-current"></i> @endif
    </a>
@else
    <button type="{{ $type }}" {{ $disabled || $loading ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon)
            <i class="{{ $icon }} text-current"></i>
        @endif
        
        <span>{{ $slot }}</span>
        
        @if($iconRight && !$loading)
            <i class="{{ $iconRight }} text-current"></i>
        @endif
    </button>
@endif
