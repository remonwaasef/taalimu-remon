        <!-- Plan Selection Modal (Restored) -->
        <div x-show="showPlanModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             x-cloak>
            <div @click.away="showPlanModal = false" 
                 class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl overflow-hidden animate-scale-in">
                <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-xl font-black text-slate-900 font-arabic">{{ app()->getLocale() == 'ar' ? 'اختر الباقة المناسبة' : 'Select Plan' }}</h3>
                        
                        <!-- Billing Country Selector -->
                        <div class="flex items-center gap-2 bg-slate-100/50 p-1.5 rounded-2xl border border-slate-200">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider px-2 whitespace-nowrap"><i class="bi bi-globe-americas me-1"></i> {{ app()->getLocale() == 'ar' ? 'دولة الفوترة' : 'Billing Region' }}</span>
                            <div class="relative" dir="ltr">
                                <select x-model="selectedCurrency" class="h-8 pl-3 pr-8 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-secondary/20 focus:border-brand-secondary appearance-none cursor-pointer shadow-sm min-w-[120px]">
                                    <option value="EGP">🇪🇬 Egypt (EGP)</option>
                                    <option value="SAR">🇸🇦 Saudi Arabia (SAR)</option>
                                    <option value="AED">🇦🇪 UAE (AED)</option>
                                    <option value="EUR">🇪🇺 Europe (EUR)</option>
                                    <option value="USD">🇺🇸 Global (USD)</option>
                                </select>
                                <div class="absolute inset-y-0 right-2 flex items-center pointer-events-none">
                                    <i class="bi bi-chevron-down text-[10px] text-slate-400"></i>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" @click="showPlanModal = false" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-slate-200 transition-colors">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <!-- Billing Toggle in Modal -->
                    <div class="flex justify-center">
                        <div class="inline-flex bg-slate-200/50 p-1.5 rounded-2xl border border-slate-200" dir="ltr">
                            <button type="button" @click="billingCycle = 'monthly'" 
                                    class="px-5 py-2 rounded-xl text-[13px] font-black transition-all font-sans uppercase tracking-wide"
                                    :class="billingCycle === 'monthly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'">
                                {{ app()->getLocale() == 'ar' ? 'شهري' : 'Month' }}
                            </button>
                            <button type="button" @click="billingCycle = 'term'" 
                                    class="px-5 py-2 rounded-xl text-[13px] font-black transition-all font-sans uppercase tracking-wide"
                                    :class="billingCycle === 'term' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'">
                                {{ app()->getLocale() == 'ar' ? 'ترم' : 'Term' }}
                            </button>
                            <button type="button" @click="billingCycle = 'yearly'" 
                                    class="px-5 py-2 rounded-xl text-[13px] font-black transition-all font-sans uppercase tracking-wide"
                                    :class="billingCycle === 'yearly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'">
                                {{ app()->getLocale() == 'ar' ? 'سنوي' : 'Yearly' }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-8 space-y-4 max-h-[60vh] overflow-y-auto custom-scrollbar">
                    <template x-for="pkg in packages" :key="pkg.slug">
                        <label class="relative block cursor-pointer group">
                            <input type="radio" name="plan_selector" :value="pkg.slug" x-model="selectedPlan" @change="showPlanModal = false" class="peer sr-only">
                            <div class="p-6 rounded-2xl border-2 border-slate-100 bg-white hover:border-brand-secondary/30 peer-checked:border-brand-secondary peer-checked:bg-brand-secondary/5 transition-all">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center transition-all bg-white"
                                             :class="selectedPlan === pkg.slug ? 'border-brand-secondary' : 'border-slate-200'">
                                            <div class="w-2.5 h-2.5 rounded-full bg-brand-secondary transition-transform"
                                                 :class="selectedPlan === pkg.slug ? 'scale-100' : 'scale-0'"></div>
                                        </div>
                                        <span class="font-black text-slate-900 uppercase tracking-tight" x-text="pkg.name"></span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-black text-brand-secondary" x-text="(billingCycle === 'yearly' ? getPriceData(pkg).yearly : (billingCycle === 'term' ? getPriceData(pkg).term : getPriceData(pkg).amount)).toLocaleString() + ' ' + getPriceData(pkg).currency"></span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </template>
                </div>
                <div class="p-6 bg-slate-50 text-center">
                    <button type="button" @click="showPlanModal = false" class="text-sm font-black text-slate-500 hover:text-slate-700 transition-colors uppercase tracking-widest">
                        {{ app()->getLocale() == 'ar' ? 'إغلاق' : 'Close' }}
                    </button>
                </div>
            </div>
        </div>
