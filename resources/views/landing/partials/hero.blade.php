{{-- Hero Section — Dark Premium Design --}}
<section class="hero-dark pt-32 md:pt-40 lg:pt-44 pb-20 lg:pb-32" id="hero">
    <div dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
        class="container relative mx-auto px-4 lg:px-8 z-10 max-w-7xl">
        <div class="grid grid-cols-12 gap-6 lg:gap-12 items-center">

            {{-- Content Side --}}
            <div class="col-span-12 md:col-span-6 text-center md:text-start order-2 md:order-1 mt-8 md:mt-0">

                {{-- Badge --}}
                <div class="hero-badge mb-6 lg:mb-8" style="display:inline-flex;">
                    <span style="width:0.5rem; height:0.5rem; border-radius:50%; background:#34d399; display:inline-block; animation:pulse 2s infinite;"></span>
                    <span style="color:#6ee7b7; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em;">{{ __('landing.hero.badge') }}</span>
                </div>

                {{-- Headline --}}
                <h1 style="color:#ffffff !important; font-size:clamp(1.875rem, 5vw, 3.75rem); font-weight:900; line-height:1.1; margin-bottom:1.5rem; letter-spacing:-0.025em;">
                    {!! __('landing.hero.title', ['highlight' => '<span class="hero-highlight" style="color:#34d399 !important;">' . __('landing.hero.title_highlight', [], app()->getLocale()) . '</span>']) !!}
                </h1>

                {{-- Subtitle --}}
                <p style="color:rgba(226,232,240,0.9) !important; font-size:clamp(1rem, 2vw, 1.25rem); font-weight:500; line-height:1.7; margin-bottom:2.5rem; max-width:32rem;">
                    {{ __('landing.hero.subtitle') }}
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-center md:justify-start mb-12">
                    <a href="{{ route('register') }}" class="btn-landing-primary" style="width:100%; max-width:280px;">
                        <span>{{ __('landing.hero.cta_primary') }}</span>
                        <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-left' : 'fa-arrow-right' }}"></i>
                    </a>
                    <a href="#features" class="btn-landing-secondary" style="width:100%; max-width:220px;">
                        <i class="fas fa-play-circle" style="color:#34d399 !important;"></i>
                        <span>{{ __('landing.nav.features') }}</span>
                    </a>
                </div>

                {{-- Trust Badges --}}
                <div style="padding-top:1.5rem; border-top:1px solid rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; gap:2rem;" class="md:justify-start flex-wrap">
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <div style="width:2.5rem; height:2.5rem; border-radius:0.75rem; background:rgba(16,185,129,0.15); border:1px solid rgba(16,185,129,0.25); display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-bolt" style="color:#34d399;"></i>
                        </div>
                        <div>
                            <div style="color:rgba(148,163,184,0.8); font-size:0.7rem; font-weight:600; text-transform:uppercase; letter-spacing:0.08em;">سريع وعملي</div>
                            <div style="color:#e2e8f0; font-size:0.875rem; font-weight:700;">إعداد خلال دقيقتين</div>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <div style="width:2.5rem; height:2.5rem; border-radius:0.75rem; background:rgba(16,185,129,0.15); border:1px solid rgba(16,185,129,0.25); display:flex; align-items:center; justify-content:center;">
                            <i class="fab fa-whatsapp" style="color:#34d399;"></i>
                        </div>
                        <div>
                            <div style="color:rgba(148,163,184,0.8); font-size:0.7rem; font-weight:600; text-transform:uppercase; letter-spacing:0.08em;">تنبيهات فورية</div>
                            <div style="color:#e2e8f0; font-size:0.875rem; font-weight:700;">ربط مع الواتساب</div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Image Side --}}
            <div class="col-span-12 md:col-span-6 relative w-full order-1 md:order-2">

                {{-- Floating WhatsApp Card --}}
                <div class="hidden sm:block absolute top-4 -left-4 lg:top-6 lg:-left-8 z-30 animate-float" style="width:16rem;">
                    <div style="background:rgba(255,255,255,0.95); border-radius:1rem; overflow:hidden; box-shadow:0 20px 50px rgba(0,0,0,0.3); border:1px solid rgba(255,255,255,0.2);">
                        <div style="background:#059669; padding:0.625rem 1rem; display:flex; align-items:center; justify-content:space-between;">
                            <div style="display:flex; align-items:center; gap:0.5rem;">
                                <i class="fab fa-whatsapp" style="color:#ffffff; font-size:0.875rem;"></i>
                                <span style="color:#ffffff; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">{{ __('landing.hero.mockup.whatsapp.title') }}</span>
                            </div>
                            <span style="color:rgba(255,255,255,0.8); font-size:0.7rem; font-weight:600;">{{ __('landing.hero.mockup.whatsapp.now') }}</span>
                        </div>
                        <div style="padding:1rem; background:#ffffff;">
                            <div style="display:flex; gap:0.75rem; margin-bottom:0.75rem;">
                                <div style="width:2rem; height:2rem; border-radius:50%; background:#ecfdf5; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-user-check" style="color:#059669; font-size:0.625rem;"></i>
                                </div>
                                <p style="color:#0f172a !important; font-size:0.75rem; line-height:1.5; font-weight:600;">
                                    {{ __('landing.hero.mockup.whatsapp.message') }}
                                </p>
                            </div>
                            <div style="background:#059669; color:#ffffff; border-radius:0.75rem; padding:0.5rem 0.75rem; text-align:center; font-weight:700; font-size:0.75rem;">
                                {{ __('landing.hero.mockup.whatsapp.cta') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Floating QR Card --}}
                <div class="hidden sm:block absolute -bottom-4 -right-2 lg:-bottom-4 lg:-right-6 z-30" style="animation: float 7s ease-in-out 1s infinite;">
                    <div style="background:rgba(255,255,255,0.95); border-radius:1rem; padding:1rem 1.5rem; box-shadow:0 20px 50px rgba(0,0,0,0.3); display:flex; align-items:center; gap:1rem; border:1px solid rgba(255,255,255,0.2);">
                        <div style="width:3rem; height:3rem; border-radius:0.75rem; background:#059669; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(5,150,105,0.3);">
                            <i class="fas fa-qrcode" style="color:#ffffff; font-size:1.25rem;"></i>
                        </div>
                        <div>
                            <div style="color:#64748b; font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em;">{{ __('landing.hero.mockup.success.label') }}</div>
                            <div style="color:#0f172a; font-size:1.125rem; font-weight:900;">{{ __('landing.hero.mockup.success.amount') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Browser Mockup --}}
                <div class="relative z-20">
                    <div class="browser-mockup">
                        <div class="browser-mockup-bar">
                            <div style="display:flex; gap:0.375rem;">
                                <div class="browser-dot" style="background:#ef4444;"></div>
                                <div class="browser-dot" style="background:#f59e0b;"></div>
                                <div class="browser-dot" style="background:#22c55e;"></div>
                            </div>
                            <div style="flex:1; margin:0 1rem;">
                                <div style="background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.1); border-radius:0.5rem; padding:0.25rem 1rem; font-size:0.7rem; color:rgba(148,163,184,0.7); font-family:monospace; text-align:center; display:flex; align-items:center; justify-content:center; gap:0.375rem; max-width:200px; margin:0 auto;">
                                    <i class="fas fa-lock" style="font-size:0.5rem; color:#22c55e;"></i>
                                    <span>app.taalimu.com</span>
                                </div>
                            </div>
                        </div>
                        <div style="background:#f1f5f9; overflow:hidden;">
                            <img src="{{ asset('images/hero-dashboard.webp') }}" alt="Taalimu Dashboard"
                                style="width:100%; height:auto; display:block;">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>