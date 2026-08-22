@props([
    'type' => 'info', // success, error, warning, info
    'title' => null,
    'dismissible' => true,
    'icon' => null
])

@php
    $configs = [
        'success' => [
            'wrapper' => 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800/40 text-emerald-900 dark:text-emerald-100',
            'iconBg' => 'bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400',
            'defaultIcon' => 'fas fa-check-circle',
            'titleColor' => 'text-emerald-950 dark:text-emerald-50'
        ],
        'error' => [
            'wrapper' => 'bg-red-50 dark:bg-red-950/40 border-red-200 dark:border-red-800/40 text-red-900 dark:text-red-100',
            'iconBg' => 'bg-red-100 dark:bg-red-900/60 text-red-600 dark:text-red-400',
            'defaultIcon' => 'fas fa-exclamation-triangle',
            'titleColor' => 'text-red-950 dark:text-red-50'
        ],
        'warning' => [
            'wrapper' => 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800/40 text-amber-900 dark:text-amber-100',
            'iconBg' => 'bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-400',
            'defaultIcon' => 'fas fa-exclamation-circle',
            'titleColor' => 'text-amber-950 dark:text-amber-50'
        ],
        'info' => [
            'wrapper' => 'bg-sky-50 dark:bg-sky-950/40 border-sky-200 dark:border-sky-800/40 text-sky-900 dark:text-sky-100',
            'iconBg' => 'bg-sky-100 dark:bg-sky-900/60 text-sky-600 dark:text-sky-400',
            'defaultIcon' => 'fas fa-info-circle',
            'titleColor' => 'text-sky-950 dark:text-sky-50'
        ],
    ];

    $cfg = $configs[$type] ?? $configs['info'];
    $iconClass = $icon ?? $cfg['defaultIcon'];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    {{ $attributes->merge(['class' => 'p-4 rounded-2xl border flex items-start gap-3.5 relative ' . $cfg['wrapper'] . ' motion-reveal-sm shadow-xs']) }}
    role="alert"
>
    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 text-sm {{ $cfg['iconBg'] }}">
        <i class="{{ $iconClass }}"></i>
    </div>

    <div class="flex-1 min-w-0 pt-0.5 text-xs sm:text-sm">
        @if($title)
            <h4 class="font-bold mb-0.5 leading-tight {{ $cfg['titleColor'] }}">{{ $title }}</h4>
        @endif
        <div class="leading-relaxed opacity-90">
            {{ $slot }}
        </div>
    </div>

    @if($dismissible)
        <button
            type="button"
            @click="show = false"
            class="shrink-0 -me-1 -mt-1 p-1.5 rounded-lg opacity-60 hover:opacity-100 transition-opacity focus:outline-none"
            aria-label="{{ __('Close') }}"
        >
            <i class="fas fa-times text-xs"></i>
        </button>
    @endif
</div>
