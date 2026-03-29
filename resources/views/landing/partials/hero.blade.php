<section class="relative pt-44 md:pt-40 lg:pt-52 pb-16 lg:pb-24 overflow-visible bg-white" id="hero">

    <div dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="container relative mx-auto px-4 lg:px-8 z-10 max-w-7xl">
        <div class="grid grid-cols-12 gap-6 lg:gap-12 items-center">
            
            <!-- Content Side -->
            <div class="col-span-12 lg:col-span-6 text-center lg:text-start" data-animate="fade-text">
                <!-- Premium Pill Badge -->
                <div class="inline-flex items-center gap-2 lg:gap-3 px-3 py-1.5 lg:px-4 lg:py-2 rounded-full bg-white border border-slate-200 shadow-sm mb-6 lg:mb-10 transition-transform hover:scale-105 cursor-pointer hover:shadow-md hover:border-emerald-200">
                    <span class="relative flex h-2 w-2 lg:h-2.5 lg:w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 lg:h-2.5 lg:w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-[9px] lg:text-xs font-bold text-slate-700 uppercase tracking-widest leading-none">{{ __('landing.hero.badge') }}</span>
                </div>

                <!-- Massive Headline -->
                <h1 class="text-3xl md:text-5xl lg:text-[4.2rem] font-black text-slate-900 leading-[1.2] lg:leading-[1.1] mb-6 lg:mb-8 tracking-tight">
                    {!! __('landing.hero.title', ['highlight' => '<span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">' . __('landing.hero.title_highlight', [], app()->getLocale()) . '</span>']) !!}
                </h1>

                <!-- Refined Subtitle -->
                <p class="text-[13px] md:text-xl text-slate-600 mb-8 lg:mb-12 max-w-xl mx-auto lg:mx-0 font-medium leading-relaxed">
                    {{ __('landing.hero.subtitle') }}
                </p>

                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row gap-3 lg:gap-4 items-center justify-center lg:justify-start mb-10 lg:mb-16 mx-auto lg:mx-0">
                    <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center bg-slate-900 text-white px-8 py-4 lg:px-10 lg:py-5 rounded-xl font-bold text-base lg:text-xl transition-all hover:bg-slate-800 hover:shadow-2xl hover:shadow-slate-900/20 hover:-translate-y-1 overflow-hidden w-full sm:w-auto">
                        <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                        <span class="relative flex items-center gap-2 lg:gap-3">
                            {{ __('landing.hero.cta_primary') }}
                            <i class="fas fa-arrow-right text-sm lg:text-lg opacity-70 group-hover:translate-x-1 transition-transform rtl:rotate-180"></i>
                        </span>
                    </a>
                    <a href="#features" class="group bg-white hover:bg-slate-50 text-slate-900 border border-slate-200 px-8 py-4 lg:px-10 lg:py-5 rounded-xl font-bold text-base lg:text-xl transition-all shadow-sm hover:shadow-md hover:-translate-y-1 w-full sm:w-auto">
                        <span class="flex items-center justify-center gap-2 lg:gap-3">
                            <i class="fas fa-play-circle text-emerald-500 text-xl lg:text-2xl group-hover:scale-110 transition-transform"></i>
                            {{ __('landing.hero.cta_secondary') }}
                        </span>
                    </a>
                </div>
                
                <!-- Trust Stats -->
                <div class="flex items-center justify-center lg:justify-start gap-8 lg:gap-12 pt-6 lg:pt-8 border-t border-slate-200/60 max-w-lg mx-auto lg:mx-0">
                    <div class="text-start">
                        <div class="text-2xl lg:text-3xl font-black text-slate-900">+500</div>
                        <div class="text-[9px] lg:text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ app()->isLocale('ar') ? 'مركز تعليمي' : 'Centers' }}</div>
                    </div>
                    <div class="w-px h-10 lg:h-12 bg-slate-200"></div>
                    <div class="text-start">
                        <div class="text-2xl lg:text-3xl font-black text-slate-900">+10k</div>
                        <div class="text-[9px] lg:text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ app()->isLocale('ar') ? 'طالب نشط' : 'Students' }}</div>
                    </div>
                </div>
            </div>

            <!-- Image Side -->
            <div class="col-span-12 lg:col-span-6 relative w-full lg:mt-0 mt-20" data-animate="fade-image">

                <!-- Floating WhatsApp Card -->
                <div class="absolute -top-16 -left-2 lg:-top-24 lg:-left-20 z-40 animate-float-slow w-56 lg:w-72" data-animate="fade-up">
                    <div class="bg-white/95 backdrop-blur-xl border border-emerald-100 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-emerald-500/10">
                        <div class="bg-emerald-500 px-3 py-2 lg:px-4 lg:py-2.5 flex items-center justify-between">
                            <div class="flex items-center gap-1.5 lg:gap-2">
                                <i class="fab fa-whatsapp text-white text-[11px] lg:text-sm"></i>
                                <span class="text-white text-[10px] lg:text-[11px] font-bold uppercase tracking-wider">{{ __('landing.hero.mockup.whatsapp.title') }}</span>
                            </div>
                            <span class="text-white/80 text-[9px] lg:text-[10px]">{{ __('landing.hero.mockup.whatsapp.now') }}</span>
                        </div>
                        <div class="p-3 lg:p-5">
                            <div class="flex items-start gap-2 lg:gap-3 mb-3 lg:mb-4">
                                <div class="w-7 h-7 lg:w-9 lg:h-9 rounded-full bg-emerald-50 flex-shrink-0 flex items-center justify-center">
                                    <i class="fas fa-user text-emerald-600 text-[11px] lg:text-xs"></i>
                                </div>
                                <p class="text-[11px] lg:text-[14px] text-slate-700 leading-snug font-medium">
                                    {{ __('landing.hero.mockup.whatsapp.message') }}
                                </p>
                            </div>
                            <div class="bg-emerald-50 rounded-lg py-2 lg:py-2.5 px-4 text-center border border-emerald-100 shadow-sm transition-transform hover:scale-105 cursor-pointer">
                                <span class="text-emerald-700 font-bold text-[10px] lg:text-[13px]">{{ __('landing.hero.mockup.whatsapp.cta') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Floating Indicators (Payment/Enrollment) -->
                <div class="absolute -bottom-6 -right-2 lg:-bottom-8 lg:-right-8 glass-card-premium p-3 lg:p-5 rounded-2xl shadow-xl z-30 animate-float-fast flex items-center gap-3 lg:gap-4 border border-white/60 bg-white/95 backdrop-blur-xl">
                    <div class="w-9 h-9 lg:w-14 lg:h-14 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center text-blue-600 shadow-inner">
                        <i class="fas fa-check-circle text-lg lg:text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-[9px] lg:text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">{{ __('landing.hero.mockup.success.label') }}</div>
                        <div class="text-sm lg:text-xl font-black text-slate-900 leading-none">{{ __('landing.hero.mockup.success.amount') }}+</div>
                    </div>
                </div>

                <!-- 3D Browser Mockup Frame -->
                <div class="hero-3d-wrapper perspective-2000 relative z-20">
                    <div class="hero-3d-card rounded-2xl lg:rounded-[2rem] overflow-hidden border border-slate-200 shadow-2xl lg:shadow-[0_40px_120px_-20px_rgba(0,0,0,0.18)] bg-white ring-1 ring-slate-900/5 hero-3d-side">
                        <!-- MacOS style browser header -->
                        <div class="flex items-center gap-2 px-3 lg:px-6 py-2.5 lg:py-4 bg-slate-50/80 backdrop-blur-md border-b border-slate-100/80">
                            <div class="flex gap-1.5 lg:gap-2">
                                <div class="w-2.5 h-2.5 lg:w-3.5 lg:h-3.5 rounded-full bg-red-400/80"></div>
                                <div class="w-2.5 h-2.5 lg:w-3.5 lg:h-3.5 rounded-full bg-yellow-400/80"></div>
                                <div class="w-2.5 h-2.5 lg:w-3.5 lg:h-3.5 rounded-full bg-emerald-400/80"></div>
                            </div>
                            <div class="flex-1 mx-2 lg:mx-4">
                                <div class="bg-white/70 border border-slate-200/60 rounded-xl px-2 lg:px-6 py-1 text-[8px] lg:text-[12px] text-slate-500 font-mono text-center truncate max-w-[120px] lg:max-w-[280px] mx-auto">
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
    from { opacity: 0; transform: translateY(30px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

/* Animations */
[data-animate="fade-text"], [data-animate="fade-image"] {
    animation: heroFadeUpMobile 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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
    transform: {{ app()->isLocale('ar') ? 'rotateY(8deg) rotateX(2deg)' : 'rotateY(-8deg) rotateX(2deg)' }} scale(1);
    transform-origin: center;
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

@media (min-width: 1024px) {
    .hero-3d-side {
        transform: {{ app()->isLocale('ar') ? 'rotateY(12deg) rotateX(4deg)' : 'rotateY(-12deg) rotateX(4deg)' }} scale(0.98);
    }
}

.hero-3d-wrapper:hover .hero-3d-side {
    transform: rotateY(0deg) rotateX(0deg) scale(1.02);
    filter: drop-shadow(0 30px 60px rgba(16, 185, 129, 0.15));
}

.glass-card-premium {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(15px);
}
</style>
