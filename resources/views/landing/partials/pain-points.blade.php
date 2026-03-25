<section class="py-24 bg-[#f8fafc] relative overflow-hidden">
    <!-- Sophisticated Background -->
    <div class="absolute top-0 left-1/4 w-[600px] h-[600px] bg-white rounded-full blur-[120px] -z-10 opacity-80"></div>
    <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-[#f0fdf4] rounded-full blur-[100px] -z-10 opacity-40"></div>

    <div class="container mx-auto px-4 lg:px-12">
        <!-- Section Header -->
        <div class="text-center mb-20">
            <h2 class="text-3xl md:text-5xl font-black text-[#0f172a] mb-6 tracking-tight leading-tight">
                {{ __('landing.pain_points.title_prefix') }} <span class="text-[#22c55e]">{{ __('landing.pain_points.title_highlight') }}</span> {{ __('landing.pain_points.title_suffix') }}
            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">
                {{ __('landing.pain_points.subtitle') }}
            </p>
        </div>

        <!-- Pain Points Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Revenue Lost -->
            <div class="group bg-white rounded-[2rem] p-8 border border-white shadow-soft transition-all duration-500 hover:-translate-y-2 hover:shadow-premium">
                <div class="w-14 h-14 rounded-2xl bg-[#fef2f2] flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-line-down text-[#ef4444] text-xl"></i>
                </div>
                <div class="text-4xl font-black text-[#ef4444] mb-3 tracking-tighter">35%</div>
                <h3 class="text-lg font-black text-[#0f172a] mb-2">{{ __('landing.pain_points.revenue_lost.title') }}</h3>
                <p class="text-slate-500 text-[15px] font-medium leading-relaxed">{{ __('landing.pain_points.revenue_lost.description') }}</p>
            </div>

            <!-- Time Wasted -->
            <div class="group bg-white rounded-[2rem] p-8 border border-white shadow-soft transition-all duration-500 hover:-translate-y-2 hover:shadow-premium">
                <div class="w-14 h-14 rounded-2xl bg-[#fff7ed] flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-clock text-[#f97316] text-xl"></i>
                </div>
                <div class="text-4xl font-black text-[#f97316] mb-3 tracking-tighter">12h</div>
                <h3 class="text-lg font-black text-[#0f172a] mb-2">{{ __('landing.pain_points.time_wasted.title') }}</h3>
                <p class="text-slate-500 text-[15px] font-medium leading-relaxed">{{ __('landing.pain_points.time_wasted.description') }}</p>
            </div>

            <!-- Parents Complaints -->
            <div class="group bg-white rounded-[2rem] p-8 border border-white shadow-soft transition-all duration-500 hover:-translate-y-2 hover:shadow-premium">
                <div class="w-14 h-14 rounded-2xl bg-[#f0f9ff] flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-exclamation-triangle text-[#0ea5e9] text-xl"></i>
                </div>
                <div class="text-4xl font-black text-[#0ea5e9] mb-3 tracking-tighter">24/7</div>
                <h3 class="text-lg font-black text-[#0f172a] mb-2">{{ __('landing.pain_points.complaints.title') }}</h3>
                <p class="text-slate-500 text-[15px] font-medium leading-relaxed">{{ __('landing.pain_points.complaints.description') }}</p>
            </div>

            <!-- Manual Work -->
            <div class="group bg-white rounded-[2rem] p-8 border border-white shadow-soft transition-all duration-500 hover:-translate-y-2 hover:shadow-premium">
                <div class="w-14 h-14 rounded-2xl bg-[#f0fdf4] flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-hand-paper text-[#22c55e] text-xl"></i>
                </div>
                <div class="text-4xl font-black text-[#22c55e] mb-3 tracking-tighter">100%</div>
                <h3 class="text-lg font-black text-[#0f172a] mb-2">{{ __('landing.pain_points.manual_work.title') }}</h3>
                <p class="text-slate-500 text-[15px] font-medium leading-relaxed">{{ __('landing.pain_points.manual_work.description') }}</p>
            </div>
        </div>

        <!-- Trust Section: Glass Badges -->
        <div class="mt-24 pt-12 border-t border-slate-200/50">
            <div class="flex flex-wrap items-center justify-center gap-12 lg:gap-20">
                <div class="flex items-center gap-3 grayscale hover:grayscale-0 transition-all duration-500">
                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center border border-slate-100">
                        <i class="fas fa-shield-check text-[#10b981]"></i>
                    </div>
                    <span class="font-black text-slate-400 text-sm uppercase tracking-widest">Secure Payments</span>
                </div>
                <div class="flex items-center gap-3 grayscale hover:grayscale-0 transition-all duration-500">
                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center border border-slate-100">
                        <i class="fab fa-whatsapp text-[#25D366]"></i>
                    </div>
                    <span class="font-black text-slate-400 text-sm uppercase tracking-widest">WhatsApp Verified</span>
                </div>
                <div class="flex items-center gap-3 grayscale hover:grayscale-0 transition-all duration-500">
                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center border border-slate-100">
                        <i class="fas fa-graduation-cap text-[#6366f1]"></i>
                    </div>
                    <span class="font-black text-slate-400 text-sm uppercase tracking-widest">Educator Trusted</span>
                </div>
            </div>
        </div>
    </div>
</section>
