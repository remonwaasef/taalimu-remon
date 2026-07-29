{{-- Hero Section — World-Class SaaS Redesign (Stripe / Linear / Framer Level) --}}
<section class="hero-section relative min-h-[90vh] flex items-center pt-28 pb-20 lg:pt-36 lg:pb-28 overflow-hidden bg-slate-950 text-slate-100" dir="rtl" style="background: radial-gradient(circle at 50% 0%, #0f172a 0%, #020617 100%);">
    
    {{-- Subtle Ambient Grid & Glow Effects --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        {{-- Subtle Grid Overlay --}}
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(#ffffff 1px, transparent 1px), linear-gradient(90deg, #ffffff 1px, transparent 1px); background-size: 40px 40px;"></div>
        
        {{-- Glow Blobs --}}
        <div class="absolute -top-40 right-1/4 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[140px]"></div>
        <div class="absolute top-1/3 -left-20 w-[450px] h-[450px] bg-teal-500/10 rounded-full blur-[130px]"></div>
        <div class="absolute bottom-0 right-1/3 w-[600px] h-[300px] bg-indigo-500/5 rounded-full blur-[150px]"></div>
    </div>

    <div class="container relative mx-auto px-4 sm:px-6 lg:px-12 max-w-7xl z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            
            {{-- RIGHT COLUMN: Content (RTL Start) --}}
            <div class="lg:col-span-6 text-right space-y-8">
                
                {{-- 1. Badge --}}
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-emerald-500/10 border border-emerald-500/25 backdrop-blur-md shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-bold text-emerald-400 tracking-wide uppercase" style="color: #34d399 !important;">
                        منصة SaaS رقم 1 لإدارة المراكز التعليمية
                    </span>
                    <i class="fas fa-sparkles text-amber-400 text-xs me-1"></i>
                </div>

                {{-- 2. Headline --}}
                <h1 class="text-3xl sm:text-4xl lg:text-5xl xl:text-[3.25rem] font-black text-white leading-[1.15] tracking-tight" style="color: #ffffff !important;">
                    أدر مركزك التعليمي بالكامل
                    <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-emerald-500" style="color: #34d399 !important;">
                        من منصة واحدة ذكية
                    </span>
                </h1>

                {{-- 3. Supporting Paragraph --}}
                <p class="text-base sm:text-lg text-slate-300 leading-relaxed max-w-xl font-normal" style="color: #cbd5e1 !important;">
                    نظام متكامل يجمع إدارة الطلاب، الحضور الذكي بـ <strong class="text-white font-semibold">QR Code</strong>، الفواتير والاشتراكات، والتقارير المالية، مع أتمتة كاملة لإشعارات <strong class="text-emerald-400 font-semibold">الواتساب</strong> لأولياء الأمور.
                </p>

                {{-- 4. CTAs --}}
                <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center pt-2">
                    <a href="{{ route('register') }}" 
                       class="group relative inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl font-bold text-white text-base transition-all duration-300 shadow-lg shadow-emerald-900/30 hover:shadow-emerald-600/40 hover:-translate-y-0.5 active:translate-y-0 overflow-hidden"
                       style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                        <span>ابدأ التجربة المجانية</span>
                        <i class="fas fa-arrow-left text-sm transition-transform duration-300 group-hover:-translate-x-1"></i>
                    </a>

                    <a href="#features" 
                       class="inline-flex items-center justify-center gap-2.5 px-7 py-4 rounded-xl font-semibold text-slate-200 text-base border border-slate-700/80 bg-slate-900/50 hover:bg-slate-800/80 hover:border-slate-600 backdrop-blur-md transition-all duration-300 hover:-translate-y-0.5"
                       style="color: #f1f5f9 !important;">
                        <i class="fas fa-play-circle text-emerald-400 text-lg"></i>
                        <span>شاهد عرضاً لمدة دقيقتين</span>
                    </a>
                </div>

                {{-- 5. Quick Benefits Checklist --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 text-xs font-medium text-slate-300 border-t border-slate-800/80">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-400"></i>
                        <span>إعداد خلال دقيقتين</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-400"></i>
                        <span>بدون بطاقة بنكية</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-400"></i>
                        <span>دعم عربي دائم</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-400"></i>
                        <span>تجربة مجانية 14 يوم</span>
                    </div>
                </div>

                {{-- 6. Trust Metrics --}}
                <div class="flex items-center gap-8 pt-4">
                    <div class="border-r-2 border-emerald-500/40 pr-4 first:pr-0 first:border-0">
                        <div class="text-xl sm:text-2xl font-black text-white tracking-tight">+5,000</div>
                        <div class="text-xs text-slate-400">طالب ومُتدرّب</div>
                    </div>
                    <div class="border-r-2 border-emerald-500/40 pr-4">
                        <div class="text-xl sm:text-2xl font-black text-white tracking-tight">+120</div>
                        <div class="text-xs text-slate-400">مركز تعليمي</div>
                    </div>
                    <div class="border-r-2 border-emerald-500/40 pr-4">
                        <div class="text-xl sm:text-2xl font-black text-emerald-400 tracking-tight" style="color: #34d399 !important;">99.9%</div>
                        <div class="text-xs text-slate-400">استقرار وتشغيل</div>
                    </div>
                </div>

            </div>

            {{-- LEFT COLUMN: SaaS Visual Composition --}}
            <div class="lg:col-span-6 relative mt-6 lg:mt-0">
                <div class="relative mx-auto max-w-[560px] lg:max-w-none">
                    
                    {{-- 1. Main SaaS Browser Mockup --}}
                    <div class="relative z-10 rounded-2xl overflow-hidden border border-slate-700/60 bg-slate-900 shadow-2xl shadow-slate-950/80 group transition-all duration-500 hover:border-emerald-500/40">
                        {{-- Browser Header --}}
                        <div class="flex items-center justify-between px-4 py-3 bg-slate-900/90 border-b border-slate-800">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-rose-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500/80"></div>
                            </div>
                            <div class="px-3 py-0.5 rounded-md bg-slate-950 border border-slate-800 text-[11px] font-mono text-slate-400 flex items-center gap-1.5">
                                <i class="fas fa-lock text-[9px] text-emerald-400"></i>
                                <span>app.taalimu.com/dashboard</span>
                            </div>
                            <div class="w-12"></div>
                        </div>

                        {{-- Main Dashboard Image --}}
                        <div class="relative bg-slate-900 overflow-hidden">
                            <img src="{{ asset('images/hero-dashboard.webp') }}" 
                                 alt="Taalimu SaaS Dashboard" 
                                 class="w-full h-auto object-cover object-top opacity-95 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 via-transparent to-transparent pointer-events-none"></div>
                        </div>
                    </div>

                    {{-- 2. Mini Floating SaaS Cards (Non-overlapping, Elegant) --}}

                    {{-- Floating Card A: WhatsApp Automation (Top Left / Visual) --}}
                    <div class="absolute -top-6 -left-4 sm:-left-8 z-20 w-60 rounded-xl p-3.5 bg-slate-900/90 border border-emerald-500/30 backdrop-blur-xl shadow-xl shadow-slate-950/60 hidden sm:block animate-float"
                         style="animation: float 6s ease-in-out infinite;">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center shrink-0">
                                <i class="fab fa-whatsapp text-emerald-400 text-lg"></i>
                            </div>
                            <div class="overflow-hidden">
                                <div class="text-[11px] font-bold text-white truncate">إشعار أولياء الأمور</div>
                                <div class="text-[10px] text-emerald-400 truncate">تم إرسال حضور الطالب تلقائياً</div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating Card B: QR Code Attendance (Top Right / Visual) --}}
                    <div class="absolute -top-8 -right-4 sm:-right-6 z-20 w-52 rounded-xl p-3 bg-slate-900/90 border border-teal-500/30 backdrop-blur-xl shadow-xl shadow-slate-950/60 hidden sm:block"
                         style="animation: float 7s ease-in-out 1s infinite;">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-teal-500/20 border border-teal-500/40 flex items-center justify-center shrink-0">
                                <i class="fas fa-qrcode text-teal-300 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-[11px] font-bold text-white">حضور بـ QR</div>
                                <div class="text-[10px] text-slate-300">أحمد علي - 09:00 ص</div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating Card C: Invoice Paid Notification (Bottom Left) --}}
                    <div class="absolute -bottom-6 -left-6 z-20 w-56 rounded-xl p-3.5 bg-slate-900/90 border border-slate-700/80 backdrop-blur-xl shadow-2xl shadow-slate-950/80 hidden sm:block"
                         style="animation: float 5.5s ease-in-out 0.5s infinite;">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center shrink-0">
                                <i class="fas fa-check-circle text-emerald-400 text-base"></i>
                            </div>
                            <div>
                                <div class="text-[11px] font-bold text-white">تحصيل فاتورة جديد</div>
                                <div class="text-[10px] text-emerald-400 font-semibold">+ 1,500 ج.م كاش</div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating Card D: Analytics Stat (Bottom Right) --}}
                    <div class="absolute -bottom-8 -right-4 z-20 w-48 rounded-xl p-3 bg-slate-900/90 border border-slate-700/80 backdrop-blur-xl shadow-2xl shadow-slate-950/80 hidden sm:block"
                         style="animation: float 6.5s ease-in-out 1.5s infinite;">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-medium text-slate-400">نسبة الحضور اليوم</span>
                            <span class="text-xs font-bold text-emerald-400">96.4%</span>
                        </div>
                        <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: 96.4%;"></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

{{-- Keyframe animations for subtle float --}}
<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
}
.animate-float {
    animation: float 6s ease-in-out infinite;
}
</style>