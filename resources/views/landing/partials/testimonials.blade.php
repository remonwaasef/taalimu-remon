{{-- Real Social Proof & Center Testimonials --}}
<section id="testimonials" class="py-20 lg:py-28 bg-white relative overflow-hidden border-t border-slate-200">
    <div class="container mx-auto px-4 lg:px-12 max-w-7xl">
        
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-16" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-50 border border-teal-200 text-[#2E8B83] text-xs font-black mb-4 shadow-xs">
                <i class="fas fa-quote-left text-xs"></i>
                <span>{{ __('landing.testimonials.badge') !== 'landing.testimonials.badge' ? __('landing.testimonials.badge') : 'قصص نجاح واقعية' }}</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 leading-tight mb-3">
                ماذا يقول <span class="text-[#2E8B83]">أصحاب المراكز والمدرسون</span> عنا؟
            </h2>
            <p class="text-sm sm:text-base text-slate-600 font-medium">
                تجارب حقيقية من مدراء مراكز تعليمية ومحاضرين وفروا مئات الساعات ونظموا عملهم بالكامل.
            </p>
        </div>

        <!-- Testimonials 3-Card Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8" data-animate="fade-in">
            
            {{-- Testimonial 1 --}}
            <div class="bg-gradient-to-br from-slate-50 to-teal-50/30 rounded-3xl p-6 sm:p-8 border border-teal-200/60 shadow-sm flex flex-col justify-between hover:shadow-lg transition-all hover:-translate-y-1">
                <div>
                    <div class="flex items-center gap-1 text-amber-400 text-xs mb-4">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-xs sm:text-sm font-bold text-slate-800 leading-relaxed mb-6">
                        "كنا نستهلك أول ربع ساعة من كل حصة لمجرد تسجيل أسماء 80 طالباً. مع كروت QR في Taalimu أصبح المسح يتم في 40 ثانية فقط وتصل رسالة فورية للأهالي. وفرت علينا فوضى الاستقبال بالكامل."
                    </p>
                </div>
                <div class="pt-4 border-t border-teal-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#2E8B83] text-white flex items-center justify-center font-bold text-sm">
                        أ.خ
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-slate-900">أ/ خالد السعيد</h4>
                        <span class="text-[10px] font-bold text-[#2E8B83]">مدير أكاديمية الأوائل - الجيزة</span>
                    </div>
                </div>
            </div>

            {{-- Testimonial 2 --}}
            <div class="bg-gradient-to-br from-slate-50 to-emerald-50/30 rounded-3xl p-6 sm:p-8 border border-emerald-200/60 shadow-sm flex flex-col justify-between hover:shadow-lg transition-all hover:-translate-y-1">
                <div>
                    <div class="flex items-center gap-1 text-amber-400 text-xs mb-4">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-xs sm:text-sm font-bold text-slate-800 leading-relaxed mb-6">
                        "أكبر كابوس كان حسابات نهاية الشهر ونسب 12 مدرساً ومساعداً. الآن النظام يخرج كشف الحساب والعمولات بنقرة زر واحدة بدون أي خطأ بشري أو خلاف مع أي مدرس."
                    </p>
                </div>
                <div class="pt-4 border-t border-emerald-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-sm">
                        د.م
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-slate-900">د/ محمود فهمي</h4>
                        <span class="text-[10px] font-bold text-emerald-700">مؤسس سنتر النخبة التعليمي - الإسكندرية</span>
                    </div>
                </div>
            </div>

            {{-- Testimonial 3 --}}
            <div class="bg-gradient-to-br from-slate-50 to-teal-50/30 rounded-3xl p-6 sm:p-8 border border-teal-200/60 shadow-sm flex flex-col justify-between hover:shadow-lg transition-all hover:-translate-y-1 md:col-span-2 lg:col-span-1">
                <div>
                    <div class="flex items-center gap-1 text-amber-400 text-xs mb-4">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-xs sm:text-sm font-bold text-slate-800 leading-relaxed mb-6">
                        "كمدرس فيزياء مستقل، كنت محرجاً دائماً من تذكير الطلاب بالأقساط المتأخرة. Taalimu أراحتني تماماً، فالإشعارات المالية التلقائية على الواتساب رفعت نسبة السداد لـ 98% في موعدها."
                    </p>
                </div>
                <div class="pt-4 border-t border-teal-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#2E8B83] text-white flex items-center justify-center font-bold text-sm">
                        م.ع
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-slate-900">م/ عمر عبد العزيز</h4>
                        <span class="text-[10px] font-bold text-[#2E8B83]">محاضر فيزياء للثانوية العامة</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
