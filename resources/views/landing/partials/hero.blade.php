{{-- Section 2: Spacious Minimal Premium Hero --}}
<section id="hero" class="relative pt-32 pb-20 lg:pt-44 lg:pb-36 overflow-hidden bg-gradient-to-b from-[#FAFBFB] via-white to-white">
    <!-- Extremely subtle warm ambient light -->
    <div class="absolute -top-40 start-1/2 -translate-x-1/2 w-[900px] h-[500px] bg-gradient-to-tr from-teal-500/4 via-emerald-500/3 to-transparent blur-3xl pointer-events-none rounded-full"></div>

    <div class="container mx-auto px-6 lg:px-12 max-w-7xl relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Right Column: Confident Arabic Typography & Clean CTAs -->
            <div class="lg:col-span-6 text-center lg:text-start" data-animate="fade-in">
                
                <!-- Eyebrow Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/60 text-[#2E8B83] text-xs font-bold mb-6 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-[#2E8B83]"></span>
                    <span>منصة إدارة التعليم المتكاملة</span>
                </div>

                <!-- Main Large Headline -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-black text-slate-900 leading-[1.25] tracking-tight mb-6">
                    كل ما تحتاجه لإدارة<br>
                    <span class="text-[#2E8B83] inline-block">مؤسستك التعليمية</span><br>
                    في منصة واحدة
                </h1>

                <!-- Supporting Copy -->
                <p class="text-base sm:text-lg text-slate-600 font-medium leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0">
                    إدارة الطلاب والمعلمين والفصول والمدفوعات والتقارير والتواصل من مكان واحد.
                </p>

                <!-- Two Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 mb-10">
                    <a href="{{ route('register') }}" data-track="hero_primary_cta" class="w-full sm:w-auto px-8 py-4 rounded-xl text-white font-extrabold text-base text-decoration-none shadow-sm hover:shadow-md transition-all transform hover:-translate-y-0.5 bg-[#2E8B83] hover:bg-[#25746D] text-center flex items-center justify-center gap-2">
                        <span>ابدأ مجانًا</span>
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                    </a>

                    <button
                        type="button"
                        @click="$dispatch('open-demo-modal')"
                        class="w-full sm:w-auto px-7 py-4 rounded-xl border border-slate-200 bg-white text-slate-800 font-bold text-base hover:bg-slate-50 hover:border-slate-300 transition-all text-center flex items-center justify-center gap-2.5 shadow-2xs group"
                    >
                        <i class="fas fa-play-circle text-[#2E8B83] text-lg group-hover:scale-105 transition-transform"></i>
                        <span>احجز عرضًا توضيحيًا</span>
                    </button>
                </div>

                <!-- Three Small Trust Points -->
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-y-2 gap-x-8 text-xs font-bold text-slate-500">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                        <span>بدون بطاقة ائتمان</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-bolt text-emerald-600 text-sm"></i>
                        <span>دعم سريع</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-shield-alt text-emerald-600 text-sm"></i>
                        <span>متاح 24/7</span>
                    </div>
                </div>

            </div>

            <!-- Left Column: Realistic Dual-Device Product Showcase -->
            <div class="lg:col-span-6" data-animate="scale-in">
                <div class="relative mx-auto max-w-lg lg:max-w-none">
                    
                    <!-- Laptop Hardware Frame -->
                    <div class="relative bg-slate-900 rounded-t-2xl pt-3 px-3 pb-0 shadow-[0_25px_60px_-15px_rgba(15,23,42,0.18)] border border-slate-800">
                        <!-- Top Camera & Notch Dot -->
                        <div class="flex items-center justify-center pb-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-700"></span>
                        </div>

                        <!-- Real Dashboard Display Screen -->
                        <div class="rounded-t-lg overflow-hidden bg-white border border-slate-100">
                            <img src="{{ asset('images/hero-dashboard.webp') }}" alt="Taalimu Dashboard Platform" class="w-full h-auto object-cover block">
                        </div>
                    </div>
                    <!-- Laptop Base Chin -->
                    <div class="h-3 bg-gradient-to-b from-slate-300 to-slate-400 rounded-b-xl max-w-[104%] -ms-[2%] shadow-md flex items-center justify-center">
                        <div class="w-20 h-1 bg-slate-500/40 rounded-full"></div>
                    </div>

                    <!-- Realistic Smartphone in Front (Subtle Depth & Reflection) -->
                    <div class="absolute -bottom-6 -start-4 sm:-start-8 w-44 sm:w-52 bg-slate-900 p-2.5 rounded-3xl shadow-[0_20px_40px_rgba(15,23,42,0.25)] border-2 border-slate-800 hidden sm:block transform rotate-1 hover:rotate-0 transition-transform">
                        <!-- Phone Screen Content -->
                        <div class="bg-white rounded-2xl overflow-hidden p-3 border border-slate-100">
                            <!-- Mini Header -->
                            <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-100">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded-full bg-[#2E8B83] text-white flex items-center justify-center text-[9px] font-bold">
                                        <i class="fas fa-qrcode"></i>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-800">حضور سريع</span>
                                </div>
                                <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">مباشر</span>
                            </div>
                            
                            <!-- Scanner Card -->
                            <div class="bg-slate-50 rounded-xl p-2.5 text-center mb-2 border border-slate-100">
                                <i class="fas fa-qrcode text-3xl text-slate-800 mb-1"></i>
                                <p class="text-[9px] font-bold text-slate-700 leading-tight">مسح كود الطالب</p>
                                <span class="text-[8px] text-slate-400 font-mono">ID: ST-9042</span>
                            </div>

                            <!-- Success Toast Notification -->
                            <div class="bg-emerald-50 rounded-lg p-1.5 border border-emerald-100 flex items-center gap-1.5 text-start">
                                <i class="fas fa-check-circle text-emerald-600 text-[10px]"></i>
                                <div class="leading-none">
                                    <p class="text-[9px] font-bold text-emerald-800 mb-0.5">تم رصد الحضور</p>
                                    <span class="text-[7px] text-emerald-600">إشعار WhatsApp تم إرساله</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>