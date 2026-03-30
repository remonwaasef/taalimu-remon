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
        
        /* Sidebar Refinements */
        .sidebar {
            box-shadow: 0 0 40px rgba(0,0,0,0.03) !important;
            border-left: 1px solid rgba(0,0,0,0.05) !important;
        }
        .sidebar .navbar-nav > .nav-item > .nav-link.active {
            background: var(--primary-gradient) !important;
            color: white !important;
            box-shadow: 0 8px 16px -4px rgba(16, 185, 129, 0.4) !important;
            border-radius: 12px;
            margin: 0 10px;
        }
        
        .sidebar .navbar-nav > .nav-item > .nav-link:hover:not(.active) {
            background: rgba(16, 185, 129, 0.08);
            color: #10b981 !important;
            border-radius: 12px;
            margin: 0 10px;
        }

        .card {
            border: 1px solid rgba(0,0,0,0.05) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.06) !important;
        }

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
    </style>
    @stack('styles')
</head>

<body class="rtl">

    <!-- loader -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div>

    <!-- Sidebar Component -->
    @include('admin::layouts.hope-sidebar')

    <main class="main-content">
        <div class="position-relative">
            <!-- Header Component -->
            @include('admin::layouts.hope-header')
            
            <div class="iq-navbar-header" style="height: 180px;">
                <div class="container-fluid iq-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flex-wrap d-flex justify-content-between align-items-center pt-4">
                                @hasSection('page-title')
                                <div class="page-title-content">
                                    <h1 class="text-white mb-1">@yield('page-title')</h1>
                                    <p class="text-white opacity-75 mb-0 small fw-bold">
                                        <i class="bi bi-info-circle me-1"></i> @yield('page-subtitle')
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
                <div class="iq-header-img">
                     <img src="{{ asset('assets/hope-ui/images/dashboard/top-header.png') }}" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">
                </div>
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
                    ©<script>document.write(new Date().getFullYear())</script> {{ env('APP_NAME') }}, Made with <span class="text-gray border-gray"> Hope UI</span>
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
    </script>

    @stack('scripts')
</body>
</html>
