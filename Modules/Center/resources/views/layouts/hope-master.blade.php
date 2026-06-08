<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('center::dashboard.header.dashboard_title'))</title>

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
    <!-- Network Monitor Styles -->
    <link rel="stylesheet" href="{{ asset('css/network-monitor.css') }}">
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
            <div class="iq-navbar-header">
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
            <x-flash-messages />

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
                    ©{{ date('Y') }} {{ config('app.name') }}, Made with <span class="text-gray border-gray"> Hope UI</span>
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

    <!-- Network Monitor (Real Connectivity Detection) -->
    <script src="{{ asset('js/network-monitor.js') }}"></script>
    <script>
        window.TaalimuNetwork.onStatusChange((isOnline) => {
            document.body.classList.toggle('is-network-offline', !isOnline);
        });
    </script>
    
    <!-- Enterprise Reliability Scripts -->
    <script src="{{ asset('js/auto-save.js') }}"></script>
    <script src="{{ asset('js/instant-search.js') }}"></script>
    <script src="{{ asset('js/crash-recovery.js') }}"></script>
    <script src="{{ asset('js/status-indicators.js') }}"></script>
    <script src="{{ asset('js/image-compressor.js') }}"></script>
    <script src="{{ asset('js/keyboard-shortcuts.js') }}"></script>
    
    <!-- Beta Bug Report Widget -->
    @include('center::partials.bug-report-widget')
    <!-- Global Double Submit Prevention -->
    <script>
        document.addEventListener('submit', function(e) {
            if (e.target && e.target.tagName === 'FORM') {
                const submitBtn = e.target.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    // Prevent default only if the form is invalid (browser usually handles this, but just in case)
                    if (!e.target.checkValidity()) {
                        return;
                    }
                    
                    setTimeout(() => {
                        submitBtn.disabled = true;
                        const isDelete = e.target.querySelector('input[name="_method"][value="DELETE"]') != null;
                        const loadingText = isDelete ? 'جاري الحذف...' : 'جاري التنفيذ...';
                        
                        // Keep original width to avoid layout shift if possible
                        submitBtn.style.minWidth = submitBtn.offsetWidth + 'px';
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mx-1"></i> ' + loadingText;
                    }, 0);
                }
            }
        });
    </script>
</body>
</html>
