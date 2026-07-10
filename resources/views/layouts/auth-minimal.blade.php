<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" nonce="{{ $csp_nonce ?? '' }}">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin nonce="{{ $csp_nonce ?? '' }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" nonce="{{ $csp_nonce ?? '' }}">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" nonce="{{ $csp_nonce ?? '' }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" nonce="{{ $csp_nonce ?? '' }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet" nonce="{{ $csp_nonce ?? '' }}">
    
    <link rel="stylesheet" href="{{ asset('css/landing-new.css') }}" nonce="{{ $csp_nonce ?? '' }}">
    <!-- Network Monitor Styles -->
    <link rel="stylesheet" href="{{ asset('css/network-monitor.css') }}" nonce="{{ $csp_nonce ?? '' }}">
    @stack('styles')
</head>
<body class="auth-minimal-body">
    @yield('content')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" nonce="{{ $csp_nonce ?? '' }}"></script>
    <!-- Network Monitor -->
    <script src="{{ asset('js/network-monitor.js') }}" nonce="{{ $csp_nonce ?? '' }}"></script>
    <script nonce="{{ $csp_nonce ?? '' }}">
        window.TaalimuNetwork.onStatusChange((isOnline) => {
            document.body.classList.toggle('is-network-offline', !isOnline);
        });
    </script>
    @stack('scripts')
</body>
</html>
