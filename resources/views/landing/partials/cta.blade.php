{{-- Final Conversion CTA Section — Compact Layout --}}
<section style="padding: 3.5rem 0; background: #ffffff;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        <div class="max-w-3xl mx-auto">
            <div style="
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f2720 100%);
                border-radius: 1.25rem;
                padding: 2.75rem 1.75rem;
                text-align: center;
                position: relative;
                overflow: hidden;
                box-shadow: 0 20px 45px -10px rgba(15,23,42,0.25);
            " data-animate="scale">
                {{-- Glow Effects --}}
                <div style="position: absolute; top: -4rem; right: -4rem; width: 12rem; height: 12rem; background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, transparent 70%); pointer-events: none;"></div>
                <div style="position: absolute; bottom: -4rem; left: -4rem; width: 12rem; height: 12rem; background: radial-gradient(circle, rgba(46,139,131,0.15) 0%, transparent 70%); pointer-events: none;"></div>

                <div style="position: relative; z-index: 10;">
                    <h2 style="color: #ffffff !important; font-size: clamp(1.5rem, 3vw, 2.25rem); font-weight: 900; margin-bottom: 0.875rem; letter-spacing: -0.02em; line-height: 1.25;">
                        {{ __('landing.cta.title') }}
                    </h2>

                    <p style="color: rgba(226,232,240,0.85) !important; font-size: 1rem; margin-bottom: 1.75rem; max-width: 32rem; margin-left: auto; margin-right: auto; font-weight: 500; line-height: 1.6;">
                        {{ __('landing.cta.subtitle') }}
                    </p>

                    {{-- Primary CTA Button --}}
                    <div style="display: flex; justify-content: center; margin-bottom: 1.25rem;">
                        <a href="{{ route('register') }}"
                           style="
                                background: #ffffff;
                                color: #2E8B83 !important;
                                padding: 0.95rem 2.5rem;
                                border-radius: 0.75rem;
                                font-weight: 900;
                                font-size: 1.05rem;
                                display: inline-flex;
                                align-items: center;
                                gap: 0.625rem;
                                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                                transition: all 0.2s;
                                text-decoration: none;
                           "
                           onmouseover="this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.transform='translateY(0)'"
                        >
                            <span style="color: #2E8B83 !important;">{{ __('landing.cta.cta_primary') }}</span>
                            <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}" style="color: #2E8B83 !important; font-size: 0.9rem;"></i>
                        </a>
                    </div>

                    {{-- Trust Reassurance Note --}}
                    <p style="font-size: 0.775rem; font-weight: 600; color: rgba(226,232,240,0.75) !important; display: flex; align-items: center; justify-content: center; gap: 0.35rem; margin: 0;">
                        <i class="fas fa-check-circle" style="color: #34d399;"></i>
                        <span>{{ __('landing.cta.trust_note') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
