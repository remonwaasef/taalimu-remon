<section id="testimonials" class="py-24 bg-[#f8fafc] relative overflow-hidden section-wave section-wave-white">
    <!-- Artistic Backdrops -->
    <div class="absolute top-1/2 left-0 w-[500px] h-[500px] bg-white rounded-full blur-[140px] -translate-y-1/2 -z-10 opacity-70"></div>
    <div class="absolute top-1/2 right-0 w-[400px] h-[400px] bg-[#f0f9ff] rounded-full blur-[120px] -translate-y-1/2 -z-10 opacity-50"></div>
    
    <div class="container mx-auto px-4 lg:px-12">
        <!-- Section Header -->
        <div class="text-center mb-20" data-animate>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-100 mb-6 shadow-sm">
                <i class="fas fa-star text-[#ffc107] text-xs"></i>
                <span class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('landing.testimonials.badge') }}</span>
            </div>
            <h2 class="text-3xl md:text-5xl font-black text-[#0f172a] mb-6 tracking-tight leading-tight">
                {{ __('landing.testimonials.title_prefix') }} <span class="text-[#22c55e]">{{ __('landing.testimonials.title_highlight') }}</span>
            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">
                {{ str_replace(config('app.name'), $siteSettings['site_name'] ?? config('app.name'), __('landing.testimonials.subtitle')) }}
            </p>
        </div>

        <!-- Testimonials Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" data-stagger>
            @foreach(__('landing.testimonials.items') as $index => $item)
            <div class="group relative bg-white rounded-3xl p-10 border border-slate-100/50 hover:shadow-premium transition-all duration-500 hover:-translate-y-2 border-l-4 border-l-transparent hover:border-l-[#22c55e]">
                <!-- Quote Icon (Subtle) -->
                <div class="absolute top-8 right-8 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="fas fa-quote-right text-4xl text-slate-400"></i>
                </div>

                <!-- Rating -->
                <div class="flex gap-1 mb-6">
                    @for ($i = 0; $i < 5; $i++)
                        <i class="fas fa-star text-[#ffc107] text-xs"></i>
                    @endfor
                </div>

                <!-- Content -->
                <p class="text-slate-600 font-medium text-lg leading-relaxed mb-8 italic">
                    "{{ $item['quote'] }}"
                </p>

                <!-- Premium Stat Box -->
                <div class="bg-[#f8fafc] rounded-2xl p-5 mb-8 flex items-center gap-4 border border-slate-50 group-hover:bg-[#f0fdf4] transition-colors duration-500">
                    <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-[#22c55e]">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="text-xl font-black text-[#0f172a]">{{ $item['stat_value'] }}</div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ $item['stat_label'] }}</div>
                    </div>
                </div>

                <!-- Author -->
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[#f8fafc] to-slate-100 flex items-center justify-center text-[#22c55e] font-black text-xl border-2 border-white shadow-soft">
                        {{ substr($item['author_name'], 0, 1) }}
                    </div>
                    <div>
                        <div class="font-black text-[#0f172a]">{{ $item['author_name'] }}</div>
                        <div class="text-[13px] font-bold text-slate-400 leading-tight">
                            {{ $item['author_role'] }} <span class="mx-1 text-slate-300">•</span> {{ $item['author_company'] }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
