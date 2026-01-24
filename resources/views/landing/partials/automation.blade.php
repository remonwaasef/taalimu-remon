<section class="py-16 lg:py-24 relative overflow-hidden">
    <!-- Background decorations -->
    <div class="absolute top-1/2 left-0 w-72 h-72 bg-cyan/5 rounded-full blur-3xl -translate-y-1/2"></div>
    <div class="absolute top-1/2 right-0 w-72 h-72 bg-light-purple/5 rounded-full blur-3xl -translate-y-1/2"></div>
    
    <div class="container relative mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12 lg:mb-20">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan/10 border border-cyan/20 mb-6">
                <span class="text-sm font-medium text-cyan">{{ __('landing.automation.badge') }}</span>
            </div>
            <h2 class="text-3xl lg:text-4xl font-bold text-foreground mb-4">
                {{ __('landing.automation.title_prefix') }} <span class="gradient-text">{{ \App\Models\SiteSetting::get('site_name', __('landing.automation.title_highlight')) }}</span>
            </h2>
            <p class="text-muted-foreground text-lg max-w-2xl mx-auto">
                {{ __('landing.automation.subtitle') }}
            </p>
        </div>

        <!-- Steps -->
        <div class="grid md:grid-cols-3 gap-8 lg:gap-12 relative">
            <!-- Connection Lines (Desktop) -->
            <div class="hidden md:block absolute top-24 left-1/3 right-1/3 h-0.5 bg-gradient-to-r from-cyan via-light-purple to-success-green"></div>
            
            <!-- Step 1 -->
            <div
                class="relative text-center group animate-fade-in"
                style="animation-delay: 0s;"
            >
                <!-- Step Circle -->
                <div class="relative mx-auto mb-8">
                    <!-- Outer ring -->
                    <div class="w-32 h-32 rounded-full border-2 border-dashed border-light-purple/30 flex items-center justify-center mx-auto group-hover:border-cyan/50 transition-colors duration-500">
                        <!-- Inner circle -->
                        <div class="w-24 h-24 rounded-full gradient-hero flex items-center justify-center shadow-lg group-hover:shadow-purple-glow transition-shadow duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-primary-foreground"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                        </div>
                    </div>
                    
                    <!-- Step number -->
                    <div class="absolute -top-2 -right-2 w-10 h-10 rounded-full bg-cyan flex items-center justify-center shadow-md">
                        <span class="text-sm font-bold text-dark-text">01</span>
                    </div>
                </div>

                <!-- Content -->
                <h3 class="text-xl font-semibold text-foreground mb-3">
                    {{ __('landing.automation.step1.title') }}
                </h3>
                <p class="text-muted-foreground max-w-xs mx-auto mb-8">
                    {{ __('landing.automation.step1.description') }}
                </p>

                <!-- Visual -->
                <div class="relative mx-auto max-w-[280px] rounded-2xl overflow-hidden border border-border shadow-2xl group-hover:-translate-y-2 transition-transform duration-500 bg-card">
                    <img src="{{ asset('images/automation/step1.webp') }}" alt="{{ __('landing.automation.step1.title') }}" class="w-full h-auto opacity-90 group-hover:opacity-100 transition-opacity" loading="lazy" decoding="async" width="600" height="400">
                    <!-- Glass overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-background/40 to-transparent pointer-events-none"></div>
                </div>

                <!-- Arrow (Mobile) -->
                <div class="md:hidden flex justify-center my-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-cyan rotate-90"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </div>
            </div>

            <!-- Step 2 -->
            <div
                class="relative text-center group animate-fade-in"
                style="animation-delay: 0.15s;"
            >
                <!-- Step Circle -->
                <div class="relative mx-auto mb-8">
                    <!-- Outer ring -->
                    <div class="w-32 h-32 rounded-full border-2 border-dashed border-light-purple/30 flex items-center justify-center mx-auto group-hover:border-cyan/50 transition-colors duration-500">
                        <!-- Inner circle -->
                        <div class="w-24 h-24 rounded-full gradient-hero flex items-center justify-center shadow-lg group-hover:shadow-purple-glow transition-shadow duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-primary-foreground"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                    </div>
                    
                    <!-- Step number -->
                    <div class="absolute -top-2 -right-2 w-10 h-10 rounded-full bg-cyan flex items-center justify-center shadow-md">
                        <span class="text-sm font-bold text-dark-text">02</span>
                    </div>
                </div>

                <!-- Content -->
                <h3 class="text-xl font-semibold text-foreground mb-3">
                    {{ __('landing.automation.step2.title') }}
                </h3>
                <p class="text-muted-foreground max-w-xs mx-auto mb-8">
                    {{ __('landing.automation.step2.description') }}
                </p>

                <!-- Visual -->
                <div class="relative mx-auto max-w-[280px] rounded-2xl overflow-hidden border border-border shadow-2xl group-hover:-translate-y-2 transition-transform duration-500 bg-card">
                    <img src="{{ asset('images/automation/step2.webp') }}" alt="{{ __('landing.automation.step2.title') }}" class="w-full h-auto opacity-90 group-hover:opacity-100 transition-opacity" loading="lazy" decoding="async" width="600" height="400">
                    <!-- Glass overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-background/40 to-transparent pointer-events-none"></div>
                </div>

                <!-- Arrow (Mobile) -->
                <div class="md:hidden flex justify-center my-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-cyan rotate-90"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </div>
            </div>

            <!-- Step 3 -->
            <div
                class="relative text-center group animate-fade-in"
                style="animation-delay: 0.3s;"
            >
                <!-- Step Circle -->
                <div class="relative mx-auto mb-8">
                    <!-- Outer ring -->
                    <div class="w-32 h-32 rounded-full border-2 border-dashed border-light-purple/30 flex items-center justify-center mx-auto group-hover:border-cyan/50 transition-colors duration-500">
                        <!-- Inner circle -->
                        <div class="w-24 h-24 rounded-full gradient-hero flex items-center justify-center shadow-lg group-hover:shadow-purple-glow transition-shadow duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-primary-foreground"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                        </div>
                    </div>
                    
                    <!-- Step number -->
                    <div class="absolute -top-2 -right-2 w-10 h-10 rounded-full bg-cyan flex items-center justify-center shadow-md">
                        <span class="text-sm font-bold text-dark-text">03</span>
                    </div>
                </div>

                <!-- Content -->
                <h3 class="text-xl font-semibold text-foreground mb-3">
                    {{ __('landing.automation.step3.title') }}
                </h3>
                <p class="text-muted-foreground max-w-xs mx-auto mb-8">
                    {{ __('landing.automation.step3.description') }}
                </p>

                <!-- Visual -->
                <div class="relative mx-auto max-w-[280px] rounded-2xl overflow-hidden border border-border shadow-2xl group-hover:-translate-y-2 transition-transform duration-500 bg-card">
                    <img src="{{ asset('images/automation/step3.webp') }}" alt="{{ __('landing.automation.step3.title') }}" class="w-full h-auto opacity-90 group-hover:opacity-100 transition-opacity" loading="lazy" decoding="async" width="600" height="400">
                    <!-- Glass overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-background/40 to-transparent pointer-events-none"></div>
                </div>
            </div>
        </div>

        <!-- Result Box -->
        <div class="mt-16 lg:mt-20 max-w-2xl mx-auto">
            <div class="relative rounded-3xl p-8 lg:p-10 text-center overflow-hidden border border-white/10 shadow-2xl" style="background: linear-gradient(135deg, #172554 0%, #1e40af 50%, #1d4ed8 100%);">
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-cyan/10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-success-green/10 rounded-full blur-2xl"></div>
                
                <div class="relative">
                    <div class="text-7xl lg:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-br from-white via-blue-100 to-blue-200 mb-2 drop-shadow-lg">
                        98%
                    </div>
                    <div class="text-xl lg:text-2xl font-semibold text-primary-foreground/90 mb-4">
                        {{ __('landing.automation.result.rate') }}
                    </div>
                    <p class="text-primary-foreground/70">
                        {{ __('landing.automation.result.text') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
