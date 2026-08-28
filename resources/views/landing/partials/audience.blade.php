{{-- Portals Section — 3 User Portal Cards Matching Reference Image --}}
<section id="portals" class="py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-5 lg:px-12 max-w-7xl">

        {{-- Section Title Header --}}
        <div class="text-center mb-12" data-animate="fade-in">
            <h2 class="text-2xl sm:text-3xl font-black text-[#2E8B83] tracking-tight">
                بوابات مخصصة لكل مستخدم
            </h2>
        </div>

        {{-- 3 User Portal Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8" data-animate="fade-in">

            {{-- Card 1: Teacher Portal --}}
            <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden hover:shadow-xl hover:border-[#2E8B83]/40 transition-all duration-300 flex flex-col">
                {{-- Card Banner Image --}}
                <div class="relative h-52 overflow-hidden bg-slate-100">
                    <img src="{{ asset('images/portals/teacher_v2.png') }}"
                         alt="بوابة المدرس"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                </div>

                {{-- Card Main Body --}}
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        {{-- Icon + Title Header --}}
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900">بوابة المدرس</h3>
                                <p class="text-xs text-slate-400 font-semibold">كل ما تحتاجه لإدارة صفوفك</p>
                            </div>
                        </div>

                        {{-- Features Bullets --}}
                        <ul class="space-y-2.5 my-5 text-xs sm:text-sm text-slate-600 font-medium">
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-check text-purple-600 text-xs"></i>
                                <span>تسجيل الحضور والغياب</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-check text-purple-600 text-xs"></i>
                                <span>إنشاء الواجبات والاختبارات</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-check text-purple-600 text-xs"></i>
                                <span>رفع المواد والملفات</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-check text-purple-600 text-xs"></i>
                                <span>التواصل مع الطلاب وأولياء الأمور</span>
                            </li>
                        </ul>
                    </div>

                    {{-- CTA Button --}}
                    <a href="{{ route('login.portal') }}"
                       class="w-full py-3 rounded-full text-sm font-bold text-center inline-flex items-center justify-center gap-2 text-decoration-none transition-all bg-[#4338ca] text-white hover:bg-[#3730a3] shadow-xs hover:shadow-md">
                        <span>دخول المدرس</span>
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>
            </div>

            {{-- Card 2: Student Portal --}}
            <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden hover:shadow-xl hover:border-[#2E8B83]/40 transition-all duration-300 flex flex-col">
                {{-- Card Banner Image --}}
                <div class="relative h-52 overflow-hidden bg-slate-100">
                    <img src="{{ asset('images/portals/student_v2.png') }}"
                         alt="بوابة الطالب"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                </div>

                {{-- Card Main Body --}}
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        {{-- Icon + Title Header --}}
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#2E8B83] flex items-center justify-center text-lg">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900">بوابة الطالب</h3>
                                <p class="text-xs text-slate-400 font-semibold">كل دراستك في مكان واحد</p>
                            </div>
                        </div>

                        {{-- Features Bullets --}}
                        <ul class="space-y-2.5 my-5 text-xs sm:text-sm text-slate-600 font-medium">
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-check text-[#2E8B83] text-xs"></i>
                                <span>عرض الجدول الدراسي</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-check text-[#2E8B83] text-xs"></i>
                                <span>متابعة الواجبات والاختبارات</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-check text-[#2E8B83] text-xs"></i>
                                <span>الاطلاع على الدرجات والتقييمات</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-check text-[#2E8B83] text-xs"></i>
                                <span>التواصل مع المعلمين</span>
                            </li>
                        </ul>
                    </div>

                    {{-- CTA Button --}}
                    <a href="{{ route('login.portal') }}"
                       class="w-full py-3 rounded-full text-sm font-bold text-center inline-flex items-center justify-center gap-2 text-decoration-none transition-all text-white shadow-xs hover:shadow-md"
                       style="background-color: #2E8B83;">
                        <span>دخول الطالب</span>
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>
            </div>

            {{-- Card 3: Parent Portal --}}
            <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden hover:shadow-xl hover:border-orange-500/40 transition-all duration-300 flex flex-col">
                {{-- Card Banner Image --}}
                <div class="relative h-52 overflow-hidden bg-slate-100">
                    <img src="{{ asset('images/portals/parent_v2.png') }}"
                         alt="بوابة ولي الأمر"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                </div>

                {{-- Card Main Body --}}
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        {{-- Icon + Title Header --}}
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-lg">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900">بوابة ولي الأمر</h3>
                                <p class="text-xs text-slate-400 font-semibold">تابع تقدم أبنائك بكل سهولة</p>
                            </div>
                        </div>

                        {{-- Features Bullets --}}
                        <ul class="space-y-2.5 my-5 text-xs sm:text-sm text-slate-600 font-medium">
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-check text-orange-600 text-xs"></i>
                                <span>متابعة الحضور والغياب</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-check text-orange-600 text-xs"></i>
                                <span>الاطلاع على الدرجات والتقارير</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-check text-orange-600 text-xs"></i>
                                <span>التواصل مع المدرسة والمعلمين</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-check text-orange-600 text-xs"></i>
                                <span>استلام الإشعارات والتنبيهات</span>
                            </li>
                        </ul>
                    </div>

                    {{-- CTA Button --}}
                    <a href="{{ route('login.portal') }}"
                       class="w-full py-3 rounded-full text-sm font-bold text-center inline-flex items-center justify-center gap-2 text-decoration-none transition-all text-white bg-orange-600 hover:bg-orange-700 shadow-xs hover:shadow-md">
                        <span>دخول ولي الأمر</span>
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>