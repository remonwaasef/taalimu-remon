{{-- Pain Points Section --}}
<section class="section-light" style="padding:6rem 0;">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center mb-16" data-animate>
            <div class="section-badge" style="margin-bottom:1.5rem; display:inline-flex;">
                <span style="color:#dc2626 !important; font-weight:700;">{{ __('landing.pain_points.badge') }}</span>
            </div>
            <h2 style="color:#0f172a !important; font-size:clamp(1.875rem, 4vw, 3rem); font-weight:900; margin-bottom:1.5rem; letter-spacing:-0.025em; line-height:1.2;">
                {{ __('landing.pain_points.title_prefix') }} <span style="color:#2E8B83 !important;">{{ __('landing.pain_points.title_highlight') }}</span> {{ __('landing.pain_points.title_suffix') }}
            </h2>
            <p style="color:#475569 !important; font-size:1.125rem; max-width:42rem; margin:0 auto; font-weight:500; line-height:1.7;">
                {{ __('landing.pain_points.subtitle') }}
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto" data-stagger>
            @foreach([
                ['stat' => '35', 'suffix' => '%', 'color' => '#ef4444', 'border_hover' => '#fca5a5', 'bg_icon' => '#fef2f2', 'key' => 'revenue_lost'],
                ['stat' => '12', 'suffix' => 'h', 'color' => '#f97316', 'border_hover' => '#fdba74', 'bg_icon' => '#fff7ed', 'key' => 'time_wasted'],
                ['stat' => '24', 'suffix' => '/7', 'color' => '#3b82f6', 'border_hover' => '#93c5fd', 'bg_icon' => '#eff6ff', 'key' => 'complaints'],
            ] as $pain)
            <div class="stat-card" x-data="{ shown: false }" x-intersect.once="shown = true">
                <div class="stat-number" style="color:{{ $pain['color'] }};"
                >
                    <span x-show="!shown">0{{ $pain['suffix'] }}</span>
                    <span x-show="shown" x-text="''" x-init="
                        $watch('shown', v => {
                            if (!v) return;
                            let el = $el; let current = 0; let target = {{ $pain['stat'] }};
                            let step = Math.ceil(target / 30);
                            let timer = setInterval(() => {
                                current += step;
                                if (current >= target) { current = target; clearInterval(timer); }
                                el.textContent = current + '{{ $pain['suffix'] }}';
                            }, 40);
                        })
                    ">0{{ $pain['suffix'] }}</span>
                </div>
                <h3 style="color:#0f172a !important; font-size:1rem; font-weight:700; margin-bottom:0.5rem;">{{ __("landing.pain_points.{$pain['key']}.title") }}</h3>
                <p style="color:#64748b !important; font-size:0.875rem; line-height:1.6;">{{ __("landing.pain_points.{$pain['key']}.description") }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
