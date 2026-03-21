<section id="faq" class="py-12 lg:py-16">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-6 lg:mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-outline-purple/10 border border-outline-purple/20 mb-4">
                <span class="text-sm font-medium text-outline-purple">{{ __('landing.faq.badge') }}</span>
            </div>
            <h2 class="text-2xl lg:text-3xl font-bold text-foreground mb-3">
                {{ __('landing.faq.title_prefix') }} <span class="gradient-text">{{ __('landing.faq.title_highlight') }}</span>
            </h2>
            @php
                $siteName = \App\Models\SiteSetting::get('site_name', config('app.name'));
            @endphp
            <p class="text-muted-foreground text-lg max-w-2xl mx-auto">
                {{ str_replace(config('app.name'), $siteName, __('landing.faq.subtitle')) }}
            </p>
        </div>

        <!-- FAQ Accordion -->
        <div class="max-w-3xl mx-auto space-y-4" x-data="{ active: null }">
            @for ($index = 0; $index < 8; $index++)
                @php
                    $question = str_replace(config('app.name'), $siteName, __("landing.faq.items.$index.question"));
                    $answer = str_replace(config('app.name'), $siteName, __("landing.faq.items.$index.answer"));
                    // Skip if translation key is not found
                    if ($question === "landing.faq.items.$index.question") break;
                @endphp
                <div class="bg-card rounded-xl border border-border px-6 shadow-sm hover:shadow-card transition-shadow" :class="{ 'border-light-purple/30': active === {{ $index }} }">
                    <button 
                        @click="active = (active === {{ $index }} ? null : {{ $index }})" 
                        class="flex items-center justify-between w-full text-left font-semibold text-foreground hover:text-primary-purple py-5 transition-colors"
                        :class="{ 'text-primary-purple': active === {{ $index }} }"
                    >
                        <span>{{ $question }}</span>
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            width="24" 
                            height="24" 
                            viewBox="0 0 24 24" 
                            fill="none" 
                            stroke="currentColor" 
                            stroke-width="2" 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            class="w-4 h-4 transition-transform duration-200"
                            :class="{ 'rotate-180': active === {{ $index }} }"
                        >
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div 
                        x-show="active === {{ $index }}" 
                        x-collapse 
                        class="text-muted-foreground pb-5 leading-relaxed"
                        style="display: none;"
                    >
                        {{ $answer }}
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>
