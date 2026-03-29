<section class="relative pt-28 lg:pt-36 pb-0 overflow-hidden bg-white" id="hero">
    <!-- Gradient Glow -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-gradient-to-b from-emerald-500/5 via-cyan-500/5 to-transparent rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute top-20 right-0 w-[300px] h-[300px] bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="container relative mx-auto px-4 lg:px-12 z-10">
        <div class="max-w-5xl mx-auto text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2.5 px-5 py-2 rounded-full bg-slate-50 border border-slate-200 mb-8 backdrop-blur-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-widest">{{ __('landing.hero.badge') }}</span>
            </div>

            <!-- Headline -->
            <h1 class="text-4xl md:text-5xl lg:text-[3.75rem] font-black text-slate-900 leading-[1.15] mb-8 tracking-tight">
                {!! __('landing.hero.title', ['highlight' => '<span class="text-emerald-500">' . __('landing.hero.title_highlight', [], app()->getLocale()) . '</span>']) !!}
                @if(__('landing.hero.title') === 'landing.hero.title')
                    {{ __('landing.pain_points.title_prefix') }} <span class="text-emerald-400">{{ __('landing.pain_points.title_highlight') }}</span><br> {{ __('landing.pain_points.title_suffix') }}
                @endif
            </h1>

            <!-- Subtitle -->
            <p class="text-lg md:text-xl text-slate-600 mb-12 max-w-2xl mx-auto font-medium leading-relaxed">
                {{ __('landing.hero.subtitle') }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                <a href="{{ route('register') }}" class="group bg-emerald-500 hover:bg-emerald-400 text-white px-10 py-4 rounded-xl font-bold text-lg shadow-xl shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all hover:scale-[1.02]">
                    <span class="flex items-center gap-2 justify-center">
                        {{ __('landing.hero.cta_primary') }}
                        <i class="fas fa-arrow-right text-sm opacity-70 group-hover:translate-x-1 transition-transform rtl:rotate-180"></i>
                    </span>
                </a>
                <a href="#features" class="bg-slate-50 hover:bg-slate-100 text-slate-900 border border-slate-200 px-10 py-4 rounded-xl font-bold text-lg transition-all">
                    {{ __('landing.hero.cta_secondary') }}
                </a>
            </div>

            <!-- Floating UI Tags -->
            <div class="relative max-w-4xl mx-auto mb-6">
                <div class="flex flex-wrap justify-center gap-3 mb-10">
                    @foreach([
                        ['label' => __('landing.features.items.0.title', [], app()->getLocale()) ?: 'Students', 'color' => 'bg-yellow-50 text-yellow-700 border-yellow-200'],
                        ['label' => __('landing.features.items.1.title', [], app()->getLocale()) ?: 'Schedule', 'color' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                        ['label' => __('landing.features.items.2.title', [], app()->getLocale()) ?: 'Payments', 'color' => 'bg-red-50 text-red-700 border-red-200'],
                        ['label' => __('landing.features.items.3.title', [], app()->getLocale()) ?: 'Reports', 'color' => 'bg-blue-50 text-blue-700 border-blue-200'],
                    ] as $tag)
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold border {{ $tag['color'] }}">{{ $tag['label'] }}</span>
                    @endforeach
                </div>
            </div>

            <!-- Dashboard Mockup -->
            <div class="relative max-w-5xl mx-auto" style="animation: heroFadeUp 0.8s ease-out forwards;">
                <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-2xl shadow-slate-200/50 bg-white">
                    <!-- Browser Bar -->
                    <div class="flex items-center gap-2 px-4 py-3 bg-slate-50 border-b border-slate-100">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-400/60"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400/60"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-400/60"></div>
                        </div>
                        <div class="flex-1 mx-4">
                            <div class="bg-white border border-slate-100 rounded-lg px-4 py-1.5 text-xs text-slate-400 font-mono text-center">
                                app.taalimu.com
                            </div>
                        </div>
                    </div>
                    <img src="{{ asset('images/hero-dashboard.png') }}" alt="Taalimu Dashboard" class="w-full h-auto">
                </div>
                
                <!-- Glow effects -->
                <div class="absolute -top-10 left-1/2 -translate-x-1/2 w-3/4 h-20 bg-emerald-500/10 blur-[60px] rounded-full -z-10"></div>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="max-w-4xl mx-auto mt-20 mb-0">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 py-10 border-t border-slate-100">
                @foreach([
                    ['value' => '500+', 'label' => __('landing.hero.trust_centers') ?? 'Centers'],
                    ['value' => '10,000+', 'label' => __('landing.hero.trust_students') ?? 'Students'],
                    ['value' => '98%', 'label' => __('landing.hero.trust_satisfaction') ?? 'Satisfaction'],
                    ['value' => '2.4h', 'label' => __('landing.cta.stats.time') ?? 'Time Saved'],
                ] as $stat)
                <div class="text-center group">
                    <div class="text-3xl lg:text-4xl font-black text-slate-900 mb-1 tracking-tight group-hover:text-emerald-500 transition-colors">{{ $stat['value'] }}</div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<style>
@keyframes heroFadeUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
