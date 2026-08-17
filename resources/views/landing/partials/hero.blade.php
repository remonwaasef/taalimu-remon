{{-- Hero Section — Premium Light SaaS Redesign --}}
<section class="hero-section" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" style="
    position: relative;
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 50%, #f0fdf4 100%);
    padding: 7rem 0 5rem 0;
    overflow: hidden;
    color: #0f172a;
    border-bottom: 1px solid #e2e8f0;
">
    {{-- Soft Ambient Glows (Light Mode) --}}
    <div style="position: absolute; inset: 0; pointer-events: none; overflow: hidden; z-index: 1;">
        <div style="position: absolute; top: -80px; {{ app()->getLocale() == 'ar' ? 'right: 15%;' : 'left: 15%;' }} width: 500px; height: 500px; background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 70%);"></div>
        <div style="position: absolute; bottom: -80px; {{ app()->getLocale() == 'ar' ? 'left: 10%;' : 'right: 10%;' }} width: 450px; height: 450px; background: radial-gradient(circle, rgba(59, 130, 246, 0.05) 0%, transparent 70%);"></div>
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
            gap: 3rem;
        ">
            
            {{-- TEXT COLUMN --}}
            <div style="
                flex: 1 1 520px;
                max-width: 600px;
                width: 100%;
                text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
            ">
                
                {{-- 1. Badge --}}
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
                    <span style="color: #25746D !important; font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.03em;">
                        {{ __('landing.hero.badge_new') }}
                    </span>
                    <i class="fas fa-sparkles" style="color: #d97706; font-size: 0.75rem;"></i>
                </div>

                {{-- 2. Headline --}}
                <h1 style="
                    color: #0f172a !important;
                    font-size: clamp(2rem, 4vw, 3.25rem);
                    font-weight: 900;
                    line-height: 1.2;
                    margin: 0 0 1.5rem 0;
                    letter-spacing: -0.02em;
                ">
                    {{ __('landing.hero.headline') }}
                    <span style="
                        color: #2E8B83 !important;
                        display: block;
                        margin-top: 0.375rem;
                    ">
                        {{ __('landing.hero.headline_highlight') }}
                    </span>
                </h1>

                {{-- 3. Paragraph --}}
                <p style="
                    color: #475569 !important;
                    font-size: 1.125rem;
                    font-weight: 500;
                    line-height: 1.7;
                    margin: 0 0 2rem 0;
                    max-width: 540px;
                ">
                    {!! __('landing.hero.description') !!}
                </p>

                {{-- 4. CTA Buttons --}}
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
                        padding: 1rem 2.25rem;
                        background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);
                        color: #ffffff !important;
                        font-weight: 800;
                        font-size: 1.05rem;
                        border-radius: 0.875rem;
                        text-decoration: none;
                        box-shadow: 0 10px 25px rgba(46, 139, 131, 0.3);
                        transition: transform 0.2s, box-shadow 0.2s;
                    " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <span style="color: #ffffff !important;">{{ __('landing.hero.cta_free') }}</span>
                        <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}" style="color: #ffffff !important; font-size: 0.875rem;"></i>
                    </a>

                    <a href="#features" style="
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.625rem;
                        padding: 1rem 1.75rem;
                        background: #ffffff;
                        border: 1px solid #cbd5e1;
                        color: #0f172a !important;
                        font-weight: 700;
                        font-size: 1rem;
                        border-radius: 0.875rem;
                        text-decoration: none;
                        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
                        transition: background 0.2s, border-color 0.2s;
                    " onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#94a3b8';" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#cbd5e1';">
                        <i class="fas fa-play-circle" style="color: #2E8B83; font-size: 1.125rem;"></i>
                        <span style="color: #0f172a !important;">{{ __('landing.hero.cta_demo') }}</span>
                    </a>
                </div>

                {{-- 5. Benefits Checklist --}}
                <div style="
                    display: flex;
                    flex-wrap: wrap;
                    gap: 1.25rem;
                    padding-top: 1.5rem;
                    border-top: 1px solid #e2e8f0;
                    margin-bottom: 1.75rem;
                ">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #334155; font-size: 0.85rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_setup') }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #334155; font-size: 0.85rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_nocard') }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #334155; font-size: 0.85rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_support') }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #334155; font-size: 0.85rem; font-weight: 700;">
                        <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                        <span>{{ __('landing.hero.check_trial') }}</span>
                    </div>
                </div>

                {{-- 6. Product Value Pillars (Honest & High Value) --}}
                <div style="
                    display: flex;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 1.5rem 1rem;
                ">
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 900; color: #2E8B83;">{{ __('landing.hero.pillar1_title') }}</div>
                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">{{ __('landing.hero.pillar1_sub') }}</div>
                    </div>
                    <div class="pillar-divider" style="width: 1px; height: 2rem; background: #cbd5e1;"></div>
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 900; color: #0f172a;">{{ __('landing.hero.pillar2_title') }}</div>
                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">{{ __('landing.hero.pillar2_sub') }}</div>
                    </div>
                    <div class="pillar-divider" style="width: 1px; height: 2rem; background: #cbd5e1;"></div>
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 900; color: #2E8B83;">{{ __('landing.hero.pillar3_title') }}</div>
                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">{{ __('landing.hero.pillar3_sub') }}</div>
                    </div>
                </div>

            </div>

            {{-- VISUAL MOCKUP COLUMN --}}
            <div style="
                flex: 1 1 480px;
                max-width: 560px;
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
                    box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.12);
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
                            padding: 0.2rem 0.75rem;
                            border-radius: 0.375rem;
                            background: #ffffff;
                            border: 1px solid #cbd5e1;
                            font-size: 0.7rem;
                            color: #475569;
                            font-family: monospace;
                            display: flex;
                            align-items: center;
                            gap: 0.375rem;
                            box-shadow: inset 0 1px 2px rgba(0,0,0,0.03);
                        ">
                            <i class="fas fa-lock" style="font-size: 0.55rem; color: #2E8B83;"></i>
                            <span>app.taalimu.com</span>
                        </div>
                        <div style="width: 2rem;"></div>
                    </div>

                    {{-- Image Mockup --}}
                    <div style="position: relative; background: #ffffff; overflow: hidden;">
                        <img src="{{ asset('images/hero-dashboard.webp') }}" alt="Taalimu Dashboard" style="width: 100%; height: auto; display: block;">
                    </div>
                </div>

                {{-- Floating Mini Cards (Clean, Light Glass Cards) --}}
                
                {{-- Card 1: WhatsApp Notification --}}
                <div style="
                    position: absolute;
                    top: -1.25rem;
                    {{ app()->getLocale() == 'ar' ? 'right: -1rem;' : 'left: -1rem;' }}
                    background: rgba(255, 255, 255, 0.98);
                    border: 1px solid #B2DDD9;
                    border-radius: 0.875rem;
                    padding: 0.75rem 1rem;
                    box-shadow: 0 15px 30px rgba(15, 23, 42, 0.1);
                    z-index: 20;
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    width: 210px;
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
                        <i class="fab fa-whatsapp" style="color: #2E8B83; font-size: 1.125rem;"></i>
                    </div>
                    <div style="overflow: hidden;">
                        <div style="font-size: 0.75rem; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ __('landing.hero.card_whatsapp_title') }}</div>
                        <div style="font-size: 0.65rem; color: #2E8B83; font-weight: 700;">{{ __('landing.hero.card_whatsapp_sub') }}</div>
                    </div>
                </div>

                {{-- Card 2: QR Attendance --}}
                <div style="
                    position: absolute;
                    bottom: -1.25rem;
                    {{ app()->getLocale() == 'ar' ? 'left: -1rem;' : 'right: -1rem;' }}
                    background: rgba(255, 255, 255, 0.98);
                    border: 1px solid #bfdbfe;
                    border-radius: 0.875rem;
                    padding: 0.75rem 1rem;
                    box-shadow: 0 15px 30px rgba(15, 23, 42, 0.1);
                    z-index: 20;
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    width: 200px;
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
                        <i class="fas fa-qrcode" style="color: #2563eb; font-size: 1rem;"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; font-weight: 800; color: #0f172a;">{{ __('landing.hero.card_qr_title') }}</div>
                        <div style="font-size: 0.65rem; color: #475569; font-weight: 600;">{{ __('landing.hero.card_qr_sub') }}</div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>