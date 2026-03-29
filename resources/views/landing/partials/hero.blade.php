<section class="relative pt-40 md:pt-32 lg:pt-40 pb-20 overflow-hidden bg-slate-50" id="hero">
    <!-- Subtle Grid Background -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9InJnYmEoMTQ4LCAxNjMsIDE4NCwgMC4xNSkiLz48L3N2Zz4=')] [mask-image:linear-gradient(to_bottom,white,transparent_80%)] z-0"></div>

    <!-- Ambient Glows -->
    <div class="absolute top-0 w-full h-[500px] bg-gradient-to-b from-emerald-500/10 via-teal-400/5 to-transparent blur-[100px] pointer-events-none z-0"></div>

    <div dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="container relative mx-auto px-4 lg:px-8 z-10 max-w-7xl">
        <div class="grid lg:grid-cols-2 gap-16 lg:gap-12 items-center">
            
            <!-- Content Side -->
            <div class="text-center lg:text-start" data-animate="fade-text">
                <!-- Premium Pill Badge -->
                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white border border-slate-200 shadow-sm mb-6 lg:mb-8 transition-transform hover:scale-105 cursor-pointer hover:shadow-md hover:border-emerald-200">
                    <span class="relative flex h-2 w-2 lg:h-2.5 lg:w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 lg:h-2.5 lg:w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] lg:text-xs font-bold text-slate-700 uppercase tracking-widest">{{ __('landing.hero.badge') }}</span>
                </div>

                <!-- Massive Headline -->
                <h1 class="text-[2.5rem] md:text-5xl lg:text-[4rem] font-black text-slate-900 leading-[1.2] lg:leading-[1.15] mb-6 tracking-tight">
                    {!! __('landing.hero.title', ['highlight' => '<span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">' . __('landing.hero.title_highlight', [], app()->getLocale()) . '</span>']) !!}
                </h1>

                <!-- Refined Subtitle -->
                <p class="text-base md:text-xl text-slate-600 mb-10 max-w-xl mx-auto lg:mx-0 font-medium leading-relaxed">
                    {{ __('landing.hero.subtitle') }}
                </p>

                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-center lg:justify-start lg:items-start mb-10 lg:mb-12 mx-auto lg:mx-0 max-w-sm sm:max-w-none">
                    <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center bg-slate-900 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all hover:bg-slate-800 hover:shadow-2xl hover:shadow-slate-900/20 hover:-translate-y-1 overflow-hidden w-full sm:w-auto">
                        <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                        <span class="relative flex items-center gap-2">
                            {{ __('landing.hero.cta_primary') }}
                            <i class="fas fa-arrow-right text-sm opacity-70 group-hover:translate-x-1 transition-transform rtl:rotate-180"></i>
                        </span>
                    </a>
                    <a href="#features" class="group bg-white hover:bg-slate-50 text-slate-900 border border-slate-200 px-8 py-4 rounded-xl font-bold text-lg transition-all shadow-sm hover:shadow-md hover:-translate-y-1 w-full sm:w-auto">
                        <span class="flex items-center justify-center gap-2">
                            <i class="fas fa-play-circle text-emerald-500 text-xl group-hover:scale-110 transition-transform"></i>
                            {{ __('landing.hero.cta_secondary') }}
                        </span>
                    </a>
                </div>
                
                <!-- Trust Stats -->
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-6 md:gap-10 pt-6 opacity-90 border-t border-slate-200/60 max-w-lg mx-auto lg:mx-0">
                    <div class="text-center lg:text-start">
                        <div class="text-2xl font-black text-slate-900 mb-0.5">+500</div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ app()->isLocale('ar') ? 'مركز تعليمي' : 'Learning Centers' }}</div>
                    </div>
                    <div class="w-px h-10 bg-slate-200"></div>
                    <div class="text-center lg:text-start">
                        <div class="text-2xl font-black text-slate-900 mb-0.5">+10k</div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ app()->isLocale('ar') ? 'طالب نشط' : 'Active Students' }}</div>
                    </div>
                </div>
            </div>

            <!-- Image Side -->
            <div class="relative w-full lg:mt-0 mt-12" data-animate="fade-image">
                <!-- Glowing effect -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-gradient-to-tr from-emerald-500/20 via-teal-500/10 to-blue-500/20 blur-[100px] -z-10 rounded-[3rem] opacity-80"></div>

                <!-- Floating Elements Over Mockup -->
                <div class="absolute -top-6 -right-4 md:-right-8 glass-card-premium p-3 md:p-4 rounded-2xl shadow-xl z-30 animate-float-slow hidden lg:flex items-center gap-4 border border-white/60 bg-white/90 backdrop-blur-xl">
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner">
                        <i class="fas fa-check-circle lg:text-lg"></i>
                    </div>
                    <div>
                        <div class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">{{ app()->isLocale('ar') ? 'تحصيل ناجح' : 'Payment Success' }}</div>
                        <div class="text-xs md:text-sm font-black text-slate-900">1,250 SAR</div>
                    </div>
                </div>

                <div class="absolute -bottom-6 -left-4 md:-left-8 glass-card-premium p-3 md:p-4 rounded-2xl shadow-xl z-30 animate-float-fast hidden lg:flex items-center gap-4 border border-white/60 bg-white/90 backdrop-blur-xl">
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center text-blue-600 shadow-inner">
                        <i class="fas fa-user-graduate lg:text-lg"></i>
                    </div>
                    <div>
                        <div class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">{{ app()->isLocale('ar') ? 'تسجيل جديد' : 'New Enrollment' }}</div>
                        <div class="text-xs md:text-sm font-black text-slate-900">+24%</div>
                    </div>
                </div>

                <!-- 3D Browser Mockup Frame -->
                <div class="hero-3d-wrapper perspective-2000 relative z-20">
                    <div class="hero-3d-card rounded-2xl md:rounded-[1.5rem] overflow-hidden border border-slate-200 shadow-[0_30px_100px_-15px_rgba(0,0,0,0.15)] bg-white ring-1 ring-slate-900/5 hero-3d-side">
                        <!-- MacOS style browser header -->
                        <div class="flex items-center gap-2 px-4 py-3 bg-slate-50/80 backdrop-blur-md border-b border-slate-100/80">
                            <div class="flex gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-400/80 shadow-[inset_0_1px_2px_rgba(0,0,0,0.1)]"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400/80 shadow-[inset_0_1px_2px_rgba(0,0,0,0.1)]"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-400/80 shadow-[inset_0_1px_2px_rgba(0,0,0,0.1)]"></div>
                            </div>
                            <div class="flex-1 mx-4">
                                <div class="bg-white/70 border border-slate-200/60 shadow-sm rounded-lg px-4 py-1 text-[10px] text-slate-500 font-mono text-center flex items-center justify-center gap-2 max-w-[200px] mx-auto">
                                    <i class="fas fa-lock text-[8px] text-slate-400"></i>
                                    app.taalimu.com
                                </div>
                            </div>
                        </div>
                        <!-- Image Container -->
                        <div class="relative bg-slate-100 overflow-hidden group">
                            <img src="{{ asset('images/hero-dashboard.png') }}" alt="Taalimu Dashboard" class="w-full h-auto object-cover object-top border-b border-white transition-transform duration-1000 group-hover:scale-[1.01]">
                            
                            <!-- Overlay gradient base for realism -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/5 to-transparent pointer-events-none"></div>
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
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Base animations (mobile is always bottom-up) */
[data-animate="fade-text"], [data-animate="fade-image"] {
    animation: heroFadeUpMobile 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Desktop animations */
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
    filter: drop-shadow(0 20px 40px rgba(0,0,0,0.08));
    /* Dynamic tilt based on direction */
    transform: {{ app()->isLocale('ar') ? 'rotateY(12deg) rotateX(4deg)' : 'rotateY(-12deg) rotateX(4deg)' }} scale(0.98);
    transform-origin: center;
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: transform, filter;
}

.hero-3d-wrapper:hover .hero-3d-side {
    transform: rotateY(0deg) rotateX(0deg) scale(1.02);
    filter: drop-shadow(0 40px 80px rgba(16, 185, 129, 0.15));
}

.glass-card-premium {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}
</style>
