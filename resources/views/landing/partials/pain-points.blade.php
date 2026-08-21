{{-- Pain Points / Problem Section --}}
<section id="problem" class="section-light" style="padding: 6rem 0; background: #ffffff; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center mb-16" data-animate>
            <div class="section-badge" style="margin-bottom: 1.25rem; display: inline-flex; background: #fef2f2; border: 1px solid #fecaca; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #dc2626 !important; font-weight: 700; font-size: 0.825rem;">{{ __('landing.pain_points.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.875rem, 4vw, 3rem); font-weight: 900; margin-bottom: 1.25rem; letter-spacing: -0.025em; line-height: 1.25;">
                {{ __('landing.pain_points.title_prefix') }} <span style="color: #dc2626 !important;">{{ __('landing.pain_points.title_highlight') }}</span>
            </h2>
            <p style="color: #475569 !important; font-size: 1.125rem; max-width: 44rem; margin: 0 auto; font-weight: 500; line-height: 1.7;">
                {{ __('landing.pain_points.subtitle') }}
            </p>
        </div>

        {{-- 6 Real Daily Pain Cards --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto mb-16" data-stagger>
            @foreach(__('landing.pain_points.items') as $index => $item)
            <div style="
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 1rem;
                padding: 1.75rem;
                transition: all 0.25s ease;
                box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            " onmouseover="this.style.borderColor='#f87171'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px -5px rgba(220,38,38,0.08)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.02)';">
                <div style="
                    width: 3rem;
                    height: 3rem;
                    border-radius: 0.75rem;
                    background: #fef2f2;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-bottom: 1.25rem;
                ">
                    <i class="fas {{ $item['icon'] }}" style="color: #dc2626; font-size: 1.25rem;"></i>
                </div>
                <h3 style="color: #0f172a !important; font-size: 1.15rem; font-weight: 800; margin-bottom: 0.65rem; line-height: 1.35;">
                    {{ $item['title'] }}
                </h3>
                <p style="color: #64748b !important; font-size: 0.9rem; line-height: 1.65; margin: 0;">
                    {{ $item['desc'] }}
                </p>
            </div>
            @endforeach
        </div>

        {{-- Resolution Callout Banner --}}
        <div class="max-w-4xl mx-auto" data-animate>
            <div style="
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                border-radius: 1.25rem;
                padding: 2.25rem 2rem;
                text-align: center;
                color: #ffffff;
                box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.2);
                border: 1px solid rgba(255, 255, 255, 0.1);
            ">
                <h3 style="color: #ffffff !important; font-size: clamp(1.25rem, 3vw, 1.75rem); font-weight: 900; margin-bottom: 0.75rem;">
                    {{ __('landing.pain_points.solution_banner_title') }}
                </h3>
                <p style="color: #cbd5e1 !important; font-size: 1.05rem; margin-bottom: 1.5rem; max-width: 36rem; margin-left: auto; margin-right: auto; line-height: 1.6;">
                    {{ __('landing.pain_points.solution_banner_subtitle') }}
                </p>
                <a href="{{ route('register') }}" style="
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    padding: 0.875rem 2rem;
                    background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);
                    color: #ffffff !important;
                    font-weight: 800;
                    font-size: 0.95rem;
                    border-radius: 0.75rem;
                    text-decoration: none;
                    box-shadow: 0 4px 14px rgba(46, 139, 131, 0.35);
                ">
                    <span>{{ __('landing.hero.cta_free') }}</span>
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i>
                </a>
            </div>
        </div>
    </div>
</section>
