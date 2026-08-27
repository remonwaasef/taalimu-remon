<section id="ecosystem" class="v3-section bg-white dark:bg-[#111f1e] border-y border-[color:var(--color-border-subtle)] dark:border-[#182e2c]" aria-labelledby="v3-eco-title">
    <div class="v3-container">
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v3-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v3.ecosystem.badge') }}
            </span>
            <h2 id="v3-eco-title" class="v3-h2 mb-3">{{ __('landing-v3.ecosystem.title') }}</h2>
            <p class="v3-lead">{{ __('landing-v3.ecosystem.subtitle') }}</p>
        </div>

        <div class="max-w-4xl mx-auto" data-reveal style="--reveal-delay: 100ms;">
            {{-- Central Hub --}}
            <div class="relative mb-12 lg:mb-16">
                <div class="flex items-center justify-center">
                    {{-- Connecting lines --}}
                    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                        <svg class="w-full h-full" viewBox="0 0 800 200" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="ecoLine" x1="0" y1="0" x2="1" y2="0">
                                    <stop offset="0%" stop-color="#2E8B83" stop-opacity="0.3"/>
                                    <stop offset="50%" stop-color="#2E8B83" stop-opacity="0.5"/>
                                    <stop offset="100%" stop-color="#2E8B83" stop-opacity="0.3"/>
                                </linearGradient>
                            </defs>
                            {{-- Lines from center to corners --}}
                            <path d="M400,100 L150,30" stroke="url(#ecoLine)" stroke-width="2" stroke-dasharray="8,4" fill="none"/>
                            <path d="M400,100 L650,30" stroke="url(#ecoLine)" stroke-width="2" stroke-dasharray="8,4" fill="none"/>
                            <path d="M400,100 L150,170" stroke="url(#ecoLine)" stroke-width="2" stroke-dasharray="8,4" fill="none"/>
                            <path d="M400,100 L650,170" stroke="url(#ecoLine)" stroke-width="2" stroke-dasharray="8,4" fill="none"/>
                        </svg>
                    </div>

                    {{-- Center Hub --}}
                    <div class="v3-hub relative z-10 !bg-gradient-to-br !from-[#2E8B83] !to-[#1E5E58] !shadow-[0_20px_40px_-12px_rgba(46,139,131,0.6)]">
                        <img src="{{ asset('images/brand/logo-icon.png') }}" alt="Taalimu" class="h-14 w-auto brightness-200" width="56" height="56" loading="lazy">
                        <span class="text-[14px] font-extrabold tracking-wide text-white">{{ __('landing-v3.ecosystem.roles.0.title') }}</span>
                        <p class="text-[11px] text-white/80 mt-1 max-w-xs text-center">{{ __('landing-v3.ecosystem.roles.0.desc') }}</p>
                    </div>
                </div>
            </div>

            {{-- Four Roles --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach(__('landing-v3.ecosystem.roles') as $index => $role)
                    <article class="v3-node p-5 relative group" role="listitem">
                        {{-- Role number/badge --}}
                        @if($index === 0)
                            <span class="absolute -top-3 -start-3 w-6 h-6 rounded-full bg-[color:var(--color-primary-500)] text-white text-[10px] font-extrabold flex items-center justify-center">
                                {{ $index + 1 }}
                            </span>
                        @endif

                        <div class="w-12 h-12 rounded-2xl {{ $role['color'] === 'success' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400' : 'bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)]' }} flex items-center justify-center mx-auto mb-4 text-xl">
                            <i class="{{ $role['icon'] }}"></i>
                        </div>

                        <h3 class="text-center font-extrabold text-[color:var(--color-text-main)] mb-2">{{ $role['title'] }}</h3>
                        <p class="text-center text-sm text-[color:var(--color-text-secondary)]">{{ $role['desc'] }}</p>

                        {{-- Feature tags --}}
                        <div class="mt-4 flex flex-wrap justify-center gap-1.5">
                            @php
                                $tags = [
                                    'center' => ['مالية', 'مدرسين', 'تقارير', 'فروع', 'حوكمة'],
                                    'teacher' => ['مجموعات', 'حضور', 'أرباح', 'تواصل', 'جدول'],
                                    'student' => ['بطاقة رقمية', 'QR', 'جدول', 'درجات', 'بوابة'],
                                    'parent' => ['إشعارات', 'متابعة', 'سداد', 'تواصل', 'اطمئنان'],
                                ];
                            @endphp
                            @foreach($tags[$role['key']] as $tag)
                                <span class="v3-chip v3-chip-primary text-[10px] px-2 py-1">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Value Flow Description --}}
            <div class="mt-12 p-6 lg:p-8 v3-card dark:bg-[#111f1e] dark:border-[#1f3936] text-center" data-reveal style="--reveal-delay: 200ms;">
                <div class="flex items-center justify-center gap-2 mb-4">
                    <i class="fas fa-sync-alt text-[color:var(--color-primary-500)] text-xl"></i>
                    <span class="text-lg font-extrabold text-[color:var(--color-text-main)]">دورة القيمة المستمرة</span>
                </div>
                <p class="text-base text-[color:var(--color-text-secondary)] max-w-2xl mx-auto">
                    المركز يدير العمليات والمالية ← المدرس يدرّس ويسجل الحضور ← الطالب يتعلم ويحصل على بطاقة رقمية ← ولي الأمر يطمئن بإشعارات فورية
                    ← <span class="font-bold text-[color:var(--color-primary-600)]">كل النشاط يعود لبيانات المركز لاتخاذ قرارات أفضل</span>
                </p>
            </div>
        </div>
    </div>
</section>