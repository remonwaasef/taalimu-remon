{{-- Section 3: User Portals (Teacher, Student, Parent) --}}
<section id="portals" class="py-24 lg:py-36 bg-white relative">
    <div class="container mx-auto px-6 lg:px-12 max-w-7xl">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 lg:mb-20" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/60 text-[#2E8B83] text-xs font-bold mb-4 shadow-2xs">
                <i class="fas fa-users text-xs"></i>
                <span>بوابات المنظومة</span>
            </div>

            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 leading-tight mb-4">
                تجربة مخصصة لكل دور<br>
                في منظومتك التعليمية
            </h2>

            <p class="text-slate-600 font-medium text-base sm:text-lg max-w-2xl mx-auto">
                واجهات مستقلة ومصممة بعناية تلبي الاحتياجات اليومية لكل فرد داخل المؤسسة.
            </p>
        </div>

        <!-- 3 Large Premium Portal Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-10">
            
            <!-- 1. Teacher Portal Card -->
            <div class="bg-[#FAFBFB] rounded-3xl overflow-hidden border border-slate-200/70 hover:border-slate-300 hover:shadow-xl transition-all duration-300 flex flex-col group" data-animate="fade-in">
                <!-- Portrait Image Container -->
                <div class="relative h-72 sm:h-80 overflow-hidden bg-slate-100">
                    <img src="{{ asset('images/portals/teacher.jpg') }}" alt="بوابة المدرس - Taalimu" class="w-full h-full object-cover object-top group-hover:scale-103 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 start-4 flex items-center gap-2 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/60 shadow-xs">
                        <i class="fas fa-chalkboard-teacher text-[#2E8B83] text-xs"></i>
                        <span class="text-xs font-extrabold text-slate-900">المعلم والمحاضر</span>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6 sm:p-8 flex flex-col justify-between flex-grow">
                    <div class="mb-6">
                        <h3 class="text-xl font-extrabold text-slate-900 mb-2">
                            بوابة المدرس
                        </h3>
                        <p class="text-slate-600 text-sm font-medium leading-relaxed">
                            كل ما تحتاجه لإدارة فصولك وطلابك، رصد الحضور، ومتابعة الدرجات والتقارير.
                        </p>
                    </div>

                    <a href="{{ route('login.portal') }}" class="w-full py-3 rounded-xl border border-slate-200 text-slate-800 font-bold text-xs hover:border-[#2E8B83] hover:bg-emerald-50/50 hover:text-[#2E8B83] text-center text-decoration-none transition-all flex items-center justify-center gap-2">
                        <span>دخول المدرس</span>
                        <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>
            </div>

            <!-- 2. Student Portal Card -->
            <div class="bg-[#FAFBFB] rounded-3xl overflow-hidden border border-slate-200/70 hover:border-slate-300 hover:shadow-xl transition-all duration-300 flex flex-col group" data-animate="fade-in">
                <!-- Portrait Image Container -->
                <div class="relative h-72 sm:h-80 overflow-hidden bg-slate-100">
                    <img src="{{ asset('images/portals/student.jpg') }}" alt="بوابة الطالب - Taalimu" class="w-full h-full object-cover object-top group-hover:scale-103 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 start-4 flex items-center gap-2 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/60 shadow-xs">
                        <i class="fas fa-user-graduate text-[#2E8B83] text-xs"></i>
                        <span class="text-xs font-extrabold text-slate-900">الطالب</span>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6 sm:p-8 flex flex-col justify-between flex-grow">
                    <div class="mb-6">
                        <h3 class="text-xl font-extrabold text-slate-900 mb-2">
                            بوابة الطالب
                        </h3>
                        <p class="text-slate-600 text-sm font-medium leading-relaxed">
                            تجربة تعلم ذكية وسلسة بين يديك: الجدول، الواجبات، الكرنيه الإلكتروني، والنتائج.
                        </p>
                    </div>

                    <a href="{{ route('login.portal') }}" class="w-full py-3 rounded-xl border border-slate-200 text-slate-800 font-bold text-xs hover:border-[#2E8B83] hover:bg-emerald-50/50 hover:text-[#2E8B83] text-center text-decoration-none transition-all flex items-center justify-center gap-2">
                        <span>دخول الطالب</span>
                        <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>
            </div>

            <!-- 3. Parent Portal Card -->
            <div class="bg-[#FAFBFB] rounded-3xl overflow-hidden border border-slate-200/70 hover:border-slate-300 hover:shadow-xl transition-all duration-300 flex flex-col group" data-animate="fade-in">
                <!-- Portrait Image Container -->
                <div class="relative h-72 sm:h-80 overflow-hidden bg-slate-100">
                    <img src="{{ asset('images/portals/parent.jpg') }}" alt="بوابة ولي الأمر - Taalimu" class="w-full h-full object-cover object-top group-hover:scale-103 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 start-4 flex items-center gap-2 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/60 shadow-xs">
                        <i class="fas fa-shield-heart text-[#2E8B83] text-xs"></i>
                        <span class="text-xs font-extrabold text-slate-900">ولي الأمر</span>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6 sm:p-8 flex flex-col justify-between flex-grow">
                    <div class="mb-6">
                        <h3 class="text-xl font-extrabold text-slate-900 mb-2">
                            بوابة ولي الأمر
                        </h3>
                        <p class="text-slate-600 text-sm font-medium leading-relaxed">
                            اطمئنان دائم ومتابعة لحظية لأبنائك: الحضور، الأداء الأكاديمي، والمستحقات المالية.
                        </p>
                    </div>

                    <a href="{{ route('login.portal') }}" class="w-full py-3 rounded-xl border border-slate-200 text-slate-800 font-bold text-xs hover:border-[#2E8B83] hover:bg-emerald-50/50 hover:text-[#2E8B83] text-center text-decoration-none transition-all flex items-center justify-center gap-2">
                        <span>دخول ولي الأمر</span>
                        <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>