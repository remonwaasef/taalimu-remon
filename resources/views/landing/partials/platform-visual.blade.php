{{-- Platform Visual Section --}}
<section id="platform-visual" class="section-light" style="padding: 5rem 0; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Header --}}
        <div class="text-center mb-16" data-animate="fade-up">
            <div class="section-badge" style="margin-bottom: 1rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 800; font-size: 0.825rem;">{{ __('landing.platform.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 900; margin-bottom: 0.875rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.platform.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.05rem; max-width: 42rem; margin: 0 auto; font-weight: 500; line-height: 1.65;">
                {{ __('landing.platform.subtitle') }}
            </p>
        </div>

        {{-- Central Platform Diagram --}}
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 3rem; position: relative; min-height: 400px;" data-animate="scale-in" data-delay="200">
            
            {{-- Center: Taalimu Platform --}}
            <div style="
                position: relative;
                z-index: 10;
                width: 220px;
                height: 220px;
                border-radius: 50%;
                background: linear-gradient(135deg, #2E8B83 0%, #16a34a 100%);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                box-shadow: 
                    0 0 0 4px rgba(46,139,131,0.15),
                    0 20px 50px -10px rgba(46,139,131,0.3);
                animation: platform-pulse 4s ease-in-out infinite;
            ">
                <i class="fas fa-graduation-cap" style="font-size: 3.5rem; color: #ffffff;"></i>
                <span style="color: #ffffff; font-size: 1.1rem; font-weight: 900; margin-top: 0.5rem; text-align: center;">{{ __('landing.platform.center_label') }}</span>
                <span style="color: rgba(255,255,255,0.8); font-size: 0.7rem; font-weight: 600; margin-top: 0.25rem;">{{ __('landing.platform.core') }}</span>
            </div>

            {{-- Orbiting Audiences --}}
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 380px; height: 380px; pointer-events: none;">
                @foreach(__('landing.platform.audiences') as $index => $audience)
                <div class="orbit-item" style="
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 160px;
                    height: 160px;
                    transform: translate(-50%, -50%) rotate({{ $index * 90 }}deg) translateY(-190px) rotate({{ -$index * 90 }}deg);
                    animation: orbit {{ 20 + $index * 2 }}s linear infinite;
                ">
                    <div style="
                        width: 100%;
                        height: 100%;
                        border-radius: 1.25rem;
                        background: #ffffff;
                        border: 1px solid #e2e8f0;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        padding: 1.25rem;
                        text-align: center;
                        box-shadow: 0 8px 24px rgba(15,23,42,0.08);
                        transition: all 0.3s ease;
                    " onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 16px 40px rgba(46,139,131,0.15)'; this.style.borderColor='#2E8B83'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 8px 24px rgba(15,23,42,0.08)'; this.style.borderColor='#e2e8f0'">
                        <div style="width: 3.5rem; height: 3.5rem; border-radius: 0.875rem; background: {{ $audience['color'] }}; display: flex; align-items: center; justify-content: center; margin-bottom: 0.875rem; color: #ffffff; font-size: 1.5rem;">
                            <i class="fas {{ $audience['icon'] }}"></i>
                        </div>
                        <h4 style="color: #0f172a; font-size: 0.95rem; font-weight: 800; margin: 0 0 0.5rem 0;">{{ $audience['label'] }}</h4>
                        <p style="color: #64748b; font-size: 0.75rem; margin: 0; line-height: 1.4;">{{ $audience['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Connection Lines (CSS) --}}
            @foreach(range(0, 3) as $i)
            <div style="
                position: absolute;
                top: 50%;
                left: 50%;
                width: 2px;
                height: 190px;
                background: linear-gradient(180deg, transparent 0%, #B2DDD9 50%, transparent 100%);
                transform-origin: bottom center;
                transform: translate(-50%, -100%) rotate({{ $i * 90 }}deg);
                opacity: 0.6;
            " aria-hidden="true"></div>
            @endforeach
        </div>

        {{-- Key Integrations --}}
        <div class="mt-16" data-animate="fade-up" data-delay="400">
            <h3 style="color: #0f172a; font-size: 1.15rem; font-weight: 800; text-align: center; margin: 0 0 1.5rem 0;">{{ __('landing.platform.integrations_title') }}</h3>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem;">
                @foreach(__('landing.platform.integrations') as $integration)
                <div style="
                    display: inline-flex;
                    align-items: center;
                    gap: 0.625rem;
                    padding: 0.625rem 1.25rem;
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 9999px;
                    font-size: 0.85rem;
                    font-weight: 600;
                    color: #334155;
                    transition: all 0.2s ease;
                " onmouseover="this.style.borderColor='#2E8B83'; this.style.color='#2E8B83'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#334155'">
                    <i class="fas {{ $integration['icon'] }}" style="color: {{ $integration['color'] }};"></i>
                    <span>{{ $integration['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<style>
@keyframes platform-pulse {
    0%, 100% { box-shadow: 0 0 0 4px rgba(46,139,131,0.15), 0 20px 50px -10px rgba(46,139,131,0.3); }
    50% { box-shadow: 0 0 0 8px rgba(46,139,131,0.2), 0 25px 60px -10px rgba(46,139,131,0.4); }
}

@keyframes orbit {
    from { transform: translate(-50%, -50%) rotate(0deg) translateY(-190px) rotate(0deg); }
    to { transform: translate(-50%, -50%) rotate(360deg) translateY(-190px) rotate(-360deg); }
}

@media (prefers-reduced-motion: reduce) {
    .orbit-item { animation: none !important; }
    [style*="platform-pulse"] { animation: none !important; }
}

@media (max-width: 768px) {
    [style*="width: 380px; height: 380px"] { width: 300px !important; height: 300px !important; }
    .orbit-item { transform: translate(-50%, -50%) rotate({{ $index * 90 }}deg) translateY(-150px) rotate({{ -$index * 90 }}deg) !important; }
}
</style>