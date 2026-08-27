<section class="relative overflow-hidden pt-28 pb-14 lg:pt-36 lg:pb-20" aria-labelledby="v3-hero-title">
    {{-- Soft brand glow with Taalimu Teal & subtle indigo accent --}}
    <div class="pointer-events-none absolute -top-32 start-1/2 -translate-x-1/2 w-[54rem] h-[28rem] rounded-full blur-3xl opacity-70 dark:opacity-40" style="background: radial-gradient(closest-side, rgba(46,139,131,0.18), rgba(79,125,243,0.08), transparent);"></div>
    <div class="pointer-events-none absolute top-40 -end-24 w-80 h-80 rounded-full blur-3xl opacity-60 dark:opacity-30" style="background: radial-gradient(closest-side, rgba(46,139,131,0.14), transparent);"></div>

    <div class="v3-container relative z-10 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">

            {{-- Copy --}}
            <div class="text-center lg:text-start min-w-0" data-reveal>
                <span class="v3-eyebrow mb-5">
                    <span class="w-2 h-2 rounded-full bg-[color:var(--color-primary-500)] animate-pulse"></span>
                    {{ __('landing-v3.hero.eyebrow') }}
                </span>

                <h1 id="v3-hero-title" class="v3-h1 mb-5">
                    {{ __('landing-v3.hero.headline') }}
                </h1>

                <p class="v3-lead max-w-xl mx-auto lg:mx-0 mb-7">
                    {{ __('landing-v3.hero.subheadline') }}
                </p>

                {{-- Audience Selector --}}
                <div class="mb-7" data-reveal style="--reveal-delay: 100ms;">
                    <p class="text-sm font-bold text-[color:var(--color-text-secondary)] mb-3 text-center lg:text-start">
                        {{ __('landing-v3.hero.audience_question') }}
                    </p>
                    <div class="grid grid-cols-2 gap-3 max-w-md mx-auto lg:mx-0">
                        <a href="{{ route('register', ['account_type' => 'instructor']) }}"
                           class="v3-audience-card group relative p-5 rounded-2xl border border-[color:var(--color-border)] dark:border-[#1f3936] bg-white dark:bg-[#111f1e] hover:border-[color:var(--color-primary-500)] hover:shadow-lg transition-all duration-300 text-decoration-none text-center">
                            <div class="w-12 h-12 rounded-xl bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)] flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-chalkboard-user text-xl"></i>
                            </div>
                            <h3 class="font-extrabold text-[color:var(--color-text-main)] mb-1">{{ __('landing-v3.hero.audience_teacher') }}</h3>
                            <p class="text-xs font-medium text-[color:var(--color-text-muted)]">{{ __('landing-v3.hero.teacher_desc') }}</p>
                        </a>
                        <a href="{{ route('register', ['account_type' => 'center']) }}"
                           class="v3-audience-card group relative p-5 rounded-2xl border border-[color:var(--color-border)] dark:border-[#1f3936] bg-white dark:bg-[#111f1e] hover:border-[color:var(--color-primary-500)] hover:shadow-lg transition-all duration-300 text-decoration-none text-center">
                            <div class="w-12 h-12 rounded-xl bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)] flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-school text-xl"></i>
                            </div>
                            <h3 class="font-extrabold text-[color:var(--color-text-main)] mb-1">{{ __('landing-v3.hero.audience_center') }}</h3>
                            <p class="text-xs font-medium text-[color:var(--color-text-muted)]">{{ __('landing-v3.hero.center_desc') }}</p>
                        </a>
                    </div>
                </div>

                {{-- CTAs: one primary action, one low-friction --}}
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 mb-7">
                    <a href="{{ route('register') }}" data-track="v3_hero_cta_clicked" class="v3-btn v3-btn-primary w-full sm:w-auto px-7 shadow-lg shadow-[rgba(46,139,131,0.25)] hover:shadow-xl">
                        {{ __('landing-v3.hero.cta_primary') }}
                        <i class="fas fa-arrow-left text-sm rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                    <a href="#showcase" data-track="v3_hero_secondary_clicked" class="v3-btn v3-btn-ghost w-full sm:w-auto dark:bg-[#111f1e] dark:border-[#1f3936] dark:hover:bg-[#172b29]">
                        <i class="far fa-play-circle text-[color:var(--color-primary-500)]"></i>
                        {{ __('landing-v3.hero.cta_secondary') }}
                    </a>
                </div>

                {{-- Trust checks --}}
                <ul class="flex flex-wrap items-center justify-center lg:justify-start gap-x-5 gap-y-2 text-xs font-bold text-[color:var(--color-text-secondary)]">
                    <li class="flex items-center gap-1.5"><i class="fas fa-circle-check text-[color:var(--color-success-500)]"></i>{{ __('landing-v3.hero.check_trial') }}</li>
                    <li class="flex items-center gap-1.5"><i class="fas fa-circle-check text-[color:var(--color-success-500)]"></i>{{ __('landing-v3.hero.check_nocard') }}</li>
                    <li class="flex items-center gap-1.5"><i class="fas fa-circle-check text-[color:var(--color-success-500)]"></i>{{ __('landing-v3.hero.check_setup') }}</li>
                </ul>
            </div>

            {{-- Real product visual --}}
            <div class="relative min-w-0" data-reveal style="--reveal-delay: 120ms;">
                <div class="v3-product-frame">
                    <div class="v3-browser-bar">
                        <div class="flex items-center gap-1.5" aria-hidden="true">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        </div>
                        <span class="v3-url-pill dark:bg-[#0b1312] dark:border-[#1f3936] dark:text-slate-400">
                            <i class="fas fa-lock text-[color:var(--color-primary-600)] text-[9px]"></i>
                            app.taalimu.com
                        </span>
                        <span class="text-[10px] font-bold text-[color:var(--color-primary-600)] flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)] animate-pulse"></span>
                            {{ __('landing-v3.hero.live_badge') }}
                        </span>
                    </div>

                    <img
                        src="{{ asset('images/hero-dashboard.webp') }}"
                        alt="{{ __('landing-v3.hero.visual_caption') }}"
                        class="w-full h-auto aspect-[16/10] object-cover object-[center_35%] max-h-[300px] sm:max-h-[380px] lg:max-h-none lg:aspect-auto"
                        width="1024" height="1024"
                        fetchpriority="high"
                        decoding="async"
                    >
                </div>

                {{-- Floating QR micro-card (verified feature) --}}
                <div class="hidden sm:flex absolute -top-4 -start-3 lg:-start-6 items-center gap-2.5 bg-white/95 dark:bg-[#111f1e]/95 backdrop-blur rounded-2xl border border-[color:var(--color-border)] dark:border-[#1f3936] shadow-[var(--shadow-elevation-3)] px-3.5 py-2.5 -rotate-1 animate-float-slow">
                    <span class="w-9 h-9 rounded-xl bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)] flex items-center justify-center">
                        <i class="fas fa-qrcode"></i>
                    </span>
                    <span>
                        <span class="block text-xs font-extrabold text-[color:var(--color-text-main)]">{{ __('landing-v3.qr_attendance.float_qr') }}</span>
                        <span class="block text-[10px] font-semibold text-[color:var(--color-text-muted)]">{{ __('landing-v3.qr_attendance.float_qr_sub') }}</span>
                    </span>
                    <i class="fas fa-check-circle text-[color:var(--color-success-500)]"></i>
                </div>

                {{-- Floating WhatsApp micro-card (verified feature) --}}
                <div class="hidden sm:flex absolute -bottom-4 -end-2 lg:-end-5 items-center gap-2.5 bg-white/95 dark:bg-[#111f1e]/95 backdrop-blur rounded-2xl border border-[color:var(--color-success-200)] dark:border-emerald-800/40 shadow-[var(--shadow-elevation-3)] px-3.5 py-2.5 rotate-1 animate-float-reverse">
                    <span class="w-8 h-8 rounded-full bg-[color:var(--color-success-500)] text-white flex items-center justify-center text-sm">
                        <i class="fab fa-whatsapp"></i>
                    </span>
                    <span class="text-[11px] font-bold text-[color:var(--color-text-main)]">{{ __('landing-v3.whatsapp.float_whatsapp') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>