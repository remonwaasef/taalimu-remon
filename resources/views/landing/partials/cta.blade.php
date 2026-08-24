{{-- Final CTA Section --}}
<section id="final-cta" style="padding: 5rem 0; background: #ffffff;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        <div class="max-w-3xl mx-auto">
            <div style="
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f2720 100%);
                border-radius: 1.5rem;
                padding: 3.5rem 2rem;
                text-align: center;
                position: relative;
                overflow: hidden;
                box-shadow: 0 25px 50px -12px rgba(15,23,42,0.3);
            " data-animate="scale-in">
                {{-- Glow Effects --}}
                <div style="position: absolute; top: -5rem; right: -5rem; width: 15rem; height: 15rem; background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, transparent 70%); pointer-events: none;"></div>
                <div style="position: absolute; bottom: -5rem; left: -5rem; width: 15rem; height: 15rem; background: radial-gradient(circle, rgba(46,139,131,0.15) 0%, transparent 70%); pointer-events: none;"></div>

                <div style="position: relative; z-index: 10;">
                    <h2 style="color: #ffffff !important; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 900; margin-bottom: 1rem; letter-spacing: -0.02em; line-height: 1.25;">
                        {{ __('landing.cta.title') }}
                    </h2>

                    <p style="color: rgba(226,232,240,0.85) !important; font-size: 1.1rem; margin-bottom: 2rem; max-width: 36rem; margin-left: auto; margin-right: auto; font-weight: 500; line-height: 1.65;">
                        {{ __('landing.cta.subtitle') }}
                    </p>

                    {{-- Primary CTA Button --}}
                    <div style="display: flex; justify-content: center; margin-bottom: 1.5rem;">
                        <a href="{{ route('register') }}"
                           style="
                                background: #ffffff;
                                color: #2E8B83 !important;
                                padding: 1.125rem 3rem;
                                border-radius: 1rem;
                                font-weight: 900;
                                font-size: 1.1rem;
                                display: inline-flex;
                                align-items: center;
                                gap: 0.75rem;
                                box-shadow: 0 12px 32px rgba(0,0,0,0.25);
                                transition: all 0.2s;
                                text-decoration: none;
                           "
                           onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 16px 40px rgba(0,0,0,0.3)'" 
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 12px 32px rgba(0,0,0,0.25)'">
                            <span style="color: #2E8B83 !important;">{{ __('landing.cta.cta_primary') }}</span>
                            <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}" style="color: #2E8B83 !important; font-size: 1rem;"></i>
                        </a>
                    </div>

                    {{-- Trust Reassurance Note --}}
                    <p style="font-size: 0.85rem; font-weight: 600; color: rgba(226,232,240,0.75) !important; display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin: 0;">
                        <i class="fas fa-check-circle" style="color: #34d399;"></i>
                        <span>{{ __('landing.cta.trust_note') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>