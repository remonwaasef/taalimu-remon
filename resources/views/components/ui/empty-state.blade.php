@props([
    'title' => 'No items found',
    'description' => 'Get started by creating a new entry or adjusting your filters.',
    'icon' => 'fas fa-inbox',
    'size' => 'md' // sm, md, lg
])

@php
    $sizes = [
        'sm' => [
            'padding' => 'py-6 px-4',
            'iconBox' => 'w-11 h-11 text-base mb-2.5',
            'title' => 'text-sm',
            'desc' => 'text-[11px] max-w-xs',
        ],
        'md' => [
            'padding' => 'py-12 px-6',
            'iconBox' => 'w-16 h-16 text-2xl mb-4',
            'title' => 'text-base',
            'desc' => 'text-xs max-w-sm',
        ],
        'lg' => [
            'padding' => 'py-16 px-8',
            'iconBox' => 'w-20 h-20 text-3xl mb-5',
            'title' => 'text-lg',
            'desc' => 'text-sm max-w-md',
        ],
    ];

    $sz = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'text-center ' . $sz['padding'] . ' rounded-2xl border border-dashed border-brand-border dark:border-slate-800 bg-slate-50/40 dark:bg-slate-900/40 my-3 font-inter motion-reveal-sm']) }}>
    <div class="{{ $sz['iconBox'] }} rounded-2xl bg-brand-50 dark:bg-brand-900/30 text-brand-primary dark:text-brand-300 flex items-center justify-center mx-auto border border-brand-100 dark:border-brand-800/40 shadow-xs">
        <i class="{{ $icon }}"></i>
    </div>

    <h3 class="{{ $sz['title'] }} font-bold text-slate-900 dark:text-slate-100 tracking-tight">{{ $title }}</h3>
    <p class="{{ $sz['desc'] }} text-slate-500 dark:text-slate-400 mt-1 mx-auto leading-relaxed">{{ $description }}</p>

    @if(isset($action) || isset($secondaryAction))
        <div class="mt-5 flex items-center justify-center gap-3">
            @if(isset($secondaryAction))
                {{ $secondaryAction }}
            @endif
            @if(isset($action))
                {{ $action }}
            @endif
        </div>
    @endif
</div>
