<section class="relative pt-32 md:pt-36 lg:pt-40 pb-16 lg:pb-24 overflow-hidden bg-white" id="hero">

    <div dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
        class="container relative mx-auto px-4 lg:px-8 z-10 max-w-7xl">
        <div class="grid grid-cols-12 gap-6 lg:gap-10 items-center">

            <!-- Content Side -->
            <div class="col-span-12 md:col-span-6 text-center md:text-start order-2 md:order-1 mt-12 md:mt-0"
                data-animate="fade-text">
                <!-- Premium Pill Badge -->
                <div
                    class="inline-flex items-center gap-2 lg:gap-3 px-3 py-1.5 lg:px-4 lg:py-2 rounded-full bg-white border border-slate-200 shadow-sm mb-6 lg:mb-10 transition-transform hover:scale-105 cursor-pointer hover:shadow-md hover:border-emerald-200">
                    <span class="relative flex h-2 w-2 lg:h-2.5 lg:w-2.5">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 lg:h-2.5 lg:w-2.5 bg-emerald-500"></span>
                    </span>
                    <span
                        class="text-[9px] lg:text-xs font-bold text-slate-700 uppercase tracking-widest leading-none">{{ __('landing.hero.badge') }}</span>
                </div>

                <!-- Massive Headline -->
                <h1
                    class="text-2xl md:text-4xl lg:text-[3.5rem] font-extrabold text-slate-900 leading-[1.2] lg:leading-[1.1] mb-5 lg:mb-7 tracking-tight">
                    {!! __('landing.hero.title', ['highlight' => '<span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">' . __('landing.hero.title_highlight', [], app()->getLocale()) . '</span>']) !!}
                </h1>

                <!-- Refined Subtitle -->
                <p
                    class="text-[13px] md:text-xl text-slate-600 mb-8 lg:mb-12 max-w-xl mx-auto md:mx-0 font-medium leading-relaxed">
                    {{ __('landing.hero.subtitle') }}
                </p>

                <!-- CTAs -->
                <div
                    class="flex flex-col sm:flex-row gap-3 lg:gap-4 items-center justify-center md:justify-start mb-10 lg:mb-16 mx-auto md:mx-0">
                    <a href="{{ route('register') }}"
                        class="group relative inline-flex items-center justify-center bg-slate-900 text-white px-8 py-4 lg:px-12 lg:py-5 rounded-xl font-extrabold text-lg lg:text-xl transition-all hover:bg-slate-800 shadow-xl lg:shadow-2xl ring-4 ring-slate-900/10 hover:shadow-slate-900/30 hover:-translate-y-1.5 overflow-hidden w-full sm:w-auto">
                        <span
                            class="absolute inset-0 w-full h-full -mt-1 rounded-xl opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                        <span class="relative flex items-center gap-3 lg:gap-4">
                            {{ __('landing.hero.cta_primary') }}
                            <i
                                class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-left group-hover:-translate-x-2' : 'fa-arrow-right group-hover:translate-x-2' }} text-lg lg:text-xl opacity-70 transition-transform duration-300"></i>
                        </span>
                    </a>
                </div>


            </div>

            <!-- Image Side -->
            <div class="col-span-12 md:col-span-6 relative w-full md:w-[95%] xl:w-[90%] md:ms-0 me-auto md:-mt-8 xl:-mt-16 order-1 md:order-2"
                data-animate="fade-image">

                <!-- Floating WhatsApp Card -->
                <div class="hidden sm:block absolute top-6 -left-2 lg:top-10 lg:-left-6 z-30 animate-float-slow w-56 lg:w-72"
                    data-animate="fade-up">
                    <div
                        class="bg-white/95 backdrop-blur-xl border border-emerald-100 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-emerald-500/10">
                        <div class="bg-emerald-500 px-3 py-2 lg:px-4 lg:py-2.5 flex items-center justify-between">
                            <div class="flex items-center gap-1.5 lg:gap-2">
                                <i class="fab fa-whatsapp text-white text-[11px] lg:text-sm"></i>
                                <span
                                    class="text-white text-[10px] lg:text-[11px] font-bold uppercase tracking-wider">{{ __('landing.hero.mockup.whatsapp.title') }}</span>
                            </div>
                            <span
                                class="text-white/80 text-[9px] lg:text-[10px]">{{ __('landing.hero.mockup.whatsapp.now') }}</span>
                        </div>
                        <div class="p-3 lg:p-5">
                            <div class="flex items-start gap-2 lg:gap-3 mb-3 lg:mb-4">
                                <div
                                    class="w-7 h-7 lg:w-9 lg:h-9 rounded-full bg-emerald-50 flex-shrink-0 flex items-center justify-center">
                                    <i class="fas fa-user text-emerald-600 text-[11px] lg:text-xs"></i>
                                </div>
                                <p class="text-[11px] lg:text-[14px] text-slate-700 leading-snug font-medium">
                                    {{ __('landing.hero.mockup.whatsapp.message') }}
                                </p>
                            </div>
                            <div
                                class="bg-emerald-50 rounded-lg py-2 lg:py-2.5 px-4 text-center border border-emerald-100 shadow-sm transition-transform hover:scale-105 cursor-pointer">
                                <span
                                    class="text-emerald-700 font-bold text-[10px] lg:text-[13px]">{{ __('landing.hero.mockup.whatsapp.cta') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Floating Indicators (Fast Login) -->
                <div
                    class="hidden sm:flex absolute -bottom-6 -right-2 lg:-bottom-8 lg:-right-8 glass-card-premium p-3 lg:p-5 rounded-2xl shadow-xl z-30 animate-float-fast items-center gap-3 lg:gap-4 border border-white/60 bg-white/95 backdrop-blur-xl">
                    <div
                        class="w-9 h-9 lg:w-14 lg:h-14 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 lg:w-7 lg:h-7" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 7V5a2 2 0 0 1 2-2h2" />
                            <path d="M17 3h2a2 2 0 0 1 2 2v2" />
                            <path d="M21 17v2a2 2 0 0 1-2 2h-2" />
                            <path d="M7 21H5a2 2 0 0 1-2-2v-2" />
                            <rect x="7" y="7" width="4" height="4" />
                            <rect x="13" y="7" width="4" height="4" />
                            <rect x="7" y="13" width="4" height="4" />
                            <path d="M14 14h2v2h-2z" />
                            <path d="M2 12h20" stroke="currentColor" class="text-emerald-400 animate-pulse"
                                stroke-width="2" />
                        </svg>
                    </div>
                    <div>
                        <div
                            class="text-[9px] lg:text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">
                            {{ __('landing.hero.mockup.success.label') }}</div>
                        <div class="text-sm lg:text-xl font-black text-slate-900 leading-none">
                            {{ __('landing.hero.mockup.success.amount') }}</div>
                    </div>
                </div>

                <!-- 3D Browser Mockup Frame -->
                <div class="hero-3d-wrapper perspective-2000 relative z-20">
                    <div
                        class="hero-3d-card rounded-2xl lg:rounded-[2rem] overflow-hidden border border-slate-200 shadow-2xl lg:shadow-[0_40px_120px_-20px_rgba(0,0,0,0.18)] bg-white ring-1 ring-slate-900/5 hero-3d-side">
                        <!-- MacOS style browser header -->
                        <div
                            class="flex items-center gap-2 px-3 lg:px-6 py-2.5 lg:py-4 bg-slate-50/80 backdrop-blur-md border-b border-slate-100/80">
                            <div class="flex gap-1.5 lg:gap-2">
                                <div class="w-2.5 h-2.5 lg:w-3.5 lg:h-3.5 rounded-full bg-red-400/80"></div>
                                <div class="w-2.5 h-2.5 lg:w-3.5 lg:h-3.5 rounded-full bg-yellow-400/80"></div>
                                <div class="w-2.5 h-2.5 lg:w-3.5 lg:h-3.5 rounded-full bg-emerald-400/80"></div>
                            </div>
                            <div class="flex-1 mx-2 lg:mx-4">
                                <div
                                    class="bg-white/70 border border-slate-200/60 rounded-xl px-2 lg:px-6 py-1 text-[8px] lg:text-[12px] text-slate-500 font-mono text-center truncate max-w-[120px] lg:max-w-[280px] mx-auto">
                                    app.taalimu.com
                                </div>
                            </div>
                        </div>
                        <!-- Image Container -->
                        <div class="relative bg-slate-100 overflow-hidden">
                            <img src="{{ asset('images/hero-dashboard.webp') }}" alt="Taalimu Dashboard"
                                class="w-full h-auto object-cover object-top border-b border-white">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>