<section 
    class="hero-section relative pt-28 lg:pt-40 pb-20 overflow-hidden bg-white" 
    id="hero"
>
    <div
        dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
        class="container relative mx-auto px-4 lg:px-12 z-10"
    >
        <div class="max-w-4xl mx-auto text-center">
            <!-- Simple Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-50 border border-slate-100 mb-8">
                <span class="flex h-1.5 w-1.5 rounded-full bg-[#22c55e]"></span>
                <span class="text-[12px] font-bold text-slate-500 uppercase tracking-widest">{{ __('landing.hero.badge') }}</span>
            </div>

            <!-- Clean Static Headline -->
            <h1 class="font-cairo text-4xl md:text-5xl lg:text-7xl font-[900] text-[#0f172a] leading-[1.1] mb-8 tracking-tight">
                أدِر مركزك التعليمي <span class="text-[#22c55e]">بذكاء</span> وحرية تامة
            </h1>

            <!-- Balanced Subheadline -->
            <p class="text-lg md:text-xl text-slate-500 mb-12 max-w-2xl mx-auto font-medium leading-relaxed">
                {{ __('landing.hero.subtitle') }}
            </p>

            <!-- Focused CTA Group -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-20">
                <a href="{{ route('register') }}" class="bg-[#22c55e] text-white px-10 py-5 rounded-xl font-bold text-lg shadow-lg shadow-green-500/20 hover:bg-[#1eb355] transition-all transform hover:-translate-y-1">
                    {{ __('landing.hero.cta_primary') }}
                </a>
                <a href="#demo" class="bg-white text-slate-700 border-2 border-slate-100 px-10 py-5 rounded-xl font-bold text-lg hover:bg-slate-50 transition-all">
                    {{ __('landing.hero.cta_secondary') }}
                </a>
            </div>

            <!-- Single Clean Mockup -->
            <div class="relative max-w-5xl mx-auto opacity-0" style="animation: heroFadeInUp 1s ease-out forwards;">
                <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-2xl">
                    <img 
                        src="{{ asset('images/hero-dashboard.png') }}" 
                        alt="Dashboard" 
                        class="w-full h-auto"
                    >
                </div>
                
                <!-- Subtle Gradient Glow behind mockup -->
                <div class="absolute -inset-10 bg-green-500/5 blur-3xl -z-10 rounded-full"></div>
            </div>

            <!-- Simplified Trust Row -->
            <div class="mt-24 pt-12 border-t border-slate-50 flex flex-wrap justify-center gap-10 lg:gap-20">
                @foreach([
                    ['value' => '500+', 'label' => __('landing.hero.trust_centers') ?? 'Educational Centers'],
                    ['value' => '10,000+', 'label' => __('landing.hero.trust_students') ?? 'Active Students'],
                    ['value' => '98%', 'label' => __('landing.hero.trust_satisfaction') ?? 'Satisfaction Rate'],
                ] as $stat)
                <div class="text-center">
                    <div class="text-3xl font-black text-[#0f172a]">{{ $stat['value'] }}</div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
