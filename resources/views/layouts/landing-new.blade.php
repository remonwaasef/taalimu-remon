<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}?v=3">
    <meta name="theme-color" content="#2E8B83">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
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


    <!-- Fonts - Optimized Loading (Cairo for Arabic, Inter for Latin) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">    
    <!-- Preload Hero Image for faster LCP -->
    <link rel="preload" as="image" href="{{ asset('images/hero-dashboard.webp') }}" type="image/webp">
    
    <!-- FAQ Schema Structured Data for Rich Snippets -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        @foreach(__('landing.faq.items') as $index => $faqItem)
        {
          "@type": "Question",
          "name": "{{ addslashes($faqItem['q']) }}",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "{{ addslashes($faqItem['a']) }}"
          }
        }{{ $loop->last ? '' : ',' }}
        @endforeach
      ]
    }
    </script>
    
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
            window.dispatchEvent(new CustomEvent('pwa-prompt-available'));
        });
    </script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/landing-new.css') }}?v={{ filemtime(public_path('css/landing-new.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/network-monitor.css') }}">
    
    <style>
        [data-animate] {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        [data-animate="scale-in"] {
            opacity: 0;
            transform: scale(0.96) translateY(12px);
        }
        [data-animate].is-visible {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        @media (prefers-reduced-motion: reduce) {
            [data-animate] {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        }
    </style>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="font-sans antialiased landing-page bg-white text-slate-900">
    <div class="min-h-screen flex flex-col">
        @include('landing.partials.header')

        <main class="flex-grow">
            @yield('content')
        </main>

        @include('landing.partials.mobile-sticky-cta')

        <x-pwa-install />

        @include('landing.partials.footer')
    </div>
    
    <!-- Interactive Demo Walkthrough Modal -->
    <div
        x-data="{ isOpen: false }"
        @open-demo-modal.window="isOpen = true"
        @keydown.escape.window="isOpen = false"
        x-show="isOpen"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="demo-modal-title"
    >
        <!-- Backdrop -->
        <div
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity"
            @click="isOpen = false"
        ></div>

        <!-- Modal Dialog -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
            <div
                x-show="isOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-3xl bg-white text-start shadow-2xl transition-all sm:my-8 w-full max-w-3xl border border-slate-200"
                @click.stop
            >
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-5 sm:p-6 border-b border-slate-100 bg-slate-50/60">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 text-[#2E8B83] flex items-center justify-center text-base font-bold">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-[#2E8B83] uppercase tracking-wider block leading-none mb-1">
                                {{ __('landing.demo_modal.badge') }}
                            </span>
                            <h3 id="demo-modal-title" class="text-base sm:text-lg font-black text-slate-900 leading-none">
                                {{ __('landing.demo_modal.title') }}
                            </h3>
                        </div>
                    </div>
                    <button
                        @click="isOpen = false"
                        type="button"
                        class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center transition-colors"
                        aria-label="{{ __('landing.demo_modal.close_btn') }}"
                    >
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <!-- Modal Body Preview -->
                <div class="p-6">
                    <div class="relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 shadow-inner aspect-video flex items-center justify-center group mb-6">
                        <img src="{{ asset('images/hero-dashboard.webp') }}" alt="Taalimu Demo" class="w-full h-full object-cover opacity-80 group-hover:opacity-90 transition-opacity">
                        <!-- Play Overlay -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-slate-950/40 backdrop-blur-[2px] p-6 text-center">
                            <div class="w-16 h-16 rounded-full bg-[#2E8B83] text-white flex items-center justify-center text-2xl shadow-xl mb-3 transform group-hover:scale-110 transition-transform">
                                <i class="fas fa-play ms-1"></i>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-white mb-1">{{ __('landing.demo_modal.title') }}</h4>
                            <p class="text-xs text-slate-300 max-w-md">{{ __('landing.demo_modal.subtitle') }}</p>
                        </div>
                    </div>

                    <!-- Key Feature Bullets -->
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 mb-6">
                        <h4 class="text-xs font-bold text-slate-800 mb-3">{{ __('landing.demo_modal.features_title') }}</h4>
                        <ul class="space-y-2 text-xs font-semibold text-slate-600">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-emerald-500 mt-0.5"></i>
                                <span>{{ __('landing.demo_modal.feature_1') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-emerald-500 mt-0.5"></i>
                                <span>{{ __('landing.demo_modal.feature_2') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-emerald-500 mt-0.5"></i>
                                <span>{{ __('landing.demo_modal.feature_3') }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2 border-t border-slate-100">
                        <button
                            @click="isOpen = false"
                            type="button"
                            class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition-colors"
                        >
                            {{ __('landing.demo_modal.close_btn') }}
                        </button>
                        <a
                            href="{{ route('register') }}"
                            class="w-full sm:w-auto px-6 py-2.5 rounded-xl text-white font-extrabold text-xs text-decoration-none shadow-md hover:shadow-lg transition-all text-center flex items-center justify-center gap-1.5"
                            style="background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);"
                        >
                            <span>{{ __('landing.demo_modal.start_trial_btn') }}</span>
                            <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cookie Consent Banner -->
    @include('components.cookie-consent')
    
    <!-- Alpine.js with Collapse plugin for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
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

    <!-- Network Monitor -->
    <script src="{{ asset('js/network-monitor.js') }}"></script>
    <script>
        window.TaalimuNetwork.onStatusChange((isOnline) => {
            document.body.classList.toggle('is-network-offline', !isOnline);
        });
    </script>

    <!-- Scroll Animation Observer -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });

            document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));
        });
    </script>

    <!-- Consent-gated Conversion Event Tracking (no third-party scripts; dataLayer-compatible) -->
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
            var depthMarks = { 50: false, 90: false };
            window.addEventListener('scroll', function () {
                var progress = window.pageYOffset / Math.max(1, document.body.scrollHeight - window.innerHeight);
                if (!depthMarks[50] && progress >= 0.5) {
                    depthMarks[50] = true; TaalimuTrack('landing_scroll_depth', { depth: 50 });
                }
                if (!depthMarks[90] && progress >= 0.9) {
                    depthMarks[90] = true; TaalimuTrack('landing_scroll_depth', { depth: 90 });
                }
            }, { passive: true });
        });
    </script>
    @stack('scripts')
</body>
</html>
