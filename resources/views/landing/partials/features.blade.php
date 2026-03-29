<!-- Social Proof / Features Light Section -->
<section id="features" class="py-24 bg-white relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-100 to-transparent"></div>
    <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-emerald-500/5 rounded-full blur-[120px] -z-10"></div>

    <div class="container mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <!-- Left Text -->
            <div data-animate>
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 border border-emerald-100 mb-8">
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">{{ __('landing.features.badge') }}</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-6 tracking-tight leading-tight">
                    {!! __('landing.features.title') !!}
                </h2>
                <p class="text-lg text-slate-600 font-medium leading-relaxed mb-10">
                    {{ __('landing.features.subtitle') }}
                </p>

                <!-- Feature List -->
                <div class="space-y-6">
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
                        <div class="flex gap-4 group">
                            <div class="w-12 h-12 rounded-xl {{ $data['bg'] }} flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <i class="fas {{ $data['icon'] }} {{ $data['color'] }}"></i>
                            </div>
                            <div>
                                <h3 class="text-slate-900 font-bold mb-1 group-hover:text-emerald-600 transition-colors">{{ $item['title'] }}</h3>
                                <p class="text-slate-500 text-sm leading-relaxed">{{ $item['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Side: Feature Cards Grid -->
            <div class="grid grid-cols-2 gap-4" data-stagger>
                @foreach(__('landing.features.items') as $index => $item)
                    @if($index >= 4) @break @endif
                    @php $data = $featuresData[$index] ?? $featuresData[0]; @endphp
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:border-emerald-200 hover:bg-white transition-all group hover:-translate-y-1">
                        <div class="w-10 h-10 rounded-xl {{ $data['bg'] }} flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas {{ $data['icon'] }} {{ $data['color'] }} text-sm"></i>
                        </div>
                        <h4 class="text-slate-900 font-bold text-sm mb-2">{{ $item['title'] }}</h4>
                        <p class="text-slate-500 text-xs leading-relaxed">{{ Str::limit($item['description'], 80) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
