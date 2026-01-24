<section id="testimonials" class="py-16 lg:py-24 relative overflow-hidden">
    <!-- Background decorations -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-light-purple/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-cyan/5 rounded-full blur-3xl"></div>
    
    <div class="container relative mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12 lg:mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-success-green/10 border border-success-green/20 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-success-green"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span class="text-sm font-medium text-success-green">{{ __('landing.testimonials.badge') }}</span>
            </div>
            <h2 class="text-3xl lg:text-4xl font-bold text-foreground mb-4">
                {{ __('landing.testimonials.title_prefix') }} <span class="gradient-text">{{ __('landing.testimonials.title_highlight') }}</span>
            </h2>
            <p class="text-muted-foreground text-lg max-w-2xl mx-auto">
                {{ str_replace(config('app.name'), \App\Models\SiteSetting::get('site_name', config('app.name')), __('landing.testimonials.subtitle')) }}
            </p>
        </div>

        <!-- Testimonials Grid -->
        <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
            <!-- Testimonial 1 -->
            <div
                class="group relative bg-card rounded-2xl p-6 lg:p-8 border-2 border-light-purple/20 hover:border-light-purple/40 transition-all duration-300 hover:shadow-card-hover animate-fade-in"
                style="animation-delay: 0s;"
            >
                <!-- Quote Icon -->
                <div class="absolute -top-4 left-6 w-10 h-10 rounded-xl gradient-hero flex items-center justify-center shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-primary-foreground"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/></svg>
                </div>

                <!-- Rating -->
                <div class="flex gap-1 mb-4 pt-4">
                    @for ($i = 0; $i < 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-cyan"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @endfor
                </div>

                <!-- Content -->
                <p class="text-foreground mb-6 leading-relaxed">
                    "{{ __('landing.testimonials.items.0.quote') }}"
                </p>

                <!-- Stat Box -->
                <div class="bg-muted/50 rounded-xl p-4 mb-6 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-success-green/10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-success-green"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold gradient-text">{{ __('landing.testimonials.items.0.stat_value') }}</div>
                        <div class="text-sm text-muted-foreground">{{ __('landing.testimonials.items.0.stat_label') }}</div>
                    </div>
                </div>

                <!-- Author -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full gradient-hero flex items-center justify-center text-primary-foreground font-bold">
                        A
                    </div>
                    <div>
                        <div class="font-semibold text-foreground">{{ __('landing.testimonials.items.0.author_name') }}</div>
                        <div class="text-sm text-muted-foreground">{{ __('landing.testimonials.items.0.author_role') }}, {{ __('landing.testimonials.items.0.author_company') }}</div>
                        <div class="text-xs text-light-purple">{{ __('landing.testimonials.items.0.author_location') }}</div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div
                class="group relative bg-card rounded-2xl p-6 lg:p-8 border-2 border-light-purple/20 hover:border-light-purple/40 transition-all duration-300 hover:shadow-card-hover animate-fade-in"
                style="animation-delay: 0.1s;"
            >
                <!-- Quote Icon -->
                <div class="absolute -top-4 left-6 w-10 h-10 rounded-xl gradient-hero flex items-center justify-center shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-primary-foreground"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/></svg>
                </div>

                <!-- Rating -->
                <div class="flex gap-1 mb-4 pt-4">
                    @for ($i = 0; $i < 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-cyan"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @endfor
                </div>

                <!-- Content -->
                <p class="text-foreground mb-6 leading-relaxed">
                    "{{ __('landing.testimonials.items.1.quote') }}"
                </p>

                <!-- Stat Box -->
                <div class="bg-muted/50 rounded-xl p-4 mb-6 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-success-green/10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-success-green"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold gradient-text">{{ __('landing.testimonials.items.1.stat_value') }}</div>
                        <div class="text-sm text-muted-foreground">{{ __('landing.testimonials.items.1.stat_label') }}</div>
                    </div>
                </div>

                <!-- Author -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full gradient-hero flex items-center justify-center text-primary-foreground font-bold">
                        F
                    </div>
                    <div>
                        <div class="font-semibold text-foreground">{{ __('landing.testimonials.items.1.author_name') }}</div>
                        <div class="text-sm text-muted-foreground">{{ __('landing.testimonials.items.1.author_role') }}, {{ __('landing.testimonials.items.1.author_company') }}</div>
                        <div class="text-xs text-light-purple">{{ __('landing.testimonials.items.1.author_location') }}</div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div
                class="group relative bg-card rounded-2xl p-6 lg:p-8 border-2 border-light-purple/20 hover:border-light-purple/40 transition-all duration-300 hover:shadow-card-hover animate-fade-in"
                style="animation-delay: 0.2s;"
            >
                <!-- Quote Icon -->
                <div class="absolute -top-4 left-6 w-10 h-10 rounded-xl gradient-hero flex items-center justify-center shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-primary-foreground"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/></svg>
                </div>

                <!-- Rating -->
                <div class="flex gap-1 mb-4 pt-4">
                    @for ($i = 0; $i < 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-cyan"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @endfor
                </div>

                <!-- Content -->
                <p class="text-foreground mb-6 leading-relaxed">
                    "{{ __('landing.testimonials.items.2.quote') }}"
                </p>

                <!-- Stat Box -->
                <div class="bg-muted/50 rounded-xl p-4 mb-6 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-success-green/10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-success-green"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold gradient-text">{{ __('landing.testimonials.items.2.stat_value') }}</div>
                        <div class="text-sm text-muted-foreground">{{ __('landing.testimonials.items.2.stat_label') }}</div>
                    </div>
                </div>

                <!-- Author -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full gradient-hero flex items-center justify-center text-primary-foreground font-bold">
                        M
                    </div>
                    <div>
                        <div class="font-semibold text-foreground">{{ __('landing.testimonials.items.2.author_name') }}</div>
                        <div class="text-sm text-muted-foreground">{{ __('landing.testimonials.items.2.author_role') }}, {{ __('landing.testimonials.items.2.author_company') }}</div>
                        <div class="text-xs text-light-purple">{{ __('landing.testimonials.items.2.author_location') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
