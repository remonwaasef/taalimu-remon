<!-- Social Proof / Features Light Section -->
<section id="features" class="py-24 bg-white relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-100 to-transparent"></div>
    <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-emerald-500/5 rounded-full blur-[120px] -z-10"></div>

    <div class="container mx-auto px-4 lg:px-12">
        <!-- Centered Header -->
        <div class="text-center mb-16" data-animate>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 border border-emerald-100 mb-8">
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">{{ __('landing.features.badge') }}</span>
            </div>
            <h2 class="text-2xl md:text-4xl font-extrabold text-slate-900 mb-6 tracking-tight leading-tight">
                {!! __('landing.features.title') !!}
            </h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto font-medium leading-relaxed">
                {{ __('landing.features.subtitle') }}
            </p>
        </div>

        <!-- Feature Cards (Squares) in One Line -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" data-stagger>
            @php
                $featuresData = [
                    ['icon' => 'fa-users', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
                    ['icon' => 'fa-calendar-check', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                    ['icon' => 'fa-credit-card', 'color' => 'text-violet-600', 'bg' => 'bg-violet-50'],
                    ['icon' => 'fa-chart-pie', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50'],
                ];
            @endphp
            @foreach(__('landing.features.items') as $index => $item)
                @if($index >= 4) @break @endif
                @php $data = $featuresData[$index] ?? $featuresData[0]; @endphp
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm hover:border-emerald-200 hover:shadow-md transition-all group hover:-translate-y-1 text-center">
                    <div class="w-14 h-14 mx-auto rounded-2xl {{ $data['bg'] }} flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas {{ $data['icon'] }} {{ $data['color'] }} text-xl"></i>
                    </div>
                    <h4 class="text-slate-900 font-bold text-lg mb-3 group-hover:text-emerald-600 transition-colors">{{ $item['title'] }}</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">{{ Str::limit($item['description'], 100) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
