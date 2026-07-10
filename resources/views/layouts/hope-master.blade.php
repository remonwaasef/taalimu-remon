<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    @yield('favicon')
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" nonce="{{ $csp_nonce ?? '' }}">
    
    <!-- Hope UI CSS (from public/assets/hope-ui) -->
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/libs.min.css') }}" nonce="{{ $csp_nonce ?? '' }}">
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/hope-ui.css?v=1.1.0') }}" nonce="{{ $csp_nonce ?? '' }}">
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/custom.css?v=1.1.0') }}" nonce="{{ $csp_nonce ?? '' }}">
    @if(isset($tenant) && ($tenant->settings['appearance']['dark_mode'] ?? false))
        <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/dark.css?v=1.1.0') }}" nonce="{{ $csp_nonce ?? '' }}">
    @elseif(isset($tenant->settings['appearance']['dark_mode']) && $tenant->settings['appearance']['dark_mode'])
        <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/dark.css?v=1.1.0') }}" nonce="{{ $csp_nonce ?? '' }}">
    @endif
    @if(app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/rtl.css?v=1.1.0') }}" nonce="{{ $csp_nonce ?? '' }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/customizer.css?v=1.1.0') }}" nonce="{{ $csp_nonce ?? '' }}">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" nonce="{{ $csp_nonce ?? '' }}" />
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" nonce="{{ $csp_nonce ?? '' }}"></script>

    <!-- Taalimu Unified Premium Emerald Theme -->
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/taalimu-unified.css?v=' . time()) }}" nonce="{{ $csp_nonce ?? '' }}">
    <!-- Network Monitor Styles -->
    <link rel="stylesheet" href="{{ asset('css/network-monitor.css') }}" nonce="{{ $csp_nonce ?? '' }}">
    
    @yield('head_extra')
    @stack('styles')
</head>

<body class="{{ (isset($tenant) && ($tenant->settings['appearance']['dark_mode'] ?? false)) ? 'dark' : '' }} {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} @yield('body_class')">

    <!-- Sidebar Component -->
    @yield('sidebar')

    <main class="main-content">
        <div class="position-relative">
            <!-- Header Component -->
            @yield('header')
            
            <!-- Sub Header Banner -->
            @yield('sub-header')
        </div>

        <div class="container-fluid content-inner py-0 @yield('content_inner_class')">
            <x-flash-messages />

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-body">
                <ul class="left-panel list-inline mb-0 p-0">
                    <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                    @yield('footer_left_extra')
                </ul>
                <div class="right-panel">
                    ©{{ date('Y') }} {{ config('app.name') }}, Made with <span class="text-gray border-gray"> Hope UI</span>
                </div>
            </div>
        </footer>
    </main>

    @stack('modals')

    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('assets/hope-ui/js/libs.min.js') }}"></script>
    
    <!-- app script -->
    <script src="{{ asset('assets/hope-ui/js/hope-ui.js') }}"></script>

    <!-- Network Monitor (Real Connectivity Detection) -->
    <script src="{{ asset('js/network-monitor.js') }}"></script>
    <script nonce="{{ $csp_nonce ?? '' }}">
        if (window.TaalimuNetwork) {
            window.TaalimuNetwork.onStatusChange((isOnline) => {
                document.body.classList.toggle('is-network-offline', !isOnline);
            });
        }
    </script>
    
    @yield('scripts_extra')
    @stack('scripts')
</body>
</html>
