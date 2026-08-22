@props([
    'label' => null,
    'name' => null,
    'required' => false,
    'helper' => null,
    'error' => null,
    'inline' => false
])

<div {{ $attributes->merge(['class' => 'form-field ' . ($inline ? 'sm:flex sm:items-center sm:gap-4' : 'space-y-1.5')]) }}>
    @if($label)
        <div class="flex items-center justify-between {{ $inline ? 'sm:w-1/3' : '' }}">
            <label @if($name) for="{{ $name }}" @endif class="block text-xs font-bold text-slate-700 dark:text-slate-200 select-none">
                {{ $label }}
                @if($required)
                    <span class="text-red-500 font-bold ms-0.5" title="{{ __('Required') }}">*</span>
                @endif
            </label>
            @if(isset($labelExtra))
                <span class="text-[11px] text-slate-400">{{ $labelExtra }}</span>
            @endif
        </div>
    @endif

    <div class="{{ $inline ? 'sm:flex-1' : '' }} relative">
        {{ $slot }}

        @if($error || ($name && $errors->has($name)))
            <p class="text-xs text-red-600 dark:text-red-400 font-medium mt-1.5 flex items-center gap-1.5 motion-reveal-sm" role="alert">
                <i class="fas fa-exclamation-circle text-[11px] shrink-0"></i>
                <span>{{ $error ?? $errors->first($name) }}</span>
            </p>
        @elseif($helper)
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1.5 leading-normal">
                {{ $helper }}
            </p>
        @endif
    </div>
</div>
