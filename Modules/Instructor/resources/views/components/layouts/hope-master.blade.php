<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'لوحة تحكم المعلم') - {{ config('app.name', 'Taalimu') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/hope-ui/images/favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Hope UI CSS (from public/assets/hope-ui) -->
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/libs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/hope-ui.css?v=1.1.0') }}">
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/custom.css?v=1.1.0') }}">
    @if(app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/rtl.css?v=1.1.0') }}">
    @endif

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* ===== Global Emerald Green Theme Override (Taalimu Unified) ===== */
        :root {
            --bs-primary: #10b981 !important; /* Emerald 500 */
            --bs-primary-rgb: 16, 185, 129 !important;
            --bs-link-color: #10b981 !important;
            --bs-link-hover-color: #059669 !important;
            --primary-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        body { 
            font-family: 'Outfit', 'Cairo', sans-serif; 
            background-color: #f8fafc;
        }

        /* Arabic text specific font weight/style adjustment */
        [dir="rtl"] body {
            font-family: 'Cairo', sans-serif;
        }

        /* Fix text cursor and typing direction for Arabic inputs */
        [dir="rtl"] .form-control, 
        [dir="rtl"] .form-select, 
        [dir="rtl"] textarea {
            direction: rtl !important;
            text-align: right !important;
        }

        /* Override Hope UI primary color classes */
        .bg-primary, .btn-primary, .badge-primary {
            background-color: #10b981 !important;
            border-color: #10b981 !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #059669 !important;
            border-color: #059669 !important;
            box-shadow: 0 8px 15px rgba(16, 185, 129, 0.2) !important;
        }

        /* Header banner */
        .iq-navbar-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            border-bottom: 0 !important;
            box-shadow: inset 0 -30px 60px -30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .iq-header-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            opacity: 0.1 !important;
            mix-blend-mode: overlay !important;
            pointer-events: none;
        }

        .iq-navbar-header h1 {
            font-weight: 800 !important;
            letter-spacing: -1px;
            margin-bottom: 0.5rem;
        }

        .iq-navbar-header p {
            font-weight: 500;
            font-size: 1.1rem;
        }

        .iq-navbar-header .iq-container {
            position: relative;
            z-index: 1;
            padding-top: 2rem !important;
        }
        
        /* Stats Card & Content */
        .content-inner {
            margin-top: -5.5rem !important; /* Deeper overlap for premium look */
            position: relative;
            z-index: 10;
        }

        .card, .stats-card {
            border: 0 !important;
            box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05), 0 5px 15px -5px rgba(0,0,0,0.02);
            border-radius: 20px !important;
            overflow: visible !important;
        }
        
        .btn-glass {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white !important;
            border-radius: 50px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        /* Table Enhancements */
        .table thead th {
            color: #2b3a4a !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .bg-soft-primary {
            background-color: rgba(0, 123, 255, 0.1) !important;
        }

        .iq-navbar-header h1, .iq-navbar-header p, .iq-navbar-header span {
            color: #ffffff !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.15);
        }
        
        /* Sidebar active item - Ultra Premium */
        .sidebar .navbar-nav > .nav-item > .nav-link.active {
            background: var(--primary-gradient) !important;
            color: white !important;
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.3) !important;
            border-radius: 14px;
            margin: 0 12px;
        }

        .sidebar .navbar-nav > .nav-item > .nav-link:hover:not(.active) {
            background: rgba(16, 185, 129, 0.05);
            color: #10b981 !important;
        }

        /* Quick Action Overlap Fix */
        .content-inner {
            margin-top: -3rem !important;
        }

        /* Auto-style the first row containing title and actions over the green banner */
        .content-inner > .d-flex:first-child h2, 
        .content-inner > .row:first-child h2 {
            color: #ffffff !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .content-inner > .d-flex:first-child .text-muted,
        .content-inner > .d-flex:first-child p {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        /* Buttons in the overlapping header */
        .content-inner > .d-flex:first-child .btn-primary {
            background-color: #ffffff !important;
            color: #10b981 !important;
            border-color: #ffffff !important;
            font-weight: 700;
        }
        .content-inner > .d-flex:first-child .btn-primary:hover {
            background-color: #f8fafc !important;
            color: #059669 !important;
            transform: translateY(-1px);
        }
        /* Fix Dropdown menus being cut off in responsive tables */
        .table-responsive, .card-body, table {
            overflow: visible !important;
        }
        @media (max-width: 991.98px) {
            .table-responsive {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
            }
        }
        
        /* Dropdown menu premium look */
        .dropdown-menu {
            border: 0 !important;
            box-shadow: 0 15px 35px -5px rgba(0,0,0,0.1), 0 5px 15px -5px rgba(0,0,0,0.05) !important;
            border-radius: 12px !important;
            padding: 0.5rem !important;
            z-index: 1060 !important;
            min-width: 180px !important;
        }
        .dropdown-item {
            border-radius: 8px !important;
            padding: 0.6rem 1rem !important;
            font-weight: 500 !important;
            transition: all 0.2s !important;
        }
        .dropdown-item:hover {
            background-color: rgba(16, 185, 129, 0.08) !important;
            color: #10b981 !important;
            transform: translateX(3px);
        }
        [dir="rtl"] .dropdown-item:hover {
            transform: translateX(-3px);
        }
    </style>
    @stack('styles')
</head>

<body class="{{ app()->getLocale() == 'ar' ? 'rtl' : '' }}">

    <!-- Sidebar Component -->
    @include('instructor::components.layouts.hope-sidebar')

    <main class="main-content">
        <div class="position-relative">
            <!-- Header Component -->
            @include('instructor::components.layouts.hope-header')
            
            <div class="iq-navbar-header" style="height: 180px;">
                <div class="container-fluid iq-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flex-wrap d-flex justify-content-between align-items-center pt-3 pb-3">
                                @hasSection('page-title')
                                <div>
                                    <h1 class="text-white display-5">@yield('page-title')</h1>
                                    <p class="text-white opacity-75 mb-0">
                                        @yield('page-subtitle')
                                    </p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    @yield('page-actions')
                                </div>
                                @else
                                <div class="text-white">
                                    <h1 class="display-5 fw-bold mb-1">{{ __('instructor::dashboard.welcome_back', ['name' => auth()->user()->name ?? '']) }}</h1>
                                    <p class="opacity-75 fs-5 mb-0">
                                        {{ app()->getLocale() == 'ar' ? 'نظرة عامة على إحصائيات وأداء أنشطتك التعليمية' : 'An overview of your educational activities and performance statistics' }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iq-header-img">
                     <img src="{{ asset('assets/hope-ui/images/dashboard/top-header.png') }}" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">
                </div>
            </div>
        </div>

        <div class="container-fluid content-inner mt-n5 py-0">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-body">
                <ul class="left-panel list-inline mb-0 p-0">
                    <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                </ul>
                <div class="right-panel">
                    ©<script>document.write(new Date().getFullYear())</script> {{ config('app.name') }}, Made with <span class="text-gray border-gray"> Hope UI</span>
                </div>
            </div>
        </footer>
    </main>

    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('assets/hope-ui/js/libs.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- app script -->
    <script src="{{ asset('assets/hope-ui/js/hope-ui.js') }}"></script>
    
    @stack('scripts')
    <script>
        // Global fix for dropdown clipping in tables/cards
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'))
            dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl, {
                    boundary: 'viewport',
                    popperConfig: (defaultConfig) => {
                        return {
                            ...defaultConfig,
                            strategy: 'fixed'
                        };
                    }
                })
            })
        });
    </script>
    @stack('modals')
</body>
</html>
