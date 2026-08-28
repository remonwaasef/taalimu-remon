{{-- Hero Section — Matches Reference Image Exactly --}}
<section id="hero" class="relative pt-28 pb-16 lg:pt-36 lg:pb-24 overflow-hidden bg-gradient-to-b from-[#f0faf8] via-white to-white">
    <div class="container mx-auto px-5 lg:px-12 max-w-7xl relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-6 items-center">

            {{-- Right Column (RTL): Text Content --}}
            <div class="text-center lg:text-start order-2 lg:order-1" data-animate="fade-in">

                {{-- Eyebrow Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#e8f5f3] border border-[#c5e8e4] text-xs font-bold text-slate-700 mb-6">
                    <span class="w-2 h-2 rounded-full bg-[#2E8B83]"></span>
                    <span>منصة متكاملة لإدارة المؤسسات التعليمية</span>
                </div>

                {{-- Main Headline --}}
                <h1 class="text-3xl sm:text-4xl lg:text-[44px] xl:text-[52px] font-black text-slate-900 leading-[1.35] tracking-tight mb-5">
                    كل ما تحتاجه لإدارة<br>
                    <span style="color: #2E8B83;">مؤسستك التعليمية</span><br>
                    في منصة واحدة
                </h1>

                {{-- Subtitle --}}
                <p class="text-sm sm:text-base lg:text-lg text-slate-500 font-medium leading-relaxed mb-8 max-w-lg mx-auto lg:mx-0">
                    إدارة الطلبة والمعلمين والفصول والدرجات والمدفوعات.
                    التقارير والتواصل... بسهولة تامة من أي مكان وفي أي وقت.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 mb-8">
                    <a href="{{ route('register') }}"
                       data-track="hero_primary_cta"
                       class="w-full sm:w-auto px-7 py-3.5 rounded-xl text-white font-extrabold text-sm text-decoration-none shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 text-center inline-flex items-center justify-center gap-2"
                       style="background-color: #2E8B83;">
                        <span>ابدأ مجاناً الآن</span>
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                    <button type="button"
                            @click="$dispatch('open-demo-modal')"
                            class="w-full sm:w-auto px-6 py-3.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-bold text-sm hover:bg-slate-50 transition-all text-center inline-flex items-center justify-center gap-2">
                        <i class="far fa-calendar-alt" style="color: #2E8B83;"></i>
                        <span>احجز عرض توضيحي</span>
                    </button>
                </div>

                {{-- Trust Points --}}
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-x-5 gap-y-2 text-xs font-semibold text-slate-400">
                    <div class="flex items-center gap-1.5">
                        <i class="fas fa-credit-card" style="color: #2E8B83;"></i>
                        <span>بدون بطاقة ائتمان</span>
                    </div>
                    <span class="text-slate-200 hidden sm:inline">|</span>
                    <div class="flex items-center gap-1.5">
                        <i class="fas fa-headset" style="color: #2E8B83;"></i>
                        <span>دعم فني 24/7</span>
                    </div>
                    <span class="text-slate-200 hidden sm:inline">|</span>
                    <div class="flex items-center gap-1.5">
                        <i class="fas fa-bolt" style="color: #2E8B83;"></i>
                        <span>إعداد سريع خلال دقائق</span>
                    </div>
                </div>
            </div>

            {{-- Left Column (RTL): Laptop + Phone Mockup --}}
            <div class="order-1 lg:order-2 relative" data-animate="scale-in">
                <div class="relative mx-auto max-w-[540px] lg:max-w-none">

                    {{-- Laptop Frame --}}
                    <div class="relative">
                        {{-- Screen bezel --}}
                        <div class="bg-[#1e293b] rounded-t-2xl pt-3 pb-2 px-3 shadow-[0_20px_60px_-15px_rgba(15,23,42,0.25)]">
                            {{-- Camera dot --}}
                            <div class="flex justify-center mb-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span>
                            </div>
                            {{-- Dashboard Screenshot --}}
                            <div class="bg-white rounded-lg overflow-hidden">
                                <img src="{{ asset('images/hero-dashboard.webp') }}"
                                     alt="لوحة تحكم Taalimu"
                                     class="w-full h-auto block"
                                     loading="eager"
                                     width="900" height="520">
                            </div>
                        </div>
                        {{-- Keyboard base --}}
                        <div class="relative mx-auto" style="width: 108%; margin-inline-start: -4%;">
                            <div class="h-3 bg-gradient-to-b from-[#cbd5e1] to-[#94a3b8] rounded-b-xl"></div>
                            <div class="h-1 bg-[#94a3b8] rounded-b-lg mx-auto" style="width: 30%;"></div>
                        </div>
                    </div>

                    {{-- Phone Mockup (overlapping bottom-right in RTL = bottom-left visually) --}}
                    <div class="absolute -bottom-6 -start-4 sm:start-auto sm:-end-4 lg:-end-8 w-[120px] sm:w-[140px] lg:w-[160px] z-20">
                        <div class="bg-white rounded-[20px] shadow-[0_15px_40px_-10px_rgba(15,23,42,0.2)] border border-slate-200 overflow-hidden p-1.5">
                            {{-- Phone notch --}}
                            <div class="bg-slate-900 rounded-t-[14px] pt-2 pb-1">
                                <div class="flex justify-center">
                                    <span class="w-8 h-1 rounded-full bg-slate-700"></span>
                                </div>
                            </div>
                            {{-- Phone screen content: Mini dashboard --}}
                            <div class="bg-white rounded-b-[14px] p-2 text-[6px] leading-tight text-slate-600 space-y-1.5">
                                {{-- Circular progress --}}
                                <div class="flex items-center justify-center py-2">
                                    <div class="relative w-14 h-14">
                                        <svg viewBox="0 0 36 36" class="w-full h-full -rotate-90">
                                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                                  fill="none" stroke="#e2e8f0" stroke-width="3"/>
                                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                                  fill="none" stroke="#2E8B83" stroke-width="3" stroke-dasharray="90, 100" stroke-linecap="round"/>
                                        </svg>
                                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                                            <span class="text-[10px] font-black text-slate-900">90%</span>
                                            <span class="text-[5px] text-emerald-600 font-bold">+12%</span>
                                        </div>
                                    </div>
                                </div>
                                {{-- Stats rows --}}
                                <div class="space-y-1 px-0.5">
                                    <div class="flex items-center justify-between bg-slate-50 rounded px-1.5 py-1">
                                        <span class="text-slate-500">الحضور</span>
                                        <span class="font-bold text-slate-800">95%</span>
                                    </div>
                                    <div class="flex items-center justify-between bg-slate-50 rounded px-1.5 py-1">
                                        <span class="text-slate-500">الطلاب</span>
                                        <span class="font-bold text-slate-800">1,250</span>
                                    </div>
                                    <div class="flex items-center justify-between bg-slate-50 rounded px-1.5 py-1">
                                        <span class="text-slate-500">المدرسين</span>
                                        <span class="font-bold text-slate-800">48</span>
                                    </div>
                                </div>
                                {{-- Bottom bar --}}
                                <div class="flex items-center justify-around pt-1.5 border-t border-slate-100">
                                    <i class="fas fa-home text-[8px]" style="color: #2E8B83;"></i>
                                    <i class="fas fa-chart-bar text-[8px] text-slate-300"></i>
                                    <i class="fas fa-users text-[8px] text-slate-300"></i>
                                    <i class="fas fa-cog text-[8px] text-slate-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>