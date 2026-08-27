<section id="problem" class="v3-section bg-slate-50/50 dark:bg-slate-900/30 border-y border-[color:var(--color-border-subtle)]" aria-labelledby="v3-problem-title">
    <div class="v3-container">
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v3-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v3.problem.badge') }}
            </span>
            <h2 id="v3-problem-title" class="v3-h2 mb-3">{{ __('landing-v3.problem.before_title') }} → {{ __('landing-v3.problem.after_title') }}</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            {{-- Before --}}
            <article class="v3-card p-7 lg:p-8 dark:bg-[#111f1e] dark:border-[#1f3936] relative overflow-hidden" data-reveal>
                <div class="pointer-events-none absolute -top-20 -end-20 w-64 h-64 rounded-full blur-3xl opacity-40 dark:opacity-20" style="background: radial-gradient(closest-side, rgba(239, 68, 68, 0.15), transparent);" aria-hidden="true"></div>
                <span class="v3-chip v3-chip-error self-start mb-5">{{ __('landing-v3.problem.before_title') }}</span>
                <h3 class="text-xl font-extrabold text-[color:var(--color-text-main)] mb-5">فوضى يومية تستهلك وقتك وطاقتك</h3>

                <ul class="space-y-3" role="list">
                    @foreach(__('landing-v3.problem.before_items') as $item)
                        <li class="flex items-start gap-3 text-sm font-medium text-[color:var(--color-text-secondary)]" role="listitem">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 flex items-center justify-center text-[10px] mt-0.5">
                                <i class="fas fa-times"></i>
                            </span>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </article>

            {{-- After --}}
            <article class="v3-card p-7 lg:p-8 border-2 border-[color:var(--color-primary-500)] dark:bg-[#111f1e] dark:border-[color:var(--color-primary-500)] relative overflow-hidden" data-reveal style="--reveal-delay: 100ms;">
                <div class="pointer-events-none absolute -top-20 -end-20 w-64 h-64 rounded-full blur-3xl opacity-40 dark:opacity-20" style="background: radial-gradient(closest-side, rgba(46, 139, 131, 0.2), transparent);" aria-hidden="true"></div>
                <span class="v3-chip v3-chip-success self-start mb-5">{{ __('landing-v3.problem.after_title') }}</span>
                <h3 class="text-xl font-extrabold text-[color:var(--color-text-main)] mb-5">نظام موحد يربط كل شيء في مكان واحد</h3>

                <p class="text-base leading-relaxed text-[color:var(--color-text-secondary)] mb-6">
                    {{ __('landing-v3.problem.after_desc') }}
                </p>

                <div class="grid grid-cols-2 gap-3" role="list">
                    @foreach([
                        ['icon' => 'fas fa-link', 'text' => 'حضور ↔ مالية'],
                        ['icon' => 'fas fa-bolt', 'text' => 'آلي فوري'],
                        ['icon' => 'fas fa-shield', 'text' => 'آمن معزول'],
                        ['icon' => 'fas fa-chart-line', 'text' => 'تقارير حية'],
                    ] as $item)
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] border border-[color:var(--color-primary-100)] dark:border-[color:var(--color-primary-800)]" role="listitem">
                            <span class="w-8 h-8 rounded-lg bg-[color:var(--color-primary-500)] text-white flex items-center justify-center text-sm">
                                <i class="{{ $item['icon'] }}"></i>
                            </span>
                            <span class="text-sm font-bold text-[color:var(--color-primary-700)] dark:text-[color:var(--color-primary-300)]">{{ $item['text'] }}</span>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>
    </div>
</section>