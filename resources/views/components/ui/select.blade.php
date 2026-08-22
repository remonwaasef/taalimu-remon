@props([
    'name' => null,
    'id' => null,
    'size' => 'md',
    'disabled' => false,
    'required' => false,
    'placeholder' => null,
    'error' => false
])

@php
    $selectId = $id ?? $name;
    $hasError = $error || ($name && $errors->has($name));

    $sizes = [
        'sm' => 'h-8 text-xs ps-3 pe-8',
        'md' => 'h-10 text-xs sm:text-sm ps-3.5 pe-9',
        'lg' => 'h-12 text-sm sm:text-base ps-4 pe-10',
    ];

    $baseClasses = "w-full rounded-xl border font-medium text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900/90 transition-all duration-150 focus:outline-none disabled:bg-slate-50 dark:disabled:bg-slate-800/60 disabled:text-slate-400 disabled:cursor-not-allowed shadow-xs appearance-none cursor-pointer";

    $stateClasses = $hasError
        ? "border-red-400 dark:border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
        : "border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20";

    $classes = "{$baseClasses} {$sizes[$size]} {$stateClasses}";
@endphp

<div class="relative w-full">
    <select
        @if($name) name="{{ $name }}" @endif
        @if($selectId) id="{{ $selectId }}" @endif
        @if($disabled) disabled @endif
        @if($required) required @endif
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if($placeholder)
            <option value="" disabled selected>{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>

    <div class="absolute inset-y-0 end-0 flex items-center pe-3 pointer-events-none text-slate-400 dark:text-slate-500 text-xs">
        <i class="fas fa-chevron-down"></i>
    </div>
</div>
