<section class="relative min-h-[70vh] pt-16 lg:pt-24 overflow-hidden bg-white">
    <!-- Sophisticated Background Gradient -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-from),_transparent_50%)] from-emerald-50/40 to-transparent"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,_var(--tw-gradient-from),_transparent_40%)] from-blue-50/30 to-transparent"></div>
    
    <div 
        dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
        class="container relative mx-auto px-4 lg:px-12 py-8 lg:py-12"
    >
        <div class="flex flex-col lg:flex-row gap-16 lg:gap-20 items-start justify-between">
            <!-- Left Content -->
            <div class="w-full lg:w-[48%] text-center lg:text-start">
                <!-- Premium Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500 text-white mb-8 animate-fade-in shadow-sm relative z-20">
                    <span class="text-xs md:text-sm font-bold tracking-tight">{{ __('landing.hero.badge') }}</span>
                </div>

                <!-- Headline -->
                <h1 
                    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" 
                    class="font-cairo text-3xl md:text-4xl lg:text-5xl xl:text-5xl font-black text-slate-900 leading-[1.15] mb-6 animate-fade-in tracking-tight delay-1"
                >
                    {!! __('landing.hero.title') !!}
                </h1>

                <!-- Subheadline -->
                <p 
                    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
                    class="text-lg md:text-xl text-slate-500 mb-8 max-w-xl mx-auto lg:mx-0 animate-fade-in font-medium leading-[1.6] delay-2"
                >
                    {{ __('landing.hero.subtitle') }}
                </p>

                <!-- Testimonial Box (NEW) -->
                <div class="mb-10 animate-fade-in delay-3">
                    <div class="inline-flex flex-col md:flex-row items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 shadow-sm max-w-lg">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-white flex items-center justify-center text-amber-400 border border-slate-100 shadow-sm text-xl">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="text-start">
                            <p class="text-sm md:text-base font-bold text-slate-800 leading-snug mb-1">
                                "{{ __('landing.hero.testimonial.quote') }}"
                            </p>
                            <p class="text-xs md:text-sm font-medium text-slate-500">
                                — {{ __('landing.hero.testimonial.author') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Premium CTA Buttons -->
                <div 
                    class="flex flex-col sm:flex-row gap-5 justify-center lg:justify-start mb-6 animate-fade-in delay-4"
                >
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl text-lg font-black h-14 px-10 group transition-all bg-emerald-500 text-white shadow-xl shadow-emerald-500/20 hover:bg-emerald-600 hover:-translate-y-1">
                        {{ __('landing.hero.cta_primary') }}
                        <i class="fas fa-arrow-left ms-2 rtl:rotate-0 ltr:rotate-180 transition-transform group-hover:-translate-x-1"></i>
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center justify-center rounded-2xl text-lg font-black h-14 px-10 group transition-all bg-white border-2 border-slate-100 text-slate-800 hover:bg-slate-50 hover:border-slate-200">
                        <span class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center me-3 text-slate-600 group-hover:bg-slate-200 transition-colors">
                            <i class="fas fa-play text-xs"></i>
                        </span>
                        {{ __('landing.hero.cta_secondary') }}
                    </a>
                </div>

                <!-- Guarantee / Info (NEW) -->
                <div class="flex items-center justify-center lg:justify-start gap-3 text-slate-500 font-bold text-sm animate-fade-in delay-5">
                    <div class="w-5 h-5 rounded-full border-2 border-emerald-500 flex items-center justify-center">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    </div>
                    <span>{{ __('landing.hero.trial_note') }}</span>
                </div>
            </div>

            <!-- Right Content - Hybrid Mockup with Reversed 3D Slant -->
            <div class="w-full lg:w-[50%] relative flex items-start justify-center lg:justify-end pt-4 lg:pt-0 pb-12 lg:pb-16">
                <div 
                    class="relative z-10 w-full max-w-[580px] animate-fade-in-right transform-gpu backface-hidden lg:-mt-16 xl:-mt-24 delay-2"
                    style="perspective: 2000px;"
                >
                    <!-- Floating Stat Cards: Collection Rate (Inward) -->
                    <div 
                        class="absolute top-4 left-6 md:top-8 md:left-12 bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] p-4 z-50 border border-slate-50 text-center animate-bounce-slow transform-gpu"
                        style="transform: translateZ(80px) rotateY(10deg);"
                    >
                        <div class="text-xl md:text-3xl font-black text-emerald-500 mb-0.5">98%</div>
                        <div class="text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('landing.hero.stats.collection') }}</div>
                    </div>

                    <!-- WhatsApp Payment Notification (Extra Compact) -->
                    <div 
                        class="absolute bottom-6 left-12 md:bottom-10 md:left-20 z-50 animate-fade-in-up delay-3 transform-gpu"
                        style="transform: translateZ(80px) rotateY(10deg);"
                    >
                        <div class="flex items-center gap-2.5 p-2 md:p-3 bg-white rounded-2xl shadow-[0_20px_40px_rgba(0,0,0,0.1)] border border-emerald-50/50 min-w-[200px] md:min-w-[280px]">
                            <div class="relative flex-shrink-0">
                                <div class="w-7 h-7 md:w-9 md:h-9 rounded-full bg-[#25D366] flex items-center justify-center text-white text-base md:text-xl shadow-md shadow-[#25D366]/20">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <div class="absolute -top-0.5 -right-0.5 w-3 h-3 md:w-4 md:h-4 bg-white rounded-full flex items-center justify-center shadow-sm">
                                    <i class="fas fa-check-circle text-emerald-500 text-[5px] md:text-[8px]"></i>
                                </div>
                            </div>
                            <div class="flex-1 text-start overflow-hidden">
                                <div class="flex flex-col gap-0">
                                    <p class="text-[10px] md:text-[13px] font-black text-slate-900 leading-tight whitespace-normal">
                                        {{ __('landing.hero.mockup.whatsapp.payment_success') }}
                                    </p>
                                    <p class="text-[7px] md:text-[9px] font-bold text-slate-400 flex items-center gap-1 uppercase tracking-wide">
                                        <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                                        {{ __('landing.hero.mockup.whatsapp.now') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Browser Frame with Reversed 3D Tilt -->
                    <div 
                        class="relative group p-1 transition-all duration-700 transform-gpu"
                        style="transform: rotateY(18deg) rotateX(8deg) rotateZ(-2deg); transform-style: preserve-3d;"
                    >
                        <!-- Glow behind the frame -->
                        <div class="absolute inset-0 bg-emerald-500/10 blur-[100px] -z-10 rounded-full scale-110"></div>
                        
                        <div class="relative bg-white/40 backdrop-blur-xl border border-white/60 rounded-[3rem] p-2 md:p-4 shadow-[0_50px_100px_rgba(0,0,0,0.08)] overflow-hidden">
                            <!-- Browser Header -->
                            <div class="flex items-center justify-between px-6 pb-4 md:pb-6 border-b border-slate-200/50 mb-2">
                                <div class="flex gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-rose-400/80"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-amber-400/80"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-400/80"></div>
                                </div>
                                <div class="h-6 px-12 rounded-full bg-slate-100 border border-slate-200/50 flex items-center justify-center">
                                    <div class="w-2 h-2 rounded-full bg-slate-300"></div>
                                </div>
                                <div class="w-10"></div>
                            </div>

                            <div class="w-full bg-white rounded-2xl md:rounded-3xl border border-slate-200/30 shadow-inner overflow-hidden flex h-[350px] md:h-[480px] select-none" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                                <!-- Sidebar (Sleek Dark) -->
                                <div class="w-12 md:w-48 bg-[#0F172A] flex flex-col p-2 md:p-4 gap-4 flex-shrink-0">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-6 h-6 rounded-lg bg-emerald-500 flex items-center justify-center">
                                            <i class="fas fa-graduation-cap text-white text-[10px]"></i>
                                        </div>
                                        <div class="hidden md:block text-[10px] font-black text-white uppercase tracking-tighter">{{ config('app.name') }}</div>
                                    </div>
                                    @for($i=0; $i<6; $i++)
                                        <div class="w-full h-8 md:h-10 flex items-center gap-3 md:px-3 rounded-xl transition-colors {{ $i === 0 ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'text-slate-500 hover:bg-slate-800' }}">
                                            <div class="w-5 h-5 flex-shrink-0 flex items-center justify-center">
                                                <i class="fas fa-{{ ['chart-pie', 'users', 'calendar-alt', 'wallet', 'envelope', 'cog'][$i] }} {{ $i === 0 ? '' : 'opacity-60' }} text-xs md:text-sm"></i>
                                            </div>
                                            <div class="hidden md:block h-1.5 bg-current opacity-20 rounded-full w-20"></div>
                                        </div>
                                    @endfor
                                </div>

                                <!-- Main Content Area -->
                                <div class="flex-1 bg-[#F8FAFC] p-3 md:p-6 overflow-hidden flex flex-col gap-4">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between">
                                        <div class="h-5 px-3 bg-white rounded-full border border-slate-200 flex items-center gap-2 shadow-sm">
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                            <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ __('landing.hero.mockup.dashboard') }}</div>
                                        </div>
                                        <div class="flex gap-2">
                                            <div class="w-7 h-7 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center">
                                                <i class="fas fa-search text-[10px] text-slate-400"></i>
                                            </div>
                                            <div class="w-7 h-7 rounded-full bg-white border border-slate-200 shadow-sm overflow-hidden">
                                                <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                                                    <i class="fas fa-user-circle text-slate-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stat Cards -->
                                    <div class="grid grid-cols-3 gap-2 md:gap-4">
                                        <div class="bg-blue-500 rounded-2xl p-3 md:p-4 text-white shadow-lg shadow-blue-500/10">
                                            <div class="text-[7px] md:text-[9px] opacity-70 font-bold mb-1 uppercase tracking-wider">{{ __('landing.hero.mockup.attendance') }}</div>
                                            <div class="text-xs md:text-xl font-black mb-1">94%</div>
                                            <div class="flex items-center gap-1">
                                                <i class="fas fa-arrow-up text-[6px]"></i>
                                                <span class="text-[6px] md:text-[8px] font-black">3.1%</span>
                                            </div>
                                        </div>
                                        <div class="bg-slate-900 rounded-2xl p-3 md:p-4 text-white shadow-lg shadow-slate-900/10">
                                            <div class="text-[7px] md:text-[9px] opacity-70 font-bold mb-1 uppercase tracking-wider">{{ __('landing.hero.mockup.students') }}</div>
                                            <div class="text-xs md:text-xl font-black mb-1">1,850</div>
                                            <div class="flex items-center gap-1 text-emerald-400">
                                                <i class="fas fa-arrow-up text-[6px]"></i>
                                                <span class="text-[6px] md:text-[8px] font-black">12%</span>
                                            </div>
                                        </div>
                                        <div class="bg-emerald-500 rounded-2xl p-3 md:p-4 text-white shadow-lg shadow-emerald-500/10">
                                            <div class="text-[7px] md:text-[9px] opacity-70 font-bold mb-1 uppercase tracking-wider">{{ __('landing.hero.mockup.revenue') }}</div>
                                            <div class="text-xs md:text-xl font-black mb-1">154,200</div>
                                            <div class="flex items-center gap-1">
                                                <i class="fas fa-arrow-up text-[6px]"></i>
                                                <span class="text-[6px] md:text-[8px] font-black">6.5%</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Chart Area -->
                                    <div class="flex-1 bg-white rounded-2xl p-4 border border-slate-200/60 shadow-sm flex flex-col gap-4 overflow-hidden relative group/chart">
                                        <div class="flex items-center justify-between">
                                            <div class="flex flex-col gap-0.5">
                                                <div class="text-[10px] font-black text-slate-800">{{ __('landing.hero.mockup.growth_analysis') }}</div>
                                                <div class="text-[7px] font-bold text-slate-400 uppercase tracking-tighter">{{ __('landing.hero.mockup.jan_dec') }}</div>
                                            </div>
                                            <div class="flex gap-1">
                                                @foreach(['1M', '3M', '6M', '1Y'] as $period)
                                                    <div class="px-2 py-0.5 rounded-md text-[6px] font-black {{ $loop->last ? 'bg-slate-100 text-slate-600' : 'text-slate-300' }}">{{ $period }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        <!-- Mock Graph SVG -->
                                        <div class="relative flex-1 bg-emerald-50/20 rounded-xl overflow-hidden mt-2 p-2">
                                            <svg class="w-full h-full" viewBox="0 0 100 40" preserveAspectRatio="none">
                                                <defs>
                                                    <linearGradient id="chartGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                                        <stop offset="0%" style="stop-color:#10b981;stop-opacity:0.2" />
                                                        <stop offset="100%" style="stop-color:#10b981;stop-opacity:0" />
                                                    </linearGradient>
                                                </defs>
                                                <path d="M0,40 C10,38 15,30 20,32 C25,34 30,20 40,22 C50,24 55,10 70,12 C85,14 90,4 100,5 L100,40 L0,40 Z" fill="url(#chartGrad)" />
                                                <path d="M0,40 C10,38 15,30 20,32 C25,34 30,20 40,22 C50,24 55,10 70,12 C85,14 90,4 100,5" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" />
                                                
                                                <!-- Points -->
                                                <circle cx="20" cy="32" r="1.5" fill="white" stroke="#10b981" stroke-width="1" />
                                                <circle cx="40" cy="22" r="1.5" fill="white" stroke="#10b981" stroke-width="1" />
                                                <circle cx="70" cy="12" r="1.5" fill="white" stroke="#10b981" stroke-width="1" />
                                                <circle cx="100" cy="5" r="2" fill="#10b981" />
                                            </svg>
                                        </div>
                                        
                                        <!-- Legend -->
                                        <div class="flex items-center gap-4 mt-2">
                                            <div class="flex items-center gap-1.5">
                                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                                <div class="text-[7px] font-bold text-slate-500">Users</div>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                                <div class="text-[7px] font-bold text-slate-500">Activity</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Background Accents -->
                    <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-emerald-400/10 rounded-full blur-[120px] -z-10"></div>
                    <div class="absolute -top-20 -left-20 w-80 h-80 bg-blue-400/10 rounded-full blur-[120px] -z-10"></div>
                </div>
            </div>
        </div>
    </div>
</section>
