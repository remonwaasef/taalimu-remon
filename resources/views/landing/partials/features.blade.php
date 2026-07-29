<!-- Features Light Section -->
<section id="features" class="py-24 lg:py-32 bg-slate-100/70 relative overflow-hidden border-y border-slate-200">
    <div class="container mx-auto px-4 lg:px-12">
        <!-- Centered Header -->
        <div class="text-center mb-16 lg:mb-20" data-animate>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-900 text-white mb-6 shadow-md">
                <span class="text-xs font-black text-emerald-400 uppercase tracking-widest">{{ __('landing.features.badge') }}</span>
            </div>
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight leading-tight">
                {!! __('landing.features.title') !!}
            </h2>
            <p class="text-base sm:text-lg text-slate-700 max-w-2xl mx-auto font-bold leading-relaxed">
                {{ __('landing.features.subtitle') }}
            </p>
        </div>

        <!-- Feature Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8" data-stagger>
            @php
                $featuresData = [
                    ['icon' => 'fa-users', 'bg' => 'bg-emerald-600', 'text' => 'text-emerald-700'],
                    ['icon' => 'fa-calendar-check', 'bg' => 'bg-blue-600', 'text' => 'text-blue-700'],
                    ['icon' => 'fa-credit-card', 'bg' => 'bg-purple-600', 'text' => 'text-purple-700'],
                    ['icon' => 'fa-chart-pie', 'bg' => 'bg-amber-600', 'text' => 'text-amber-700'],
                ];
            @endphp
            @foreach(__('landing.features.items') as $index => $item)
                @if($index >= 4) @break @endif
                @php $data = $featuresData[$index] ?? $featuresData[0]; @endphp
                <div class="bg-white rounded-3xl p-8 border-2 border-slate-200 shadow-xl hover:border-emerald-500 transition-all duration-300 group hover:-translate-y-2 text-center flex flex-col justify-between">
                    <div>
                        <div class="w-16 h-16 mx-auto rounded-2xl {{ $data['bg'] }} flex items-center justify-center mb-6 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas {{ $data['icon'] }} text-2xl"></i>
                        </div>
                        <h3 class="text-slate-900 font-black text-xl mb-3 group-hover:text-emerald-600 transition-colors">{{ $item['title'] }}</h3>
                        <p class="text-slate-700 text-sm leading-relaxed font-bold">{{ Str::limit($item['description'], 110) }}</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-200 flex items-center justify-center text-xs font-black text-emerald-700 group-hover:translate-x-1 transition-transform">
                        <span>{{ __('landing.nav.features') }}</span>
                        <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-left me-1' : 'fa-arrow-right ms-1' }}"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


