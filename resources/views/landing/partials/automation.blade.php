{{-- How It Works Section --}}
<section class="section-alt" style="padding:6rem 0; border-top:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center mb-16" data-animate>
            <div class="section-badge" style="margin-bottom:1.5rem; display:inline-flex;">
                <span style="color:#25746D !important; font-weight:700;">{{ __('landing.automation.badge') ?? 'كيف يعمل' }}</span>
            </div>
            <h2 style="color:#0f172a !important; font-size:clamp(1.875rem, 4vw, 3rem); font-weight:900; margin-bottom:1.5rem; letter-spacing:-0.025em; line-height:1.2;">
                {{ __('landing.automation.title_prefix') }} <span style="color:#2E8B83 !important;">{{ __('landing.automation.title_highlight') }}</span>
            </h2>
            <p style="color:#475569 !important; font-size:1.125rem; max-width:42rem; margin:0 auto; font-weight:500; line-height:1.7;">
                {{ __('landing.automation.subtitle') }}
            </p>
        </div>

        {{-- 3-Step Timeline --}}
        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto relative" data-stagger>
            {{-- Connector Line --}}
            <div class="hidden md:block absolute top-10 left-[16.66%] right-[16.66%]" style="height:2px; background:linear-gradient(90deg, #B2DDD9, #2E8B83, #B2DDD9);"></div>

            @foreach([
                ['num' => '01', 'icon' => 'fa-user-plus', 'bg' => '#2E8B83', 'key' => 0],
                ['num' => '02', 'icon' => 'fa-cogs', 'bg' => '#3b82f6', 'key' => 1],
                ['num' => '03', 'icon' => 'fa-rocket', 'bg' => '#8b5cf6', 'key' => 2],
            ] as $step)
            <div class="text-center relative">
                <div class="step-circle" style="background:{{ $step['bg'] }};">
                    <i class="fas {{ $step['icon'] }}" style="color:#ffffff;"></i>
                </div>
                <div style="color:#94a3b8; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.15em; margin-bottom:0.75rem;">
                    {{ __('landing.automation.badge') ?? 'خطوة' }} {{ $step['num'] }}
                </div>
                <h3 style="color:#0f172a !important; font-size:1.25rem; font-weight:800; margin-bottom:0.75rem;">
                    {{ __("landing.automation.step" . ($step['key'] + 1) . ".title") }}
                </h3>
                <p style="color:#64748b !important; font-size:0.875rem; line-height:1.6; max-width:18rem; margin:0 auto;">
                    {{ __("landing.automation.step" . ($step['key'] + 1) . ".description") }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</section>
