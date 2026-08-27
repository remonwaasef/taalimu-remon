<section id="showcase" class="v3-section bg-white dark:bg-[#111f1e] border-y border-[color:var(--color-border-subtle)] dark:border-[#182e2c]" aria-labelledby="v3-showcase-title">
    <div class="v3-container">
        <div class="text-center max-w-2xl mx-auto mb-10 lg:mb-12" data-reveal>
            <span class="v3-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v3.showcase.badge') }}
            </span>
            <h2 id="v3-showcase-title" class="v3-h2 mb-3">{{ __('landing-v3.showcase.title') }}</h2>
            <p class="v3-lead">{{ __('landing-v3.showcase.subtitle') }}</p>
        </div>

        <div x-data="{ tab: 'dashboard' }" data-reveal style="--reveal-delay: 100ms;">
            {{-- Tabs --}}
            <div class="flex items-center gap-1.5 overflow-x-auto justify-start sm:justify-center p-1.5 rounded-[var(--card-radius)] bg-[color:var(--color-secondary-50)] dark:bg-[#0b1312] border border-[color:var(--color-border-subtle)] dark:border-[#1f3936] max-w-full sm:max-w-3xl mx-auto mb-8 lg:mb-10"
                 role="tablist" aria-label="{{ __('landing-v3.showcase.badge') }}">
                @foreach([
                    'dashboard' => __('landing-v3.showcase.tabs.dashboard'),
                    'students' => __('landing-v3.showcase.tabs.students'),
                    'finance' => __('landing-v3.showcase.tabs.finance'),
                    'reports' => __('landing-v3.showcase.tabs.reports'),
                    'whatsapp' => __('landing-v3.showcase.tabs.whatsapp'),
                    'teacher' => __('landing-v3.showcase.tabs.teacher'),
                ] as $key => $label)
                    <button type="button"
                            role="tab"
                            class="v3-tab dark:hover:bg-[#172b29]"
                            :aria-selected="tab === '{{ $key }}' ? 'true' : 'false'"
                            @click="tab = '{{ $key }}'">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="max-w-5xl mx-auto">
                {{-- Tab: real dashboard image --}}
                <div x-show="tab === 'dashboard'" x-transition.opacity role="tabpanel" aria-label="{{ __('landing-v3.showcase.tabs.dashboard') }}">
                    <figure class="v3-product-frame">
                        <img src="{{ asset('images/hero-dashboard.webp') }}"
                             alt="{{ __('landing-v3.showcase.dashboard_desc') }}"
                             class="w-full h-auto object-cover aspect-[16/9] object-[center_20%] sm:aspect-auto"
                             width="1024" height="1024" loading="lazy" decoding="async">
                        <figcaption class="p-5 lg:p-6 bg-white dark:bg-[#142524] border-t border-[color:var(--color-border-subtle)] dark:border-[#182e2c]">
                            <h3 class="text-base font-extrabold text-[color:var(--color-text-main)] mb-1">{{ __('landing-v3.showcase.dashboard_title') }}</h3>
                            <p class="text-xs font-medium text-[color:var(--color-text-secondary)]">{{ __('landing-v3.showcase.dashboard_desc') }}</p>
                        </figcaption>
                    </figure>
                </div>

                {{-- Tab: students table --}}
                <div x-show="tab === 'students'" x-cloak x-transition.opacity role="tabpanel" aria-label="{{ __('landing-v3.showcase.tabs.students') }}">
                    <div class="v3-preview dark:bg-[#0b1312]">
                        <div class="v3-preview-toolbar dark:bg-[#142524] dark:border-[#182e2c]">
                            <span class="text-sm font-extrabold text-[color:var(--color-text-main)]">{{ __('landing-v3.showcase.students_title') }}</span>
                            <span class="v3-chip v3-chip-primary"><i class="fas fa-plus text-[8px]"></i>+</span>
                        </div>
                        <div class="divide-y divide-[color:var(--color-border-subtle)] dark:divide-[#182e2c]">
                            @foreach([
                                ['أحمد علي', 'A', 'مجموعة الفيزياء', 90, 'success'],
                                ['فاطمة أحمد', 'ف', 'مجموعة الرياضيات', 75, 'success'],
                                ['محمد خالد', 'م', 'مجموعة الكيمياء', 60, 'warning'],
                                ['سارة يوسف', 'س', 'مجموعة الفيزياء', 92, 'success'],
                            ] as [$name, $initial, $group, $pct, $state])
                                <div class="flex items-center gap-3 px-4 lg:px-6 py-3.5 bg-white dark:bg-[#111f1e]">
                                    <span class="v3-avatar {{ $state === 'warning' ? 'bg-amber-500' : 'bg-[color:var(--color-primary-500)]' }}">{{ $initial }}</span>
                                    <span class="flex-1 min-w-0">
                                        <span class="block text-[13px] font-extrabold text-[color:var(--color-text-main)] truncate" dir="auto">{{ $name }}</span>
                                        <span class="block text-[10px] font-semibold text-[color:var(--color-text-muted)]">{{ $group }}</span>
                                    </span>
                                    <span class="hidden sm:flex items-center gap-2 w-32" aria-hidden="true">
                                        <span class="flex-1 h-1.5 rounded-full bg-[color:var(--color-secondary-100)] dark:bg-slate-800 overflow-hidden">
                                            <span class="block h-full rounded-full {{ $state === 'warning' ? 'bg-amber-400' : 'bg-[color:var(--color-primary-500)]' }}" style="width: {{ $pct }}%;"></span>
                                        </span>
                                        <span class="text-[11px] font-extrabold text-[color:var(--color-text-secondary)] w-8 text-center">{{ $pct }}%</span>
                                    </span>
                                    <span class="v3-chip {{ $state === 'warning' ? 'v3-chip-warning' : 'v3-chip-success' }}">
                                        {{ $state === 'warning' ? '…' : '✓' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-center text-xs font-semibold text-[color:var(--color-text-muted)] mt-4 px-4">{{ __('landing-v3.showcase.students_desc') }}</p>
                </div>

                {{-- Tab: finance --}}
                <div x-show="tab === 'finance'" x-cloak x-transition.opacity role="tabpanel" aria-label="{{ __('landing-v3.showcase.tabs.finance') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        @foreach([
                            ['إيرادات الشهر', '45,000 ج.م', '+12%', 'success', 'fas fa-money-bill-wave'],
                            ['متحصلات', '38,500 ج.م', '+8%', 'success', 'fas fa-hand-holding-usd'],
                            ['مستحقات', '12,300 ج.م', '-5%', 'warning', 'fas fa-clock'],
                            ['مصروفات', '15,200 ج.م', '+3%', 'info', 'fas fa-receipt'],
                        ] as [$label, $value, $change, $type, $icon])
                            <div class="v3-card p-5 dark:bg-[#111f1e] dark:border-[#1f3936]">
                                <p class="text-xs font-extrabold text-[color:var(--color-text-secondary)] mb-2">{{ $label }}</p>
                                <p class="text-2xl font-extrabold text-[color:var(--color-text-main)] mb-1">{{ $value }}</p>
                                <span class="v3-chip v3-chip-{{ $type }} text-xs">{{ $change }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="v3-card p-5 dark:bg-[#111f1e] dark:border-[#1f3936]">
                        <h4 class="text-sm font-extrabold text-[color:var(--color-text-main)] mb-4">{{ __('landing-v3.showcase.finance_title') }}</h4>
                        <svg viewBox="0 0 300 100" class="w-full h-auto" role="img" aria-label="{{ __('landing-v3.showcase.finance_desc') }}">
                            <defs>
                                <linearGradient id="v3finance" x1="0" y1="0" x2="1" y2="0">
                                    <stop offset="0%" stop-color="#2E8B83"/><stop offset="100%" stop-color="#4F7DF3"/>
                                </linearGradient>
                            </defs>
                            <polyline points="0,80 30,72 60,68 90,55 120,60 150,45 180,40 210,30 240,35 270,20 300,25" fill="none" stroke="url(#v3finance)" stroke-width="3" stroke-linecap="round"/>
                            <circle cx="270" cy="20" r="5" fill="#2E8B83"/>
                        </svg>
                    </div>
                    <p class="text-center text-xs font-semibold text-[color:var(--color-text-muted)] mt-4 px-4">{{ __('landing-v3.showcase.finance_desc') }}</p>
                </div>

                {{-- Tab: reports --}}
                <div x-show="tab === 'reports'" x-cloak x-transition.opacity role="tabpanel" aria-label="{{ __('landing-v3.showcase.tabs.reports') }}">
                    <div class="grid sm:grid-cols-2 gap-4 mb-6">
                        <div class="v3-card p-5 dark:bg-[#111f1e] dark:border-[#1f3936]">
                            <span class="text-xs font-extrabold text-[color:var(--color-text-secondary)] block mb-4">{{ __('landing-v3.showcase.reports_title') }}</span>
                            <svg viewBox="0 0 200 72" class="w-full h-auto" role="img" aria-label="{{ __('landing-v3.showcase.reports_desc') }}">
                                <defs>
                                    <linearGradient id="v3reports" x1="0" y1="0" x2="1" y2="0">
                                        <stop offset="0%" stop-color="#2E8B83"/><stop offset="100%" stop-color="#4F7DF3"/>
                                    </linearGradient>
                                </defs>
                                <polyline points="0,58 25,50 50,54 75,38 100,44 125,26 150,32 175,16 200,20" fill="none" stroke="url(#v3reports)" stroke-width="3" stroke-linecap="round"/>
                                <circle cx="175" cy="16" r="4" fill="#2E8B83"/>
                            </svg>
                        </div>
                        <div class="v3-card p-5 flex flex-col justify-between gap-4 dark:bg-[#111f1e] dark:border-[#1f3936]">
                            <span class="text-xs font-extrabold text-[color:var(--color-text-secondary)]">{{ __('landing-v3.outcomes.items.money.tag') }}</span>
                            <div class="space-y-3">
                                @foreach([[85, 'bg-[color:var(--color-primary-500)]'], [64, 'bg-[color:var(--color-primary-300)]'], [42, 'bg-emerald-400']] as [$w, $color])
                                    <div class="h-2 rounded-full bg-[color:var(--color-secondary-100)] dark:bg-slate-800 overflow-hidden" aria-hidden="true">
                                        <div class="h-full rounded-full {{ $color }}" style="width: {{ $w }}%;"></div>
                                    </div>
                                @endforeach
                            </div>
                            <span class="v3-chip v3-chip-primary self-start">+{{ __('landing-v3.showcase.tabs.reports') }}</span>
                        </div>
                    </div>
                    <p class="text-center text-xs font-semibold text-[color:var(--color-text-muted)] mt-4 px-4">{{ __('landing-v3.showcase.reports_desc') }}</p>
                </div>

                {{-- Tab: WhatsApp alert --}}
                <div x-show="tab === 'whatsapp'" x-cloak x-transition.opacity role="tabpanel" aria-label="{{ __('landing-v3.showcase.tabs.whatsapp') }}">
                    <div class="max-w-md mx-auto v3-wa-bubble dark:bg-emerald-950/30 dark:border-emerald-800/50">
                        <div class="flex items-start gap-3">
                            <span class="w-9 h-9 rounded-full bg-[color:var(--color-success-500)] text-white flex items-center justify-center shrink-0"><i class="fab fa-whatsapp"></i></span>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <span class="text-xs font-extrabold text-[color:var(--color-success-800)] dark:text-emerald-300">Taalimu</span>
                                    <span class="text-[10px] font-semibold text-[color:var(--color-text-muted)]">{{ __('landing-v3.showcase.whatsapp_time') }}</span>
                                </div>
                                <p class="text-[13px] font-medium leading-relaxed text-[color:var(--color-text-main)]">{{ __('landing-v3.showcase.whatsapp_msg') }}</p>
                                <span class="flex items-center justify-end gap-1 mt-2 text-[10px] font-bold text-[color:var(--color-success-700)] dark:text-emerald-400">
                                    {{ __('landing-v3.showcase.whatsapp_via') }}
                                    <i class="fas fa-check-double"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <p class="text-center text-xs font-semibold text-[color:var(--color-text-muted)] mt-4 px-4">{{ __('landing-v3.showcase.whatsapp_desc') }}</p>
                </div>

                {{-- Tab: Teacher experience --}}
                <div x-show="tab === 'teacher'" x-cloak x-transition.opacity role="tabpanel" aria-label="{{ __('landing-v3.showcase.tabs.teacher') }}">
                    <div class="grid md:grid-cols-3 gap-4">
                        @foreach([
                            ['fas fa-users', 'مجموعاتي', '3 مجموعات نشطة', '120 طالب'],
                            ['fas fa-qrcode', 'حضور QR', 'مسح في ثانية', 'صفر ورق'],
                            ['fas fa-money-bill-wave', 'أرباحي', '15,000 ج.م', 'هذا الشهر'],
                        ] as [$icon, $title, $desc, $metric])
                            <div class="v3-card p-5 text-center dark:bg-[#111f1e] dark:border-[#1f3936]">
                                <div class="w-12 h-12 rounded-2xl bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)] flex items-center justify-center mx-auto mb-3 text-xl">
                                    <i class="{{ $icon }}"></i>
                                </div>
                                <h4 class="font-extrabold text-[color:var(--color-text-main)] mb-1">{{ $title }}</h4>
                                <p class="text-sm text-[color:var(--color-text-secondary)] mb-2">{{ $desc }}</p>
                                <span class="text-lg font-extrabold text-[color:var(--color-primary-600)]">{{ $metric }}</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-center text-xs font-semibold text-[color:var(--color-text-muted)] mt-4 px-4">{{ __('landing-v3.showcase.teacher_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>