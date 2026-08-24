{{-- Problem Section --}}
<section id="problem" class="section-alt" style="padding: 5rem 0; background: #ffffff; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Header --}}
        <div class="text-center mb-16" data-animate="fade-up">
            <div class="section-badge" style="margin-bottom: 1rem; display: inline-flex; background: #fef2f2; border: 1px solid #fecaca; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #dc2626 !important; font-weight: 700; font-size: 0.825rem;">{{ __('landing.problem.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 900; margin-bottom: 0.875rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.problem.title_prefix') }}
                <span style="color: #dc2626;">{{ __('landing.problem.title_highlight') }}</span>
            </h2>
            <p style="color: #475569 !important; font-size: 1.05rem; max-width: 42rem; margin: 0 auto; font-weight: 500; line-height: 1.65;">
                {{ __('landing.problem.subtitle') }}
            </p>
        </div>

        {{-- Pain Points Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;" data-stagger>
            @foreach(__('landing.problem.items') as $index => $item)
            <div style="
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 1rem;
                padding: 1.75rem 1.5rem;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            " onmouseover="this.style.borderColor='#2E8B83'; this.style.boxShadow='0 12px 32px rgba(46,139,131,0.1)'; this.style.background='#ffffff'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.background='#f8fafc'">
                <div style="width: 3rem; height: 3rem; border-radius: 0.75rem; background: #fef2f2; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; color: #dc2626; font-size: 1.25rem;">
                    <i class="fas {{ $item['icon'] }}"></i>
                </div>
                <h3 style="color: #0f172a !important; font-size: 1.1rem; font-weight: 800; margin: 0 0 0.5rem 0;">{{ $item['title'] }}</h3>
                <p style="color: #64748b; font-size: 0.9rem; margin: 0; line-height: 1.6;">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Solution Banner --}}
        <div style="margin-top: 3.5rem;" data-animate="fade-up" data-delay="300">
            <div style="
                background: linear-gradient(135deg, #E6F4F3 0%, #f0fdf4 100%);
                border: 1px solid #B2DDD9;
                border-radius: 1.5rem;
                padding: 2.5rem 2rem;
                text-align: center;
                position: relative;
                overflow: hidden;
            ">
                <div style="position: absolute; top: -2rem; right: -2rem; width: 8rem; height: 8rem; background: radial-gradient(circle, rgba(46,139,131,0.1) 0%, transparent 70%); pointer-events: none;"></div>
                <div style="position: absolute; bottom: -2rem; left: -2rem; width: 8rem; height: 8rem; background: radial-gradient(circle, rgba(34,197,94,0.1) 0%, transparent 70%); pointer-events: none;"></div>
                <div style="position: relative; z-index: 1;">
                    <h3 style="color: #2E8B83 !important; font-size: clamp(1.35rem, 2.5vw, 1.75rem); font-weight: 900; margin-bottom: 0.75rem;">{{ __('landing.problem.solution_banner_title') }}</h3>
                    <p style="color: #25746D !important; font-size: 1.05rem; font-weight: 500; max-width: 36rem; margin: 0 auto; line-height: 1.6;">{{ __('landing.problem.solution_banner_subtitle') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>