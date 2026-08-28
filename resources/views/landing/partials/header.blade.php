{{-- Minimal Clean Premium Navigation Bar --}}
<header
    class="landing-header fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-slate-100/90 shadow-[0_2px_15px_rgba(0,0,0,0.02)]"
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
    <div class="container mx-auto px-6 lg:px-12 max-w-7xl">
        <div class="flex items-center justify-between h-20">
            
            <!-- Right side: Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 text-decoration-none">
                <img src="{{ asset('images/brand/logo-full.png') }}" alt="Taalimu" class="h-8 sm:h-9 w-auto">
            </a>

            <!-- Center: Navigation Links (Desktop) -->
            <nav class="hidden lg:flex items-center gap-8 xl:gap-10">
                <a href="#hero" @click.prevent="scrollToSection('#hero')" class="text-slate-600 hover:text-[#2E8B83] font-semibold text-sm transition-colors text-decoration-none">
                    {{ __('landing.nav.product') ?? 'المنتج' }}
                </a>
                <a href="#portals" @click.prevent="scrollToSection('#portals')" class="text-slate-600 hover:text-[#2E8B83] font-semibold text-sm transition-colors text-decoration-none">
                    {{ __('landing.nav.portals') ?? 'البوابات' }}
                </a>
                <a href="#attendance" @click.prevent="scrollToSection('#attendance')" class="text-slate-600 hover:text-[#2E8B83] font-semibold text-sm transition-colors text-decoration-none">
                    {{ __('landing.nav.attendance') ?? 'الحضور الذكي' }}
                </a>
                <a href="#whatsapp" @click.prevent="scrollToSection('#whatsapp')" class="text-slate-600 hover:text-[#2E8B83] font-semibold text-sm transition-colors text-decoration-none">
                    {{ __('landing.nav.whatsapp') ?? 'إشعارات WhatsApp' }}
                </a>
                <a href="#capabilities" @click.prevent="scrollToSection('#capabilities')" class="text-slate-600 hover:text-[#2E8B83] font-semibold text-sm transition-colors text-decoration-none">
                    {{ __('landing.nav.features') ?? 'المميزات' }}
                </a>
            </nav>

            <!-- Left side: Actions (Desktop) -->
            <div class="hidden lg:flex items-center gap-4">
                <!-- Language Switcher Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button" class="flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200/80 text-slate-700 font-bold text-xs hover:border-slate-300 transition-colors bg-white shadow-xs">
                        <i class="fas fa-globe text-[#2E8B83]"></i>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <i class="fas fa-chevron-down text-[9px] text-slate-400"></i>
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false" x-transition class="absolute end-0 mt-2 w-36 bg-white rounded-2xl shadow-xl py-2 overflow-hidden z-50 border border-slate-100">
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
                <a href="{{ route('login.portal') }}" class="px-4 py-2.5 rounded-xl text-slate-700 hover:text-slate-900 font-bold text-xs hover:bg-slate-50 transition-colors text-decoration-none">
                    {{ __('landing.nav.sign_in') ?? 'تسجيل الدخول' }}
                </a>

                <!-- Primary CTA Button -->
                <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-xl text-xs font-extrabold text-white text-decoration-none shadow-sm hover:shadow-md transition-all transform hover:-translate-y-0.5 bg-[#2E8B83] hover:bg-[#25746D]">
                    {{ __('landing.nav.start_trial') ?? 'ابدأ مجانًا' }}
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex lg:hidden items-center gap-3">
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white text-decoration-none bg-[#2E8B83]">
                    {{ __('landing.nav.start_trial') ?? 'ابدأ مجانًا' }}
                </a>
                <button @click="isMenuOpen = !isMenuOpen" type="button" class="p-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50" aria-label="Toggle Navigation">
                    <i class="fas" :class="isMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Drawer Overlay -->
    <div x-show="isMenuOpen" x-cloak x-transition.opacity class="fixed inset-0 bg-slate-900/30 backdrop-blur-xs z-40 lg:hidden" @click="isMenuOpen = false"></div>

    <!-- Mobile Drawer Content -->
    <div x-show="isMenuOpen" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="fixed bottom-0 start-0 end-0 bg-white rounded-t-3xl shadow-2xl p-6 z-50 lg:hidden max-h-[85vh] overflow-y-auto border-t border-slate-100">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
            <img src="{{ asset('images/brand/logo-full.png') }}" alt="Taalimu" class="h-8 w-auto">
            <button @click="isMenuOpen = false" class="p-2 text-slate-400 hover:text-slate-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <nav class="flex flex-col space-y-3 mb-6">
            <a href="#hero" @click="scrollToSection('#hero')" class="p-3 rounded-xl font-bold text-sm text-slate-700 hover:bg-slate-50 text-decoration-none">
                {{ __('landing.nav.product') ?? 'المنتج' }}
            </a>
            <a href="#portals" @click="scrollToSection('#portals')" class="p-3 rounded-xl font-bold text-sm text-slate-700 hover:bg-slate-50 text-decoration-none">
                {{ __('landing.nav.portals') ?? 'البوابات' }}
            </a>
            <a href="#attendance" @click="scrollToSection('#attendance')" class="p-3 rounded-xl font-bold text-sm text-slate-700 hover:bg-slate-50 text-decoration-none">
                {{ __('landing.nav.attendance') ?? 'الحضور الذكي' }}
            </a>
            <a href="#whatsapp" @click="scrollToSection('#whatsapp')" class="p-3 rounded-xl font-bold text-sm text-slate-700 hover:bg-slate-50 text-decoration-none">
                {{ __('landing.nav.whatsapp') ?? 'إشعارات WhatsApp' }}
            </a>
            <a href="#capabilities" @click="scrollToSection('#capabilities')" class="p-3 rounded-xl font-bold text-sm text-slate-700 hover:bg-slate-50 text-decoration-none">
                {{ __('landing.nav.features') ?? 'المميزات' }}
            </a>
        </nav>

        <div class="pt-4 border-t border-slate-100 flex flex-col gap-3">
            <a href="{{ route('login.portal') }}" class="w-full py-3 rounded-xl border border-slate-200 text-center font-bold text-xs text-slate-700 hover:bg-slate-50 text-decoration-none">
                {{ __('landing.nav.sign_in') ?? 'تسجيل الدخول' }}
            </a>
            <a href="{{ route('register') }}" class="w-full py-3 rounded-xl text-center font-extrabold text-xs text-white text-decoration-none bg-[#2E8B83]">
                {{ __('landing.nav.start_trial') ?? 'ابدأ مجانًا' }}
            </a>
        </div>
    </div>
</header>