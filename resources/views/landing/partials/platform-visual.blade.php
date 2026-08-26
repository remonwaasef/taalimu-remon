<section class="py-20 lg:py-28 bg-white relative overflow-hidden">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl relative z-10">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-50 border border-teal-200/80 text-[#2E8B83] text-xs font-bold mb-4 shadow-sm">
                <i class="fas fa-hubspot text-xs"></i>
                <span>{{ __('landing.platform_visual.badge') }}</span>
            </div>

            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
                {{ __('landing.platform_visual.title') }}
            </h2>

            <p class="text-slate-600 font-medium text-base">
                {{ __('landing.platform_visual.subtitle') }}
            </p>
        </div>

        <!-- Central Platform Visual Diagram Container -->
        <div class="max-w-4xl mx-auto bg-slate-50/80 rounded-3xl p-8 sm:p-12 border border-slate-200/80 relative shadow-xl" data-animate="scale-in">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center relative z-10">
                
                <!-- Left Nodes: Teacher & Center -->
                <div class="flex flex-col gap-6">
                    <!-- Teacher Node -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-md flex items-center gap-4 hover:border-[#2E8B83] transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-teal-50 text-[#2E8B83] flex items-center justify-center text-xl shrink-0 font-bold">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm mb-0.5">{{ __('landing.platform_visual.node_teacher') }}</h4>
                            <span class="text-xs text-slate-500">{{ __('landing.mockups.node_teacher_sub') }}</span>
                        </div>
                    </div>

                    <!-- Center Node -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-md flex items-center gap-4 hover:border-emerald-500 transition-colors">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-xl shrink-0 font-bold shadow-sm">
                            <i class="fas fa-school"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm mb-0.5">{{ __('landing.platform_visual.node_center') }}</h4>
                            <span class="text-xs text-slate-500">{{ __('landing.mockups.node_center_sub') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Central Engine Node: Taalimu -->
                <div class="my-4 md:my-0 text-center">
                    <div class="w-28 h-28 mx-auto rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white flex flex-col items-center justify-center shadow-2xl border-4 border-white relative group">
                        <div class="absolute inset-0 bg-emerald-500/20 rounded-3xl blur-xl group-hover:bg-emerald-500/30 transition-all pointer-events-none"></div>
                        <img src="{{ asset('images/brand/logo-icon.png') }}" alt="Taalimu Engine" class="h-10 w-auto mb-1.5 relative z-10">
                        <span class="text-[11px] font-extrabold text-emerald-400 relative z-10 font-mono tracking-wider">TAALIMU</span>
                    </div>
                </div>

                <!-- Right Nodes: Student & Parent -->
                <div class="flex flex-col gap-6">
                    <!-- Student Node -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-md flex items-center gap-4 hover:border-blue-500 transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0 font-bold">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm mb-0.5">{{ __('landing.platform_visual.node_student') }}</h4>
                            <span class="text-xs text-slate-500">{{ __('landing.mockups.node_student_sub') }}</span>
                        </div>
                    </div>

                    <!-- Parent Node -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-md flex items-center gap-4 hover:border-purple-500 transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0 font-bold">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm mb-0.5">{{ __('landing.platform_visual.node_parent') }}</h4>
                            <span class="text-xs text-slate-500">{{ __('landing.mockups.node_parent_sub') }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>