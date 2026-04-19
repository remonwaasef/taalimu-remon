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

        /* Unified Header Banner - Premium Emerald */
        .iq-navbar-header {
            padding: 0 !important;
            height: 180px !important;
            background: linear-gradient(135deg, #065f46 0%, #10b981 100%) !important;
            position: relative;
            overflow: hidden;
            border-bottom: none !important;
        }

        .iq-navbar-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 86c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm66 3c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm-46-73c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm0 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm0-60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.4;
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
            opacity: 0.03;
            filter: grayscale(100%);
            mix-blend-mode: overlay;
            object-fit: cover;
        }

        .iq-navbar-header .container-fluid {
            position: relative;
            z-index: 2;
        }

        .iq-navbar-header h1 {
            font-size: 2.2rem !important;
            font-weight: 800 !important;
            letter-spacing: -1px;
            margin-bottom: 0.5rem;
            color: #ffffff !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .iq-navbar-header p {
            font-size: 1.1rem !important;
            font-weight: 500 !important;
            color: rgba(255,255,255,0.85) !important;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: #ffffff !important;
            transition: all 0.3s ease !important;
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.25) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        /* Premium Buttons in Header */
        .iq-navbar-header .btn-primary {
            background-color: #10b981 !important;
            color: #ffffff !important;
            border: none;
            box-shadow: 0 4px 12px rgba(16,185,129,0.2) !important;
            font-weight: 700 !important;
            padding: 0.75rem 1.5rem !important;
            transition: all 0.3s ease !important;
        }

        .iq-navbar-header .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16,185,129,0.3) !important;
            background-color: #059669 !important;
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

        /* Sidebar active item - Soft Premium (All Levels) */
        .sidebar .nav-item .nav-link.active,
        .sidebar-default .sidebar-list .nav-item li > .nav-link.active {
            background: #ecfdf5 !important; /* Soft Pastel Green */
            color: #059669 !important; /* Deep Emerald */
            box-shadow: none !important;
            border-radius: 10px !important;
            font-weight: 700;
        }

        .sidebar .nav-item .nav-link.active i,
        .sidebar-default .sidebar-list .nav-item li > .nav-link.active i {
            color: #059669 !important;
        }
        
        .sidebar .nav-item .nav-link {
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar .nav-item .nav-link:hover:not(.active) {
            background: rgba(16, 185, 129, 0.05); /* Very light emerald tint on hover */
            color: #10b981 !important;
        }

        /* Header banner */
        .iq-header-img {
            background: transparent !important;
        }
        .iq-header-img img {
            opacity: 0.03;
            mix-blend-mode: overlay;
        }

        .iq-navbar-header h1, .iq-navbar-header p, .iq-navbar-header span {
            /* Now handled by top css block (darker colors) */
        }

        /* ===== Premium Form Controls ===== */
        .form-control, .form-select {
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 0.75rem 1rem !important;
            font-size: 0.9rem;
            font-weight: 500;
            color: #334155 !important;
            background-color: #ffffff !important;
            transition: all 0.25s ease;
        }
        .form-control::placeholder {
            color: #94a3b8 !important;
            font-weight: 400;
        }
        .form-control:focus, .form-select:focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.1) !important;
            background-color: #ffffff !important;
        }
        textarea.form-control {
            min-height: 100px;
        }
        .form-label, label {
            font-weight: 700 !important;
            font-size: 0.85rem !important;
            color: #1e293b !important;
            margin-bottom: 0.5rem !important;
        }
        .form-check-input:checked {
            background-color: #10b981 !important;
            border-color: #10b981 !important;
        }
        .input-group-text {
            border-radius: 12px !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #f8fafc !important;
            color: #64748b !important;
            font-weight: 600;
        }

        /* ===== Premium Tab Navigation ===== */
        .nav-tabs {
            border-bottom: none !important;
            gap: 0.25rem;
            background: #f1f5f9;
            border-radius: 14px;
            padding: 5px;
            display: inline-flex;
        }
        .nav-tabs .nav-link {
            border: none !important;
            border-radius: 10px !important;
            padding: 0.6rem 1.25rem !important;
            font-weight: 700 !important;
            font-size: 0.85rem !important;
            color: #64748b !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent !important;
        }
        .nav-tabs .nav-link:hover:not(.active) {
            color: #10b981 !important;
            background: rgba(16, 185, 129, 0.05) !important;
        }
        .nav-tabs .nav-link.active {
            background: #ffffff !important;
            color: #059669 !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06) !important;
        }
        .nav-pills .nav-link {
            border-radius: 10px !important;
            font-weight: 700 !important;
            font-size: 0.85rem !important;
            padding: 0.6rem 1.25rem !important;
            color: #64748b !important;
            transition: all 0.25s ease;
        }
        .nav-pills .nav-link.active {
            background: #10b981 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25) !important;
        }

        /* ===== Premium Breadcrumbs ===== */
        .breadcrumb {
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
            font-size: 0.85rem;
        }
        .breadcrumb-item a {
            color: #64748b !important;
            text-decoration: none !important;
            font-weight: 600;
        }
        .breadcrumb-item a:hover {
            color: #10b981 !important;
        }
        .breadcrumb-item.active {
            color: #1e293b !important;
            font-weight: 700;
        }

        /* ===== Premium Buttons ===== */
        .btn {
            border-radius: 12px !important;
            font-weight: 700 !important;
            font-size: 0.85rem !important;
            padding: 0.6rem 1.25rem !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            letter-spacing: 0.015em;
        }
        .btn-sm {
            padding: 0.4rem 0.9rem !important;
            font-size: 0.8rem !important;
            border-radius: 10px !important;
        }
        .btn-lg {
            padding: 0.85rem 1.75rem !important;
            font-size: 0.95rem !important;
            border-radius: 14px !important;
        }
        .btn-primary {
            background: #10b981 !important;
            border-color: #10b981 !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
        }
        .btn-primary:hover {
            background: #059669 !important;
            border-color: #059669 !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3) !important;
        }
        .btn-success {
            background: #10b981 !important;
            border-color: #10b981 !important;
        }
        .btn-light {
            background: #f1f5f9 !important;
            border: 1px solid #e2e8f0 !important;
            color: #475569 !important;
        }
        .btn-light:hover {
            background: #e2e8f0 !important;
            color: #1e293b !important;
        }

        /* ===== Premium Badges ===== */
        .badge {
            border-radius: 8px !important;
            font-weight: 700 !important;
            font-size: 0.72rem !important;
            padding: 0.35rem 0.75rem !important;
            letter-spacing: 0.3px;
        }
        .badge.bg-success, .badge.bg-primary {
            background: #ecfdf5 !important;
            color: #059669 !important;
        }
        .badge.bg-danger {
            background: #fef2f2 !important;
            color: #dc2626 !important;
        }
        .badge.bg-warning {
            background: #fffbeb !important;
            color: #d97706 !important;
        }
        .badge.bg-info {
            background: #f0f9ff !important;
            color: #0284c7 !important;
        }

        /* ===== Premium Pagination ===== */
        .pagination {
            gap: 4px;
        }
        .page-item .page-link {
            border: none !important;
            border-radius: 10px !important;
            color: #64748b !important;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 0.5rem 0.85rem;
            background: transparent;
            transition: all 0.2s ease;
        }
        .page-item .page-link:hover {
            background: #f1f5f9 !important;
            color: #10b981 !important;
        }
        .page-item.active .page-link {
            background: #10b981 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
        }

        /* ===== Premium Modals ===== */
        .modal-content {
            border: none !important;
            border-radius: 1.25rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
            overflow: hidden;
        }
        .modal-header {
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 1.25rem 1.5rem !important;
        }
        .modal-header .modal-title {
            font-weight: 800 !important;
            font-size: 1.1rem !important;
            color: #0f172a !important;
        }
        .modal-body {
            padding: 1.5rem !important;
        }
        .modal-footer {
            border-top: 1px solid #f1f5f9 !important;
            padding: 1rem 1.5rem !important;
        }

        /* ===== Premium Alerts ===== */
        .alert {
            border: none !important;
            border-radius: 14px !important;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 1rem 1.25rem !important;
        }
        .alert-success {
            background: #ecfdf5 !important;
            color: #065f46 !important;
        }
        .alert-danger {
            background: #fef2f2 !important;
            color: #991b1b !important;
        }
        .alert-warning {
            background: #fffbeb !important;
            color: #92400e !important;
        }
        .alert-info {
            background: #f0f9ff !important;
            color: #075985 !important;
        }

        /* ===== Premium Dropdowns ===== */
        .dropdown-menu {
            border: 1px solid #f1f5f9 !important;
            border-radius: 14px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
            padding: 0.5rem !important;
            animation: fadeInDown 0.15s ease;
        }
        .dropdown-item {
            border-radius: 10px !important;
            padding: 0.6rem 1rem !important;
            font-weight: 600 !important;
            font-size: 0.85rem !important;
            color: #475569 !important;
            transition: all 0.15s ease;
        }
        .dropdown-item:hover, .dropdown-item:focus {
            background: #f0fdf4 !important;
            color: #059669 !important;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== Upload / File zones ===== */
        .custom-file-input, input[type="file"] {
            border-radius: 12px !important;
        }

        /* ===== Section Title Typography ===== */
        h1, h2, h3, h4, h5, h6 {
            color: #0f172a;
        }
        .page-title, .card-title {
            font-weight: 800 !important;
            color: #0f172a !important;
        }

        /* Premium Table Styles */
        .table {
            border-collapse: separate !important;
            border-spacing: 0 0.5rem !important;
        }
        .table thead th, .table thead td {
            background-color: transparent !important;
            color: #64748b !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 0.75rem !important;
            letter-spacing: 0.5px;
            border-bottom: none !important;
            padding: 1rem 1.25rem !important;
        }
        .table tbody tr {
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            background-color: #f8fafc;
        }
        .table tbody td {
            border: none !important;
            border-top: 1px solid transparent !important;
            border-bottom: 1px solid transparent !important;
            padding: 1rem 1.25rem !important;
            vertical-align: middle;
        }
        /* Handle RTL and LTR for border radius on table rows */
        [dir="rtl"] .table tbody td:first-child,
        [dir="ltr"] .table tbody td:last-child {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }
        [dir="rtl"] .table tbody td:last-child,
        [dir="ltr"] .table tbody td:first-child {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        /* Card styling */
        .card {
            border: none;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05) !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important; /* Slate 200 */
            border-radius: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* Content overlap effect */
        .content-inner {
            margin-top: -3rem !important;
            position: relative;
            z-index: 10;
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
                            <div class="flex-wrap d-flex justify-content-between align-items-center pt-3 pb-3">
                                @hasSection('page-title')
                                <div>
                                    <h1 class="text-white">@yield('page-title')</h1>
                                    <p class="mb-0 text-white opacity-75">@yield('page-subtitle')</p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    @yield('page-actions')
                                </div>
                                @else
                                @php
                                    $hour = now()->format('H');
                                    $greeting = $hour < 12 ? __('center::messages.good_morning') : ($hour < 17 ? __('center::messages.good_afternoon') : __('center::messages.good_evening'));
                                    // Fallback if translations are missing
                                    if(str_starts_with($greeting, 'center::')) {
                                        $greeting = $hour < 12 ? 'صباح الخير' : ($hour < 17 ? 'مساء الخير' : 'طاب مساؤك');
                                    }
                                    
                                    $quotes = [
                                        app()->getLocale() == 'ar' ? 'جاهز لإدارة مركزك اليوم؟' : 'Ready to manage your center today?',
                                        app()->getLocale() == 'ar' ? 'التعليم هو أساس بناء المستقبل.' : 'Education is the foundation of the future.',
                                        app()->getLocale() == 'ar' ? 'كل إنجاز عظيم يبدأ بخطوة جادة.' : 'Every great achievement begins with a serious step.',
                                        app()->getLocale() == 'ar' ? 'مرحباً بك في لوحة تحكم المركز الشاملة.' : 'Welcome to your comprehensive center dashboard.',
                                    ];
                                    $randomQuote = $quotes[array_rand($quotes)];
                                @endphp
                                <div class="text-white">
                                    <h1 class="display-5 fw-bold mb-1">{{ $greeting }}، {{ auth('admin')->user()->name ?? auth()->user()->name ?? 'مديرنا' }}! 🌟</h1>
                                    <p class="opacity-75 fs-5 mb-0">{{ $randomQuote }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
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
