<section class="relative pt-28 lg:pt-36 pb-0 overflow-hidden bg-white" id="hero">
    <!-- Gradient Glow -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-gradient-to-b from-emerald-500/5 via-cyan-500/5 to-transparent rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute top-20 right-0 w-[300px] h-[300px] bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="container relative mx-auto px-4 lg:px-12 z-10">
        <div class="grid lg:grid-cols-2 gap-16 lg:items-center">
            <!-- Left Side: Content -->
            <div class="{{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} order-2 lg:order-1" data-animate>
                <!-- Badge -->
                <div class="inline-flex items-center gap-2.5 px-5 py-2 rounded-full bg-slate-50 border border-slate-200 mb-8 backdrop-blur-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-[11px] font-bold text-slate-600 uppercase tracking-widest">{{ __('landing.hero.badge') }}</span>
                </div>

                <!-- Headline -->
                <h1 class="text-4xl md:text-5xl lg:text-[3.5rem] font-black text-slate-900 leading-[1.1] mb-6 tracking-tight">
                    {!! __('landing.hero.title', ['highlight' => '<span class="text-emerald-500">' . __('landing.hero.title_highlight', [], app()->getLocale()) . '</span>']) !!}
                </h1>

                <!-- Subtitle -->
                <p class="text-lg text-slate-600 mb-10 max-w-xl {{ app()->isLocale('ar') ? 'mr-0' : 'ml-0' }} font-medium leading-relaxed">
                    {{ __('landing.hero.subtitle') }}
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-12">
                    <a href="{{ route('register') }}" class="group bg-emerald-500 hover:bg-emerald-400 text-white px-10 py-4 rounded-xl font-bold text-lg shadow-xl shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all hover:scale-[1.02]">
                        <span class="flex items-center gap-2 justify-center">
                            {{ __('landing.hero.cta_primary') }}
                            <i class="fas fa-arrow-right text-sm opacity-70 group-hover:translate-x-1 transition-transform rtl:rotate-180"></i>
                        </span>
                    </a>
                    <a href="#features" class="bg-slate-50 hover:bg-slate-100 text-slate-900 border border-slate-200 px-10 py-4 rounded-xl font-bold text-lg transition-all">
                        {{ __('landing.hero.cta_secondary') }}
                    </a>
                </div>

                <!-- Mini Stats / Trust -->
                <div class="flex items-center gap-6 py-6 border-t border-slate-100">
                    <div>
                        <div class="text-2xl font-black text-slate-900">500+</div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('landing.hero.trust_centers') }}</div>
                    </div>
                    <div class="w-px h-10 bg-slate-100"></div>
                    <div>
                        <div class="text-2xl font-black text-slate-900">10k+</div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __('landing.hero.trust_students') }}</div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Professional Mockup -->
            <div class="relative order-1 lg:order-2" data-animate="scale">
                <!-- Floating Card 1 -->
                <div class="absolute -top-6 -right-6 md:-right-12 glass-card-premium p-4 rounded-2xl shadow-xl z-30 animate-float-slow hidden md:flex items-center gap-4 border border-white/50">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase">{{ app()->isLocale('ar') ? 'طالب جديد' : 'New Enrollment' }}</div>
                        <div class="text-sm font-black text-slate-900">+24.5%</div>
                    </div>
                </div>

                <!-- Floating Card 2 -->
                <div class="absolute -bottom-6 -left-6 md:-left-12 glass-card-premium p-4 rounded-2xl shadow-xl z-30 animate-float-fast hidden md:flex items-center gap-4 border border-white/50">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase">{{ app()->isLocale('ar') ? 'دفعة ناجحة' : 'Recent Payment' }}</div>
                        <div class="text-sm font-black text-slate-900">$1,250.00</div>
                    </div>
                </div>

                <!-- Main Mockup Frame -->
                <div class="perspective-2000">
                    <div class="hero-3d-card rounded-2xl overflow-hidden border border-slate-200 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.15)] bg-white">
                        <!-- Browser Header -->
                        <div class="flex items-center gap-2 px-4 py-3 bg-slate-50 border-b border-slate-100">
                            <div class="flex gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-400/60"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400/60"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-400/60"></div>
                            </div>
                            <div class="flex-1 mx-4">
                                <div class="bg-white border border-slate-100 rounded-lg px-4 py-1 text-[10px] text-slate-400 font-mono text-center">
                                    app.taalimu.com
                                </div>
                            </div>
                        </div>
                        <div class="relative bg-slate-100">
                            <img src="{{ asset('images/hero-dashboard.png') }}" alt="Taalimu Dashboard" class="w-full h-auto brightness-[1.02] contrast-[1.02]">
                        </div>
                    </div>
                </div>

                <!-- Glow effect background -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-emerald-500/5 blur-[100px] -z-10 rounded-full"></div>
            </div>
        </div>
    </div>

    </div>
</section>

<style>
@keyframes heroFadeUp {
    from { opacity: 0; transform: translateY(30px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.hero-perspective {
    perspective: 2000px;
}

.hero-3d-card {
    transform: {{ app()->isLocale('ar') ? 'rotateY(12deg) rotateX(5deg)' : 'rotateY(-12deg) rotateX(5deg)' }} scale(0.98);
    transform-origin: center;
    transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.hero-3d-card:hover {
    transform: rotateY(0) rotateX(0) scale(1.02);
}

.glass-card-premium {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1);
}
</style>
