<section class="relative min-h-screen pt-20 pb-32 overflow-hidden hero-mesh-bg">
    <!-- Animated Background Blobs -->
    <div class="blob top-[-10%] left-[-10%] opacity-20"></div>
    <div class="blob bottom-[-10%] right-[-10%] opacity-30 animation-delay-2000" style="background: linear-gradient(135deg, var(--hero-mesh-3) 0%, var(--hero-mesh-1) 100%);"></div>
    
    <div 
        dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
        class="container relative z-10 mx-auto px-4"
    >
        <!-- Content Wrapper - Centered Design -->
        <div class="max-w-4xl mx-auto text-center mb-16 lg:mb-24">
            <!-- Premium Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-morphism mb-8 animate-fade-in shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
                </span>
                <span class="text-xs font-bold text-primary tracking-wide uppercase">{{ __('landing.hero.badge') }}</span>
            </div>

            <!-- Main Headline -->
            <h1 
                class="font-cairo text-4xl md:text-5xl lg:text-7xl font-black text-primary leading-[1.1] mb-8 animate-fade-in-up tracking-tight"
            >
                {!! __('landing.hero.title') !!}
            </h1>

            <!-- Subheadline -->
            <p 
                class="text-lg md:text-xl text-muted-foreground/80 mb-10 max-w-2xl mx-auto animate-fade-in-up font-medium leading-relaxed delay-2 shadow-sm"
            >
                {{ \App\Models\SiteSetting::get('site_description_' . app()->getLocale(), __('landing.hero.subtitle')) }}
            </p>

            <!-- Premium CTA Group -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up delay-3">
                <a href="{{ route('register') }}?account_type=center" class="group relative inline-flex items-center justify-center p-0.5 mb-2 overflow-hidden text-sm font-black text-white rounded-full bg-gradient-to-br from-secondary to-secondary-vibrant group-hover:from-secondary group-hover:to-secondary-vibrant hover:text-white focus:ring-4 focus:outline-none focus:ring-secondary/30 transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-xl shadow-secondary/20">
                    <span class="relative px-8 py-4 transition-all ease-in duration-75 bg-secondary group-hover:bg-opacity-0 rounded-full flex items-center gap-3">
                        <i class="fas fa-rocket text-sm"></i>
                        {{ app()->isLocale('ar') ? 'سجل كمركز تعليمي' : 'Register as Center' }}
                    </span>
                </a>
                
                <a href="{{ route('register') }}?account_type=instructor" class="group relative inline-flex items-center justify-center p-0.5 mb-2 overflow-hidden text-sm font-black text-primary rounded-full border-2 border-primary/20 hover:border-primary/40 transition-all duration-300 transform hover:scale-105 active:scale-95">
                    <span class="relative px-8 py-3.5 flex items-center gap-3">
                        <i class="fas fa-user-tie text-sm"></i>
                        {{ app()->isLocale('ar') ? 'سجل كمدرس مستقل' : 'Register as Teacher' }}
                    </span>
                </a>
            </div>
        </div>

        <!-- Interactive Visual Section -->
        <div class="relative max-w-6xl mx-auto mt-12 px-4 lg:px-0">
            <!-- Main Browser Mockup -->
            <div class="relative z-10 glass-morphism p-2 rounded-3xl browser-frame-shadow animate-fade-in-up delay-4">
                <div class="bg-white rounded-2xl overflow-hidden border border-border/50">
                    <!-- Browser Header -->
                    <div class="flex items-center justify-between px-4 py-3 bg-muted/30 border-b border-border/50">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                        </div>
                        <div class="h-6 px-12 rounded-full bg-white/50 border border-border/50 flex items-center text-[10px] text-muted-foreground font-mono">
                            taalimu.com/dashboard/analytics
                        </div>
                        <div class="w-10"></div>
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
                        class="w-full h-auto"
                        width="1200"
                        height="800"
                        decoding="async"
                        loading="eager"
                        fetchpriority="high"
                    >
                </div>
            </div>

            <!-- Floating Interactive Elements -->
            <!-- WhatsApp Notification Card -->
            <div class="absolute -top-10 -start-10 md:-start-20 z-20 w-64 md:w-80 animate-float-slow hidden md:block">
                <div class="glass-morphism p-1 rounded-3xl shadow-2xl border-white/50">
                    <div class="bg-white/90 rounded-2xl p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-success-green flex items-center justify-center text-white shadow-lg shadow-success-green/20">
                                <i class="fab fa-whatsapp text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-black text-primary">{{ __('landing.hero.mockup.whatsapp.title') }}</h4>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success-green animate-pulse"></span>
                                    <span class="text-[10px] font-bold text-success-green tracking-wide">{{ __('landing.hero.mockup.whatsapp.online') }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-[12px] text-foreground font-medium leading-relaxed mb-3">
                            {{ __('landing.hero.mockup.whatsapp.message') }}
                        </p>
                        <div class="flex justify-between items-center pt-3 border-t border-border/30">
                            <span class="text-[12px] font-black text-secondary flex items-center gap-1">
                                {{ __('landing.hero.mockup.whatsapp.cta') }}
                                <i class="fas fa-arrow-left text-[10px] rtl:block ltr:hidden"></i>
                                <i class="fas fa-arrow-right text-[10px] ltr:block rtl:hidden"></i>
                            </span>
                            <span class="text-[9px] font-bold text-muted-foreground">10:30 AM</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Badge 1: Revenue -->
            <div class="absolute top-[40%] -start-8 md:-start-16 z-20 animate-float-delayed hidden md:block">
                <div class="glass-morphism px-6 py-4 rounded-2xl shadow-xl border-white/40">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                            <i class="fas fa-chart-line text-lg"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-black gradient-text">38%+</div>
                            <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">{{ __('landing.hero.stats.revenue') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Badge 2: Time Saving -->
            <div class="absolute bottom-10 -end-8 md:-end-16 z-20 animate-float-slow hidden md:block" style="animation-delay: 1.5s;">
                <div class="glass-morphism px-6 py-4 rounded-2xl shadow-xl border-white/40">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary/5 flex items-center justify-center text-primary">
                            <i class="fas fa-clock text-lg"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-primary">15h</div>
                            <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">{{ __('landing.hero.stats.time') }}</div>
                        </div>
                    </div>
                </div>
            </div>

<section class="relative min-h-screen pt-20 pb-32 overflow-hidden hero-mesh-bg">
    <!-- Animated Background Blobs -->
    <div class="blob top-[-10%] left-[-10%] opacity-20"></div>
    <div class="blob bottom-[-10%] right-[-10%] opacity-30 animation-delay-2000" style="background: linear-gradient(135deg, var(--hero-mesh-3) 0%, var(--hero-mesh-1) 100%);"></div>
    
    <div 
        dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
        class="container relative z-10 mx-auto px-4"
    >
        <!-- Content Wrapper - Centered Design -->
        <div class="max-w-4xl mx-auto text-center mb-16 lg:mb-24">
            <!-- Premium Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-morphism mb-8 animate-fade-in shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
                </span>
                <span class="text-xs font-bold text-primary tracking-wide uppercase">{{ __('landing.hero.badge') }}</span>
            </div>

            <!-- Main Headline -->
            <h1 
                class="font-cairo text-4xl md:text-5xl lg:text-7xl font-black text-primary leading-[1.1] mb-8 animate-fade-in-up tracking-tight"
            >
                {!! __('landing.hero.title') !!}
            </h1>

            <!-- Subheadline -->
            <p 
                class="text-lg md:text-xl text-muted-foreground/80 mb-10 max-w-2xl mx-auto animate-fade-in-up font-medium leading-relaxed delay-2 shadow-sm"
            >
                {{ \App\Models\SiteSetting::get('site_description_' . app()->getLocale(), __('landing.hero.subtitle')) }}
            </p>

            <!-- Premium CTA Group -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up delay-3">
                <a href="{{ route('register') }}?account_type=center" class="group relative inline-flex items-center justify-center p-0.5 mb-2 overflow-hidden text-sm font-black text-white rounded-full bg-gradient-to-br from-secondary to-secondary-vibrant group-hover:from-secondary group-hover:to-secondary-vibrant hover:text-white focus:ring-4 focus:outline-none focus:ring-secondary/30 transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-xl shadow-secondary/20">
                    <span class="relative px-8 py-4 transition-all ease-in duration-75 bg-secondary group-hover:bg-opacity-0 rounded-full flex items-center gap-3">
                        <i class="fas fa-rocket text-sm"></i>
                        {{ app()->isLocale('ar') ? 'سجل كمركز تعليمي' : 'Register as Center' }}
                    </span>
                </a>
                
                <a href="{{ route('register') }}?account_type=instructor" class="group relative inline-flex items-center justify-center p-0.5 mb-2 overflow-hidden text-sm font-black text-primary rounded-full border-2 border-primary/20 hover:border-primary/40 transition-all duration-300 transform hover:scale-105 active:scale-95">
                    <span class="relative px-8 py-3.5 flex items-center gap-3">
                        <i class="fas fa-user-tie text-sm"></i>
                        {{ app()->isLocale('ar') ? 'سجل كمدرس مستقل' : 'Register as Teacher' }}
                    </span>
                </a>
            </div>
        </div>

        <!-- Interactive Visual Section -->
        <div class="relative max-w-6xl mx-auto mt-12 px-4 lg:px-0">
            <!-- Main Browser Mockup -->
            <div class="relative z-10 glass-morphism p-2 rounded-3xl browser-frame-shadow animate-fade-in-up delay-4">
                <div class="bg-white rounded-2xl overflow-hidden border border-border/50">
                    <!-- Browser Header -->
                    <div class="flex items-center justify-between px-4 py-3 bg-muted/30 border-b border-border/50">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                        </div>
                        <div class="h-6 px-12 rounded-full bg-white/50 border border-border/50 flex items-center text-[10px] text-muted-foreground font-mono">
                            taalimu.com/dashboard/analytics
                        </div>
                        <div class="w-10"></div>
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
                        class="w-full h-auto"
                        width="1200"
                        height="800"
                        decoding="async"
                        loading="eager"
                        fetchpriority="high"
                    >
                </div>
            </div>

            <!-- Floating Interactive Elements -->
            <!-- WhatsApp Notification Card -->
            <div class="absolute -top-10 -start-10 md:-start-20 z-20 w-64 md:w-80 animate-float-slow hidden md:block">
                <div class="glass-morphism p-1 rounded-3xl shadow-2xl border-white/50">
                    <div class="bg-white/90 rounded-2xl p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-success-green flex items-center justify-center text-white shadow-lg shadow-success-green/20">
                                <i class="fab fa-whatsapp text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-black text-primary">{{ __('landing.hero.mockup.whatsapp.title') }}</h4>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success-green animate-pulse"></span>
                                    <span class="text-[10px] font-bold text-success-green tracking-wide">{{ __('landing.hero.mockup.whatsapp.online') }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-[12px] text-foreground font-medium leading-relaxed mb-3">
                            {{ __('landing.hero.mockup.whatsapp.message') }}
                        </p>
                        <div class="flex justify-between items-center pt-3 border-t border-border/30">
                            <span class="text-[12px] font-black text-secondary flex items-center gap-1">
                                {{ __('landing.hero.mockup.whatsapp.cta') }}
                                <i class="fas fa-arrow-left text-[10px] rtl:block ltr:hidden"></i>
                                <i class="fas fa-arrow-right text-[10px] ltr:block rtl:hidden"></i>
                            </span>
                            <span class="text-[9px] font-bold text-muted-foreground">10:30 AM</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Badge 1: Revenue -->
            <div class="absolute top-[40%] -start-8 md:-start-16 z-20 animate-float-delayed hidden md:block">
                <div class="glass-morphism px-6 py-4 rounded-2xl shadow-xl border-white/40">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                            <i class="fas fa-chart-line text-lg"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-black gradient-text">38%+</div>
                            <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">{{ __('landing.hero.stats.revenue') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Badge 2: Time Saving -->
            <div class="absolute bottom-10 -end-8 md:-end-16 z-20 animate-float-slow hidden md:block" style="animation-delay: 1.5s;">
                <div class="glass-morphism px-6 py-4 rounded-2xl shadow-xl border-white/40">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary/5 flex items-center justify-center text-primary">
                            <i class="fas fa-clock text-lg"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-primary">15h</div>
                            <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">{{ __('landing.hero.stats.time') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust Badge: Security -->
            <div class="absolute -bottom-12 start-1/4 z-20 animate-fade-in-up delay-5 hidden md:block">
                <div class="glass-morphism px-5 py-3 rounded-full flex items-center gap-3 border-white/30 backdrop-blur-xl">
                    <div class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-shield-alt text-xs"></i>
                    </div>
                    <span class="text-xs font-black text-primary">{{ __('landing.hero.trust.security') }}</span>
                    <div class="w-1px h-4 bg-primary/10 mx-1"></div>
                    <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest">{{ app()->isLocale('ar') ? 'معايير عالمية' : 'Enterprise Grade' }}</span>
                </div>
            </div>
        </div>
    </div>
</section>
