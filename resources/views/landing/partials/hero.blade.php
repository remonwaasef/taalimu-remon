<section class="relative min-h-[70vh] pt-16 lg:pt-24 overflow-hidden bg-white z-0">
    <!-- Clean Minimal Background Shapes -->
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-green-50 rounded-full blur-[120px] -z-10 opacity-70"></div>
    <div class="absolute -bottom-40 -left-20 w-[600px] h-[600px] bg-slate-50 rounded-full blur-[120px] -z-10"></div>
    
    <div 
        dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
        class="container relative mx-auto px-4 lg:px-12 py-8 lg:py-12"
    >
        <div class="flex flex-col lg:flex-row gap-16 lg:gap-20 items-start justify-between">
            <!-- Left Content -->
            <div class="w-full lg:w-[48%] text-center lg:text-start">
                <!-- Premium Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500 text-white mb-8 animate-fade-in shadow-sm relative z-20">
                    <span class="text-xs md:text-sm font-bold tracking-tight">{{ __('landing.hero.badge') }}</span>
                </div>

                <!-- Headline -->
                <h1 
                    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" 
                    class="font-cairo text-3xl md:text-4xl lg:text-5xl xl:text-5xl font-black text-slate-900 leading-[1.15] mb-6 animate-fade-in tracking-tight delay-1"
                >
                    {!! __('landing.hero.title') !!}
                </h1>

                <!-- Subheadline -->
                <p 
                    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
                    class="text-lg md:text-xl text-slate-500 mb-8 max-w-xl mx-auto lg:mx-0 animate-fade-in font-medium leading-[1.6] delay-2"
                >
                    {{ __('landing.hero.subtitle') }}
                </p>

                <!-- Testimonial Box (NEW) -->
                <div class="mb-10 animate-fade-in delay-3">
                    <div class="inline-flex flex-col md:flex-row items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 shadow-sm max-w-lg">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-white flex items-center justify-center text-amber-400 border border-slate-100 shadow-sm text-xl">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="text-start">
                            <p class="text-sm md:text-base font-bold text-slate-800 leading-snug mb-1">
                                "{{ __('landing.hero.testimonial.quote') }}"
                            </p>
                            <p class="text-xs md:text-sm font-medium text-slate-500">
                                — {{ __('landing.hero.testimonial.author') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Premium CTA Buttons -->
                <div 
                    class="flex flex-col sm:flex-row gap-5 justify-center lg:justify-start mb-6 animate-fade-in delay-4"
                >
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl text-lg font-black h-14 px-10 group transition-all bg-emerald-500 text-white shadow-xl shadow-emerald-500/20 hover:bg-emerald-600 hover:-translate-y-1">
                        {{ __('landing.hero.cta_primary') }}
                        <i class="fas fa-arrow-left ms-2 rtl:rotate-0 ltr:rotate-180 transition-transform group-hover:-translate-x-1"></i>
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center justify-center rounded-2xl text-lg font-black h-14 px-10 group transition-all bg-white border-2 border-slate-100 text-slate-800 hover:bg-slate-50 hover:border-slate-200">
                        <span class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center me-3 text-slate-600 group-hover:bg-slate-200 transition-colors">
                            <i class="fas fa-play text-xs"></i>
                        </span>
                        {{ __('landing.hero.cta_secondary') }}
                    </a>
                </div>

                <!-- Guarantee / Info (NEW) -->
                <div class="flex items-center justify-center lg:justify-start gap-3 text-slate-500 font-bold text-sm animate-fade-in delay-5">
                    <div class="w-5 h-5 rounded-full border-2 border-emerald-500 flex items-center justify-center">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    </div>
                    <span>{{ __('landing.hero.trial_note') }}</span>
                </div>
            </div>

            <!-- Right Content - Clean Minimal Image -->
            <div class="w-full lg:w-[50%] relative flex items-center justify-center pt-4 lg:pt-0 pb-12 lg:pb-16">
                <!-- Simple glowing accent -->
                <div class="absolute inset-0 bg-green-100 rounded-full blur-3xl opacity-30 -z-10 transform scale-125"></div>
                
                <div class="relative z-10 w-full max-w-[580px] animate-fade-in-right">
                    @php
                        $heroImage = match(app()->getLocale()) {
                            'en' => 'hero-mockup-en.webp',
                            'fr' => 'hero-mockup-fr.webp',
                            default => 'hero-mockup-ar.png',
                        };
                    @endphp
                    <img 
                        src="{{ asset('images/' . $heroImage) }}" 
                        alt="Taalimu Dashboard Mockup" 
                        class="w-full h-auto rounded-3xl shadow-2xl border-4 border-white"
                        width="800"
                        height="550"
                        decoding="async"
                        loading="eager"
                        fetchpriority="high"
                    >
                </div>
            </div>
        </div>
    </div>
</section>
