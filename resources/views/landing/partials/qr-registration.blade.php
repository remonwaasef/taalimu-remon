{{-- QR Code & WhatsApp Dual Split Section — Matching Reference Image --}}
<section id="features-split" class="py-16 lg:py-24 bg-[#f8fafc]">
    <div class="container mx-auto px-5 lg:px-12 max-w-7xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8" data-animate="fade-in">

            {{-- Card 1: QR Code Attendance System --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col justify-between">
                <div class="p-6 sm:p-8 lg:p-10">
                    {{-- Pill Badge --}}
                    <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#e8f5f3] border border-[#c5e8e4] text-xs font-bold text-[#2E8B83] mb-4">
                        <i class="fas fa-qrcode text-[10px]"></i>
                        <span>حضور ذكي وسريع</span>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mb-3 tracking-tight">
                        نظام حضور <span style="color: #2E8B83;">QR Code</span>
                    </h3>

                    {{-- Description --}}
                    <p class="text-sm sm:text-base text-slate-600 font-medium leading-relaxed mb-6">
                        سجل حضور وانصراف الطلاب بسهولة وسرعة باستخدام رمز QR مع تقارير فورية ودقيقة.
                    </p>

                    {{-- 4 Feature Tag Pills --}}
                    <div class="flex flex-wrap gap-2.5 mb-8">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-50 border border-slate-200/80 text-xs font-bold text-slate-700">
                            <div class="w-5 h-5 rounded-full bg-teal-50 text-[#2E8B83] flex items-center justify-center text-[10px]">
                                <i class="fas fa-[#2E8B83] fa-bullseye"></i>
                            </div>
                            <span>دقة عالية</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-50 border border-slate-200/80 text-xs font-bold text-slate-700">
                            <div class="w-5 h-5 rounded-full bg-teal-50 text-[#2E8B83] flex items-center justify-center text-[10px]">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <span>سريع وسهل</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-50 border border-slate-200/80 text-xs font-bold text-slate-700">
                            <div class="w-5 h-5 rounded-full bg-teal-50 text-[#2E8B83] flex items-center justify-center text-[10px]">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <span>تقارير فورية</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-50 border border-slate-200/80 text-xs font-bold text-slate-700">
                            <div class="w-5 h-5 rounded-full bg-teal-50 text-[#2E8B83] flex items-center justify-center text-[10px]">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <span>آمن وموثوق</span>
                        </div>
                    </div>

                    {{-- CTA Link --}}
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-sm font-extrabold text-[#2E8B83] hover:underline text-decoration-none transition-colors">
                        <span>تعرف على نظام الحضور</span>
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>

                {{-- Mockup Visual Area --}}
                <div class="flex justify-center items-center pb-8 pt-2 px-6 bg-gradient-to-b from-transparent to-slate-50/50">
                    <div class="relative w-[210px]">
                        <div class="bg-slate-900 rounded-[30px] p-2 shadow-xl border border-slate-700">
                            <div class="flex justify-center pb-1">
                                <span class="w-10 h-1 rounded-full bg-slate-800"></span>
                            </div>
                            <div class="bg-slate-100 rounded-[22px] p-4 text-center space-y-3">
                                <div class="text-[10px] font-bold text-slate-500">امسح رمز QR للحضور</div>
                                {{-- QR Box Visual --}}
                                <div class="bg-white border-2 border-dashed border-[#2E8B83] rounded-2xl p-4 shadow-2xs">
                                    <div class="grid grid-cols-5 gap-1 w-24 mx-auto">
                                        <div class="w-4 h-4 bg-slate-900 rounded-xs"></div>
                                        <div class="w-4 h-4 bg-slate-900 rounded-xs"></div>
                                        <div class="w-4 h-4 bg-white"></div>
                                        <div class="w-4 h-4 bg-slate-900 rounded-xs"></div>
                                        <div class="w-4 h-4 bg-slate-900 rounded-xs"></div>
                                        <div class="w-4 h-4 bg-slate-900 rounded-xs"></div>
                                        <div class="w-4 h-4 bg-white"></div>
                                        <div class="w-4 h-4 bg-slate-900 rounded-xs"></div>
                                        <div class="w-4 h-4 bg-white"></div>
                                        <div class="w-4 h-4 bg-slate-900 rounded-xs"></div>
                                        <div class="w-4 h-4 bg-white"></div>
                                        <div class="w-4 h-4 bg-slate-900 rounded-xs"></div>
                                        <div class="w-4 h-4 bg-slate-900 rounded-xs"></div>
                                        <div class="w-4 h-4 bg-slate-900 rounded-xs"></div>
                                        <div class="w-4 h-4 bg-white"></div>
                                        <div class="w-4 h-4 bg-slate-900 rounded-xs"></div>
                                        <div class="w-4 h-4 bg-slate-900 rounded-xs"></div>
                                        <div class="w-4 h-4 bg-white"></div>
                                        <div class="w-4 h-4 bg-slate-900 rounded-xs"></div>
                                        <div class="w-4 h-4 bg-slate-900 rounded-xs"></div>
                                    </div>
                                </div>
                                {{-- Success Pill Badge --}}
                                <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-extrabold shadow-2xs border border-emerald-200">
                                    <i class="fas fa-check-circle text-emerald-600 text-xs"></i>
                                    <span>تم تسجيل الحضور بنجاح</span>
                                </div>
                                <div class="text-[8px] font-mono text-slate-400">09:15 AM</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: WhatsApp Notifications --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col justify-between">
                <div class="p-6 sm:p-8 lg:p-10">
                    {{-- Pill Badge --}}
                    <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-700 mb-4">
                        <i class="fab fa-whatsapp text-[11px]"></i>
                        <span>تواصل فوري وفعّال</span>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mb-3 tracking-tight">
                        إشعارات عبر <span class="text-emerald-600">WhatsApp</span>
                    </h3>

                    {{-- Description --}}
                    <p class="text-sm sm:text-base text-slate-600 font-medium leading-relaxed mb-6">
                        إشعارات فورية لكل ما يهمك وأولياء الأمور، من الحضور، الدرجات، الرسوم، والفعاليات المهمة.
                    </p>

                    {{-- 4 Feature Tag Pills --}}
                    <div class="flex flex-wrap gap-2.5 mb-8">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-50 border border-slate-200/80 text-xs font-bold text-slate-700">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-[10px]">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <span>متكاملة</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-50 border border-slate-200/80 text-xs font-bold text-slate-700">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-[10px]">
                                <i class="fas fa-lock"></i>
                            </div>
                            <span>آمنة</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-50 border border-slate-200/80 text-xs font-bold text-slate-700">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-[10px]">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <span>موثوقة</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-50 border border-slate-200/80 text-xs font-bold text-slate-700">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-[10px]">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <span>فورية</span>
                        </div>
                    </div>

                    {{-- CTA Link --}}
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-sm font-extrabold text-emerald-600 hover:underline text-decoration-none transition-colors">
                        <span>تعرف على الإشعارات</span>
                        <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>

                {{-- Mockup Visual Area --}}
                <div class="flex justify-center items-center pb-8 pt-2 px-6 bg-gradient-to-b from-transparent to-slate-50/50">
                    <div class="relative w-[210px]">
                        {{-- WhatsApp Badge Decor --}}
                        <div class="absolute -top-3 -end-3 z-30 w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg text-lg">
                            <i class="fab fa-whatsapp"></i>
                            <span class="absolute -top-1 -end-1 w-4 h-4 rounded-full bg-red-500 text-white text-[8px] font-black flex items-center justify-center border-2 border-white">1</span>
                        </div>

                        <div class="bg-slate-900 rounded-[30px] p-2 shadow-xl border border-slate-700">
                            <div class="flex justify-center pb-1">
                                <span class="w-10 h-1 rounded-full bg-slate-800"></span>
                            </div>
                            <div class="bg-[#efeae2] rounded-[22px] overflow-hidden text-[9px]">
                                {{-- WhatsApp Top Bar --}}
                                <div class="bg-[#075e54] text-white px-3 py-2 flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <span class="font-bold">Taalimu</span>
                                </div>
                                {{-- Chat Messages --}}
                                <div class="p-3 space-y-2">
                                    <div class="bg-white p-2 rounded-xl rounded-tr-none shadow-2xs space-y-1 text-slate-800">
                                        <div class="font-bold text-emerald-700 text-[9px]">مرحباً ولي الأمر 👋</div>
                                        <div>تم تسجيل حضور أحمد اليوم في مادة الرياضيات</div>
                                        <div class="text-[7px] text-slate-400 text-end">09:15 AM</div>
                                    </div>
                                    <div class="bg-white p-2 rounded-xl rounded-tr-none shadow-2xs space-y-1 text-slate-800">
                                        <div class="font-bold text-emerald-700 text-[9px]">تذكير بموعد الاختبار 📝</div>
                                        <div>تذكير: موعد اختبار الرياضيات يوم الأحد 14 مايو</div>
                                        <div class="text-[7px] text-slate-400 text-end">10:30 AM</div>
                                    </div>
                                    <div class="bg-white p-2 rounded-xl rounded-tr-none shadow-2xs space-y-1 text-slate-800">
                                        <div class="font-bold text-emerald-700 text-[9px]">تم إرسال تقرير إنجازات 📊</div>
                                        <div>تم إرسال تقرير درجات الفصل الدراسي الثاني</div>
                                        <div class="text-[7px] text-slate-400 text-end">01:45 PM</div>
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