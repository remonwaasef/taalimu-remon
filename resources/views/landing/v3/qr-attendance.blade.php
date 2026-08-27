<section id="qr-attendance" class="v3-section bg-slate-50/50 dark:bg-slate-900/30" aria-labelledby="v3-qr-title">
    <div class="v3-container">
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v3-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v3.qr_attendance.badge') }}
            </span>
            <h2 id="v3-qr-title" class="v3-h2 mb-3">{{ __('landing-v3.qr_attendance.title') }}</h2>
            <p class="v3-lead">{{ __('landing-v3.qr_attendance.subtitle') }}</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            {{-- Visual Flow --}}
            <div class="relative" data-reveal>
                <div class="v3-card p-6 lg:p-8 dark:bg-[#111f1e] dark:border-[#1f3936] relative overflow-hidden">
                    {{-- Flow Steps --}}
                    <div class="space-y-6">
                        @foreach(__('landing-v3.qr_attendance.flow') as $index => $step)
                            <div class="flex items-center gap-4 relative group">
                                <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)] flex items-center justify-center text-xl">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                                <span class="text-sm font-bold text-[color:var(--color-text-main)] whitespace-nowrap">{{ $step }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Phone mockup with QR --}}
                    <div class="mt-8 flex justify-center">
                        <div class="relative">
                            <div class="w-48 h-[320px] rounded-[32px] bg-slate-900 border-4 border-slate-700 shadow-2xl relative overflow-hidden">
                                <div class="absolute inset-0 bg-slate-800 flex items-center justify-center p-6">
                                    <div class="w-full h-full bg-white rounded-xl flex items-center justify-center relative">
                                        <img src="{{ asset('images/hero-dashboard.webp') }}"
                                             alt="QR Scanner View"
                                             class="w-32 h-32 object-cover rounded-lg">
                                        {{-- Scanner overlay --}}
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                            <div class="w-40 h-40 border-2 border-[color:var(--color-primary-500)] rounded-lg relative">
                                                <div class="absolute -top-2 -start-2 w-4 h-4 border-t-2 border-l-2 border-[color:var(--color-primary-500)] rounded-tl-lg"></div>
                                                <div class="absolute -top-2 -end-2 w-4 h-4 border-t-2 border-r-2 border-[color:var(--color-primary-500)] rounded-tr-lg"></div>
                                                <div class="absolute -bottom-2 -start-2 w-4 h-4 border-b-2 border-l-2 border-[color:var(--color-primary-500)] rounded-bl-lg"></div>
                                                <div class="absolute -bottom-2 -end-2 w-4 h-4 border-b-2 border-r-2 border-[color:var(--color-primary-500)] rounded-br-lg"></div>
                                                <div class="absolute top-1/2 start-1/2 -translate-x-1/2 -translate-y-1/2 w-24 h-1 bg-[color:var(--color-primary-500)] animate-pulse opacity-50"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Features --}}
            <div data-reveal style="--reveal-delay: 100ms;">
                <h3 class="text-xl font-extrabold text-[color:var(--color-text-main)] mb-6">لماذا يعمل QR Attendance بسلاسة؟</h3>
                <ul class="space-y-4" role="list">
                    @foreach(__('landing-v3.qr_attendance.features') as $feature)
                        <li class="flex items-start gap-3 p-4 v3-card dark:bg-[#111f1e] dark:border-[#1f3936]" role="listitem">
                            <span class="flex-shrink-0 w-10 h-10 rounded-xl bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)] flex items-center justify-center">
                                <i class="fas fa-check"></i>
                            </span>
                            <span class="text-sm font-medium text-[color:var(--color-text-main)]">{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>

                {{-- CTA --}}
                <div class="mt-8">
                    <a href="{{ route('register') }}" data-track="v3_qr_cta_clicked" class="v3-btn v3-btn-primary w-full sm:w-auto px-8">
                        {{ __('landing-v3.qr_attendance.cta') }}
                        <i class="fas fa-arrow-left text-sm rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>