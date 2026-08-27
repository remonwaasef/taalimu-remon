<section id="cta" class="v3-section v3-final relative overflow-hidden !bg-slate-950 text-white" aria-labelledby="v3-final-title">
    <div class="pointer-events-none absolute -top-1/2 start-1/2 -translate-x-1/2 w-[44rem] h-[22rem] rounded-full blur-3xl opacity-60" style="background: radial-gradient(closest-side, rgba(46,139,131,0.45), transparent);" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -bottom-1/2 -end-20 w-80 h-80 rounded-full blur-3xl opacity-40" style="background: radial-gradient(closest-side, rgba(34,197,94,0.3), transparent);" aria-hidden="true"></div>

    <div class="v3-container relative z-10">
        <div class="text-center max-w-2xl mx-auto" data-reveal>
            <h2 id="v3-final-title" class="v3-h1 mb-4 text-white font-extrabold">{{ __('landing-v3.final_cta.title') }}</h2>
            <p class="v3-lead mb-8 text-slate-300 text-base sm:text-lg">{{ __('landing-v3.final_cta.subtitle') }}</p>

            <a href="{{ route('register') }}" data-track="v3_final_cta_clicked" class="v3-btn v3-btn-primary w-full sm:w-auto px-9 py-4 text-base sm:text-lg shadow-xl shadow-[rgba(46,139,131,0.4)] hover:shadow-2xl">
                {{ __('landing-v3.final_cta.cta_primary') }}
                <i class="fas fa-arrow-left text-sm rtl:rotate-0 ltr:rotate-180"></i>
            </a>

            <p class="mt-6 text-xs font-bold text-slate-400">
                <i class="fas fa-shield-alt text-[color:var(--color-primary-300)] me-1"></i>
                {{ __('landing-v3.final_cta.note') }}
            </p>
        </div>
    </div>
</section>