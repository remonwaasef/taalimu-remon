{{-- Interactive ROI & Time Savings Calculator --}}
<section id="roi-calculator" class="py-20 lg:py-28 bg-gradient-to-b from-slate-50 via-white to-slate-50 relative overflow-hidden">
    <!-- Subtle Background Glows -->
    <div class="absolute top-1/2 start-0 -translate-y-1/2 w-96 h-96 bg-teal-500/5 blur-3xl pointer-events-none rounded-full"></div>
    <div class="absolute top-1/3 end-0 w-96 h-96 bg-emerald-500/5 blur-3xl pointer-events-none rounded-full"></div>

    <div class="container mx-auto px-4 lg:px-8 max-w-7xl relative z-10"
         x-data="{
             students: 200,
             get hoursSaved() {
                 return Math.round((this.students * 0.14) + 12);
             },
             get callsEliminated() {
                 return Math.round(this.students * 3.5);
             },
             get hoursPercentage() {
                 return Math.min(100, Math.round((this.students / 1000) * 100));
             }
         }">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-50 border border-teal-200/80 text-[#2E8B83] text-xs font-bold mb-4 shadow-sm">
                <i class="fas fa-calculator text-xs"></i>
                <span>{{ __('landing.roi_calculator.badge') }}</span>
            </div>

            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
                {{ __('landing.roi_calculator.title') }}
            </h2>

            <p class="text-slate-600 font-medium text-base">
                {{ __('landing.roi_calculator.subtitle') }}
            </p>
        </div>

        <!-- Calculator Card Container -->
        <div class="max-w-4xl mx-auto bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-2xl relative" data-animate="scale-in">
            
            <!-- Slider Control Area -->
            <div class="mb-10 pb-8 border-b border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <label for="students-slider" class="text-sm sm:text-base font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-user-graduate text-[#2E8B83]"></i>
                        <span>{{ __('landing.roi_calculator.slider_label') }}</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl sm:text-3xl font-black text-[#2E8B83] font-mono" x-text="students"></span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">{{ __('landing.roi_calculator.students_unit') }}</span>
                    </div>
                </div>

                <!-- Range Slider -->
                <div class="relative py-2">
                    <input
                        id="students-slider"
                        type="range"
                        min="30"
                        max="1200"
                        step="10"
                        x-model.number="students"
                        class="w-full h-3 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-[#2E8B83] focus:outline-none focus:ring-2 focus:ring-[#2E8B83]/30"
                        aria-label="{{ __('landing.roi_calculator.slider_label') }}"
                    >
                    <div class="flex justify-between text-[11px] text-slate-400 font-semibold mt-2">
                        <span>30 {{ __('landing.roi_calculator.students_unit') }}</span>
                        <span>300</span>
                        <span>600</span>
                        <span>900</span>
                        <span>1200+ {{ __('landing.roi_calculator.students_unit') }}</span>
                    </div>
                </div>
            </div>

            <!-- Dynamic Metrics Output Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <!-- Metric 1: Hours Saved -->
                <div class="bg-emerald-50/60 rounded-2xl p-5 border border-emerald-100 transition-all transform hover:-translate-y-1">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-lg mb-3 shadow-md">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="flex items-baseline gap-1.5 mb-1">
                        <span class="text-3xl font-black text-slate-900 font-mono" x-text="hoursSaved"></span>
                        <span class="text-xs font-bold text-emerald-700">ساعة / شهر</span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-800 mb-1">{{ __('landing.roi_calculator.metric_hours_title') }}</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">{{ __('landing.roi_calculator.metric_hours_desc') }}</p>
                </div>

                <!-- Metric 2: Financial Precision & Leak Prevention -->
                <div class="bg-teal-50/60 rounded-2xl p-5 border border-teal-100 transition-all transform hover:-translate-y-1">
                    <div class="w-10 h-10 rounded-xl bg-[#2E8B83] text-white flex items-center justify-center text-lg mb-3 shadow-md">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="flex items-baseline gap-1.5 mb-1">
                        <span class="text-3xl font-black text-slate-900 font-mono">100%</span>
                        <span class="text-xs font-bold text-teal-700">دقة التحصيل</span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-800 mb-1">{{ __('landing.roi_calculator.metric_leak_title') }}</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">{{ __('landing.roi_calculator.metric_leak_desc') }}</p>
                </div>

                <!-- Metric 3: Parent Peace of Mind & Calls Saved -->
                <div class="bg-sky-50/60 rounded-2xl p-5 border border-sky-100 transition-all transform hover:-translate-y-1">
                    <div class="w-10 h-10 rounded-xl bg-sky-500 text-white flex items-center justify-center text-lg mb-3 shadow-md">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="flex items-baseline gap-1.5 mb-1">
                        <span class="text-3xl font-black text-slate-900 font-mono" x-text="callsEliminated"></span>
                        <span class="text-xs font-bold text-sky-700">إشعار فوري</span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-800 mb-1">{{ __('landing.roi_calculator.metric_satisfaction_title') }}</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">{{ __('landing.roi_calculator.metric_satisfaction_desc') }}</p>
                </div>

            </div>

            <!-- Bottom CTA Banner Inside Card -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-2xl bg-slate-900 text-white">
                <div class="flex items-center gap-3 text-center sm:text-start">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 hidden sm:flex">
                        <i class="fas fa-sparkles"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-white">{{ __('landing.roi_calculator.cta_text') }}</p>
                        <span class="text-[10px] text-slate-400">بدون بطاقة بنكية • تجربة كاملة 14 يوماً</span>
                    </div>
                </div>
                <a href="{{ route('register') }}" class="w-full sm:w-auto shrink-0 px-6 py-2.5 rounded-xl text-xs font-extrabold text-white text-decoration-none shadow-md hover:shadow-lg transition-all text-center flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);">
                    <span>{{ __('landing.roi_calculator.cta_btn') }}</span>
                    <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180"></i>
                </a>
            </div>

        </div>

    </div>
</section>
