<footer class="v2-section bg-white border-t border-[color:var(--color-border-subtle)] pt-10 pb-6" role="contentinfo">
    <div class="v2-container">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-[color:var(--color-text-muted)]">
            <p>{{ __('landing-v2.footer.tagline') }}</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('privacy') }}" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v2.footer.privacy') }}</a>
                <a href="{{ route('terms') }}" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v2.footer.terms') }}</a>
                <a href="{{ route('login.portal') }}" class="hover:text-[color:var(--color-primary-600)] transition-colors text-decoration-none">{{ __('landing-v2.footer.login') }}</a>
            </div>
        </div>
        <p class="mt-4 text-[11px] font-medium text-[color:var(--color-text-muted)]">{{ __('landing-v2.footer.copyright') }}</p>
    </div>
</footer>