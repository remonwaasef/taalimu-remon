{{-- Section 7: Final CTA (Spacious Soft Mint Background) --}}
<section class="py-24 lg:py-36 bg-[#E6F4F3]/60 relative overflow-hidden border-t border-[#B2DDD9]/40">
    <!-- Subtle Ambient Glow -->
    <div class="absolute -bottom-24 start-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-emerald-500/10 blur-3xl pointer-events-none rounded-full"></div>

    <div class="container mx-auto px-6 lg:px-12 max-w-4xl text-center relative z-10" data-animate="fade-in">
        
        <!-- Eyebrow Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-[#B2DDD9] text-[#25746D] text-xs font-bold mb-6 shadow-2xs">
            <i class="fas fa-sparkles text-xs"></i>
            <span>انضم لأكثر من 500+ مركز تعليمي ومدرس</span>
        </div>

        <!-- Main Headline -->
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 leading-tight mb-6">
            جاهز للارتقاء<br>
            بإدارة مؤسستك التعليمية؟
        </h2>

        <!-- Supporting Copy -->
        <p class="text-base sm:text-lg text-slate-700 font-medium leading-relaxed mb-10 max-w-2xl mx-auto">
            ابدأ رحلتك الآن واكتشف كيف يمكن لـ Taalimu تبسيط عملياتك اليومية وتوفير وقتك.
        </p>

        <!-- Two Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
            <a href="{{ route('register') }}" data-track="final_cta_primary" class="w-full sm:w-auto px-8 py-4 rounded-xl text-white font-extrabold text-base text-decoration-none shadow-sm hover:shadow-md transition-all transform hover:-translate-y-0.5 bg-[#2E8B83] hover:bg-[#25746D] text-center flex items-center justify-center gap-2">
                <span>ابدأ مجانًا</span>
                <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
            </a>

            <button
                type="button"
                @click="$dispatch('open-demo-modal')"
                class="w-full sm:w-auto px-7 py-4 rounded-xl border border-slate-300 bg-white text-slate-800 font-bold text-base hover:bg-slate-50 transition-all text-center flex items-center justify-center gap-2 shadow-2xs"
            >
                <i class="fas fa-play-circle text-[#2E8B83] text-lg"></i>
                <span>احجز عرضًا توضيحيًا</span>
            </button>
        </div>

        <p class="text-xs font-semibold text-slate-500">
            إعداد سريع في دقائق • بدون بطاقة ائتمان • تجربة كاملة 14 يوماً
        </p>

    </div>
</section>