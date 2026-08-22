<div
    x-data="{
        toasts: [],
        add(toast) {
            const id = Date.now() + Math.random();
            const duration = toast.duration || 4000;
            const newToast = { id, ...toast };
            this.toasts.push(newToast);
            if (duration > 0) {
                setTimeout(() => this.remove(id), duration);
            }
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    x-on:toast.window="add($event.detail)"
    class="fixed bottom-5 end-5 z-[70] flex flex-col gap-2.5 max-w-sm w-full pointer-events-none px-4 sm:px-0"
    aria-live="polite"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl text-slate-800 dark:text-slate-100 font-inter"
            :class="{
                'border-s-4 border-s-emerald-500': toast.type === 'success',
                'border-s-4 border-s-red-500': toast.type === 'error',
                'border-s-4 border-s-amber-500': toast.type === 'warning',
                'border-s-4 border-s-sky-500': toast.type === 'info' || !toast.type,
            }"
        >
            <div
                class="w-7 h-7 rounded-xl flex items-center justify-center shrink-0 text-xs mt-0.5"
                :class="{
                    'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400': toast.type === 'success',
                    'bg-red-50 text-red-600 dark:bg-red-950/50 dark:text-red-400': toast.type === 'error',
                    'bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400': toast.type === 'warning',
                    'bg-sky-50 text-sky-600 dark:bg-sky-950/50 dark:text-sky-400': toast.type === 'info' || !toast.type,
                }"
            >
                <i :class="toast.icon || (toast.type === 'success' ? 'fas fa-check' : toast.type === 'error' ? 'fas fa-times' : toast.type === 'warning' ? 'fas fa-exclamation' : 'fas fa-info')"></i>
            </div>

            <div class="flex-1 min-w-0">
                <template x-if="toast.title">
                    <h5 class="text-xs font-bold leading-tight" x-text="toast.title"></h5>
                </template>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-snug mt-0.5" x-text="toast.message"></p>
            </div>

            <button
                type="button"
                @click="remove(toast.id)"
                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-lg transition-colors shrink-0"
                aria-label="{{ __('Close') }}"
            >
                <i class="fas fa-times text-[10px]"></i>
            </button>
        </div>
    </template>
</div>
