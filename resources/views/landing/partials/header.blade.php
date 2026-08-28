{{-- Minimal Clean Header Matching Image 2 --}}
<header
    class="landing-header fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-slate-100/90 shadow-[0_2px_10px_rgba(0,0,0,0.02)]"
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
>
    <div class="container mx-auto px-6 lg:px-12 max-w-7xl">
        <div class="flex items-center justify-between h-20">
            
            <!-- Right side: Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 text-decoration-none">
                <div class="w-8 h-8 rounded-lg bg-[#008A70] text-white flex items-center justify-center font-black text-base shadow-xs">
                    <i class="fas fa-graduation-cap text-sm"></i>
                </div>
                <span class="text-xl font-black text-slate-900 tracking-tight font-sans">Taalimu</span>
            </a>

            <!-- Center: Navigation Links (Desktop) -->
            <nav class="hidden lg:flex items-center gap-8 xl:gap-10">
                <a href="#capabilities" @click.prevent="scrollToSection('#capabilities')" class="text-slate-700 hover:text-[#008A70] font-bold text-sm transition-colors text-decoration-none">
                    المميزات
                </a>
                <a href="#capabilities" @click.prevent="scrollToSection('#capabilities')" class="text-slate-700 hover:text-[#008A70] font-bold text-sm transition-colors text-decoration-none">
                    الأسعار
                </a>
                <a href="#features-split" @click.prevent="scrollToSection('#features-split')" class="text-slate-700 hover:text-[#008A70] font-bold text-sm transition-colors text-decoration-none">
                    الموارد
                </a>
                <a href="#portals" @click.prevent="scrollToSection('#portals')" class="text-slate-700 hover:text-[#008A70] font-bold text-sm transition-colors text-decoration-none">
                    من نحن
                </a>
            </nav>

            <!-- Left side: Actions (Desktop) -->
            <div class="hidden lg:flex items-center gap-4">
                <!-- Sign In Link -->
                <a href="{{ route('login.portal') }}" class="px-3 py-2 text-slate-800 hover:text-[#008A70] font-bold text-sm transition-colors text-decoration-none">
                    تسجيل الدخول
                </a>

                <!-- Primary CTA Button -->
                <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-xl text-sm font-extrabold text-white text-decoration-none shadow-xs hover:shadow-md transition-all transform hover:-translate-y-0.5 bg-[#008A70] hover:bg-[#00745e]">
                    ابدأ مجاناً
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <div class="flex lg:hidden items-center gap-3">
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white text-decoration-none bg-[#008A70]">
                    ابدأ مجاناً
                </a>
                <button @click="isMenuOpen = !isMenuOpen" type="button" class="p-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50" aria-label="Toggle Navigation">
                    <i class="fas" :class="isMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Drawer Content -->
    <div x-show="isMenuOpen" x-cloak x-transition class="fixed inset-x-0 top-20 bg-white shadow-xl p-6 z-50 lg:hidden border-b border-slate-100">
        <nav class="flex flex-col space-y-3 mb-6">
            <a href="#capabilities" @click="scrollToSection('#capabilities')" class="p-3 rounded-xl font-bold text-sm text-slate-800 hover:bg-slate-50 text-decoration-none">المميزات</a>
            <a href="#capabilities" @click="scrollToSection('#capabilities')" class="p-3 rounded-xl font-bold text-sm text-slate-800 hover:bg-slate-50 text-decoration-none">الأسعار</a>
            <a href="#features-split" @click="scrollToSection('#features-split')" class="p-3 rounded-xl font-bold text-sm text-slate-800 hover:bg-slate-50 text-decoration-none">الموارد</a>
            <a href="#portals" @click="scrollToSection('#portals')" class="p-3 rounded-xl font-bold text-sm text-slate-800 hover:bg-slate-50 text-decoration-none">من نحن</a>
        </nav>
        <div class="pt-4 border-t border-slate-100 flex flex-col gap-3">
            <a href="{{ route('login.portal') }}" class="w-full py-3 rounded-xl border border-slate-200 text-center font-bold text-sm text-slate-800 text-decoration-none">تسجيل الدخول</a>
            <a href="{{ route('register') }}" class="w-full py-3 rounded-xl text-center font-extrabold text-sm text-white text-decoration-none bg-[#008A70]">ابدأ مجاناً</a>
        </div>
    </div>
</header>