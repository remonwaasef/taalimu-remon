{{-- CTA Section --}}
<section style="padding:6rem 0; background:#ffffff;">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="max-w-4xl mx-auto">
            <div class="force-white" style="background:linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f2720 100%); border-radius:1.5rem; padding:3rem 2rem; text-align:center; position:relative; overflow:hidden; box-shadow:0 25px 60px -15px rgba(15,23,42,0.3);" data-animate="scale">
                {{-- Glow Effects --}}
                <div style="position:absolute; top:-5rem; right:-5rem; width:16rem; height:16rem; background:radial-gradient(circle, rgba(16,185,129,0.15) 0%, transparent 70%); pointer-events:none;"></div>
                <div style="position:absolute; bottom:-5rem; left:-5rem; width:16rem; height:16rem; background:radial-gradient(circle, rgba(14,165,233,0.1) 0%, transparent 70%); pointer-events:none;"></div>

                <div style="position:relative; z-index:10;">
                    <h2 style="color:#ffffff !important; font-size:clamp(1.5rem, 4vw, 2.75rem); font-weight:900; margin-bottom:1.5rem; letter-spacing:-0.025em; line-height:1.2;">
                        {{ __('landing.cta.title') }}
                    </h2>

                    <p style="color:rgba(226,232,240,0.8) !important; font-size:1.125rem; margin-bottom:2.5rem; max-width:36rem; margin-left:auto; margin-right:auto; font-weight:500; line-height:1.7;">
                        {{ __('landing.cta.subtitle') }}
                    </p>

                    {{-- CTA Button --}}
                    <div style="display:flex; justify-content:center; margin-bottom:2.5rem;">
                        <a href="{{ route('register') }}"
                           style="background:#ffffff; color:#2E8B83 !important; padding:1.25rem 3rem; border-radius:1rem; font-weight:900; font-size:1.25rem; display:inline-flex; align-items:center; gap:0.75rem; box-shadow:0 15px 40px rgba(0,0,0,0.2); transition:all 0.3s; text-decoration:none; ring:4px solid rgba(255,255,255,0.1);"
                           onmouseover="this.style.transform='translateY(-3px) scale(1.02)'; this.style.boxShadow='0 20px 50px rgba(0,0,0,0.25)'"
                           onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 15px 40px rgba(0,0,0,0.2)'"
                        >
                            <span style="color:#2E8B83 !important;">{{ __('landing.cta.cta_primary') }}</span>
                            <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-left' : 'fa-arrow-right' }}" style="color:#2E8B83 !important; font-size:1rem;"></i>
                        </a>
                    </div>

                    {{-- Trust Note --}}
                    <p style="font-size:0.75rem; font-weight:600; color:rgba(148,163,184,0.8) !important; display:flex; align-items:center; justify-content:center; gap:0.5rem; text-transform:uppercase; letter-spacing:0.08em;">
                        <i class="fas fa-shield-check" style="color:#34d399;"></i>
                        <span style="color:rgba(148,163,184,0.8) !important;">{{ __('landing.cta.trust_note') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
