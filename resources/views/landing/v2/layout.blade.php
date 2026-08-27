<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2E8B83">
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

    {{-- Dashboard-aligned typography: Cairo (AR) + Inter (EN) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- LCP preload: the real product visual --}}
    <link rel="preload" as="image" href="{{ asset('images/hero-dashboard.webp') }}" type="image/webp">

    <link rel="stylesheet" href="{{ asset('css/landing-v2.css') }}?v={{ filemtime(public_path('css/landing-v2.css')) }}">

    {{-- Inline Dark Mode Auto-Detection & Enhanced UI Styles --}}
    <script>
        (function() {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
            }
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
                if (event.matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            });
        })();

        window.pwaDeferredPrompt = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            window.pwaDeferredPrompt = e;
            window.dispatchEvent(new CustomEvent('pwa-prompt-available'));
        });
    </script>
    <style>
        /* Smooth scrolling and typography */
        html {
            font-family: 'Cairo', 'Inter', system-ui, -apple-system, sans-serif;
            scroll-behavior: smooth;
        }
        [dir="ltr"] body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        /* Dark mode enhanced backgrounds */
        .dark body {
            background-color: #0b1312;
            color: #f3fbfb;
        }
        .dark .bg-white {
            background-color: #111f1e !important;
        }
        .dark .v2-card {
            background-color: #111f1e;
            border-color: #1f3936;
        }
        .dark .v2-product-frame {
            background-color: #111f1e;
            border-color: #1f3936;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.6), 0 0 30px rgba(46, 139, 131, 0.15);
        }
        .dark .v2-browser-bar {
            background-color: #0d1817;
            border-color: #182e2c;
        }
        .dark .v2-node {
            background-color: #111f1e;
            border-color: #1f3936;
        }
        .dark .v2-node:hover {
            border-color: #2e8b83;
            background-color: #142524;
        }
        .dark .v2-preview {
            background-color: #0b1312;
            border-color: #182e2c;
        }
        .dark .v2-preview-toolbar {
            background-color: #111f1e;
            border-color: #182e2c;
        }
        .dark .v2-faq-item {
            background-color: #111f1e;
            border-color: #1f3936;
        }
        .dark .v2-faq-item:hover {
            border-color: #2e8b83;
        }
        .dark .v2-sticky-bar {
            background-color: rgba(17, 31, 30, 0.95);
            border-color: #1f3936;
        }
        /* Micro-animations */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-6px) rotate(0.5deg); }
        }
        .animate-float-slow {
            animation: floatSlow 5s ease-in-out infinite;
        }
        .animate-float-reverse {
            animation: floatSlow 6s ease-in-out infinite reverse;
        }
        /* Brand gradients */
        .bg-brand-gradient {
            background: linear-gradient(135deg, #2E8B83 0%, #1E5E58 100%);
        }
        .text-brand-gradient {
            background: linear-gradient(135deg, #2E8B83 0%, #4F7DF3 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        /* Feature hover */
        .v2-feature-card {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .v2-feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px -8px rgba(46, 139, 131, 0.16);
            border-color: #80c4bd;
        }
        .dark .v2-feature-card:hover {
            box-shadow: 0 16px 32px -8px rgba(0, 0, 0, 0.5), 0 0 20px rgba(46, 139, 131, 0.2);
            border-color: #2e8b83;
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden transition-colors duration-300">
    <div class="min-h-screen flex flex-col">
        @include('landing.v2.header')

        <main class="flex-grow">
            @include('landing.v2.hero')
            @include('landing.v2.ecosystem')
            @include('landing.v2.features')
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
