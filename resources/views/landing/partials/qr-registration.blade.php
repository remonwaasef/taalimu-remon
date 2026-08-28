{{-- Section 4: Smart Attendance with QR Code --}}
<section id="attendance" class="py-24 lg:py-36 bg-[#FAFBFB] relative overflow-hidden border-y border-slate-100">
    <div class="container mx-auto px-6 lg:px-12 max-w-7xl">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Side 1: Minimal Explanation & Key Highlights -->
            <div class="lg:col-span-6 text-center lg:text-start" data-animate="fade-in">
                
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/60 text-[#2E8B83] text-xs font-bold mb-6 shadow-2xs">
                    <i class="fas fa-qrcode text-xs"></i>
                    <span>حضور فوري بلا أوراق</span>
                </div>

                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 leading-tight mb-6">
                    نظام حضور ذكي وسريع<br>
                    باستخدام QR Code
                </h2>

                <p class="text-base sm:text-lg text-slate-600 font-medium leading-relaxed mb-10 max-w-xl mx-auto lg:mx-0">
                    ودّع كشوف الغياب الورقية والنداء التقليدي. مسح واحد بالهاتف يسجّل حضور الطالب، ويخصم الحصة، ويخطر ولي الأمر لحظيًا.
                </p>

                <!-- 4 Core Features Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-start mb-8">
                    
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-[#2E8B83] flex items-center justify-center text-base font-bold shrink-0 border border-emerald-100">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-900 mb-1">تسجيل سريع</h4>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed">رصد حضور الفصل كاملاً في ثوانٍ معدودة دون تعطيل الحصة.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-2xl bg-teal-50 text-[#2E8B83] flex items-center justify-center text-base font-bold shrink-0 border border-teal-100">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-900 mb-1">دقة عالية</h4>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed">كود QR فريد ومؤمن لكل طالب يمنع الخلط والخطأ البشري.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-base font-bold shrink-0 border border-sky-100">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-900 mb-1">تقارير فورية</h4>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed">تحديث لحظي لنسب الحضور في لوحات المدرسين والإدارة.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-base font-bold shrink-0 border border-amber-100">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-900 mb-1">منع التلاعب</h4>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed">مصادقة مشفرة تمنع تكرار مسح الكود أو تسجيل الحضور عن بُعد.</p>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Side 2: Realistic 3D Physical Smartphone with QR Scanner UI -->
            <div class="lg:col-span-6 flex justify-center" data-animate="scale-in">
                <div class="relative w-full max-w-sm">
                    
                    <!-- Ambient Device Glow -->
                    <div class="absolute inset-0 bg-emerald-500/10 rounded-full blur-3xl transform -rotate-3 scale-90"></div>

                    <!-- Smartphone Body Frame -->
                    <div class="relative bg-slate-900 rounded-[2.75rem] p-3.5 shadow-[0_30px_70px_-15px_rgba(15,23,42,0.25)] border-[3px] border-slate-800 transform rotate-1 hover:rotate-0 transition-transform duration-500">
                        
                        <!-- Dynamic Island / Speaker Notch -->
                        <div class="absolute top-6 start-1/2 -translate-x-1/2 w-24 h-4 bg-slate-950 rounded-full z-30 flex items-center justify-end px-2">
                            <span class="w-2 h-2 rounded-full bg-slate-800"></span>
                        </div>

                        <!-- Inner Screen Display -->
                        <div class="relative bg-slate-950 rounded-[2.25rem] overflow-hidden p-6 text-white text-center min-h-[480px] flex flex-col justify-between border border-slate-800">
                            
                            <!-- Scanner Top Header -->
                            <div class="pt-4 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400">ماسح الحضور الذكي</span>
                                <span class="text-[10px] font-bold text-emerald-400 bg-emerald-950/80 border border-emerald-500/30 px-2 py-0.5 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    الكاميرا نشطة
                                </span>
                            </div>

                            <!-- QR Viewfinder Target Area -->
                            <div class="my-auto py-6">
                                <div class="relative w-48 h-48 mx-auto bg-white/5 rounded-3xl border-2 border-dashed border-emerald-400/80 p-4 flex items-center justify-center shadow-[0_0_30px_rgba(46,139,131,0.2)]">
                                    <!-- Corner Bracket Accents -->
                                    <span class="absolute top-2 start-2 w-4 h-4 border-t-2 border-s-2 border-emerald-400 rounded-tl-lg"></span>
                                    <span class="absolute top-2 end-2 w-4 h-4 border-t-2 border-e-2 border-emerald-400 rounded-tr-lg"></span>
                                    <span class="absolute bottom-2 start-2 w-4 h-4 border-b-2 border-s-2 border-emerald-400 rounded-bl-lg"></span>
                                    <span class="absolute bottom-2 end-2 w-4 h-4 border-b-2 border-e-2 border-emerald-400 rounded-br-lg"></span>

                                    <!-- QR Code Graphic -->
                                    <div class="bg-white p-3 rounded-2xl shadow-inner relative">
                                        <i class="fas fa-qrcode text-6xl text-slate-900"></i>
                                        <!-- Glowing Scanner Sweep Line -->
                                        <div class="absolute inset-x-0 h-0.5 bg-emerald-500 shadow-[0_0_12px_#10b981] animate-pulse top-1/2"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Successful Confirmation Toast Badge -->
                            <div class="bg-emerald-500/90 backdrop-blur-md text-white rounded-2xl p-3.5 shadow-xl border border-emerald-400/40 text-start flex items-center gap-3 transform -translate-y-2">
                                <div class="w-9 h-9 rounded-xl bg-white text-emerald-600 flex items-center justify-center text-lg font-black shrink-0 shadow-xs">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="leading-tight">
                                    <h5 class="text-xs font-black text-white mb-0.5">تم تسجيل الحضور بنجاح</h5>
                                    <span class="text-[10px] text-emerald-100">الطالب: عمر خالد • مجموعة A1</span>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
</section>