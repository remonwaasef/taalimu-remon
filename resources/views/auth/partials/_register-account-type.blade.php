            <!-- Account Type Selection (Premium Position) -->
            <div x-show="currentStep === 1" x-cloak class="mb-4 transition-all duration-700 ease-in-out relative z-10" :class="!accountType ? 'transform scale-110 translate-y-[3vh] pb-12 mt-4' : ''">
                <label class="text-[11px] font-black text-slate-500 px-1 font-arabic uppercase tracking-wider block mb-3 text-center opacity-70 transition-all duration-700" :class="!accountType ? 'text-base text-slate-800 opacity-100 font-black mb-6' : ''">
                    {{ app()->isLocale('ar') ? 'ابدأ كـ ...' : 'Start as ...' }}
                </label>
                <div class="grid grid-cols-2 gap-4 px-2">
                    <!-- Instructor Option -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" value="instructor" x-model="accountType" class="peer sr-only">
                        <div class="relative flex flex-col items-center justify-center p-4 rounded-2xl border-2 bg-white transition-all duration-500 hover:shadow-xl overflow-hidden group shadow-md"
                             :class="[
                                !accountType ? 'p-6 border-slate-100 hover:border-brand-secondary/30' : 'p-4',
                                accountType === 'instructor' ? 'border-brand-secondary ring-4 ring-brand-secondary/10 shadow-brand-secondary/10' : (accountType ? 'border-slate-100 opacity-60' : '')
                             ]">
                            <!-- Trial Badge -->
                            <div class="absolute top-0 right-0 bg-emerald-600 text-white text-[9px] font-black uppercase tracking-wider px-3 py-1 rounded-bl-xl rounded-tr-2xl z-20 shadow-lg shadow-emerald-600/20">
                                <i class="bi bi-lightning-fill me-1 text-[8px]"></i>
                                <span x-text="currentPlan.trial_days > 0 ? currentPlan.trial_days : '30'"></span> {{ app()->isLocale('ar') ? 'يوم مجاناً' : 'Days Free' }}
                            </div>

                            <!-- Background Accent -->
                            <div class="absolute top-0 right-0 w-24 h-24 rounded-full -mr-12 -mt-12 transition-transform duration-700 group-hover:scale-150"
                                 :class="accountType === 'instructor' || !accountType ? 'bg-brand-secondary/5' : 'bg-slate-100'"></div>
                            
                            <!-- Icon Container -->
                            <div class="rounded-xl flex items-center justify-center transition-all duration-500 group-hover:rotate-6 shadow-lg"
                                 :class="{
                                    'bg-brand-secondary text-white shadow-brand-secondary/40 w-10 h-10 mb-2 scale-110': accountType === 'instructor',
                                    'bg-slate-100 text-slate-400 w-12 h-12 mb-3': !accountType,
                                    'bg-slate-100 text-slate-400 group-hover:bg-brand-secondary/10 group-hover:text-brand-secondary shadow-slate-200/50 w-10 h-10 mb-2': accountType && accountType !== 'instructor'
                                 }">
                                <i class="fas fa-chalkboard-teacher transition-all duration-500" :class="!accountType ? 'text-2xl' : 'text-xl'"></i>
                            </div>
                            <span class="font-black transition-all" 
                                  :class="[
                                     !accountType ? 'text-base text-slate-800' : 'text-xs',
                                     accountType === 'instructor' ? 'text-brand-secondary' : 'text-slate-500'
                                  ]">{{ app()->isLocale('ar') ? 'مدرس مستقل' : 'Independent Tutor' }}</span>
                            
                            <!-- Success Dot -->
                            <div class="absolute top-4 right-4 opacity-0 scale-0 transition-all duration-300"
                                 :class="accountType === 'instructor' ? 'opacity-100 scale-100' : ''">
                                <div class="w-3 h-3 bg-brand-secondary rounded-full ring-4 ring-brand-secondary/20"></div>
                            </div>
                        </div>
                    </label>

                    <!-- Center Option (Premium) -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" value="center" x-model="accountType" class="peer sr-only">
                        <div class="relative flex flex-col items-center justify-center p-4 rounded-2xl border-2 bg-white transition-all duration-500 hover:shadow-xl overflow-hidden group shadow-md"
                             :class="[
                                !accountType ? 'p-6 border-slate-100 hover:border-brand-secondary/30' : 'p-4',
                                accountType === 'center' ? 'border-brand-secondary ring-4 ring-brand-secondary/10 shadow-brand-secondary/10' : (accountType ? 'border-slate-100 opacity-60' : '')
                             ]">
                            <!-- Trial Badge -->
                            <div class="absolute top-0 right-0 bg-emerald-600 text-white text-[9px] font-black uppercase tracking-wider px-3 py-1 rounded-bl-xl rounded-tr-2xl z-20 shadow-lg shadow-emerald-600/20">
                                <i class="bi bi-lightning-fill me-1 text-[8px]"></i>
                                <span x-text="currentPlan.trial_days > 0 ? currentPlan.trial_days : '30'"></span> {{ app()->isLocale('ar') ? 'يوم مجاناً' : 'Days Free' }}
                            </div>

                            <!-- Background Accent -->
                            <div class="absolute top-0 right-0 w-24 h-24 rounded-full -mr-12 -mt-12 transition-transform duration-700 group-hover:scale-150"
                                 :class="accountType === 'center' || !accountType ? 'bg-brand-secondary/5' : 'bg-slate-100'"></div>
                            
                            <!-- Icon Container -->
                            <div class="rounded-xl flex items-center justify-center transition-all duration-500 group-hover:-rotate-6 shadow-lg"
                                 :class="{
                                    'bg-brand-secondary text-white shadow-brand-secondary/40 w-10 h-10 mb-2 scale-110': accountType === 'center',
                                    'bg-slate-100 text-slate-400 w-12 h-12 mb-3': !accountType,
                                    'bg-slate-100 text-slate-400 group-hover:bg-brand-secondary/10 group-hover:text-brand-secondary shadow-slate-200/50 w-10 h-10 mb-2': accountType && accountType !== 'center'
                                 }">
                                <i class="fas fa-university transition-all duration-500" :class="!accountType ? 'text-2xl' : 'text-xl'"></i>
                            </div>
                            <span class="font-black transition-all" 
                                  :class="[
                                     !accountType ? 'text-base text-slate-800' : 'text-xs',
                                     accountType === 'center' ? 'text-brand-secondary' : 'text-slate-500'
                                  ]">{{ app()->isLocale('ar') ? 'مركز تعليمي' : 'Educational Center' }}</span>
                            
                            <!-- Success Dot -->
                            <div class="absolute top-4 right-4 opacity-0 scale-0 transition-all duration-300"
                                 :class="accountType === 'center' ? 'opacity-100 scale-100' : ''">
                                <div class="w-3 h-3 bg-brand-secondary rounded-full ring-4 ring-brand-secondary/20"></div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
