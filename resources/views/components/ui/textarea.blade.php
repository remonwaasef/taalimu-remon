@props([
    'name' => null,
    'id' => null,
    'rows' => 3,
    'placeholder' => null,
    'disabled' => false,
    'readonly' => false,
    'required' => false,
    'error' => false
])

@php
    $textareaId = $id ?? $name;
    $hasError = $error || ($name && $errors->has($name));

    $baseClasses = "w-full rounded-xl border p-3.5 text-xs sm:text-sm font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 bg-white dark:bg-slate-900/90 transition-all duration-150 focus:outline-none disabled:bg-slate-50 dark:disabled:bg-slate-800/60 disabled:text-slate-400 disabled:cursor-not-allowed shadow-xs resize-y";

    $stateClasses = $hasError
        ? "border-red-400 dark:border-red-500/60 focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
        : "border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20";

    $classes = "{$baseClasses} {$stateClasses}";
@endphp

<textarea
    @if($name) name="{{ $name }}" @endif
    @if($textareaId) id="{{ $textareaId }}" @endif
    rows="{{ $rows }}"
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($disabled) disabled @endif
    @if($readonly) readonly @endif
    @if($required) required @endif
    {{ $attributes->merge(['class' => $classes]) }}
>{{ $slot }}</textarea>
