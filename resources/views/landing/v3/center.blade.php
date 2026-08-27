<section id="center" class="v3-section bg-slate-50/50 dark:bg-slate-900/30" aria-labelledby="v3-center-title">
    <div class="v3-container">
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v3-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v3.center.badge') }}
            </span>
            <h2 id="v3-center-title" class="v3-h2 mb-3">{{ __('landing-v3.center.title') }}</h2>
            <p class="v3-lead">{{ __('landing-v3.center.subtitle') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" role="list">
            @foreach(__('landing-v3.center.features') as $index => $feature)
                <article class="v3-card v3-feature-card p-6 dark:bg-[#111f1e] dark:border-[#1f3936]" data-reveal style="--reveal-delay: {{ $index * 80 }}ms;" role="listitem">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4 text-xl">
                        <i class="{{ $feature['icon'] }}"></i>
                    </div>

                    <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-2">{{ $feature['title'] }}</h3>

                    <p class="text-sm leading-relaxed text-[color:var(--color-text-secondary)]">{{ $feature['desc'] }}</p>
                </article>
            @endforeach
        </div>

        {{-- Center Dashboard Preview --}}
        <div class="mt-12 lg:mt-16" data-reveal style="--reveal-delay: 200ms;">
            <div class="v3-card overflow-hidden dark:bg-[#111f1e] dark:border-[#1f3936]">
                <div class="v3-browser-bar dark:bg-[#0d1817] dark:border-[#182e2c]">
                    <div class="flex items-center gap-1.5" aria-hidden="true">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                    </div>
                    <span class="v3-url-pill dark:bg-[#0b1312] dark:border-[#1f3936] dark:text-slate-400">
                        <i class="fas fa-school text-[color:var(--color-primary-600)] text-[9px]"></i>
                        center.taalimu.com
                    </span>
                </div>

                <div class="p-6 lg:p-8">
                    {{-- Top Metrics --}}
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        @foreach([
                            ['الطلاب النشطين', '1,234', '+12%', 'success', 'fas fa-user-graduate', 'primary'],
                            ['المدرسين', '28', '+3', 'success', 'fas fa-chalkboard-teacher', 'indigo'],
                            ['الإيرادات', '145,000 ج.م', '+18%', 'success', 'fas fa-wallet', 'amber'],
                            ['نسبة الحضور', '94%', '+2%', 'success', 'fas fa-chart-pie', 'sky'],
                        ] as [$label, $value, $change, $type, $icon, $color])
                            <div class="p-4 rounded-2xl bg-white dark:bg-[#142524] border border-[color:var(--color-border)] dark:border-[#1f3936]">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="w-8 h-8 rounded-lg bg-{{ $color }}-50 dark:bg-{{ $color }}-950/40 text-{{ $color }}-600 dark:text-{{ $color }}-400 flex items-center justify-center">
                                        <i class="{{ $icon }} text-sm"></i>
                                    </div>
                                    <span class="v3-chip v3-chip-{{ $type }} text-[10px]">{{ $change }}</span>
                                </div>
                                <p class="text-xs font-extrabold text-[color:var(--color-text-secondary)] mb-1">{{ $label }}</p>
                                <p class="text-2xl font-extrabold text-[color:var(--color-text-main)]">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Recent Activity + Quick Actions --}}
                    <div class="grid lg:grid-cols-2 gap-8">
                        {{-- Recent Students --}}
                        <div class="space-y-3">
                            <h4 class="font-extrabold text-[color:var(--color-text-main)] mb-3">أحدث الطلاب المسجلين</h4>
                            @foreach([
                                ['أحمد محمد', 'مجموعة الفيزياء', 'السبت 10:00', 'success'],
                                ['فاطمة علي', 'مجموعة الرياضيات', 'الأحد 12:00', 'success'],
                                ['محمد حسن', 'مجموعة الكيمياء', 'الاثنين 14:00', 'warning'],
                            ] as [$name, $group, $schedule, $status])
                                <div class="flex items-center justify-between p-3 rounded-xl bg-white dark:bg-[#142524] border border-[color:var(--color-border)] dark:border-[#1f3936]">
                                    <div class="flex items-center gap-3">
                                        <x-ui.avatar :name="{{ $name }}" size="sm" />
                                        <div>
                                            <p class="font-bold text-[color:var(--color-text-main)] text-sm truncate max-w-[150px]">{{ $name }}</p>
                                            <p class="text-xs text-[color:var(--color-text-muted)]">{{ $group }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right hidden sm:block">
                                        <p class="text-xs text-[color:var(--color-text-muted)]">{{ $schedule }}</p>
                                        <x-ui.badge variant="{{ $status }}" size="xs" dot="true">{{ $status === 'success' ? 'نشط' : 'معلق' }}</x-ui.badge>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Quick Actions --}}
                        <div class="grid grid-cols-2 gap-3">
                            @foreach([
                                ['fas fa-user-plus', 'طالب جديد', 'primary'],
                                ['fas fa-folder-plus', 'مجموعة جديدة', 'indigo'],
                                ['fas fa-qrcode', 'الحضور', 'sky'],
                                ['fas fa-receipt', 'تحصيل رسوم', 'amber'],
                                ['fas fa-users-cog', 'إدارة المدرسين', 'success'],
                                ['fas fa-chart-line', 'التقارير', 'purple'],
                            ] as [$icon, $label, $color])
                                <a href="#" class="p-4 rounded-2xl bg-white dark:bg-[#142524] border border-[color:var(--color-border)] dark:border-[#1f3936] hover:border-{{ $color }}-500/40 transition-all text-center group">
                                    <div class="w-10 h-10 rounded-xl bg-{{ $color }}-50 dark:bg-{{ $color }}-950/40 text-{{ $color }}-600 dark:text-{{ $color }}-400 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                        <i class="{{ $icon }}"></i>
                                    </div>
                                    <span class="text-xs font-bold text-[color:var(--color-text-main)] leading-tight">{{ $label }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <div class="text-center mt-10" data-reveal style="--reveal-delay: 300ms;">
            <a href="{{ route('register', ['account_type' => 'center']) }}" data-track="v3_center_cta_clicked" class="v3-btn v3-btn-primary px-8">
                {{ __('landing-v3.center.cta') }}
                <i class="fas fa-arrow-left text-sm rtl:rotate-0 ltr:rotate-180"></i>
            </a>
        </div>
    </div>
</section>