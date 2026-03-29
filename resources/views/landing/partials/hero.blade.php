<section class="relative pt-32 md:pt-32 lg:pt-40 pb-16 lg:pb-20 overflow-hidden bg-slate-50" id="hero">
    <!-- Subtle Grid Background -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9InJnYmEoMTQ4LCAxNjMsIDE4NCwgMC4xNSkiLz48L3N2Zz4=')] [mask-image:linear-gradient(to_bottom,white,transparent_80%)] z-0"></div>

    <!-- Ambient Glows -->
    <div class="absolute top-0 w-full h-[400px] lg:h-[500px] bg-gradient-to-b from-emerald-500/10 via-teal-400/5 to-transparent blur-[80px] lg:blur-[100px] pointer-events-none z-0"></div>

    <div dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="container relative mx-auto px-3 lg:px-8 z-10 max-w-7xl">
        <div class="grid grid-cols-12 gap-4 lg:gap-12 items-center">
            
            <!-- Content Side -->
            <div class="col-span-7 lg:col-span-6 text-start" data-animate="fade-text">
                <!-- Premium Pill Badge -->
                <div class="inline-flex items-center gap-2 lg:gap-3 px-3 py-1.5 lg:px-4 lg:py-2 rounded-full bg-white border border-slate-200 shadow-sm mb-4 lg:mb-8 transition-transform hover:scale-105 cursor-pointer hover:shadow-md hover:border-emerald-200">
                    <span class="relative flex h-2 w-2 lg:h-2.5 lg:w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 lg:h-2.5 lg:w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-[8px] lg:text-xs font-bold text-slate-700 uppercase tracking-widest leading-none">{{ __('landing.hero.badge') }}</span>
                </div>

                <!-- Massive Headline -->
                <h1 class="text-xl md:text-5xl lg:text-[4rem] font-black text-slate-900 leading-[1.2] lg:leading-[1.15] mb-4 lg:mb-6 tracking-tight">
                    {!! __('landing.hero.title', ['highlight' => '<span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">' . __('landing.hero.title_highlight', [], app()->getLocale()) . '</span>']) !!}
                </h1>

                <!-- Refined Subtitle -->
                <p class="text-[10px] md:text-xl text-slate-600 mb-6 lg:mb-10 max-w-xl font-medium leading-relaxed line-clamp-2 lg:line-clamp-none">
                    {{ __('landing.hero.subtitle') }}
                </p>

                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row gap-2 lg:gap-4 items-start mb-6 lg:mb-12">
                    <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center bg-slate-900 text-white px-4 py-2.5 lg:px-8 lg:py-4 rounded-lg lg:rounded-xl font-bold text-xs lg:text-lg transition-all hover:bg-slate-800 hover:shadow-2xl hover:shadow-slate-900/20 hover:-translate-y-1 overflow-hidden w-full sm:w-auto">
                        <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                        <span class="relative flex items-center gap-1 lg:gap-2">
                            {{ __('landing.hero.cta_primary') }}
                            <i class="fas fa-arrow-right text-[10px] lg:text-sm opacity-70 group-hover:translate-x-1 transition-transform rtl:rotate-180"></i>
                        </span>
                    </a>
                    <a href="#features" class="group bg-white hover:bg-slate-50 text-slate-900 border border-slate-200 px-4 py-2.5 lg:px-8 lg:py-4 rounded-lg lg:rounded-xl font-bold text-xs lg:text-lg transition-all shadow-sm hover:shadow-md hover:-translate-y-1 w-full sm:w-auto">
                        <span class="flex items-center justify-center gap-1.5 lg:gap-2">
                            <i class="fas fa-play-circle text-emerald-500 text-sm lg:text-xl group-hover:scale-110 transition-transform"></i>
                            {{ __('landing.hero.cta_secondary') }}
                        </span>
                    </a>
                </div>
                
                <!-- Trust Stats (Simplified on Mobile) -->
                <div class="flex items-center gap-4 lg:gap-10 pt-4 lg:pt-6 opacity-90 border-t border-slate-200/60">
                    <div class="text-start">
                        <div class="text-sm lg:text-2xl font-black text-slate-900 mb-0.5">+500</div>
                        <div class="text-[7px] lg:text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ app()->isLocale('ar') ? 'مركز' : 'Centers' }}</div>
                    </div>
                    <div class="w-px h-6 lg:h-10 bg-slate-200"></div>
                    <div class="text-start">
                        <div class="text-sm lg:text-2xl font-black text-slate-900 mb-0.5">+10k</div>
                        <div class="text-[7px] lg:text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ app()->isLocale('ar') ? 'طالب' : 'Students' }}</div>
                    </div>
                </div>
            </div>

            <!-- Image Side -->
            <div class="col-span-5 lg:col-span-6 relative w-full" data-animate="fade-image">
                <!-- Glowing effect -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[160%] lg:w-[120%] h-[160%] lg:h-[120%] bg-gradient-to-tr from-emerald-500/25 via-teal-500/15 to-blue-500/20 blur-[40px] lg:blur-[100px] -z-10 rounded-full opacity-80"></div>

                <!-- Floating Elements (Ultra Small on Mobile) -->
                <div class="absolute -top-3 -right-1 lg:-top-6 lg:-right-8 glass-card-premium p-1.5 lg:p-4 rounded-lg lg:rounded-2xl shadow-lg z-30 animate-float-slow flex items-center gap-1.5 lg:gap-4 border border-white/60 bg-white/95 backdrop-blur-xl">
                    <div class="w-5 h-5 lg:w-12 lg:h-12 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner">
                        <i class="fas fa-check-circle text-[8px] lg:text-lg"></i>
                    </div>
                    <div class="hidden sm:block lg:block">
                        <div class="text-[6px] lg:text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">{{ app()->isLocale('ar') ? 'ناجح' : 'Success' }}</div>
                        <div class="text-[8px] lg:text-sm font-black text-slate-900 leading-none">1.2k</div>
                    </div>
                </div>

                <!-- 3D Browser Mockup Frame -->
                <div class="hero-3d-wrapper perspective-2000 relative z-20">
                    
                    <!-- WhatsApp Floating Notification -->
                    <div class="absolute -top-12 -left-4 lg:-top-20 lg:-left-20 z-40 animate-float-slow w-48 lg:w-64" data-animate="fade-up">
                        <div class="bg-white/95 backdrop-blur-xl border border-emerald-100 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-emerald-500/10">
                            <div class="bg-emerald-500 px-3 py-1.5 lg:px-4 lg:py-2 flex items-center justify-between">
                                <div class="flex items-center gap-1.5 lg:gap-2">
                                    <i class="fab fa-whatsapp text-white text-[10px] lg:text-xs"></i>
                                    <span class="text-white text-[9px] lg:text-[11px] font-bold uppercase tracking-wider">{{ __('landing.hero.mockup.whatsapp.title') }}</span>
                                </div>
                                <span class="text-white/80 text-[8px] lg:text-[10px]">{{ __('landing.hero.mockup.whatsapp.now') }}</span>
                            </div>
                            <div class="p-2.5 lg:p-4">
                                <div class="flex items-start gap-2 lg:gap-3 mb-2 lg:mb-3">
                                    <div class="w-6 h-6 lg:w-8 lg:h-8 rounded-full bg-slate-100 flex-shrink-0 flex items-center justify-center">
                                        <i class="fas fa-user text-slate-400 text-[10px] lg:text-xs"></i>
                                    </div>
                                    <p class="text-[9px] lg:text-[13px] text-slate-700 leading-snug font-medium">
                                        {{ __('landing.hero.mockup.whatsapp.message') }}
                                    </p>
                                </div>
                                <div class="bg-emerald-50 rounded-lg py-1.5 px-3 text-center border border-emerald-100 shadow-sm">
                                    <span class="text-emerald-700 font-bold text-[9px] lg:text-[12px]">{{ __('landing.hero.mockup.whatsapp.cta') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hero-3d-card rounded-lg lg:rounded-[1.5rem] overflow-hidden border border-slate-200 shadow-xl lg:shadow-[0_30px_100px_-15px_rgba(0,0,0,0.15)] bg-white ring-1 ring-slate-900/5 hero-3d-side">
                        <!-- MacOS style browser header -->
                        <div class="flex items-center gap-1 lg:gap-2 px-1.5 py-1 lg:px-4 lg:py-3 bg-slate-50/80 backdrop-blur-md border-b border-slate-100/80">
                            <div class="flex gap-0.5 lg:gap-2">
                                <div class="w-1 h-1 lg:w-2.5 lg:h-2.5 rounded-full bg-red-400/80"></div>
                                <div class="w-1 h-1 lg:w-2.5 lg:h-2.5 rounded-full bg-yellow-400/80"></div>
                                <div class="w-1 h-1 lg:w-2.5 lg:h-2.5 rounded-full bg-emerald-400/80"></div>
                            </div>
                            <div class="flex-1 mx-1 lg:mx-4">
                                <div class="bg-white/70 border border-slate-200/60 rounded px-1 lg:px-4 py-0.5 text-[5px] lg:text-[10px] text-slate-500 font-mono text-center truncate max-w-[80px] lg:max-w-[200px] mx-auto">
                                    app.taalimu.com
                                </div>
                            </div>
                        </div>
                        <!-- Image Container -->
                        <div class="relative bg-slate-100 overflow-hidden">
                            <img src="{{ asset('images/hero-dashboard.png') }}" alt="Taalimu Dashboard" class="w-full h-auto object-cover object-top border-b border-white">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<style>
@keyframes heroFadeInRight {
    from { opacity: 0; transform: translateX(30px) scale(0.98); }
    to { opacity: 1; transform: translateX(0) scale(1); }
}
@keyframes heroFadeInLeft {
    from { opacity: 0; transform: translateX(-30px) scale(0.98); }
    to { opacity: 1; transform: translateX(0) scale(1); }
}
@keyframes heroFadeUpMobile {
    from { opacity: 0; transform: translateY(20px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

/* Animations */
[data-animate="fade-text"], [data-animate="fade-image"] {
    animation: heroFadeUpMobile 0.8s lg:heroFadeInRight 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@media (min-width: 1024px) {
    [dir="rtl"] [data-animate="fade-text"] { animation: heroFadeInRight 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    [dir="rtl"] [data-animate="fade-image"] { animation: heroFadeInLeft 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    [dir="ltr"] [data-animate="fade-text"] { animation: heroFadeInLeft 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    [dir="ltr"] [data-animate="fade-image"] { animation: heroFadeInRight 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
}

.perspective-2000 {
    perspective: 2000px;
}

.hero-3d-wrapper {
    transform-style: preserve-3d;
}

.hero-3d-side {
    filter: drop-shadow(0 10px 20px rgba(0,0,0,0.05));
    transform: {{ app()->isLocale('ar') ? 'rotateY(8deg) rotateX(2deg)' : 'rotateY(-8deg) rotateX(2deg)' }} scale(1);
    transform-origin: center;
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

@media (min-width: 1024px) {
    .hero-3d-side {
        filter: drop-shadow(0 20px 40px rgba(0,0,0,0.08));
        transform: {{ app()->isLocale('ar') ? 'rotateY(12deg) rotateX(4deg)' : 'rotateY(-12deg) rotateX(4deg)' }} scale(0.98);
    }
}

.hero-3d-wrapper:hover .hero-3d-side {
    transform: rotateY(0deg) rotateX(0deg) scale(1.02);
    filter: drop-shadow(0 30px 60px rgba(16, 185, 129, 0.15));
}

.glass-card-premium {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
}
</style>
