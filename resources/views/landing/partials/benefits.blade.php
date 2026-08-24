{{-- Benefits Section --}}
<section id="benefits" class="section-alt" style="padding: 5rem 0; background: #ffffff; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Header --}}
        <div class="text-center mb-16" data-animate="fade-up">
            <div class="section-badge" style="margin-bottom: 1rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 800; font-size: 0.825rem;">{{ __('landing.benefits.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 900; margin-bottom: 0.875rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.benefits.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.05rem; max-width: 42rem; margin: 0 auto; font-weight: 500; line-height: 1.65;">
                {{ __('landing.benefits.subtitle') }}
            </p>
        </div>

        {{-- Benefits Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;" data-stagger>
            @foreach(__('landing.benefits.items') as $benefit)
            <div style="
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 1.25rem;
                padding: 2rem 1.75rem;
                text-align: center;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            " onmouseover="this.style.background='#ffffff'; this.style.borderColor='#2E8B83'; this.style.boxShadow='0 20px 40px rgba(46,139,131,0.12)'; this.style.transform='translateY(-4px)'" onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.transform='translateY(0)'">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: {{ $benefit['gradient'] }};"></div>
                <div style="width: 4rem; height: 4rem; border-radius: 1rem; background: {{ $benefit['bg'] }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem auto; color: {{ $benefit['color'] }}; font-size: 1.75rem;">
                    <i class="fas {{ $benefit['icon'] }}"></i>
                </div>
                <h3 style="color: #0f172a; font-size: 1.25rem; font-weight: 800; margin: 0 0 0.75rem 0;">{{ $benefit['title'] }}</h3>
                <p style="color: #64748b; font-size: 0.95rem; margin: 0; line-height: 1.65;">{{ $benefit['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>