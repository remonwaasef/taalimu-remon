{{-- Hero Section — Ultra-High Fidelity Match with Reference Image --}}
<section id="hero" class="relative pt-28 pb-16 lg:pt-36 lg:pb-24 overflow-hidden bg-gradient-to-b from-[#f4faf9] via-white to-white">
    <div class="container mx-auto px-5 lg:px-12 max-w-7xl relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 items-center">

            {{-- Right Column (RTL): Text Content & CTAs --}}
            <div class="lg:col-span-6 text-center lg:text-start order-2 lg:order-1" data-animate="fade-in">

                {{-- Eyebrow Pill Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#e8f5f3] border border-[#c5e8e4] text-xs font-bold text-slate-700 mb-6">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#2E8B83]"></span>
                    <span>منصة متكاملة لإدارة المؤسسات التعليمية</span>
                </div>

                {{-- H1 Headline --}}
                <h1 class="text-3xl sm:text-4xl lg:text-[46px] xl:text-[54px] font-black text-slate-900 leading-[1.3] tracking-tight mb-5">
                    كل ما تحتاجه لإدارة<br>
                    <span style="color: #2E8B83;">مؤسستك التعليمية</span><br>
                    في منصة واحدة
                </h1>

                {{-- Subtitle --}}
                <p class="text-sm sm:text-base lg:text-lg text-slate-600 font-medium leading-relaxed mb-8 max-w-lg mx-auto lg:mx-0">
                    إدارة الطلبة والمعلمين والفصول والدرجات والمدفوعات. التقارير والتواصل... بسهولة تامّة من أي مكان وفي أي وقت.
                </p>

                {{-- Primary & Secondary CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3.5 mb-8">
                    <a href="{{ route('register') }}"
                       data-track="hero_primary_cta"
                       class="w-full sm:w-auto px-8 py-3.5 rounded-full text-white font-extrabold text-sm text-decoration-none shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 text-center inline-flex items-center justify-center gap-2"
                       style="background-color: #2E8B83;">
                        <span>ابدأ مجاناً الآن</span>
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                    <button type="button"
                            @click="$dispatch('open-demo-modal')"
                            class="w-full sm:w-auto px-7 py-3.5 rounded-full border border-slate-200 bg-white text-slate-700 font-bold text-sm hover:bg-slate-50 shadow-xs transition-all text-center inline-flex items-center justify-center gap-2">
                        <i class="far fa-calendar-alt text-base" style="color: #2E8B83;"></i>
                        <span>احجز عرض توضيحي</span>
                    </button>
                </div>

                {{-- Trust Badges --}}
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-x-5 gap-y-2 text-xs font-bold text-slate-500">
                    <div class="flex items-center gap-2">
                        <i class="far fa-credit-card text-sm" style="color: #2E8B83;"></i>
                        <span>بدون بطاقة ائتمان</span>
                    </div>
                    <span class="text-slate-300 hidden sm:inline">•</span>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-headset text-sm" style="color: #2E8B83;"></i>
                        <span>دعم فني 24/7</span>
                    </div>
                    <span class="text-slate-300 hidden sm:inline">•</span>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-bolt text-sm" style="color: #2E8B83;"></i>
                        <span>إعداد سريع خلال دقائق</span>
                    </div>
                </div>
            </div>

            {{-- Left Column (RTL): Laptop & Smartphone Visual --}}
            <div class="lg:col-span-6 order-1 lg:order-2 relative" data-animate="scale-in">
                <div class="relative mx-auto max-w-[580px] lg:max-w-none">

                    {{-- Laptop Device Frame --}}
                    <div class="relative shadow-[0_25px_60px_-15px_rgba(15,23,42,0.2)] rounded-t-2xl border border-slate-700/60 bg-[#0f172a] p-2 sm:p-3">
                        {{-- Top Camera Bezel --}}
                        <div class="flex justify-center pb-2">
                            <span class="w-2 h-2 rounded-full bg-slate-700"></span>
                        </div>

                        {{-- Laptop Screen Content (HTML Dashboard Replica matching Reference Image) --}}
                        <div class="bg-slate-50 rounded-lg overflow-hidden text-[9px] sm:text-[11px] text-slate-700 select-none p-3 sm:p-4 space-y-3 font-sans border border-slate-200">
                            
                            {{-- Dashboard Top Header Bar --}}
                            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                                <div class="flex items-center gap-2">
                                    <span class="text-base">👋</span>
                                    <span class="font-extrabold text-slate-900 text-xs sm:text-sm">مرحباً بك في Taalimu</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 border border-slate-300 flex items-center justify-center text-[9px] font-bold text-slate-600">
                                        <i class="fas fa-user text-slate-500"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- 4 Quick Stat Cards --}}
                            <div class="grid grid-cols-4 gap-2 sm:gap-2.5">
                                <div class="bg-white p-2 rounded-xl border border-slate-200/80 shadow-2xs">
                                    <div class="text-[8px] sm:text-[10px] font-bold text-slate-400 mb-1">إجمالي المدفوعات</div>
                                    <div class="font-black text-slate-900 text-xs sm:text-base leading-none mb-1">8,450</div>
                                    <div class="text-[7px] sm:text-[9px] font-extrabold text-emerald-600 flex items-center gap-0.5">
                                        <i class="fas fa-arrow-up text-[7px]"></i> +16%
                                    </div>
                                </div>

                                <div class="bg-white p-2 rounded-xl border border-slate-200/80 shadow-2xs">
                                    <div class="text-[8px] sm:text-[10px] font-bold text-slate-400 mb-1">المعلمون</div>
                                    <div class="font-black text-slate-900 text-xs sm:text-base leading-none mb-1">320</div>
                                    <div class="text-[7px] sm:text-[9px] font-extrabold text-emerald-600 flex items-center gap-0.5">
                                        <i class="fas fa-arrow-up text-[7px]"></i> +8%
                                    </div>
                                </div>

                                <div class="bg-white p-2 rounded-xl border border-slate-200/80 shadow-2xs">
                                    <div class="text-[8px] sm:text-[10px] font-bold text-slate-400 mb-1">الفصول</div>
                                    <div class="font-black text-slate-900 text-xs sm:text-base leading-none mb-1">26</div>
                                    <div class="text-[7px] sm:text-[9px] font-extrabold text-emerald-600 flex items-center gap-0.5">
                                        <i class="fas fa-arrow-up text-[7px]"></i> +5%
                                    </div>
                                </div>

                                <div class="bg-white p-2 rounded-xl border border-slate-200/80 shadow-2xs">
                                    <div class="text-[8px] sm:text-[10px] font-bold text-slate-400 mb-1">إجمالي الطلاب</div>
                                    <div class="font-black text-slate-900 text-xs sm:text-base leading-none mb-1">1,250</div>
                                    <div class="text-[7px] sm:text-[9px] font-extrabold text-emerald-600 flex items-center gap-0.5">
                                        <i class="fas fa-arrow-up text-[7px]"></i> +12%
                                    </div>
                                </div>
                            </div>

                            {{-- Charts Row --}}
                            <div class="grid grid-cols-12 gap-2.5">
                                {{-- Line Chart (Attendance Rate) --}}
                                <div class="col-span-8 bg-white p-2.5 rounded-xl border border-slate-200/80 shadow-2xs">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-bold text-slate-800 text-[10px] sm:text-xs">نسبة الحضور</span>
                                        <span class="text-[8px] text-slate-400 font-semibold">100%</span>
                                    </div>
                                    {{-- SVG Line Graph --}}
                                    <div class="h-16 w-full">
                                        <svg viewBox="0 0 300 80" class="w-full h-full">
                                            <path d="M0,60 Q50,20 100,45 T200,25 T300,50" fill="none" stroke="#2E8B83" stroke-width="2.5" />
                                            <path d="M0,70 Q50,40 100,55 T200,35 T300,60" fill="none" stroke="#60a5fa" stroke-width="2" stroke-dasharray="3,3" />
                                            <circle cx="100" cy="45" r="3" fill="#2E8B83" />
                                            <circle cx="200" cy="25" r="3" fill="#2E8B83" />
                                        </svg>
                                    </div>
                                    <div class="flex justify-between text-[7px] sm:text-[9px] text-slate-400 font-semibold pt-1">
                                        <span>يناير</span><span>فبراير</span><span>مارس</span><span>أبريل</span><span>مايو</span><span>يونيو</span>
                                    </div>
                                </div>

                                {{-- Donut Chart (Students Breakdown) --}}
                                <div class="col-span-4 bg-white p-2.5 rounded-xl border border-slate-200/80 shadow-2xs flex flex-col justify-between">
                                    <span class="font-bold text-slate-800 text-[10px] sm:text-xs">توزيع الطلاب</span>
                                    <div class="relative w-12 h-12 sm:w-14 sm:h-14 mx-auto my-1">
                                        <svg viewBox="0 0 36 36" class="w-full h-full">
                                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e2e8f0" stroke-width="4"/>
                                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#2E8B83" stroke-width="4" stroke-dasharray="50, 100" stroke-linecap="round"/>
                                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#3b82f6" stroke-width="4" stroke-dasharray="30, 100" stroke-dashoffset="-50" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <div class="space-y-0.5 text-[7px] sm:text-[8px] text-slate-600 font-bold">
                                        <div class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-[#2E8B83]"></span> إبتدائي</div>
                                        <div class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> متوسط</div>
                                        <div class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> ثانوي</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bottom Grid: Daily Schedule & Recent Students --}}
                            <div class="grid grid-cols-2 gap-2.5">
                                {{-- Recent Students --}}
                                <div class="bg-white p-2.5 rounded-xl border border-slate-200/80 shadow-2xs space-y-1.5">
                                    <span class="font-bold text-slate-800 text-[10px] sm:text-xs block mb-1">الطلاب الجدد</span>
                                    <div class="flex items-center justify-between text-[8px] sm:text-[9px] bg-slate-50 p-1 rounded-lg">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-4 h-4 rounded-full bg-teal-100 text-[#2E8B83] flex items-center justify-center font-bold text-[7px]">م</div>
                                            <div>
                                                <div class="font-bold text-slate-800">محمد أحمد</div>
                                                <div class="text-[7px] text-slate-400">الصف الثاني الابتدائي</div>
                                            </div>
                                        </div>
                                        <span class="text-[7px] text-slate-400">12 مايو</span>
                                    </div>
                                    <div class="flex items-center justify-between text-[8px] sm:text-[9px] bg-slate-50 p-1 rounded-lg">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-4 h-4 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-[7px]">س</div>
                                            <div>
                                                <div class="font-bold text-slate-800">سارة علي</div>
                                                <div class="text-[7px] text-slate-400">الصف الخامس الابتدائي</div>
                                            </div>
                                        </div>
                                        <span class="text-[7px] text-slate-400">11 مايو</span>
                                    </div>
                                    <a href="#" class="text-[8px] font-bold text-[#2E8B83] block text-start hover:underline">عرض جميع الطلاب &rsaquo;</a>
                                </div>

                                {{-- Daily Schedule --}}
                                <div class="bg-white p-2.5 rounded-xl border border-slate-200/80 shadow-2xs space-y-1.5">
                                    <span class="font-bold text-slate-800 text-[10px] sm:text-xs block mb-1">الجدول اليومي</span>
                                    <div class="flex items-center justify-between text-[8px] sm:text-[9px] bg-slate-50 p-1 rounded-lg">
                                        <span class="font-bold text-slate-800">اللغة العربية 📕</span>
                                        <span class="text-[7px] font-mono text-slate-500">08:00 - 09:00</span>
                                    </div>
                                    <div class="flex items-center justify-between text-[8px] sm:text-[9px] bg-slate-50 p-1 rounded-lg">
                                        <span class="font-bold text-slate-800">رياضيات 📐</span>
                                        <span class="text-[7px] font-mono text-slate-500">09:15 - 10:15</span>
                                    </div>
                                    <div class="flex items-center justify-between text-[8px] sm:text-[9px] bg-slate-50 p-1 rounded-lg">
                                        <span class="font-bold text-slate-800">علوم 🧪</span>
                                        <span class="text-[7px] font-mono text-slate-500">10:30 - 11:30</span>
                                    </div>
                                    <a href="#" class="text-[8px] font-bold text-[#2E8B83] block text-start hover:underline">عرض الجدول بالكامل &rsaquo;</a>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Laptop Base Lip --}}
                    <div class="relative mx-auto bg-slate-800 rounded-b-xl h-2.5 sm:h-3 w-[104%] -ms-[2%] border-t border-slate-700">
                        <div class="w-16 h-1 bg-slate-600 rounded-full mx-auto mt-0.5"></div>
                    </div>

                    {{-- Overlapping Phone Mockup (Front Left in RTL) --}}
                    <div class="absolute -bottom-6 -start-4 sm:-start-6 lg:-start-10 w-[135px] sm:w-[155px] lg:w-[175px] z-20">
                        <div class="bg-slate-900 rounded-[26px] p-2 shadow-[0_20px_50px_rgba(15,23,42,0.3)] border border-slate-700">
                            {{-- Phone Speaker Notch --}}
                            <div class="flex justify-center pb-1.5">
                                <span class="w-8 h-1 rounded-full bg-slate-800"></span>
                            </div>
                            
                            {{-- Phone Screen --}}
                            <div class="bg-white rounded-[20px] p-2.5 text-[8px] leading-tight space-y-2 font-sans overflow-hidden">
                                {{-- App Top Header --}}
                                <div class="flex items-center justify-between border-b border-slate-100 pb-1">
                                    <span class="font-bold text-slate-900 text-[9px]">الرئيسية</span>
                                    <i class="fas fa-bell text-[8px] text-slate-400"></i>
                                </div>

                                {{-- Attendance Progress Ring --}}
                                <div class="text-center bg-slate-50 py-2 rounded-xl border border-slate-100">
                                    <div class="text-[7px] font-bold text-slate-400 mb-1">نسبة الحضور اليوم</div>
                                    <div class="relative w-12 h-12 mx-auto">
                                        <svg viewBox="0 0 36 36" class="w-full h-full -rotate-90">
                                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e2e8f0" stroke-width="3.5"/>
                                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#2E8B83" stroke-width="3.5" stroke-dasharray="90, 100" stroke-linecap="round"/>
                                        </svg>
                                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                                            <span class="text-[9px] font-black text-slate-900">90%</span>
                                            <span class="text-[5px] text-emerald-600 font-bold">+12%</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Quick Action Tiles --}}
                                <div class="grid grid-cols-2 gap-1.5 text-center text-[7px] font-bold">
                                    <div class="bg-teal-50 text-[#2E8B83] p-1.5 rounded-lg">
                                        <i class="fas fa-qrcode block text-[10px] mb-0.5"></i>
                                        <span>سجل الحضور</span>
                                    </div>
                                    <div class="bg-blue-50 text-blue-600 p-1.5 rounded-lg">
                                        <i class="fas fa-wallet block text-[10px] mb-0.5"></i>
                                        <span>شحن الرصيد</span>
                                    </div>
                                    <div class="bg-purple-50 text-purple-600 p-1.5 rounded-lg">
                                        <i class="fas fa-file-alt block text-[10px] mb-0.5"></i>
                                        <span>تسليم الواجب</span>
                                    </div>
                                    <div class="bg-amber-50 text-amber-600 p-1.5 rounded-lg">
                                        <i class="fas fa-bell block text-[10px] mb-0.5"></i>
                                        <span>الإشعارات</span>
                                    </div>
                                </div>

                                {{-- App Bottom Navigation --}}
                                <div class="flex justify-around items-center pt-1.5 border-t border-slate-100 text-[8px]">
                                    <i class="fas fa-home text-[#2E8B83]"></i>
                                    <i class="fas fa-calendar-alt text-slate-300"></i>
                                    <i class="fas fa-user-check text-slate-300"></i>
                                    <i class="fas fa-cog text-slate-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>