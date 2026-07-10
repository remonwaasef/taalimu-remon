<section id="faq" class="py-24 bg-white relative overflow-hidden">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center mb-16" data-animate>
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight">
                {{ __('landing.faq.title_prefix') }} <span class="text-emerald-500">{{ __('landing.faq.title_highlight') }}</span>
            </h2>
            @php $siteName = \App\Models\SiteSetting::get('site_name', 'Taalimu'); @endphp
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">
                {{ str_replace(config('app.name'), $siteName, __('landing.faq.subtitle')) }}
            </p>
        </div>

        <div class="max-w-3xl mx-auto space-y-3" x-data="{ active: null }" data-stagger>
            @for ($index = 0; $index < 8; $index++)
                @php
                    $question = str_replace(config('app.name'), $siteName, __("landing.faq.items.$index.question"));
                    $answer = str_replace(config('app.name'), $siteName, __("landing.faq.items.$index.answer"));
                    if ($question === "landing.faq.items.$index.question") break;
                @endphp
                <div class="group bg-white rounded-xl border border-slate-200 transition-all duration-300 overflow-hidden" 
                     :class="{ 'border-emerald-200 shadow-lg shadow-emerald-500/5': active === {{ $index }} }">
                    <button 
                        @click="active = (active === {{ $index }} ? null : {{ $index }})" 
                        class="flex items-center justify-between w-full text-left px-6 py-5 transition-colors"
                    >
                        <span class="text-base font-bold text-slate-800 group-hover:text-emerald-600 transition-colors" :class="{ 'text-emerald-600': active === {{ $index }} }">
                            {{ $question }}
                        </span>
                        <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center transition-all duration-300 flex-shrink-0 ml-4"
                             :class="{ 'rotate-180 bg-emerald-500 text-white': active === {{ $index }} }">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </div>
                    </button>
                    <div 
                        x-show="active === {{ $index }}" 
                        x-collapse 
                        class="px-6 pb-5 text-slate-500 text-sm leading-relaxed"
                    >
                        {{ $answer }}
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>
