<footer class="v3-section bg-slate-50 dark:bg-[#0b1312] border-t border-[color:var(--color-border-subtle)] dark:border-[#182e2c] pt-14 pb-8" role="contentinfo">
    <div class="v3-container">
        {{-- Footer Main Columns --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 pb-12 border-b border-[color:var(--color-border-subtle)] dark:border-[#182e2c]">

            {{-- Column 1: Brand info --}}
            <div class="space-y-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 text-decoration-none">
                    <img src="{{ asset('images/brand/logo-full.png') }}" alt="Taalimu" class="h-8 w-auto dark:brightness-110" width="120" height="32">
                </a>
                <p class="text-xs leading-relaxed text-[color:var(--color-text-secondary)]">
                    {{ __('landing-v3.footer.tagline') }}
                </p>
                <div class="flex items-center gap-3 text-xs text-[color:var(--color-primary-600)] font-bold">
                    <i class="fas fa-headset"></i>
                    <span>{{ __('landing-v3.footer.support') }}</span>
                </div>
            </div>

            {{-- Column 2: Product --}}
            <div>
                <h4 class="text-xs font-extrabold text-[color:var(--color-text-main)] uppercase tracking-wider mb-4">{{ __('landing-v3.footer.product') }}</h4>
                <ul class="space-y-2.5 text-xs font-semibold text-[color:var(--color-text-secondary)]">
                    <li><a href="#showcase" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v3.footer.features') }}</a></li>
                    <li><a href="#pricing" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v3.footer.pricing') }}</a></li>
                    <li><a href="#teacher" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v3.footer.teacher') }}</a></li>
                    <li><a href="#center" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v3.footer.center') }}</a></li>
                </ul>
            </div>

            {{-- Column 3: Resources --}}
            <div>
                <h4 class="text-xs font-extrabold text-[color:var(--color-text-main)] uppercase tracking-wider mb-4">{{ __('landing-v3.footer.resources') }}</h4>
                <ul class="space-y-2.5 text-xs font-semibold text-[color:var(--color-text-secondary)]">
                    <li><a href="{{ route('register') }}" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v3.footer.register') }}</a></li>
                    <li><a href="{{ route('login.portal') }}" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v3.footer.login') }}</a></li>
                    <li><a href="#faq" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v3.footer.docs') }}</a></li>
                    <li><a href="#ecosystem" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v3.footer.community') }}</a></li>
                </ul>
            </div>

            {{-- Column 4: Legal --}}
            <div>
                <h4 class="text-xs font-extrabold text-[color:var(--color-text-main)] uppercase tracking-wider mb-4">{{ __('landing-v3.footer.legal') }}</h4>
                <ul class="space-y-2.5 text-xs font-semibold text-[color:var(--color-text-secondary)]">
                    <li><a href="{{ route('privacy') }}" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v3.footer.privacy') }}</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v3.footer.terms') }}</a></li>
                    <li><a href="{{ route('cookies') }}" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v3.footer.cookies') }}</a></li>
                    <li><a href="{{ route('gdpr') }}" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v3.footer.gdpr') }}</a></li>
                </ul>
            </div>

        </div>

        {{-- Footer Bottom bar --}}
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-[color:var(--color-text-muted)]">
            <p>{{ __('landing-v3.footer.copyright') }}</p>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Systems Operational</span>
                </span>
            </div>
        </div>
    </div>
</footer>