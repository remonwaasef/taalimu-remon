@props([
    'id',
    'title' => null,
    'size' => 'md' // sm, md, lg, xl
])

@php
    $sizes = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-2xl',
    ];
@endphp

<div
    x-data="{ show: false }"
    x-on:open-drawer.window="if ($event.detail === '{{ $id }}') show = true"
    x-on:close-drawer.window="if ($event.detail === '{{ $id }}') show = false"
    x-on:keydown.escape.window="show = false"
    x-effect="document.body.style.overflow = show ? 'hidden' : ''"
>
    <template x-teleport="body">
        <div
            x-show="show"
            x-cloak
            class="fixed inset-0 z-50 overflow-hidden"
            role="dialog"
            aria-modal="true"
            aria-label="{{ $title ?? 'Drawer' }}"
        >
            <!-- Backdrop -->
            <div
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="show = false"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
            ></div>

            <!-- Drawer Container (slides from end / right in LTR, left in RTL) -->
            <div class="fixed inset-y-0 end-0 flex max-w-full">
                <div
                    x-show="show"
                    x-transition:enter="transform transition ease-out duration-300"
                    x-transition:enter-start="translate-x-full rtl:-translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full rtl:-translate-x-full"
                    class="w-screen {{ $sizes[$size] ?? $sizes['md'] }} bg-white dark:bg-slate-900 shadow-2xl border-s border-slate-200 dark:border-slate-800 flex flex-col"
                >
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        @if($title || isset($header))
                            @if(isset($header))
                                {{ $header }}
                            @else
                                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 tracking-tight">{{ $title }}</h3>
                            @endif
                        @else
                            <div></div>
                        @endif

                        <button
                            @click="show = false"
                            type="button"
                            aria-label="{{ __('Close') }}"
                            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none"
                        >
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="flex-1 overflow-y-auto p-6">
                        {{ $slot }}
                    </div>

                    <!-- Footer -->
                    @if(isset($footer))
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-3">
                            {{ $footer }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </template>
</div>
