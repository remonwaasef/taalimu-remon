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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Hope UI & Bootstrap Core CSS (Compatible with TailwindCSS) -->
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/libs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/hope-ui.css?v=1.1.1') }}">
    @if(app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/rtl.css?v=1.1.1') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/taalimu-unified.css?v=' . time()) }}">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ApexCharts Script -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vite Assets (TailwindCSS + App + Global Interactions) -->
    @vite(['resources/css/tailwind.css', 'resources/css/app.scss', 'resources/js/app.js', 'resources/js/taalimu-global.js'])

    @stack('styles')
</head>
<body class="h-full bg-[#FAFAFA] dark:bg-[#0B1118] text-slate-800 dark:text-slate-100 {{ app()->getLocale() == 'ar' ? 'font-cairo' : 'font-inter' }} antialiased selection:bg-brand-primary selection:text-white transition-colors duration-200" x-data="{ sidebarOpen: false, collapsed: false }" x-effect="document.body.style.overflow = sidebarOpen ? 'hidden' : ''">

    <!-- Accessibility Skip to Main Content Link -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:start-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-brand-primary focus:text-white focus:rounded-xl focus:shadow-lg focus:font-bold">
        {{ __('Skip to main content') }}
    </a>

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
        aria-hidden="true"
    ></div>

    <div class="min-h-screen flex">
        <!-- Sidebar Navigation (hidden on mobile, overlay on toggle) -->
        <div
            x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full rtl:translate-x-full"
            x-transition:enter-end="translate-x-0 rtl:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0 rtl:translate-x-0"
            x-transition:leave-end="-translate-x-full rtl:translate-x-full"
            x-cloak
            class="fixed inset-y-0 start-0 z-50 lg:hidden w-64"
            x-on:keydown.escape.window="sidebarOpen = false"
            @resize.window="if (window.innerWidth >= 1024) sidebarOpen = false"
            role="dialog"
            aria-modal="true"
            aria-label="{{ __('Sidebar navigation') }}"
        >
            @yield('sidebar')
        </div>

        <!-- Desktop Sidebar -->
        <div class="hidden lg:block shrink-0">
            <div class="sidebar-shell" :class="collapsed && 'collapsed'">
                @yield('sidebar')
            </div>
        </div>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Navbar with Mobile Menu Toggle -->
            <header class="h-16 bg-white/90 dark:bg-[#121A24]/90 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 sticky top-0 z-20 px-4 sm:px-6 flex items-center justify-between gap-4 font-inter">
                <!-- Mobile Menu Button -->
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    aria-label="{{ __('Toggle navigation') }}"
                    aria-expanded="false"
                    :aria-expanded="sidebarOpen ? 'true' : 'false'"
                    class="lg:hidden w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center justify-center"
                >
                    <i class="fas fa-bars text-sm"></i>
                </button>

                <!-- Left Section: Command Palette Trigger & Search -->
                <div class="flex items-center gap-3 flex-1">
                    <!-- Desktop Sidebar Collapse Toggle -->
                    <button
                        @click="collapsed = !collapsed"
                        aria-label="{{ __('Toggle sidebar') }}"
                        :aria-expanded="collapsed ? 'false' : 'true'"
                        class="hidden lg:inline-flex w-9 h-9 rounded-xl border border-slate-200/80 dark:border-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors items-center justify-center shrink-0"
                    >
                        <i class="fas fa-bars text-sm transition-transform duration-300" :class="collapsed && 'rotate-180'"></i>
                    </button>

                    <button
                        type="button"
                        @click="$dispatch('open-command-palette')"
                        class="w-full max-w-md h-9.5 ps-3.5 pe-3 bg-slate-100/70 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 border border-slate-200/70 dark:border-slate-700/60 rounded-xl text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-all flex items-center justify-between group text-start shadow-2xs"
                    >
                        <span class="flex items-center gap-2">
                            <i class="fas fa-search text-xs text-slate-400 group-hover:text-brand-primary transition-colors"></i>
                            <span class="truncate hidden sm:inline">{{ __('Search or press Ctrl+K...') }}</span>
                            <span class="truncate sm:hidden">{{ __('Search...') }}</span>
                        </span>
                        <kbd class="hidden sm:inline-flex items-center gap-0.5 px-2 py-0.5 text-[10px] font-mono font-bold text-slate-500 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md shadow-2xs">
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
                        </x-slot>
                    </x-ui.dropdown>

