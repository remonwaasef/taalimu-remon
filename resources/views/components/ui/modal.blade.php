@props([
    'id',
    'title' => null,
    'size' => 'md'
])

@php
    $sizes = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'full' => 'max-w-6xl',
    ];

    $modalSize = $sizes[$size] ?? $sizes['md'];
@endphp

<div
    x-data="{ show: false }"
    x-on:open-modal.window="if ($event.detail === '{{ $id }}') show = true"
    x-on:close-modal.window="if ($event.detail === '{{ $id }}') show = false"
    x-on:keydown.escape.window="show = false"
    x-effect="document.body.style.overflow = show ? 'hidden' : ''"
>
    <template x-teleport="body">
        <div
            x-show="show"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            role="dialog"
            aria-modal="true"
            aria-label="{{ $title ?? 'Modal' }}"
        >
            <!-- Backdrop -->
            <div
                x-show="show"
                x-transition:enter="ease-out duration-250"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="show = false"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
            ></div>

            <!-- Modal Container -->
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
                <div
                    x-show="show"
                    x-transition:enter="ease-out duration-250"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-start shadow-xl transition-all w-full {{ $modalSize }} border border-brand-border dark:border-slate-800 my-auto"
                >
                    @if($title || isset($header))
                        <div class="px-6 py-4 border-b border-brand-border dark:border-slate-800 flex items-center justify-between">
                            @if(isset($header))
                                {{ $header }}
                            @else
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 font-inter">{{ $title }}</h3>
                            @endif
                            
                            <button @click="show = false" type="button" aria-label="Close" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <i class="fas fa-times text-base"></i>
                            </button>
                        </div>
                    @endif

                    <div class="p-6">
                        {{ $slot }}
                    </div>

                    @if(isset($footer))
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-brand-border dark:border-slate-800 flex items-center justify-end gap-3">
                            {{ $footer }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </template>
</div>
