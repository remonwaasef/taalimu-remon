{{-- Mobile Sticky CTA Bar — appears after hero scroll, primary conversion driver on small screens --}}
<div
    x-data="{ show: false }"
    @scroll.window.passive="show = window.pageYOffset > 520"
    x-show="show"
    x-transition.opacity.duration.200ms
    x-cloak
    class="fixed bottom-0 inset-x-0 z-40 lg:hidden"
    role="complementary"
    aria-label="{{ __('landing.cta.title') }}"
>
    <div class="bg-white/95 backdrop-blur-md border-t border-slate-200 shadow-[0_-4px_20px_rgba(15,23,42,0.08)] px-4 pt-2.5 pb-[calc(0.625rem+env(safe-area-inset-bottom))]">
        <div class="flex items-center gap-3 max-w-xl mx-auto">
            <div class="flex-1 min-w-0 leading-tight">
                <p class="text-xs font-extrabold text-slate-900 truncate">{{ __('landing.cta.mobile_title') }}</p>
                <p class="text-[10px] font-semibold text-slate-500 truncate">{{ __('landing.cta.mobile_sub') }}</p>
            </div>
            <a
                href="{{ route('register') }}"
                data-track="landing_sticky_cta_clicked"
                class="shrink-0 px-5 py-3 rounded-xl text-white font-extrabold text-sm text-decoration-none shadow-lg flex items-center justify-center gap-1.5"
                style="background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);"
            >
                {{ __('landing.nav.start_trial') }}
                <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
            </a>
        </div>
    </div>
</div>
