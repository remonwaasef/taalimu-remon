<section id="trust" class="v3-section bg-white dark:bg-[#111f1e] border-y border-[color:var(--color-border-subtle)] dark:border-[#182e2c]" aria-labelledby="v3-trust-title">
    <div class="v3-container">
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v3-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v3.trust.badge') }}
            </span>
            <h2 id="v3-trust-title" class="v3-h2 mb-3">{{ __('landing-v3.trust.title') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" role="list">
            @foreach(__('landing-v3.trust.items') as $index => $item)
                <article class="v3-card v3-feature-card p-6 dark:bg-[#111f1e] dark:border-[#1f3936]" data-reveal style="--reveal-delay: {{ $index * 100 }}ms;" role="listitem">
                    <div class="w-12 h-12 rounded-2xl bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)] flex items-center justify-center mb-4 text-xl">
                        <i class="{{ $item['icon'] }}"></i>
                    </div>

                    <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-2">{{ $item['title'] }}</h3>

                    <p class="text-sm leading-relaxed text-[color:var(--color-text-secondary)]">{{ $item['desc'] }}</p>
                </article>
            @endforeach
        </div>

        {{-- Compliance Badges --}}
        <div class="mt-12 text-center" data-reveal style="--reveal-delay: 200ms;">
            <p class="text-sm font-bold text-[color:var(--color-text-secondary)] mb-4">معايير الامتثال والأمان</p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                @foreach(['GDPR Ready', 'ISO 27001 Aligned', 'SOC 2 Type II', 'PCI DSS Compliant', 'HTTPS Everywhere', '99.9% SLA'] as $badge)
                    <span class="px-4 py-2 rounded-full bg-[color:var(--color-secondary-100)] dark:bg-slate-800 border border-[color:var(--color-border)] dark:border-[#1f3936] text-xs font-extrabold text-[color:var(--color-text-secondary)]">{{ $badge }}</span>
                @endforeach
            </div>
        </div>
    </div>
</section>