<!-- Pain Points Section -->
<section class="py-24 bg-white relative overflow-hidden"
    x-data="{ visible: false }"
    x-intersect.once="visible = true"
>
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
    
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center mb-16" data-animate>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 border border-red-100 mb-6">
                <span class="text-xs font-bold text-red-500 uppercase tracking-widest">{{ __('landing.pain_points.badge') }}</span>
            </div>
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight leading-tight">
                {{ __('landing.pain_points.title_prefix') }} <span class="text-emerald-600">{{ __('landing.pain_points.title_highlight') }}</span> {{ __('landing.pain_points.title_suffix') }}
            </h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto font-medium">
                {{ __('landing.pain_points.subtitle') }}
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto" data-stagger>
            @foreach([
                ['stat' => '35', 'suffix' => '%', 'color' => 'text-red-500', 'border' => 'border-red-100 hover:border-red-200', 'bg' => 'bg-red-50', 'key' => 'revenue_lost'],
                ['stat' => '12', 'suffix' => 'h', 'color' => 'text-orange-500', 'border' => 'border-orange-100 hover:border-orange-200', 'bg' => 'bg-orange-50', 'key' => 'time_wasted'],
                ['stat' => '24', 'suffix' => '/7', 'color' => 'text-blue-500', 'border' => 'border-blue-100 hover:border-blue-200', 'bg' => 'bg-blue-50', 'key' => 'complaints'],
            ] as $pain)
            <div class="group bg-white rounded-2xl p-8 border {{ $pain['border'] }} transition-all duration-300 hover:-translate-y-1 hover:shadow-lg text-center">
                <div class="text-5xl font-black mb-3 tracking-tighter {{ $pain['color'] }}"
                     x-data="{ shown: false }" x-intersect.once="shown = true"
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
                <h3 class="text-base font-bold text-slate-800 mb-2">{{ __("landing.pain_points.{$pain['key']}.title") }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ __("landing.pain_points.{$pain['key']}.description") }}</p>
            </div>
            @endforeach
        </div>

        <!-- Second row -->
        <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto mt-6" data-stagger>
            @foreach([
                ['stat' => '100', 'suffix' => '%', 'color' => 'text-emerald-500', 'border' => 'border-emerald-100 hover:border-emerald-200', 'key' => 'manual_work'],
                ['stat' => '98', 'suffix' => '%', 'color' => 'text-violet-500', 'border' => 'border-violet-100 hover:border-violet-200', 'key' => 'revenue_lost'],
                ['stat' => '0', 'suffix' => '', 'color' => 'text-slate-800', 'border' => 'border-slate-200 hover:border-slate-300', 'key' => 'complaints'],
            ] as $pain)
            <div class="group bg-white rounded-2xl p-8 border {{ $pain['border'] }} transition-all duration-300 hover:-translate-y-1 hover:shadow-lg text-center">
                <div class="text-5xl font-black mb-3 tracking-tighter {{ $pain['color'] }}">
                    {{ $pain['stat'] }}{{ $pain['suffix'] }}
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-2">{{ __("landing.pain_points.{$pain['key']}.title") }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ __("landing.pain_points.{$pain['key']}.description") }}</p>
            </div>
            @endforeach
        </div>

        <!-- Trust Badges -->
        <div class="mt-16 pt-12 border-t border-slate-100" data-animate>
            <div class="flex flex-wrap items-center justify-center gap-12 lg:gap-20">
                @foreach([
                    ['icon' => 'fa-shield-check', 'color' => '#10b981', 'label' => __('landing.pain_points.trust_secure') ?? 'Secure Payments'],
                    ['icon' => 'fa-whatsapp', 'color' => '#25D366', 'label' => __('landing.pain_points.trust_whatsapp') ?? 'WhatsApp Verified', 'brand' => true],
                    ['icon' => 'fa-graduation-cap', 'color' => '#6366f1', 'label' => __('landing.pain_points.trust_educators') ?? 'Educator Trusted']
                ] as $badge)
                <div class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center border border-slate-100 group-hover:scale-110 transition-transform">
                        <i class="{{ isset($badge['brand']) ? 'fab' : 'fas' }} {{ $badge['icon'] }}" style="color: {{ $badge['color'] }};"></i>
                    </div>
                    <span class="font-bold text-slate-400 text-sm uppercase tracking-wider group-hover:text-slate-600 transition-colors">{{ $badge['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
