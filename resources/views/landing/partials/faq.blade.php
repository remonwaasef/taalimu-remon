<section id="faq" class="py-20 lg:py-28 bg-slate-50 relative">
    <div class="container mx-auto px-4 lg:px-8 max-w-5xl">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-50 border border-teal-200/80 text-[#2E8B83] text-xs font-bold mb-4 shadow-sm">
                <i class="fas fa-question-circle text-xs"></i>
                <span>{{ __('landing.faq.badge') }}</span>
            </div>

            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
                {{ __('landing.faq.title') }}
            </h2>

            <p class="text-slate-600 font-medium text-base">
                {{ __('landing.faq.subtitle') }}
            </p>
        </div>

        <!-- Accessible Accordion Grid -->
        <div x-data="{ activeAccordion: null }" class="space-y-4" data-animate="fade-in">
            @foreach(__('landing.faq.items') as $index => $item)
                <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:border-slate-300 transition-colors">
                    <button 
                        @click="activeAccordion = (activeAccordion === {{ $index }} ? null : {{ $index }})"
                        type="button" 
                        class="w-full p-5 text-start flex items-center justify-between gap-4 font-bold text-slate-900 text-sm sm:text-base focus:outline-none"
                    >
                        <span>{{ $item['q'] }}</span>
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 shrink-0 transition-transform" :class="{ 'rotate-180 bg-teal-50 text-[#2E8B83]': activeAccordion === {{ $index }} }">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </button>

                    <div 
                        x-show="activeAccordion === {{ $index }}" 
                        x-cloak 
                        x-collapse 
                        class="px-5 pb-5 pt-1 text-slate-600 font-medium text-xs sm:text-sm leading-relaxed border-t border-slate-100/60"
                    >
                        <p>{{ $item['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>