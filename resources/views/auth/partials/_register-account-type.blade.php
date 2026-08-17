                <!-- Account Type: Inline Chip Selector -->
                <div class="flex items-center justify-center gap-2 mb-4">
                    <div class="inline-flex items-center bg-slate-50 rounded-lg p-0.5 border border-slate-100">
                        <button type="button" @click="accountType = 'center'"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-[11px] font-black transition-all duration-200"
                                :class="accountType === 'center' 
                                    ? 'bg-white text-brand-secondary shadow-sm border border-brand-secondary/15' 
                                    : 'text-slate-400 hover:text-slate-600'">
                            <i class="fas fa-university text-[11px]"></i>
                            {{ app()->isLocale('ar') ? 'مركز تعليمي' : 'Center' }}
                        </button>
                        <button type="button" @click="accountType = 'instructor'"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-[11px] font-black transition-all duration-200"
                                :class="accountType === 'instructor' 
                                    ? 'bg-white text-brand-secondary shadow-sm border border-brand-secondary/15' 
                                    : 'text-slate-400 hover:text-slate-600'">
                            <i class="fas fa-chalkboard-teacher text-[11px]"></i>
                            {{ app()->isLocale('ar') ? 'مدرس مستقل' : 'Tutor' }}
                        </button>
                    </div>
                </div>
