<section id="features" class="py-20 lg:py-28 bg-white relative">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-red-50 border border-red-200/80 text-red-600 text-xs font-bold mb-4 shadow-sm">
                <i class="fas fa-exclamation-triangle text-xs"></i>
                <span>{{ __('landing.pain_points.badge') }}</span>
            </div>

            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
                {{ __('landing.pain_points.title_prefix') }}
                <span class="text-red-600">{{ __('landing.pain_points.title_highlight') }}</span>
            </h2>

            <p class="text-slate-600 font-medium text-base">
                {{ __('landing.pain_points.subtitle') }}
            </p>
        </div>

        <!-- Pain Points Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 mb-16">
            @foreach(__('landing.pain_points.items') as $item)
                <div class="p-6 rounded-2xl bg-slate-50/80 border border-slate-200/80 hover:bg-white hover:border-slate-300 hover:shadow-xl transition-all duration-300 group" data-animate="fade-in">
                    <div class="w-12 h-12 rounded-xl bg-red-100/80 text-red-600 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas {{ $item['icon'] }}"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">
                        {{ $item['title'] }}
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed font-medium">
                        {{ $item['desc'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <!-- Solution Banner -->
        <div class="rounded-3xl p-8 lg:p-12 text-white relative overflow-hidden shadow-2xl" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
            <div class="absolute -end-10 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 text-center max-w-3xl mx-auto">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-400 flex items-center justify-center text-2xl mb-4 shadow-lg">
                    <i class="fas fa-[#2E8B83] fa-magic text-[#2E8B83]"></i>
                </div>
                <h3 class="text-xl sm:text-2xl lg:text-3xl font-extrabold mb-3 text-white">
                    {{ __('landing.pain_points.solution_banner_title') }}
                </h3>
                <p class="text-slate-300 text-sm sm:text-base font-medium mb-6">
                    {{ __('landing.pain_points.solution_banner_subtitle') }}
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl text-white font-extrabold text-sm text-decoration-none shadow-lg hover:shadow-xl transition-all" style="background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);">
                    <span>{{ __('landing.nav.start_trial') }}</span>
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
            </div>
        </div>

    </div>
</section>
