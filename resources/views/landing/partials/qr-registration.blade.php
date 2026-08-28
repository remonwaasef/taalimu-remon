{{-- QR Code & WhatsApp Dual Cards — Matching Reference Image --}}
<section id="features-split" class="py-16 lg:py-24 bg-[#f8fafb]">
    <div class="container mx-auto px-5 lg:px-12 max-w-7xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8" data-animate="fade-in">

            {{-- Card 1: QR Code Attendance --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 lg:p-8">
                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#e8f5f3] text-[10px] font-bold text-[#2E8B83] mb-4">
                        <i class="fas fa-qrcode text-[9px]"></i>
                        <span>حضور ذكي وسريع</span>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-xl lg:text-2xl font-black text-slate-900 mb-2">
                        نظام حضور <span style="color: #2E8B83;">QR Code</span>
                    </h3>

                    {{-- Description --}}
                    <p class="text-sm text-slate-500 leading-relaxed mb-5">
                        سجّل حضور وانصراف الطلاب بسهولة وسرعة
                        باستخدام رمز QR مع تقارير فورية ودقيقة.
                    </p>

                    {{-- Feature Pills --}}
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-50 border border-slate-100 text-[11px] font-semibold text-slate-600">
                            <i class="fas fa-bolt text-[#2E8B83] text-[9px]"></i>
                            تسجيل فوري
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-50 border border-slate-100 text-[11px] font-semibold text-slate-600">
                            <i class="fas fa-chart-line text-[#2E8B83] text-[9px]"></i>
                            تقارير دقيقة
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-50 border border-slate-100 text-[11px] font-semibold text-slate-600">
                            <i class="fas fa-map-marker-alt text-[#2E8B83] text-[9px]"></i>
                            أين وماذا
                        </span>
                    </div>

                    {{-- CTA Link --}}
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#2E8B83] hover:underline text-decoration-none transition-colors">
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                        <span>تعرف على نظام الحضور</span>
                    </a>
                </div>

                {{-- Phone Mockup --}}
                <div class="flex justify-center pb-4 px-6">
                    <div class="relative w-[180px]">
                        <div class="bg-slate-900 rounded-[24px] p-1.5 shadow-xl">
                            <div class="bg-white rounded-[18px] overflow-hidden p-3">
                                {{-- QR Code Visual --}}
                                <div class="text-center">
                                    <div class="text-[9px] font-bold text-slate-400 mb-2">مسح رمز الحضور</div>
                                    <div class="bg-white border-2 border-dashed border-[#2E8B83] rounded-xl p-4 mb-3">
                                        <div class="grid grid-cols-5 gap-0.5 w-20 mx-auto">
                                            @for($i = 0; $i < 25; $i++)
                                                <div class="aspect-square rounded-sm {{ rand(0,1) ? 'bg-slate-800' : 'bg-white' }}"></div>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-center gap-1 text-[8px] font-bold" style="color: #2E8B83;">
                                        <i class="fas fa-check-circle"></i>
                                        <span>تم تسجيل الحضور نجاحاً</span>
                                    </div>
                                    <div class="text-[7px] text-slate-400 mt-1">09:15 صباحاً</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: WhatsApp Notifications --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 lg:p-8">
                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-[10px] font-bold text-emerald-700 mb-4">
                        <i class="fab fa-whatsapp text-[9px]"></i>
                        <span>تواصل فوري وفعّال</span>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-xl lg:text-2xl font-black text-slate-900 mb-2">
                        إشعارات عبر <span class="text-emerald-600">WhatsApp</span>
                    </h3>

                    {{-- Description --}}
                    <p class="text-sm text-slate-500 leading-relaxed mb-5">
                        إشعارات فورية لكل ما يهمك: الحضور، المدفوعات،
                        الدرجات، المواعيد والمستجدات المهمة.
                    </p>

                    {{-- Feature Pills --}}
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-50 border border-slate-100 text-[11px] font-semibold text-slate-600">
                            <i class="fas fa-bell text-emerald-600 text-[9px]"></i>
                            إشعارات فورية
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-50 border border-slate-100 text-[11px] font-semibold text-slate-600">
                            <i class="fas fa-comments text-emerald-600 text-[9px]"></i>
                            رسائل تلقائية
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-50 border border-slate-100 text-[11px] font-semibold text-slate-600">
                            <i class="fas fa-user-shield text-emerald-600 text-[9px]"></i>
                            تنبيهات الأهل
                        </span>
                    </div>

                    {{-- CTA Link --}}
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-600 hover:underline text-decoration-none transition-colors">
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                        <span>تعرف على الإشعارات</span>
                    </a>
                </div>

                {{-- Phone Mockup with WhatsApp --}}
                <div class="flex justify-center pb-4 px-6">
                    <div class="relative w-[180px]">
                        <div class="bg-slate-900 rounded-[24px] p-1.5 shadow-xl">
                            <div class="bg-white rounded-[18px] overflow-hidden">
                                {{-- WhatsApp Header --}}
                                <div class="bg-emerald-600 px-3 py-2 flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                                        <i class="fab fa-whatsapp text-white text-[8px]"></i>
                                    </div>
                                    <span class="text-[8px] font-bold text-white">Taalimu</span>
                                    <div class="ms-auto flex items-center gap-1">
                                        <span class="w-3 h-3 rounded-full bg-red-500 text-white text-[6px] flex items-center justify-center font-bold">1</span>
                                    </div>
                                </div>
                                {{-- Messages --}}
                                <div class="p-2 space-y-1.5 bg-[#ECE5DD]">
                                    <div class="bg-white rounded-lg px-2 py-1.5 text-[7px] text-slate-700 shadow-sm max-w-[85%]">
                                        <p class="font-bold text-emerald-700 mb-0.5">تنبيه حضور</p>
                                        <p>تم تسجيل حضور ابنكم أحمد ✅</p>
                                        <span class="text-[5px] text-slate-400 block text-end">09:15 ص</span>
                                    </div>
                                    <div class="bg-white rounded-lg px-2 py-1.5 text-[7px] text-slate-700 shadow-sm max-w-[85%]">
                                        <p class="font-bold text-emerald-700 mb-0.5">تحديث الدرجات</p>
                                        <p>تم رصد درجة اختبار الرياضيات 📊</p>
                                        <span class="text-[5px] text-slate-400 block text-end">10:30 ص</span>
                                    </div>
                                    <div class="bg-white rounded-lg px-2 py-1.5 text-[7px] text-slate-700 shadow-sm max-w-[85%]">
                                        <p class="font-bold text-emerald-700 mb-0.5">دفع مستحقات</p>
                                        <p>تم استلام دفعة بقيمة 500 ر.س 💰</p>
                                        <span class="text-[5px] text-slate-400 block text-end">02:45 م</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>