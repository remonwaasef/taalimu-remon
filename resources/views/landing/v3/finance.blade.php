<section id="finance" class="v3-section bg-slate-50/50 dark:bg-slate-900/30" aria-labelledby="v3-finance-title">
    <div class="v3-container">
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v3-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v3.finance.badge') }}
            </span>
            <h2 id="v3-finance-title" class="v3-h2 mb-3">{{ __('landing-v3.finance.title') }}</h2>
            <p class="v3-lead">{{ __('landing-v3.finance.subtitle') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" role="list">
            @foreach(__('landing-v3.finance.capabilities') as $index => $capability)
                <article class="v3-card v3-feature-card p-6 dark:bg-[#111f1e] dark:border-[#1f3936]" data-reveal style="--reveal-delay: {{ $index * 100 }}ms;" role="listitem">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-4 text-xl">
                        <i class="{{ $capability['icon'] }}"></i>
                    </div>

                    <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-2">{{ $capability['title'] }}</h3>

                    <p class="text-sm leading-relaxed text-[color:var(--color-text-secondary)]">{{ $capability['desc'] }}</p>
                </article>
            @endforeach
        </div>

        {{-- Financial Dashboard Preview --}}
        <div class="mt-12 lg:mt-16" data-reveal style="--reveal-delay: 200ms;">
            <div class="v3-card overflow-hidden dark:bg-[#111f1e] dark:border-[#1f3936]">
                <div class="v3-browser-bar dark:bg-[#0d1817] dark:border-[#182e2c]">
                    <div class="flex items-center gap-1.5" aria-hidden="true">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                    </div>
                    <span class="v3-url-pill dark:bg-[#0b1312] dark:border-[#1f3936] dark:text-slate-400">
                        <i class="fas fa-chart-line text-[color:var(--color-primary-600)] text-[9px]"></i>
                        finance.taalimu.com
                    </span>
                </div>

                <div class="p-6 lg:p-8">
                    {{-- KPI Cards --}}
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        @foreach([
                            ['إيرادات الشهر', '45,000 ج.م', '+12%', 'success', 'fas fa-money-bill-wave'],
                            ['متحصلات', '38,500 ج.م', '+8%', 'success', 'fas fa-hand-holding-usd'],
                            ['مستحقات', '12,300 ج.م', '-5%', 'warning', 'fas fa-clock'],
                            ['مصروفات', '15,200 ج.م', '+3%', 'info', 'fas fa-receipt'],
                        ] as [$label, $value, $change, $type, $icon])
                            <div class="p-4 rounded-2xl bg-white dark:bg-[#142524] border border-[color:var(--color-border)] dark:border-[#1f3936]">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-xs font-extrabold text-[color:var(--color-text-secondary)]">{{ $label }}</p>
                                    <span class="v3-chip v3-chip-{{ $type }} text-[10px]">{{ $change }}</span>
                                </div>
                                <p class="text-2xl font-extrabold text-[color:var(--color-text-main)]">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Chart --}}
                    <div class="h-64 bg-[color:var(--color-bg-main)] dark:bg-[#0b1312] rounded-2xl border border-[color:var(--color-border-subtle)] dark:border-[#182e2c] flex items-center justify-center relative overflow-hidden">
                        <svg viewBox="0 0 400 200" class="w-full h-full px-4 py-4" role="img" aria-label="Revenue trend chart">
                            <defs>
                                <linearGradient id="financeArea" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#2E8B83" stop-opacity="0.3"/>
                                    <stop offset="100%" stop-color="#2E8B83" stop-opacity="0"/>
                                </linearGradient>
                                <linearGradient id="financeLine" x1="0" y1="0" x2="1" y2="0">
                                    <stop offset="0%" stop-color="#2E8B83"/><stop offset="100%" stop-color="#4F7DF3"/>
                                </linearGradient>
                            </defs>
                            <path d="M20,150 L60,130 L100,140 L140,110 L180,120 L220,90 L260,100 L300,70 L340,80 L380,60" fill="none" stroke="url(#financeLine)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M20,150 L60,130 L100,140 L140,110 L180,120 L220,90 L260,100 L300,70 L340,80 L380,60 L380,180 L20,180 Z" fill="url(#financeArea)"/>
                            <circle cx="300" cy="70" r="5" fill="#2E8B83"/>
                            <circle cx="220" cy="90" r="4" fill="#4F7DF3" opacity="0.6"/>
                            <circle cx="140" cy="110" r="4" fill="#4F7DF3" opacity="0.6"/>
                        </svg>
                        <div class="absolute bottom-4 start-4 text-xs font-bold text-[color:var(--color-text-muted)]">آخر 30 يوم</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <div class="text-center mt-10" data-reveal style="--reveal-delay: 300ms;">
            <a href="{{ route('register') }}" data-track="v3_finance_cta_clicked" class="v3-btn v3-btn-primary px-8">
                {{ __('landing-v3.finance.cta') }}
                <i class="fas fa-arrow-left text-sm rtl:rotate-0 ltr:rotate-180"></i>
            </a>
        </div>
    </div>
</section>