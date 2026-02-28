<section class="py-16 lg:py-24 bg-muted/30">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12 lg:mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-foreground mb-4">
                {{ __('landing.pain_points.title_prefix') }} <span class="gradient-text">{{ __('landing.pain_points.title_highlight') }}</span>{{ __('landing.pain_points.title_suffix') }}
            </h2>
            <p class="text-muted-foreground text-lg max-w-2xl mx-auto">
                {{ __('landing.pain_points.subtitle') }}
            </p>
        </div>

        <!-- Pain Points Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            <!-- Revenue Lost -->
            <div
                class="group relative bg-card rounded-2xl p-8 border border-border hover:border-light-purple/30 transition-all duration-300 hover:shadow-card-hover animate-fade-in"
                style="animation-delay: 0s;"
            >
                <!-- Icon -->
                <div class="w-16 h-16 rounded-2xl bg-primary/5 border-primary/10 border flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-primary"><polyline points="22 17 13.5 8.5 8.5 13.5 2 7"/><polyline points="16 17 22 17 22 11"/></svg>
                </div>

                <!-- Stat -->
                <div class="text-5xl lg:text-6xl font-bold text-primary mb-2">
                    35%
                </div>

                <!-- Title -->
                <h3 class="text-xl font-semibold text-foreground mb-2">
                    {{ __('landing.pain_points.revenue_lost.title') }}
                </h3>

                <!-- Description -->
                <p class="text-muted-foreground">
                    {{ __('landing.pain_points.revenue_lost.description') }}
                </p>

                <!-- Decorative line -->
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary/10 rounded-b-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>

            <!-- Time Wasted -->
            <div
                class="group relative bg-card rounded-2xl p-8 border border-border hover:border-light-purple/30 transition-all duration-300 hover:shadow-card-hover animate-fade-in"
                style="animation-delay: 0.1s;"
            >
                <!-- Icon -->
                <div class="w-16 h-16 rounded-2xl bg-primary/5 border-primary/10 border flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-primary"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>

                <!-- Stat -->
                <div class="text-5xl lg:text-6xl font-bold text-primary mb-2">
                    12h
                </div>

                <!-- Title -->
                <h3 class="text-xl font-semibold text-foreground mb-2">
                    {{ __('landing.pain_points.time_wasted.title') }}
                </h3>

                <!-- Description -->
                <p class="text-muted-foreground">
                    {{ __('landing.pain_points.time_wasted.description') }}
                </p>

                <!-- Decorative line -->
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary/10 rounded-b-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>

            <!-- Visibility -->
            <div
                class="group relative bg-card rounded-2xl p-8 border border-border hover:border-light-purple/30 transition-all duration-300 hover:shadow-card-hover animate-fade-in"
                style="animation-delay: 0.2s;"
            >
                <!-- Icon -->
                <div class="w-16 h-16 rounded-2xl bg-primary/5 border-primary/10 border flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-primary"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>

                <!-- Stat -->
                <div class="text-5xl lg:text-6xl font-bold text-primary mb-2">
                    0
                </div>

                <!-- Title -->
                <h3 class="text-xl font-semibold text-foreground mb-2">
                    {{ __('landing.pain_points.visibility.title') }}
                </h3>

                <!-- Description -->
                <p class="text-muted-foreground">
                    {{ __('landing.pain_points.visibility.description') }}
                </p>

                <!-- Decorative line -->
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary/10 rounded-b-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>

            <!-- Scheduling Chaos -->
            <div
                class="group relative bg-card rounded-2xl p-8 border border-border hover:border-light-purple/30 transition-all duration-300 hover:shadow-card-hover animate-fade-in"
                style="animation-delay: 0.3s;"
            >
                <!-- Icon -->
                <div class="w-16 h-16 rounded-2xl bg-primary/5 border-primary/10 border flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-primary"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 3-3 3 3"/><path d="m9 13 3 3 3-3"/></svg>
                </div>

                <!-- Stat -->
                <div class="text-5xl lg:text-6xl font-bold text-primary mb-2">
                    25+
                </div>

                <!-- Title -->
                <h3 class="text-xl font-semibold text-foreground mb-2">
                    {{ __('landing.pain_points.scheduling_chaos.title') }}
                </h3>

                <!-- Description -->
                <p class="text-muted-foreground">
                    {{ __('landing.pain_points.scheduling_chaos.description') }}
                </p>

                <!-- Decorative line -->
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary/10 rounded-b-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>
        </div>

        <!-- Bottom CTA Text -->
        <div class="text-center mt-12">
            <p class="text-lg text-foreground">
                {!! __('landing.pain_points.cta') !!}
            </p>
        </div>
    </div>
</section>
