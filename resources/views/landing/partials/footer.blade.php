<footer class="bg-slate-50 relative overflow-hidden pt-20 pb-10 border-t border-border/50">
    <!-- Decorative Accents -->
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-primary/10 to-transparent"></div>
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-64 h-64 bg-secondary/5 rounded-full blur-3xl"></div>

    <div class="container relative mx-auto px-4 lg:px-8">
        <!-- Main Footer Links -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-10 md:gap-12 mb-20">
            
            <!-- Brand/About Column -->
            <div class="col-span-2 lg:col-span-2 space-y-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group transition-all duration-300">
                    <img src="{{ asset('images/brand/logo-full.png?v=2') }}" alt="{{ config('app.name') }}" class="h-9 w-auto object-contain transition-transform duration-500 group-hover:scale-110 mix-blend-multiply">
                    <div class="flex flex-col">
                        <span class="font-bold text-lg text-primary leading-none tracking-tight">
                            {{ \App\Models\SiteSetting::get('site_name', 'Taalimu') }}
                        </span>
                        <span class="text-[8px] font-extrabold text-secondary uppercase tracking-[0.2em] mt-0.5 opacity-70">
                            {{ __('landing.navbar.badge_short') ?? 'Smart Education' }}
                        </span>
                    </div>
                </a>
                <p class="text-muted-foreground text-base leading-relaxed max-w-sm font-medium">
                    {{ \App\Models\SiteSetting::get('site_description_' . app()->getLocale(), __('landing.hero.subtitle')) }}
                </p>
                
                <!-- Social Media -->
                <div class="flex items-center gap-5">
                    <a href="#" class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-muted-foreground hover:bg-secondary hover:text-white transition-all duration-300 border border-border group shadow-sm">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M22 4.01c-1 .49-1.98.689-3 .99-1.121-1.265-2.783-1.335-4.38-.737S11.977 6.323 12 8V9c-4-.531-7-2-9-4 0 0-4 9 5 13-2 1-5 1.5-7 1 4 4 10 4 15 1 5-3 5-15 4-16.01 1-.49 1.98-.689 3-.99z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-muted-foreground hover:bg-secondary hover:text-white transition-all duration-300 border border-border group shadow-sm">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-muted-foreground hover:bg-secondary hover:text-white transition-all duration-300 border border-border group shadow-sm">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-muted-foreground hover:bg-secondary hover:text-white transition-all duration-300 border border-border group shadow-sm">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37zM17.5 6.5h.01"/></svg>
                    </a>
                </div>
            </div>

            <!-- Product Links -->
            <div>
                <h4 class="text-primary font-bold text-lg mb-8">{{ __('landing.footer.product.title') }}</h4>
                <ul class="space-y-4">
                    <li><a href="#features" class="text-muted-foreground hover:text-secondary transition-colors duration-300 text-sm font-medium">{{ __('landing.footer.product.features') }}</a></li>
                    <li><a href="#pricing" class="text-muted-foreground hover:text-secondary transition-colors duration-300 text-sm font-medium">{{ __('landing.footer.product.pricing') }}</a></li>
                    <li><a href="#" class="text-muted-foreground hover:text-secondary transition-colors duration-300 text-sm font-medium">{{ __('landing.footer.product.integrations') }}</a></li>
                    <li><a href="#" class="text-muted-foreground hover:text-secondary transition-colors duration-300 text-sm font-medium">{{ __('landing.footer.product.updates') }}</a></li>
                </ul>
            </div>

            <!-- Support Links -->
            <div>
                <h4 class="text-primary font-bold text-lg mb-8">{{ __('landing.footer.resources.title') }}</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="text-muted-foreground hover:text-secondary transition-colors duration-300 text-sm font-medium">{{ __('landing.footer.resources.help') }}</a></li>
                    <li><a href="#" class="text-muted-foreground hover:text-secondary transition-colors duration-300 text-sm font-medium">{{ __('landing.footer.resources.docs') }}</a></li>
                    <li><a href="#" class="text-muted-foreground hover:text-secondary transition-colors duration-300 text-sm font-medium">{{ __('landing.footer.resources.blog') }}</a></li>
                    <li><a href="#" class="text-muted-foreground hover:text-secondary transition-colors duration-300 text-sm font-medium">{{ __('landing.footer.resources.api') }}</a></li>
                </ul>
            </div>

            <!-- Legal Links -->
            <div>
                <h4 class="text-primary font-bold text-lg mb-8">{{ __('landing.footer.legal.title') }}</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('privacy') }}" class="text-muted-foreground hover:text-secondary transition-colors duration-300 text-sm font-medium">{{ __('landing.footer.legal.privacy') }}</a></li>
                    <li><a href="{{ route('terms') }}" class="text-muted-foreground hover:text-secondary transition-colors duration-300 text-sm font-medium">{{ __('landing.footer.legal.terms') }}</a></li>
                    <li><a href="{{ route('cookies') }}" class="text-muted-foreground hover:text-secondary transition-colors duration-300 text-sm font-medium">{{ __('landing.footer.legal.cookie') }}</a></li>
                    <li><button onclick="openCookieSettings()" class="text-muted-foreground hover:text-secondary transition-colors duration-300 text-sm font-medium">{{ __('gdpr.banner.settings') }}</button></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Footer Section -->
        <div class="pt-8 border-t border-border/50 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-muted-foreground text-sm font-medium">
                {{ str_replace(config('app.name'), \App\Models\SiteSetting::get('site_name', config('app.name')), __('landing.footer.copyright')) }}
            </div>
            
            <!-- System Status -->
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-success-green/10 border border-success-green/20">
                    <span class="w-2 h-2 rounded-full bg-success-green animate-pulse"></span>
                    <span class="text-[10px] font-bold text-success-green uppercase tracking-wider">All Systems Operational</span>
                </div>
            </div>
        </div>
    </div>
</footer>
