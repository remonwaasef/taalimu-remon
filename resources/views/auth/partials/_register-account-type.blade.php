            <!-- Account Type Selection (Smart Default - Center Pre-Selected) -->
            <div x-show="currentStep === 1" x-cloak class="mb-4 transition-all duration-500 relative z-10">
                <!-- Pre-Selected Center Card (Primary Option) -->
                <div class="mb-3 relative">
                    <label class="relative cursor-pointer group block">
                        <input type="radio" value="center" x-model="accountType" class="peer sr-only" checked>
                        <div class="relative flex items-center gap-4 p-4 rounded-2xl border-2 bg-white transition-all duration-500 hover:shadow-xl overflow-hidden group shadow-md"
                             :class="accountType === 'center' 
                                ? 'border-brand-secondary ring-4 ring-brand-secondary/10 shadow-brand-secondary/10' 
                                : 'border-slate-100 opacity-70 hover:border-slate-200'">
                            
                            <!-- Most Popular Badge -->
                            <div class="absolute top-0 ltr:right-0 rtl:left-0 flex items-center gap-1 bg-amber-500 text-white text-[9px] font-black uppercase tracking-wider px-3 py-1 ltr:rounded-bl-xl ltr:rounded-tr-2xl rtl:rounded-br-xl rtl:rounded-tl-2xl z-20 shadow-lg shadow-amber-500/20">
                                <i class="bi bi-star-fill text-[8px]"></i>
                                <span>{{ app()->isLocale('ar') ? 'الأكثر شيوعاً' : 'Most Popular' }}</span>
                            </div>

                            <!-- Trial Badge -->
                            <div class="absolute bottom-0 ltr:right-0 rtl:left-0 bg-emerald-600 text-white text-[9px] font-black uppercase tracking-wider px-3 py-1 ltr:rounded-tl-xl ltr:rounded-br-2xl rtl:rounded-tr-xl rtl:rounded-bl-2xl z-20 shadow-lg shadow-emerald-600/20">
                                <i class="bi bi-lightning-fill me-1 text-[8px]"></i>
                                <span x-text="currentPlan.trial_days > 0 ? currentPlan.trial_days : '30'"></span> {{ app()->isLocale('ar') ? 'يوم مجاناً' : 'Days Free' }}
                            </div>

                            <!-- Background Accent -->
                            <div class="absolute top-0 ltr:right-0 rtl:left-0 w-32 h-32 rounded-full ltr:-mr-16 rtl:-ml-16 -mt-16 transition-transform duration-700 group-hover:scale-150"
                                 :class="accountType === 'center' ? 'bg-brand-secondary/5' : 'bg-slate-50'"></div>
                            
                            <!-- Icon Container -->
                            <div class="rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-500 group-hover:-rotate-6 shadow-lg w-14 h-14"
                                 :class="accountType === 'center' 
                                    ? 'bg-brand-secondary text-white shadow-brand-secondary/40' 
                                    : 'bg-slate-100 text-slate-400 group-hover:bg-brand-secondary/10 group-hover:text-brand-secondary'">
                                <i class="fas fa-university text-2xl transition-all duration-500"></i>
                            </div>

                            <!-- Text Content -->
                            <div class="flex-1 min-w-0 relative z-10">
                                <span class="font-black text-base block transition-all"
                                      :class="accountType === 'center' ? 'text-brand-secondary' : 'text-slate-600'">
                                    {{ app()->isLocale('ar') ? 'مركز تعليمي' : 'Educational Center' }}
                                </span>
                                <span class="text-[11px] text-slate-400 font-medium block mt-0.5">
                                    {{ app()->isLocale('ar') ? 'إدارة الطلاب، الكورسات، الحضور، والفواتير' : 'Manage students, courses, attendance & billing' }}
                                </span>
                            </div>

                            <!-- Success Check -->
                            <div class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center transition-all duration-300"
                                 :class="accountType === 'center' 
                                    ? 'bg-brand-secondary text-white scale-100' 
                                    : 'bg-slate-100 scale-75 opacity-0'">
                                <i class="bi bi-check2 text-sm font-bold"></i>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Independent Instructor Link (Secondary Option) -->
                <div class="text-center mt-3 mb-2">
                    <button type="button" 
                            @click="accountType = (accountType === 'instructor' ? 'center' : 'instructor')"
                            class="inline-flex items-center gap-2 text-xs font-bold transition-all duration-300 group px-4 py-2.5 rounded-xl"
                            :class="accountType === 'instructor' 
                                ? 'text-brand-secondary bg-brand-secondary/5 border border-brand-secondary/20 shadow-sm' 
                                : 'text-slate-400 hover:text-brand-secondary hover:bg-slate-50'">
                        <i class="fas fa-chalkboard-teacher text-sm transition-transform group-hover:scale-110"
                           :class="accountType === 'instructor' ? 'text-brand-secondary' : ''"></i>
                        <span x-text="accountType === 'instructor' 
                            ? '{{ app()->isLocale('ar') ? '✓ تم اختيار مدرس مستقل — العودة لمركز تعليمي؟' : '✓ Independent Tutor selected — switch back to Center?' }}' 
                            : '{{ app()->isLocale('ar') ? 'هل أنت مدرس مستقل؟ سجّل من هنا' : 'Are you an independent tutor? Register here' }}'">
                        </span>
                    </button>
                </div>

                <!-- Prominent Centered Login Link (Always visible right below selection) -->
                <div class="mt-4 text-center">
                    <span class="text-xs sm:text-sm text-slate-600 font-arabic font-bold inline-flex items-center gap-1 bg-slate-100/80 px-4 py-2 rounded-full border border-slate-200/60 shadow-sm">
                        {{ __('auth.login.no_account_link') }}
                        <a href="{{ route('login.portal') }}" class="text-brand-secondary font-black hover:underline me-1">{{ __('auth.login.title') }}</a>
                    </span>
                </div>
            </div>
