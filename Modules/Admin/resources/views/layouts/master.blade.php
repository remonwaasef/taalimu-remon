<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ \App\Models\SiteSetting::get('site_name', 'EduCentral') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    
    <!-- PWA Support -->
    <link rel="manifest" href="/manifest.json?v=3">
    <meta name="theme-color" content="#3A0CA3">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Taalimu">

    <script>
        window.pwaDeferredPrompt = window.pwaDeferredPrompt || null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            window.pwaDeferredPrompt = e;
            window.dispatchEvent(new CustomEvent('pwa-prompt-available'));
        });
    </script>
    
    
    
    <style>
        :root {
            /* Power Palette - Aligning with landing-new.css */
            --dark-purple: 258 86% 34%;      /* Deep Indigo #3A0CA3 */
            --primary-purple: 230 100% 58%;   /* Royal Blue #2A4DFF */
            --light-purple: 229 83% 60%;    /* Lighter Blue #4361EE */
            --cyan: 165 75% 63%;            /* Aqua #5BE7C4 */
            --success-green: 160 100% 45%;  /* Emerald Green */
            --background: 216 33% 98%;      /* Off-white #F7F9FC */
            --foreground: 240 18% 13%;      /* Dark Text #1C1C28 */
            
            /* Gradients - Power Palette Standard */
            --gradient-hero: linear-gradient(135deg, #3A0CA3 0%, #2A4DFF 100%);
            --gradient-primary: linear-gradient(135deg, #2A4DFF 0%, #4361EE 100%);
            --gradient-cyan: linear-gradient(135deg, #5BE7C4 0%, #2A4DFF 100%);
            
            /* Bootstrap Overrides */
            --bs-primary: #3A0CA3;
            --bs-primary-rgb: 58, 12, 163;
            
            --bs-success: #10b981;
            --bs-success-rgb: 16, 185, 129;
            
            --bs-info: #2A4DFF;
            --bs-info-rgb: 42, 77, 255;
            
            --bs-warning: #f59e0b;
            --bs-warning-rgb: 245, 158, 11;
            
            --bs-danger: #ef4444;
            --bs-danger-rgb: 239, 68, 68;
            
            --bs-body-bg: #F7F9FC;
            --bs-body-color: #1C1C28;
            
            /* Sidebar - Luxurious Dark Aesthetic */
            --sidebar-width: 280px;
            --sidebar-bg: #0a0a0c;          /* Deep Obsidian */
            --sidebar-text: #e2e8f0;
            --sidebar-hover: rgba(255, 255, 255, 0.03);
            --sidebar-active-bg: rgba(67, 97, 238, 0.1);
            --sidebar-border: rgba(255, 255, 255, 0.05);
            
            /* Shadows */
            --shadow-sm: 0 2px 8px rgba(58, 12, 163, 0.08);
            --shadow-md: 0 4px 16px rgba(58, 12, 163, 0.1);
            --shadow-lg: 0 8px 32px rgba(58, 12, 163, 0.15);
            --shadow-xl: 0 16px 48px rgba(58, 12, 163, 0.2);
        }
        
        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
        }

        /* Fix text cursor and typing direction for Arabic inputs */
        [dir="rtl"] .form-control, 
        [dir="rtl"] .form-select, 
        [dir="rtl"] textarea {
            direction: rtl !important;
            text-align: right !important;
        }
        
        /* Sidebar Luxury Design */
        .sidebar {
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg) !important;
            color: var(--sidebar-text);
            position: fixed;
            right: 0;
            top: 0;
            z-index: 1000;
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
            border-left: 1px solid var(--sidebar-border);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
        }
        
        .sidebar .border-bottom {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Sidebar Brand */
        .sidebar a.text-white {
            transition: all 0.3s ease !important;
        }
        
        .sidebar a.text-white:hover {
            transform: translateX(-5px) !important;
        }
        
        /* Navigation Links */
        .nav-link {
            color: rgba(226, 232, 240, 0.7);
            padding: 0.9rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 12px;
            margin: 0.3rem 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--primary-gradient);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .nav-link:hover {
            color: white;
            background: var(--sidebar-hover);
            transform: translateX(-5px);
        }
        
        .nav-link.active {
            color: white;
            background: var(--sidebar-active-bg);
            box-shadow: inset 0 0 10px rgba(67, 97, 238, 0.05);
        }
        
        .nav-link.active::before {
            transform: scaleY(0.6);
            border-radius: 0 4px 4px 0;
        }
        
        .nav-link span {
            font-size: 1.25rem;
            width: 28px;
            text-align: center;
            filter: drop-shadow(0 0 8px rgba(99, 102, 241, 0.3));
        }
        
        /* Main Content */
        .main-content {
            margin-right: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            background: var(--bg-light);
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            background: white;
        }
        
        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }
        
        /* Buttons */
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            box-shadow: var(--shadow-md);
            padding: 0.625rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }
        
        /* Badges */
        .badge {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 600;
        }
        
        /* Table */
        .table {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background: rgba(99, 102, 241, 0.03);
        }
        
        /* Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }
        
        /* Mobile Responsive Sync */
        @media (max-width: 992px) {
            .sidebar {
                right: -280px !important;
            }
            .sidebar.active {
                right: 0 !important;
            }
            .main-content {
                margin-right: 0 !important;
            }
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        .navbar-main {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 0.75rem 1.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1001; /* Higher than sidebar overlay if needed, but primarily to create context */
            overflow: visible !important;
        }

        .lang-dropdown .dropdown-toggle {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .lang-dropdown .dropdown-toggle:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: var(--bs-primary);
        }

        .lang-dropdown {
            position: relative;
            z-index: 1050;
        }

        .lang-dropdown .dropdown-menu {
            border: none;
            box-shadow: var(--shadow-lg);
            border-radius: 12px;
            padding: 0.5rem;
            min-width: 140px;
            z-index: 1060;
        }

        .lang-dropdown .dropdown-item {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .lang-dropdown .dropdown-item:hover {
            background: var(--sidebar-active-bg);
            color: var(--bs-primary);
        }

        .lang-dropdown .dropdown-item.active {
            background: var(--bs-primary);
            color: white;
        }

        .lang-dropdown .dropdown-menu.show, .user-dropdown .dropdown-menu.show {
            display: block !important;
            position: absolute;
            top: 100%;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 999;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
    </style>
    @stack('styles')
</head>
<body style="font-family: 'Cairo', sans-serif;">
    @if(session()->has('impersonator_id'))
        <div class="alert alert-warning mb-0 rounded-0 border-0 p-3 d-flex justify-content-between align-items-center" style="z-index: 2000; position: relative;">
            <div class="fw-bold">
                <i class="bi bi-person-exclamation me-2"></i>
                {!! __('admin::admin.impersonation.alert', ['name' => '<strong>' . auth()->user()->name . '</strong>']) !!}
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
                    {{ substr(\App\Models\SiteSetting::get('site_name', 'EduCentral'), 0, 1) }}
                </span>
                {{ \App\Models\SiteSetting::get('site_name', 'EduCentral') }}
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
                    <span>إدارة المراكز والاشتراكات</span>
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
                    {{ __('admin::admin.coupons_discounts') }}
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
                    <span>النسخ الاحتياطي</span>
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
                    <span>فريق الإدارة</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('consent.report') }}" class="nav-link {{ request()->routeIs('consent.report') ? 'active' : '' }}">
                    <span>🍪</span>
                    <span>{{ __('admin::admin.sidebar.cookie_reports') }}</span>
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

        <!-- Flash Messages (Handled by SweetAlert2) -->

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

        // SweetAlert2 Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-start', // Admin is always RTL as per html tag
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif
    </script>
    <script>
        // Bootstrap dropdowns and other components are auto-initialized by the data-api
        // contained in the Vite-bundled bootstrap JS (app.js).
        document.addEventListener('DOMContentLoaded', function() {
            // Manual fixes for specific UI edge cases can go here
        });
    </script>
    @stack('scripts')
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js?v=6')
                    .then((reg) => console.log('SW registered:', reg.scope))
                    .catch((err) => console.log('SW failed:', err));
            });
        }
    </script>
</body>
</html>
