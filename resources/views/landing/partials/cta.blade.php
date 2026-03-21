<section class="bg-white py-8 lg:py-12 relative overflow-hidden">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="relative bg-primary rounded-2xl p-5 lg:p-8 text-center overflow-hidden border border-white/10 shadow-2xl">
            <!-- Background decorations - Enhanced for dark background -->
            <div class="absolute top-0 left-0 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-secondary/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/20 mb-6 backdrop-blur-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
                    </span>
                    <span class="text-xs font-bold text-white tracking-wider uppercase">{{ __('landing.cta.badge') }}</span>
                </div>

                <!-- Headline -->
                <h2 class="text-xl md:text-2xl lg:text-3xl font-bold text-white mb-2 max-w-3xl mx-auto leading-tight">
                    {{ __('landing.cta.title') }}
                </h2>

                <!-- Subheadline -->
                <p class="text-sm lg:text-base text-white/70 mb-4 max-w-2xl mx-auto">
                    {{ __('landing.cta.subtitle') }}
                </p>

                <!-- Stats - Enhanced for dark background -->
                <div class="flex flex-wrap justify-center gap-4 lg:gap-8 mb-6">
                    <div class="text-center">
                        <div class="text-xl lg:text-2xl font-bold text-white">{{ __('landing.cta.stats.revenue_value') }}</div>
                        <div class="text-[10px] text-white/50 uppercase tracking-wider">{{ __('landing.cta.stats.revenue') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl lg:text-2xl font-bold text-white">{{ __('landing.cta.stats.time_value') }}</div>
                        <div class="text-[10px] text-white/50 uppercase tracking-wider">{{ __('landing.cta.stats.time') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl lg:text-2xl font-bold text-white">{{ __('landing.cta.stats.trial_value') }}</div>
                        <div class="text-[10px] text-white/50 uppercase tracking-wider">{{ __('landing.cta.stats.trial') }}</div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('register') }}?account_type=center" class="inline-flex items-center justify-center rounded-full text-sm font-bold transition-all bg-secondary text-white shadow-xl hover:bg-secondary/90 hover:scale-105 hover:-translate-y-1 h-10 px-6 group whitespace-nowrap">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        {{ app()->isLocale('ar') ? 'ابدأ كمركز تعليمي' : 'Start as Center' }}
                    </a>
                    <a href="{{ route('register') }}?account_type=instructor" class="inline-flex items-center justify-center rounded-full text-sm font-bold transition-all bg-white text-primary shadow-xl hover:scale-105 hover:-translate-y-1 h-10 px-6 group whitespace-nowrap">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        {{ app()->isLocale('ar') ? 'ابدأ كمدرس مستقل' : 'Start as Teacher' }}
                    </a>
                </div>

                <!-- Trust Note -->
                <p class="mt-6 text-xs text-white/40 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                    {{ __('landing.cta.trust_note') }}
                </p>
            </div>
        </div>
    </div>
</section>
