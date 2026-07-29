<!-- Features Light Section -->
<section id="features" class="py-24 lg:py-32 bg-slate-50/50 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
    <div class="absolute top-1/2 right-0 w-96 h-96 bg-emerald-400/10 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-12">
        <!-- Centered Header -->
        <div class="text-center mb-16 lg:mb-20" data-animate>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 border border-emerald-200/80 mb-6 shadow-sm">
                <span class="text-xs font-black text-emerald-600 uppercase tracking-widest">{{ __('landing.features.badge') }}</span>
            </div>
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight leading-tight">
                {!! __('landing.features.title') !!}
            </h2>
            <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto font-medium leading-relaxed">
                {{ __('landing.features.subtitle') }}
            </p>
        </div>

        <!-- Feature Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8" data-stagger>
            @php
                $featuresData = [
                    ['icon' => 'fa-users', 'from' => 'from-emerald-500', 'to' => 'to-teal-600', 'shadow' => 'shadow-emerald-500/20'],
                    ['icon' => 'fa-calendar-check', 'from' => 'from-blue-500', 'to' => 'to-indigo-600', 'shadow' => 'shadow-blue-500/20'],
                    ['icon' => 'fa-credit-card', 'from' => 'from-violet-500', 'to' => 'to-purple-600', 'shadow' => 'shadow-purple-500/20'],
                    ['icon' => 'fa-chart-pie', 'from' => 'from-amber-500', 'to' => 'to-orange-600', 'shadow' => 'shadow-amber-500/20'],
                ];
            @endphp
            @foreach(__('landing.features.items') as $index => $item)
                @if($index >= 4) @break @endif
                @php $data = $featuresData[$index] ?? $featuresData[0]; @endphp
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-lg shadow-slate-900/5 hover:border-emerald-300 hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300 group hover:-translate-y-2 text-center flex flex-col justify-between">
                    <div>
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br {{ $data['from'] }} {{ $data['to'] }} flex items-center justify-center mb-6 text-white shadow-md {{ $data['shadow'] }} group-hover:scale-110 transition-transform duration-300">
                            <i class="fas {{ $data['icon'] }} text-2xl"></i>
                        </div>
                        <h3 class="text-slate-900 font-extrabold text-xl mb-3 group-hover:text-emerald-600 transition-colors">{{ $item['title'] }}</h3>
                        <p class="text-slate-600 text-sm leading-relaxed font-medium">{{ Str::limit($item['description'], 110) }}</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-center text-xs font-extrabold text-emerald-600 group-hover:translate-x-1 transition-transform">
                        <span>{{ __('landing.nav.features') }}</span>
                        <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-left me-1' : 'fa-arrow-right ms-1' }}"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

