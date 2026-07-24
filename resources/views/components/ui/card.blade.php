@props([
    'title' => null,
    'subtitle' => null,
    'action' => null,
    'glass' => false,
    'noPadding' => false
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 border border-brand-border dark:border-slate-800 rounded-2xl shadow-sm transition-all duration-200 hover:shadow-md relative overflow-hidden ' . ($glass ? 'bg-white/80 dark:bg-slate-900/80 backdrop-blur-md' : '')]) }}>
    @if($title || isset($header) || $action)
        <div class="px-6 py-4 border-b border-brand-border dark:border-slate-800 flex items-center justify-between gap-4">
            @if(isset($header))
                {{ $header }}
            @else
                <div>
                    @if($title)
                        <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 font-inter leading-tight">{{ $title }}</h3>
                    @endif
                    @if($subtitle)
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-inter">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif
            
            @if($action)
                <div>{{ $action }}</div>
            @endif
        </div>
    @endif

    <div class="{{ $noPadding ? '' : 'p-6' }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-3.5 bg-slate-50/50 dark:bg-slate-800/50 border-t border-brand-border dark:border-slate-800 text-xs text-slate-500 rounded-b-2xl">
            {{ $footer }}
        </div>
    @endif
</div>
