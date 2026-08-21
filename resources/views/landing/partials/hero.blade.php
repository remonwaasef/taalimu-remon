{{-- Hero Section — High-Converting Transformation --}}
<section class="hero-section" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" style="
    position: relative;
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 60%, #f0fdf4 100%);
    padding: 7.5rem 0 5rem 0;
    overflow: hidden;
    color: #0f172a;
    border-bottom: 1px solid #e2e8f0;
">
    {{-- Soft Ambient Glows --}}
    <div style="position: absolute; inset: 0; pointer-events: none; overflow: hidden; z-index: 1;">
        <div style="position: absolute; top: -80px; {{ app()->getLocale() == 'ar' ? 'right: 15%;' : 'left: 15%;' }} width: 500px; height: 500px; background: radial-gradient(circle, rgba(46, 139, 131, 0.1) 0%, transparent 70%);"></div>
        <div style="position: absolute; bottom: -80px; {{ app()->getLocale() == 'ar' ? 'left: 10%;' : 'right: 10%;' }} width: 450px; height: 450px; background: radial-gradient(circle, rgba(34, 197, 94, 0.08) 0%, transparent 70%);"></div>
    </div>

    {{-- Main Container --}}
    <div style="
        position: relative;
        z-index: 10;
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 1.5rem;
    ">
        <div style="
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 3.5rem;
        ">
            
            {{-- TEXT COLUMN --}}
            <div style="
                flex: 1 1 520px;
                max-width: 620px;
                width: 100%;
                text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
            ">
                
                {{-- 1. Trust Badge --}}
                <div style="
                    display: inline-flex;
                    align-items: center;
                    gap: 0.625rem;
                    padding: 0.5rem 1.25rem;
                    border-radius: 9999px;
                    background: #E6F4F3;
                    border: 1px solid #B2DDD9;
                    margin-bottom: 1.5rem;
                    box-shadow: 0 2px 4px rgba(46, 139, 131, 0.05);
                ">
                    <span style="width: 0.5rem; height: 0.5rem; border-radius: 50%; background: #2E8B83; display: inline-block;"></span>
                    <span style="color: #25746D !important; font-size: 0.825rem; font-weight: 800; letter-spacing: 0.02em;">
                        {{ __('landing.hero.badge') }}
                    </span>
                    <i class="fas fa-check-circle" style="color: #2E8B83; font-size: 0.8rem;"></i>
                </div>

                {{-- 2. Value-First Headline --}}
                <h1 style="
                    color: #0f172a !important;
                    font-size: clamp(2.25rem, 4.5vw, 3.5rem);
                    font-weight: 900;
                    line-height: 1.18;
                    margin: 0 0 1.5rem 0;
                    letter-spacing: -0.025em;
                ">
                    {{ __('landing.hero.headline') }}
                    <span style="
                        color: #2E8B83 !important;
                        display: block;
                        margin-top: 0.35rem;
                        background: linear-gradient(135deg, #2E8B83 0%, #16a34a 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                    ">
                        {{ __('landing.hero.headline_highlight') }}
                    </span>
                </h1>

                {{-- 3. High-Converting Subheadline --}}
                <p style="
                    color: #475569 !important;
                    font-size: 1.15rem;
                    font-weight: 500;
                    line-height: 1.75;
                    margin: 0 0 2.25rem 0;
                    max-width: 560px;
                ">
                    {{ __('landing.hero.description') }}
                </p>

                {{-- 4. Primary & Secondary CTA Buttons --}}
                <div style="
                    display: flex;
                    flex-wrap: wrap;
                    gap: 1rem;
                    align-items: center;
                    margin-bottom: 2rem;
                ">
                    <a href="{{ route('register') }}" style="
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.75rem;
                        padding: 1.125rem 2.5rem;
                        background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);
                        color: #ffffff !important;
                        font-weight: 800;
                        font-size: 1.1rem;
                        border-radius: 0.875rem;
                        text-decoration: none;
                        box-shadow: 0 12px 30px rgba(46, 139, 131, 0.32);
                        transition: transform 0.2s, box-shadow 0.2s;
                    " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <span style="color: #ffffff !important;">{{ __('landing.hero.cta_free') }}</span>
                        <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}" style="color: #ffffff !important; font-size: 0.95rem;"></i>
                    </a>

                    <a href="#showcase" style="
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.625rem;
                        padding: 1.125rem 1.875rem;
                        background: #ffffff;
                        border: 1px solid #cbd5e1;
                        color: #0f172a !important;
                        font-weight: 700;
                        font-size: 1.05rem;
                        border-radius: 0.875rem;
                        text-decoration: none;
                        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
                        transition: background 0.2s, border-color 0.2s;
                    " onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#94a3b8';" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#cbd5e1';">
                        <i class="fas fa-play-circle" style="color: #2E8B83; font-size: 1.25rem;"></i>
                        <span style="color: #0f172a !important;">{{ __('landing.hero.cta_demo') }}</span>
                    </a>
                </div>

                {{-- 5. Frictionless Reassurance Checklist --}}
                <div style="
                    display: flex;
                    flex-wrap: wrap;
                    gap: 1.25rem;
                    padding-top: 1.5rem;
                    border-top: 1px solid #e2e8f0;
                    margin-bottom: 2rem;
                ">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #334155; font-size: 0.875rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_nocard') }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #334155; font-size: 0.875rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_setup') }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #334155; font-size: 0.875rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_trial') }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #334155; font-size: 0.875rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_arabic') }}</span>
                    </div>
                </div>

                {{-- 6. Value Pillars --}}
                <div style="
                    display: flex;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 1.5rem 1.25rem;
                ">
                    <div>
                        <div style="font-size: 1.35rem; font-weight: 900; color: #2E8B83;">{{ __('landing.hero.pillar1_title') }}</div>
                        <div style="font-size: 0.775rem; color: #64748b; font-weight: 600;">{{ __('landing.hero.pillar1_sub') }}</div>
                    </div>
                    <div class="pillar-divider" style="width: 1px; height: 2rem; background: #cbd5e1;"></div>
                    <div>
                        <div style="font-size: 1.35rem; font-weight: 900; color: #0f172a;">{{ __('landing.hero.pillar2_title') }}</div>
                        <div style="font-size: 0.775rem; color: #64748b; font-weight: 600;">{{ __('landing.hero.pillar2_sub') }}</div>
                    </div>
                    <div class="pillar-divider" style="width: 1px; height: 2rem; background: #cbd5e1;"></div>
                    <div>
                        <div style="font-size: 1.35rem; font-weight: 900; color: #2E8B83;">{{ __('landing.hero.pillar3_title') }}</div>
                        <div style="font-size: 0.775rem; color: #64748b; font-weight: 600;">{{ __('landing.hero.pillar3_sub') }}</div>
                    </div>
                </div>

            </div>

            {{-- VISUAL COLUMN: Dashboard + Live Simulated Automation --}}
            <div style="
                flex: 1 1 480px;
                max-width: 580px;
                width: 100%;
                position: relative;
            ">
                {{-- Mac Browser Frame --}}
                <div style="
                    position: relative;
                    width: 100%;
                    border-radius: 1.25rem;
                    overflow: hidden;
                    border: 1px solid #cbd5e1;
                    box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.15);
                    background: #ffffff;
                ">
                    {{-- Browser Header Bar --}}
                    <div style="
                        padding: 0.65rem 1rem;
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
                            border-radius: 0.375rem;
                            background: #ffffff;
                            border: 1px solid #cbd5e1;
                            font-size: 0.725rem;
                            color: #475569;
                            font-family: monospace;
                            display: flex;
                            align-items: center;
                            gap: 0.375rem;
                            box-shadow: inset 0 1px 2px rgba(0,0,0,0.03);
                        ">
                            <i class="fas fa-lock" style="font-size: 0.6rem; color: #2E8B83;"></i>
                            <span>app.taalimu.com/center</span>
                        </div>
                        <div style="width: 2rem;"></div>
                    </div>

                    {{-- Image Mockup / Real Dashboard Snapshot --}}
                    <div style="position: relative; background: #ffffff; overflow: hidden;">
                        <img src="{{ asset('images/hero-dashboard.webp') }}" alt="Taalimu Educational Center Dashboard" style="width: 100%; height: auto; display: block;">
                    </div>
                </div>

                {{-- Floating Automation Card 1: WhatsApp Notification --}}
                <div style="
                    position: absolute;
                    top: -1.25rem;
                    {{ app()->getLocale() == 'ar' ? 'right: -1rem;' : 'left: -1rem;' }}
                    background: rgba(255, 255, 255, 0.98);
                    border: 1px solid #B2DDD9;
                    border-radius: 0.875rem;
                    padding: 0.75rem 1rem;
                    box-shadow: 0 15px 30px rgba(15, 23, 42, 0.12);
                    z-index: 20;
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    max-width: 260px;
                ">
                    <div style="
                        width: 2.25rem;
                        height: 2.25rem;
                        border-radius: 0.5rem;
                        background: #E6F4F3;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                    ">
                        <i class="fab fa-whatsapp" style="color: #2E8B83; font-size: 1.25rem;"></i>
                    </div>
                    <div style="overflow: hidden;">
                        <div style="font-size: 0.775rem; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ __('landing.hero.card_whatsapp_title') }}</div>
                        <div style="font-size: 0.675rem; color: #2E8B83; font-weight: 700;">{{ __('landing.hero.card_whatsapp_sub') }}</div>
                    </div>
                </div>

                {{-- Floating Automation Card 2: QR Check-in --}}
                <div style="
                    position: absolute;
                    bottom: -1.25rem;
                    {{ app()->getLocale() == 'ar' ? 'left: -1rem;' : 'right: -1rem;' }}
                    background: rgba(255, 255, 255, 0.98);
                    border: 1px solid #bfdbfe;
                    border-radius: 0.875rem;
                    padding: 0.75rem 1rem;
                    box-shadow: 0 15px 30px rgba(15, 23, 42, 0.12);
                    z-index: 20;
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    max-width: 260px;
                ">
                    <div style="
                        width: 2.25rem;
                        height: 2.25rem;
                        border-radius: 0.5rem;
                        background: #eff6ff;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                    ">
                        <i class="fas fa-qrcode" style="color: #2563eb; font-size: 1.1rem;"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.775rem; font-weight: 800; color: #0f172a;">{{ __('landing.hero.card_qr_title') }}</div>
                        <div style="font-size: 0.675rem; color: #475569; font-weight: 600;">{{ __('landing.hero.card_qr_sub') }}</div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>