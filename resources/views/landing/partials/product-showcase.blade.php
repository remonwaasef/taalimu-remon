<section class="py-20 lg:py-28 bg-slate-50 relative">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-12" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-50 border border-teal-200/80 text-[#2E8B83] text-xs font-bold mb-4 shadow-sm">
                <i class="fas fa-desktop text-xs"></i>
                <span>{{ __('landing.showcase.badge') }}</span>
            </div>

            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
                {{ __('landing.showcase.title') }}
            </h2>

            <p class="text-slate-600 font-medium text-base">
                {{ __('landing.showcase.subtitle') }}
            </p>
        </div>

        <!-- Interactive Tabs Navigation -->
        <div x-data="{ activeTab: 'center' }" data-animate="scale-in">
            
            <div class="flex flex-wrap items-center justify-center gap-2 mb-8 bg-white p-2 rounded-2xl border border-slate-200/80 max-w-3xl mx-auto shadow-sm">
                <button @click="activeTab = 'center'" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all" :class="activeTab === 'center' ? 'bg-[#2E8B83] text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                    <i class="fas fa-chart-line me-1.5"></i>
                    <span>{{ __('landing.showcase.tabs.center') }}</span>
                </button>
                
                <button @click="activeTab = 'scanner'" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all" :class="activeTab === 'scanner' ? 'bg-[#2E8B83] text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                    <i class="fas fa-qrcode me-1.5"></i>
                    <span>{{ __('landing.showcase.tabs.scanner') }}</span>
                </button>
                
                <button @click="activeTab = 'whatsapp'" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all" :class="activeTab === 'whatsapp' ? 'bg-[#2E8B83] text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                    <i class="fab fa-whatsapp me-1.5"></i>
                    <span>{{ __('landing.showcase.tabs.whatsapp') }}</span>
                </button>
                
                <button @click="activeTab = 'pos'" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all" :class="activeTab === 'pos' ? 'bg-[#2E8B83] text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                    <i class="fas fa-cash-register me-1.5"></i>
                    <span>{{ __('landing.showcase.tabs.pos') }}</span>
                </button>

                <button @click="activeTab = 'parent'" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all" :class="activeTab === 'parent' ? 'bg-[#2E8B83] text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                    <i class="fas fa-user-shield me-1.5"></i>
                    <span>{{ __('landing.showcase.tabs.parent') }}</span>
                </button>
            </div>

            <!-- Tab 1: Center Dashboard -->
            <div x-show="activeTab === 'center'" x-cloak x-transition class="bg-white rounded-3xl p-6 lg:p-8 border border-slate-200 shadow-2xl">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-1">{{ __('landing.showcase.center_title') }}</h3>
                    <p class="text-slate-600 text-xs sm:text-sm font-medium">{{ __('landing.showcase.center_desc') }}</p>
                </div>
                <img src="{{ asset('images/hero-dashboard.webp') }}" alt="Center Dashboard Showcase" class="w-full h-auto rounded-2xl border border-slate-100 shadow-sm object-cover">
            </div>

            <!-- Tab 2: QR Scanner -->
            <div x-show="activeTab === 'scanner'" x-cloak x-transition class="bg-white rounded-3xl p-6 lg:p-8 border border-slate-200 shadow-2xl">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-1">{{ __('landing.showcase.scanner_title') }}</h3>
                    <p class="text-slate-600 text-xs sm:text-sm font-medium">{{ __('landing.showcase.scanner_desc') }}</p>
                </div>
                <div class="bg-slate-900 rounded-2xl p-8 text-center max-w-lg mx-auto text-white shadow-xl">
                    <div class="w-24 h-24 mx-auto bg-white rounded-2xl p-2 flex items-center justify-center mb-4 relative shadow-lg">
                        <i class="fas fa-qrcode text-6xl text-slate-900"></i>
                        <div class="absolute inset-x-0 h-0.5 bg-emerald-500 shadow-[0_0_8px_#10b981] animate-pulse top-1/2"></div>
                    </div>
                    <h4 class="text-base font-bold text-white mb-1">{{ __('landing.mockups.scanner_title') }}</h4>
                    <p class="text-xs text-slate-400">{{ __('landing.mockups.scanner_hint') }}</p>
                </div>
            </div>

            <!-- Tab 3: WhatsApp Log -->
            <div x-show="activeTab === 'whatsapp'" x-cloak x-transition class="bg-white rounded-3xl p-6 lg:p-8 border border-slate-200 shadow-2xl">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-1">{{ __('landing.showcase.whatsapp_title') }}</h3>
                    <p class="text-slate-600 text-xs sm:text-sm font-medium">{{ __('landing.showcase.whatsapp_desc') }}</p>
                </div>
                <div class="space-y-3 max-w-2xl mx-auto">
                    <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fab fa-whatsapp text-2xl text-emerald-600"></i>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">{{ __('landing.mockups.whatsapp_attendance_entry') }}</h4>
                                <p class="text-[11px] text-slate-500">{{ __('landing.mockups.sent_to') }} +2010****8941</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded bg-emerald-500 text-white text-[10px] font-bold">{{ __('landing.mockups.delivered') }} <i class="fas fa-check-double ms-1"></i></span>
                    </div>

                    <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fab fa-whatsapp text-2xl text-emerald-600"></i>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">{{ __('landing.mockups.whatsapp_payment_entry') }}</h4>
                                <p class="text-[11px] text-slate-500">{{ __('landing.mockups.sent_to') }} +2011****3301</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded bg-emerald-500 text-white text-[10px] font-bold">{{ __('landing.mockups.delivered') }} <i class="fas fa-check-double ms-1"></i></span>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Financial POS -->
            <div x-show="activeTab === 'pos'" x-cloak x-transition class="bg-white rounded-3xl p-6 lg:p-8 border border-slate-200 shadow-2xl">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-1">{{ __('landing.showcase.pos_title') }}</h3>
                    <p class="text-slate-600 text-xs sm:text-sm font-medium">{{ __('landing.showcase.pos_desc') }}</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 max-w-xl mx-auto">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-4">
                        <span class="font-bold text-sm text-slate-900">{{ __('landing.mockups.invoice_title') }}</span>
                        <span class="text-xs font-mono text-slate-500">INV-2026-0041</span>
                    </div>
                    <div class="space-y-2 text-xs text-slate-700 font-medium mb-4">
                        <div class="flex justify-between"><span>{{ __('landing.mockups.invoice_student') }}</span><span class="font-bold text-slate-900">{{ __('landing.mockups.student_name') }}</span></div>
                        <div class="flex justify-between"><span>{{ __('landing.mockups.invoice_course') }}</span><span class="font-bold text-slate-900">{{ __('landing.mockups.invoice_course_value') }}</span></div>
                        <div class="flex justify-between"><span>{{ __('landing.mockups.invoice_paid') }}</span><span class="font-bold text-emerald-600">500 {{ app()->isLocale('ar') ? 'ج.م' : 'EGP' }}</span></div>
                        <div class="flex justify-between"><span>{{ __('landing.mockups.invoice_remaining') }}</span><span class="font-bold text-slate-500">0 {{ app()->isLocale('ar') ? 'ج.م' : 'EGP' }}</span></div>
                    </div>
                    <button type="button" class="w-full py-2.5 rounded-xl bg-[#2E8B83] text-white font-bold text-xs shadow-md">
                        {{ __('landing.mockups.invoice_print') }}
                    </button>
                </div>
            </div>

            <!-- Tab 5: Parent View -->
            <div x-show="activeTab === 'parent'" x-cloak x-transition class="bg-white rounded-3xl p-6 lg:p-8 border border-slate-200 shadow-2xl">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-1">{{ __('landing.showcase.parent_title') }}</h3>
                    <p class="text-slate-600 text-xs sm:text-sm font-medium">{{ __('landing.showcase.parent_desc') }}</p>
                </div>
                <div class="bg-purple-50/60 border border-purple-100 p-6 rounded-2xl max-w-lg mx-auto">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center text-lg">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">{{ __('landing.mockups.parent_updates_title') }}</h4>
                            <span class="text-xs text-purple-700 font-medium">{{ __('landing.mockups.parent_updates_sub') }}</span>
                        </div>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-purple-100 text-xs text-slate-700 space-y-1.5">
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-500"></i><span>{{ __('landing.mockups.parent_update_attendance') }}</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-500"></i><span>{{ __('landing.mockups.parent_update_grade') }}</span></div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-500"></i><span>{{ __('landing.mockups.parent_update_paid') }}</span></div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>