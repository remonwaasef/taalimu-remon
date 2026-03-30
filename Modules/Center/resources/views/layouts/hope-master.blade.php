<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('center::messages.blade_0457'))</title>

    @if($tenant->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $tenant->favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/hope-ui/images/favicon.ico') }}">
    @endif
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Hope UI CSS (from public/assets/hope-ui) -->
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/libs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/hope-ui.css?v=1.1.0') }}">
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/custom.css?v=1.1.0') }}">
    @if(($tenant->settings['appearance']['dark_mode'] ?? false))
        <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/dark.css?v=1.1.0') }}">
    @endif
    @if(app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/rtl.css?v=1.1.0') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/customizer.css?v=1.1.0') }}">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
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
            color: #1e293b;
        }

        /* Arabic text specific font weight/style adjustment */
        [dir="rtl"] body {
            font-family: 'Cairo', sans-serif;
        }

        /* Unified Header Banner - Ultra Premium */
        .iq-navbar-header {
            padding: 2.5rem 0 !important;
            height: auto !important;
            min-height: 200px;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            position: relative;
            overflow: hidden;
        }
        
        .iq-navbar-header .iq-header-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .iq-navbar-header .iq-header-img img {
            opacity: 0.12;
            mix-blend-mode: overlay;
            object-fit: cover;
        }

        .iq-navbar-header .container-fluid {
            position: relative;
            z-index: 2;
        }

        .iq-navbar-header h1 {
            font-size: 1.85rem !important;
            font-weight: 800 !important;
            letter-spacing: -0.5px;
            margin-bottom: 0.25rem;
            color: #ffffff !important;
        }
        
        .iq-navbar-header p {
            font-size: 0.95rem !important;
            font-weight: 500 !important;
            color: rgba(255, 255, 255, 0.85) !important;
        }

        /* Premium Buttons in Header */
        .iq-navbar-header .btn-primary {
            background-color: #ffffff !important;
            color: #10b981 !important;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
            font-weight: 700 !important;
            padding: 0.75rem 1.5rem !important;
            transition: all 0.3s ease !important;
        }

        .iq-navbar-header .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
            background-color: #f8fafc !important;
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
        .btn-outline-primary {
            color: #10b981 !important;
            border-color: #10b981 !important;
        }
        .btn-outline-primary:hover {
            background-color: #10b981 !important;
            border-color: #10b981 !important;
            color: white !important;
        }
        .text-primary {
            color: #10b981 !important;
        }
        .border-primary {
            border-color: #10b981 !important;
        }
        a {
            color: #10b981;
            transition: all 0.2s ease;
        }
        a:hover {
            color: #059669;
        }

        /* Sidebar active item - Ultra Premium */
        .sidebar .navbar-nav > .nav-item > .nav-link.active {
            background: var(--primary-gradient) !important;
            color: white !important;
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.3) !important;
            border-radius: 14px;
            margin: 0 12px;
        }
        
        .sidebar .navbar-nav > .nav-item > .nav-link {
            padding: 12px 18px;
            border-radius: 14px;
            margin: 0 12px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar .navbar-nav > .nav-item > .nav-link:hover:not(.active) {
            background: rgba(16, 185, 129, 0.05); /* Very light emerald tint on hover */
            color: #10b981 !important;
        }

        /* Header banner */
        .iq-header-img {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #064e3b 100%) !important;
        }
        .iq-header-img img {
            opacity: 0.08;
            mix-blend-mode: overlay;
        }

        .iq-navbar-header h1, .iq-navbar-header p, .iq-navbar-header span {
            color: #ffffff !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        /* Form focus states */
        .form-control:focus, .form-select:focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.1) !important;
        }

        /* Card styling */
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.1);
            border-radius: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
        }

        /* Quick Action Overlap Fix */
        .content-inner {
            margin-top: -3.5rem !important;
        }

        /* Auto-style the first row containing title and actions over the green banner */
        .content-inner > .d-flex:first-child h2, 
        .content-inner > .row:first-child h2 {
            display: none !important; /* Hide redundant titles in content if we use header sections */
        }

        /* Scrollbar Theme */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.2);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.4);
        }
    </style>
    @stack('styles')
</head>

<body class="{{ ($tenant->settings['appearance']['dark_mode'] ?? false) ? 'dark' : '' }} {{ app()->getLocale() == 'ar' ? 'rtl' : '' }}">

    <!-- loader -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div>

    <!-- Sidebar Component -->
    @include('center::layouts.hope-sidebar')

    <main class="main-content">
        <div class="position-relative">
            <!-- Header Component -->
            @include('center::layouts.hope-header')
            
            <!-- Sub Header Banner -->
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
                <!-- Banner Image -->
                <div class="iq-header-img" style="background-color: var(--bs-primary);">
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
                    <li class="list-inline-item"><a href="#">Terms of Use</a></li>
                </ul>
                <div class="right-panel">
                    ©<script>document.write(new Date().getFullYear())</script> {{ env('APP_NAME') }}, Made with <span class="text-gray border-gray"> Hope UI</span>
                </div>
            </div>
        </footer>
    </main>

    <!-- Settings Wrapper for Hope UI (can be removed if customizing isn't needed by users) -->
    
    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('assets/hope-ui/js/libs.min.js') }}"></script>
    
    <!-- app script -->
    <script src="{{ asset('assets/hope-ui/js/hope-ui.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
