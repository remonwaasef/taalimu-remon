<section class="relative min-h-screen pt-20 lg:pt-24 overflow-hidden hero-professional-bg noise-overlay">
    <!-- Background Elements - Simplified -->
    <div class="absolute inset-0 bg-slate-50/50"></div>
    
    <div 
        dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
        class="container relative mx-auto px-4 lg:px-8 py-12 lg:py-20"
    >
        <div class="flex flex-col lg:flex-row gap-20 lg:gap-24 items-center justify-between">
            <!-- Left Content -->
            <div class="w-full lg:w-[45%] text-center lg:text-start">
                <!-- Premium Badge -->
                <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-primary/5 backdrop-blur-md border border-primary/10 mb-10 animate-fade-in shadow-sm relative z-20">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                    </span>
                    <span class="text-sm font-bold text-primary tracking-wide">{{ __('landing.hero.badge') }}</span>
                </div>

                <!-- Headline -->
                <h1 
                    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" 
                    class="font-cairo text-4xl md:text-5xl lg:text-4xl xl:text-5xl font-black text-primary leading-[1.2] mb-8 animate-fade-in tracking-tight delay-1"
                >
                    {!! __('landing.hero.title') !!}
                </h1>

                <!-- Subheadline -->
                <p 
                    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
                    class="text-xl md:text-2xl text-slate-200/90 mb-10 max-w-xl mx-auto lg:mx-0 animate-fade-in font-medium leading-[1.6] md:leading-relaxed delay-2"
                >
                    {{ \App\Models\SiteSetting::get('site_description_' . app()->getLocale(), __('landing.hero.subtitle')) }}
                </p>

                <!-- Premium CTA Buttons -->
                <div 
                    class="flex flex-col sm:flex-row gap-5 justify-center lg:justify-start mb-12 animate-fade-in delay-3"
                >
                    <a href="{{ route('register') }}?account_type=center" class="inline-flex items-center justify-center rounded-full text-lg font-black h-16 md:h-20 px-10 md:px-12 group transition-all bg-secondary text-white shadow-lg hover:bg-secondary/90 hover:-translate-y-1">
                        <i class="fas fa-university me-3"></i>
                        {{ app()->isLocale('ar') ? 'سجل كمركز تعليمي' : 'Register as Center' }}
                    </a>
                    <a href="{{ route('register') }}?account_type=instructor" class="inline-flex items-center justify-center rounded-full text-lg font-black h-16 md:h-20 px-10 md:px-12 group transition-all border-2 border-primary/10 text-primary hover:bg-primary/5">
                        <i class="fas fa-chalkboard-teacher me-3"></i>
                        {{ app()->isLocale('ar') ? 'سجل كمدرس مستقل' : 'Register as Teacher' }}
                    </a>
                </div>

                <!-- Professional Trust Badges - Simplified -->
                <div 
                    class="flex flex-wrap gap-8 justify-center lg:justify-start animate-fade-in py-6 border-y border-white/10 mb-12 delay-4"
                >
                    <div class="flex items-center gap-3 text-sm font-bold text-slate-300 group cursor-default">
                        <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-white group-hover:bg-primary group-hover:text-white transition-colors border border-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1-1z"/></svg>
                        </div>
                        {{ __('landing.hero.trust.security') }}
                    </div>
                    <div class="flex items-center gap-3 text-sm font-bold text-slate-300 group cursor-default">
                        <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-white group-hover:bg-primary group-hover:text-white transition-colors border border-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                        </div>
                        {{ __('landing.hero.trust.centers') }}
                    </div>
                    <div class="flex items-center gap-3 text-sm font-bold text-slate-300 group cursor-default">
                        <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-white group-hover:bg-primary group-hover:text-white transition-colors border border-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/></svg>
                        </div>
                        {{ __('landing.hero.trust.uptime') }}
                    </div>
                </div>

                <!-- High Impact Stats -->
                <div 
                    class="grid grid-cols-3 gap-6 animate-fade-in delay-5"
                >
                    <div class="text-center lg:text-start p-4 rounded-2xl bg-white border border-border shadow-sm">
                        <div class="text-3xl lg:text-4xl font-black gradient-text mb-1">38%</div>
                        <div class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest">{{ __('landing.hero.stats.revenue') }}</div>
                    </div>
                    <div class="text-center lg:text-start p-4 rounded-2xl bg-white border border-border shadow-sm">
                        <div class="text-3xl lg:text-4xl font-black gradient-text mb-1">15h</div>
                        <div class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest">{{ __('landing.hero.stats.time') }}</div>
                    </div>
                    <div class="text-center lg:text-start p-4 rounded-2xl bg-white border border-border shadow-sm">
                        <div class="text-3xl lg:text-4xl font-black gradient-text mb-1">98%</div>
                        <div class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest">{{ __('landing.hero.stats.collection') }}</div>
                    </div>
                </div>
            </div>

            <!-- Right Content - Hybrid Mockup -->
            <div class="w-full lg:w-[55%] relative flex items-center justify-center lg:justify-end">
                <div 
                    class="relative z-10 w-full max-w-[750px] animate-fade-in-right animate-float-slow transform-gpu backface-hidden delay-2"
                >
                    <!-- Localized Floating WhatsApp Notification -->
                    <div 
                        class="absolute top-0 -start-6 md:-start-12 w-64 md:w-80 glass-card rounded-3xl shadow-2xl z-50 p-1 border border-white/40 overflow-hidden"
                    >
                        <div class="bg-white/90 rounded-[1.4rem] overflow-hidden">
                            <div class="p-3 md:p-4 flex items-center gap-3 bg-muted/20">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-2xl bg-success-green flex items-center justify-center shadow-lg shadow-success-green/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-0.5">
                                        <span class="text-xs md:text-sm font-bold text-foreground">{{ __('landing.hero.mockup.whatsapp.title') }}</span>
                                        <span class="text-[9px] md:text-[10px] font-medium text-muted-foreground">{{ __('landing.hero.mockup.whatsapp.now') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-1.5 h-1.5 rounded-full bg-success-green"></div>
                                        <span class="text-[10px] md:text-[11px] font-bold text-success-green uppercase tracking-wider">{{ __('landing.hero.mockup.whatsapp.online') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 md:p-4 bg-gradient-to-b from-muted/10 to-transparent">
                                <div class="bg-white rounded-2xl p-3 md:p-4 shadow-sm border border-border/10">
                                    <p class="text-[12px] md:text-[13px] text-foreground font-medium leading-relaxed antialiased">
                                        {{ __('landing.hero.mockup.whatsapp.message') }}
                                    </p>
                                    <div class="mt-3 md:mt-4 pt-2 md:pt-3 border-t border-border/50 flex justify-between items-center">
                                        <span class="text-[12px] md:text-[13px] font-black text-cyan flex items-center gap-1">
                                            {{ __('landing.hero.mockup.whatsapp.cta') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="rtl:rotate-180"><path d="m9 18 6-6-6-6"/></svg>
                                        </span>
                                        <span class="text-[9px] md:text-[10px] font-bold text-muted-foreground">10:30 AM</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Elite Browser Frame Mockup -->
                    <div class="relative group browser-frame-shadow p-2 md:p-3 bg-white/40 backdrop-blur-xl border border-white/50 rounded-3xl overflow-hidden">
                        <!-- Browser Header -->
                        <div class="flex items-center justify-between px-4 pb-3 md:pb-4 border-b border-black/5 mb-2">
                            <div class="flex gap-1.5">
                                <div class="w-2 md:w-2.5 h-2 md:h-2.5 rounded-full bg-red-400/80"></div>
                                <div class="w-2 md:w-2.5 h-2 md:h-2.5 rounded-full bg-amber-400/80"></div>
                                <div class="w-2 md:w-2.5 h-2 md:h-2.5 rounded-full bg-emerald-400/80"></div>
                            </div>
                            <div class="h-5 md:h-6 px-10 rounded-full bg-black/5 border border-black/5 flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-black/10"></div>
                            </div>
                            <div class="w-6 md:w-10"></div>
                        </div>

                        @php
                            $heroImage = match(app()->getLocale()) {
                                'en' => 'hero-mockup-en.webp',
                                'fr' => 'hero-mockup-fr.webp',
                                default => 'hero-mockup-v2.webp',
                            };
                        @endphp
                        <img 
                            src="{{ asset('images/' . $heroImage) }}" 
                            alt="EduFlow Dashboard Mockup" 
                            class="w-full h-auto rounded-xl md:rounded-2xl border border-black/5 shadow-inner"
                            width="750"
                            height="500"
                            decoding="async"
                            loading="eager"
                            fetchpriority="high"
                        >
                    </div>
                    
                    <!-- Minimalist Decorative Elements -->
                    <div class="absolute -top-12 -left-12 w-32 h-32 bg-primary/5 rounded-full blur-2xl -z-10"></div>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
