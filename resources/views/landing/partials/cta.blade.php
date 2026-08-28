{{-- Final CTA Section — Matching Reference Image --}}
<section id="final-cta" class="py-16 lg:py-20 bg-white">
    <div class="container mx-auto px-5 lg:px-12 max-w-7xl" data-animate="fade-in">
        <div class="rounded-3xl px-6 py-12 sm:px-12 sm:py-16 text-center relative overflow-hidden" style="background: linear-gradient(135deg, #2E8B83 0%, #1a6b64 100%);">

            {{-- Decorative circles --}}
            <div class="absolute top-0 start-0 w-40 h-40 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 end-0 w-60 h-60 bg-white/5 rounded-full translate-x-1/3 translate-y-1/3"></div>

            {{-- Content --}}
            <div class="relative z-10">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white leading-tight mb-4">
                    جاهز للارتقاء بإدارة مؤسستك التعليمية؟
                </h2>
                <p class="text-sm sm:text-base text-white/80 font-medium max-w-xl mx-auto mb-8">
                    ابدأ رحلتك المجانية الآن واكتشف كيف يمكن لـ Taalimu تبسيط عملك وتنظيم مؤسستك.
                </p>

                {{-- Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('register') }}"
                       data-track="cta_primary"
                       class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-white font-extrabold text-sm text-decoration-none shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 text-center inline-flex items-center justify-center gap-2"
                       style="color: #2E8B83;">
                        <span>ابدأ مجاناً الآن</span>
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                    <button type="button"
                            @click="$dispatch('open-demo-modal')"
                            class="w-full sm:w-auto px-6 py-3.5 rounded-xl border-2 border-white/40 text-white font-bold text-sm hover:bg-white/10 transition-all text-center inline-flex items-center justify-center gap-2">
                        <i class="far fa-calendar-alt text-sm"></i>
                        <span>احجز عرض توضيحي</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>