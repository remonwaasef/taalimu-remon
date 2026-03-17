<section class="bg-white py-16 lg:py-24 relative overflow-hidden">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="relative bg-primary/5 rounded-[40px] p-8 lg:p-16 text-center overflow-hidden border border-primary/10">
            <!-- Background decorations -->
            <!-- Background decorations - Simplified -->
            <div class="absolute top-0 left-0 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
            
            <!-- Grid pattern overlay -->
            <div class="absolute inset-0 bg-hero-pattern opacity-10"></div>

            <div class="relative">
                <!-- Badge - Simplified -->
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/5 border border-primary/10 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-primary"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275Z"/></svg>
                    <span class="text-sm font-medium text-primary">{{ __('landing.cta.badge') }}</span>
                </div>

                <!-- Headline -->
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-4 max-w-3xl mx-auto leading-tight">
                    {{ __('landing.cta.title') }}
                </h2>

                <!-- Subheadline -->
                <p class="text-lg lg:text-xl text-muted-foreground/80 mb-8 max-w-2xl mx-auto">
                    {{ __('landing.cta.subtitle') }}
                </p>

                <!-- Stats - Simplified -->
                <div class="flex flex-wrap justify-center gap-8 lg:gap-16 mb-10">
                    <div class="text-center">
                        <div class="text-3xl lg:text-4xl font-bold text-primary">{{ __('landing.cta.stats.revenue_value') }}</div>
                        <div class="text-sm text-muted-foreground">{{ __('landing.cta.stats.revenue') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl lg:text-4xl font-bold text-primary">{{ __('landing.cta.stats.time_value') }}</div>
                        <div class="text-sm text-muted-foreground">{{ __('landing.cta.stats.time') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl lg:text-4xl font-bold text-primary">{{ __('landing.cta.stats.trial_value') }}</div>
                        <div class="text-sm text-muted-foreground">{{ __('landing.cta.stats.trial') }}</div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}?account_type=center" class="inline-flex items-center justify-center rounded-full text-lg font-bold transition-all bg-secondary text-white shadow-lg hover:bg-secondary/90 hover:scale-105 hover:-translate-y-1 h-16 px-10 group whitespace-nowrap">
                        <i class="fas fa-university me-3"></i>
                        {{ app()->isLocale('ar') ? 'ابدأ كمركز تعليمي' : 'Start as Center' }}
                    </a>
                    <a href="{{ route('register') }}?account_type=instructor" class="inline-flex items-center justify-center rounded-full text-lg font-bold transition-all bg-white text-primary shadow-lg border border-primary/10 hover:scale-105 hover:-translate-y-1 h-16 px-10 group whitespace-nowrap">
                        <i class="fas fa-chalkboard-teacher me-3"></i>
                        {{ app()->isLocale('ar') ? 'ابدأ كمدرس مستقل' : 'Start as Teacher' }}
                    </a>
                    <button class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border bg-transparent shadow-sm h-14 px-8 text-lg border-primary/10 text-primary hover:bg-primary/5 hover:text-primary">
                        {{ __('landing.cta.cta_secondary') }}
                    </button>
                </div>

                <!-- Trust note -->
                <p class="text-sm text-primary-foreground/60 mt-6">
                    {{ __('landing.cta.trust_note') }}
                </p>
            </div>
        </div>
    </div>
</section>
