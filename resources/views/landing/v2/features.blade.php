<section id="features" class="v2-section bg-slate-50/50 dark:bg-slate-900/30 border-y border-[color:var(--color-border-subtle)]" aria-labelledby="v2-features-title">
    <div class="v2-container">
        {{-- Section Heading --}}
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v2-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v2.features_grid.badge') }}
            </span>
            <h2 id="v2-features-title" class="v2-h2 mb-3">{{ __('landing-v2.features_grid.title') }}</h2>
            <p class="v2-lead">{{ __('landing-v2.features_grid.subtitle') }}</p>
        </div>

        {{-- Features Grid (6 Cards) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            
            {{-- 1. QR Attendance --}}
            <div class="v2-card v2-feature-card p-7 flex flex-col justify-between" data-reveal style="--reveal-delay: 50ms;">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-5">
                        <div class="w-12 h-12 rounded-2xl bg-[color:var(--color-primary-50)] text-[color:var(--color-primary-600)] dark:bg-[color:var(--color-primary-light)] flex items-center justify-center text-xl">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <span class="v2-chip v2-chip-primary">{{ __('landing-v2.features_grid.items.qr_attendance.tag') }}</span>
                    </div>
                    <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-2.5">
                        {{ __('landing-v2.features_grid.items.qr_attendance.title') }}
                    </h3>
                    <p class="text-sm leading-relaxed text-[color:var(--color-text-secondary)]">
                        {{ __('landing-v2.features_grid.items.qr_attendance.desc') }}
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[color:var(--color-border-subtle)] flex items-center gap-2 text-xs font-bold text-[color:var(--color-primary-600)]">
                    <span>{{ __('landing-v2.hero.float_qr') }}</span>
                    <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180"></i>
                </div>
            </div>

            {{-- 2. WhatsApp Instant Alerts --}}
            <div class="v2-card v2-feature-card p-7 flex flex-col justify-between" data-reveal style="--reveal-delay: 100ms;">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-5">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 flex items-center justify-center text-xl">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <span class="v2-chip v2-chip-success">{{ __('landing-v2.features_grid.items.whatsapp_instant.tag') }}</span>
                    </div>
                    <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-2.5">
                        {{ __('landing-v2.features_grid.items.whatsapp_instant.title') }}
                    </h3>
                    <p class="text-sm leading-relaxed text-[color:var(--color-text-secondary)]">
                        {{ __('landing-v2.features_grid.items.whatsapp_instant.desc') }}
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[color:var(--color-border-subtle)] flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                    <span>Meta Cloud API</span>
                    <i class="fas fa-check-double text-[10px]"></i>
                </div>
            </div>

            {{-- 3. Finance & Invoicing --}}
            <div class="v2-card v2-feature-card p-7 flex flex-col justify-between" data-reveal style="--reveal-delay: 150ms;">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-5">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 flex items-center justify-center text-xl">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <span class="v2-chip v2-chip-warning">{{ __('landing-v2.features_grid.items.finance_management.tag') }}</span>
                    </div>
                    <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-2.5">
                        {{ __('landing-v2.features_grid.items.finance_management.title') }}
                    </h3>
                    <p class="text-sm leading-relaxed text-[color:var(--color-text-secondary)]">
                        {{ __('landing-v2.features_grid.items.finance_management.desc') }}
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[color:var(--color-border-subtle)] flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-400">
                    <span>{{ __('landing-v2.value.items.money.tag') }}</span>
                    <i class="fas fa-chart-pie text-[10px]"></i>
                </div>
            </div>

            {{-- 4. Digital Student Card --}}
            <div class="v2-card v2-feature-card p-7 flex flex-col justify-between" data-reveal style="--reveal-delay: 200ms;">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-5">
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 dark:bg-sky-950/40 dark:text-sky-400 flex items-center justify-center text-xl">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <span class="v2-chip v2-chip-primary">{{ __('landing-v2.features_grid.items.digital_student_card.tag') }}</span>
                    </div>
                    <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-2.5">
                        {{ __('landing-v2.features_grid.items.digital_student_card.title') }}
                    </h3>
                    <p class="text-sm leading-relaxed text-[color:var(--color-text-secondary)]">
                        {{ __('landing-v2.features_grid.items.digital_student_card.desc') }}
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[color:var(--color-border-subtle)] flex items-center gap-2 text-xs font-bold text-sky-600 dark:text-sky-400">
                    <span>PWA & Digital ID</span>
                    <i class="fas fa-mobile-screen text-[10px]"></i>
                </div>
            </div>

            {{-- 5. Smart Analytics --}}
            <div class="v2-card v2-feature-card p-7 flex flex-col justify-between" data-reveal style="--reveal-delay: 250ms;">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-5">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 flex items-center justify-center text-xl">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="v2-chip v2-chip-primary">{{ __('landing-v2.features_grid.items.smart_analytics.tag') }}</span>
                    </div>
                    <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-2.5">
                        {{ __('landing-v2.features_grid.items.smart_analytics.title') }}
                    </h3>
                    <p class="text-sm leading-relaxed text-[color:var(--color-text-secondary)]">
                        {{ __('landing-v2.features_grid.items.smart_analytics.desc') }}
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[color:var(--color-border-subtle)] flex items-center gap-2 text-xs font-bold text-indigo-600 dark:text-indigo-400">
                    <span>{{ __('landing-v2.showcase.tabs.reports') }}</span>
                    <i class="fas fa-bolt text-[10px]"></i>
                </div>
            </div>

            {{-- 6. Security & Isolation --}}
            <div class="v2-card v2-feature-card p-7 flex flex-col justify-between" data-reveal style="--reveal-delay: 300ms;">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-5">
                        <div class="w-12 h-12 rounded-2xl bg-[color:var(--color-primary-50)] text-[color:var(--color-primary-600)] dark:bg-[color:var(--color-primary-light)] flex items-center justify-center text-xl">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <span class="v2-chip v2-chip-success">{{ __('landing-v2.features_grid.items.security_roles.tag') }}</span>
                    </div>
                    <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-2.5">
                        {{ __('landing-v2.features_grid.items.security_roles.title') }}
                    </h3>
                    <p class="text-sm leading-relaxed text-[color:var(--color-text-secondary)]">
                        {{ __('landing-v2.features_grid.items.security_roles.desc') }}
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[color:var(--color-border-subtle)] flex items-center gap-2 text-xs font-bold text-[color:var(--color-primary-600)]">
                    <span>Multi-Tenancy Isolation</span>
                    <i class="fas fa-lock text-[10px]"></i>
                </div>
            </div>

        </div>
    </div>
</section>
