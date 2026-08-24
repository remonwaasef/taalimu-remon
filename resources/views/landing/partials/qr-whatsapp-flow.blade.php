{{-- QR + WhatsApp Master Flow Section --}}
<section id="qr-whatsapp-flow" class="section-light" style="padding: 5rem 0; background: linear-gradient(180deg, #f8fafc 0%, #E6F4F3 100%); border-bottom: 1px solid #B2DDD9;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Header --}}
        <div class="text-center mb-16" data-animate="fade-up">
            <div class="section-badge" style="margin-bottom: 1rem; display: inline-flex; background: #ffffff; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px; box-shadow: 0 2px 8px rgba(46,139,131,0.06);">
                <i class="fas fa-sync-alt" style="color: #2E8B83; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.5rem;"></i>
                <span style="color: #25746D !important; font-weight: 800; font-size: 0.825rem;">{{ __('landing.flow.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 900; margin-bottom: 0.5rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.flow.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.05rem; max-width: 42rem; margin: 0 auto; font-weight: 500; line-height: 1.65;">
                {{ __('landing.flow.subtitle') }}
            </p>
        </div>

        {{-- Animated Timeline --}}
        <div style="position: relative; max-width: 900px; margin: 0 auto;" data-animate="fade-up" data-delay="200">
            {{-- Connecting Line --}}
            <div style="
                position: absolute;
                top: 0;
                bottom: 0;
                {{ app()->getLocale() == 'ar' ? 'right: 3rem;' : 'left: 3rem;' }}
                width: 2px;
                background: linear-gradient(180deg, #B2DDD9 0%, #2E8B83 50%, #16a34a 100%);
                z-index: 1;
            "></div>

            <div style="display: flex; flex-direction: column; gap: 2.5rem; position: relative; z-index: 2;">
                @foreach(__('landing.flow.steps') as $index => $step)
                <div style="display: flex; gap: 1.5rem; align-items: flex-start;" data-animate="slide-in-{{ $loop->even ? 'right' : 'left' }}" data-delay="{{ $index * 100 }}">
                    {{-- Step Number --}}
                    <div style="
                        flex-shrink: 0;
                        width: 3.5rem;
                        height: 3.5rem;
                        border-radius: 1rem;
                        background: {{ $step['color'] }};
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: #ffffff;
                        font-size: 1.35rem;
                        font-weight: 900;
                        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
                        position: relative;
                    ">
                        {{ $index + 1 }}
                        {{-- Pulse ring --}}
                        <div style="
                            position: absolute;
                            inset: -4px;
                            border-radius: 1.25rem;
                            border: 2px solid {{ $step['color'] }};
                            opacity: 0;
                            animation: pulse-ring 3s ease-out infinite {{ $index * 0.5 }}s;
                        " aria-hidden="true"></div>
                    </div>

                    {{-- Step Content --}}
                    <div style="flex: 1; padding-top: 0.5rem; {{ app()->getLocale() == 'ar' ? 'padding-right: 1rem;' : 'padding-left: 1rem;' }} border-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 3px solid {{ $step['color'] }}; padding-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                            <div style="
                                width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; 
                                background: {{ $step['bg'] }}; 
                                display: flex; align-items: center; justify-content: center; 
                                color: {{ $step['color'] }}; font-size: 1.15rem; flex-shrink: 0;
                            ">
                                <i class="fas {{ $step['icon'] }}"></i>
                            </div>
                            <h3 style="color: #0f172a; font-size: 1.1rem; font-weight: 800; margin: 0;">{{ $step['title'] }}</h3>
                        </div>
                        <p style="color: #64748b; font-size: 0.9rem; margin: 0; line-height: 1.6;">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Summary Tagline --}}
        <div class="text-center mt-16" data-animate="fade-up" data-delay="600">
            <div style="
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                padding: 1rem 2rem;
                background: #ffffff;
                border: 1px solid #B2DDD9;
                border-radius: 9999px;
                box-shadow: 0 8px 24px rgba(46,139,131,0.1);
            ">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}" style="color: #2E8B83; font-size: 1.25rem;"></i>
                <span style="color: #2E8B83; font-size: 1.1rem; font-weight: 900; white-space: nowrap;">{{ __('landing.flow.tagline') }}</span>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes pulse-ring {
    0% { transform: scale(1); opacity: 0.5; }
    100% { transform: scale(1.3); opacity: 0; }
}

@media (prefers-reduced-motion: reduce) {
    [style*="pulse-ring"] { animation: none !important; }
}
</style>