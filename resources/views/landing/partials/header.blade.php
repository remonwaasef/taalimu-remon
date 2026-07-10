<header 
    class="fixed top-0 left-0 right-0 z-[100] transition-all duration-300"
    x-data="{ 
        scrolled: false,
        isMenuOpen: false
    }"
    @scroll.window="scrolled = window.pageYOffset > 20"
    @resize.window="isMenuOpen = false"
    :class="scrolled ? 'bg-white border-b border-slate-200/50 py-2 shadow-md' : 'bg-transparent py-4'"
>
    <div class="container mx-auto px-4 lg:px-12">
        <div class="flex items-center justify-between gap-4">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 lg:gap-3 group shrink-0">
                <img src="{{ asset('images/brand/logo-full.png?v=3') }}" alt="Logo" class="h-6 sm:h-7 lg:h-8 w-auto group-hover:scale-105 transition-transform">
                <div class="hidden md:flex flex-col">
                    <span class="font-black text-sm lg:text-lg text-slate-900 leading-tight tracking-tight whitespace-nowrap">
                        {{ $siteSettings['site_name'] ?? 'Taalimu' }}
                    </span>
                </div>
            </a>

            <!-- Nav Links (Desktop) -->
            <nav class="hidden lg:flex items-center gap-6 xl:gap-8">
                @foreach(['features', 'pricing', 'faq'] as $nav)
                <a href="#{{$nav}}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors whitespace-nowrap">
                    {{ __("landing.nav.$nav") }}
                </a>
                @endforeach
            </nav>

            <!-- Actions (Desktop) -->
            <div class="hidden lg:flex items-center gap-3 xl:gap-4">
                <!-- Lang -->
                <div x-data="{ open: false }" @resize.window="open = false" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors px-3 py-2 rounded-lg hover:bg-slate-100">
                        <i class="fas fa-globe text-xs"></i>
                        {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false" x-transition :class="{'hidden': !open}" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-2xl py-2 overflow-hidden z-50 border border-slate-200">
                        @foreach(['ar' => 'العربية', 'fr' => 'Français'] as $code => $label)
                        <a href="{{ route('lang.switch', ['locale' => $code]) }}" class="block px-4 py-2.5 text-xs @if(app()->isLocale($code)) text-emerald-600 font-bold @else text-slate-600 hover:bg-slate-50 @endif">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('login.portal') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors whitespace-nowrap">
                    {{ __('landing.nav.sign_in') }}
                </a>
                
                <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center bg-slate-900 text-white px-5 py-2 lg:px-6 lg:py-2 rounded-lg font-bold text-xs transition-all hover:bg-slate-800 shadow-xl shadow-slate-900/10 hover:-translate-y-1 active:scale-95 overflow-hidden">
                    {{ __('landing.nav.start_trial') }}
                </a>
            </div>

            <!-- Mobile Actions -->
            <div class="flex lg:hidden items-center gap-1 sm:gap-2">
                <!-- Mobile Lang -->
                <div x-data="{ open: false }" @resize.window="open = false" class="relative">
                    <button @click="open = !open" class="flex items-center gap-1.5 text-[10px] sm:text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors px-2 py-2 rounded-lg hover:bg-slate-100">
                        <i class="fas fa-globe"></i>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false" x-transition :class="{'hidden': !open}" class="hidden absolute right-0 mt-2 w-32 bg-white rounded-xl shadow-2xl py-2 overflow-hidden z-50 border border-slate-200">
                        @foreach(['ar' => 'العربية', 'fr' => 'Français'] as $code => $label)
                        <a href="{{ route('lang.switch', ['locale' => $code]) }}" class="block px-3 py-2 text-xs @if(app()->isLocale($code)) text-emerald-600 font-bold @else text-slate-600 @endif">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <button @click="isMenuOpen = !isMenuOpen" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-slate-900 rounded-lg hover:bg-slate-100 transition-colors">
                    <i class="fas" :class="isMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div 
        x-show="isMenuOpen" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        :class="{'hidden': !isMenuOpen}"
        class="hidden lg:hidden absolute top-full left-0 right-0 bg-white border-b border-slate-200 shadow-xl py-6 px-4 z-40"
    >
        <nav class="flex flex-col gap-4">
            @foreach(['features', 'pricing', 'faq'] as $nav)
            <a href="#{{$nav}}" @click="isMenuOpen = false" class="text-lg font-semibold text-slate-600 hover:text-slate-900 py-2">
                {{ __("landing.nav.$nav") }}
            </a>
            @endforeach
            <div class="h-px bg-slate-100 my-2"></div>
            <a href="{{ route('login.portal') }}" class="text-lg font-semibold text-slate-900">{{ __('landing.nav.sign_in') }}</a>
            <a href="{{ route('register') }}" class="bg-emerald-500 text-white text-center py-4 rounded-xl font-bold shadow-lg shadow-emerald-500/20">
                {{ __('landing.nav.start_trial') }}
            </a>
        </nav>
    </div>
</header>
