<header 
    class="fixed top-0 left-0 right-0 z-50 bg-card/80 backdrop-blur-lg border-b border-border/50"
    x-data="{ isMenuOpen: false }"
>
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl gradient-hero flex items-center justify-center shadow-md">
                    <span class="text-primary-foreground font-bold text-xl">{{ substr(\App\Models\SiteSetting::get('site_name', config('app.name')), 0, 1) }}</span>
                </div>
                <span class="font-bold text-xl text-foreground">{{ \App\Models\SiteSetting::get('site_name', config('app.name')) }}</span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-8">
                <a href="#features" class="text-muted-foreground hover:text-foreground transition-colors font-medium">{{ __('landing.nav.features') }}</a>
                <a href="#pricing" class="text-muted-foreground hover:text-foreground transition-colors font-medium">{{ __('landing.nav.pricing') }}</a>
                <a href="#testimonials" class="text-muted-foreground hover:text-foreground transition-colors font-medium">{{ __('landing.nav.testimonials') }}</a>
                <a href="#faq" class="text-muted-foreground hover:text-foreground transition-colors font-medium">{{ __('landing.nav.faq') }}</a>
            </nav>

            <!-- Desktop CTA -->
            <div class="hidden lg:flex items-center gap-4">
                <a href="{{ route('login.portal') }}" class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 text-muted-foreground">
                    {{ __('landing.nav.sign_in') }}
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                    {{ __('landing.nav.start_trial') }}
                </a>
                
                <x-pwa-install />
                
                <!-- Language Switcher Dropdown -->
                <div x-data="{ langOpen: false }" class="relative">
                    <button 
                        @click="langOpen = !langOpen"
                        @click.away="langOpen = false"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-muted/50 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/>
                            <path d="M2 12h20"/>
                        </svg>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div 
                        x-show="langOpen"
                        x-transition
                        class="absolute right-0 mt-2 w-40 bg-card border border-border rounded-lg shadow-lg overflow-hidden z-50"
                        style="display: none;"
                    >
                        <a href="{{ route('lang.switch', ['locale' => 'en']) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-muted/50 transition-colors {{ app()->getLocale() == 'en' ? 'bg-muted/30 text-foreground font-medium' : 'text-muted-foreground' }}">
                            <span class="text-lg">🇬🇧</span>
                            <span>English</span>
                        </a>
                        <a href="{{ route('lang.switch', ['locale' => 'ar']) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-muted/50 transition-colors {{ app()->getLocale() == 'ar' ? 'bg-muted/30 text-foreground font-medium' : 'text-muted-foreground' }}">
                            <span class="text-lg">🇸🇦</span>
                            <span>العربية</span>
                        </a>
                        <a href="{{ route('lang.switch', ['locale' => 'fr']) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-muted/50 transition-colors {{ app()->getLocale() == 'fr' ? 'bg-muted/30 text-foreground font-medium' : 'text-muted-foreground' }}">
                            <span class="text-lg">🇫🇷</span>
                            <span>Français</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <button
                class="lg:hidden p-2 text-foreground"
                @click="isMenuOpen = !isMenuOpen"
                aria-label="Toggle menu"
            >
                <svg x-show="!isMenuOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                <svg x-show="isMenuOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div 
            class="lg:hidden py-4 border-t border-border/50 animate-fade-in"
            x-show="isMenuOpen"
            x-transition
            style="display: none;"
        >
            <nav class="flex flex-col gap-4">
                <a href="#features" class="text-muted-foreground hover:text-foreground transition-colors font-medium py-2" @click="isMenuOpen = false">{{ __('landing.nav.features') }}</a>
                <a href="#pricing" class="text-muted-foreground hover:text-foreground transition-colors font-medium py-2" @click="isMenuOpen = false">{{ __('landing.nav.pricing') }}</a>
                <a href="#testimonials" class="text-muted-foreground hover:text-foreground transition-colors font-medium py-2" @click="isMenuOpen = false">{{ __('landing.nav.testimonials') }}</a>
                <a href="#faq" class="text-muted-foreground hover:text-foreground transition-colors font-medium py-2" @click="isMenuOpen = false">{{ __('landing.nav.faq') }}</a>
                
                <div class="flex flex-col gap-3 pt-4 border-t border-border/50">
                    <a href="{{ route('login.portal') }}" class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-full">
                        {{ __('landing.nav.sign_in') }}
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full">
                        {{ __('landing.nav.start_trial') }}
                    </a>
                    
                    <x-pwa-install buttonClass="inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-accent text-accent-foreground shadow hover:bg-accent/90 h-10 px-4 py-2 w-full" />
                    
                    <!-- Language Options -->
                    <div class="pt-2 border-t border-border/50">
                        <div class="text-xs text-muted-foreground mb-2 px-4">Language / اللغة</div>
                        <a href="{{ route('lang.switch', ['locale' => 'en']) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-muted/50 transition-colors {{ app()->getLocale() == 'en' ? 'bg-muted/30 text-foreground font-medium' : 'text-muted-foreground' }}">
                            <span class="text-lg">🇬🇧</span>
                            <span>English</span>
                        </a>
                        <a href="{{ route('lang.switch', ['locale' => 'ar']) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-muted/50 transition-colors {{ app()->getLocale() == 'ar' ? 'bg-muted/30 text-foreground font-medium' : 'text-muted-foreground' }}">
                            <span class="text-lg">🇸🇦</span>
                            <span>العربية</span>
                        </a>
                        <a href="{{ route('lang.switch', ['locale' => 'fr']) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-muted/50 transition-colors {{ app()->getLocale() == 'fr' ? 'bg-muted/30 text-foreground font-medium' : 'text-muted-foreground' }}">
                            <span class="text-lg">🇫🇷</span>
                            <span>Français</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
