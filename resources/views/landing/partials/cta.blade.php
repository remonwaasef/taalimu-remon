<section class="py-20 lg:py-28 bg-white relative overflow-hidden">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        
        <div class="rounded-3xl p-10 sm:p-16 text-white text-center relative overflow-hidden shadow-2xl" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);" data-animate="scale-in">
            <!-- Background Glow Effects -->
            <div class="absolute -top-24 start-1/2 -translate-x-1/2 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 end-10 w-96 h-96 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-3xl mx-auto">
                <div class="w-16 h-16 mx-auto rounded-3xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-400 flex items-center justify-center text-3xl mb-6 shadow-lg">
                    <i class="fas fa-[#2E8B83] fa-rocket text-[#2E8B83]"></i>
                </div>

                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white leading-tight mb-6">
                    {{ __('landing.cta.title') }}
                </h2>

                <p class="text-slate-300 text-base sm:text-lg font-medium leading-relaxed mb-8 max-w-2xl mx-auto">
                    {{ __('landing.cta.subtitle') }}
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl text-white font-extrabold text-base text-decoration-none shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-0.5 text-center flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);">
                        <span>{{ __('landing.cta.primary_btn') }}</span>
                        <i class="fas fa-arrow-left text-sm"></i>
                    </a>

                    <a href="#solutions" class="w-full sm:w-auto px-7 py-4 rounded-xl border border-slate-700 bg-slate-800/80 text-white font-bold text-base hover:bg-slate-800 transition-all text-decoration-none text-center">
                        {{ __('landing.cta.secondary_btn') }}
                    </a>
                </div>

                <p class="text-xs text-slate-400 font-medium">
                    <i class="fas fa-shield-alt text-emerald-400 me-1"></i>
                    {{ __('landing.cta.note') }}
                </p>
            </div>
        </div>

    </div>
</section>