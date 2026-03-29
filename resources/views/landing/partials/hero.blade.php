<section class="relative pt-32 lg:pt-48 pb-20 overflow-hidden bg-slate-50" id="hero">
    <!-- Subtle Grid Background -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9InJnYmEoMTQ4LCAxNjMsIDE4NCwgMC4xNSkiLz48L3N2Zz4=')] [mask-image:linear-gradient(to_bottom,white,transparent_80%)] z-0"></div>

    <!-- Ambient Glows -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-gradient-to-b from-emerald-500/10 via-teal-400/5 to-transparent rounded-full blur-[100px] pointer-events-none z-0"></div>

    <div dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="container relative mx-auto px-4 lg:px-8 z-10 max-w-7xl">
        <div class="text-center max-w-4xl mx-auto mb-16" data-animate>
            <!-- Premium Pill Badge -->
            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white border border-slate-200 shadow-sm mb-8 transition-transform hover:scale-105 cursor-pointer hover:shadow-md hover:border-emerald-200">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-widest">{{ __('landing.hero.badge') }}</span>
            </div>

            <!-- Massive Central Headline -->
            <h1 class="text-5xl md:text-6xl lg:text-[4.5rem] font-black text-slate-900 leading-[1.15] mb-8 tracking-tight">
                {!! __('landing.hero.title', ['highlight' => '<span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">' . __('landing.hero.title_highlight', [], app()->getLocale()) . '</span>']) !!}
            </h1>

            <!-- Refined Subtitle -->
            <p class="text-xl md:text-2xl text-slate-600 mb-10 max-w-2xl mx-auto font-medium leading-relaxed">
                {{ __('landing.hero.subtitle') }}
            </p>

            <!-- Centered CTAs -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
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
            <div class="flex items-center justify-center gap-8 md:gap-16 pt-6 opacity-90">
                <div class="text-center">
                    <div class="text-3xl font-black text-slate-900 mb-1">+500</div>
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ app()->isLocale('ar') ? 'مركز تعليمي' : 'Learning Centers' }}</div>
                </div>
                <div class="w-px h-12 bg-slate-200"></div>
                <div class="text-center">
                    <div class="text-3xl font-black text-slate-900 mb-1">+10k</div>
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ app()->isLocale('ar') ? 'طالب نشط' : 'Active Students' }}</div>
                </div>
                <div class="w-px h-12 bg-slate-200 hidden md:block"></div>
                <div class="text-center hidden md:block">
                    <div class="text-3xl font-black text-slate-900 mb-1">99.9%</div>
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ app()->isLocale('ar') ? 'نسبة الاستقرار' : 'Uptime' }}</div>
                </div>
            </div>
        </div>

        <!-- The Magnificent Centralized Mockup -->
        <div class="relative max-w-5xl mx-auto mt-20" data-animate="fade-up">
            <!-- Floating Elements Over Mockup -->
            <div class="absolute -top-8 -left-4 md:-left-12 glass-card-premium p-4 md:px-6 md:py-4 rounded-2xl shadow-2xl z-30 animate-float-slow hidden md:flex items-center gap-4 border border-white/60 bg-white/90 backdrop-blur-xl">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">{{ app()->isLocale('ar') ? 'تحصيل ناجح' : 'Payment Success' }}</div>
                    <div class="text-base font-black text-slate-900">1,250 SAR</div>
                </div>
            </div>

            <div class="absolute -bottom-8 -right-4 md:-right-12 glass-card-premium p-4 md:px-6 md:py-4 rounded-2xl shadow-2xl z-30 animate-float-fast hidden md:flex items-center gap-4 border border-white/60 bg-white/90 backdrop-blur-xl">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center text-blue-600 shadow-inner">
                    <i class="fas fa-user-graduate text-xl"></i>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">{{ app()->isLocale('ar') ? 'تسجيل جديد' : 'New Enrollment' }}</div>
                    <div class="text-base font-black text-slate-900">+24% <span class="text-[10px] text-slate-400 font-medium ml-1/2">{{ app()->isLocale('ar') ? 'هذا الأسبوع' : 'This Week' }}</span></div>
                </div>
            </div>

            <!-- 3D Browser Mockup Frame -->
            <div class="hero-3d-wrapper perspective-2000 relative z-20">
                <div class="hero-3d-card rounded-2xl md:rounded-[2rem] overflow-hidden border border-slate-200 shadow-[0_30px_100px_-15px_rgba(0,0,0,0.15)] bg-white ring-1 ring-slate-900/5">
                    <!-- MacOS style browser header -->
                    <div class="flex items-center gap-2 px-4 py-3 md:py-4 bg-slate-50/80 backdrop-blur-md border-b border-slate-100/80">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-400/80 shadow-[inset_0_1px_2px_rgba(0,0,0,0.1)]"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400/80 shadow-[inset_0_1px_2px_rgba(0,0,0,0.1)]"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-400/80 shadow-[inset_0_1px_2px_rgba(0,0,0,0.1)]"></div>
                        </div>
                        <div class="flex-1 mx-4">
                            <div class="bg-white/70 border border-slate-200/60 shadow-sm rounded-lg px-4 py-1.5 text-[11px] text-slate-500 font-mono text-center flex items-center justify-center gap-2 max-w-sm mx-auto">
                                <i class="fas fa-lock text-[10px] text-slate-400"></i>
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

            <!-- Intense Mockup Glow Background Base -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[110%] h-[90%] bg-gradient-to-tr from-emerald-500/20 via-teal-500/10 to-blue-500/20 blur-[100px] -z-10 rounded-[3rem] opacity-70"></div>
        </div>
    </div>
</section>

<style>
@keyframes heroFadeUp {
    from { opacity: 0; transform: translateY(40px) scale(0.96); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

[data-animate="fade-up"] {
    animation: heroFadeUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

[data-animate] {
    animation: heroFadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.perspective-2000 {
    perspective: 2000px;
}

.hero-3d-wrapper {
    transform-style: preserve-3d;
}

.hero-3d-card {
    filter: drop-shadow(0 20px 40px rgba(0,0,0,0.08));
    transform: rotateX(6deg) scale(0.98) translateY(10px);
    transform-origin: bottom center;
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: transform, filter;
}

.hero-3d-wrapper:hover .hero-3d-card {
    transform: rotateX(0deg) scale(1) translateY(0);
    filter: drop-shadow(0 40px 80px rgba(16, 185, 129, 0.15));
}

.glass-card-premium {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}
</style>
