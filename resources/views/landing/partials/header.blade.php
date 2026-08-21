<header
    class="landing-header fixed top-0 left-0 right-0"
    style="background-color: #ffffff !important; background: #ffffff !important; opacity: 1 !important; border-bottom: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.04); z-index: 99999 !important;"
    x-data="{
        scrolled: false,
        isMenuOpen: false
    }"
    @scroll.window="scrolled = window.pageYOffset > 20"
    @keydown.escape.window="isMenuOpen = false"
    x-effect="document.body.style.overflow = isMenuOpen ? 'hidden' : ''"
>
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1240px; margin: 0 auto;">
        <div class="flex items-center justify-between gap-4" style="min-height: 4.5rem;">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 lg:gap-3 group shrink-0">
                <img src="{{ asset('images/brand/logo-full.png?v=3') }}" alt="Taalimu Logo" class="h-7 sm:h-8 w-auto">
            </a>

            <!-- Nav Links (Desktop) -->
            <nav class="hidden lg:flex items-center gap-6 xl:gap-8">
                <a href="#outcome" class="nav-link" style="color: #334155; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#334155'">
                    {{ __('landing.outcome.badge') }}
                </a>
                <a href="#whatsapp" class="nav-link" style="color: #334155; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#334155'">
                    {{ __('landing.nav.whatsapp') }}
                </a>
                <a href="#excel" class="nav-link" style="color: #334155; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#334155'">
                    {{ __('landing.nav.excel_migration') }}
                </a>
                <a href="#pricing" class="nav-link" style="color: #334155; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#334155'">
                    {{ __('landing.nav.pricing') }}
                </a>
                <a href="#faq" class="nav-link" style="color: #334155; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#334155'">
                    {{ __('landing.nav.faq') }}
                </a>
            </nav>

            <!-- Actions (Desktop) -->
            <div class="hidden lg:flex items-center gap-3 xl:gap-4">
                <!-- Lang Switcher -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" style="color: #334155; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.875rem; border-radius: 0.625rem; border: 1px solid #e2e8f0; background: transparent; cursor: pointer;">
                        <i class="fas fa-globe" style="color: #2E8B83;"></i>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                    </button>
                    <div x-show="open" style="display: none;" x-cloak @click.away="open = false" x-transition class="absolute right-0 mt-2 w-36 bg-white rounded-xl shadow-2xl py-2 overflow-hidden z-50 border border-slate-200">
                        <a href="{{ route('lang.switch', ['locale' => 'ar']) }}" style="display: block; padding: 0.5rem 1rem; font-size: 0.85rem; text-decoration: none; {{ app()->isLocale('ar') ? 'color: #2E8B83; font-weight: 700; background: #E6F4F3;' : 'color: #334155;' }}">
                            العربية (AR)
                        </a>
                        <a href="{{ route('lang.switch', ['locale' => 'en']) }}" style="display: block; padding: 0.5rem 1rem; font-size: 0.85rem; text-decoration: none; {{ app()->isLocale('en') ? 'color: #2E8B83; font-weight: 700; background: #E6F4F3;' : 'color: #334155;' }}">
                            English (EN)
                        </a>
                    </div>
                </div>

                <!-- Sign In -->
                <a href="{{ route('login.portal') }}" style="color: #0f172a; font-weight: 700; font-size: 0.875rem; padding: 0.625rem 1.125rem; border-radius: 0.625rem; text-decoration: none; border: 1px solid #cbd5e1; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <span>{{ __('landing.nav.sign_in') }}</span>
                </a>

                <!-- Primary CTA -->
                <a href="{{ route('register') }}" style="
                    padding: 0.625rem 1.35rem;
                    font-size: 0.875rem;
                    font-weight: 800;
                    border-radius: 0.625rem;
                    background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);
                    color: #ffffff !important;
                    text-decoration: none;
                    box-shadow: 0 4px 14px rgba(46,139,131,0.28);
                    transition: transform 0.2s;
                " onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    {{ __('landing.nav.start_trial') }}
                </a>
            </div>

            <!-- Mobile Actions -->
            <div class="flex lg:hidden items-center gap-2">
                <!-- Mobile Lang -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" style="color: #334155; font-weight: 700; font-size: 0.75rem; display: flex; align-items: center; gap: 0.35rem; padding: 0.45rem 0.65rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                        <i class="fas fa-globe" style="color: #2E8B83;"></i>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                    </button>
                    <div x-show="open" style="display: none;" x-cloak @click.away="open = false" x-transition class="absolute right-0 mt-2 w-32 bg-white rounded-xl shadow-2xl py-2 overflow-hidden z-50 border border-slate-200">
                        <a href="{{ route('lang.switch', ['locale' => 'ar']) }}" style="display: block; padding: 0.5rem 0.75rem; font-size: 0.75rem; text-decoration: none; {{ app()->isLocale('ar') ? 'color: #2E8B83; font-weight: 700; background: #E6F4F3;' : 'color: #334155;' }}">
                            العربية
                        </a>
                        <a href="{{ route('lang.switch', ['locale' => 'en']) }}" style="display: block; padding: 0.5rem 0.75rem; font-size: 0.75rem; text-decoration: none; {{ app()->isLocale('en') ? 'color: #2E8B83; font-weight: 700; background: #E6F4F3;' : 'color: #334155;' }}">
                            English
                        </a>
                    </div>
                </div>

                <a href="{{ route('register') }}" style="
                    padding: 0.45rem 0.85rem;
                    font-size: 0.75rem;
                    font-weight: 800;
                    border-radius: 0.5rem;
                    background: #2E8B83;
                    color: #ffffff !important;
                    text-decoration: none;
                ">
                    {{ __('landing.nav.start_trial') }}
                </a>

                <button @click="isMenuOpen = !isMenuOpen" :aria-expanded="isMenuOpen ? 'true' : 'false'" aria-label="Menu" style="width: 2.25rem; height: 2.25rem; display: flex; align-items: center; justify-content: center; color: #0f172a; border-radius: 0.5rem; border: 1px solid #e2e8f0; background: #ffffff;">
                    <i class="fas" :class="isMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Slide Menu -->
    <div
        id="mobileMenu"
        x-show="isMenuOpen" style="display: none;" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="lg:hidden absolute top-full left-0 right-0 bg-white border-b border-slate-200 shadow-xl py-6 px-6 z-40 max-h-[80vh] overflow-y-auto"
    >
        <nav class="flex flex-col gap-4">
            <a href="#outcome" @click="isMenuOpen = false" style="color: #0f172a; font-weight: 700; font-size: 1.05rem; padding: 0.35rem 0; text-decoration: none;">
                {{ __('landing.outcome.badge') }}
            </a>
            <a href="#whatsapp" @click="isMenuOpen = false" style="color: #0f172a; font-weight: 700; font-size: 1.05rem; padding: 0.35rem 0; text-decoration: none;">
                {{ __('landing.nav.whatsapp') }}
            </a>
            <a href="#excel" @click="isMenuOpen = false" style="color: #0f172a; font-weight: 700; font-size: 1.05rem; padding: 0.35rem 0; text-decoration: none;">
                {{ __('landing.nav.excel_migration') }}
            </a>
            <a href="#pricing" @click="isMenuOpen = false" style="color: #0f172a; font-weight: 700; font-size: 1.05rem; padding: 0.35rem 0; text-decoration: none;">
                {{ __('landing.nav.pricing') }}
            </a>
            <a href="#faq" @click="isMenuOpen = false" style="color: #0f172a; font-weight: 700; font-size: 1.05rem; padding: 0.35rem 0; text-decoration: none;">
                {{ __('landing.nav.faq') }}
            </a>
            <div style="height: 1px; background: #e2e8f0; margin: 0.5rem 0;"></div>
            <a href="{{ route('login.portal') }}" style="color: #0f172a; font-weight: 700; font-size: 1.05rem; text-decoration: none;">
                {{ __('landing.nav.sign_in') }}
            </a>
            <a href="{{ route('register') }}" style="
                text-align: center;
                border-radius: 0.75rem;
                padding: 0.875rem;
                background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);
                color: #ffffff !important;
                font-weight: 800;
                text-decoration: none;
            ">
                {{ __('landing.nav.start_trial') }}
            </a>
        </nav>
    </div>
</header>