<!-- Notification Bell -->
                    @php
                        $bellUser = auth()->user();
                        $unreadCount = $bellUser ? (int) $bellUser->unreadNotifications()->count() : 0;
                        $recentNotifications = $bellUser ? $bellUser->notifications()->take(5)->get() : collect();
                        $bellIndexRoute = app()->bound('tenant') && $bellUser && Route::has('center.notifications.index')
                            ? tenant_route('center.notifications.index', [])
                            : null;
                    @endphp
                    <div
                        class="relative"
                        x-data="{
                            swinging: false,
                            unread: @js($unreadCount),
                            ping() {
                                this.swinging = false;
                                this.$nextTick(() => { this.swinging = true; });
                            },
                            stopSwing() { this.swinging = false; }
                        }"
                        @new-notification.window="unread = unread + 1; ping()"
                        x-init="if (unread > 0 && !sessionStorage.getItem('bell_intro')) { sessionStorage.setItem('bell_intro', '1'); ping(); }"
                    >
                        <x-ui.dropdown align="right" width="80">
                            <x-slot name="trigger">
                                <button
                                    type="button"
                                    data-bell-swing
                                    @click="stopSwing()"
                                    aria-label="{{ __('Notifications') }} ({{ $unreadCount }})"
                                    aria-haspopup="true"
                                    :aria-expanded="open ? 'true' : 'false'"
                                    class="w-9 h-9 rounded-xl border border-brand-border dark:border-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center justify-center relative"
                                    :class="swinging && 'bell-active'"
                                >
                                    <i class="fas fa-bell text-xs" :class="swinging && 'bell-swing'" @animationend="swinging = false"></i>
                                    <span
                                        x-show="unread > 0"
                                        x-cloak
                                        class="absolute -top-1 -end-1 min-w-[18px] h-[18px] px-1 rounded-full bg-brand-primary text-white text-[11px] font-bold flex items-center justify-center ring-2 ring-white dark:ring-slate-900"
                                        :class="swinging && 'badge-pop'"
                                    ><span x-text="unread"></span></span>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2.5 border-b border-brand-border dark:border-slate-800 flex items-center justify-between">
                                    <span class="font-bold text-xs text-slate-900 dark:text-slate-100">{{ __('Notifications') }}</span>
                                    @if($unreadCount > 0)
                                        @if($bellUser && app()->bound('tenant') && Route::has('center.notifications.readAll'))
                                            <form method="POST" action="{{ tenant_route('center.notifications.readAll') }}">
                                                @csrf
                                                <button type="submit" class="text-[11px] text-brand-primary font-semibold hover:underline">
                                                    <i class="fas fa-check-double me-1"></i>{{ __('Mark all as read') }}
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                                <div class="divide-y divide-brand-border dark:divide-slate-800 max-h-80 overflow-y-auto">
                                    @forelse($recentNotifications as $notification)
                                        <a
                                            @if($bellUser && app()->bound('tenant') && Route::has('center.notifications.read'))
                                                href="{{ tenant_route('center.notifications.read', [$notification->id]) }}"
                                            @else href="#" @endif
                                            class="flex items-start gap-3 p-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors {{ $notification->read_at ? '' : 'bg-brand-50/60 dark:bg-brand-900/20' }}"
                                        >
                                            <div class="w-8 h-8 rounded-xl bg-brand-50 dark:bg-brand-900/30 text-brand-primary flex items-center justify-center shrink-0">
                                                <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }} text-xs"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ Str::limit($notification->data['title'] ?? '', 60) }}</p>
                                                <p class="text-[11px] text-slate-400 mt-0.5 truncate">{{ Str::limit($notification->data['message'] ?? '', 80) }}</p>
                                                <span class="text-[11px] text-slate-400 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if(!$notification->read_at)
                                                <span class="w-2 h-2 rounded-full bg-brand-primary mt-1.5 shrink-0"></span>
                                            @endif
                                        </a>
                                    @empty
                                        <div class="p-6 text-center text-xs text-slate-400">
                                            <i class="fas fa-bell-slash text-lg mb-1 block"></i>
                                            {{ __('No new notifications') }}
                                        </div>
                                    @endforelse
                                </div>
                                @if($bellIndexRoute)
                                    <div class="px-4 py-2 border-t border-brand-border dark:border-slate-800">
                                        <a href="{{ $bellIndexRoute }}" class="block text-center text-[11px] font-semibold text-brand-primary hover:underline">
                                            {{ __('View all notifications') }}
                                        </a>
                                    </div>
                                @endif
                            </x-slot>
                        </x-ui.dropdown>
                    </div>

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
            <main id="main-content" class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto motion-page pb-20 lg:pb-8">
                <!-- Trial & Subscription Alerts -->
                <x-trial-alert-banner />
                <!-- Flash Messages -->
                <x-flash-messages />
                <div class="motion-page" style="animation-delay: 60ms">
                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Floating Bottom Action Dock (Thumb Zone UX) -->
    @if(app()->bound('tenant') && !Route::is('login.*') && !Route::is('register.*'))
        @php
            $mDomain = app('tenant')?->domain;
            $mIsDashboard = Route::currentRouteNamed('center.dashboard');
            $mIsStudents = Route::currentRouteNamed('center.students.*');
            $mIsAttendance = Route::currentRouteNamed('center.attendance.*');
            $mIsSales = Route::currentRouteNamed('center.sales.*');
        @endphp
        <nav class="lg:hidden fixed bottom-0 inset-x-0 z-30 bg-white/95 dark:bg-[#101A26]/95 backdrop-blur-md border-t border-slate-200/90 dark:border-slate-800 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] px-2 py-1.5 flex items-center justify-around" aria-label="{{ __('Mobile navigation') }}">
            <!-- 1. Home -->
            <a href="{{ route('center.dashboard', ['tenant' => $mDomain]) }}" class="flex flex-col items-center justify-center w-14 py-1 text-center transition-colors {{ $mIsDashboard ? 'text-brand-primary dark:text-brand-300 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800' }}">
                <i class="fas fa-home text-base mb-1"></i>
                <span class="text-[10px] leading-none">{{ __('center::sidebar.dashboard') }}</span>
            </a>

            <!-- 2. Students -->
            <a href="{{ route('center.students.index', ['tenant' => $mDomain]) }}" class="flex flex-col items-center justify-center w-14 py-1 text-center transition-colors {{ $mIsStudents ? 'text-brand-primary dark:text-brand-300 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800' }}">
                <i class="fas fa-user-graduate text-base mb-1"></i>
                <span class="text-[10px] leading-none">{{ __('center::sidebar.students') }}</span>
            </a>

            <!-- 3. Raised QR Scan Action Button (Centerpiece) -->
            <a href="{{ route('center.attendance.index', ['tenant' => $mDomain]) }}" class="flex flex-col items-center justify-center -mt-5 group" title="تحضير سريع">
                <div class="w-12 h-12 rounded-full bg-brand-primary text-white shadow-lg shadow-brand-primary/30 flex items-center justify-center text-lg group-hover:scale-105 group-active:scale-95 transition-all border-2 border-white dark:border-[#101A26]">
                    <i class="fas fa-qrcode"></i>
                </div>
                <span class="text-[9px] font-bold text-slate-600 dark:text-slate-300 mt-1">حضور</span>
            </a>

            <!-- 4. Sales / Billing -->
            <a href="{{ route('center.sales.index', ['tenant' => $mDomain]) }}" class="flex flex-col items-center justify-center w-14 py-1 text-center transition-colors {{ $mIsSales ? 'text-brand-primary dark:text-brand-300 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800' }}">
                <i class="fas fa-wallet text-base mb-1"></i>
                <span class="text-[10px] leading-none">{{ __('center::sidebar.payments') }}</span>
            </a>

            <!-- 5. More Menu Toggle -->
            <button type="button" @click="sidebarOpen = true" class="flex flex-col items-center justify-center w-14 py-1 text-center text-slate-500 dark:text-slate-400 hover:text-slate-800" aria-label="القائمة">
                <i class="fas fa-bars text-base mb-1"></i>
                <span class="text-[10px] leading-none">المزيد</span>
            </button>
        </nav>
    @endif

    <!-- Global Toast Notifications Container -->
    <x-ui.toast />

    <!-- Global Command Palette Modal (Ctrl+K) -->
    <x-ui.command-palette />

    <!-- Bootstrap & Hope UI Core JS -->
    <script src="{{ asset('assets/hope-ui/js/libs.min.js') }}"></script>
    <script src="{{ asset('assets/hope-ui/js/hope-ui.js') }}"></script>

    @stack('modals')

    @stack('scripts')
</body>
</html>
