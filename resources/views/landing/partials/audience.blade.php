{{-- Section 3: Portals Section Matching Reference Image --}}
<section id="portals" class="py-20 lg:py-28 bg-white relative">
    <div class="container mx-auto px-6 lg:px-12 max-w-7xl">
        
        <!-- Section Eyebrow Centered -->
        <div class="text-center mb-16" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50/80 border border-emerald-200/70 text-xs font-bold shadow-2xs" style="color: #2E8B83;">
                <span>بوابات مخصصة لكل مستخدم</span>
            </div>
        </div>

        <!-- 3 Portal Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-8">
            
            <!-- 1. Teacher Portal Card (Purple Accent) -->
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 hover:border-indigo-200 hover:shadow-xl transition-all duration-300 flex flex-col group shadow-sm" data-animate="fade-in">
                <!-- Studio Cutout Image Container -->
                <div class="relative h-64 sm:h-72 overflow-hidden bg-gradient-to-b from-slate-50 to-white flex items-end justify-center pt-4">
                    <img src="{{ asset('images/portals/teacher_v2.png') }}" alt="بوابة المدرس" class="h-full w-auto object-contain object-bottom group-hover:scale-103 transition-transform duration-500">
                </div>

                <!-- Card Content -->
                <div class="p-6 sm:p-7 flex flex-col justify-between flex-grow bg-white border-t border-slate-100">
                    <div class="mb-6">
                        <!-- Icon Circle -->
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-[#5C52E6] flex items-center justify-center text-lg font-bold mb-3">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        
                        <h3 class="text-xl font-extrabold text-slate-900 mb-1">
                            بوابة المدرس
                        </h3>
                        <p class="text-xs text-slate-500 font-medium mb-4">
                            كل ما تحتاجه لإدارة صفوفك
                        </p>

                        <!-- Features Checklist -->
                        <ul class="space-y-2 text-xs font-semibold text-slate-700 list-none p-0 m-0">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-[#5C52E6] text-[10px]"></i>
                                <span>تسجيل الحضور والغياب</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-[#5C52E6] text-[10px]"></i>
                                <span>إضافة الواجبات والاختبارات</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-[#5C52E6] text-[10px]"></i>
                                <span>رفع المواد والملفات</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-[#5C52E6] text-[10px]"></i>
                                <span>التواصل مع الطلاب وأولياء الأمور</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('login.portal') }}" class="w-full py-3 rounded-xl text-white font-extrabold text-xs text-center text-decoration-none transition-all flex items-center justify-center gap-2 shadow-xs" style="background-color: #5C52E6; color: #ffffff;">
                        <span>دخول المدرس</span>
                        <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>
            </div>

            <!-- 2. Student Portal Card (Teal Accent) -->
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 hover:border-emerald-200 hover:shadow-xl transition-all duration-300 flex flex-col group shadow-sm" data-animate="fade-in">
                <!-- Studio Cutout Image Container -->
                <div class="relative h-64 sm:h-72 overflow-hidden bg-gradient-to-b from-slate-50 to-white flex items-end justify-center pt-4">
                    <img src="{{ asset('images/portals/student_v2.png') }}" alt="بوابة الطالب" class="h-full w-auto object-contain object-bottom group-hover:scale-103 transition-transform duration-500">
                </div>

                <!-- Card Content -->
                <div class="p-6 sm:p-7 flex flex-col justify-between flex-grow bg-white border-t border-slate-100">
                    <div class="mb-6">
                        <!-- Icon Circle -->
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center text-lg font-bold mb-3" style="color: #2E8B83;">
                            <i class="fas fa-book-open"></i>
                        </div>
                        
                        <h3 class="text-xl font-extrabold text-slate-900 mb-1">
                            بوابة الطالب
                        </h3>
                        <p class="text-xs text-slate-500 font-medium mb-4">
                            كل دراستك في مكان واحد
                        </p>

                        <!-- Features Checklist -->
                        <ul class="space-y-2 text-xs font-semibold text-slate-700 list-none p-0 m-0">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-[10px]" style="color: #2E8B83;"></i>
                                <span>عرض الجدول الدراسي</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-[10px]" style="color: #2E8B83;"></i>
                                <span>متابعة الواجبات والاختبارات</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-[10px]" style="color: #2E8B83;"></i>
                                <span>الاطلاع على الدرجات والتقييمات</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-[10px]" style="color: #2E8B83;"></i>
                                <span>التواصل مع المعلمين</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('login.portal') }}" class="w-full py-3 rounded-xl text-white font-extrabold text-xs text-center text-decoration-none transition-all flex items-center justify-center gap-2 shadow-xs" style="background-color: #2E8B83; color: #ffffff;">
                        <span>دخول الطالب</span>
                        <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>
            </div>

            <!-- 3. Parent Portal Card (Orange Accent) -->
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 hover:border-orange-200 hover:shadow-xl transition-all duration-300 flex flex-col group shadow-sm" data-animate="fade-in">
                <!-- Studio Cutout Image Container -->
                <div class="relative h-64 sm:h-72 overflow-hidden bg-gradient-to-b from-slate-50 to-white flex items-end justify-center pt-4">
                    <img src="{{ asset('images/portals/parent_v2.png') }}" alt="بوابة ولي الأمر" class="h-full w-auto object-contain object-bottom group-hover:scale-103 transition-transform duration-500">
                </div>

                <!-- Card Content -->
                <div class="p-6 sm:p-7 flex flex-col justify-between flex-grow bg-white border-t border-slate-100">
                    <div class="mb-6">
                        <!-- Icon Circle -->
                        <div class="w-10 h-10 rounded-2xl bg-orange-50 text-[#E05D26] flex items-center justify-center text-lg font-bold mb-3">
                            <i class="fas fa-users"></i>
                        </div>
                        
                        <h3 class="text-xl font-extrabold text-slate-900 mb-1">
                            بوابة ولي الأمر
                        </h3>
                        <p class="text-xs text-slate-500 font-medium mb-4">
                            تابع تقدم أبنائك بكل سهولة
                        </p>

                        <!-- Features Checklist -->
                        <ul class="space-y-2 text-xs font-semibold text-slate-700 list-none p-0 m-0">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-[#E05D26] text-[10px]"></i>
                                <span>متابعة الحضور والغياب</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-[#E05D26] text-[10px]"></i>
                                <span>الاطلاع على الدرجات والتقارير</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-[#E05D26] text-[10px]"></i>
                                <span>التواصل مع المدرسة والمعلمين</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-[#E05D26] text-[10px]"></i>
                                <span>استلام الإشعارات والتنبيهات</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('login.portal') }}" class="w-full py-3 rounded-xl text-white font-extrabold text-xs text-center text-decoration-none transition-all flex items-center justify-center gap-2 shadow-xs" style="background-color: #E05D26; color: #ffffff;">
                        <span>دخول ولي الأمر</span>
                        <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>