<!-- Alpine.js Modal Component -->
<!-- Usage: <x-ui.modal id="modal-id" title="Modal Title">Content</x-ui.modal> -->
<!-- Trigger: <button @click="$dispatch('open-modal', { id: 'modal-id' })">Open</button> -->

@props([
    'id',
    'title' => '',
    'size' => 'md', // sm, md, lg, xl, full
    'closeable' => true,
])

<div
    x-data="{
        open: false,
        init() {
            this.$watch('open', (value) => {
                document.body.style.overflow = value ? 'hidden' : '';
            });
            this.$on('open-modal', (event) => {
                if (event.detail.id === this.$id('$el')) {
                    this.open = true;
                }
            });
            this.$on('close-modal', (event) => {
                if (event.detail.id === this.$id('$el') || !event.detail.id) {
                    this.open = false;
                }
            });
        }
    }"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
    @keydown.escape.window="open = false"
>
    <!-- Backdrop -->
    <div
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"
        @click="open = false"
        aria-hidden="true"
    ></div>

    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="w-full bg-white dark:bg-slate-900 rounded-2xl shadow-xl transform overflow-hidden"
            :class="{
                'max-w-sm': size === 'sm',
                'max-w-md': size === 'md',
                'max-w-lg': size === 'lg',
                'max-w-2xl': size === 'xl',
                'max-w-4xl': size === 'full',
            }"
        >
            @if($title)
                <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 id="{{ $id }}-title" class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $title }}</h3>
                    @if($closeable)
                        <button
                            @click="open = false"
                            class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center"
                            aria-label="Close"
                        >
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    @endif
                </div>
            @endif

            <div class="p-4">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>