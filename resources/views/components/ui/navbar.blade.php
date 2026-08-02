@props([
    'title' => null,
    'user' => null
])

<header class="h-16 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-brand-border dark:border-slate-800 sticky top-0 z-20 px-4 sm:px-6 flex items-center justify-between gap-4 font-inter">
    <!-- Left Section: Command Palette Trigger & Search -->
    <div class="flex items-center gap-4 flex-1">
        <button
            type="button"
            @click="$dispatch('open-command-palette')"
            class="w-full max-w-md h-9 ps-3.5 pe-4 bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 rounded-xl text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-all flex items-center justify-between group text-start"
        >
            <span class="flex items-center gap-2">
                <i class="fas fa-search text-xs"></i>
                <span class="truncate">Search or press Ctrl+K...</span>
            </span>
            <kbd class="hidden sm:inline-flex items-center gap-0.5 px-2 py-0.5 text-[10px] font-mono font-medium text-slate-400 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md shadow-xs">
                ⌘K
            </kbd>
        </button>
    </div>

    <!-- Right Section: Theme Switcher, Language Switcher, Notifications & Profile -->
    <div class="flex items-center gap-2 sm:gap-3">
        <!-- Light / Dark / System Theme Toggle -->
        <x-ui.dropdown align="right" width="48">
            <x-slot name="trigger">
                <button type="button" class="w-9 h-9 rounded-xl border border-brand-border dark:border-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center justify-center">
                    <i class="fas fa-sun dark:hidden text-xs"></i>
                    <i class="fas fa-moon hidden dark:block text-xs text-indigo-400"></i>
                </button>
            </x-slot>

            <x-slot name="content">
                <button @click="theme = 'light'; localStorage.setItem('theme', 'light'); document.documentElement.classList.remove('dark')" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 text-start">
                    <i class="fas fa-sun text-amber-500 w-4"></i> Light Mode
                </button>
                <button @click="theme = 'dark'; localStorage.setItem('theme', 'dark'); document.documentElement.classList.add('dark')" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 text-start">
                    <i class="fas fa-moon text-indigo-400 w-4"></i> Dark Mode
                </button>
                <button @click="localStorage.removeItem('theme'); location.reload()" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 text-start">
                    <i class="fas fa-desktop text-slate-400 w-4"></i> System Default
                </button>
            </x-slot>
        </x-ui.dropdown>

        <!-- Language Switcher -->
        <x-ui.dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="h-9 px-2.5 rounded-xl border border-brand-border dark:border-slate-800 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center gap-1.5">
                    <i class="fas fa-globe text-slate-400"></i>
                    <span>{{ strtoupper(app()->getLocale()) }}</span>
                </button>
            </x-slot>

            <x-slot name="content">
                <a href="{{ route('lang.switch', 'ar') }}" class="flex items-center px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 font-arabic">العربية</a>
                <a href="{{ route('lang.switch', 'en') }}" class="flex items-center px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">English</a>
                <a href="{{ route('lang.switch', 'fr') }}" class="flex items-center px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">Français</a>
            </x-slot>
        </x-ui.dropdown>

        <!-- Notification Bell Dropdown -->
        <x-ui.dropdown align="right" width="64">
            <x-slot name="trigger">
                <button type="button" class="w-9 h-9 rounded-xl border border-brand-border dark:border-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center justify-center relative">
                    <i class="fas fa-bell text-xs"></i>
                    <span class="absolute top-1.5 end-1.5 w-2 h-2 bg-brand-primary rounded-full ring-2 ring-white dark:ring-slate-900"></span>
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="px-4 py-2.5 border-b border-brand-border dark:border-slate-800 flex items-center justify-between">
                    <span class="font-bold text-xs text-slate-900 dark:text-slate-100">Notifications</span>
                    <span class="text-[10px] text-brand-primary font-semibold">2 New</span>
                </div>
                <div class="divide-y divide-brand-border dark:divide-slate-800 max-h-64 overflow-y-auto">
                    <div class="p-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-200">New Student Registered</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Ahmed Hassan joined Grade 10 Group A.</p>
                        <span class="text-[9px] text-slate-400 mt-1 block">5m ago</span>
                    </div>
                    <div class="p-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Monthly Billing Invoice</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Subscription invoice generated successfully.</p>
                        <span class="text-[9px] text-slate-400 mt-1 block">1h ago</span>
                    </div>
                </div>
            </x-slot>
        </x-ui.dropdown>

        <!-- User Profile Dropdown -->
        <x-ui.dropdown align="right" width="56">
            <x-slot name="trigger">
                <button class="flex items-center gap-2 p-1 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-start">
                    <x-ui.avatar :name="auth()->user()->name ?? 'User'" size="sm" />
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="px-4 py-2.5 border-b border-brand-border dark:border-slate-800">
                    <p class="text-xs font-bold text-slate-900 dark:text-slate-100">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email ?? 'user@taalimu.com' }}</p>
                </div>
                <div class="py-1">
                    @if (app()->bound('tenant'))
                        <form method="POST" action="{{ route('center.logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 text-start">
                                <i class="fas fa-sign-out-alt"></i> Sign Out
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 text-start">
                                <i class="fas fa-sign-out-alt"></i> Sign Out
                            </button>
                        </form>
                    @endif
                </div>
            </x-slot>
        </x-ui.dropdown>
    </div>
</header>
