<header
    class="landing-header fixed top-0 left-0 right-0 z-50 transition-all duration-300"
    style="background-color: rgba(255, 255, 255, 0.92); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(226, 232, 240, 0.8); box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);"
    x-data="{
        scrolled: false,
        isMenuOpen: false,
        scrollToSection(selector) {
            const target = document.querySelector(selector);
            if (target) {
                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
            this.isMenuOpen = false;
        }
    }"
    @scroll.window="scrolled = window.pageYOffset > 20"
    @keydown.escape.window="isMenuOpen = false"
    x-effect="document.body.style.overflow = isMenuOpen ? 'hidden' : ''"
>
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 text-decoration-none">
                <img src="{{ asset('images/brand/logo-full.png') }}" alt="Taalimu" class="h-8 sm:h-9 w-auto">
            </a>

            <!-- Navigation Links (Desktop) -->
            <nav class="hidden lg:flex items-center gap-6 xl:gap-8">
                <a href="#features" @click.prevent="scrollToSection('#features')" class="text-slate-700 hover:text-[#2E8B83] font-semibold text-sm transition-colors text-decoration-none">
                    {{ __('landing.nav.features') }}
                </a>
                <a href="#qr-registration" @click.prevent="scrollToSection('#qr-registration')" class="text-slate-700 hover:text-[#2E8B83] font-semibold text-sm transition-colors text-decoration-none">
                    {{ __('landing.nav.qr_registration') }}
                </a>
                <a href="#whatsapp-notifications" @click.prevent="scrollToSection('#whatsapp-notifications')" class="text-slate-700 hover:text-[#2E8B83] font-semibold text-sm transition-colors text-decoration-none">
                    {{ __('landing.nav.whatsapp') }}
                </a>
                <a href="#solutions" @click.prevent="scrollToSection('#solutions')" class="text-slate-700 hover:text-[#2E8B83] font-semibold text-sm transition-colors text-decoration-none">
                    {{ __('landing.nav.solutions') }}
                </a>
                <a href="#how-it-works" @click.prevent="scrollToSection('#how-it-works')" class="text-slate-700 hover:text-[#2E8B83] font-semibold text-sm transition-colors text-decoration-none">
                    {{ __('landing.nav.how_it_works') }}
                </a>
                <a href="#faq" @click.prevent="scrollToSection('#faq')" class="text-slate-700 hover:text-[#2E8B83] font-semibold text-sm transition-colors text-decoration-none">
                    {{ __('landing.nav.faq') }}
                </a>
            </nav>

            <!-- Actions (Desktop) -->
            <div class="hidden lg:flex items-center gap-3 xl:gap-4">
                <!-- Language Switcher Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button" class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 text-slate-700 font-bold text-xs hover:border-slate-300 transition-colors bg-white">
                        <i class="fas fa-globe text-[#2E8B83]"></i>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false" x-transition class="absolute end-0 mt-2 w-36 bg-white rounded-xl shadow-xl py-2 overflow-hidden z-50 border border-slate-100">
                        <a href="{{ route('lang.switch', ['locale' => 'ar']) }}" class="block px-4 py-2 text-xs font-semibold text-decoration-none {{ app()->isLocale('ar') ? 'text-[#2E8B83] bg-emerald-50/60' : 'text-slate-700 hover:bg-slate-50' }}">
                            العربية (AR)
                        </a>
                        <a href="{{ route('lang.switch', ['locale' => 'en']) }}" class="block px-4 py-2 text-xs font-semibold text-decoration-none {{ app()->isLocale('en') ? 'text-[#2E8B83] bg-emerald-50/60' : 'text-slate-700 hover:bg-slate-50' }}">
                            English (EN)
                        </a>
                        <a href="{{ route('lang.switch', ['locale' => 'fr']) }}" class="block px-4 py-2 text-xs font-semibold text-decoration-none {{ app()->isLocale('fr') ? 'text-[#2E8B83] bg-emerald-50/60' : 'text-slate-700 hover:bg-slate-50' }}">
                            Français (FR)
                        </a>
                    </div>
                </div>

                <!-- Sign In Button -->
                <a href="{{ route('login.portal') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-900 font-bold text-xs hover:bg-slate-50 transition-all text-decoration-none">
                    {{ __('landing.nav.sign_in') }}
                </a>

                <!-- Primary Action Button -->
                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-lg text-xs font-extrabold text-white text-decoration-none shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5" style="background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);">
                    {{ __('landing.nav.start_trial') }}
                </a>
            </div>

            <!-- Mobile Toggle & Actions -->
            <div class="flex lg:hidden items-center gap-2">
                <!-- Mobile Lang Button -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button" class="p-2 rounded-lg border border-slate-200 text-slate-700 text-xs font-bold bg-white">
                        <i class="fas fa-globe text-[#2E8B83]"></i>
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false" class="absolute end-0 mt-2 w-32 bg-white rounded-xl shadow-xl py-2 z-50 border border-slate-100">
                        <a href="{{ route('lang.switch', ['locale' => 'ar']) }}" class="block px-3 py-1.5 text-xs text-decoration-none {{ app()->isLocale('ar') ? 'text-[#2E8B83] font-bold' : 'text-slate-700' }}">العربية</a>
                        <a href="{{ route('lang.switch', ['locale' => 'en']) }}" class="block px-3 py-1.5 text-xs text-decoration-none {{ app()->isLocale('en') ? 'text-[#2E8B83] font-bold' : 'text-slate-700' }}">English</a>
                        <a href="{{ route('lang.switch', ['locale' => 'fr']) }}" class="block px-3 py-1.5 text-xs text-decoration-none {{ app()->isLocale('fr') ? 'text-[#2E8B83] font-bold' : 'text-slate-700' }}">Français</a>
                    </div>
                </div>

                <!-- Hamburger Button -->
                <button @click="isMenuOpen = !isMenuOpen" type="button" class="p-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors" aria-label="Toggle navigation">
                    <i class="fas" :class="isMenuOpen ? 'fa-times text-lg' : 'fa-bars text-lg'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Overlay -->
    <div x-show="isMenuOpen" x-cloak x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden" @click="isMenuOpen = false"></div>

    <!-- Mobile Drawer Content -->
    <div x-show="isMenuOpen" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="fixed bottom-0 start-0 end-0 bg-white rounded-t-3xl shadow-2xl p-6 z-50 lg:hidden max-h-[85vh] overflow-y-auto border-t border-slate-200">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
            <img src="{{ asset('images/brand/logo-full.png') }}" alt="Taalimu" class="h-8 w-auto">
            <button @click="isMenuOpen = false" class="p-2 text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <nav class="flex flex-col gap-3 mb-6">
            <a href="#features" @click.prevent="scrollToSection('#features')" class="py-2.5 px-4 rounded-xl text-slate-700 font-bold text-sm hover:bg-slate-50 text-decoration-none">
                {{ __('landing.nav.features') }}
            </a>
            <a href="#qr-registration" @click.prevent="scrollToSection('#qr-registration')" class="py-2.5 px-4 rounded-xl text-slate-700 font-bold text-sm hover:bg-slate-50 text-decoration-none">
                {{ __('landing.nav.qr_registration') }}
            </a>
            <a href="#whatsapp-notifications" @click.prevent="scrollToSection('#whatsapp-notifications')" class="py-2.5 px-4 rounded-xl text-slate-700 font-bold text-sm hover:bg-slate-50 text-decoration-none">
                {{ __('landing.nav.whatsapp') }}
            </a>
            <a href="#solutions" @click.prevent="scrollToSection('#solutions')" class="py-2.5 px-4 rounded-xl text-slate-700 font-bold text-sm hover:bg-slate-50 text-decoration-none">
                {{ __('landing.nav.solutions') }}
            </a>
            <a href="#how-it-works" @click.prevent="scrollToSection('#how-it-works')" class="py-2.5 px-4 rounded-xl text-slate-700 font-bold text-sm hover:bg-slate-50 text-decoration-none">
                {{ __('landing.nav.how_it_works') }}
            </a>
            <a href="#faq" @click.prevent="scrollToSection('#faq')" class="py-2.5 px-4 rounded-xl text-slate-700 font-bold text-sm hover:bg-slate-50 text-decoration-none">
                {{ __('landing.nav.faq') }}
            </a>
        </nav>

        <div class="flex flex-col gap-3">
            <a href="{{ route('login.portal') }}" class="w-full text-center py-3 rounded-xl border border-slate-300 text-slate-900 font-bold text-sm text-decoration-none">
                {{ __('landing.nav.sign_in') }}
            </a>
            <a href="{{ route('register') }}" class="w-full text-center py-3 rounded-xl text-white font-extrabold text-sm text-decoration-none shadow-md" style="background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);">
                {{ __('landing.nav.start_trial') }}
            </a>
        </div>
    </div>
</header>