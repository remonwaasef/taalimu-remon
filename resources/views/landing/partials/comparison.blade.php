<section id="comparison" class="py-16 lg:py-24 bg-background relative overflow-hidden">
    <!-- Background Accents -->
    <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-cyan/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-1/3 h-1/3 bg-light-purple/5 blur-[120px] rounded-full translate-y-1/2 -translate-x-1/2"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-12 lg:mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 mb-6">
                <span class="text-sm font-medium text-primary">{{ __('landing.comparison.badge') }}</span>
            </div>
            <h2 class="text-3xl lg:text-4xl font-bold text-foreground mb-4 text-center mx-auto">
                {!! __('landing.comparison.title') !!}
            </h2>
            <p class="text-muted-foreground text-lg max-w-2xl mx-auto">
                {{ __('landing.comparison.subtitle') }}
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 max-w-6xl mx-auto">
            <!-- Manual / Traditional Management -->
            <div class="group relative bg-card/50 backdrop-blur-sm rounded-[40px] p-8 lg:p-10 border border-border hover:border-destructive/30 transition-all duration-500 hover:shadow-2xl">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-destructive/10 flex items-center justify-center text-destructive">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 9-6 6"/><path d="m9 9 6 6"/><circle cx="12" cy="12" r="10"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-foreground">
                        {{ __('landing.comparison.manual.title') }}
                    </h3>
                </div>

                <ul class="space-y-6">
                    @foreach(__('landing.comparison.manual.items') as $item)
                        <li class="flex items-start gap-4 group/item">
                            <div class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-destructive/5 flex items-center justify-center text-destructive/40 group-hover/item:text-destructive transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </div>
                            <span class="text-muted-foreground leading-relaxed">
                                {{ $item }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Edu System -->
            <div class="group relative bg-muted/30 backdrop-blur-sm rounded-[40px] p-8 lg:p-10 border border-light-purple/20 hover:border-cyan/40 transition-all duration-500 hover:shadow-2xl overflow-hidden">
                <!-- Highlight Effect -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-cyan/10 blur-3xl rounded-full"></div>
                
                <div class="flex items-center gap-4 mb-8 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-cyan/10 flex items-center justify-center text-cyan shadow-[0_0_20px_rgba(0,255,255,0.2)]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-foreground">
                        {{ __('landing.comparison.edu.title') }}
                    </h3>
                </div>

                <ul class="space-y-6 relative z-10">
                    @foreach(__('landing.comparison.edu.items') as $item)
                        <li class="flex items-start gap-4 group/item">
                            <div class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-cyan/10 flex items-center justify-center text-cyan group-hover/item:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <span class="text-foreground font-medium leading-relaxed">
                                {{ $item }}
                            </span>
                        </li>
                    @endforeach
                </ul>

                <!-- CTA Subtle Trigger -->
                <div class="mt-10 pt-8 border-t border-border/10">
                    <a href="#pricing" class="inline-flex items-center gap-2 text-cyan font-bold hover:gap-3 transition-all">
                        <span>{{ __('landing.hero.cta_primary') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
