<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Models\SiteSetting::get('site_name', config('app.name')) }}</title>
    <meta name="description" content="{{ \App\Models\SiteSetting::get('site_description', __('landing.hero.subtitle')) }}">

    <!-- Fonts - Optimized Loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
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
    <link rel="manifest" href="/manifest.json?v=2">
    <meta name="theme-color" content="#3A0CA3">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Taalimu">

    <!-- Scripts -->
    @vite(['resources/css/landing-new.css'])
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom CSS Variables -->
    <style>
        :root {
            --dark-purple: 263 84% 21%;
            --primary-purple: 263 82% 30%;
            --light-purple: 263 76% 47%;
            --cyan: 191 100% 50%;
            --success-green: 160 100% 45%;
            --background: 225 33% 98%;
            --foreground: 240 22% 14%;
            --card: 0 0% 100%;
            --card-foreground: 240 22% 14%;
            --primary: 263 82% 30%;
            --primary-foreground: 0 0% 100%;
            --secondary: 263 84% 21%;
            --secondary-foreground: 0 0% 100%;
            --muted: 225 33% 95%;
            --muted-foreground: 240 6% 46%;
            --accent: 191 100% 50%;
            --accent-foreground: 240 22% 14%;
            --border: 263 30% 90%;
            --gradient-hero: linear-gradient(135deg, hsl(263 84% 21%) 0%, hsl(263 82% 30%) 50%, hsl(263 76% 47%) 100%);
        }
        
        body {
            font-family: 'Inter', 'Cairo', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 1rem;
            line-height: 1.5;
            color: #1a1a2e; /* Fallback dark color */
        }
        
        [dir="rtl"], .rtl {
            font-family: 'Cairo', 'Inter', sans-serif;
        }
        
        .gradient-hero {
            background: var(--gradient-hero);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, hsl(191 100% 50%) 0%, hsl(160 100% 45%) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Mesh Gradient - Performance Optimized */
        .mesh-gradient {
            background-color: #f8faff !important;
            background-image: linear-gradient(135deg, hsla(263, 84%, 95%, 1) 0%, hsla(191, 100%, 95%, 1) 100%) !important;
        }
        
        /* Animations Disabled for Performance */
        .animate-float,
        .animate-float-delayed,
        .animate-pulse-slow {
            animation: none !important;
        }
        
        .animate-float-slow {
            animation: none !important;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in-right {
            animation: fadeInRight 0.6s ease-out;
        }
        
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
</head>
<body class="font-sans antialiased text-foreground bg-background selection:bg-primary selection:text-white">
    <div class="min-h-screen flex flex-col">
        @include('landing.partials.header')

        <main class="flex-grow">
            @yield('content')
        </main>

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

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then((reg) => console.log('SW registered:', reg.scope))
                    .catch((err) => console.log('SW failed:', err));
            });
        }
    </script>
</body>
</html>
