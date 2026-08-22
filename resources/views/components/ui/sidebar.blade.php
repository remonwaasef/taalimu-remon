@props([
    'logo' => null,
    'brandName' => 'Taalimu',
    'homeUrl' => '/'
])

<aside
    class="sidebar-container bg-white dark:bg-slate-900 border-e border-brand-border dark:border-slate-800 flex flex-col justify-between h-screen h-dvh sticky top-0 z-30 font-inter transition-all duration-300 shrink-0 w-64 select-none"
    :class="collapsed ? 'w-20' : 'w-64'"
>
    <!-- Brand Header -->
    <div class="flex-1 flex flex-col min-h-0">
        <div class="h-16 px-5 flex items-center justify-between border-b border-brand-border dark:border-slate-800 shrink-0 overflow-hidden">
            <a href="{{ $homeUrl }}" class="flex items-center gap-3 group min-w-0">
                @if($logo)
                    <img src="{{ $logo }}" alt="Logo" class="h-8 w-8 object-contain shrink-0 rounded-xl">
                @else
                    <div class="w-8 h-8 rounded-xl bg-brand-primary text-white font-black flex items-center justify-center text-sm shadow-sm shadow-brand-primary/30 shrink-0">
                        {{ mb_substr($brandName, 0, 1) }}
                    </div>
                @endif
                <span
                    x-show="!collapsed"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-x-2 rtl:translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="font-extrabold text-sm text-slate-900 dark:text-slate-100 tracking-tight font-inter truncate"
                >
                    {{ $brandName }}
                </span>
            </a>
        </div>

        <!-- Navigation Menu Links -->
        <nav class="flex-1 p-3 space-y-1 overflow-y-auto scrollbar-none">
            {{ $slot }}
        </nav>
    </div>

    <!-- Footer Profile & Bottom Slot -->
    @if(isset($footer))
        <div class="p-3 border-t border-brand-border dark:border-slate-800 shrink-0 bg-slate-50/50 dark:bg-slate-900/50">
            {{ $footer }}
        </div>
    @endif
</aside>
