<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}?v=3">
    <meta name="theme-color" content="#059669">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}
    
    <!-- Multilingual SEO -->
    <link rel="alternate" hreflang="ar" href="{{ url()->current() }}?hl=ar" />
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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">    
    <!-- Preload Hero Image for faster LCP -->
    <link rel="preload" as="image" href="{{ asset('images/hero-dashboard.webp') }}" type="image/webp">
    
    <!-- Resource Hints -->
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- PWA Support -->
    <meta name="mobile-web-app-capable" content="yes">
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
    <link rel="stylesheet" href="{{ asset('css/landing-new.css') }}?v={{ filemtime(public_path('css/landing-new.css')) }}">
    <!-- Network Monitor Styles -->
    <link rel="stylesheet" href="{{ asset('css/network-monitor.css') }}">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom CSS Variables & Landing Page Override -->
    <style>
        [x-cloak] { display: none !important; }

        /* ============================================================
           LANDING PAGE — GLOBAL COLOR & CONTRAST FIX
           Forces dark readable text, overriding compiled Tailwind HSL vars
           ============================================================ */

        /* Base Text — Force dark readable colors */
        .landing-page h1, .landing-page h2, .landing-page h3,
        .landing-page h4, .landing-page h5, .landing-page h6 {
            color: #0f172a !important;
            font-weight: 800;
        }
        .landing-page p {
            color: #334155 !important;
        }
        .landing-page .text-desc {
            color: #475569 !important;
        }

        /* Force specific overrides for known problematic classes */
        .landing-page .force-dark { color: #0f172a !important; }
        .landing-page .force-muted { color: #475569 !important; }
        .landing-page .force-white { color: #ffffff !important; }
        .landing-page .force-white p,
        .landing-page .force-white h1,
        .landing-page .force-white h2,
        .landing-page .force-white h3,
        .landing-page .force-white span { color: #ffffff !important; }
        .landing-page .force-white .text-desc { color: rgba(255,255,255,0.85) !important; }

        /* ============================================================
           PREMIUM HEADER
           ============================================================ */
        .landing-header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .landing-header.scrolled {
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .landing-header a.nav-link {
            color: #334155 !important;
            font-weight: 600;
            transition: color 0.2s;
        }
        .landing-header a.nav-link:hover { color: #059669 !important; }

        /* ============================================================
           HERO SECTION — Dark Premium Theme
           ============================================================ */
        .hero-dark {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f2720 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-dark::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(16,185,129,0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-dark::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(14,165,233,0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-dark h1, .hero-dark h2, .hero-dark h3 { color: #ffffff !important; }
        .hero-dark p { color: rgba(226,232,240,0.9) !important; }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            background: rgba(16,185,129,0.15);
            border: 1px solid rgba(16,185,129,0.3);
            color: #6ee7b7 !important;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .hero-highlight {
            color: #34d399 !important;
            position: relative;
        }
        .hero-highlight::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #34d399, #059669);
            border-radius: 2px;
        }

        /* ============================================================
           PREMIUM BUTTONS
           ============================================================ */
        .btn-landing-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: #ffffff !important;
            font-weight: 800;
            font-size: 1.125rem;
            border-radius: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 30px rgba(5,150,105,0.35);
            text-decoration: none;
        }
        .btn-landing-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(5,150,105,0.45);
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
        }
        .btn-landing-primary span, .btn-landing-primary i { color: #ffffff !important; }

        .btn-landing-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: rgba(255,255,255,0.1);
            border: 2px solid rgba(255,255,255,0.25);
            color: #ffffff !important;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            backdrop-filter: blur(4px);
        }
        .btn-landing-secondary:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.4);
            transform: translateY(-1px);
        }
        .btn-landing-secondary span, .btn-landing-secondary i { color: #ffffff !important; }

        .btn-landing-dark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: #0f172a;
            color: #ffffff !important;
            font-weight: 800;
            border-radius: 0.875rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(15,23,42,0.2);
        }
        .btn-landing-dark:hover {
            background: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(15,23,42,0.3);
        }
        .btn-landing-dark span, .btn-landing-dark i { color: #ffffff !important; }

        /* ============================================================
           PREMIUM CARDS
           ============================================================ */
        .landing-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .landing-card:hover {
            border-color: #a7f3d0;
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.08);
            transform: translateY(-4px);
        }
        .landing-card h3 { color: #0f172a !important; font-weight: 800; }
        .landing-card p { color: #475569 !important; }

        .landing-card-featured {
            background: #ffffff;
            border: 2px solid #059669;
            border-radius: 1.25rem;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(5,150,105,0.15);
            position: relative;
        }

        /* ============================================================
           PRICING TOGGLE
           ============================================================ */
        .pricing-toggle-container {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            padding: 0.375rem;
            border-radius: 1rem;
            border: 2px solid #e2e8f0;
        }
        .pricing-toggle-btn {
            padding: 0.625rem 1.5rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 700;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
            background: transparent;
            color: #475569;
            position: relative;
        }
        .pricing-toggle-btn.active {
            background: #0f172a;
            color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(15,23,42,0.2);
        }

        /* ============================================================
           SECTION STYLING
           ============================================================ */
        .section-light { background: #ffffff; }
        .section-alt { background: #f8fafc; }
        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #047857 !important;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .section-badge-dark {
            background: #0f172a;
            border: none;
            color: #6ee7b7 !important;
        }

        /* ============================================================
           STAT CARD (Pain Points)
           ============================================================ */
        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 2rem 1.5rem;
            text-align: center;
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px -8px rgba(0,0,0,0.08);
        }
        .stat-number {
            font-size: 3.5rem;
            font-weight: 900;
            letter-spacing: -0.05em;
            line-height: 1;
            margin-bottom: 0.75rem;
        }
        .stat-card h3 { color: #0f172a !important; font-weight: 700; }
        .stat-card p { color: #64748b !important; }

        /* ============================================================
           FAQ ACCORDION
           ============================================================ */
        .faq-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.3s;
        }
        .faq-item:hover { border-color: #a7f3d0; }
        .faq-item.active {
            border-color: #059669;
            box-shadow: 0 8px 25px rgba(5,150,105,0.08);
        }
        .faq-question {
            color: #0f172a !important;
            font-weight: 700;
        }
        .faq-answer {
            color: #475569 !important;
        }

        /* ============================================================
           STEP CARD (How It Works)
           ============================================================ */
        .step-circle {
            width: 5rem;
            height: 5rem;
            border-radius: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #ffffff;
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            margin: 0 auto 1.5rem;
            transition: transform 0.3s;
        }
        .step-circle:hover { transform: scale(1.1); }

        /* ============================================================
           FOOTER
           ============================================================ */
        .landing-footer h4 { color: #0f172a !important; }
        .landing-footer p { color: #64748b !important; }
        .landing-footer a { color: #64748b !important; transition: color 0.2s; }
        .landing-footer a:hover { color: #059669 !important; }

        /* ============================================================
           ANIMATIONS
           ============================================================ */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
        .animate-in { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-in-delay-1 { animation: fadeInUp 0.6s ease-out 0.1s forwards; opacity: 0; }
        .animate-in-delay-2 { animation: fadeInUp 0.6s ease-out 0.2s forwards; opacity: 0; }
        .animate-in-delay-3 { animation: fadeInUp 0.6s ease-out 0.3s forwards; opacity: 0; }
        .animate-float { animation: float 6s ease-in-out infinite; }

        /* Scroll reveal */
        [data-animate] { opacity: 0; transform: translateY(20px); transition: all 0.7s cubic-bezier(0.4,0,0.2,1); }
        [data-animate].is-visible { opacity: 1; transform: translateY(0); }
        [data-stagger] > * { opacity: 0; transform: translateY(15px); transition: all 0.5s cubic-bezier(0.4,0,0.2,1); }
        [data-stagger].is-visible > *:nth-child(1) { opacity: 1; transform: translateY(0); transition-delay: 0.05s; }
        [data-stagger].is-visible > *:nth-child(2) { opacity: 1; transform: translateY(0); transition-delay: 0.15s; }
        [data-stagger].is-visible > *:nth-child(3) { opacity: 1; transform: translateY(0); transition-delay: 0.25s; }
        [data-stagger].is-visible > *:nth-child(4) { opacity: 1; transform: translateY(0); transition-delay: 0.35s; }

        /* ============================================================
           BROWSER MOCKUP
           ============================================================ */
        .browser-mockup {
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 30px 60px -15px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.05);
        }
        .browser-mockup-bar {
            background: rgba(30,41,59,0.8);
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .browser-dot {
            width: 0.625rem;
            height: 0.625rem;
            border-radius: 50%;
        }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (max-width: 768px) {
            .hero-dark { padding-top: 7rem; padding-bottom: 3rem; }
            .stat-number { font-size: 2.5rem; }
            .btn-landing-primary { padding: 0.875rem 1.75rem; font-size: 1rem; }
        }

        /* Pricing check icon */
        .check-icon {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 50%;
            background: #ecfdf5;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #059669;
            font-size: 0.625rem;
        }
    </style>
</head>
<body class="font-sans antialiased landing-page" style="background:#ffffff; color:#0f172a;">
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
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
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
                navigator.serviceWorker.register('/service-worker.js?v=7')
                    .then((reg) => console.log('SW registered:', reg.scope))
                    .catch((err) => console.log('SW failed:', err));
            });
        }
    </script>

    <!-- Network Monitor (Real Connectivity Detection) -->
    <script src="{{ asset('js/network-monitor.js') }}"></script>
    <script>
        // Toggle body class for offline CSS effects
        window.TaalimuNetwork.onStatusChange((isOnline) => {
            document.body.classList.toggle('is-network-offline', !isOnline);
        });
    </script>

    <!-- V2: Scroll Animation Observer -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

            document.querySelectorAll('[data-animate], [data-stagger]').forEach(el => observer.observe(el));
        });
    </script>
    @stack('scripts')
    <x-cookie-consent />
</body>
</html>
