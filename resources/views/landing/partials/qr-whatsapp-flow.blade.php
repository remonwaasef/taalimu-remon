<section class="py-20 lg:py-28 bg-slate-900 text-white relative overflow-hidden">
    <!-- Gradient Accents -->
    <div class="absolute top-0 start-1/4 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 end-1/4 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 max-w-7xl relative z-10">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold mb-4">
                <i class="fas fa-route text-xs"></i>
                <span>{{ __('landing.master_flow.badge') }}</span>
            </div>

            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-tight mb-4">
                {{ __('landing.master_flow.title') }}
            </h2>

            <p class="text-slate-400 font-medium text-base">
                {{ __('landing.master_flow.subtitle') }}
            </p>
        </div>

        <!-- 6-Step Interactive Timeline Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @foreach(__('landing.master_flow.steps') as $step)
                <div class="bg-slate-800/80 rounded-2xl p-6 border border-slate-700/80 hover:border-emerald-500/50 hover:bg-slate-800 transition-all duration-300 relative group" data-animate="fade-in">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-2xl font-black text-emerald-400 font-mono">
                            {{ $step['num'] }}
                        </span>
                        <div class="w-8 h-8 rounded-full bg-slate-700 text-emerald-400 flex items-center justify-center text-xs font-bold group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>

                    <h3 class="text-base font-bold text-white mb-2">
                        {{ $step['title'] }}
                    </h3>

                    <p class="text-slate-400 text-xs leading-relaxed font-medium">
                        {{ $step['desc'] }}
                    </p>
                </div>
            @endforeach
        </div>

    </div>
</section>