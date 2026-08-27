<section id="comparison" class="v3-section bg-slate-50/50 dark:bg-slate-900/30" aria-labelledby="v3-comp-title">
    <div class="v3-container">
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v3-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v3.comparison.badge') }}
            </span>
            <h2 id="v3-comp-title" class="v3-h2 mb-3">{{ __('landing-v3.comparison.title') }}</h2>
        </div>

        <div class="max-w-5xl mx-auto" data-reveal style="--reveal-delay: 100ms;">
            {{-- Comparison Table --}}
            <div class="overflow-x-auto rounded-2xl border border-[color:var(--color-border)] dark:border-[#1f3936] bg-white dark:bg-[#111f1e]">
                <table class="w-full" role="table">
                    <thead>
                        <tr class="bg-[color:var(--color-secondary-50)] dark:bg-[#0b1312] border-b border-[color:var(--color-border)] dark:border-[#1f3936]">
                            <th class="p-4 lg:p-6 text-start font-extrabold text-[color:var(--color-text-main)]">المعيار</th>
                            <th class="p-4 lg:p-6 text-center font-extrabold text-red-600 dark:text-red-400">{{ __('landing-v3.comparison.without') }}</th>
                            <th class="p-4 lg:p-6 text-center font-extrabold text-emerald-600 dark:text-emerald-400">{{ __('landing-v3.comparison.with') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[color:var(--color-border-subtle)] dark:divide-[#182e2c]">
                        @foreach(__('landing-v3.comparison.without') as $index => $without)
                            <tr class="hover:bg-[color:var(--color-secondary-50)] dark:hover:bg-[#0d1817] transition-colors">
                                <td class="p-4 lg:p-6 font-bold text-[color:var(--color-text-main)]">{{ $index + 1 }}</td>
                                <td class="p-4 lg:p-6 text-center text-[color:var(--color-text-secondary)]">
                                    <span class="flex items-center justify-center gap-2">
                                        <i class="fas fa-times-circle text-red-500"></i>
                                        {{ $without }}
                                    </span>
                                </td>
                                <td class="p-4 lg:p-6 text-center text-[color:var(--color-text-secondary)]">
                                    <span class="flex items-center justify-center gap-2">
                                        <i class="fas fa-check-circle text-emerald-500"></i>
                                        {{ __('landing-v3.comparison.with')[$index] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Key Differentiators --}}
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6" role="list">
                @foreach([
                    ['fas fa-bolt', 'تنفيذ فوري', 'من التسجيل للتشغيل في دقائق، لا شهور'],
                    ['fas fa-puzzle-piece', 'كل شيء متصل', 'حضور ← مالية ← تواصل ← تقارير، نظام واحد'],
                    ['fas fa-rocket', 'مصمم للتوسع', 'من مدرس واحد لـ 1000+ طالب بسلاسة'],
                ] as $index => $item)
                    <div class="v3-card p-6 text-center dark:bg-[#111f1e] dark:border-[#1f3936]" data-reveal style="--reveal-delay: {{ $index * 100 + 200 }}ms;" role="listitem">
                        <div class="w-14 h-14 rounded-2xl bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)] flex items-center justify-center mx-auto mb-4 text-2xl">
                            <i class="{{ $item[0] }}"></i>
                        </div>
                        <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-2">{{ $item[1] }}</h3>
                        <p class="text-sm text-[color:var(--color-text-secondary)]">{{ $item[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>