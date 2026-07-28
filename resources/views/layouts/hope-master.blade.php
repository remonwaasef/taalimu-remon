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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Hope UI CSS (from public/assets/hope-ui) -->
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/libs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/hope-ui.css?v=1.1.0') }}">
    <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/custom.css?v=1.1.0') }}">
    @if(isset($tenant) && ($tenant->settings['appearance']['dark_mode'] ?? false))
        <link rel="stylesheet" href="{{ asset('assets/hope-ui/css/dark.css?v=1.1.0') }}">
    @elseif(isset($tenant->settings['appearance']['dark_mode']) && $tenant->settings['appearance']['dark_mode'])
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
    <!-- Network Monitor Styles -->
    <link rel="stylesheet" href="{{ asset('css/network-monitor.css') }}">
    
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
        <footer class="footer" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-top: 1px solid rgba(99, 102, 241, 0.15); padding: 0; margin-top: auto;">
            <div class="footer-body" style="padding: 16px 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <ul class="left-panel list-inline mb-0 p-0" style="display: flex; align-items: center; gap: 8px; margin: 0; padding: 0; list-style: none;">
                        <li class="list-inline-item" style="margin: 0;">
                            <a href="{{ route('privacy') }}" style="color: #94a3b8; font-size: 12px; font-weight: 500; text-decoration: none; padding: 4px 10px; border-radius: 6px; transition: all 0.2s; border: 1px solid transparent;" onmouseover="this.style.color='#10b981';this.style.borderColor='rgba(16,185,129,0.3)';this.style.backgroundColor='rgba(16,185,129,0.05)'" onmouseout="this.style.color='#94a3b8';this.style.borderColor='transparent';this.style.backgroundColor='transparent'">
                                <i class="fas fa-shield-alt" style="margin-inline-end: 4px; font-size: 10px;"></i>Privacy Policy
                            </a>
                        </li>
                        @yield('footer_left_extra')
                    </ul>
                </div>
                <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 20px; font-size: 10px; font-weight: 700; color: #10b981; text-transform: uppercase; letter-spacing: 0.05em;">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block; animation: pulse 2s infinite;"></span>
                        Operational
                    </span>
                    <span style="color: #64748b; font-size: 12px; font-weight: 500;">
                        ©{{ date('Y') }} {{ config('app.name') }}
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; background: rgba(99, 102, 241, 0.08); border: 1px solid rgba(99, 102, 241, 0.2); border-radius: 6px; font-size: 10px; font-weight: 600; color: #818cf8;">
                        <i class="fas fa-code-branch" style="font-size: 9px;"></i> v{{ config('app.version', '1.0') }}
                    </span>
                </div>
            </div>
            <style>@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}</style>
        </footer>
    </main>

    @stack('modals')

    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('assets/hope-ui/js/libs.min.js') }}"></script>
    
    <!-- app script -->
    <script src="{{ asset('assets/hope-ui/js/hope-ui.js') }}"></script>

    <!-- Network Monitor (Real Connectivity Detection) -->
    <script src="{{ asset('js/network-monitor.js') }}"></script>
    <script>
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
