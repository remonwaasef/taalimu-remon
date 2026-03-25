<section 
    class="hero-section relative min-h-[95vh] pt-24 lg:pt-32 pb-16 overflow-hidden bg-white section-wave z-0" 
    id="hero"
    x-data="{ 
        mouseX: 0, 
        mouseY: 0,
        words: [
            '{{ __('landing.hero.types.center') ?? 'مركزك التعليمي' }}', 
            '{{ __('landing.hero.types.school') ?? 'مدرستك' }}', 
            '{{ __('landing.hero.types.academy') ?? 'أكاديميتك' }}'
        ],
        wordIndex: 0,
        currentWord: '',
        isDeleting: false,
        updateMouse(e) {
            this.mouseX = (e.clientX / window.innerWidth - 0.5) * 20;
            this.mouseY = (e.clientY / window.innerHeight - 0.5) * 20;
        },
        type() {
            let fullWord = this.words[this.wordIndex];
            if (this.isDeleting) {
                this.currentWord = fullWord.substring(0, this.currentWord.length - 1);
            } else {
                this.currentWord = fullWord.substring(0, this.currentWord.length + 1);
            }

            let speed = this.isDeleting ? 50 : 150;

            if (!this.isDeleting && this.currentWord === fullWord) {
                speed = 2000;
                this.isDeleting = true;
            } else if (this.isDeleting && this.currentWord === '') {
                this.isDeleting = false;
                this.wordIndex = (this.wordIndex + 1) % this.words.length;
                speed = 500;
            }

            setTimeout(() => this.type(), speed);
        }
    }"
    x-init="type()"
    @mousemove="updateMouse($event)"
