<section class="relative min-h-[85vh] pt-20 lg:pt-24 overflow-hidden bg-white">
    <!-- Background Decorative Elements -->
    <div class="absolute top-0 right-0 w-[50%] h-full bg-gradient-to-l from-success-green/5 to-transparent -z-10"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-success-green/10 rounded-full blur-3xl -z-10"></div>
    
    <div 
        dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
        class="container relative mx-auto px-4 lg:px-8"
    >
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-8 items-center">
            <!-- Left Content -->
            <div class="w-full lg:w-1/2 text-center lg:text-start z-10">
                <!-- Premium Green Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-success-green text-white mb-8 animate-fade-in shadow-lg shadow-success-green/20">
                    <span class="text-sm font-bold tracking-wide">{{ __('landing.hero.badge') }}</span>
                </div>

                <!-- Headline -->
                <h1 
                    class="font-cairo text-3xl md:text-5xl lg:text-4xl xl:text-5xl font-black text-primary leading-[1.15] mb-6 animate-fade-in delay-1"
                >
                    {!! __('landing.hero.title') !!}
                </h1>

                <!-- Subheadline -->
                <p 
                    class="text-lg md:text-xl text-muted-foreground mb-8 max-w-2xl mx-auto lg:mx-0 animate-fade-in font-medium leading-relaxed delay-2"
                >
                    {{ __('landing.hero.subtitle') }}
                </p>

                <!-- Testimonial Box -->
                <div class="inline-flex items-center gap-4 p-4 md:p-5 rounded-2xl bg-slate-50 border border-slate-100 mb-10 animate-fade-in delay-3 text-start group">
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white flex items-center justify-center shadow-sm text-amber-400 group-hover:scale-110 transition-transform">
                        <i class="fas fa-star text-lg md:text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm md:text-base font-bold text-primary mb-1">"{{ __('landing.hero.testimonial.quote') }}"</p>
                        <p class="text-[12px] md:text-sm text-muted-foreground font-medium">— {{ __('landing.hero.testimonial.author') }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-6 animate-fade-in delay-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl text-lg font-black h-14 px-10 group transition-all bg-success-green text-white shadow-xl shadow-success-green/25 hover:bg-success-green/90 hover:-translate-y-1">
                        {{ __('landing.hero.cta_primary') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="ms-2 group-hover:translate-x-1 transition-transform rtl:rotate-180"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center justify-center rounded-2xl text-lg font-black h-14 px-10 group transition-all border-2 border-slate-200 text-primary hover:bg-slate-50 hover:border-slate-300">
                        <i class="fas fa-play-circle me-3 text-xl text-primary/40 group-hover:text-primary transition-colors"></i>
                        {{ __('landing.hero.cta_secondary') }}
                    </a>
                </div>

                <!-- Trial Info -->
                <div class="flex items-center justify-center lg:justify-start gap-2 animate-fade-in delay-5 text-muted-foreground font-bold text-sm">
                    <div class="w-5 h-5 rounded-full border-2 border-success-green/30 flex items-center justify-center">
                        <div class="w-2 h-2 rounded-full bg-success-green"></div>
                    </div>
                    {{ __('landing.hero.cta_footer') }}
                </div>
            </div>

            <!-- Right Content - Premium Mockup -->
            <div class="w-full lg:w-1/2 relative flex items-center justify-center">
                <div class="relative w-full max-w-[650px] animate-fade-in-right transform-gpu">
                    <!-- Dashboard Mockup Image -->
                    <div class="relative z-10 browser-frame-shadow rounded-[2rem] overflow-hidden border-8 border-white bg-white">
                        <img 
                            src="{{ asset('images/hero-mockup-v2.webp') }}" 
                            alt="Taalimu Dashboard" 
                            class="w-full h-auto"
                            width="800"
                            height="600"
                        >
                    </div>

                    <!-- Floating Card: Profit -->
                    <div class="absolute -bottom-6 -start-6 md:-start-12 z-20 glass-card p-4 md:p-6 animate-float-slow shadow-2xl">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-success-green/10 flex items-center justify-center text-success-green">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m22 7-8.5 8.5-5-5L2 17"/><path d="M16 7h6v6"/></svg>
                            </div>
                            <div>
                                <div class="text-2xl md:text-3xl font-black text-primary">{{ __('landing.hero.mockup.profit.value') }}</div>
                                <div class="text-[11px] md:text-xs font-bold text-muted-foreground uppercase tracking-widest">{{ __('landing.hero.mockup.profit.label') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Card: WhatsApp Notification -->
                    <div class="absolute bottom-12 -end-4 md:-end-10 z-20 glass-card p-4 md:p-5 animate-float-slow delay-1 shadow-2xl min-w-[200px] md:min-w-[260px]">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-emerald-500 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                <i class="fab fa-whatsapp text-white text-xl md:text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-[13px] md:text-[15px] font-black text-primary mb-0.5">{{ __('landing.hero.mockup.whatsapp.payment_received') }}</div>
                                <div class="text-[10px] md:text-[11px] font-bold text-muted-foreground">{{ __('landing.hero.mockup.whatsapp.payment_time') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Background Glow -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-success-green/20 blur-[120px] -z-10"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .text-primary-green {
        color: hsl(var(--success-green));
    }
    
    .animate-float-slow {
        animation: float-slow 4s ease-in-out infinite;
    }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    .delay-5 { animation-delay: 0.5s; }

    @keyframes float-slow {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
</style>
