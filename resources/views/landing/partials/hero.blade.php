<section class="relative min-h-[85vh] pt-20 lg:pt-28 pb-12 overflow-hidden bg-white z-0">
    <!-- Background Decorations -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-green-50 rounded-full blur-[100px] -z-10 opacity-60"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-green-50/30 rounded-full blur-[80px] -z-10"></div>
    
    <!-- Dotted Pattern -->
    <div class="absolute top-20 left-10 w-24 h-24 opacity-20 -z-10" style="background-image: radial-gradient(circle, #22c55e 1.5px, transparent 1.5px); background-size: 12px 12px;"></div>
    <div class="absolute bottom-32 right-16 w-20 h-20 opacity-15 -z-10" style="background-image: radial-gradient(circle, #22c55e 1.5px, transparent 1.5px); background-size: 12px 12px;"></div>

    <div 
        dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
        class="container relative mx-auto px-4 lg:px-12"
    >
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-8 items-center justify-between">
            <!-- Left Content -->
            <div class="w-full lg:w-[50%] text-center lg:text-start">
                <!-- Guarantee Badge -->
                <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full bg-green-50 border border-green-200/60 mb-8 animate-fade-in shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-[#22c55e]"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span class="text-sm font-bold text-slate-700 tracking-tight">{{ __('landing.hero.badge') }}</span>
                </div>

                <!-- Headline -->
                <h1 
                    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" 
                    class="font-cairo text-3xl md:text-4xl lg:text-[2.8rem] xl:text-5xl font-black text-slate-900 leading-[1.15] mb-6 animate-fade-in tracking-tight"
                >
                    {!! __('landing.hero.title') !!}
                </h1>

                <!-- Subheadline -->
                <p 
                    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
                    class="text-base md:text-lg text-slate-500 mb-10 max-w-lg mx-auto lg:mx-0 animate-fade-in font-medium leading-[1.7]"
                >
                    {{ __('landing.hero.subtitle') }}
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-8 animate-fade-in">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full text-base font-bold h-13 px-8 py-3.5 group transition-all duration-300 bg-[#22c55e] text-white shadow-lg shadow-green-500/25 hover:bg-[#16a34a] hover:-translate-y-0.5 hover:shadow-xl hover:shadow-green-500/30">
                        {{ __('landing.hero.cta_primary') }}
                        <svg class="w-4 h-4 ms-2 rtl:rotate-180 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center justify-center rounded-full text-base font-bold h-13 px-8 py-3.5 group transition-all duration-300 bg-white border-2 border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:-translate-y-0.5">
                        <span class="w-7 h-7 rounded-full bg-green-50 flex items-center justify-center me-2.5 text-[#22c55e] group-hover:bg-green-100 transition-colors">
                            <i class="fas fa-play text-[10px]"></i>
                        </span>
                        {{ __('landing.hero.cta_secondary') }}
                    </a>
                </div>

                <!-- Star Rating + Users -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-6 animate-fade-in">
                    <!-- Star Rating -->
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-0.5">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <span class="text-sm font-bold text-slate-700">(4.7 {{ __('landing.hero.ratings') ?? 'Ratings' }})</span>
                    </div>
                    <!-- Users -->
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-2 rtl:space-x-reverse">
                            <div class="w-7 h-7 rounded-full bg-green-200 border-2 border-white flex items-center justify-center text-[10px] font-bold text-green-700">A</div>
                            <div class="w-7 h-7 rounded-full bg-blue-200 border-2 border-white flex items-center justify-center text-[10px] font-bold text-blue-700">S</div>
                            <div class="w-7 h-7 rounded-full bg-amber-200 border-2 border-white flex items-center justify-center text-[10px] font-bold text-amber-700">M</div>
                        </div>
                        <span class="text-sm font-medium text-slate-500">{{ __('landing.hero.trial_note') }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Content - EducateX Style Visual -->
            <div class="w-full lg:w-[48%] relative flex items-center justify-center">
                <!-- Large Green Background Shape -->
                <div class="absolute w-[340px] h-[340px] md:w-[420px] md:h-[420px] lg:w-[460px] lg:h-[460px] bg-[#22c55e] rounded-[2.5rem] rotate-6 -z-0 opacity-90 shadow-2xl shadow-green-500/20"></div>
                <div class="absolute w-[340px] h-[340px] md:w-[420px] md:h-[420px] lg:w-[460px] lg:h-[460px] bg-[#16a34a] rounded-[2.5rem] -rotate-3 -z-10 opacity-40"></div>

                <!-- Hero Person Image -->
                <div class="relative z-10 animate-fade-in-right">
                    <img 
                        src="{{ asset('images/hero-teacher.png') }}" 
                        alt="{{ __('landing.hero.badge') }}" 
                        class="w-[300px] md:w-[380px] lg:w-[420px] h-auto object-contain drop-shadow-2xl"
                        width="420"
                        height="500"
                        decoding="async"
                        loading="eager"
                        fetchpriority="high"
                    >
                </div>

                <!-- Floating Stat Card: Top Right -->
                <div class="absolute top-4 right-0 md:top-6 md:-right-4 bg-white rounded-2xl shadow-xl p-3.5 z-20 border border-slate-100 animate-float-slow">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#22c55e]"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div>
                            <div class="text-lg font-black text-slate-800">130+</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('landing.hero.stats.collection') ?? 'Expert Instructors' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Floating Stat Card: Bottom Left -->
                <div class="absolute bottom-8 left-0 md:bottom-12 md:-left-4 bg-white rounded-2xl shadow-xl p-3.5 z-20 border border-slate-100 animate-float-slow" style="animation-delay: 1.5s;">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </div>
                        <div>
                            <div class="text-lg font-black text-slate-800">98%</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('landing.automation.result.rate') ?? 'Collection Rate' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Small decorative shapes -->
                <div class="absolute -top-4 left-8 w-6 h-6 bg-amber-300 rounded-full opacity-80 animate-bounce-subtle -z-10"></div>
                <div class="absolute bottom-20 -right-6 w-4 h-4 bg-[#22c55e] rounded-sm rotate-45 opacity-60 -z-10"></div>
                <div class="absolute top-1/2 -left-8 w-3 h-3 bg-blue-400 rounded-full opacity-50 -z-10"></div>
            </div>
        </div>
    </div>
</section>
