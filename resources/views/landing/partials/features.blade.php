<section id="features" class="relative py-24 bg-white overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-slate-50 rounded-full blur-[120px] -z-10 opacity-60"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[#f0fdf4] rounded-full blur-[100px] -z-10 opacity-50"></div>

    <div class="container mx-auto px-4 lg:px-12">
        <!-- Section Header: Centered & Impactful -->
        <div class="text-center mb-20">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#f0fdf4] border border-green-100 mb-6">
                <span class="text-xs font-black text-[#22c55e] uppercase tracking-[0.2em]">{{ __('landing.features.badge') }}</span>
            </div>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-[#0f172a] mb-6 tracking-tight leading-[1.1]">
                {!! __('landing.features.title') !!}
            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium leading-relaxed">
                {{ __('landing.features.subtitle') }}
            </p>
        </div>

        <!-- Features Grid: Spacious & Professional -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
@php
    $featuresData = [
        ['icon' => 'fa-users', 'color' => '#22c55e', 'bg' => '#f0fdf4'],
        ['icon' => 'fa-calendar-check', 'color' => '#0ea5e9', 'bg' => '#f0f9ff'],
        ['icon' => 'fa-comments', 'color' => '#22c55e', 'bg' => '#f0fdf4'],
        ['icon' => 'fa-credit-card', 'color' => '#8b5cf6', 'bg' => '#f5f3ff'],
        ['icon' => 'fa-chart-pie', 'color' => '#22c55e', 'bg' => '#f0fdf4'],
        ['icon' => 'fa-shield-alt', 'color' => '#0ea5e9', 'bg' => '#f0f9ff'],
        ['icon' => 'fa-clock', 'color' => '#22c55e', 'bg' => '#f0fdf4'],
        ['icon' => 'fa-check-double', 'color' => '#8b5cf6', 'bg' => '#f5f3ff']
    ];
@endphp

            @foreach(__('landing.features.items') as $index => $item)
                @php $data = $featuresData[$index] ?? $featuresData[0]; @endphp
                <div
                    class="group relative bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-soft hover:shadow-premium transition-all duration-500 overflow-hidden"
                >
                    <!-- Hover Accent -->
                    <div class="absolute top-0 left-0 w-2 h-0 bg-[#22c55e] group-hover:h-full transition-all duration-500"></div>

                    <!-- Icon: Large & Refined -->
                    <div class="w-16 h-16 rounded-2xl mb-8 flex items-center justify-center transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3" style="background-color: {{ $data['bg'] }}; color: {{ $data['color'] }};">
                        <i class="fas {{ $data['icon'] }} text-2xl"></i>
                    </div>

                    <!-- Title -->
                    <h3 class="text-xl font-black text-[#0f172a] mb-4 tracking-tight group-hover:text-[#22c55e] transition-colors">
                        {{ $item['title'] }}
                    </h3>

                    <!-- Description -->
                    <p class="text-slate-500 font-medium leading-[1.8] text-sm md:text-[15px]">
                        {{ $item['description'] }}
                    </p>

                    <!-- Subtle Footer -->
                    <div class="mt-8 flex items-center gap-2 text-[#22c55e] opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                        <span class="text-xs font-black uppercase tracking-wider">{{ __('landing.features.learn_more') }}</span>
                        <i class="fas fa-arrow-right text-[10px] rtl:rotate-180"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

