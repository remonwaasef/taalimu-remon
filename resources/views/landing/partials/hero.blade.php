<section class="hero-section relative min-h-[92vh] pt-24 lg:pt-32 pb-16 overflow-hidden bg-white z-0" id="hero">
    <!-- Background Decorative Elements -->
    <div class="hero-bg-gradient absolute inset-0 -z-10"></div>
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-gradient-to-bl from-green-50 to-transparent rounded-full blur-[120px] -z-10 opacity-70"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-gradient-to-tr from-emerald-50/40 to-transparent rounded-full blur-[100px] -z-10"></div>

    <!-- Animated Geometric Shapes -->
    <div class="absolute top-20 left-[8%] w-3 h-3 bg-[#22c55e] rounded-full opacity-40 animate-float-shape" style="animation-delay: 0s;"></div>
    <div class="absolute top-[30%] right-[5%] w-4 h-4 bg-amber-300 rounded-full opacity-30 animate-float-shape" style="animation-delay: 1.5s;"></div>
    <div class="absolute bottom-[25%] left-[12%] w-2.5 h-2.5 bg-blue-400 rounded-sm rotate-45 opacity-30 animate-float-shape" style="animation-delay: 3s;"></div>
    <div class="absolute top-[60%] right-[15%] w-2 h-2 bg-[#22c55e] rounded-full opacity-25 animate-float-shape" style="animation-delay: 2s;"></div>

    <!-- Dotted Pattern -->
    <div class="absolute top-16 left-6 w-28 h-28 opacity-[0.06] -z-10" style="background-image: radial-gradient(circle, #1e293b 1.2px, transparent 1.2px); background-size: 14px 14px;"></div>
    <div class="absolute bottom-24 right-12 w-24 h-24 opacity-[0.05] -z-10" style="background-image: radial-gradient(circle, #1e293b 1.2px, transparent 1.2px); background-size: 14px 14px;"></div>

    <div
        dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
        class="container relative mx-auto px-4 lg:px-12"
    >
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-10 items-center justify-between">
            <!-- Left Content -->
            <div class="w-full lg:w-[48%] text-center lg:text-start">
                <!-- Guarantee Badge -->
                <div class="hero-badge inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200/50 mb-8 shadow-sm opacity-0" style="animation: heroFadeInUp 0.6s ease-out 0.1s forwards;">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#22c55e] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#22c55e]"></span>
                    </span>
                    <span class="text-sm font-bold text-slate-700 tracking-tight">{{ __('landing.hero.badge') }}</span>
                </div>

                <!-- Headline -->
                <h1
                    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
                    class="font-cairo text-3xl md:text-4xl lg:text-[2.75rem] xl:text-[3.25rem] font-black text-slate-900 leading-[1.12] mb-6 tracking-tight opacity-0"
                    style="animation: heroFadeInUp 0.7s ease-out 0.2s forwards;"
                >
                    {!! __('landing.hero.title') !!}
                </h1>

                <!-- Subheadline -->
                <p
                    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
                    class="text-base md:text-lg text-slate-500 mb-10 max-w-xl mx-auto lg:mx-0 font-medium leading-[1.75] opacity-0"
                    style="animation: heroFadeInUp 0.7s ease-out 0.35s forwards;"
                >
                    {{ __('landing.hero.subtitle') }}
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-10 opacity-0" style="animation: heroFadeInUp 0.7s ease-out 0.5s forwards;">
                    <a href="{{ route('register') }}" class="hero-cta-primary inline-flex items-center justify-center rounded-full text-base font-bold h-14 px-9 py-4 group transition-all duration-300 bg-[#22c55e] text-white shadow-lg shadow-green-500/25 hover:bg-[#16a34a] hover:-translate-y-1 hover:shadow-xl hover:shadow-green-500/35 relative overflow-hidden">
                        <span class="relative z-10 flex items-center">
                            {{ __('landing.hero.cta_primary') }}
                            <svg class="w-4.5 h-4.5 ms-2 rtl:rotate-180 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </span>
                        <span class="hero-cta-glow"></span>
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center justify-center rounded-full text-base font-bold h-14 px-9 py-4 group transition-all duration-300 bg-white border-2 border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:-translate-y-1 hover:shadow-lg">
                        <span class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center me-2.5 text-[#22c55e] group-hover:bg-green-100 transition-colors">
                            <i class="fas fa-play text-[10px] ms-0.5"></i>
                        </span>
                        {{ __('landing.hero.cta_secondary') }}
                    </a>
                </div>

                <!-- Social Proof Bar -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-6 opacity-0" style="animation: heroFadeInUp 0.7s ease-out 0.65s forwards;">
                    <!-- Star Rating -->
                    <div class="flex items-center gap-2.5">
                        <div class="flex items-center gap-0.5">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4.5 h-4.5 text-amber-400 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <span class="text-sm font-bold text-slate-700">(4.7)</span>
                    </div>

                    <div class="hidden sm:block w-px h-5 bg-slate-200"></div>

                    <!-- User Avatars + Count -->
                    <div class="flex items-center gap-3" x-data="{ count: 0 }" x-init="
                        let target = 500;
                        let step = Math.ceil(target / 40);
                        let interval = setInterval(() => {
                            count += step;
                            if(count >= target) { count = target; clearInterval(interval); }
                        }, 50);
                    ">
                        <div class="flex -space-x-2.5 rtl:space-x-reverse">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-300 to-green-500 border-2 border-white flex items-center justify-center text-[10px] font-bold text-white shadow-sm">A</div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-300 to-blue-500 border-2 border-white flex items-center justify-center text-[10px] font-bold text-white shadow-sm">S</div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-300 to-amber-500 border-2 border-white flex items-center justify-center text-[10px] font-bold text-white shadow-sm">M</div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-rose-300 to-rose-500 border-2 border-white flex items-center justify-center text-[10px] font-bold text-white shadow-sm">F</div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-extrabold text-slate-800">+<span x-text="count">0</span>+</span>
                            <span class="text-[11px] text-slate-400 font-medium -mt-0.5">{{ __('landing.hero.stats.collection') }}</span>
                        </div>
                    </div>

                    <div class="hidden sm:block w-px h-5 bg-slate-200"></div>

                    <!-- Trial Note -->
                    <span class="text-xs font-medium text-slate-400">{{ __('landing.hero.trial_note') }}</span>
                </div>
            </div>

            <!-- Right Content - Dashboard Showcase -->
            <div class="w-full lg:w-[52%] relative flex items-center justify-center opacity-0" style="animation: heroFadeInRight 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.4s forwards;">

                <!-- Main Dashboard Image with Perspective -->
                <div class="hero-dashboard-wrapper relative">
                    <div class="hero-dashboard-frame relative rounded-2xl overflow-hidden shadow-2xl border border-slate-200/60">
                        <!-- Browser Chrome Bar -->
                        <div class="bg-slate-100 border-b border-slate-200/80 px-4 py-2.5 flex items-center gap-2">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-400/80"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-400/80"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400/80"></div>
                            </div>
                            <div class="flex-1 mx-8">
                                <div class="bg-white rounded-lg px-4 py-1.5 text-[11px] text-slate-400 font-medium text-center border border-slate-200/50 flex items-center justify-center gap-1.5">
                                    <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                                    app.taalimu.com/dashboard
                                </div>
                            </div>
                        </div>

                        <!-- Dashboard Content -->
                        <div class="hero-dashboard-content bg-[#1a1f36] p-4 sm:p-6">
                            <!-- Stats Row -->
                            <div class="grid grid-cols-3 gap-3 mb-4">
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/5">
                                    <div class="text-white/50 text-[10px] font-medium mb-1">{{ __('landing.features.items.0.title') }}</div>
                                    <div class="text-white text-lg font-black">450</div>
                                    <div class="flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                        <span class="text-green-400 text-[10px] font-bold">+12%</span>
                                    </div>
                                </div>
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/5">
                                    <div class="text-white/50 text-[10px] font-medium mb-1">{{ __('landing.features.items.1.title') }}</div>
                                    <div class="text-white text-lg font-black">94%</div>
                                    <div class="flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                        <span class="text-green-400 text-[10px] font-bold">+5%</span>
                                    </div>
                                </div>
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/5">
                                    <div class="text-white/50 text-[10px] font-medium mb-1">{{ __('landing.features.items.2.title') }}</div>
                                    <div class="text-white text-lg font-black">98%</div>
                                    <div class="flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                        <span class="text-green-400 text-[10px] font-bold">+3%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Chart Area -->
                            <div class="bg-white/5 rounded-xl p-4 border border-white/5 mb-4">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-white/70 text-xs font-semibold">{{ __('landing.hero.stats.revenue') }}</span>
                                    <span class="text-green-400 text-xs font-bold">+38%</span>
                                </div>
                                <!-- SVG Chart -->
                                <svg class="w-full h-16" viewBox="0 0 300 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#22c55e" stop-opacity="0.3"/>
                                            <stop offset="100%" stop-color="#22c55e" stop-opacity="0"/>
                                        </linearGradient>
                                    </defs>
                                    <path d="M0,50 C20,45 40,40 60,35 C80,30 100,38 120,28 C140,18 160,22 180,15 C200,8 220,12 240,8 C260,4 280,6 300,2" stroke="#22c55e" stroke-width="2.5" fill="none" class="hero-chart-line"/>
                                    <path d="M0,50 C20,45 40,40 60,35 C80,30 100,38 120,28 C140,18 160,22 180,15 C200,8 220,12 240,8 C260,4 280,6 300,2 V60 H0 Z" fill="url(#chartGradient)" class="hero-chart-fill"/>
                                </svg>
                            </div>

                            <!-- Student Row -->
                            <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center text-[11px] font-bold text-white">أ</div>
                                        <div>
                                            <div class="text-white text-xs font-bold">{{ app()->getLocale() == 'ar' ? 'أحمد محمد' : 'Ahmed Mohamed' }}</div>
                                            <div class="text-white/40 text-[10px]">{{ app()->getLocale() == 'ar' ? 'الصف العاشر' : 'Grade 10' }}</div>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full bg-green-500/20 text-green-400 text-[10px] font-bold">{{ app()->getLocale() == 'ar' ? 'نشط' : 'Active' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating WhatsApp Notification Card -->
                    <div class="hero-float-card absolute -bottom-4 -left-4 sm:-bottom-6 sm:-left-8 bg-white rounded-2xl shadow-2xl shadow-slate-900/10 p-4 z-30 border border-slate-100 max-w-[220px] opacity-0" style="animation: heroSlideInLeft 0.7s cubic-bezier(0.16, 1, 0.3, 1) 1s forwards;">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-green-500/20">
                                <i class="fab fa-whatsapp text-white text-lg"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[11px] font-extrabold text-slate-800 truncate">{{ __('landing.hero.mockup.whatsapp.title') }}</div>
                                <div class="text-[10px] text-green-600 font-semibold">{{ __('landing.hero.mockup.whatsapp.payment_success') }}</div>
                            </div>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-[9px] text-slate-400">{{ __('landing.hero.mockup.whatsapp.just_now') }}</span>
                            <span class="text-[9px] text-green-500 font-bold">✓✓</span>
                        </div>
                    </div>

                    <!-- Floating Attendance Card - Top Right -->
                    <div class="hero-float-card absolute -top-4 -right-2 sm:-top-6 sm:-right-6 bg-white rounded-2xl shadow-2xl shadow-slate-900/10 p-3.5 z-30 border border-slate-100 opacity-0" style="animation: heroSlideInRight 0.7s cubic-bezier(0.16, 1, 0.3, 1) 1.2s forwards;">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('landing.features.items.1.title') }}</div>
                                <div class="text-xl font-black text-slate-800">95%</div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Revenue Card - Bottom Right -->
                    <div class="hero-float-card absolute bottom-12 -right-2 sm:bottom-16 sm:-right-8 bg-white rounded-2xl shadow-2xl shadow-slate-900/10 p-3.5 z-30 border border-slate-100 opacity-0" style="animation: heroSlideInRight 0.7s cubic-bezier(0.16, 1, 0.3, 1) 1.4s forwards;">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('landing.hero.stats.revenue') }}</div>
                                <div class="text-xl font-black text-green-600">+38%</div>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Glow Behind Dashboard -->
                    <div class="absolute inset-0 bg-gradient-to-br from-green-400/10 to-emerald-400/5 rounded-3xl blur-3xl -z-10 scale-110"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Wave Separator -->
    <div class="absolute bottom-0 left-0 right-0 -z-10">
        <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
            <path d="M0,60 C360,20 720,90 1080,40 C1260,15 1380,25 1440,30 L1440,100 L0,100 Z" fill="#f8fafc" fill-opacity="0.5"/>
        </svg>
    </div>
</section>