>
    <!-- Premium Atmosphere -->
    <div class="absolute inset-0 bg-noise pointer-events-none -z-10"></div>
    <div class="hero-orb orb-1"></div>
    <div class="hero-orb orb-2"></div>

    <div
        dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
        class="container relative mx-auto px-4 lg:px-12"
    >
        <div class="flex flex-col lg:flex-row gap-16 lg:gap-10 items-center justify-between">
            <!-- Left Content: High Impact -->
            <div class="w-full lg:w-[46%] text-center lg:text-start z-10">
                <!-- Premium Badge -->
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full glass-premium mb-8 shadow-sm border border-slate-200/50 opacity-0" style="animation: heroFadeInUp 0.6s ease-out 0.1s forwards;">
                    <span class="flex h-2 w-2 rounded-full bg-[#22c55e] shadow-[0_0_8px_#22c55e]"></span>
                    <span class="text-[13px] font-extrabold text-[#0f172a] uppercase tracking-wider">{{ __('landing.hero.badge') }}</span>
                </div>

                <!-- Headline with Typewriter -->
                <h1
                    class="font-cairo text-4xl md:text-5xl lg:text-[3.5rem] xl:text-[4.2rem] font-[900] text-[#0f172a] leading-[1.08] mb-8 tracking-[-0.03em] opacity-0 min-h-[2.2em] lg:min-h-0"
                    style="animation: heroFadeInUp 0.7s ease-out 0.2s forwards;"
                >
                    أدِر <span class="text-[#22c55e] transition-all duration-300" x-text="currentWord"></span><br>
                    بذكاء ووفّر ساعات من العمل أسبوعياً
                </h1>

                <!-- Subheadline -->
                <p
                    class="text-lg md:text-xl text-slate-500 mb-12 max-w-xl mx-auto lg:mx-0 font-medium leading-[1.6] opacity-0"
                    style="animation: heroFadeInUp 0.7s ease-out 0.35s forwards;"
                >
                    {{ __('landing.hero.subtitle') }}
                </p>

                <!-- Premium CTA Group -->
                <div class="flex flex-col sm:flex-row gap-5 justify-center lg:justify-start mb-16 opacity-0" style="animation: heroFadeInUp 0.7s ease-out 0.5s forwards;">
                    <a href="{{ route('register') }}" class="relative group">
                        <div class="absolute inset-0 bg-[#22c55e] rounded-full blur-xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
                        <div class="relative bg-[#22c55e] text-white px-10 py-5 rounded-full font-[800] text-lg flex items-center justify-center gap-2 shadow-2xl shadow-green-500/30 hover:scale-[1.03] active:scale-[0.98] transition-all duration-300">
                            {{ __('landing.hero.cta_primary') }}
                            <i class="fas fa-arrow-right text-sm ms-1 rtl:rotate-180"></i>
                        </div>
                    </a>
                    
                    <a href="#demo" class="px-10 py-5 rounded-full font-[800] text-lg text-[#1e293b] border-2 border-slate-200 hover:bg-slate-50 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="fas fa-play text-xs opacity-40"></i>
                        {{ __('landing.hero.cta_secondary') }}
                    </a>
                </div>

                <!-- Trust Indicators -->
                <div class="trust-indicator opacity-0" style="animation: heroFadeInUp 0.7s ease-out 0.65s forwards;">
                    <div class="flex items-center justify-center lg:justify-start gap-4 mb-4">
                        <div class="flex -space-x-3 rtl:space-x-reverse">
                            @foreach([1,2,3,4] as $i)
                            <div class="w-10 h-10 rounded-full border-4 border-white shadow-sm overflow-hidden bg-slate-200">
                                <img src="https://i.pravatar.cc/100?u={{$i}}" alt="user" class="w-full h-full object-cover">
                            </div>
                            @endforeach
                            <div class="w-10 h-10 rounded-full border-4 border-white shadow-sm bg-[#0f172a] text-white flex items-center justify-center text-[11px] font-bold">+2k</div>
                        </div>
                        <div class="h-8 w-px bg-slate-200"></div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1 text-amber-500">
                                @for($i=0; $i<5; $i++) <i class="fas fa-star text-[11px]"></i> @endfor
                                <span class="text-sm font-bold text-[#1e293b] ms-1">4.9/5</span>
                            </div>
                            <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ __('landing.hero.ratings') ?? 'User Rating' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Visual: The 3D Masterpiece -->
            <div class="w-full lg:w-[50%] relative py-12 lg:py-0">
                <div 
                    class="relative w-full aspect-[4/3] flex items-center justify-center"
                    :style="`transform: perspective(1000px) rotateX(${mouseY/2}deg) rotateY(${-mouseX/2}deg)`"
                    style="transition: transform 0.1s ease-out;"
                >
                    <!-- Main Dashboard Window -->
                    <div class="relative w-full max-w-[550px] rounded-2xl overflow-hidden glass-premium shadow-[0_50px_100px_-20px_rgba(0,0,0,0.15)] border border-white/50 z-20">
                        <div class="bg-slate-50/50 backdrop-blur-md px-4 py-3 flex items-center gap-2 border-b border-slate-200/50">
                            <div class="flex gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-[#ff5f56]"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-[#ffbd2e]"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-[#27c93f]"></div>
                            </div>
                            <div class="flex-1 px-4">
                                <div class="bg-white/50 rounded-lg h-6 flex items-center px-4 text-[10px] text-slate-400 font-medium">app.taalimu.com</div>
                            </div>
                        </div>
                        <div class="bg-[#f8fafc] group">
                            <img 
                                src="{{ asset('images/hero-dashboard.png') }}" 
                                alt="Dashboard" 
                                class="w-full h-auto object-cover opacity-90 group-hover:opacity-100 transition-opacity"
                            >
                        </div>
                    </div>

                    <!-- Floating Glass Cards (Masterpiece Widgets) -->
                    <div 
                        class="absolute -top-10 -left-10 w-48 hero-widget p-4 rounded-2xl z-30 opacity-0"
                        :style="`transform: translate(${-mouseX}px, ${-mouseY}px)`"
                        style="animation: heroSlideInLeft 0.8s ease-out 0.8s forwards;"
                    >
                        <div class="text-[10px] font-bold text-slate-400 uppercase mb-2">{{ __('landing.hero.stats.revenue') }}</div>
                        <div class="flex items-end gap-2">
                            <span class="text-2xl font-black text-[#0f172a]">+38%</span>
                            <span class="text-[11px] text-[#22c55e] font-bold pb-1 bg-green-50 px-1.5 rounded">↑ 12%</span>
                        </div>
                        <div class="mt-4 flex gap-1 items-end">
                            @foreach([30, 45, 25, 60, 40, 75, 50, 90] as $h)
                            <div class="flex-1 bg-green-500/20 rounded-t-sm" style="height: {{$h}}px;"></div>
                            @endforeach
                        </div>
                    </div>

                    <div 
                        class="absolute -bottom-10 -left-6 w-56 hero-widget p-4 rounded-2xl z-30 border-l-4 border-l-[#22c55e] opacity-0"
                        :style="`transform: translate(${mouseX/2}px, ${mouseY/2}px)`"
                        style="animation: heroSlideInLeft 0.8s ease-out 1.1s forwards; animation: heroFloating 7s ease-in-out infinite 0.5s;"
                    >
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#e7f9ee] flex items-center justify-center text-[#22c55e]">
                                <i class="fab fa-whatsapp text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-[11px] font-black text-[#0f172a] truncate">Ahmed Mohamed</div>
                                <div class="text-[10px] text-slate-400 truncate">{{ __('landing.hero.mockup.whatsapp.payment_success') }}</div>
                            </div>
                        </div>
                    </div>

                    <div 
                        class="absolute -bottom-4 -right-12 w-48 hero-widget p-5 rounded-2xl z-30 opacity-0"
                        :style="`transform: translate(${-mouseX/3}px, ${-mouseY/3}px)`"
                        style="animation: heroFadeInRight 0.8s ease-out 1.4s forwards; animation: heroFloating 8s ease-in-out infinite 1s;"
                    >
                        <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">التقارير التلقائية</div>
                        <div class="text-3xl font-black text-[#0f172a]">94%</div>
                        <div class="mt-3 flex items-center gap-2">
                            <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#22c55e] rounded-full" style="width: 94%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="absolute inset-0 bg-green-500/10 rounded-full blur-[100px] -z-10 scale-125"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Infinity Trust Marquee -->
    <div class="mt-32 trust-marquee-container border-y border-slate-100 py-10">
        <div class="trust-marquee-track">
            @php
                $marqueeStats = [
                    ['value' => '500+', 'label' => __('landing.hero.trust_centers') ?? 'Educational Centers'],
                    ['value' => '10,000+', 'label' => __('landing.hero.trust_students') ?? 'Active Students'],
                    ['value' => '98%', 'label' => __('landing.hero.trust_satisfaction') ?? 'Satisfaction Rate'],
                    ['value' => '15h', 'label' => __('landing.hero.trust_saved') ?? 'Saved Per Week'],
                ];
            @endphp
            
            {{-- Double the loop for seamless infinite scrolling --}}
            @foreach(array_merge($marqueeStats, $marqueeStats, $marqueeStats) as $stat)
            <div class="flex items-center gap-4 min-w-[200px]">
                <div class="text-3xl font-black text-[#0f172a] tracking-tight">{{ $stat['value'] }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-tight w-24">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>
