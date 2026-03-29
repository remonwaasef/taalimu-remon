<!-- Light CTA Section -->
<section class="py-24 bg-white relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-100 to-transparent"></div>
    <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[600px] h-[400px] bg-emerald-500/5 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-12">
        <div class="max-w-4xl mx-auto">
            <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-3xl p-12 lg:p-16 text-center shadow-2xl shadow-emerald-900/20 relative overflow-hidden" data-animate="scale">
                <!-- Glow -->
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-[100px]"></div>
                <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-emerald-400/10 rounded-full blur-[100px]"></div>
                
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-6 tracking-tight leading-tight">
                        {{ __('landing.cta.title') }}
                    </h2>

                    <p class="text-lg text-emerald-50/80 mb-10 max-w-2xl mx-auto font-medium">
                        {{ __('landing.cta.subtitle') }}
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-10">
                        <a href="{{ route('register') }}?account_type=center" 
                           class="bg-white text-emerald-700 hover:bg-emerald-50 px-8 py-4 rounded-xl font-bold text-base shadow-xl transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                            <i class="fas fa-building opacity-70"></i>
                            {{ app()->isLocale('ar') ? 'ابدأ كمركز تعليمي' : __('landing.hero.cta_primary') }}
                        </a>
                        <a href="{{ route('register') }}?account_type=instructor" 
                           class="bg-emerald-500/20 hover:bg-emerald-500/30 text-white border border-white/20 px-8 py-4 rounded-xl font-bold text-base transition-all backdrop-blur-sm flex items-center justify-center gap-2">
                            <i class="fas fa-user-tie opacity-70"></i>
                            {{ app()->isLocale('ar') ? 'ابدأ كمدرس مستقل' : __('landing.hero.cta_secondary') }}
                        </a>
                    </div>

                    <!-- Trust -->
                    <p class="text-xs font-semibold text-emerald-100 flex items-center justify-center gap-2 uppercase tracking-wider">
                        <i class="fas fa-shield-check text-white"></i>
                        {{ __('landing.cta.trust_note') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
