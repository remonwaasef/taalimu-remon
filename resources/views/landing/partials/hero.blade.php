<section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden bg-gradient-to-b from-slate-50 via-white to-slate-50">
    <!-- Subtle Background Glows -->
    <div class="absolute -top-40 start-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-gradient-to-tr from-emerald-100/40 via-teal-100/30 to-transparent blur-3xl pointer-events-none rounded-full"></div>
    <div class="absolute top-1/3 end-0 w-[400px] h-[400px] bg-teal-500/5 blur-3xl pointer-events-none rounded-full"></div>

    <div class="container mx-auto px-4 lg:px-8 max-w-7xl relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            
            <!-- Hero Content Column -->
            <div class="lg:col-span-6 text-center lg:text-start" data-animate="fade-in">
                <!-- Eyebrow Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/80 text-[#2E8B83] text-xs font-bold mb-6 shadow-sm">
                    <span class="flex h-2 w-2 rounded-full bg-[#2E8B83] animate-pulse"></span>
                    <span>{{ __('landing.hero.eyebrow') }}</span>
                </div>

                <!-- Main Headline -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 leading-[1.2] tracking-tight mb-6">
                    {{ __('landing.hero.headline') }}
                </h1>

                <!-- Subheadline -->
                <p class="text-base sm:text-lg text-slate-600 font-medium leading-relaxed mb-8 max-w-2xl mx-auto lg:mx-0">
                    {{ __('landing.hero.subheadline') }}
                </p>

                <!-- Call-to-Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 mb-8">
                    <a href="{{ route('register') }}" data-track="landing_hero_cta_clicked" class="w-full sm:w-auto px-8 py-4 rounded-xl text-white font-extrabold text-base text-decoration-none shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 text-center flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);">
                        <span>{{ __('landing.hero.cta_primary') }}</span>
                        <i class="fas fa-arrow-left text-sm rtl:rotate-0 ltr:rotate-180"></i>
                    </a>

                    <a href="#how-it-works" class="w-full sm:w-auto px-7 py-4 rounded-xl border border-slate-300 bg-white text-slate-800 font-bold text-base hover:bg-slate-50 transition-all text-decoration-none text-center flex items-center justify-center gap-2 shadow-sm">
                        <i class="fas fa-play-circle text-[#2E8B83] text-lg"></i>
                        <span>{{ __('landing.hero.cta_secondary') }}</span>
                    </a>
                </div>

                <!-- Trust Badges Checkmarks -->
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-y-2 gap-x-6 text-xs font-semibold text-slate-500">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-500"></i>
                        <span>{{ __('landing.hero.check_nocard') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-500"></i>
                        <span>{{ __('landing.hero.check_setup') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-500"></i>
                        <span>{{ __('landing.hero.check_trial') }}</span>
                    </div>
                </div>
            </div>

            <!-- Hero Visual Column (Real UI + Micro Animation) -->
            <div class="lg:col-span-6" data-animate="scale-in">
                <div class="relative mx-auto max-w-lg lg:max-w-none">
                    
                    <!-- Main Platform Container Frame -->
                    <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-200/80 overflow-hidden p-2 sm:p-4">
                        <!-- Top Window Controls -->
                        <div class="flex items-center justify-between pb-3 px-2 border-b border-slate-100">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-red-400"></span>
                                <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                                <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                            </div>
                            <div class="flex items-center gap-2 bg-slate-100 px-3 py-1 rounded-md text-[11px] font-mono text-slate-500">
                                <i class="fas fa-lock text-emerald-600"></i>
                                <span>app.taalimu.com/center/dashboard</span>
                            </div>
                            <span class="text-xs text-slate-400"><i class="fas fa-signal"></i> Live</span>
                        </div>

                        <!-- Real Dashboard Preview Content -->
                        <div class="pt-3">
                            <img src="{{ asset('images/hero-dashboard.webp') }}" alt="Taalimu Dashboard Preview" class="w-full h-auto rounded-xl border border-slate-100 shadow-sm object-cover">
                        </div>

                        <!-- Floating Live QR Card Widget -->
                        <div class="absolute top-12 start-4 sm:start-8 bg-white/95 backdrop-blur-md rounded-xl p-3.5 shadow-xl border border-slate-100 max-w-[210px] hidden sm:block transform -rotate-1 hover:rotate-0 transition-transform">
                            <div class="flex items-center gap-2.5 mb-2">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 border border-teal-100 flex items-center justify-center text-[#2E8B83]">
                                    <i class="fas fa-qrcode"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 leading-none">{{ __('landing.hero.card_qr_title') }}</h4>
                                    <span class="text-[10px] text-slate-500">ID: ST-9042</span>
                                </div>
                            </div>
                            <!-- Simulated Scanner Animation Box -->
                            <div class="relative bg-slate-900 p-2 rounded-lg text-center overflow-hidden">
                                <div class="w-16 h-16 mx-auto bg-white rounded p-1 flex items-center justify-center relative">
                                    <i class="fas fa-qrcode text-3xl text-slate-900"></i>
                                    <!-- Scan Line Animation -->
                                    <div class="absolute inset-x-0 h-0.5 bg-emerald-500 shadow-[0_0_8px_#10b981] animate-pulse top-1/2"></div>
                                </div>
                                <span class="text-[10px] text-emerald-400 font-mono mt-1.5 block">Scanner Active</span>
                            </div>
                        </div>

                        <!-- Floating Live WhatsApp Badge Widget -->
                        <div class="absolute bottom-6 end-4 sm:end-8 bg-white/95 backdrop-blur-md rounded-xl p-3.5 shadow-xl border border-emerald-100 max-w-[260px] transform rotate-1 hover:rotate-0 transition-transform">
                            <div class="flex items-center gap-2.5 mb-1.5">
                                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-sm shadow-md">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-bold text-slate-900 truncate">{{ __('landing.hero.card_whatsapp_title') }}</h4>
                                        <span class="text-[9px] text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.5 rounded">{{ __('landing.hero.card_now') }}</span>
                                    </div>
                                    <p class="text-[10px] text-slate-500 truncate">{{ __('landing.hero.card_whatsapp_sub') }}</p>
                                </div>
                            </div>
                            <div class="bg-emerald-50/80 rounded-lg p-2 border border-emerald-100 text-[10px] text-slate-700 font-medium">
                                <i class="fas fa-check-double text-emerald-600 me-1"></i>
                                <span>{{ __('landing.hero.card_whatsapp_msg') }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>