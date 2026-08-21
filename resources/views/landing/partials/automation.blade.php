{{-- How It Works Section (3 Clear Steps) --}}
<section id="how-it-works" class="section-alt" style="padding: 6rem 0; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center mb-16" data-animate>
            <div class="section-badge" style="margin-bottom: 1.25rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 700; font-size: 0.825rem;">{{ __('landing.how_it_works.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.875rem, 4vw, 3rem); font-weight: 900; margin-bottom: 1.25rem; letter-spacing: -0.025em; line-height: 1.25;">
                {{ __('landing.how_it_works.title_prefix') }} <span style="color: #2E8B83 !important;">{{ __('landing.how_it_works.title_highlight') }}</span>
            </h2>
            <p style="color: #475569 !important; font-size: 1.125rem; max-width: 42rem; margin: 0 auto; font-weight: 500; line-height: 1.7;">
                {{ __('landing.how_it_works.subtitle') }}
            </p>
        </div>

        {{-- 3-Step Timeline Grid --}}
        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto relative" data-stagger>
            @foreach([
                ['step' => 'step1', 'icon' => 'fa-user-plus', 'color' => '#2E8B83'],
                ['step' => 'step2', 'icon' => 'fa-file-import', 'color' => '#2563eb'],
                ['step' => 'step3', 'icon' => 'fa-rocket', 'color' => '#16a34a'],
            ] as $stepItem)
            @php $stepData = __('landing.how_it_works.' . $stepItem['step']); @endphp
            <div style="
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 1.25rem;
                padding: 2.25rem 1.75rem;
                text-align: center;
                position: relative;
                box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            ">
                <div style="
                    width: 3.5rem;
                    height: 3.5rem;
                    border-radius: 1rem;
                    background: {{ $stepItem['color'] }};
                    color: #ffffff;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.5rem;
                    margin: 0 auto 1.25rem auto;
                    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
                ">
                    <i class="fas {{ $stepItem['icon'] }}"></i>
                </div>
                
                <div style="font-size: 0.75rem; font-weight: 800; color: {{ $stepItem['color'] }}; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">
                    {{ __('landing.how_it_works.badge') }} {{ $stepData['num'] }}
                </div>
                
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 0.75rem;">
                    {{ $stepData['title'] }}
                </h3>
                
                <p style="font-size: 0.875rem; color: #64748b; line-height: 1.6; margin: 0;">
                    {{ $stepData['desc'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</section>
