<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="h-full" x-data="{ theme: localStorage.getItem('theme') || 'system' }" :class="{ 'dark': theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches) }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Apply saved theme before first paint to prevent white flash (FOUC) -->
    <script>
        (function () {
            var theme = null;
            try { theme = localStorage.getItem('theme'); } catch (e) {}
            var dark = theme === 'dark' ||
                ((theme === null || theme === 'system') && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (dark) {
                document.documentElement.classList.add('dark');
            }
            if (theme === null || theme === 'system') {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
                    document.documentElement.classList.toggle('dark', e.matches);
                });
            }
        })();
    </script>

    <title>@yield('title', config('app.name', 'Taalimu'))</title>

    <!-- Google Fonts: Inter & Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ApexCharts Script -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Hope UI & Bootstrap CSS (Legacy Component Support) -->
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/libs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/hope-ui.css?v=1.3.1') }}">
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/taalimu-unified.css?v=1.3.1') }}">
    @if(app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/rtl.css?v=1.3.1') }}">
    @endif

    <!-- Vite Assets (TailwindCSS + App + Global Interactions) -->
    @vite(['resources/css/tailwind.css', 'resources/css/app.scss', 'resources/js/app.js', 'resources/js/taalimu-global.js'])

    @stack('styles')
</head>
<body class="h-full bg-brand-bg dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-inter antialiased selection:bg-brand-primary selection:text-white transition-colors duration-200" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Overlay -->
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden"
        style="display: none;"
    ></div>

    <div class="min-h-screen flex">
        <!-- Sidebar Navigation (hidden on mobile, overlay on toggle) -->
        <div
            class="fixed inset-y-0 start-0 z-50 lg:relative lg:z-auto transition-transform duration-300 lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full rtl:translate-x-full lg:translate-x-0 rtl:lg:translate-x-0'"
        >
            @yield('sidebar')
        </div>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Navbar with Mobile Menu Toggle -->
            <header class="h-16 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-brand-border dark:border-slate-800 sticky top-0 z-20 px-4 sm:px-6 flex items-center justify-between gap-4 font-inter">
                <!-- Mobile Menu Button -->
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden w-9 h-9 rounded-xl border border-brand-border dark:border-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center justify-center"
                >
                    <i class="fas fa-bars text-sm"></i>
                </button>

                <!-- Left Section: Command Palette Trigger & Search -->
                <div class="flex items-center gap-4 flex-1">
                    <button
                        type="button"
                        @click="$dispatch('open-command-palette')"
                        class="w-full max-w-md h-9 ps-3.5 pe-4 bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 rounded-xl text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-all flex items-center justify-between group text-start"
                    >
                        <span class="flex items-center gap-2">
                            <i class="fas fa-search text-xs"></i>
                            <span class="truncate hidden sm:inline">Search or press Ctrl+K...</span>
                            <span class="truncate sm:hidden">Search...</span>
                        </span>
                        <kbd class="hidden sm:inline-flex items-center gap-0.5 px-2 py-0.5 text-[10px] font-mono font-medium text-slate-400 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md shadow-xs">
                            ⌘K
                        </kbd>
                    </button>
                </div>

                <!-- Right Section: Theme Switcher, Language, Notifications & Profile -->
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

                    <!-- Notification Bell -->
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
                                <span class="text-[10px] text-brand-primary font-semibold">New</span>
                            </div>
                            <div class="divide-y divide-brand-border dark:divide-slate-800 max-h-64 overflow-y-auto">
                                <div class="p-3 text-center text-xs text-slate-400">
                                    <i class="fas fa-bell-slash text-lg mb-1 block"></i>
                                    No new notifications
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
                                    <a href="{{ tenant_route('instructor.settings') }}" class="w-full flex items-center gap-2 px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 text-start">
                                        <i class="fas fa-cog w-4 text-center"></i> Settings
                                    </a>
                                    <form method="POST" action="{{ tenant_route('center.logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 text-start">
                                            <i class="fas fa-sign-out-alt w-4 text-center"></i> Sign Out
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.settings.index') }}" class="w-full flex items-center gap-2 px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 text-start">
                                        <i class="fas fa-cog w-4 text-center"></i> Settings
                                    </a>
                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 text-start">
                                            <i class="fas fa-sign-out-alt w-4 text-center"></i> Sign Out
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </x-slot>
                    </x-ui.dropdown>
                </div>
            </header>

            <!-- Main Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                <!-- Flash Messages -->
                <x-flash-messages />

                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Global Command Palette Modal (Ctrl+K) -->
    <x-ui.command-palette />

    @stack('scripts')
</body>
</html>
