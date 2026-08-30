@props([
    'title' => null,
    'subtitle' => null,
    'action' => null,
    'variant' => 'default', // default, flat, bordered, ghost
    'glass' => false,
    'noPadding' => false,
    'collapsible' => false,
    'collapsed' => false,
    'loading' => false
])

@php
    $variants = [
        'default' => 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs hover:shadow-sm',
        'flat' => 'bg-slate-50 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-800',
        'bordered' => 'bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700',
        'ghost' => 'bg-transparent border border-dashed border-slate-200 dark:border-slate-800',
    ];

    $glassClass = $glass ? 'bg-white/80 dark:bg-slate-900/80 backdrop-blur-md' : '';
    $cardClass = ($variants[$variant] ?? $variants['default']) . ' rounded-2xl transition-all duration-200 relative overflow-hidden font-inter ' . $glassClass;
@endphp

<div
    @if($collapsible) x-data="{ collapsed: @json($collapsed) }" @endif
    {{ $attributes->merge(['class' => $cardClass]) }}
>
    @if($title || isset($header) || $action || $collapsible)
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/40 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-4">
            @if(isset($header))
                {{ $header }}
            @else
                <div class="min-w-0 flex-1">
                    @if($title)
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 tracking-tight leading-tight truncate">{{ $title }}</h3>
                    @endif
                    @if($subtitle)
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif
            
            <div class="flex items-center gap-2 shrink-0">
                @if($action)
                    <div>{{ $action }}</div>
                @endif

                @if($collapsible)
                    <button
                        type="button"
                        @click="collapsed = !collapsed"
                        class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center"
                        aria-label="{{ __('Toggle Card') }}"
                    >
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': collapsed }"></i>
                    </button>
                @endif
            </div>
        </div>
    @endif

    @if($loading)
        <div class="p-6 space-y-4 animate-pulse">
            <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded-lg w-1/3"></div>
            <div class="h-8 bg-slate-200 dark:bg-slate-800 rounded-lg w-1/2"></div>
            <div class="h-3 bg-slate-200 dark:bg-slate-800 rounded-lg w-full"></div>
        </div>
    @else
        <div
            @if($collapsible) x-show="!collapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" @endif
            class="{{ $noPadding ? '' : 'p-6' }}"
        >
            {{ $slot }}
        </div>
    @endif

    @if(isset($footer))
        <div
            @if($collapsible) x-show="!collapsed" @endif
            class="px-6 py-3.5 bg-slate-50/50 dark:bg-slate-800/50 border-t border-brand-border dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400 rounded-b-2xl"
        >
            {{ $footer }}
        </div>
    @endif
</div>
