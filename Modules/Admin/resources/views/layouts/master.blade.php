<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ $siteSettings['site_name'] ?? 'EduCentral' }}</title>
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#3A0CA3">
    <link rel="manifest" href="/manifest.json?v={{ filemtime(public_path('manifest.json')) }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Taalimu">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" 
          onerror="this.onerror=null;this.href='{{ asset('assets/css/bootstrap.rtl.min.css') }}'">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"
          onerror="this.onerror=null;this.href='{{ asset('assets/css/bootstrap-icons.css') }}'">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          onerror="this.onerror=null;this.href='{{ asset('assets/css/fontawesome.min.css') }}'">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
          onerror="this.onerror=null;this.href='{{ asset('assets/css/animate.min.css') }}'">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <script>
        window.pwaDeferredPrompt = window.pwaDeferredPrompt || null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            window.pwaDeferredPrompt = e;
            window.dispatchEvent(new CustomEvent('pwa-prompt-available'));
        });
    </script>

    <link rel="stylesheet" href="{{ asset('assets/css/admin-custom.css') . '?v=' . filemtime(public_path('assets/css/admin-custom.css')) }}">
    @stack('styles')
</head>
<body style="font-family: 'Cairo', sans-serif;">
    @if(session()->has('impersonator_id'))
        <div class="alert alert-warning mb-0 rounded-0 border-0 p-3 d-flex justify-content-between align-items-center" style="z-index: 2000; position: relative;">
            <div class="fw-bold">
                <i class="bi bi-person-exclamation me-2"></i>
                {!! __('admin::admin.impersonation.alert', ['name' => '<strong>' . e(auth()->user()->name) . '</strong>']) !!}
            </div>
            <a href="{{ route('admin.impersonate.stop') }}" class="btn btn-dark btn-sm rounded-pill px-4 fw-bold">
                <i class="bi bi-box-arrow-right me-1"></i> {{ __('admin::admin.impersonation.stop') }}
            </a>
        </div>
    @endif
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar d-flex flex-column" id="sidebar">
        <div class="p-4 mb-3 border-bottom d-flex align-items-center justify-content-between" style="border-bottom: 1px solid var(--sidebar-border) !important;">
            <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none fs-4 fw-bold d-flex align-items-center gap-2">
                <span class="bg-primary rounded p-1 d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; font-size: 1.2rem;">
                    {{ substr($siteSettings['site_name'] ?? 'EduCentral', 0, 1) }}
                </span>
                {{ $siteSettings['site_name'] ?? 'EduCentral' }}
            </a>
            <button type="button" class="btn btn-link text-white p-0 d-lg-none" id="sidebarClose">
                <i class="bi bi-x-lg fs-4"></i>
            </button>
        </div>

        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span>📊</span>
                    <span>{{ __('admin::admin.sidebar.dashboard') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.tenants.index') }}" class="nav-link {{ request()->routeIs('admin.tenants.*') || request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                    <span>🏢</span>
                    <span>{{ __('admin::admin.sidebar.tenants_subscriptions') ?? 'إدارة المراكز والاشتراكات' }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') && !str_contains(request()->fullUrl(), 'tab=coupons') ? 'active' : '' }}">
                    <span>⚙️</span>
                    <span>{{ __('admin::admin.sidebar.settings') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.index', ['tab' => 'coupons']) }}" class="nav-link {{ str_contains(request()->fullUrl(), 'tab=coupons') ? 'active' : '' }}">
                    <span>🎟️</span>
                    <span>{{ __('admin::admin.coupons_discounts') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.tickets.index') }}" class="nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                    <span>🎫</span>
                    <span>{{ __('admin::admin.sidebar.support') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.activity-logs.index') }}" class="nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                    <span>📋</span>
                    <span>{{ __('admin::admin.sidebar.activity_logs') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.operation-issues.index') }}" class="nav-link {{ request()->routeIs('admin.operation-issues.*') ? 'active' : '' }}">
                    <span>🔴</span>
                    <span>{{ __('admin::admin.sidebar.operation_issues') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.backups.index') }}" class="nav-link {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}">
                    <span>💾</span>
                    <span>{{ __('admin::admin.sidebar.backups') ?? 'النسخ الاحتياطي' }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <span>🛡️</span>
                    <span>{{ __('admin::admin.sidebar.roles') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span>👥</span>
                    <span>{{ __('admin::admin.sidebar.admin_team') ?? 'فريق الإدارة' }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('consent.report') }}" class="nav-link {{ request()->routeIs('consent.report') ? 'active' : '' }}">
                    <span>🍪</span>
                    <span>{{ __('admin::admin.sidebar.cookie_reports') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.bug_reports.index') }}" class="nav-link {{ request()->routeIs('admin.bug_reports.index') ? 'active' : '' }}">
                    <span>🐛</span>
                    <span>{{ __('admin::admin.sidebar.bug_reports') ?? 'تقارير الأخطاء (Beta)' }}</span>
                </a>
            </li>
        </ul>

    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- New Top Header -->
        <nav class="navbar navbar-main shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link text-dark p-0 d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list fs-2"></i>
                </button>
                <h4 class="mb-0 fw-bold d-none d-sm-block text-primary">@yield('page-title', __('admin.title'))</h4>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Language Dropdown -->
                <div class="dropdown lang-dropdown">
                    <button class="dropdown-toggle" type="button" id="langDropdown" aria-expanded="false" onclick="toggleCustomDropdown(event, this)">
                        @php
                            $currentLocale = app()->getLocale();
                            $locales = [
                                'ar' => ['name' => 'العربية', 'flag' => '🇸🇦'],
                                'en' => ['name' => 'English', 'flag' => '🇺🇸'],
                                'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
                            ];
                        @endphp
                        <span>{{ $locales[$currentLocale]['flag'] }}</span>
                        <span class="d-none d-md-inline">{{ $locales[$currentLocale]['name'] }}</span>
                        <i class="bi bi-chevron-down small"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="langDropdown">
                        @foreach($locales as $code => $lang)
                            <li>
                                <a class="dropdown-item {{ $currentLocale == $code ? 'active' : '' }}" href="{{ route('lang.switch', $code) }}">
                                    <span>{{ $lang['flag'] }}</span>
                                    {{ $lang['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Quick Search or Notification could go here -->

                <!-- User Dropdown in Header -->
                <div class="dropdown user-dropdown">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle py-1 px-2 rounded-pill hover-bg-light" id="dropdownUserHeader" aria-expanded="false" onclick="toggleCustomDropdown(event, this)">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 35px; height: 35px; background: var(--gradient-primary) !important;">
                            {{ substr(auth()->user()->name ?? 'Admin', 0, 1) }}
                        </div>
                        <span class="ms-2 fw-bold d-none d-md-inline">{{ auth()->user()->name ?? __('admin::admin.sidebar.admin') }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2 mt-2 rounded-4" aria-labelledby="dropdownUserHeader">
                        <li class="px-3 py-2 border-bottom mb-2 d-md-none">
                            <div class="fw-bold">{{ auth()->user()->name }}</div>
                            <div class="text-muted small">{{ auth()->user()->email }}</div>
                        </li>
                        <li><a class="dropdown-item py-2 px-3 rounded-3 mx-2 w-auto" href="{{ route('admin.users.edit', auth()->id()) }}">
                            <i class="bi bi-person me-2"></i> {{ __('admin::admin.sidebar.profile') }}
                        </a></li>
                        <li><hr class="dropdown-divider mx-2"></li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST" id="admin-logout-form-header" class="d-none">
                                @csrf
                            </form>
                            <a class="dropdown-item text-danger py-2 px-3 rounded-3 mx-2 w-auto" href="{{ route('admin.logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('admin-logout-form-header').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> {{ __('admin.sidebar.logout') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        <x-flash-messages />

        @yield('content')
    </div>

    <script>
        function toggleCustomDropdown(event, btn) {
            if (event) event.stopPropagation();
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu.show').forEach(el => {
                if (el.previousElementSibling !== btn) {
                    el.classList.remove('show');
                }
            });

            const menu = btn.nextElementSibling;
            if (menu) {
                menu.classList.toggle('show');
                
                // Handle click outside to close
                const closeHandler = function(e) {
                    if (!btn.contains(e.target) && !menu.contains(e.target)) {
                        menu.classList.remove('show');
                        document.removeEventListener('click', closeHandler);
                    }
                };
                
                if (menu.classList.contains('show')) {
                    document.addEventListener('click', closeHandler);
                }
            }
        }

        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('sidebarToggle');
        const close = document.getElementById('sidebarClose');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        toggle?.addEventListener('click', toggleSidebar);
        close?.addEventListener('click', toggleSidebar);
        overlay?.addEventListener('click', toggleSidebar);

    </script>
    <script>
        // Bootstrap dropdowns and other components are auto-initialized by the data-api
        // contained in the Vite-bundled bootstrap JS (app.js).
        document.addEventListener('DOMContentLoaded', function() {
            // Manual fixes for specific UI edge cases can go here
        });
    </script>
    @stack('scripts')
    <script src="{{ asset('js/offline-sync.js') . '?v=' . (file_exists(public_path('js/offline-sync.js')) ? filemtime(public_path('js/offline-sync.js')) : '1') }}"></script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js?v=' + new Date().getTime())
                    .then((reg) => console.log('SW registered:', reg.scope))
                    .catch((err) => console.log('SW failed:', err));
            });
        }
    </script>
    <x-cookie-consent />
</body>
</html>
