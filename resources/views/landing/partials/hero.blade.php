{{-- Hero Section — Premium, Animated, Arabic-first, RTL-first --}}
<section id="hero" class="hero-section" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" style="
    position: relative;
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 50%, #f0fdf4 100%);
    padding: 6rem 0 4rem 0;
    overflow: hidden;
    color: #0f172a;
    border-bottom: 1px solid #e2e8f0;
">
    {{-- Soft Ambient Glows --}}
    <div style="position: absolute; inset: 0; pointer-events: none; overflow: hidden; z-index: 1;">
        <div style="position: absolute; top: -80px; {{ app()->getLocale() == 'ar' ? 'right: 10%;' : 'left: 10%;' }} width: 500px; height: 500px; background: radial-gradient(circle, rgba(46, 139, 131, 0.08) 0%, transparent 70%);"></div>
        <div style="position: absolute; bottom: -80px; {{ app()->getLocale() == 'ar' ? 'left: 5%;' : 'right: 5%;' }} width: 400px; height: 400px; background: radial-gradient(circle, rgba(34, 197, 94, 0.06) 0%, transparent 70%);"></div>
        <div style="position: absolute; top: 50%; {{ app()->getLocale() == 'ar' ? 'left: 5%;' : 'right: 5%;' }} width: 300px; height: 300px; background: radial-gradient(circle, rgba(79, 125, 243, 0.05) 0%, transparent 70%); transform: translateY(-50%);"></div>
    </div>

    {{-- Floating Animation Elements --}}
    <div class="floating-qr" style="
        position: absolute;
        top: 15%;
        {{ app()->getLocale() == 'ar' ? 'right: 2%;' : 'left: 2%;' }}
        width: 80px;
        height: 80px;
        background: #2E8B83;
        border-radius: 12px;
        opacity: 0.06;
        animation: float-slow 8s ease-in-out infinite;
        z-index: 2;
    " aria-hidden="true"></div>
    <div class="floating-phone" style="
        position: absolute;
        bottom: 10%;
        {{ app()->getLocale() == 'ar' ? 'left: 3%;' : 'right: 3%;' }}
        width: 60px;
        height: 100px;
        border: 3px solid #2E8B83;
        border-radius: 16px;
        opacity: 0.05;
        animation: float-slow 10s ease-in-out infinite reverse;
        z-index: 2;
    " aria-hidden="true"></div>

    {{-- Main Container --}}
    <div style="
        position: relative;
        z-index: 10;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    ">
        <div style="
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 3rem;
        ">
            
            {{-- TEXT COLUMN --}}
            <div style="
                flex: 1 1 480px;
                max-width: 600px;
                width: 100%;
                text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
            " data-animate="fade-up">
                
                {{-- 1. Trust Badge --}}
                <div style="
                    display: inline-flex;
                    align-items: center;
                    gap: 0.625rem;
                    padding: 0.5rem 1.25rem;
                    border-radius: 9999px;
                    background: #E6F4F3;
                    border: 1px solid #B2DDD9;
                    margin-bottom: 1.25rem;
                    box-shadow: 0 2px 8px rgba(46, 139, 131, 0.06);
                ">
                    <span style="width: 0.5rem; height: 0.5rem; border-radius: 50%; background: #2E8B83; display: inline-block; animation: pulse-soft 2s ease-in-out infinite;"></span>
                    <span style="color: #25746D !important; font-size: 0.825rem; font-weight: 800; letter-spacing: 0.01em;">
                        {{ __('landing.hero.badge') }}
                    </span>
                </div>

                {{-- 2. Value-First Headline --}}
                <h1 style="
                    color: #0f172a !important;
                    font-size: clamp(2rem, 4vw, 3rem);
                    font-weight: 900;
                    line-height: 1.18;
                    margin: 0 0 1.25rem 0;
                    letter-spacing: -0.025em;
                ">
                    {{ __('landing.hero.headline') }}
                    <span style="
                        color: #2E8B83 !important;
                        display: block;
                        margin-top: 0.375rem;
                        background: linear-gradient(135deg, #2E8B83 0%, #16a34a 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                    ">
                        {{ __('landing.hero.headline_highlight') }}
                    </span>
                </h1>

                {{-- 3. Subheadline --}}
                <p style="
                    color: #475569 !important;
                    font-size: clamp(1rem, 1.2vw, 1.125rem);
                    font-weight: 500;
                    line-height: 1.7;
                    margin: 0 0 1.75rem 0;
                    max-width: 540px;
                ">
                    {{ __('landing.hero.description') }}
                </p>

                {{-- 4. Supporting Hook --}}
                <div style="
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    padding: 0.75rem 1rem;
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 12px;
                    margin-bottom: 1.75rem;
                    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
                " data-animate="fade-up" data-delay="100">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}" style="color: #2E8B83; font-size: 1.1rem;"></i>
                    <span style="color: #0f172a; font-size: 0.9rem; font-weight: 600;">{{ __('landing.hero.supporting_hook') }}</span>
                </div>

                {{-- 5. CTA Buttons --}}
                <div style="
                    display: flex;
                    flex-wrap: wrap;
                    gap: 1rem;
                    align-items: center;
                    margin-bottom: 2rem;
                ">
                    <a href="{{ route('register') }}" class="btn-hero-primary" style="
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.75rem;
                        padding: 1rem 2.25rem;
                        background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);
                        color: #ffffff !important;
                        font-weight: 800;
                        font-size: 1rem;
                        border-radius: 1rem;
                        text-decoration: none;
                        box-shadow: 0 12px 32px rgba(46, 139, 131, 0.32);
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                    " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 16px 40px rgba(46,139,131,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 12px 32px rgba(46,139,131,0.32)'">
                        <span style="color: #ffffff !important;">{{ __('landing.hero.cta_free') }}</span>
                        <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}" style="color: #ffffff !important; font-size: 0.95rem;"></i>
                    </a>

                    <a href="#features" class="btn-hero-secondary" style="
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.625rem;
                        padding: 1rem 1.75rem;
                        background: #ffffff;
                        border: 1.5px solid #cbd5e1;
                        color: #0f172a !important;
                        font-weight: 700;
                        font-size: 0.95rem;
                        border-radius: 1rem;
                        text-decoration: none;
                        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
                        transition: border-color 0.2s ease, box-shadow 0.2s ease;
                    " onmouseover="this.style.borderColor='#2E8B83'; this.style.boxShadow='0 8px 24px rgba(46,139,131,0.12)'" onmouseout="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'">
                        <i class="fas fa-play-circle" style="color: #2E8B83; font-size: 1.2rem;"></i>
                        <span style="color: #0f172a !important;">{{ __('landing.hero.cta_demo') }}</span>
                    </a>
                </div>

                {{-- 6. Trust Checklist --}}
                <div style="
                    display: flex;
                    flex-wrap: wrap;
                    gap: 1rem 1.5rem;
                    padding-top: 1.5rem;
                    border-top: 1px solid #e2e8f0;
                    margin-bottom: 1.5rem;
                " data-animate="fade-up" data-delay="200">
                    <div style="display: flex; align-items: center; gap: 0.4rem; color: #334155; font-size: 0.825rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_nocard') }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.4rem; color: #334155; font-size: 0.825rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_setup') }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.4rem; color: #334155; font-size: 0.825rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_trial') }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.4rem; color: #334155; font-size: 0.825rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_arabic') }}</span>
                    </div>
                </div>

                {{-- 7. Value Pillars --}}
                <div style="
                    display: flex;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 1.5rem 1.25rem;
                " data-animate="fade-up" data-delay="300">
                    <div>
                        <div style="font-size: 1.35rem; font-weight: 900; color: #2E8B83;">{{ __('landing.hero.pillar1_title') }}</div>
                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">{{ __('landing.hero.pillar1_sub') }}</div>
                    </div>
                    <div class="pillar-divider" style="width: 1px; height: 2rem; background: #cbd5e1;"></div>
                    <div>
                        <div style="font-size: 1.35rem; font-weight: 900; color: #0f172a;">{{ __('landing.hero.pillar2_title') }}</div>
                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">{{ __('landing.hero.pillar2_sub') }}</div>
                    </div>
                    <div class="pillar-divider" style="width: 1px; height: 2rem; background: #cbd5e1;"></div>
                    <div>
                        <div style="font-size: 1.35rem; font-weight: 900; color: #2E8B83;">{{ __('landing.hero.pillar3_title') }}</div>
                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">{{ __('landing.hero.pillar3_sub') }}</div>
                    </div>
                </div>

            </div>

            {{-- VISUAL COLUMN --}}
            <div style="
                flex: 1 1 480px;
                max-width: 580px;
                width: 100%;
                position: relative;
            " data-animate="scale-in" data-delay="200">
                {{-- Main Dashboard Mockup --}}
                <div style="
                    position: relative;
                    width: 100%;
                    border-radius: 1.25rem;
                    overflow: hidden;
                    border: 1px solid #cbd5e1;
                    box-shadow: 
                        0 25px 50px -12px rgba(15, 23, 42, 0.15),
                        0 0 0 1px rgba(255,255,255,0.5) inset;
                    background: #ffffff;
                ">
                    {{-- Browser Header Bar --}}
                    <div style="
                        padding: 0.625rem 1rem;
                        background: #f1f5f9;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        border-bottom: 1px solid #e2e8f0;
                    ">
                        <div style="display: flex; gap: 0.375rem;">
                            <span style="width: 0.625rem; height: 0.625rem; border-radius: 50%; background: #ef4444; display: inline-block;"></span>
                            <span style="width: 0.625rem; height: 0.625rem; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
                            <span style="width: 0.625rem; height: 0.625rem; border-radius: 50%; background: #22c55e; display: inline-block;"></span>
                        </div>
                        <div style="
                            padding: 0.2rem 0.85rem;
                            border-radius: 0.5rem;
                            background: #ffffff;
                            border: 1px solid #cbd5e1;
                            font-size: 0.7rem;
                            color: #475569;
                            font-family: monospace;
                            display: flex;
                            align-items: center;
                            gap: 0.375rem;
                        ">
                            <i class="fas fa-lock" style="font-size: 0.6rem; color: #2E8B83;"></i>
                            <span>app.taalimu.com</span>
                        </div>
                        <div style="width: 2rem;"></div>
                    </div>

                    {{-- Dashboard Screenshot --}}
                    <div style="position: relative; background: #ffffff; overflow: hidden;">
                        <img src="{{ asset('images/hero-dashboard.webp') }}" alt="{{ __('landing.hero.dashboard_alt') }}" style="width: 100%; height: auto; display: block;" loading="eager" fetchpriority="high">
                    </div>
                </div>

                {{-- Floating Mini Card 1: WhatsApp Notification --}}
                <div class="float-card-whatsapp" style="
                    position: absolute;
                    top: -1.5rem;
                    {{ app()->getLocale() == 'ar' ? 'right: -1rem;' : 'left: -1rem;' }}
                    background: rgba(255, 255, 255, 0.98);
                    border: 1px solid #B2DDD9;
                    border-radius: 1rem;
                    padding: 0.75rem 1rem;
                    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
                    z-index: 20;
                    display: flex;
                    align-items: center;
                    gap: 0.875rem;
                    max-width: 280px;
                    animation: float-gentle 6s ease-in-out infinite;
                " data-animate="slide-in-right" data-delay="400">
                    <div style="
                        width: 2.5rem;
                        height: 2.5rem;
                        border-radius: 0.625rem;
                        background: #E6F4F3;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                    ">
                        <i class="fab fa-whatsapp" style="color: #2E8B83; font-size: 1.25rem;"></i>
                    </div>
                    <div style="overflow: hidden;">
                        <div style="font-size: 0.825rem; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ __('landing.hero.card_whatsapp_title') }}</div>
                        <div style="font-size: 0.7rem; color: #2E8B83; font-weight: 700; margin-top: 0.125rem;">{{ __('landing.hero.card_whatsapp_sub') }}</div>
                    </div>
                </div>

                {{-- Floating Mini Card 2: QR Code --}}
                <div class="float-card-qr" style="
                    position: absolute;
                    bottom: -1.5rem;
                    {{ app()->getLocale() == 'ar' ? 'left: -1rem;' : 'right: -1rem;' }}
                    background: rgba(255, 255, 255, 0.98);
                    border: 1px solid #bfdbfe;
                    border-radius: 1rem;
                    padding: 0.75rem 1rem;
                    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
                    z-index: 20;
                    display: flex;
                    align-items: center;
                    gap: 0.875rem;
                    max-width: 280px;
                    animation: float-gentle 6s ease-in-out infinite 3s;
                " data-animate="slide-in-left" data-delay="500">
                    <div style="
                        width: 2.5rem;
                        height: 2.5rem;
                        border-radius: 0.625rem;
                        background: #eff6ff;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                    ">
                        <i class="fas fa-qrcode" style="color: #2563eb; font-size: 1.15rem;"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.825rem; font-weight: 800; color: #0f172a;">{{ __('landing.hero.card_qr_title') }}</div>
                        <div style="font-size: 0.7rem; color: #475569; font-weight: 600; margin-top: 0.125rem;">{{ __('landing.hero.card_qr_sub') }}</div>
                    </div>
                </div>

                {{-- Floating Mini Card 3: Parent Connection --}}
                <div class="float-card-parent" style="
                    position: absolute;
                    top: 50%;
                    {{ app()->getLocale() == 'ar' ? 'left: -1rem;' : 'right: -1rem;' }}
                    transform: translateY(-50%);
                    background: rgba(255, 255, 255, 0.98);
                    border: 1px solid #fde68a;
                    border-radius: 1rem;
                    padding: 0.75rem 1rem;
                    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
                    z-index: 20;
                    display: flex;
                    align-items: center;
                    gap: 0.875rem;
                    max-width: 280px;
                    animation: float-gentle 7s ease-in-out infinite 1.5s;
                " data-animate="slide-in-left" data-delay="600">
                    <div style="
                        width: 2.5rem;
                        height: 2.5rem;
                        border-radius: 0.625rem;
                        background: #fffbeb;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                    ">
                        <i class="fas fa-user-check" style="color: #f59e0b; font-size: 1.15rem;"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.825rem; font-weight: 800; color: #0f172a;">{{ __('landing.hero.card_parent_title') }}</div>
                        <div style="font-size: 0.7rem; color: #475569; font-weight: 600; margin-top: 0.125rem;">{{ __('landing.hero.card_parent_sub') }}</div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

{{-- Hero Animation Styles --}}
<style>
@keyframes float-slow {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    25% { transform: translate(20px, -20px) rotate(3deg); }
    50% { transform: translate(-15px, 15px) rotate(-2deg); }
    75% { transform: translate(15px, 10px) rotate(1deg); }
}

@keyframes float-gentle {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}

@keyframes pulse-soft {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

@media (prefers-reduced-motion: reduce) {
    .floating-qr,
    .floating-phone,
    .float-card-whatsapp,
    .float-card-qr,
    .float-card-parent {
        animation: none !important;
    }
}

@media (max-width: 1024px) {
    .float-card-whatsapp,
    .float-card-qr,
    .float-card-parent {
        position: relative !important;
        top: auto !important;
        bottom: auto !important;
        left: auto !important;
        right: auto !important;
        transform: none !important;
        margin-top: 1rem;
        max-width: 100%;
        animation: none !important;
    }
    .hero-visual-floating-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }
}
</style>