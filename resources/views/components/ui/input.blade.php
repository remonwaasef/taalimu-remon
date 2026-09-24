@props([
    'type' => 'text',
    'name' => null,
    'id' => null,
    'value' => null,
    'placeholder' => null,
    'icon' => null,
    'iconRight' => null,
    'size' => 'md', // sm, md, lg
    'disabled' => false,
    'readonly' => false,
    'required' => false,
    'error' => false
])

@php
    $inputId = $id ?? $name;
    $hasError = $error || ($name && $errors->has($name));

    $sizes = [
        'sm' => 'h-9 text-xs px-3',
        'md' => 'h-11 text-xs sm:text-sm px-3.5',
        'lg' => 'h-12 text-sm sm:text-base px-4',
    ];

    $paddingLeft = $icon ? ($size === 'sm' ? 'ps-8' : ($size === 'lg' ? 'ps-11' : 'ps-9.5')) : '';
    $paddingRight = $iconRight ? ($size === 'sm' ? 'pe-8' : ($size === 'lg' ? 'pe-11' : 'pe-9.5')) : '';

    $baseClasses = "w-full rounded-[10px] border font-medium text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 bg-white dark:bg-slate-900/90 transition-all duration-150 focus:outline-none disabled:bg-slate-50 dark:disabled:bg-slate-800/60 disabled:text-slate-400 disabled:cursor-not-allowed shadow-xs";

    $stateClasses = $hasError
        ? "border-red-500 dark:border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
        : "border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/15";

    $classes = "{$baseClasses} {$sizes[$size]} {$paddingLeft} {$paddingRight} {$stateClasses}";
@endphp

<div class="relative w-full">
    @if($icon)
        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400 dark:text-slate-500 text-xs sm:text-sm">
            <i class="{{ $icon }}"></i>
        </div>
    @endif

    <input
        type="{{ $type }}"
        @if($name) name="{{ $name }}" @endif
        @if($inputId) id="{{ $inputId }}" @endif
        @if($value !== null) value="{{ is_array(old($name, $value)) ? json_encode(old($name, $value)) : old($name, $value) }}" @elseif($name) value="{{ is_array(old($name)) ? '' : old($name) }}" @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($required) required @endif
        {{ $attributes->merge(['class' => $classes]) }}
    />

    @if($iconRight)
        <div class="absolute inset-y-0 end-0 flex items-center pe-3 pointer-events-none text-slate-400 dark:text-slate-500 text-xs sm:text-sm">
            <i class="{{ $iconRight }}"></i>
        </div>
    @endif
</div>
