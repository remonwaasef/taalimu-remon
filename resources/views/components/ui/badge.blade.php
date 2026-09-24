@props([
    'variant' => 'neutral', // neutral, brand, success, warning, danger, info, outline
    'size' => 'md',        // sm, md, lg
    'dot' => false,
    'icon' => null,
    'removable' => false,
    'onRemove' => null
])

@php
    $base = "inline-flex items-center font-bold rounded-full transition-colors select-none";
    
    $variants = [
        'neutral' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700',
        'brand' => 'bg-[#EEF0FF] text-[#5B5FEF] dark:bg-brand-900/30 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/40',
        'success' => 'bg-[#DCFCE7] text-[#166534] dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40',
        'warning' => 'bg-[#FEF3C7] text-[#92400E] dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40',
        'danger' => 'bg-[#FEE2E2] text-[#991B1B] dark:bg-red-950/40 dark:text-red-300 border border-red-200/60 dark:border-red-800/40',
        'info' => 'bg-[#E0F2FE] text-[#075985] dark:bg-sky-950/40 dark:text-sky-300 border border-sky-200/60 dark:border-sky-800/40',
        'outline' => 'bg-transparent text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700',
    ];

    $dots = [
        'neutral' => 'bg-slate-400',
        'brand' => 'bg-brand-primary',
        'success' => 'bg-emerald-500',
        'warning' => 'bg-amber-500',
        'danger' => 'bg-red-500',
        'info' => 'bg-sky-500',
        'outline' => 'bg-slate-400',
    ];

    $sizes = [
        'sm' => 'text-[10px] px-2 py-0.5 gap-1',
        'md' => 'text-xs px-2.5 py-0.5 gap-1.5',
        'lg' => 'text-xs sm:text-sm px-3 py-1 gap-2',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['neutral']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $dots[$variant] ?? $dots['neutral'] }}"></span>
    @elseif($icon)
        <i class="{{ $icon }} text-[10px] shrink-0"></i>
    @endif

    <span>{{ $slot }}</span>

    @if($removable)
        <button
            type="button"
            @if($onRemove) @click="{{ $onRemove }}" @endif
            class="ms-0.5 -me-0.5 p-0.5 hover:bg-black/10 dark:hover:bg-white/10 rounded-full transition-colors focus:outline-none"
            aria-label="{{ __('Remove') }}"
        >
            <i class="fas fa-times text-[9px]"></i>
        </button>
    @endif
</span>
