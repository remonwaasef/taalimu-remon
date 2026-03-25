<section class="py-24 bg-white relative overflow-hidden">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="relative animated-gradient-bg rounded-[3.5rem] p-12 lg:p-20 text-center overflow-hidden border border-white/10 shadow-premium" data-animate="scale">
            <!-- Advanced Mesh Backdrop -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#22c55e]/20 via-transparent to-[#0ea5e9]/20 opacity-40"></div>
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-[#22c55e]/10 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-[#0ea5e9]/10 rounded-full blur-[120px]"></div>
            
            <div class="relative z-10">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/5 border border-white/10 mb-10 backdrop-blur-xl">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#22c55e] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#22c55e]"></span>
                    </span>
                    <span class="text-[10px] font-black text-white tracking-[0.2em] uppercase">{{ __('landing.cta.badge') }}</span>
                </div>

                <!-- Headline -->
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-8 max-w-4xl mx-auto tracking-tighter leading-[1.1] drop-shadow-xl">
                    {{ __('landing.cta.title') }}
                </h2>

                <!-- Subheadline -->
                <p class="text-lg lg:text-xl text-slate-400 mb-12 max-w-2xl mx-auto font-medium">
                    {{ __('landing.cta.subtitle') }}
                </p>

                <!-- Premium Stats Grid -->
                <div class="flex flex-wrap justify-center gap-12 lg:gap-24 mb-16">
                    <div class="group">
                        <div class="text-4xl lg:text-5xl font-black text-white mb-2 tracking-tighter group-hover:scale-110 transition-transform">{{ __('landing.cta.stats.revenue_value') }}</div>
                        <div class="text-[11px] text-slate-500 font-bold uppercase tracking-[0.2em]">{{ __('landing.cta.stats.revenue') }}</div>
                    </div>
                    <div class="group">
                        <div class="text-4xl lg:text-5xl font-black text-white mb-2 tracking-tighter group-hover:scale-110 transition-transform">{{ __('landing.cta.stats.time_value') }}</div>
                        <div class="text-[11px] text-slate-500 font-bold uppercase tracking-[0.2em]">{{ __('landing.cta.stats.time') }}</div>
                    </div>
                    <div class="group">
                        <div class="text-4xl lg:text-5xl font-black text-white mb-2 tracking-tighter group-hover:scale-110 transition-transform">{{ __('landing.cta.stats.trial_value') }}</div>
                        <div class="text-[11px] text-slate-500 font-bold uppercase tracking-[0.2em]">{{ __('landing.cta.stats.trial') }}</div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-5 justify-center">
                    <a href="{{ route('register') }}?account_type=center" 
                       class="inline-flex items-center justify-center rounded-[1.5rem] text-sm font-black transition-all bg-[#22c55e] text-white shadow-lg shadow-green-500/20 hover:shadow-green-500/40 hover:scale-105 h-16 px-10 group whitespace-nowrap">
                        <i class="fas fa-building me-3 opacity-70"></i>
                        {{ app()->isLocale('ar') ? 'ابدأ كمركز تعليمي' : 'Start as Center' }}
                    </a>
                    <a href="{{ route('register') }}?account_type=instructor" 
                       class="inline-flex items-center justify-center rounded-[1.5rem] text-sm font-black transition-all bg-white text-[#0f172a] shadow-lg hover:shadow-white/10 hover:scale-105 h-16 px-10 group whitespace-nowrap">
                        <i class="fas fa-user-tie me-3 opacity-70"></i>
                        {{ app()->isLocale('ar') ? 'ابدأ كمدرس مستقل' : 'Start as Teacher' }}
                    </a>
                </div>

                <!-- Trust Note -->
                <p class="mt-12 text-xs font-bold text-slate-500 flex items-center justify-center gap-3 uppercase tracking-widest">
                    <i class="fas fa-shield-check text-[#22c55e]"></i>
                    {{ __('landing.cta.trust_note') }}
                </p>
            </div>
        </div>
    </div>
</section>
