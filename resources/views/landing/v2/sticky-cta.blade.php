{{-- Mobile sticky CTA — appears after hero, consent-gated tracking --}}
<div
    x-data="{ show: false }"
    @scroll.window.passive="show = window.pageYOffset > 420"
    x-show="show"
    x-transition.opacity.duration.200ms
    x-cloak
    class="v2-sticky-bar fixed bottom-0 inset-x-0 z-40 lg:hidden"
    role="complementary"
    aria-label="{{ __('landing-v2.hero.cta_primary') }}"
>
    <div class="v2-container px-4">
        <div class="flex items-center gap-3 max-w-xl mx-auto py-3">
            <div class="flex-1 min-w-0">
                <p class="text-[11px] font-extrabold text-[color:var(--color-text-main)] truncate">{{ __('landing-v2.hero.cta_primary') }}</p>
                <p class="text-[10px] font-semibold text-[color:var(--color-text-muted)] truncate">{{ __('landing-v2.final.note') }}</p>
            </div>
            <a href="{{ route('register') }}" data-track="v2_sticky_cta_clicked"
               class="v2-btn v2-btn-primary shrink-0 !min-h-0 !py-2.5 !px-5 !text-sm" style="min-width: 9rem;">
                {{ __('landing-v2.hero.cta_primary') }}
                <i class="fas fa-arrow-left text-[11px] rtl:rotate-0 ltr:rotate-180"></i>
            </a>
        </div>
    </div>
</div>