<header 
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
    x-data="{ 
        scrolled: false,
        isMenuOpen: false
    }"
    @scroll.window="scrolled = window.pageYOffset > 20"
    :class="scrolled ? 'bg-white/80 backdrop-blur-xl border-b border-slate-200/50 py-3 shadow-sm' : 'bg-transparent py-5'"
>
    <div class="container mx-auto px-4 lg:px-12">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/brand/logo-full.png?v=3') }}" alt="Logo" class="h-8 lg:h-10 w-auto group-hover:scale-105 transition-transform">
                <div class="hidden sm:flex flex-col">
                    <span class="font-black text-lg text-slate-900 leading-tight tracking-tight">
                        {{ \App\Models\SiteSetting::get('site_name', 'Taalimu') }}
                    </span>
                </div>
            </a>

            <!-- Nav Links -->
            <nav class="hidden lg:flex items-center gap-8">
                @foreach(['features', 'pricing', 'faq'] as $nav)
                <a href="#{{$nav}}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                    {{ __("landing.nav.$nav") }}
                </a>
                @endforeach
            </nav>

            <!-- Actions -->
            <div class="hidden lg:flex items-center gap-4">
                <!-- Lang -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors px-3 py-2 rounded-lg hover:bg-slate-100">
                        <i class="fas fa-globe text-xs"></i>
                        {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false" x-transition class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-2xl py-2 overflow-hidden z-50 border border-slate-200">
                        @foreach(['ar' => 'العربية', 'fr' => 'Français'] as $code => $label)
                        <a href="{{ route('lang.switch', ['locale' => $code]) }}" class="block px-4 py-2.5 text-sm @if(app()->isLocale($code)) text-emerald-600 font-bold @else text-slate-600 hover:bg-slate-50 @endif">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('login.portal') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                    {{ __('landing.nav.sign_in') }}
                </a>
                
                <a href="{{ route('register') }}" class="bg-emerald-500 hover:bg-emerald-400 text-white px-6 py-2.5 rounded-full font-bold text-sm transition-all hover:scale-105 shadow-lg shadow-emerald-500/20">
                    {{ __('landing.nav.start_trial') }}
                </a>
            </div>

            <!-- Mobile Actions -->
            <div class="flex lg:hidden items-center gap-1 sm:gap-2">
                <!-- Mobile Lang -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors px-2 py-2 rounded-lg hover:bg-slate-100">
                        <i class="fas fa-globe"></i>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false" x-transition class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-2xl py-2 overflow-hidden z-50 border border-slate-200">
                        @foreach(['ar' => 'العربية', 'fr' => 'Français'] as $code => $label)
                        <a href="{{ route('lang.switch', ['locale' => $code]) }}" class="block px-4 py-2.5 text-sm @if(app()->isLocale($code)) text-emerald-600 font-bold @else text-slate-600 hover:bg-slate-50 @endif">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <button @click="isMenuOpen = !isMenuOpen" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center text-slate-900 rounded-lg hover:bg-slate-100 transition-colors">
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
        class="lg:hidden absolute top-full left-0 right-0 bg-white border-b border-slate-200 shadow-xl py-6 px-4 z-40"
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
