            <!-- Account Type (Smart Default — Minimal) -->
            <div x-show="currentStep === 1" x-cloak class="mb-3 relative z-10">
                <!-- Center: Slim Selected Bar -->
                <label class="relative cursor-pointer group block">
                    <input type="radio" value="center" x-model="accountType" class="peer sr-only" checked>
                    <div class="relative flex items-center gap-3 py-2.5 px-4 rounded-xl border-2 bg-white transition-all duration-300 overflow-hidden"
                         :class="accountType === 'center' 
                            ? 'border-brand-secondary/40 bg-brand-secondary/[0.03]' 
                            : 'border-slate-100 opacity-60 hover:border-slate-200'">
                        
                        <!-- Most Popular Badge -->
                        <template x-if="accountType === 'center'">
                            <div class="absolute top-0 ltr:right-0 rtl:left-0 flex items-center gap-1 bg-amber-500/90 text-white text-[8px] font-black uppercase tracking-wider px-2 py-0.5 ltr:rounded-bl-lg ltr:rounded-tr-xl rtl:rounded-br-lg rtl:rounded-tl-xl z-20">
                                <i class="bi bi-star-fill text-[7px]"></i>
                                {{ app()->isLocale('ar') ? 'الأكثر شيوعاً' : 'Popular' }}
                            </div>
                        </template>
                        
                        <!-- Icon -->
                        <div class="rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-300 w-9 h-9"
                             :class="accountType === 'center' 
                                ? 'bg-brand-secondary text-white shadow-sm shadow-brand-secondary/30' 
                                : 'bg-slate-100 text-slate-400'">
                            <i class="fas fa-university text-sm"></i>
                        </div>

                        <!-- Text -->
                        <div class="flex-1 min-w-0">
                            <span class="font-black text-sm block leading-tight transition-colors"
                                  :class="accountType === 'center' ? 'text-slate-800' : 'text-slate-500'">
                                {{ app()->isLocale('ar') ? 'مركز تعليمي' : 'Educational Center' }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium block">
                                {{ app()->isLocale('ar') ? 'طلاب · كورسات · حضور · فواتير' : 'Students · Courses · Attendance · Billing' }}
                            </span>
                        </div>

                        <!-- Check -->
                        <div class="flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center transition-all duration-300"
                             :class="accountType === 'center' 
                                ? 'bg-brand-secondary text-white' 
                                : 'bg-slate-100 opacity-0'">
                            <i class="bi bi-check2 text-xs"></i>
                        </div>
                    </div>
                </label>

                <!-- Instructor Toggle Link -->
                <div class="text-center mt-2">
                    <button type="button" 
                            @click="accountType = (accountType === 'instructor' ? 'center' : 'instructor')"
                            class="inline-flex items-center gap-1.5 text-[11px] font-bold transition-all duration-300 group px-3 py-1.5 rounded-lg"
                            :class="accountType === 'instructor' 
                                ? 'text-brand-secondary bg-brand-secondary/5 border border-brand-secondary/15' 
                                : 'text-slate-400 hover:text-slate-600'">
                        <i class="fas fa-chalkboard-teacher text-[10px]"
                           :class="accountType === 'instructor' ? 'text-brand-secondary' : ''"></i>
                        <span x-text="accountType === 'instructor' 
                            ? '{{ app()->isLocale('ar') ? '✓ مدرس مستقل — العودة لمركز تعليمي؟' : '✓ Tutor mode — switch to Center?' }}' 
                            : '{{ app()->isLocale('ar') ? 'مدرس مستقل؟ اضغط هنا' : 'Independent tutor? Click here' }}'">
                        </span>
                    </button>
                </div>
            </div>
