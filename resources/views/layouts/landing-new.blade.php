<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}
    
    <!-- Multilingual SEO -->
    <link rel="alternate" hreflang="ar" href="{{ url()->current() }}?hl=ar" />
    <link rel="alternate" hreflang="en" href="{{ url()->current() }}?hl=en" />
    <link rel="alternate" hreflang="fr" href="{{ url()->current() }}?hl=fr" />
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/brand/logo-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/logo-icon.png') }}">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "WebApplication",
      "name": "Taalimu",
      "url": "https://taalimu.com",
      "logo": "https://taalimu.com/images/logo.png",
      "description": "{{ __('landing.hero.subtitle') }}",
      "applicationCategory": "EducationalApplication",
      "operatingSystem": "Web",
      "offer": {
        "@@type": "Offer",
        "price": "0",
        "priceCurrency": "USD"
      }
    }
    </script>

    <!-- Fonts - Optimized Loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Preload Hero Image for faster LCP -->
    @php
        $heroImage = match(app()->getLocale()) {
            'en' => 'hero-mockup-en.webp',
            'fr' => 'hero-mockup-fr.webp',
            default => 'hero-mockup-v2.webp',
        };
    @endphp
    <link rel="preload" as="image" href="{{ asset('images/' . $heroImage) }}" type="image/webp">
    
    <!-- Resource Hints -->
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- PWA Support -->
    <link rel="manifest" href="/manifest.json?v=3">
    <meta name="theme-color" content="#3A0CA3">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Taalimu">

    <script>
        // Global PWA State Handler
        window.pwaDeferredPrompt = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            window.pwaDeferredPrompt = e;
            // Dispatch custom event for components that are already loaded
            window.dispatchEvent(new CustomEvent('pwa-prompt-available'));
        });
    </script>

    <!-- Scripts -->
    @vite(['resources/css/landing-new.css'])
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom CSS Variables -->

</head>
<body class="font-sans antialiased text-foreground bg-background selection:bg-primary selection:text-white">
    <div class="min-h-screen flex flex-col">
        @include('landing.partials.header')

        <main class="flex-grow">
            @yield('content')
        </main>

        <x-pwa-install />

        @include('landing.partials.footer')
    </div>
    
    
    <!-- Cookie Consent Banner -->
    @include('components.cookie-consent')
    
    <!-- Alpine.js with Collapse plugin for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // SweetAlert2 Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: '{{ app()->getLocale() == "ar" ? "top-start" : "top-end" }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif
    </script>

    <!-- Service Worker Registration & PWA Redirection -->
    <script>
        // Redirect to login if opened as PWA from home page
        if (window.matchMedia('(display-mode: standalone)').matches && 
            (window.location.pathname === '/' || window.location.pathname === '')) {
            window.location.href = '/login';
        }

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js?v=6')
                    .then((reg) => console.log('SW registered:', reg.scope))
                    .catch((err) => console.log('SW failed:', err));
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
