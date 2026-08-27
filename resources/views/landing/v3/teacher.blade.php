<section id="teacher" class="v3-section bg-white dark:bg-[#111f1e] border-y border-[color:var(--color-border-subtle)] dark:border-[#182e2c]" aria-labelledby="v3-teacher-title">
    <div class="v3-container">
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v3-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v3.teacher.badge') }}
            </span>
            <h2 id="v3-teacher-title" class="v3-h2 mb-3">{{ __('landing-v3.teacher.title') }}</h2>
            <p class="v3-lead">{{ __('landing-v3.teacher.subtitle') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" role="list">
            @foreach(__('landing-v3.teacher.features') as $index => $feature)
                <article class="v3-card v3-feature-card p-6 dark:bg-[#111f1e] dark:border-[#1f3936]" data-reveal style="--reveal-delay: {{ $index * 100 }}ms;" role="listitem">
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 flex items-center justify-center mb-4 text-xl">
                        <i class="{{ $feature['icon'] }}"></i>
                    </div>

                    <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-2">{{ $feature['title'] }}</h3>

                    <p class="text-sm leading-relaxed text-[color:var(--color-text-secondary)]">{{ $feature['desc'] }}</p>
                </article>
            @endforeach
        </div>

        {{-- Teacher Dashboard Preview --}}
        <div class="mt-12 lg:mt-16" data-reveal style="--reveal-delay: 200ms;">
            <div class="v3-card overflow-hidden dark:bg-[#111f1e] dark:border-[#1f3936]">
                <div class="v3-browser-bar dark:bg-[#0d1817] dark:border-[#182e2c]">
                    <div class="flex items-center gap-1.5" aria-hidden="true">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                    </div>
                    <span class="v3-url-pill dark:bg-[#0b1312] dark:border-[#1f3936] dark:text-slate-400">
                        <i class="fas fa-chalkboard-user text-[color:var(--color-primary-600)] text-[9px]"></i>
                        instructor.taalimu.com
                    </span>
                </div>

                <div class="p-6 lg:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        @foreach([
                            ['طلابي', '47', 'fas fa-user-graduate', 'primary'],
                            ['مجموعاتي', '3', 'fas fa-users', 'success'],
                            ['أرباح الشهر', '12,500 ج.م', 'fas fa-money-bill-wave', 'amber'],
                        ] as [$label, $value, $icon, $color])
                            <div class="p-4 rounded-2xl bg-white dark:bg-[#142524] border border-[color:var(--color-border)] dark:border-[#1f3936] text-center">
                                <div class="w-10 h-10 rounded-xl bg-{{ $color }}-50 dark:bg-{{ $color }}-950/40 text-{{ $color }}-600 dark:text-{{ $color }}-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                    <i class="{{ $icon }}"></i>
                                </div>
                                <p class="text-xs font-extrabold text-[color:var(--color-text-secondary)] mb-1">{{ $label }}</p>
                                <p class="text-2xl font-extrabold text-[color:var(--color-text-main)]">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Today's Schedule --}}
                    <div class="space-y-3">
                        @foreach([
                            ['10:00 - 11:30', 'فيزياء - مجموعة السبت', 'قاعة 1', 'primary'],
                            ['12:00 - 13:30', 'كيمياء - مجموعة الأحد', 'قاعة 2', 'success'],
                            ['14:00 - 15:30', 'رياضيات - مجموعة الاثنين', 'قاعة 1', 'sky'],
                        ] as [$time, $course, $room, $color])
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-white dark:bg-[#142524] border border-[color:var(--color-border)] dark:border-[#1f3936]">
                                <div class="w-20 h-20 rounded-xl bg-{{ $color }}-50 dark:bg-{{ $color }}-950/40 text-{{ $color }}-600 dark:text-{{ $color }}-400 flex flex-col items-center justify-center text-center shrink-0">
                                    <span class="text-sm font-extrabold">{{ $time }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-[color:var(--color-text-main)] truncate">{{ $course }}</h4>
                                    <p class="text-xs text-[color:var(--color-text-muted)]">{{ $room }}</p>
                                </div>
                                <a href="#" class="v3-btn v3-btn-primary text-xs px-3">QR Scanner</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <div class="text-center mt-10" data-reveal style="--reveal-delay: 300ms;">
            <a href="{{ route('register', ['account_type' => 'instructor']) }}" data-track="v3_teacher_cta_clicked" class="v3-btn v3-btn-primary px-8">
                {{ __('landing-v3.teacher.cta') }}
                <i class="fas fa-arrow-left text-sm rtl:rotate-0 ltr:rotate-180"></i>
            </a>
        </div>
    </div>
</section>