{{-- Hero Section — Compact & Refined SaaS Layout --}}
<section class="hero-section" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" style="
    position: relative;
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 60%, #f0fdf4 100%);
    padding: 5.5rem 0 3rem 0;
    overflow: hidden;
    color: #0f172a;
    border-bottom: 1px solid #e2e8f0;
">
    {{-- Soft Ambient Glows --}}
    <div style="position: absolute; inset: 0; pointer-events: none; overflow: hidden; z-index: 1;">
        <div style="position: absolute; top: -60px; {{ app()->getLocale() == 'ar' ? 'right: 15%;' : 'left: 15%;' }} width: 400px; height: 400px; background: radial-gradient(circle, rgba(46, 139, 131, 0.08) 0%, transparent 70%);"></div>
        <div style="position: absolute; bottom: -60px; {{ app()->getLocale() == 'ar' ? 'left: 10%;' : 'right: 10%;' }} width: 350px; height: 350px; background: radial-gradient(circle, rgba(34, 197, 94, 0.06) 0%, transparent 70%);"></div>
    </div>

    {{-- Main Container --}}
    <div style="
        position: relative;
        z-index: 10;
        max-width: 1140px;
        margin: 0 auto;
        padding: 0 1.25rem;
    ">
        <div style="
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 2.25rem;
        ">
            
            {{-- TEXT COLUMN --}}
            <div style="
                flex: 1 1 480px;
                max-width: 560px;
                width: 100%;
                text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
            ">
                
                {{-- 1. Trust Badge --}}
                <div style="
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    padding: 0.35rem 0.95rem;
                    border-radius: 9999px;
                    background: #E6F4F3;
                    border: 1px solid #B2DDD9;
                    margin-bottom: 1rem;
                    box-shadow: 0 2px 4px rgba(46, 139, 131, 0.04);
                ">
                    <span style="width: 0.45rem; height: 0.45rem; border-radius: 50%; background: #2E8B83; display: inline-block;"></span>
                    <span style="color: #25746D !important; font-size: 0.775rem; font-weight: 800; letter-spacing: 0.01em;">
                        {{ __('landing.hero.badge') }}
                    </span>
                </div>

                {{-- 2. Value-First Headline --}}
                <h1 style="
                    color: #0f172a !important;
                    font-size: clamp(1.75rem, 3.2vw, 2.5rem);
                    font-weight: 900;
                    line-height: 1.22;
                    margin: 0 0 1rem 0;
                    letter-spacing: -0.02em;
                ">
                    {{ __('landing.hero.headline') }}
                    <span style="
                        color: #2E8B83 !important;
                        display: block;
                        margin-top: 0.25rem;
                        background: linear-gradient(135deg, #2E8B83 0%, #16a34a 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                    ">
                        {{ __('landing.hero.headline_highlight') }}
                    </span>
                </h1>

                {{-- 3. Subheadline --}}
                <p style="
                    color: #475569 !important;
                    font-size: 1rem;
                    font-weight: 500;
                    line-height: 1.65;
                    margin: 0 0 1.5rem 0;
                    max-width: 500px;
                ">
                    {{ __('landing.hero.description') }}
                </p>

                {{-- 4. CTA Buttons --}}
                <div style="
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.75rem;
                    align-items: center;
                    margin-bottom: 1.5rem;
                ">
                    <a href="{{ route('register') }}" style="
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.625rem;
                        padding: 0.85rem 1.85rem;
                        background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);
                        color: #ffffff !important;
                        font-weight: 800;
                        font-size: 0.95rem;
                        border-radius: 0.75rem;
                        text-decoration: none;
                        box-shadow: 0 8px 20px rgba(46, 139, 131, 0.28);
                        transition: transform 0.2s;
                    " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <span style="color: #ffffff !important;">{{ __('landing.hero.cta_free') }}</span>
                        <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}" style="color: #ffffff !important; font-size: 0.85rem;"></i>
                    </a>

                    <a href="#outcome" style="
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.5rem;
                        padding: 0.85rem 1.35rem;
                        background: #ffffff;
                        border: 1px solid #cbd5e1;
                        color: #0f172a !important;
                        font-weight: 700;
                        font-size: 0.925rem;
                        border-radius: 0.75rem;
                        text-decoration: none;
                        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
                    ">
                        <i class="fas fa-play-circle" style="color: #2E8B83; font-size: 1.1rem;"></i>
                        <span style="color: #0f172a !important;">{{ __('landing.hero.cta_demo') }}</span>
                    </a>
                </div>

                {{-- 5. Checklist --}}
                <div style="
                    display: flex;
                    flex-wrap: wrap;
                    gap: 1rem;
                    padding-top: 1.125rem;
                    border-top: 1px solid #e2e8f0;
                    margin-bottom: 1.25rem;
                ">
                    <div style="display: flex; align-items: center; gap: 0.35rem; color: #334155; font-size: 0.775rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_nocard') }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.35rem; color: #334155; font-size: 0.775rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_setup') }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.35rem; color: #334155; font-size: 0.775rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_trial') }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.35rem; color: #334155; font-size: 0.775rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_arabic') }}</span>
                    </div>
                </div>

                {{-- 6. Value Pillars --}}
                <div style="
                    display: flex;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 1.25rem 1rem;
                ">
                    <div>
                        <div style="font-size: 1.15rem; font-weight: 900; color: #2E8B83;">{{ __('landing.hero.pillar1_title') }}</div>
                        <div style="font-size: 0.7rem; color: #64748b; font-weight: 600;">{{ __('landing.hero.pillar1_sub') }}</div>
                    </div>
                    <div class="pillar-divider" style="width: 1px; height: 1.5rem; background: #cbd5e1;"></div>
                    <div>
                        <div style="font-size: 1.15rem; font-weight: 900; color: #0f172a;">{{ __('landing.hero.pillar2_title') }}</div>
                        <div style="font-size: 0.7rem; color: #64748b; font-weight: 600;">{{ __('landing.hero.pillar2_sub') }}</div>
                    </div>
                    <div class="pillar-divider" style="width: 1px; height: 1.5rem; background: #cbd5e1;"></div>
                    <div>
                        <div style="font-size: 1.15rem; font-weight: 900; color: #2E8B83;">{{ __('landing.hero.pillar3_title') }}</div>
                        <div style="font-size: 0.7rem; color: #64748b; font-weight: 600;">{{ __('landing.hero.pillar3_sub') }}</div>
                    </div>
                </div>

            </div>

            {{-- VISUAL COLUMN --}}
            <div style="
                flex: 1 1 420px;
                max-width: 490px;
                width: 100%;
                position: relative;
            ">
                {{-- Mac Browser Frame --}}
                <div style="
                    position: relative;
                    width: 100%;
                    border-radius: 1rem;
                    overflow: hidden;
                    border: 1px solid #cbd5e1;
                    box-shadow: 0 18px 40px -10px rgba(15, 23, 42, 0.12);
                    background: #ffffff;
                ">
                    {{-- Browser Header Bar --}}
                    <div style="
                        padding: 0.5rem 0.85rem;
                        background: #f1f5f9;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        border-bottom: 1px solid #e2e8f0;
                    ">
                        <div style="display: flex; gap: 0.3rem;">
                            <span style="width: 0.5rem; height: 0.5rem; border-radius: 50%; background: #ef4444; display: inline-block;"></span>
                            <span style="width: 0.5rem; height: 0.5rem; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
                            <span style="width: 0.5rem; height: 0.5rem; border-radius: 50%; background: #22c55e; display: inline-block;"></span>
                        </div>
                        <div style="
                            padding: 0.15rem 0.65rem;
                            border-radius: 0.35rem;
                            background: #ffffff;
                            border: 1px solid #cbd5e1;
                            font-size: 0.65rem;
                            color: #475569;
                            font-family: monospace;
                            display: flex;
                            align-items: center;
                            gap: 0.3rem;
                        ">
                            <i class="fas fa-lock" style="font-size: 0.55rem; color: #2E8B83;"></i>
                            <span>app.taalimu.com</span>
                        </div>
                        <div style="width: 1.5rem;"></div>
                    </div>

                    {{-- Image Mockup --}}
                    <div style="position: relative; background: #ffffff; overflow: hidden;">
                        <img src="{{ asset('images/hero-dashboard.webp') }}" alt="Taalimu Dashboard" style="width: 100%; height: auto; display: block;">
                    </div>
                </div>

                {{-- Floating Mini Card 1 --}}
                <div style="
                    position: absolute;
                    top: -1rem;
                    {{ app()->getLocale() == 'ar' ? 'right: -0.75rem;' : 'left: -0.75rem;' }}
                    background: rgba(255, 255, 255, 0.98);
                    border: 1px solid #B2DDD9;
                    border-radius: 0.75rem;
                    padding: 0.55rem 0.85rem;
                    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.1);
                    z-index: 20;
                    display: flex;
                    align-items: center;
                    gap: 0.625rem;
                    max-width: 230px;
                ">
                    <div style="
                        width: 1.85rem;
                        height: 1.85rem;
                        border-radius: 0.4rem;
                        background: #E6F4F3;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                    ">
                        <i class="fab fa-whatsapp" style="color: #2E8B83; font-size: 1rem;"></i>
                    </div>
                    <div style="overflow: hidden;">
                        <div style="font-size: 0.725rem; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ __('landing.hero.card_whatsapp_title') }}</div>
                        <div style="font-size: 0.625rem; color: #2E8B83; font-weight: 700;">{{ __('landing.hero.card_whatsapp_sub') }}</div>
                    </div>
                </div>

                {{-- Floating Mini Card 2 --}}
                <div style="
                    position: absolute;
                    bottom: -1rem;
                    {{ app()->getLocale() == 'ar' ? 'left: -0.75rem;' : 'right: -0.75rem;' }}
                    background: rgba(255, 255, 255, 0.98);
                    border: 1px solid #bfdbfe;
                    border-radius: 0.75rem;
                    padding: 0.55rem 0.85rem;
                    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.1);
                    z-index: 20;
                    display: flex;
                    align-items: center;
                    gap: 0.625rem;
                    max-width: 230px;
                ">
                    <div style="
                        width: 1.85rem;
                        height: 1.85rem;
                        border-radius: 0.4rem;
                        background: #eff6ff;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                    ">
                        <i class="fas fa-qrcode" style="color: #2563eb; font-size: 0.95rem;"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.725rem; font-weight: 800; color: #0f172a;">{{ __('landing.hero.card_qr_title') }}</div>
                        <div style="font-size: 0.625rem; color: #475569; font-weight: 600;">{{ __('landing.hero.card_qr_sub') }}</div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>