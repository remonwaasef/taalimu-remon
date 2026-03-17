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
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg> Secure Payments
                </div>
                <div class="flex items-center gap-2 font-bold text-xl text-foreground">
                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path></svg> WhatsApp Verified
                </div>
                <div class="flex items-center gap-2 font-bold text-xl text-foreground">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Educator Trusted
                </div>
            </div>
        </div>
    </div>
</section>
