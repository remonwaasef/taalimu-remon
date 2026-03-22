<section class="relative min-h-[70vh] pt-16 lg:pt-24 overflow-hidden bg-white">
    <!-- Sophisticated Background Gradient -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-from),_transparent_50%)] from-emerald-50/40 to-transparent"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,_var(--tw-gradient-from),_transparent_40%)] from-blue-50/30 to-transparent"></div>
    
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

            <!-- Right Content - Hybrid Mockup with Reversed 3D Slant -->
            <div class="w-full lg:w-[50%] relative flex items-start justify-center lg:justify-end pt-4 lg:pt-0 pb-12 lg:pb-16">
                <div 
                    class="relative z-10 w-full max-w-[580px] animate-fade-in-right transform-gpu backface-hidden lg:-mt-16 xl:-mt-24 delay-2"
                    style="perspective: 2000px;"
                >
                    <!-- Floating Stat Cards: Collection Rate (Inward) -->
                    <div 
                        class="absolute top-4 left-6 md:top-8 md:left-12 bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] p-4 z-50 border border-slate-50 text-center animate-bounce-slow transform-gpu"
                        style="transform: translateZ(80px) rotateY(10deg);"
                    >
                        <div class="text-xl md:text-3xl font-black text-emerald-500 mb-0.5">98%</div>
                        <div class="text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('landing.hero.stats.collection') }}</div>
                    </div>

                    <!-- WhatsApp Payment Notification (Inward) -->
                    <div 
                        class="absolute bottom-8 left-12 md:bottom-12 md:left-20 z-50 animate-fade-in-up delay-3 transform-gpu"
                        style="transform: translateZ(100px) rotateY(10deg);"
                    >
                        <div class="flex items-center gap-3 p-3 md:p-5 bg-white rounded-[2rem] shadow-[0_30px_60px_rgba(0,0,0,0.12)] border border-slate-50 min-w-[240px] md:min-w-[300px]">
                            <div class="w-10 h-10 md:w-14 md:h-14 rounded-full bg-[#25D366] flex items-center justify-center text-white text-xl md:text-3xl shadow-lg shadow-[#25D366]/20">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div class="flex-1 text-start">
                                <div class="flex items-center justify-between gap-2 mb-0.5">
                                    <p class="text-sm md:text-lg font-black text-slate-800">{{ __('landing.hero.mockup.whatsapp.payment_success') }}</p>
                                    <i class="fas fa-check-circle text-emerald-500 text-sm md:text-lg"></i>
                                </div>
                                <p class="text-[10px] md:text-sm font-bold text-slate-400">{{ __('landing.hero.mockup.whatsapp.just_now') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Browser Frame with Reversed 3D Tilt -->
                    <div 
                        class="relative group p-1 transition-all duration-700 transform-gpu"
                        style="transform: rotateY(18deg) rotateX(8deg) rotateZ(-2deg); transform-style: preserve-3d;"
                    >
                        <!-- Glow behind the frame -->
                        <div class="absolute inset-0 bg-emerald-500/10 blur-[100px] -z-10 rounded-full scale-110"></div>
                        
                        <div class="relative bg-white/40 backdrop-blur-xl border border-white/60 rounded-[3rem] p-2 md:p-4 shadow-[0_50px_100px_rgba(0,0,0,0.08)] overflow-hidden">
                            <!-- Browser Header -->
                            <div class="flex items-center justify-between px-6 pb-4 md:pb-6 border-b border-slate-200/50 mb-2">
                                <div class="flex gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-rose-400/80"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-amber-400/80"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-400/80"></div>
                                </div>
                                <div class="h-6 px-12 rounded-full bg-slate-100 border border-slate-200/50 flex items-center justify-center">
                                    <div class="w-2 h-2 rounded-full bg-slate-300"></div>
                                </div>
                                <div class="w-10"></div>
                            </div>

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
                                class="w-full h-auto rounded-2xl md:rounded-3xl border border-slate-200/30 shadow-inner"
                                width="800"
                                height="550"
                                decoding="async"
                                loading="eager"
                                fetchpriority="high"
                            >
                        </div>
                    </div>

                    <!-- Background Accents -->
                    <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-emerald-400/10 rounded-full blur-[120px] -z-10"></div>
                    <div class="absolute -top-20 -left-20 w-80 h-80 bg-blue-400/10 rounded-full blur-[120px] -z-10"></div>
                </div>
            </div>
        </div>
    </div>
</section>
