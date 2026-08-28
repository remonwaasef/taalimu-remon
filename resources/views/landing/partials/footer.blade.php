{{-- Minimal Clean Light Footer Matching Reference Image --}}
<footer class="bg-white text-slate-600 pt-16 pb-8 border-t border-slate-100">
    <div class="container mx-auto px-6 lg:px-12 max-w-7xl">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10 pb-12 border-b border-slate-100">

            <!-- Right Column in RTL (Span 4): اشترك في نشرتنا البريدية -->
            <div class="lg:col-span-4 order-1">
                <h4 class="text-xs font-black text-slate-900 mb-2">
                    اشترك في نشرتنا البريدية
                </h4>
                <p class="text-xs text-slate-500 mb-4 font-medium leading-relaxed">
                    احصل على آخر التحديثات والنصائح الخاصة بإدارة المؤسسات التعليمية
                </p>
                <form class="space-y-2" onsubmit="event.preventDefault(); alert('شكرًا لاشتراكك في نشرة Taalimu!');">
                    <input type="email" placeholder="أدخل بريدك الإلكتروني" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#2E8B83]/30 bg-[#FAFBFB] text-center">
                    <button type="submit" class="w-full py-2.5 rounded-xl text-white font-extrabold text-xs transition-colors shadow-2xs" style="background-color: #2E8B83; color: #ffffff;">
                        اشتراك الآن
                    </button>
                </form>
            </div>

            <!-- Middle Columns (Span 5): الشركة / الموارد / المنتج -->
            <div class="lg:col-span-5 grid grid-cols-3 gap-4 order-2 text-center lg:text-start">
                
                <!-- الشركة -->
                <div>
                    <h4 class="text-xs font-black text-slate-900 mb-3">
                        الشركة
                    </h4>
                    <ul class="space-y-2.5 text-xs font-semibold list-none p-0 m-0">
                        <li><a href="#portals" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">من نحن</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">وظائف</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">تواصل معنا</a></li>
                    </ul>
                </div>

                <!-- الموارد -->
                <div>
                    <h4 class="text-xs font-black text-slate-900 mb-3">
                        الموارد
                    </h4>
                    <ul class="space-y-2.5 text-xs font-semibold list-none p-0 m-0">
                        <li><a href="#" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">المدونة</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">الأدلة</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">الأسئلة الشائعة</a></li>
                    </ul>
                </div>

                <!-- المنتج -->
                <div>
                    <h4 class="text-xs font-black text-slate-900 mb-3">
                        المنتج
                    </h4>
                    <ul class="space-y-2.5 text-xs font-semibold list-none p-0 m-0">
                        <li><a href="#capabilities" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">المميزات</a></li>
                        <li><a href="#capabilities" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">الأسعار</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">التحديثات</a></li>
                    </ul>
                </div>

            </div>

            <!-- Left Column in RTL (Span 3): Brand & Description -->
            <div class="lg:col-span-3 order-3 text-center lg:text-start">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-3 text-decoration-none">
                    <img src="{{ asset('images/brand/logo-icon.png') }}" alt="Taalimu" class="w-7 h-7 object-contain">
                    <span class="text-xl font-black text-slate-900 tracking-tight font-sans">Taalimu</span>
                </a>
                <p class="text-xs text-slate-500 font-medium leading-relaxed mb-4">
                    منصة متكاملة لإدارة المؤسسات التعليمية تجمع كل ما تحتاجه في منصة ذكية وآمنة
                </p>
                <!-- Social Icons -->
                <div class="flex items-center justify-center lg:justify-start gap-2.5 text-slate-400 text-xs">
                    <a href="#" class="w-7 h-7 rounded-lg bg-slate-50 hover:bg-emerald-50 hover:text-[#2E8B83] flex items-center justify-center transition-colors text-decoration-none"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-7 h-7 rounded-lg bg-slate-50 hover:bg-emerald-50 hover:text-[#2E8B83] flex items-center justify-center transition-colors text-decoration-none"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="w-7 h-7 rounded-lg bg-slate-50 hover:bg-emerald-50 hover:text-[#2E8B83] flex items-center justify-center transition-colors text-decoration-none"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="w-7 h-7 rounded-lg bg-slate-50 hover:bg-emerald-50 hover:text-[#2E8B83] flex items-center justify-center transition-colors text-decoration-none"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="w-7 h-7 rounded-lg bg-slate-50 hover:bg-emerald-50 hover:text-[#2E8B83] flex items-center justify-center transition-colors text-decoration-none"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

        </div>

        <!-- Bottom Copyright & Legal -->
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] font-medium text-slate-400">
            <p>Taalimu {{ date('Y') }} © جميع الحقوق محفوظة</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('terms') }}" class="text-slate-400 hover:text-slate-600 transition-colors text-decoration-none">الشروط والأحكام</a>
                <a href="{{ route('privacy') }}" class="text-slate-400 hover:text-slate-600 transition-colors text-decoration-none">سياسة الخصوصية</a>
            </div>
        </div>

    </div>
</footer>
