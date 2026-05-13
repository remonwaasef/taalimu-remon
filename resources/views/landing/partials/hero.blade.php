<section class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden bg-slate-50" id="hero">
    <!-- Subtle Background Pattern -->
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#10b981 1px, transparent 1px); background-size: 32px 32px;"></div>

    <div dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="container relative mx-auto px-4 lg:px-8 z-10 max-w-7xl">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
            
            <!-- Content Side -->
            <div class="w-full lg:w-5/12 text-center lg:text-start" data-animate="fade-text">
                <!-- Premium Pill Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 shadow-sm mb-8 transition-transform hover:-translate-y-1">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-widest">{{ __('landing.hero.badge') }}</span>
                </div>

                <!-- Headline -->
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight md:leading-tight lg:leading-tight mb-6">
                    {!! __('landing.hero.title', ['highlight' => '<span class="text-emerald-600">' . __('landing.hero.title_highlight', [], app()->getLocale()) . '</span>']) !!}
                </h1>

                <!-- Subtitle -->
                <p class="text-base md:text-lg lg:text-xl text-slate-600 mb-10 max-w-2xl mx-auto lg:mx-0 font-medium leading-relaxed">
                    {{ __('landing.hero.subtitle') }}
                </p>

                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-slate-900 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all hover:bg-slate-800 shadow-lg hover:shadow-xl hover:-translate-y-1 w-full sm:w-auto gap-3">
                        {{ __('landing.hero.cta_primary') }}
                        <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-left' : 'fa-arrow-right' }}"></i>
                    </a>
                </div>
            </div>

            <!-- Image Side -->
            <div class="w-full lg:w-7/12 relative" data-animate="fade-image">
                <div class="relative rounded-2xl overflow-hidden shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] border border-slate-200/60 bg-white">
                    <!-- Browser Header -->
                    <div class="flex items-center gap-2 px-4 py-3 bg-slate-100 border-b border-slate-200">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                        </div>
                    </div>
                    <!-- Image -->
                    <img src="{{ asset('images/hero-dashboard.png') }}" alt="Taalimu Dashboard" class="w-full h-auto object-cover object-top block">
                </div>
                
                <!-- Simple Decorative Element -->
                <div class="absolute -bottom-6 -right-6 lg:-bottom-10 lg:-right-10 w-24 h-24 lg:w-32 lg:h-32 bg-emerald-500/10 rounded-full blur-2xl z-[-1]"></div>
                <div class="absolute -top-6 -left-6 lg:-top-10 lg:-left-10 w-32 h-32 lg:w-48 lg:h-48 bg-blue-500/10 rounded-full blur-2xl z-[-1]"></div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes heroFadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

[data-animate="fade-text"] {
    animation: heroFadeInUp 0.8s ease-out forwards;
}
[data-animate="fade-image"] {
    animation: heroFadeInUp 0.8s ease-out 0.2s forwards;
    opacity: 0;
}
</style>
