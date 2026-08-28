{{-- Portals Section — 3 User Portal Cards Matching Reference Image --}}
<section id="portals" class="py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-5 lg:px-12 max-w-7xl">

        {{-- Section Header --}}
        <div class="text-center mb-12" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#e8f5f3] border border-[#c5e8e4] text-xs font-bold text-[#2E8B83] mb-4">
                <i class="fas fa-users text-[10px]"></i>
                <span>بوابات مخصصة لكل مستخدم</span>
            </div>
        </div>

        {{-- 3 Portal Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8" data-animate="fade-in">

            {{-- Card 1: Teacher Portal --}}
            <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-[#2E8B83]/30 transition-all duration-300">
                {{-- Card Image --}}
                <div class="relative h-48 overflow-hidden bg-gradient-to-br from-[#e8f5f3] to-[#d1ede9]">
                    <img src="{{ asset('images/portals/teacher_v2.png') }}"
                         alt="بوابة المدرس"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                </div>
                {{-- Card Content --}}
                <div class="p-5">
                    {{-- Icon + Title --}}
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-[#e8f5f3] flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-[#2E8B83]"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">بوابة المدرس</h3>
                    </div>
                    {{-- Features List --}}
                    <ul class="space-y-2 mb-5 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-[#2E8B83] text-xs mt-1"></i>
                            <span>إدارة الحضور والغياب</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-[#2E8B83] text-xs mt-1"></i>
                            <span>رصد الدرجات والتقييمات</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-[#2E8B83] text-xs mt-1"></i>
                            <span>الاطلاع على الجدول والتقارير</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-[#2E8B83] text-xs mt-1"></i>
                            <span>رفع المواد والأنشطة</span>
                        </li>
                    </ul>
                    {{-- CTA Button --}}
                    <a href="{{ route('register') }}"
                       class="w-full py-2.5 rounded-xl text-sm font-bold text-center inline-flex items-center justify-center gap-2 text-decoration-none transition-all border-2 border-[#2E8B83] text-[#2E8B83] hover:bg-[#2E8B83] hover:text-white">
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                        <span>دخول المدرس</span>
                    </a>
                </div>
            </div>

            {{-- Card 2: Student Portal --}}
            <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-[#2E8B83]/30 transition-all duration-300">
                {{-- Card Image --}}
                <div class="relative h-48 overflow-hidden bg-gradient-to-br from-[#e8f5f3] to-[#d1ede9]">
                    <img src="{{ asset('images/portals/student_v2.png') }}"
                         alt="بوابة الطالب"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                </div>
                {{-- Card Content --}}
                <div class="p-5">
                    {{-- Icon + Title --}}
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-[#e8f5f3] flex items-center justify-center">
                            <i class="fas fa-user-graduate text-[#2E8B83]"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">بوابة الطالب</h3>
                    </div>
                    {{-- Subtitle --}}
                    <p class="text-xs text-slate-400 font-medium mb-2">كل دراستك في مكان واحد</p>
                    {{-- Features List --}}
                    <ul class="space-y-2 mb-5 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-[#2E8B83] text-xs mt-1"></i>
                            <span>كل ما تحتاجه لإدارة صفوفك</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-[#2E8B83] text-xs mt-1"></i>
                            <span>متابعة الواجبات والأنشطة</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-[#2E8B83] text-xs mt-1"></i>
                            <span>الاطلاع على التقييمات</span>
                        </li>
                    </ul>
                    {{-- CTA Button --}}
                    <a href="{{ route('register') }}"
                       class="w-full py-2.5 rounded-xl text-sm font-bold text-center inline-flex items-center justify-center gap-2 text-decoration-none transition-all text-white"
                       style="background-color: #2E8B83;">
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                        <span>دخول الطالب</span>
                    </a>
                </div>
            </div>

            {{-- Card 3: Parent Portal --}}
            <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-[#2E8B83]/30 transition-all duration-300">
                {{-- Card Image --}}
                <div class="relative h-48 overflow-hidden bg-gradient-to-br from-[#e8f5f3] to-[#d1ede9]">
                    <img src="{{ asset('images/portals/parent_v2.png') }}"
                         alt="بوابة ولي الأمر"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                </div>
                {{-- Card Content --}}
                <div class="p-5">
                    {{-- Icon + Title --}}
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-[#fff3e0] flex items-center justify-center">
                            <i class="fas fa-user-friends text-orange-500"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">بوابة ولي الأمر</h3>
                    </div>
                    {{-- Subtitle --}}
                    <p class="text-xs text-slate-400 font-medium mb-2">تابع تقدم أبنائك بكل سهولة</p>
                    {{-- Features List --}}
                    <ul class="space-y-2 mb-5 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-orange-500 text-xs mt-1"></i>
                            <span>متابعة الحضور والغياب</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-orange-500 text-xs mt-1"></i>
                            <span>الاطلاع على الدرجات والتقارير</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-orange-500 text-xs mt-1"></i>
                            <span>استقبال الإشعارات والتنبيهات</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-orange-500 text-xs mt-1"></i>
                            <span>التواصل مع المؤسسة والمدرسين</span>
                        </li>
                    </ul>
                    {{-- CTA Button --}}
                    <a href="{{ route('register') }}"
                       class="w-full py-2.5 rounded-xl text-sm font-bold text-center inline-flex items-center justify-center gap-2 text-decoration-none transition-all border-2 border-orange-500 text-orange-500 hover:bg-orange-500 hover:text-white">
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                        <span>دخول ولي الأمر</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>