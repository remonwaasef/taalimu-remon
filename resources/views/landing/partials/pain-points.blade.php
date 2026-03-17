<section class="py-16 lg:py-24 bg-slate-100 border-y border-slate-200">
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
                class="group relative bg-white rounded-3xl p-8 border border-white shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-fade-in delay-0"
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
            </div>

            <!-- Time Wasted -->
            <div
                class="group relative bg-white rounded-3xl p-8 border border-white shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-fade-in delay-1"
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
            </div>

            <!-- Parents Complaints -->
            <div
                class="group relative bg-white rounded-3xl p-8 border border-white shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-fade-in delay-2"
            >
                <!-- Icon -->
                <div class="w-16 h-16 rounded-2xl bg-primary/5 border-primary/10 border flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-primary"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>

                <!-- Stat -->
                <div class="text-5xl lg:text-6xl font-bold text-primary mb-2">
                    24/7
                </div>

                <!-- Title -->
                <h3 class="text-xl font-semibold text-foreground mb-2">
                    {{ __('landing.pain_points.complaints.title') }}
                </h3>

                <!-- Description -->
                <p class="text-muted-foreground">
                    {{ __('landing.pain_points.complaints.description') }}
                </p>
            </div>

            <!-- Manual Work -->
            <div
                class="group relative bg-white rounded-3xl p-8 border border-white shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-fade-in delay-3"
            >
                <!-- Icon -->
                <div class="w-16 h-16 rounded-2xl bg-primary/5 border-primary/10 border flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-primary"><path d="m16 6 4 14"/><path d="M12 6v14"/><path d="M8 8v12"/><path d="M4 4v16"/></svg>
                </div>

                <!-- Stat -->
                <div class="text-5xl lg:text-6xl font-bold text-primary mb-2">
                    100%
                </div>

                <!-- Title -->
                <h3 class="text-xl font-semibold text-foreground mb-2">
                    {{ __('landing.pain_points.manual_work.title') }}
                </h3>

                <!-- Description -->
                <p class="text-muted-foreground">
                    {{ __('landing.pain_points.manual_work.description') }}
                </p>
            </div>
        </div>

        <!-- Trust Indicator -->
        <div class="mt-16 text-center animate-fade-in delay-200">
            <p class="text-sm font-semibold text-primary uppercase tracking-widest mb-4">
                {{ __('landing.pain_points.trust_label') }}
            </p>
            <div class="flex flex-wrap items-center justify-center gap-8 lg:gap-16 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                <!-- Trust icons/logos placeholders -->
                <div class="flex items-center gap-2 font-bold text-xl text-foreground">
                    <i class="fas fa-shield-alt text-primary"></i> Secure Payments
                </div>
                <div class="flex items-center gap-2 font-bold text-xl text-foreground">
                    <i class="fab fa-whatsapp text-green-500"></i> WhatsApp Verified
                </div>
                <div class="flex items-center gap-2 font-bold text-xl text-foreground">
                    <i class="fas fa-user-check text-blue-500"></i> Educator Trusted
                </div>
            </div>
        </div>
    </div>
</section>
