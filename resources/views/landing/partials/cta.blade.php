{{-- Section 6: Final CTA Matching Image 2 --}}
<section class="py-16 lg:py-24 bg-white relative overflow-hidden">
    <div class="container mx-auto px-6 lg:px-12 max-w-6xl">
        
        <!-- Mint Box Container -->
        <div class="relative rounded-3xl bg-[#EAF5F3] p-10 sm:p-16 border border-[#BDE3DC] overflow-hidden text-center" data-animate="fade-in">
            
            <!-- Left Decorative Potted Plant (Matching Image 2) -->
            <div class="absolute -bottom-6 start-4 sm:start-8 w-28 sm:w-40 pointer-events-none hidden md:block opacity-90">
                <img src="{{ asset('images/decor/plant.png') }}" alt="Decoration" class="w-full h-auto object-contain">
            </div>

            <!-- Centered Content -->
            <div class="max-w-2xl mx-auto relative z-10">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 leading-tight mb-4">
                    جاهز للارتقاء بإدارة مؤسستك التعليمية؟
                </h2>

                <p class="text-xs sm:text-sm text-slate-600 font-medium leading-relaxed mb-8 max-w-xl mx-auto">
                    ابدأ رحلتك المجانية الآن واكتشف كيف يمكن لـ Taalimu تبسيط عملك وتنظيم مؤسستك.
                </p>

                <!-- Two Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" data-track="final_cta_primary" class="w-full sm:w-auto px-8 py-3.5 rounded-xl text-white font-extrabold text-sm text-decoration-none shadow-xs hover:shadow-md transition-all transform hover:-translate-y-0.5 bg-[#008A70] hover:bg-[#00745e] text-center flex items-center justify-center gap-2">
                        <span>ابدأ مجاناً الآن</span>
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                    </a>

                    <button
                        type="button"
                        @click="$dispatch('open-demo-modal')"
                        class="w-full sm:w-auto px-6 py-3.5 rounded-xl border border-slate-300 bg-white text-slate-800 font-bold text-sm hover:bg-slate-50 transition-all text-center flex items-center justify-center gap-2 shadow-2xs"
                    >
                        <i class="far fa-calendar-alt text-[#008A70]"></i>
                        <span>احجز عرض توضيحي</span>
                    </button>
                </div>
            </div>

        </div>

    </div>
</section>