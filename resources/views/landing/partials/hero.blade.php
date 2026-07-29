<section class="relative pt-32 md:pt-36 lg:pt-40 pb-16 lg:pb-28 overflow-hidden bg-gradient-to-b from-slate-50 via-white to-emerald-50/20" id="hero">
    <!-- Ambient Background Glow & Gradients -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[600px] pointer-events-none overflow-hidden -z-10">
        <div class="absolute -top-40 left-1/4 w-96 h-96 bg-emerald-400/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute top-20 right-1/4 w-[450px] h-[450px] bg-teal-300/15 rounded-full blur-[140px]"></div>
    </div>

    <div dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
        class="container relative mx-auto px-4 lg:px-8 z-10 max-w-7xl">
        <div class="grid grid-cols-12 gap-6 lg:gap-12 items-center">

            <!-- Content Side -->
            <div class="col-span-12 md:col-span-6 text-center md:text-start order-2 md:order-1 mt-8 md:mt-0"
                data-animate="fade-text">
                <!-- Premium Pill Badge -->
                <div
                    class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/90 backdrop-blur-md border border-emerald-200/80 shadow-sm hover:shadow-md hover:border-emerald-400 transition-all duration-300 cursor-pointer mb-6 lg:mb-8">
                    <span class="relative flex h-2.5 w-2.5">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span
                        class="text-xs font-extrabold text-slate-800 uppercase tracking-widest leading-none">{{ __('landing.hero.badge') }}</span>
                    <i class="fas fa-sparkles text-amber-400 text-xs ms-1"></i>
                </div>

                <!-- Massive Headline -->
                <h1
                    class="text-3xl sm:text-4xl md:text-5xl lg:text-[3.75rem] font-black text-slate-900 leading-[1.15] lg:leading-[1.1] mb-6 tracking-tight">
                    {!! __('landing.hero.title', ['highlight' => '<span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 via-teal-500 to-emerald-500">' . __('landing.hero.title_highlight', [], app()->getLocale()) . '</span>']) !!}
                </h1>

                <!-- Refined Subtitle -->
                <p
                    class="text-base sm:text-lg md:text-xl text-slate-600 mb-8 lg:mb-10 max-w-xl mx-auto md:mx-0 font-medium leading-relaxed">
                    {{ __('landing.hero.subtitle') }}
                </p>

                <!-- CTAs -->
                <div
                    class="flex flex-col sm:flex-row gap-4 items-center justify-center md:justify-start mb-10 lg:mb-14 mx-auto md:mx-0">
                    <a href="{{ route('register') }}"
                        class="group relative inline-flex items-center justify-center bg-gradient-to-r from-slate-900 to-slate-800 hover:from-emerald-600 hover:to-teal-600 text-white px-8 py-4 lg:px-10 lg:py-4.5 rounded-2xl font-black text-base lg:text-lg transition-all duration-300 shadow-xl shadow-slate-900/15 hover:shadow-emerald-500/25 hover:-translate-y-1 overflow-hidden w-full sm:w-auto">
                        <span class="relative flex items-center justify-center gap-3">
                            <span>{{ __('landing.hero.cta_primary') }}</span>
                            <i
                                class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-left group-hover:-translate-x-1.5' : 'fa-arrow-right group-hover:translate-x-1.5' }} text-base transition-transform duration-300"></i>
                        </span>
                    </a>

                    <a href="#features"
                        class="inline-flex items-center justify-center text-slate-700 hover:text-emerald-600 bg-white hover:bg-slate-50 border border-slate-200/90 px-7 py-4 lg:px-8 lg:py-4.5 rounded-2xl font-bold text-base transition-all duration-200 hover:border-slate-300 w-full sm:w-auto shadow-sm">
                        <i class="fas fa-play-circle text-emerald-500 text-lg me-2.5"></i>
                        <span>{{ __('landing.nav.features') }}</span>
                    </a>
                </div>

                <!-- Trust Badges / Social Proof Stats -->
                <div class="pt-6 border-t border-slate-200/60 flex items-center justify-center md:justify-start gap-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 font-bold">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">سريع وعملي</div>
                            <div class="text-sm font-black text-slate-900">إعداد خلال دقيقتين</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 font-bold">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">تنبيهات فورية</div>
                            <div class="text-sm font-black text-slate-900">ربط مع الواتساب</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Image Side -->
            <div class="col-span-12 md:col-span-6 relative w-full md:w-[95%] xl:w-[90%] md:ms-0 me-auto order-1 md:order-2"
                data-animate="fade-image">

                <!-- Floating WhatsApp Card -->
                <div class="hidden sm:block absolute top-6 -left-4 lg:top-8 lg:-left-8 z-30 w-60 lg:w-72 transition-all duration-300 hover:scale-105"
                    data-animate="fade-up">
                    <div
                        class="bg-white/95 backdrop-blur-xl border border-emerald-200/80 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-emerald-500/10">
                        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-4 py-2.5 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fab fa-whatsapp text-white text-sm"></i>
                                <span
                                    class="text-white text-[11px] font-extrabold uppercase tracking-wider">{{ __('landing.hero.mockup.whatsapp.title') }}</span>
                            </div>
                            <span
                                class="text-white/90 text-[10px] font-medium">{{ __('landing.hero.mockup.whatsapp.now') }}</span>
                        </div>
                        <div class="p-4">
                            <div class="flex items-start gap-3 mb-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-emerald-100 flex-shrink-0 flex items-center justify-center">
                                    <i class="fas fa-user-check text-emerald-700 text-xs"></i>
                                </div>
                                <p class="text-xs lg:text-sm text-slate-700 leading-snug font-medium">
                                    {{ __('landing.hero.mockup.whatsapp.message') }}
                                </p>
                            </div>
                            <div
                                class="bg-emerald-50/80 rounded-xl py-2 px-3 text-center border border-emerald-100 shadow-sm">
                                <span
                                    class="text-emerald-700 font-extrabold text-xs lg:text-sm">{{ __('landing.hero.mockup.whatsapp.cta') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Floating Indicators (Attendance Success) -->
                <div
                    class="hidden sm:flex absolute -bottom-6 -right-2 lg:-bottom-6 lg:-right-6 glass-card-premium p-4 rounded-2xl shadow-2xl z-30 items-center gap-4 border border-emerald-100 bg-white/95 backdrop-blur-xl hover:scale-105 transition-transform">
                    <div
                        class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
                        <i class="fas fa-qrcode text-xl"></i>
                    </div>
                    <div>
                        <div
                            class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">
                            {{ __('landing.hero.mockup.success.label') }}</div>
                        <div class="text-base lg:text-lg font-black text-slate-900 leading-none">
                            {{ __('landing.hero.mockup.success.amount') }}</div>
                    </div>
                </div>

                <!-- 3D Browser Mockup Frame -->
                <div class="relative z-20">
                    <div
                        class="rounded-2xl lg:rounded-[2.25rem] overflow-hidden border border-slate-200/80 shadow-2xl shadow-slate-900/10 bg-white ring-1 ring-slate-900/5 transition-all duration-500 hover:shadow-emerald-500/10">
                        <!-- MacOS style browser header -->
                        <div
                            class="flex items-center gap-2 px-4 py-3 bg-slate-100/90 backdrop-blur-md border-b border-slate-200/80">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-rose-400"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                            </div>
                            <div class="flex-1 mx-4">
                                <div
                                    class="bg-white border border-slate-200/80 rounded-xl px-4 py-1 text-xs text-slate-600 font-mono text-center truncate max-w-[240px] mx-auto shadow-inner flex items-center justify-center gap-2">
                                    <i class="fas fa-lock text-[10px] text-emerald-500"></i>
                                    <span>app.taalimu.com</span>
                                </div>
                            </div>
                        </div>
                        <!-- Image Container -->
                        <div class="relative bg-slate-100 overflow-hidden">
                            <img src="{{ asset('images/hero-dashboard.webp') }}" alt="Taalimu Dashboard"
                                class="w-full h-auto object-cover object-top border-b border-slate-100">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>