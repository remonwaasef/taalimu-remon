<header
    class="fixed top-0 inset-x-0 z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-[color:var(--color-border)]"
    x-data="{ scrolled: false, open: false, go(sel) { const t = document.querySelector(sel); if (t) { const y = t.getBoundingClientRect().top + window.pageYOffset - 72; window.scrollTo({ top: y, behavior: 'smooth' }); } this.open = false; } }"
    @scroll.window="scrolled = window.pageYOffset > 16"
    @keydown.escape.window="open = false"
    :class="scrolled ? 'shadow-[0_4px_16px_rgba(15,23,42,0.06)]' : ''"
>
    <div class="v2-container">
        <div class="flex items-center justify-between h-16" :class="scrolled ? 'h-14' : 'h-16'" style="transition: var(--btn-transition);">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 text-decoration-none" aria-label="Taalimu">
                <img src="{{ asset('images/brand/logo-full.png') }}" alt="Taalimu" class="h-8 w-auto" width="120" height="32">
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-1" aria-label="{{ __('landing-v2.nav.menu') }}">
                <a href="#value" @click.prevent="go('#value')" class="px-3.5 py-2 rounded-lg text-[13px] font-bold text-[color:var(--color-text-secondary)] hover:text-[color:var(--color-primary-600)] hover:bg-[color:var(--color-primary-50)] transition-colors text-decoration-none">{{ __('landing-v2.nav.features') }}</a>
                <a href="#showcase" @click.prevent="go('#showcase')" class="px-3.5 py-2 rounded-lg text-[13px] font-bold text-[color:var(--color-text-secondary)] hover:text-[color:var(--color-primary-600)] hover:bg-[color:var(--color-primary-50)] transition-colors text-decoration-none">{{ __('landing-v2.nav.showcase') }}</a>
                <a href="#pricing" @click.prevent="go('#pricing')" class="px-3.5 py-2 rounded-lg text-[13px] font-bold text-[color:var(--color-text-secondary)] hover:text-[color:var(--color-primary-600)] hover:bg-[color:var(--color-primary-50)] transition-colors text-decoration-none">{{ __('landing-v2.nav.pricing') }}</a>
                <a href="#faq" @click.prevent="go('#faq')" class="px-3.5 py-2 rounded-lg text-[13px] font-bold text-[color:var(--color-text-secondary)] hover:text-[color:var(--color-primary-600)] hover:bg-[color:var(--color-primary-50)] transition-colors text-decoration-none">{{ __('landing-v2.nav.faq') }}</a>
            </nav>

            {{-- Actions --}}
            <div class="hidden md:flex items-center gap-2.5">
                <a href="{{ route('login.portal') }}" class="px-4 py-2 rounded-[var(--btn-radius)] text-[13px] font-bold text-[color:var(--color-text-main)] border border-[color:var(--color-border-strong)] hover:bg-[color:var(--color-surface-hover)] transition-colors text-decoration-none">
                    {{ __('landing-v2.nav.sign_in') }}
                </a>
                <a href="{{ route('register') }}" data-track="v2_nav_cta_clicked" class="v2-btn v2-btn-primary !min-h-0 !py-2 !px-4 !text-[13px]">
                    {{ __('landing-v2.nav.cta') }}
                </a>
            </div>

            {{-- Mobile --}}
            <div class="flex md:hidden items-center gap-2">
                <a href="{{ route('register') }}" data-track="v2_nav_cta_clicked" class="v2-btn v2-btn-primary !min-h-0 !py-2 !px-3.5 !text-xs">{{ __('landing-v2.nav.cta') }}</a>
                <button type="button" @click="open = !open" class="p-2 rounded-lg border border-[color:var(--color-border)] text-[color:var(--color-text-secondary)]" aria-label="{{ __('landing-v2.nav.menu') }}" :aria-expanded="open ? 'true' : 'false'">
                    <i class="fas text-base" :class="open ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile drawer --}}
    <div x-show="open" x-cloak x-transition.opacity.duration.200ms class="fixed inset-0 top-16 bg-slate-950/30 md:hidden" @click="open = false"></div>
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="absolute top-full inset-x-0 md:hidden">
        <nav class="v2-container pb-4" aria-label="{{ __('landing-v2.nav.menu') }}">
            <div class="v2-card p-2 flex flex-col">
                <a href="#value" @click.prevent="go('#value')" class="px-4 py-3 rounded-xl text-sm font-bold text-[color:var(--color-text-main)] hover:bg-[color:var(--color-surface-hover)] text-decoration-none">{{ __('landing-v2.nav.features') }}</a>
                <a href="#showcase" @click.prevent="go('#showcase')" class="px-4 py-3 rounded-xl text-sm font-bold text-[color:var(--color-text-main)] hover:bg-[color:var(--color-surface-hover)] text-decoration-none">{{ __('landing-v2.nav.showcase') }}</a>
                <a href="#pricing" @click.prevent="go('#pricing')" class="px-4 py-3 rounded-xl text-sm font-bold text-[color:var(--color-text-main)] hover:bg-[color:var(--color-surface-hover)] text-decoration-none">{{ __('landing-v2.nav.pricing') }}</a>
                <a href="#faq" @click.prevent="go('#faq')" class="px-4 py-3 rounded-xl text-sm font-bold text-[color:var(--color-text-main)] hover:bg-[color:var(--color-surface-hover)] text-decoration-none">{{ __('landing-v2.nav.faq') }}</a>
                <div class="h-px bg-[color:var(--color-border-subtle)] my-1"></div>
                <a href="{{ route('login.portal') }}" class="px-4 py-3 rounded-xl text-sm font-bold text-[color:var(--color-text-secondary)] hover:bg-[color:var(--color-surface-hover)] text-decoration-none">{{ __('landing-v2.nav.sign_in') }}</a>
            </div>
        </nav>
    </div>
</header>
