{{-- Mobile sticky CTA — appears after hero, consent-gated tracking --}}
<div
    x-data="{ show: false }"
    @scroll.window.passive="show = window.pageYOffset > 420"
    x-show="show"
    x-transition.opacity.duration.200ms
    x-cloak
    class="v3-sticky-bar fixed bottom-0 inset-x-0 z-40 lg:hidden dark:bg-[#111f1e]/95 dark:border-[#1f3936]"
    role="complementary"
    aria-label="{{ __('landing-v3.hero.cta_primary') }}"
>
    <div class="v3-container px-4">
        <div class="flex items-center gap-3 max-w-xl mx-auto py-3">
            <div class="flex-1 min-w-0">
                <p class="text-[11px] font-extrabold text-[color:var(--color-text-main)] truncate">{{ __('landing-v3.hero.cta_primary') }}</p>
                <p class="text-[10px] font-semibold text-[color:var(--color-text-muted)] truncate">{{ __('landing-v3.final_cta.note') }}</p>
            </div>
            <a href="{{ route('register') }}" data-track="v3_sticky_cta_clicked"
               class="v3-btn v3-btn-primary shrink-0 !min-h-0 !py-2.5 !px-5 !text-sm shadow-md" style="min-width: 8.5rem;">
                {{ __('landing-v3.hero.cta_primary') }}
                <i class="fas fa-arrow-left text-[11px] rtl:rotate-0 ltr:rotate-180"></i>
            </a>
        </div>
    </div>
</div>