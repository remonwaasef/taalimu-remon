{{-- Section 2: Hero Section Matching Image 2 --}}
<section id="hero" class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden bg-gradient-to-b from-[#F7FAF9] via-white to-white">
    <div class="container mx-auto px-6 lg:px-12 max-w-7xl relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-14 items-center">
            
            <!-- Right Column: Confident Arabic Typography & CTA (RTL) -->
            <div class="lg:col-span-6 text-center lg:text-start" data-animate="fade-in">
                
                <!-- Eyebrow Badge with Green Dot -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50/80 border border-emerald-200/70 text-slate-700 text-xs font-bold mb-6 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-[#008A70]"></span>
                    <span>منصة متكاملة لإدارة المؤسسات التعليمية</span>
                </div>

                <!-- Main Headline -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl xl:text-[54px] font-black text-slate-900 leading-[1.3] tracking-tight mb-6">
                    كل ما تحتاجه لإدارة<br>
                    <span class="text-[#008A70]">مؤسستك التعليمية</span><br>
                    في منصة واحدة
                </h1>

                <!-- Supporting Copy -->
                <p class="text-base sm:text-lg text-slate-600 font-medium leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0">
                    إدارة الطلبة والمعلمين والفصول والدرجات والمدفوعات، التقارير والتواصل... بسهولة تامة من أي مكان وفي أي وقت.
                </p>

                <!-- Two Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 mb-10">
                    <a href="{{ route('register') }}" data-track="hero_primary_cta" class="w-full sm:w-auto px-8 py-3.5 rounded-xl text-white font-extrabold text-sm sm:text-base text-decoration-none shadow-sm hover:shadow-md transition-all transform hover:-translate-y-0.5 bg-[#008A70] hover:bg-[#00745e] text-center flex items-center justify-center gap-2">
                        <span>ابدأ مجاناً الآن</span>
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                    </a>

                    <button
                        type="button"
                        @click="$dispatch('open-demo-modal')"
                        class="w-full sm:w-auto px-6 py-3.5 rounded-xl border border-slate-200 bg-white text-slate-800 font-bold text-sm sm:text-base hover:bg-slate-50 transition-all text-center flex items-center justify-center gap-2 shadow-2xs"
                    >
                        <i class="far fa-calendar-alt text-[#008A70]"></i>
                        <span>احجز عرض توضيحي</span>
                    </button>
                </div>

                <!-- Three Trust Points Bar -->
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-y-2 gap-x-6 text-xs font-bold text-slate-500">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-credit-card text-[#008A70]"></i>
                        <span>بدون بطاقة ائتمان</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-headset text-[#008A70]"></i>
                        <span>دعم فني 24/7</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-bolt text-[#008A70]"></i>
                        <span>إعداد سريع خلال دقائق</span>
                    </div>
                </div>

            </div>

            <!-- Left Column: Realistic Sleek Laptop with Dashboard & Smartphone (Matching Image 2) -->
            <div class="lg:col-span-6 relative" data-animate="scale-in">
                
                <!-- Realistic Laptop Hardware Container -->
                <div class="relative mx-auto max-w-lg lg:max-w-none">
                    
                    <!-- Screen Frame -->
                    <div class="bg-slate-900 rounded-t-2xl p-2.5 shadow-[0_25px_60px_-15px_rgba(15,23,42,0.18)] border border-slate-800">
                        <!-- Top Camera Notch -->
                        <div class="flex items-center justify-center pb-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-700"></span>
                        </div>

                        <!-- Real Dashboard Interface Window -->
                        <div class="bg-white rounded-lg overflow-hidden p-3 border border-slate-100 text-slate-800 text-xs select-none">
                            
                            <!-- Dashboard Header -->
                            <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="font-extrabold text-xs text-slate-900">مرحباً بك في Taalimu 👋</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-[10px] font-bold">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Top 4 Stats Metric Cards -->
                            <div class="grid grid-cols-4 gap-2 mb-2.5">
                                <div class="bg-slate-50 p-2 rounded-lg border border-slate-100 text-center">
                                    <span class="text-[9px] text-slate-400 block mb-0.5">إجمالي الطلاب</span>
                                    <span class="text-xs font-black text-slate-900 block leading-none">1,250</span>
                                    <span class="text-[8px] font-bold text-emerald-600">+12%</span>
                                </div>
                                <div class="bg-slate-50 p-2 rounded-lg border border-slate-100 text-center">
                                    <span class="text-[9px] text-slate-400 block mb-0.5">المعلمون</span>
                                    <span class="text-xs font-black text-slate-900 block leading-none">320</span>
                                    <span class="text-[8px] font-bold text-emerald-600">+8%</span>
                                </div>
                                <div class="bg-slate-50 p-2 rounded-lg border border-slate-100 text-center">
                                    <span class="text-[9px] text-slate-400 block mb-0.5">الفصول</span>
                                    <span class="text-xs font-black text-slate-900 block leading-none">26</span>
                                    <span class="text-[8px] font-bold text-emerald-600">+5%</span>
                                </div>
                                <div class="bg-slate-50 p-2 rounded-lg border border-slate-100 text-center">
                                    <span class="text-[9px] text-slate-400 block mb-0.5">إجمالي المدفوعات</span>
                                    <span class="text-xs font-black text-slate-900 block leading-none">8,450</span>
                                    <span class="text-[8px] font-bold text-emerald-600">+16%</span>
                                </div>
                            </div>

                            <!-- Charts Row -->
                            <div class="grid grid-cols-12 gap-2 mb-2.5">
                                <!-- Attendance Chart -->
                                <div class="col-span-8 bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-[10px] font-bold text-slate-800">نسبة الحضور</span>
                                        <span class="text-[8px] text-slate-400 font-bold">هذا الأسبوع</span>
                                    </div>
                                    <!-- Wave Graph Simulation -->
                                    <svg viewBox="0 0 200 45" class="w-full h-10 stroke-[#008A70] fill-emerald-50/50">
                                        <path d="M0,35 Q25,10 50,22 T100,15 T150,25 T200,8 L200,45 L0,45 Z" />
                                        <path d="M0,35 Q25,10 50,22 T100,15 T150,25 T200,8" fill="none" stroke-width="2" />
                                    </svg>
                                    <div class="flex justify-between text-[7px] text-slate-400 pt-1 font-mono">
                                        <span>يناير</span><span>فبراير</span><span>مارس</span><span>أبريل</span><span>مايو</span><span>يونيو</span>
                                    </div>
                                </div>

                                <!-- Student Distribution Donut Chart -->
                                <div class="col-span-4 bg-slate-50 p-2.5 rounded-lg border border-slate-100 flex flex-col justify-between">
                                    <span class="text-[10px] font-bold text-slate-800 text-center">توزيع الطلاب</span>
                                    <div class="relative w-12 h-12 mx-auto my-1">
                                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                                            <circle cx="18" cy="18" r="14" fill="transparent" stroke="#E2E8F0" stroke-width="4"></circle>
                                            <circle cx="18" cy="18" r="14" fill="transparent" stroke="#008A70" stroke-width="4" stroke-dasharray="60 100"></circle>
                                            <circle cx="18" cy="18" r="14" fill="transparent" stroke="#38BDF8" stroke-width="4" stroke-dasharray="25 100" stroke-dashoffset="-60"></circle>
                                        </svg>
                                    </div>
                                    <div class="flex justify-around text-[7px] text-slate-500 font-bold">
                                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-[#008A70]"></span>ابتدائي</span>
                                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>متوسط</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Row: Recent Students & Daily Schedule -->
                            <div class="grid grid-cols-2 gap-2">
                                <div class="bg-slate-50 p-2 rounded-lg border border-slate-100">
                                    <span class="text-[9px] font-bold text-slate-800 block mb-1.5">الطلاب الجدد</span>
                                    <div class="space-y-1 text-[8px]">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-slate-700">محمد أحمد</span>
                                            <span class="text-slate-400">الصف الرابع</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-slate-700">سارة علي</span>
                                            <span class="text-slate-400">الصف الثاني</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-slate-50 p-2 rounded-lg border border-slate-100">
                                    <span class="text-[9px] font-bold text-slate-800 block mb-1.5">الجدول اليومي</span>
                                    <div class="space-y-1 text-[8px]">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-slate-700">اللغة العربية</span>
                                            <span class="text-slate-400 font-mono">08:00 - 09:00</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-slate-700">رياضيات</span>
                                            <span class="text-slate-400 font-mono">09:15 - 10:15</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Laptop Base Chin -->
                    <div class="h-3 bg-gradient-to-b from-slate-300 to-slate-400 rounded-b-xl max-w-[104%] -ms-[2%] shadow-md flex items-center justify-center">
                        <div class="w-20 h-1 bg-slate-500/40 rounded-full"></div>
                    </div>

                    <!-- Smartphone Overlay in Front (Matching Image 2) -->
                    <div class="absolute -bottom-6 -start-4 sm:-start-6 w-44 sm:w-48 bg-slate-900 p-2.5 rounded-3xl shadow-[0_25px_50px_rgba(15,23,42,0.28)] border-2 border-slate-800 hidden sm:block transform rotate-1 hover:rotate-0 transition-transform">
                        <div class="bg-white rounded-2xl overflow-hidden p-3 border border-slate-100">
                            <!-- Phone Top Attendance Card -->
                            <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100 mb-2">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[9px] font-bold text-slate-700">نسبة الحضور اليوم</span>
                                    <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-1 py-0.5 rounded">+12%</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-base font-black text-slate-900">90%</span>
                                    <div class="w-5 h-5 rounded-full border-2 border-[#008A70] border-t-transparent animate-spin"></div>
                                </div>
                            </div>

                            <!-- 4 Mini Quick Action Buttons -->
                            <div class="grid grid-cols-2 gap-1.5 mb-2">
                                <div class="bg-emerald-50/70 p-1.5 rounded-lg text-center border border-emerald-100">
                                    <i class="fas fa-qrcode text-[#008A70] text-[10px] mb-0.5"></i>
                                    <span class="text-[7px] font-bold text-slate-800 block">تسجيل حضور</span>
                                </div>
                                <div class="bg-slate-50 p-1.5 rounded-lg text-center border border-slate-100">
                                    <i class="fas fa-wallet text-slate-600 text-[10px] mb-0.5"></i>
                                    <span class="text-[7px] font-bold text-slate-800 block">شحن الرصيد</span>
                                </div>
                                <div class="bg-slate-50 p-1.5 rounded-lg text-center border border-slate-100">
                                    <i class="fas fa-calendar-alt text-slate-600 text-[10px] mb-0.5"></i>
                                    <span class="text-[7px] font-bold text-slate-800 block">جدول الحضور</span>
                                </div>
                                <div class="bg-slate-50 p-1.5 rounded-lg text-center border border-slate-100">
                                    <i class="fas fa-bell text-slate-600 text-[10px] mb-0.5"></i>
                                    <span class="text-[7px] font-bold text-slate-800 block">إشعارات</span>
                                </div>
                            </div>

                            <!-- Bottom Mini Bar -->
                            <div class="flex justify-around pt-1 border-t border-slate-100 text-[8px] text-slate-400 font-bold">
                                <span class="text-[#008A70]">الرئيسية</span>
                                <span>الطلاب</span>
                                <span>الحصص</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>