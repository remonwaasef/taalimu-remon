<footer class="bg-slate-900 text-slate-400 py-16 border-t border-slate-800">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800">
            
            <!-- Brand Column -->
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="inline-block mb-4 text-decoration-none">
                    <img src="{{ asset('images/brand/logo-full.png') }}" alt="Taalimu" class="h-8 w-auto brightness-200">
                </a>
                <p class="text-xs sm:text-sm text-slate-400 font-medium leading-relaxed max-w-sm mb-6">
                    {{ __('landing.footer.description') }}
                </p>
                <div class="flex items-center gap-3">
                    <a href="#" class="w-9 h-9 rounded-full bg-slate-800 text-slate-400 hover:text-white hover:bg-emerald-600 transition-colors flex items-center justify-center text-sm">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-slate-800 text-slate-400 hover:text-white hover:bg-emerald-600 transition-colors flex items-center justify-center text-sm">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-slate-800 text-slate-400 hover:text-white hover:bg-emerald-600 transition-colors flex items-center justify-center text-sm">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <!-- Solutions Links -->
            <div>
                <h4 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">
                    {{ __('landing.footer.col_solutions') }}
                </h4>
                <ul class="space-y-2.5 text-xs font-medium">
                    <li><a href="#qr-registration" class="hover:text-emerald-400 transition-colors text-decoration-none">تسجيل QR Code</a></li>
                    <li><a href="#whatsapp-notifications" class="hover:text-emerald-400 transition-colors text-decoration-none">إشعارات Meta WhatsApp</a></li>
                    <li><a href="#solutions" class="hover:text-emerald-400 transition-colors text-decoration-none">لوحة المراكز والأكاديميات</a></li>
                    <li><a href="#solutions" class="hover:text-emerald-400 transition-colors text-decoration-none">بوابة المدرسين</a></li>
                    <li><a href="#solutions" class="hover:text-emerald-400 transition-colors text-decoration-none">بوابة ولي الأمر والطلاب</a></li>
                </ul>
            </div>

            <!-- Features Links -->
            <div>
                <h4 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">
                    {{ __('landing.footer.col_features') }}
                </h4>
                <ul class="space-y-2.5 text-xs font-medium">
                    <li><a href="#features" class="hover:text-emerald-400 transition-colors text-decoration-none">الحضور والغياب الذكي</a></li>
                    <li><a href="#features" class="hover:text-emerald-400 transition-colors text-decoration-none">المالية وفواتير (POS)</a></li>
                    <li><a href="#features" class="hover:text-emerald-400 transition-colors text-decoration-none">استيراد كشوفات Excel</a></li>
                    <li><a href="#features" class="hover:text-emerald-400 transition-colors text-decoration-none">الاختبارات وبنك الأسئلة</a></li>
                    <li><a href="#pricing" class="hover:text-emerald-400 transition-colors text-decoration-none">الباقات والأسعار</a></li>
                </ul>
            </div>

            <!-- Quick Links & Auth -->
            <div>
                <h4 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">
                    {{ __('landing.footer.col_company') }}
                </h4>
                <ul class="space-y-2.5 text-xs font-medium">
                    <li><a href="#how-it-works" class="hover:text-emerald-400 transition-colors text-decoration-none">كيف تبدأ مع المنصة</a></li>
                    <li><a href="#faq" class="hover:text-emerald-400 transition-colors text-decoration-none">الأسئلة الشائعة</a></li>
                    <li><a href="{{ route('login.portal') }}" class="hover:text-emerald-400 transition-colors text-decoration-none">تسجيل الدخول للمركز</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-emerald-400 transition-colors text-decoration-none">إنشاء حساب جديد</a></li>
                </ul>
            </div>

        </div>

        <!-- Copyright & Legal Bottom Bar -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-medium text-slate-500">
            <p>{{ __('landing.footer.copyright') }}</p>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-slate-400 transition-colors text-decoration-none">{{ __('landing.footer.privacy') }}</a>
                <a href="#" class="hover:text-slate-400 transition-colors text-decoration-none">{{ __('landing.footer.terms') }}</a>
            </div>
        </div>

    </div>
</footer>