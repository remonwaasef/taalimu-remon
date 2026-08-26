<section id="cta" class="v2-section v2-final relative" aria-labelledby="v2-final-title">
    <div class="pointer-events-none absolute -top-1/2 start-1/2 -translate-x-1/2 w-[40rem] h-[20rem] rounded-full blur-3xl" style="background: radial-gradient(closest-side, rgba(79,70,229,0.25), transparent);" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -bottom-1/2 -end-20 w-80 h-80 rounded-full blur-3xl" style="background: radial-gradient(closest-side, rgba(34,197,94,0.15), transparent);" aria-hidden="true"></div>

    <div class="v2-container relative z-10">
        <div class="text-center max-w-2xl mx-auto" data-reveal>
            <h2 id="v2-final-title" class="v2-h1 mb-4">{{ __('landing-v2.final.title') }}</h2>
            <p class="v2-lead mb-7">{{ __('landing-v2.final.subtitle') }}</p>

            <a href="{{ route('register') }}" data-track="v2_final_cta_clicked" class="v2-btn v2-btn-primary w-full sm:w-auto px-8 py-4 text-lg">
                {{ __('landing-v2.final.cta') }}
                <i class="fas fa-arrow-left text-sm rtl:rotate-0 ltr:rotate-180"></i>
            </a>

            <p class="mt-6 text-xs font-bold text-[color:var(--color-primary-100)]">
                <i class="fas fa-shield-alt text-[color:var(--color-primary-300)] me-1"></i>
                {{ __('landing-v2.final.note') }}
            </p>
        </div>
    </div>
</section>