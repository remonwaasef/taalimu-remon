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

    <!-- Taalimu Unified Premium Emerald Theme -->
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/taalimu-unified.css?v=' . time()) }}">
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
            <div class="iq-navbar-header" style="height: 240px;">
                <div class="container-fluid iq-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flex-wrap d-flex justify-content-between align-items-center pt-2 pb-5">
                                @hasSection('page-title')
                                <div>
                                    <h1 class="text-white">@yield('page-title')</h1>
                                    <p class="mb-0 text-white opacity-75">@yield('page-subtitle')</p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    @yield('page-actions')
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid content-inner py-0">
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
    
    @stack('modals')

    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('assets/hope-ui/js/libs.min.js') }}"></script>
    
    <!-- app script -->
    <script src="{{ asset('assets/hope-ui/js/hope-ui.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
