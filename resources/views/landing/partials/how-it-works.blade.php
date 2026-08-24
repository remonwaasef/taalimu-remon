{{-- How It Works Section --}}
<section id="how-it-works" class="section-light" style="padding: 5rem 0; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Header --}}
        <div class="text-center mb-16" data-animate="fade-up">
            <div class="section-badge" style="margin-bottom: 1rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 800; font-size: 0.825rem;">{{ __('landing.how_it_works.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 900; margin-bottom: 0.5rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.how_it_works.title_prefix') }}
                <span style="color: #2E8B83;">{{ __('landing.how_it_works.title_highlight') }}</span>
            </h2>
            <p style="color: #475569 !important; font-size: 1.05rem; max-width: 42rem; margin: 0 auto; font-weight: 500; line-height: 1.65;">
                {{ __('landing.how_it_works.subtitle') }}
            </p>
        </div>

        {{-- Steps --}}
        @php
            $stepsRaw = __('landing.how_it_works.steps');
            $steps = is_array($stepsRaw) ? $stepsRaw : [
                __('landing.how_it_works.step1'),
                __('landing.how_it_works.step2'),
                __('landing.how_it_works.step3')
            ];
            $colors = ['#2E8B83', '#2563eb', '#16a34a'];
        @endphp
        <div style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center;" data-stagger>
            @foreach($steps as $index => $step)
            @php $stepColor = $step['color'] ?? ($colors[$index] ?? '#2E8B83'); @endphp
            <div style="
                flex: 1 1 260px;
                max-width: 300px;
                text-align: center;
                position: relative;
            " data-animate="fade-up" data-delay="{{ $index * 150 }}">
                {{-- Step Number --}}
                <div style="
                    width: 4.5rem; height: 4.5rem; border-radius: 1.25rem; 
                    background: {{ $stepColor }};
                    display: flex; align-items: center; justify-content: center; 
                    margin: 0 auto 1.25rem auto; color: #ffffff; font-size: 1.5rem; font-weight: 900;
                    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
                    position: relative;
                ">
                    {{ $step['num'] ?? sprintf('%02d', $index + 1) }}
                </div>
                
                {{-- Connecting Line (except last) --}}
                @if(!$loop->last)
                <div style="
                    position: absolute;
                    top: 2.25rem;
                    {{ app()->getLocale() == 'ar' ? 'left: 100%;' : 'right: 100%;' }}
                    width: 50%;
                    height: 2px;
                    background: linear-gradient(90deg, {{ $stepColor }}, #e2e8f0);
                    opacity: 0.5;
                    z-index: -1;
                " aria-hidden="true"></div>
                @endif

                <h3 style="color: #0f172a; font-size: 1.15rem; font-weight: 800; margin: 0 0 0.625rem 0;">{{ $step['title'] }}</h3>
                <p style="color: #64748b; font-size: 0.9rem; margin: 0; line-height: 1.6;">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- CTA at bottom --}}
        <div class="text-center mt-16" data-animate="fade-up" data-delay="600">
            <a href="{{ route('register') }}" style="
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                padding: 1rem 2.5rem;
                background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);
                color: #ffffff !important;
                font-weight: 800;
                font-size: 1rem;
                border-radius: 1rem;
                text-decoration: none;
                box-shadow: 0 12px 32px rgba(46, 139, 131, 0.32);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 16px 40px rgba(46,139,131,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 12px 32px rgba(46,139,131,0.32)'">
                <span>{{ is_array(__('landing.how_it_works.cta')) ? __('landing.nav.start_trial') : __('landing.how_it_works.cta') }}</span>
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i>
            </a>
        </div>
    </div>
</section>