<section id="comparison" class="py-24 bg-white relative overflow-hidden section-wave">
    <!-- Artistic Accents -->
    <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-slate-50 rounded-full blur-[100px] -z-10 opacity-70"></div>

    <div class="container mx-auto px-4 lg:px-12 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-20" data-animate>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#fef2f2] border border-red-50 mb-6">
                <span class="text-xs font-black text-[#ef4444] uppercase tracking-[0.2em]">{{ __('landing.comparison.badge') }}</span>
            </div>
            <h2 class="text-3xl md:text-5xl font-black text-[#0f172a] mb-6 tracking-tight leading-tight">
                {!! str_replace(config('app.name'), \App\Models\SiteSetting::get('site_name', config('app.name')), __('landing.comparison.title')) !!}
            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">
                {{ __('landing.comparison.subtitle') }}
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12 max-w-6xl mx-auto items-stretch">
            <!-- Manual / Traditional Management -->
            <div class="group relative bg-[#fef2f2]/30 rounded-[2.5rem] p-10 border border-red-50 hover:shadow-soft transition-all duration-500" data-animate="fade-left">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-white shadow-soft flex items-center justify-center text-red-500">
                        <i class="fas fa-times-circle text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-[#0f172a]">
                        {{ __('landing.comparison.manual.title') }}
                    </h3>
                </div>

                <ul class="space-y-6">
                    @foreach(__('landing.comparison.manual.items') as $item)
                        <li class="flex items-start gap-4">
                            <div class="mt-1 w-5 h-5 rounded-full bg-white flex items-center justify-center text-red-400/50 flex-shrink-0">
                                <i class="fas fa-minus text-[10px]"></i>
                            </div>
                            <span class="text-slate-500 font-medium leading-relaxed">
                                {{ $item }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Edu System -->
            <div class="group relative bg-white rounded-[2.5rem] p-10 border border-[#22c55e]/20 shadow-premium hover:-translate-y-2 transition-all duration-500 overflow-hidden" data-animate="fade-right">
                <!-- Highlight Effect -->
                <div class="absolute top-0 right-0 w-48 h-48 bg-[#22c55e]/5 blur-[100px] rounded-full"></div>
                
                <div class="flex items-center gap-4 mb-8 relative z-10">
                    <div class="w-14 h-14 rounded-2xl bg-[#f0fdf4] flex items-center justify-center text-[#22c55e] shadow-soft">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-[#0f172a]">
                        {{ str_replace(config('app.name'), \App\Models\SiteSetting::get('site_name', config('app.name')), __('landing.comparison.edu.title')) }}
                    </h3>
                </div>

                <ul class="space-y-6 relative z-10">
                    @foreach(__('landing.comparison.edu.items') as $item)
                        <li class="flex items-start gap-4">
                            <div class="mt-1 w-5 h-5 rounded-full bg-[#f0fdf4] flex items-center justify-center text-[#22c55e] shadow-sm flex-shrink-0">
                                <i class="fas fa-check text-[10px]"></i>
                            </div>
                            <span class="text-slate-700 font-black leading-relaxed">
                                {{ $item }}
                            </span>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-12 pt-8 border-t border-slate-50">
                    <a href="#pricing" class="inline-flex items-center gap-3 text-[#22c55e] font-black group/cta">
                        <span class="uppercase tracking-widest text-xs">{{ __('landing.hero.cta_primary') }}</span>
                        <i class="fas fa-arrow-right text-xs group-hover:translate-x-2 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
