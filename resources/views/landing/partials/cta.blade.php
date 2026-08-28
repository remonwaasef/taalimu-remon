{{-- Final CTA Card — Matching Reference Image exactly --}}
<section id="final-cta" class="py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-5 lg:px-12 max-w-7xl" data-animate="fade-in">
        <div class="rounded-[32px] p-8 sm:p-12 lg:p-14 relative overflow-hidden bg-[#e8f5f3] border border-[#c5e8e4]">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">

                {{-- Visual Decor: Green Plant (Left side in RTL visual balance) --}}
                <div class="hidden lg:flex lg:col-span-3 items-center justify-center">
                    <img src="{{ asset('images/decor/plant.png') }}"
                         alt="Taalimu"
                         class="w-40 sm:w-48 h-auto object-contain drop-shadow-md">
                </div>

                {{-- Content & Actions (Right side in RTL) --}}
                <div class="lg:col-span-9 text-center lg:text-start space-y-4">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 leading-tight tracking-tight">
                        جاهز للارتقاء بإدارة مؤسستك التعليمية؟
                    </h2>
                    <p class="text-sm sm:text-base text-slate-600 font-medium max-w-2xl mx-auto lg:mx-0">
                        ابدأ رحلتك المجانية الآن واكتشف كيف يمكن لـ Taalimu تبسيط عملك وتنظيم مؤسستك.
                    </p>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3.5 pt-2">
                        <a href="{{ route('register') }}"
                           data-track="cta_primary"
                           class="w-full sm:w-auto px-8 py-3.5 rounded-full text-white font-extrabold text-sm text-decoration-none shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 text-center inline-flex items-center justify-center gap-2"
                           style="background-color: #2E8B83;">
                            <span>ابدأ مجاناً الآن</span>
                            <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                        </a>
                        <button type="button"
                                @click="$dispatch('open-demo-modal')"
                                class="w-full sm:w-auto px-7 py-3.5 rounded-full border border-slate-300 bg-white text-slate-700 font-bold text-sm hover:bg-slate-50 transition-all text-center inline-flex items-center justify-center gap-2 shadow-2xs">
                            <i class="far fa-calendar-alt text-base" style="color: #2E8B83;"></i>
                            <span>احجز عرض توضيحي</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>