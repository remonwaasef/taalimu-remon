<section class="py-20 lg:py-28 bg-white relative">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs font-bold mb-4 shadow-sm">
                <i class="fas fa-th-large text-xs"></i>
                <span>{{ __('landing.bento.badge') }}</span>
            </div>

            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
                {{ __('landing.bento.title') }}
            </h2>

            <p class="text-slate-600 font-medium text-base">
                {{ __('landing.bento.subtitle') }}
            </p>
        </div>

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            
            <!-- Large Bento Card 1: QR Code Registration (Span 7) -->
            <div class="md:col-span-7 bg-slate-50/80 rounded-3xl p-8 border border-slate-200/80 hover:shadow-xl transition-all duration-300 flex flex-col justify-between" data-animate="fade-in">
                <div class="mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-[#2E8B83] flex items-center justify-center text-xl mb-4 font-bold">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-2">
                        {{ __('landing.bento.qr_title') }}
                    </h3>
                    <p class="text-slate-600 text-sm font-medium leading-relaxed">
                        {{ __('landing.bento.qr_desc') }}
                    </p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-200 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-barcode text-2xl text-[#2E8B83]"></i>
                        <span class="text-xs font-bold text-slate-800">كرنيه الطالب والتسجيل السريع</span>
                    </div>
                    <span class="px-2.5 py-1 rounded bg-teal-50 text-[#2E8B83] text-[10px] font-bold">تلقائي</span>
                </div>
            </div>

            <!-- Large Bento Card 2: Meta WhatsApp Notifications (Span 5) -->
            <div class="md:col-span-5 bg-gradient-to-br from-emerald-900 via-slate-900 to-slate-900 text-white rounded-3xl p-8 border border-emerald-800/40 hover:shadow-xl transition-all duration-300 flex flex-col justify-between" data-animate="fade-in">
                <div class="mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-xl mb-4 font-bold shadow-md">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-white mb-2">
                        {{ __('landing.bento.whatsapp_title') }}
                    </h3>
                    <p class="text-slate-300 text-sm font-medium leading-relaxed">
                        {{ __('landing.bento.whatsapp_desc') }}
                    </p>
                </div>
                <div class="bg-slate-800/80 rounded-2xl p-3.5 border border-slate-700/80 text-xs text-emerald-300 flex items-center gap-2">
                    <i class="fas fa-check-double text-emerald-400"></i>
                    <span>تكامل رسمي موثق عبر Meta Cloud API</span>
                </div>
            </div>

            <!-- Medium Bento Card 3: Student Management (Span 4) -->
            <div class="md:col-span-4 bg-slate-50/80 rounded-3xl p-6 border border-slate-200/80 hover:shadow-lg transition-all" data-animate="fade-in">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg mb-3 font-bold">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-1.5">{{ __('landing.bento.student_mgmt_title') }}</h4>
                <p class="text-xs text-slate-600 font-medium leading-relaxed">{{ __('landing.bento.student_mgmt_desc') }}</p>
            </div>

            <!-- Medium Bento Card 4: Attendance Tracking (Span 4) -->
            <div class="md:col-span-4 bg-slate-50/80 rounded-3xl p-6 border border-slate-200/80 hover:shadow-lg transition-all" data-animate="fade-in">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#2E8B83] flex items-center justify-center text-lg mb-3 font-bold">
                    <i class="fas fa-user-check"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-1.5">{{ __('landing.bento.attendance_title') }}</h4>
                <p class="text-xs text-slate-600 font-medium leading-relaxed">{{ __('landing.bento.attendance_desc') }}</p>
            </div>

            <!-- Medium Bento Card 5: Financial POS & Invoicing (Span 4) -->
            <div class="md:col-span-4 bg-slate-50/80 rounded-3xl p-6 border border-slate-200/80 hover:shadow-lg transition-all" data-animate="fade-in">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg mb-3 font-bold">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-1.5">{{ __('landing.bento.finance_title') }}</h4>
                <p class="text-xs text-slate-600 font-medium leading-relaxed">{{ __('landing.bento.finance_desc') }}</p>
            </div>

            <!-- Medium Bento Card 6: Reports & Analytics (Span 4) -->
            <div class="md:col-span-4 bg-slate-50/80 rounded-3xl p-6 border border-slate-200/80 hover:shadow-lg transition-all" data-animate="fade-in">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg mb-3 font-bold">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-1.5">{{ __('landing.bento.reports_title') }}</h4>
                <p class="text-xs text-slate-600 font-medium leading-relaxed">{{ __('landing.bento.reports_desc') }}</p>
            </div>

            <!-- Medium Bento Card 7: Excel Migration (Span 4) -->
            <div class="md:col-span-4 bg-slate-50/80 rounded-3xl p-6 border border-slate-200/80 hover:shadow-lg transition-all" data-animate="fade-in">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg mb-3 font-bold">
                    <i class="fas fa-file-excel"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-1.5">{{ __('landing.bento.excel_title') }}</h4>
                <p class="text-xs text-slate-600 font-medium leading-relaxed">{{ __('landing.bento.excel_desc') }}</p>
            </div>

            <!-- Medium Bento Card 8: Online Quizzes (Span 4) -->
            <div class="md:col-span-4 bg-slate-50/80 rounded-3xl p-6 border border-slate-200/80 hover:shadow-lg transition-all" data-animate="fade-in">
                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-lg mb-3 font-bold">
                    <i class="fas fa-tasks"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-1.5">{{ __('landing.bento.quizzes_title') }}</h4>
                <p class="text-xs text-slate-600 font-medium leading-relaxed">{{ __('landing.bento.quizzes_desc') }}</p>
            </div>

        </div>

    </div>
</section>