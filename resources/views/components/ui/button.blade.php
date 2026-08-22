@props([
    'variant' => 'primary', // primary, secondary, outline, ghost, danger, success, warning, info, link
    'size' => 'md',       // xs, sm, md, lg, icon
    'type' => 'button',
    'icon' => null,
    'iconRight' => null,
    'loading' => false,
    'disabled' => false,
    'href' => null,
    'target' => null
])

@php
    $baseClasses = "inline-flex items-center justify-center font-bold tracking-tight select-none transition-all duration-150 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none active:scale-[0.98]";

    $variants = [
        'primary' => 'bg-brand-primary hover:bg-brand-600 text-white shadow-sm hover:shadow shadow-brand-primary/20 border border-brand-primary focus-visible:ring-2 focus-visible:ring-brand-primary/40 focus-visible:ring-offset-2',
        'secondary' => 'bg-slate-900 hover:bg-slate-800 text-white dark:bg-slate-100 dark:hover:bg-white dark:text-slate-900 shadow-sm border border-transparent focus-visible:ring-2 focus-visible:ring-slate-400',
        'outline' => 'bg-white hover:bg-brand-50 text-slate-700 hover:text-brand-primary dark:bg-slate-900 dark:hover:bg-brand-950/40 dark:text-slate-200 dark:hover:text-brand-300 border border-slate-200 dark:border-slate-700 hover:border-brand-primary/30 dark:hover:border-brand-500/30 shadow-xs focus-visible:ring-2 focus-visible:ring-brand-primary/30',
        'ghost' => 'bg-transparent hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 border border-transparent focus-visible:ring-2 focus-visible:ring-slate-300',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-sm shadow-red-600/20 border border-red-600 focus-visible:ring-2 focus-visible:ring-red-500/40',
        'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm shadow-emerald-600/20 border border-emerald-600 focus-visible:ring-2 focus-visible:ring-emerald-500/40',
        'warning' => 'bg-amber-500 hover:bg-amber-600 text-white shadow-sm shadow-amber-500/20 border border-amber-500 focus-visible:ring-2 focus-visible:ring-amber-400/40',
        'info' => 'bg-sky-600 hover:bg-sky-700 text-white shadow-sm shadow-sky-600/20 border border-sky-600 focus-visible:ring-2 focus-visible:ring-sky-400/40',
        'link' => 'bg-transparent text-brand-primary hover:text-brand-600 hover:underline p-0 h-auto font-semibold focus-visible:ring-1 focus-visible:ring-brand-primary',
    ];

    $sizes = [
        'xs' => 'text-[11px] h-7 px-2.5 rounded-lg gap-1.5',
        'sm' => 'text-xs h-8 px-3 rounded-xl gap-1.5',
        'md' => 'text-xs sm:text-sm h-9.5 px-4 rounded-xl gap-2',
        'lg' => 'text-sm sm:text-base h-11 px-5 rounded-xl gap-2.5',
        'icon' => 'w-9 h-9 p-0 rounded-xl justify-center',
        'icon-sm' => 'w-8 h-8 p-0 rounded-lg justify-center text-xs',
        'icon-lg' => 'w-11 h-11 p-0 rounded-xl justify-center text-base',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" @if($target) target="{{ $target }}" @endif {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon) <i class="{{ $icon }} text-current shrink-0"></i> @endif
        @if(trim($slot)) <span>{{ $slot }}</span> @endif
        @if($iconRight) <i class="{{ $iconRight }} text-current shrink-0"></i> @endif
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $disabled || $loading ? 'disabled' : '' }}
        @if($loading) aria-busy="true" @endif
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if($loading)
            <svg class="animate-spin h-3.5 w-3.5 text-current shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon)
            <i class="{{ $icon }} text-current shrink-0"></i>
        @endif
        
        @if(trim($slot))
            <span>{{ $slot }}</span>
        @endif
        
        @if($iconRight && !$loading)
            <i class="{{ $iconRight }} text-current shrink-0"></i>
        @endif
    </button>
@endif
