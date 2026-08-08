<header
    class="landing-header fixed top-0 left-0 right-0"
    style="background-color: #ffffff !important; background: #ffffff !important; opacity: 1 !important; border-bottom: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.06); z-index: 99999 !important;"
    x-data="{
        scrolled: false,
        isMenuOpen: false
    }"
    @scroll.window="scrolled = window.pageYOffset > 20"
>
    <div class="container mx-auto px-4 lg:px-12">
        <div class="flex items-center justify-between gap-4">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 lg:gap-3 group shrink-0">
                <img src="{{ asset('images/brand/logo-full.png?v=3') }}" alt="Logo" class="h-6 sm:h-7 lg:h-8 w-auto group-hover:scale-105 transition-transform">
                <div class="hidden md:flex flex-col">
                    <span style="color:#0f172a; font-weight:900; font-size:1.125rem; letter-spacing:-0.025em;">
                        {{ \App\Models\SiteSetting::get('site_name', 'Taalimu') }}
                    </span>
                </div>
            </a>

            <!-- Nav Links (Desktop) -->
            <nav class="hidden lg:flex items-center gap-6 xl:gap-8">
                @foreach(['features', 'pricing', 'faq'] as $nav)
                <a href="#{{$nav}}" class="nav-link" style="color:#334155; font-weight:600; font-size:0.875rem; transition:color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#334155'">
                    {{ __("landing.nav.$nav") }}
                </a>
                @endforeach
                <a href="{{ route('login.portal') }}" style="color:#2E8B83; font-weight:700; font-size:0.875rem; background:#E6F4F3; border:1px solid #B2DDD9; padding:0.5rem 1rem; border-radius:0.75rem; display:inline-flex; align-items:center; gap:0.5rem; transition:all 0.2s;" onmouseover="this.style.background='#CCE9E7'" onmouseout="this.style.background='#E6F4F3'">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>{{ __('landing.nav.sign_in') }}</span>
                </a>
            </nav>

            <!-- Actions (Desktop) -->
            <div class="hidden lg:flex items-center gap-3 xl:gap-4">
                <!-- Lang -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" style="color:#334155; font-weight:600; font-size:0.875rem; display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem; border-radius:0.75rem; border:1px solid #e2e8f0; transition:all 0.2s; background:transparent;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-globe" style="color:#2E8B83;"></i>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                    </button>
                    <div x-show="open" style="display: none;" x-cloak @click.away="open = false" x-transition class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-2xl py-2 overflow-hidden z-50 border border-slate-200">
                        @foreach(['ar' => 'العربية'] as $code => $label)
                        <a href="{{ route('lang.switch', ['locale' => $code]) }}" style="display:block; padding:0.625rem 1rem; font-size:0.875rem; {{ app()->isLocale($code) ? 'color:#2E8B83; font-weight:700; background:#E6F4F3;' : 'color:#334155;' }} transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='{{ app()->isLocale($code) ? '#E6F4F3' : 'transparent' }}'">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('register') }}" class="btn-landing-primary" style="padding:0.625rem 1.5rem; font-size:0.875rem; border-radius:0.75rem; box-shadow:0 4px 14px rgba(46,139,131,0.25);">
                    {{ __('landing.nav.start_trial') }}
                </a>
            </div>

            <!-- Mobile Actions -->
            <div class="flex lg:hidden items-center gap-1 sm:gap-2">
                <!-- Mobile Lang -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" style="color:#334155; font-weight:600; font-size:0.75rem; display:flex; align-items:center; gap:0.375rem; padding:0.5rem; border-radius:0.5rem;">
                        <i class="fas fa-globe"></i>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                    </button>
                    <div x-show="open" style="display: none;" x-cloak @click.away="open = false" x-transition class="absolute right-0 mt-2 w-32 bg-white rounded-xl shadow-2xl py-2 overflow-hidden z-50 border border-slate-200">
                        @foreach(['ar' => 'العربية'] as $code => $label)
                        <a href="{{ route('lang.switch', ['locale' => $code]) }}" style="display:block; padding:0.5rem 0.75rem; font-size:0.75rem; {{ app()->isLocale($code) ? 'color:#2E8B83; font-weight:700;' : 'color:#334155;' }}">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <button @click="isMenuOpen = !isMenuOpen" style="width:2.5rem; height:2.5rem; display:flex; align-items:center; justify-content:center; color:#0f172a; border-radius:0.5rem;">
                    <i class="fas" :class="isMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div
        x-show="isMenuOpen" style="display: none;" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="lg:hidden absolute top-full left-0 right-0 bg-white border-b border-slate-200 shadow-xl py-6 px-4 z-40"
    >
        <nav class="flex flex-col gap-4">
            @foreach(['features', 'pricing', 'faq'] as $nav)
            <a href="#{{$nav}}" @click="isMenuOpen = false" style="color:#0f172a; font-weight:600; font-size:1.125rem; padding:0.5rem 0;">
                {{ __("landing.nav.$nav") }}
            </a>
            @endforeach
            <div style="height:1px; background:#e2e8f0; margin:0.5rem 0;"></div>
            <a href="{{ route('login.portal') }}" style="color:#0f172a; font-weight:600; font-size:1.125rem;">{{ __('landing.nav.sign_in') }}</a>
            <a href="{{ route('register') }}" class="btn-landing-primary" style="text-align:center; border-radius:0.75rem;">
                {{ __('landing.nav.start_trial') }}
            </a>
        </nav>
    </div>
</header>
