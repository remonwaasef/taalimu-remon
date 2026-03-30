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

                    <!-- CTA Button -->
                    <div class="flex justify-center mb-10">
                        <a href="{{ route('register') }}" 
                           class="group bg-white text-emerald-700 hover:bg-emerald-50 px-12 py-6 lg:px-16 lg:py-7 rounded-2xl font-black text-xl lg:text-2xl shadow-2xl transition-all hover:scale-[1.03] hover:-translate-y-1 flex items-center justify-center gap-3 lg:gap-4 ring-4 ring-white/20">
                            {{ __('landing.cta.cta_primary') }}
                            <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-left group-hover:-translate-x-2' : 'fa-arrow-right group-hover:translate-x-2' }} text-lg lg:text-2xl opacity-70 transition-transform duration-300"></i>
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
