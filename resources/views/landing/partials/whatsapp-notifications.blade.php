<section id="whatsapp-notifications" class="py-20 lg:py-28 bg-white relative">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs font-bold mb-4 shadow-sm">
                <i class="fab fa-whatsapp text-sm"></i>
                <span>{{ __('landing.whatsapp_section.badge') }}</span>
            </div>

            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
                {{ __('landing.whatsapp_section.title') }}
            </h2>

            <p class="text-slate-600 font-medium text-base">
                {{ __('landing.whatsapp_section.subtitle') }}
            </p>
        </div>

        <!-- WhatsApp Cases Interactive Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            @foreach(__('landing.whatsapp_section.cases') as $key => $case)
                <div class="bg-slate-50/80 rounded-3xl p-6 lg:p-8 border border-slate-200/80 hover:border-emerald-200 hover:shadow-xl transition-all duration-300 flex flex-col justify-between" data-animate="fade-in">
                    <div>
                        <!-- Header Tag -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full bg-emerald-100/80 text-emerald-800 text-xs font-bold border border-emerald-200">
                                {{ $case['tag'] }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium"><i class="fas fa-bolt text-amber-500 me-1"></i> {{ __('landing.mockups.auto_badge') }}</span>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            {{ $case['title'] }}
                        </h3>

                        <p class="text-slate-600 text-xs sm:text-sm font-medium mb-6">
                            {{ $case['desc'] }}
                        </p>
                    </div>

                    <!-- Simulated Real WhatsApp Chat Bubble -->
                    <div class="bg-emerald-50/90 border border-emerald-200/90 rounded-2xl p-4 relative shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-sm shrink-0 shadow">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-emerald-900">Taalimu Notifications</span>
                                    <span class="text-[10px] text-slate-400">{{ __('landing.hero.card_now') }}</span>
                                </div>
                                <p class="text-xs text-slate-800 font-sans leading-relaxed">
                                    {{ $case['msg'] }}
                                </p>
                                <div class="flex items-center justify-end gap-1 mt-2 text-[10px] text-emerald-700 font-bold">
                                    <span>{{ __('landing.mockups.sent_via_api') }}</span>
                                    <i class="fas fa-check-double text-emerald-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>