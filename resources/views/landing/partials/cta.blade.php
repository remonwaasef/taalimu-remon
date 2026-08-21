{{-- Final Conversion CTA Section --}}
<section style="padding: 6.5rem 0; background: #ffffff;">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="max-w-4xl mx-auto">
            <div style="
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f2720 100%);
                border-radius: 1.75rem;
                padding: 4rem 2.5rem;
                text-align: center;
                position: relative;
                overflow: hidden;
                box-shadow: 0 25px 60px -15px rgba(15,23,42,0.3);
            " data-animate="scale">
                {{-- Glow Effects --}}
                <div style="position: absolute; top: -5rem; right: -5rem; width: 16rem; height: 16rem; background: radial-gradient(circle, rgba(16,185,129,0.18) 0%, transparent 70%); pointer-events: none;"></div>
                <div style="position: absolute; bottom: -5rem; left: -5rem; width: 16rem; height: 16rem; background: radial-gradient(circle, rgba(46,139,131,0.2) 0%, transparent 70%); pointer-events: none;"></div>

                <div style="position: relative; z-index: 10;">
                    <h2 style="color: #ffffff !important; font-size: clamp(1.75rem, 4vw, 3rem); font-weight: 900; margin-bottom: 1.25rem; letter-spacing: -0.025em; line-height: 1.2;">
                        {{ __('landing.cta.title') }}
                    </h2>

                    <p style="color: rgba(226,232,240,0.85) !important; font-size: 1.15rem; margin-bottom: 2.5rem; max-width: 38rem; margin-left: auto; margin-right: auto; font-weight: 500; line-height: 1.7;">
                        {{ __('landing.cta.subtitle') }}
                    </p>

                    {{-- Primary CTA Button --}}
                    <div style="display: flex; justify-content: center; margin-bottom: 2rem;">
                        <a href="{{ route('register') }}"
                           style="
                                background: #ffffff;
                                color: #2E8B83 !important;
                                padding: 1.25rem 3.25rem;
                                border-radius: 1rem;
                                font-weight: 900;
                                font-size: 1.2rem;
                                display: inline-flex;
                                align-items: center;
                                gap: 0.75rem;
                                box-shadow: 0 15px 40px rgba(0,0,0,0.25);
                                transition: all 0.25s;
                                text-decoration: none;
                           "
                           onmouseover="this.style.transform='translateY(-3px) scale(1.02)'; this.style.boxShadow='0 20px 50px rgba(0,0,0,0.3)'"
                           onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 15px 40px rgba(0,0,0,0.25)'"
                        >
                            <span style="color: #2E8B83 !important;">{{ __('landing.cta.cta_primary') }}</span>
                            <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}" style="color: #2E8B83 !important; font-size: 1rem;"></i>
                        </a>
                    </div>

                    {{-- Trust Reassurance Note --}}
                    <p style="font-size: 0.825rem; font-weight: 600; color: rgba(226,232,240,0.75) !important; display: flex; align-items: center; justify-content: center; gap: 0.5rem; letter-spacing: 0.03em;">
                        <i class="fas fa-check-circle" style="color: #34d399;"></i>
                        <span>{{ __('landing.cta.trust_note') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
