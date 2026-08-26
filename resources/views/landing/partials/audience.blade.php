<section id="solutions" class="py-20 lg:py-28 bg-slate-50 relative">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-50 border border-teal-200/80 text-[#2E8B83] text-xs font-bold mb-4 shadow-sm">
                <i class="fas fa-users text-xs"></i>
                <span>{{ __('landing.audiences.badge') }}</span>
            </div>

            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
                {{ __('landing.audiences.title') }}
            </h2>

            <p class="text-slate-600 font-medium text-base">
                {{ __('landing.audiences.subtitle') }}
            </p>
        </div>

        <!-- 4 Audience Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- 1. Teacher Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 hover:shadow-xl transition-all duration-300 flex flex-col justify-between" data-animate="fade-in">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-[#2E8B83] flex items-center justify-center text-xl mb-4 font-bold">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <span class="text-xs font-bold text-[#2E8B83] uppercase tracking-wider block mb-1">
                        {{ __('landing.audiences.teacher.role') }}
                    </span>
                    <h3 class="text-base font-extrabold text-slate-900 mb-4">
                        {{ __('landing.audiences.teacher.title') }}
                    </h3>
                    <ul class="space-y-2.5 mb-6">
                        @foreach(__('landing.audiences.teacher.features') as $feat)
                            <li class="flex items-start gap-2 text-xs font-semibold text-slate-700">
                                <i class="fas fa-check text-emerald-500 mt-0.5"></i>
                                <span>{{ $feat }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <a href="{{ route('register') }}" data-track="landing_audience_teacher_cta" class="w-full py-2.5 rounded-xl border border-slate-200 hover:border-[#2E8B83] hover:bg-emerald-50/50 text-[#2E8B83] font-bold text-xs text-center text-decoration-none transition-colors">
                    {{ __('landing.mockups.audience_teacher_cta') }}
                </a>
            </div>

            <!-- 2. Educational Center Card -->
            <div class="bg-white rounded-3xl p-6 border border-teal-200/90 shadow-lg relative flex flex-col justify-between" data-animate="fade-in">
                <span class="absolute -top-3 end-6 px-2.5 py-0.5 rounded-full bg-emerald-500 text-white text-[10px] font-bold shadow">
                    {{ __('landing.mockups.audience_center_badge') }}
                </span>
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-xl mb-4 font-bold shadow-md">
                        <i class="fas fa-school"></i>
                    </div>
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block mb-1">
                        {{ __('landing.audiences.center.role') }}
                    </span>
                    <h3 class="text-base font-extrabold text-slate-900 mb-4">
                        {{ __('landing.audiences.center.title') }}
                    </h3>
                    <ul class="space-y-2.5 mb-6">
                        @foreach(__('landing.audiences.center.features') as $feat)
                            <li class="flex items-start gap-2 text-xs font-semibold text-slate-700">
                                <i class="fas fa-check text-emerald-500 mt-0.5"></i>
                                <span>{{ $feat }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <a href="{{ route('register') }}" data-track="landing_audience_center_cta" class="w-full py-2.5 rounded-xl text-white font-bold text-xs text-center text-decoration-none shadow-md" style="background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);">
                    {{ __('landing.mockups.audience_center_cta') }}
                </a>
            </div>

            <!-- 3. Student Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 hover:shadow-xl transition-all duration-300 flex flex-col justify-between" data-animate="fade-in">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-4 font-bold">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">
                        {{ __('landing.audiences.student.role') }}
                    </span>
                    <h3 class="text-base font-extrabold text-slate-900 mb-4">
                        {{ __('landing.audiences.student.title') }}
                    </h3>
                    <ul class="space-y-2.5 mb-6">
                        @foreach(__('landing.audiences.student.features') as $feat)
                            <li class="flex items-start gap-2 text-xs font-semibold text-slate-700">
                                <i class="fas fa-check text-blue-500 mt-0.5"></i>
                                <span>{{ $feat }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <a href="{{ route('login.portal') }}" class="w-full py-2.5 rounded-xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50/50 text-blue-600 font-bold text-xs text-center text-decoration-none transition-colors">
                    {{ __('landing.mockups.audience_student_cta') }}
                </a>
            </div>

            <!-- 4. Parent Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 hover:shadow-xl transition-all duration-300 flex flex-col justify-between" data-animate="fade-in">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-4 font-bold">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <span class="text-xs font-bold text-purple-600 uppercase tracking-wider block mb-1">
                        {{ __('landing.audiences.parent.role') }}
                    </span>
                    <h3 class="text-base font-extrabold text-slate-900 mb-4">
                        {{ __('landing.audiences.parent.title') }}
                    </h3>
                    <ul class="space-y-2.5 mb-6">
                        @foreach(__('landing.audiences.parent.features') as $feat)
                            <li class="flex items-start gap-2 text-xs font-semibold text-slate-700">
                                <i class="fas fa-check text-purple-500 mt-0.5"></i>
                                <span>{{ $feat }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <a href="{{ route('login.portal') }}" class="w-full py-2.5 rounded-xl border border-slate-200 hover:border-purple-500 hover:bg-purple-50/50 text-purple-600 font-bold text-xs text-center text-decoration-none transition-colors">
                    {{ __('landing.mockups.audience_parent_cta') }}
                </a>
            </div>

        </div>

    </div>
</section>