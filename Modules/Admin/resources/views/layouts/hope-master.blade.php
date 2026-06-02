<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - {{ \App\Models\SiteSetting::get('site_name', 'EduCentral') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/hope-ui/images/favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Hope UI CSS (from public/assets/hope-ui) -->
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/libs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/hope-ui.css?v=1.1.0') }}">
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/custom.css?v=1.1.0') }}">
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/rtl.css?v=1.1.0') }}">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <!-- Taalimu Unified Premium Emerald Theme -->
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/taalimu-unified.css?v=' . time()) }}">

    <style>
        /* ===== Admin Specific Overrides (Bento Pro Palette) ===== */
        :root {
            /* Bento Pro Palette (High Contrast) */
            --bs-primary: #10b981 !important;
            --bs-primary-rgb: 16, 185, 129 !important;
            
            --sidebar-bg: #0f172a; /* Slate 900 */
            --sidebar-hover: rgba(255, 255, 255, 0.05);
            --sidebar-text: rgba(255, 255, 255, 0.65);
            --sidebar-active-text: #ffffff;
            
            --bg-workspace: #f8fafc; /* Slate 50 */
            --card-border: #e2e8f0; /* Slate 200 */
            --text-main: #1e293b; /* Slate 800 */
            
            --shadow-bento: none;
            --radius-bento: 12px;
        }

        body { 
            font-family: 'Inter', 'Outfit', 'Cairo', sans-serif; 
            background-color: var(--bg-workspace) !important;
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
        }

        /* Arabic text specific font weight/style adjustment */
        [dir="rtl"] body {
            font-family: 'Cairo', sans-serif;
            letter-spacing: 0;
        }

        /* Global Transitions */
        * { transition: background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease; }

        /* Override Hope UI primary color classes */
        .bg-primary, .btn-primary, .badge-primary {
            background-color: var(--bs-primary) !important;
            border-color: var(--bs-primary) !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #059669 !important;
            border-color: #059669 !important;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2) !important;
        }

        /* Header banner */
        .iq-navbar-header { 
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #064e3b 100%) !important; 
            border-bottom: 5px solid rgba(255,255,255,0.1);
        }
        .iq-header-img img {
            opacity: 0.12 !important;
            mix-blend-mode: overlay !important;
            filter: contrast(1.2);
        }
        .iq-navbar-header h1 {
            font-weight: 800 !important;
            letter-spacing: -0.5px;
            color: #ffffff !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Bento Sidebar (Dark Theme) */
        .sidebar {
            background: var(--sidebar-bg) !important;
            border-left: 1px solid rgba(255,255,255,0.05) !important;
            box-shadow: 1px 0 0 0 rgba(255,255,255,0.05) !important;
        }
        
        .sidebar .static-item {
            padding: 1.5rem 1.25rem 0.5rem !important;
            border-top: 1px solid rgba(255,255,255,0.02);
            margin-top: 1rem;
        }
        .sidebar .static-item .default-icon {
            font-size: 0.6rem !important;
            color: rgba(255, 255, 255, 0.4) !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar .navbar-nav > .nav-item > .nav-link {
            padding: 0.65rem 1rem !important;
            border-radius: 8px !important;
            margin: 0.15rem 0.75rem;
            color: var(--sidebar-text) !important;
            font-size: 0.875rem;
        }

        .sidebar .navbar-nav > .nav-item > .nav-link.active {
            background: var(--sidebar-hover) !important;
            color: var(--sidebar-active-text) !important;
            font-weight: 600;
            border-right: 3px solid var(--bs-primary) !important;
        }
        
        .sidebar .navbar-nav > .nav-item > .nav-link:hover:not(.active) {
            background: var(--sidebar-hover) !important;
            color: var(--sidebar-active-text) !important;
        }

        .sidebar .icon {
            font-size: 1.15rem;
            margin-left: 10px;
            width: 24px;
            opacity: 0.6;
        }
        .sidebar .navbar-nav > .nav-item > .nav-link.active .icon {
            opacity: 1;
            color: var(--bs-primary);
        }

        /* Bento Grid Cards */
        .card {
            border: 1px solid var(--card-border) !important;
            box-shadow: none !important;
            border-radius: var(--radius-bento) !important;
            background: #ffffff !important;
        }
        
        /* Specialized Table for Bento */
        .table thead th {
            background: #f8fafc !important;
            color: #64748b !important; /* Slate 500 */
            border-bottom: 1px solid var(--card-border) !important;
            font-weight: 700 !important;
            font-size: 0.75rem !important;
            text-transform: uppercase;
        }
        .bg-hover-light-soft:hover {
            background-color: #f1f5f9 !important;
        }

        /* Header simplification */
        .iq-navbar-header { 
            height: auto !important; 
            background: white !important; 
            border-bottom: 1px solid var(--card-border);
            padding: 1.5rem 0;
            margin-bottom: 2rem;
        }
        .iq-navbar-header .text-white { color: var(--text-main) !important; }
        .iq-header-img { display: none !important; }

        /* Quick Action Overlap Fix */
        .content-inner {
            margin-top: -4.5rem !important;
            padding-bottom: 3rem !important;
        }

        /* Buttons in the overlapping header */
        .iq-navbar-header .btn-primary {
            background-color: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            backdrop-filter: blur(10px);
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .iq-navbar-header .btn-primary:hover {
            background-color: #ffffff !important;
            color: #059669 !important;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }

        .dropdown-menu.show {
            display: block !important;
            position: absolute;
            top: 100%;
        }
    </style>
    <!-- Network Resilience Monitor -->
    <link rel="stylesheet" href="{{ asset('css/network-monitor.css') }}">
    @stack('styles')
</head>

<body class="rtl">

    <!-- Sidebar Component -->
    @include('admin::layouts.hope-sidebar')

    <main class="main-content">
        <div class="position-relative">
            <!-- Header Component -->
            @include('admin::layouts.hope-header')
            
            <div class="iq-navbar-header">
                <div class="container-fluid iq-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flex-wrap d-flex justify-content-between align-items-center pt-5">
                                @hasSection('page-title')
                                <div class="page-title-content animated-scaleX">
                                    <h1 class="text-white mb-2 fw-bold" style="font-size: 2.2rem; letter-spacing: -1px;">@yield('page-title')</h1>
                                    <p class="text-white opacity-75 mb-0 small fw-bold">
                                        <i class="bi bi-stars me-1 text-warning"></i> @yield('page-subtitle')
                                    </p>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    @yield('page-actions')
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Premium Mesh Overlay -->
                <div class="iq-header-img" style="background-image: url(\"data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 4c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM54 96c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-8-70c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm50-10c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM16 62c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm31 34c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM91 69c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM64 39c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM24 78c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm-8-10c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm44 14c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm24-74c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm6 4c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-18-7c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-24 4c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm4 18c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm6 66c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm16-93c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm0 16c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm4 52c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm33 7c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-56-65c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm0 31c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-11-1c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm48 3c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm23-3c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-99-13c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm93 25c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm11 11c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-2 13c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-45-21c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-31 26c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm33-7c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-35-1c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-42-51c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm39-19c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm47 88c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-35-73c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zM20 98c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm76-86c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zM10 8c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm66 66c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm20 4c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E\"); opacity: 0.6 !important; mix-blend-mode: soft-light !important;"></div>
            </div>
        </div>

        <div class="container-fluid content-inner mt-n5 py-0">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-body">
                <ul class="left-panel list-inline mb-0 p-0">
                    <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                </ul>
                <div class="right-panel">
                    ©{{ date('Y') }} {{ config('app.name') }}, Made with <span class="text-gray border-gray"> Hope UI</span>
                </div>
            </div>
        </footer>
    </main>

    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('assets/hope-ui/js/libs.min.js') }}"></script>
    
    <!-- app script -->
    <script src="{{ asset('assets/hope-ui/js/hope-ui.js') }}"></script>
    
    <script>
        // SweetAlert2 Toast Configuration (Matching Admin Master)
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-start',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif

        @if(session('error'))
            Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
        @endif

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
    </script>

    <!-- Network Resilience Monitor -->
    <script src="{{ asset('js/network-monitor.js') }}"></script>
    @stack('scripts')
</body>
</html>
