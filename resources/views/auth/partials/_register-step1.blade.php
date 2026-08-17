                <!-- STEP 1: Center Details -->
                <div x-show="currentStep === 1" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-[13px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">
                            <span x-text="accountType === 'center' ? '{{ __('auth.register.center_name') }}' : ({{ Js::from(app()->isLocale('ar') ? 'اسم المدرس / المنصة' : 'Teacher / Platform Name') }})"></span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-5 flex items-center pointer-events-none text-slate-300 group-focus-within:text-brand-secondary transition-colors"><i class="bi bi-building"></i></div>
                            <input type="text" name="center_name" x-model="centerName"
                                @input="if(!manuallyEditedSubdomain) { subdomain = generateSlug(centerName); checkSubdomain(); }"
                                class="w-full h-10 ps-12 pe-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold font-arabic focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner"
                                placeholder="{{ __('auth.register.center_name_placeholder') }}" :required="currentStep === 1">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[13px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">{{ app()->isLocale('ar') ? 'رابط المنصة' : 'Platform Link' }}</label>
                        <div class="relative flex items-center w-full group" dir="ltr">
                            <div class="absolute left-0 inset-y-0 hidden sm:flex items-center px-4 pointer-events-none text-brand-secondary font-black text-xs bg-brand-secondary/5 border-r border-brand-secondary/10 rounded-l-2xl">https://</div>
                            <input type="text" name="subdomain" x-model="subdomain"
                                @input="manuallyEditedSubdomain = true; subdomain = cleanSlug(subdomain);"
                                @input.debounce.500ms="checkSubdomain()"
                                class="w-full h-10 pl-[64px] pr-[92px] sm:pl-[80px] sm:pr-[115px] bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold font-sans focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner"
                                placeholder="center-name" :required="currentStep === 1">
                            <div class="absolute right-0 inset-y-0 flex items-center pr-4 pointer-events-none text-slate-400 font-bold text-xs gap-3">
                                <span>.taalimu.com</span>
                                <div class="flex items-center justify-center w-5 h-5">
                                    <template x-if="subdomainStatus === 'loading'"><div class="w-4 h-4 border-2 border-brand-secondary border-t-transparent rounded-full animate-spin"></div></template>
                                    <template x-if="subdomainStatus === 'valid'"><i class="bi bi-check-circle-fill text-emerald-500 text-lg"></i></template>
                                    <template x-if="subdomainStatus === 'invalid'"><i class="bi bi-x-circle-fill text-red-500 text-lg"></i></template>
                                </div>
                            </div>
                        </div>
                        <p x-show="subdomainMessage" :class="subdomainStatus === 'valid' ? 'text-emerald-600' : 'text-red-500'" 
                           class="text-[11px] font-bold px-2 mt-1 animate-fade-in" x-text="subdomainMessage"></p>
                    </div>

                    <div class="pt-2">
                        <button type="button" @click="nextStep()"
                            class="w-full h-11 rounded-full flex items-center justify-center gap-3 group bg-gradient-to-r from-emerald-600 to-teal-500 text-white shadow-lg shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-600 hover:-translate-y-1 active:scale-95 transition-all">
                            <span class="text-base font-black font-arabic">{{ app()->isLocale('ar') ? 'استمرار' : 'Continue' }}</span>
                            <i class="bi bi-arrow-right-short text-xl group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>
