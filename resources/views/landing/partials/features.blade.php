<section id="features" class="py-16 lg:py-24 bg-slate-100 border-y border-slate-200">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12 lg:mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 mb-6">
                <span class="text-sm font-medium text-primary">{{ __('landing.features.badge') }}</span>
            </div>
            <h2 class="text-3xl lg:text-4xl font-bold text-foreground mb-4">
                {!! __('landing.features.title') !!}
            </h2>
            <p class="text-muted-foreground text-lg max-w-2xl mx-auto">
                {{ __('landing.features.subtitle') }}
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            {{-- Feature data remains same, just updated card styling --}}
@php
    $featuresData = [
        ['icon' => 'user-friends', 'color' => 'blue', 'delay' => '0'],
        ['icon' => 'calendar-check', 'color' => 'green', 'delay' => '100'],
        ['icon' => 'whatsapp', 'color' => 'whatsapp', 'delay' => '200'],
        ['icon' => 'file-invoice-dollar', 'color' => 'blue', 'delay' => '300'],
        ['icon' => 'chart-line', 'color' => 'indigo', 'delay' => '400'],
        ['icon' => 'mobile-alt', 'color' => 'purple', 'delay' => '500'],
        ['icon' => 'shield-alt', 'color' => 'green', 'delay' => '600'],
        ['icon' => 'sync', 'color' => 'blue', 'delay' => '700']
    ];
@endphp

            @foreach(__('landing.features.items') as $index => $item)
                <div
                    class="group bg-white rounded-3xl p-8 border border-white shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2 animate-fade-in"
                    style="animation-delay: {{ $index * 100 }}ms;"
                >
                    <!-- Icon -->
                    <div class="w-14 h-14 rounded-2xl bg-primary/5 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-{{ $featuresData[$index]['icon'] ?? 'star' }} text-2xl text-primary"></i>
                    </div>

                    <!-- Title -->
                    <h3 class="text-xl font-semibold text-foreground mb-3">
                        {{ $item['title'] }}
                    </h3>

                    <!-- Description -->
                    <p class="text-muted-foreground leading-relaxed text-sm">
                        {{ $item['description'] }}
                    </p>

                    <!-- Learn More -->
                    <div class="mt-6 flex items-center gap-2 text-primary opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-sm font-bold">{{ __('landing.features.learn_more') }}</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
