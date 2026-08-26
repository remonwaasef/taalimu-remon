<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#4F46E5">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="manifest" href="{{ asset('manifest.json') }}?v=3">
    <link rel="icon" type="image/png" href="{{ asset('images/brand/logo-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/logo-icon.png') }}">

    {!! SEO::generate() !!}

    {{-- Multilingual SEO --}}
    <link rel="alternate" hreflang="ar" href="{{ url()->current() }}?hl=ar" />
    <link rel="alternate" hreflang="en" href="{{ url()->current() }}?hl=en" />
    <link rel="alternate" hreflang="fr" href="{{ url()->current() }}?hl=fr" />
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}" />

    {{-- Dashboard-aligned typography: Tajawal (AR) + Inter (EN) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- LCP preload: the real product visual --}}
    <link rel="preload" as="image" href="{{ asset('images/hero-dashboard.webp') }}" type="image/webp">

    <link rel="stylesheet" href="{{ asset('css/landing-v2.css') }}?v={{ filemtime(public_path('css/landing-v2.css')) }}">

    <script>
        window.pwaDeferredPrompt = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            window.pwaDeferredPrompt = e;
            window.dispatchEvent(new CustomEvent('pwa-prompt-available'));
        });
    </script>
</head>
<body class="antialiased">
    <div class="min-h-screen flex flex-col">
        @include('landing.v2.header')

        <main class="flex-grow">
            @include('landing.v2.hero')
            @include('landing.v2.ecosystem')
            @include('landing.v2.value')
            @include('landing.v2.showcase')
            @include('landing.v2.pricing')
            @include('landing.v2.final-cta')
        </main>

        @include('landing.v2.sticky-cta')
        @include('landing.v2.footer')
    </div>

    @include('components.cookie-consent')

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Scroll reveal --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -32px 0px' });
            document.querySelectorAll('[data-reveal]').forEach((el) => observer.observe(el));
        });
    </script>

    {{-- Consent-gated conversion tracking (dataLayer-compatible, no third-party scripts) --}}
    <script>
        window.TaalimuTrack = window.TaalimuTrack || function (event, params) {
            try {
                if ((localStorage.getItem('gdpr_cookie_consent') || '') !== 'accepted') return;
                var payload = Object.assign({ event: event, ts: Date.now() }, params || {});
                if (Array.isArray(window.dataLayer)) window.dataLayer.push(payload);
                else { (window.TaalimuEvents = window.TaalimuEvents || []).push(payload); }
            } catch (e) { /* no-op */ }
        };
        document.addEventListener('click', function (e) {
            var el = e.target.closest('[data-track]');
            if (!el) return;
            TaalimuTrack(el.getAttribute('data-track'), { href: el.getAttribute('href') });
        }, false);
        document.addEventListener('DOMContentLoaded', function () {
            var marks = { 50: false, 90: false };
            window.addEventListener('scroll', function () {
                var p = window.pageYOffset / Math.max(1, document.body.scrollHeight - window.innerHeight);
                if (!marks[50] && p >= 0.5) { marks[50] = true; TaalimuTrack('v2_scroll_depth', { depth: 50 }); }
                if (!marks[90] && p >= 0.9) { marks[90] = true; TaalimuTrack('v2_scroll_depth', { depth: 90 }); }
            }, { passive: true });
        });
    </script>

    {{-- PWA: standalone users go straight to login --}}
    <script>
        if (window.matchMedia('(display-mode: standalone)').matches &&
            (window.location.pathname === '/' || window.location.pathname === '')) {
            window.location.href = '/login';
        }
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js?v=7')
                    .then((reg) => console.log('SW registered:', reg.scope))
                    .catch((err) => console.log('SW failed:', err));
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
