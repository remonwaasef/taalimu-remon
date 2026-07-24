@props([
    'logo' => null,
    'brandName' => 'Taalimu',
    'menu' => []
])

<aside
    x-data="{ open: false }"
    class="w-64 bg-white dark:bg-slate-900 border-e border-brand-border dark:border-slate-800 flex flex-col justify-between h-screen sticky top-0 z-30 font-inter transition-all duration-300 shrink-0"
>
    <!-- Brand Header -->
    <div>
        <div class="h-16 px-6 flex items-center justify-between border-b border-brand-border dark:border-slate-800">
            <a href="/" class="flex items-center gap-3 group">
                @if($logo)
                    <img src="{{ $logo }}" alt="Logo" class="h-8 w-auto">
                @else
                    <div class="w-8 h-8 rounded-xl bg-brand-primary text-white font-black flex items-center justify-center text-sm shadow-md shadow-brand-primary/20">
                        T
                    </div>
                @endif
                <span class="font-extrabold text-base text-slate-900 dark:text-slate-100 tracking-tight font-inter">
                    {{ $brandName }}
                </span>
            </a>
        </div>

        <!-- Navigation Menu Links -->
        <nav class="p-4 space-y-1.5 overflow-y-auto max-h-[calc(100vh-8rem)]">
            {{ $slot }}
        </nav>
    </div>

    <!-- Footer Profile & Bottom Slot -->
    @if(isset($footer))
        <div class="p-4 border-t border-brand-border dark:border-slate-800">
            {{ $footer }}
        </div>
    @endif
</aside>
