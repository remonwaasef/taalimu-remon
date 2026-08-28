{{-- Section 4: Dual Split Feature Cards (QR Attendance & WhatsApp Notifications) Matching Reference Image --}}
<section id="features-split" class="py-20 lg:py-28 bg-[#FAFBFB] relative border-y border-slate-100">
    <div class="container mx-auto px-6 lg:px-12 max-w-7xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10">
            
            <!-- Right Card (RTL): نظام حضور QR Code -->
            <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-sm flex flex-col justify-between" data-animate="fade-in">
                <div>
                    <!-- Eyebrow -->
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-xs font-bold mb-4" style="color: #2E8B83;">
                        <span>حضور ذكي وسريع</span>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mb-3">
                        نظام حضور QR Code
                    </h3>

                    <p class="text-xs sm:text-sm text-slate-600 font-medium leading-relaxed mb-6">
                        سجل حضور وانصراف الطلاب بسهولة وسرعة باستخدام رمز QR مع تقارير فورية ودقيقة.
                    </p>

                    <!-- 4 Mini Feature Pills in 2x2 Grid -->
                    <div class="grid grid-cols-2 gap-2.5 mb-8">
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs font-bold text-slate-700">
                            <i class="fas fa-bullseye" style="color: #2E8B83;"></i>
                            <span>دقة عالية</span>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs font-bold text-slate-700">
                            <i class="fas fa-bolt" style="color: #2E8B83;"></i>
                            <span>سريع ومريح</span>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs font-bold text-slate-700">
                            <i class="fas fa-chart-bar" style="color: #2E8B83;"></i>
                            <span>تقارير فورية</span>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs font-bold text-slate-700">
                            <i class="fas fa-shield-alt" style="color: #2E8B83;"></i>
                            <span>أمن وموثوق</span>
                        </div>
                    </div>

                    <!-- Button -->
                    <button type="button" @click="$dispatch('open-demo-modal')" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-800 font-bold text-xs hover:border-[#2E8B83] hover:text-[#2E8B83] transition-colors inline-flex items-center gap-2 shadow-2xs mb-8">
                        <span>تعرف على نظام الحضور</span>
                        <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180"></i>
                    </button>
                </div>

                <!-- 3D Smartphone Device Mockup with QR Scanner (Matching Reference Image) -->
                <div class="relative max-w-xs mx-auto w-full pt-4">
                    <div class="bg-slate-900 rounded-[2.5rem] p-3 shadow-[0_20px_45px_rgba(15,23,42,0.22)] border-2 border-slate-800">
                        <div class="bg-slate-950 rounded-[2rem] p-4 text-center text-white border border-slate-800">
                            <div class="text-[9px] font-bold text-slate-400 mb-3">امسح رمز QR للحضور</div>
                            
                            <!-- Scanner Frame -->
                            <div class="relative w-36 h-36 mx-auto bg-white/5 rounded-2xl border-2 border-dashed border-emerald-400/80 p-3 flex items-center justify-center mb-3">
                                <div class="bg-white p-2.5 rounded-xl">
                                    <i class="fas fa-qrcode text-4xl text-slate-900"></i>
                                </div>
                            </div>

                            <!-- Success Toast -->
                            <div class="bg-emerald-500/90 text-white rounded-xl p-2 text-[10px] font-bold flex items-center justify-center gap-1.5 shadow-sm">
                                <i class="fas fa-check-circle"></i>
                                <span>تم تسجيل الحضور بنجاح</span>
                            </div>
                            <span class="text-[8px] text-slate-400 font-mono mt-1 block">اليوم 09:15 AM</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Left Card (RTL): إشعارات عبر WhatsApp -->
            <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-sm flex flex-col justify-between relative overflow-hidden" data-animate="fade-in">
                
                <!-- Floating WhatsApp 3D Icon with Red Badge (Top-Left on Card) -->
                <div class="absolute top-6 end-6 w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-2xl shadow-lg border-2 border-white">
                    <i class="fab fa-whatsapp"></i>
                    <span class="absolute -top-1 -end-1 w-5 h-5 bg-rose-500 text-white rounded-full text-[10px] font-black flex items-center justify-center shadow-xs border-2 border-white">
                        1
                    </span>
                </div>

                <div>
                    <!-- Eyebrow -->
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-xs font-bold mb-4" style="color: #2E8B83;">
                        <span>تواصل فوري وفعال</span>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mb-3">
                        إشعارات عبر WhatsApp
                    </h3>

                    <p class="text-xs sm:text-sm text-slate-600 font-medium leading-relaxed mb-6 max-w-md">
                        إشعارات فورية لكل ما يهمك وأولياء الأمور عن الحضور، الدرجات، الرسوم، والفعاليات المهمة.
                    </p>

                    <!-- 4 Mini Feature Pills in 2x2 Grid -->
                    <div class="grid grid-cols-2 gap-2.5 mb-8 max-w-sm">
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs font-bold text-slate-700">
                            <i class="fas fa-comments" style="color: #2E8B83;"></i>
                            <span>متكاملة</span>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs font-bold text-slate-700">
                            <i class="fas fa-lock" style="color: #2E8B83;"></i>
                            <span>آمنة</span>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs font-bold text-slate-700">
                            <i class="fas fa-check-double" style="color: #2E8B83;"></i>
                            <span>موثوقة</span>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs font-bold text-slate-700">
                            <i class="fas fa-bolt" style="color: #2E8B83;"></i>
                            <span>فورية</span>
                        </div>
                    </div>

                    <!-- Button -->
                    <button type="button" @click="$dispatch('open-demo-modal')" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-800 font-bold text-xs hover:border-[#2E8B83] hover:text-[#2E8B83] transition-colors inline-flex items-center gap-2 shadow-2xs mb-8">
                        <span>تعرف على الإشعارات</span>
                        <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180"></i>
                    </button>
                </div>

                <!-- 3D Smartphone Device Mockup with WhatsApp Messages (Matching Reference Image) -->
                <div class="relative max-w-xs mx-auto w-full pt-4">
                    <div class="bg-slate-900 rounded-[2.5rem] p-3 shadow-[0_20px_45px_rgba(15,23,42,0.22)] border-2 border-slate-800">
                        <div class="bg-[#ECE5DD] rounded-[2rem] p-3 min-h-[260px] flex flex-col justify-start border border-slate-300">
                            
                            <!-- WhatsApp Header -->
                            <div class="bg-[#075E54] text-white p-2 rounded-xl mb-2.5 flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-[10px]">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <span class="text-[10px] font-bold">Taalimu</span>
                            </div>

                            <!-- Notification Message Bubble 1 -->
                            <div class="bg-[#DCF8C6] rounded-xl p-2 mb-2 text-slate-800 text-start shadow-2xs">
                                <span class="text-[9px] font-bold text-[#075E54] block mb-0.5">Taalimu ✓</span>
                                <p class="text-[8px] font-bold leading-tight mb-0.5">مرحباً 👋 ولي الأمر: تم تسجيل حضور أحمد اليوم في مادة الرياضيات</p>
                                <span class="text-[7px] text-slate-400 font-mono block text-end">09:15 AM</span>
                            </div>

                            <!-- Notification Message Bubble 2 -->
                            <div class="bg-white rounded-xl p-2 mb-2 text-slate-800 text-start shadow-2xs">
                                <span class="text-[9px] font-bold text-[#075E54] block mb-0.5">Taalimu ✓</span>
                                <p class="text-[8px] font-bold leading-tight mb-0.5">تذكير: موعد اختبار الرياضيات يوم الأحد 14 مايو</p>
                                <span class="text-[7px] text-slate-400 font-mono block text-end">10:00 AM</span>
                            </div>

                            <!-- Notification Message Bubble 3 -->
                            <div class="bg-white rounded-xl p-2 text-slate-800 text-start shadow-2xs">
                                <span class="text-[9px] font-bold text-[#075E54] block mb-0.5">Taalimu ✓</span>
                                <p class="text-[8px] font-bold leading-tight mb-0.5">تم إصدار تقرير درجات الفصل الدراسي الثاني</p>
                                <span class="text-[7px] text-slate-400 font-mono block text-end">03:45 PM</span>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>