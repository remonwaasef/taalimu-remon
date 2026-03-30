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
        }
        .iq-header-img img {
            opacity: 0.08 !important;
            mix-blend-mode: overlay !important;
        }
        .iq-navbar-header h1, .iq-navbar-header p, .iq-navbar-header span {
            color: #ffffff !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        /* Sidebar Refinements */
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

        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border-radius: 1rem;
        }

        /* Quick Action Overlap Fix */
        .content-inner {
            margin-top: -3.5rem !important;
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
                            <div class="flex-wrap d-flex justify-content-between align-items-center">
                                @hasSection('page-title')
                                <div>
                                    <h1 class="text-white">@yield('page-title')</h1>
                                    <p class="text-white opacity-75 mb-0 small">@yield('page-subtitle')</p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
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
