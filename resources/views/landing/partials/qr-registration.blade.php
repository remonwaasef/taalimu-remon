<section id="qr-registration" class="py-20 lg:py-28 bg-slate-50 relative overflow-hidden">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl relative z-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-12 items-center">
            
            <!-- Visual Column (QR Cards & Scanner Mockup) -->
            <div class="lg:col-span-6 order-2 lg:order-1" data-animate="scale-in">
                <div class="relative mx-auto max-w-md lg:max-w-none">
                    
                    <!-- Background Backdrop Card -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-200/80 relative">
                        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#2E8B83] flex items-center justify-center font-bold text-lg">
                                    <i class="fas fa-qrcode"></i>
                                </div>
                                <div>
                                    <h3 class="font-extrabold text-slate-900 text-base">{{ __('landing.mockups.qr_system_title') }}</h3>
                                    <span class="text-xs text-slate-500">{{ __('landing.mockups.qr_system_sub') }}</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                                {{ __('landing.mockups.active_now') }}
                            </span>
                        </div>

                        <!-- Real Digital Student Badge Mockup -->
                        <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white rounded-2xl p-6 shadow-xl mb-6 relative overflow-hidden">
                            <div class="absolute -end-10 -top-10 w-32 h-32 bg-emerald-500/20 rounded-full blur-2xl pointer-events-none"></div>
                            
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-mono tracking-widest text-emerald-400 font-bold">STUDENT PASS</span>
                                <img src="{{ asset('images/brand/logo-icon.png') }}" alt="Taalimu" class="h-6 w-auto">
                            </div>

                            <div class="flex items-center gap-4">
                                <!-- Simulated QR Code -->
                                <div class="bg-white p-2 rounded-xl shadow-md shrink-0">
                                    <i class="fas fa-qrcode text-5xl text-slate-900"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-base font-bold text-white truncate">{{ __('landing.mockups.student_name') }}</h4>
                                    <p class="text-xs text-slate-400 mb-1">{{ __('landing.mockups.student_class') }}</p>
                                    <div class="inline-block px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[10px] font-mono border border-emerald-400/30">
                                        ID: #ST-892401
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Live Attendance Scanning Result Banner -->
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                                <i class="fas fa-check text-base"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-bold text-slate-900">{{ __('landing.mockups.attendance_done') }}</h4>
                                    <span class="text-[10px] text-emerald-700 font-bold">{{ app()->isLocale('ar') ? '04:30 م' : '04:30 PM' }}</span>
                                </div>
                                <p class="text-[11px] text-slate-600">{{ __('landing.mockups.parent_notified') }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Content Column -->
            <div class="lg:col-span-6 order-1 lg:order-2" data-animate="fade-in">
                <!-- Section Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-50 border border-teal-200/80 text-[#2E8B83] text-xs font-bold mb-4 shadow-sm">
                    <i class="fas fa-qrcode text-xs"></i>
                    <span>{{ __('landing.qr_section.badge') }}</span>
                </div>

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
                    {{ __('landing.qr_section.title') }}
                </h2>

                <p class="text-slate-600 font-medium text-base leading-relaxed mb-8">
                    {{ __('landing.qr_section.subtitle') }}
                </p>

                <!-- Workflow Steps -->
                <div class="space-y-4 mb-8">
                    <div class="flex items-start gap-4 p-3.5 rounded-xl bg-white border border-slate-200/60 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 text-[#2E8B83] flex items-center justify-center font-bold text-sm shrink-0">1</div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 mb-0.5">{{ __('landing.qr_section.step1_title') }}</h3>
                            <p class="text-xs text-slate-600 font-medium">{{ __('landing.qr_section.step1_desc') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-3.5 rounded-xl bg-white border border-slate-200/60 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 text-[#2E8B83] flex items-center justify-center font-bold text-sm shrink-0">2</div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 mb-0.5">{{ __('landing.qr_section.step2_title') }}</h3>
                            <p class="text-xs text-slate-600 font-medium">{{ __('landing.qr_section.step2_desc') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-3.5 rounded-xl bg-white border border-slate-200/60 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 text-[#2E8B83] flex items-center justify-center font-bold text-sm shrink-0">3</div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 mb-0.5">{{ __('landing.qr_section.step3_title') }}</h3>
                            <p class="text-xs text-slate-600 font-medium">{{ __('landing.qr_section.step3_desc') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Benefits List -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-slate-200">
                    @foreach(__('landing.qr_section.benefits') as $benefit)
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                            <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                            <span>{{ $benefit }}</span>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>

    </div>
</section>