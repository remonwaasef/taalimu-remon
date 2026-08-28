{{-- Footer — Premium Design Matching Reference Image --}}
<footer class="bg-white border-t border-slate-200 text-slate-700 pt-14 pb-8">
    <div class="container mx-auto px-5 lg:px-12 max-w-7xl">

        {{-- Newsletter Subscription Section --}}
        <div class="bg-[#f4faf9] rounded-2xl p-6 sm:p-8 border border-[#c5e8e4] mb-12 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-1 text-center md:text-start">
                <h3 class="text-lg font-black text-slate-900">اشترك في نشرتنا البريدية</h3>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">احصل على آخر التحديثات والنصائح الخاصة بإدارة المؤسسات التعليمية</p>
            </div>
            <form action="#" method="POST" class="w-full md:w-auto flex flex-col sm:flex-row gap-2.5">
                @csrf
                <input type="email"
                       placeholder="أدخل بريدك الإلكتروني"
                       required
                       class="px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#2E8B83] focus:ring-2 focus:ring-[#2E8B83]/20 min-w-[260px]">
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl text-white text-sm font-extrabold transition-all hover:opacity-95 shadow-xs whitespace-nowrap"
                        style="background-color: #2E8B83;">
                    اشترك الآن
                </button>
            </form>
        </div>

        {{-- Footer Main Columns Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">

            {{-- Column 1: Brand Info & Social Icons --}}
            <div class="lg:col-span-2 space-y-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 text-decoration-none">
                    <div class="w-8 h-8 rounded-xl bg-[#e8f5f3] flex items-center justify-center">
                        <img src="{{ asset('images/brand/logo-icon.png') }}" alt="Taalimu" class="w-5 h-5 object-contain">
                    </div>
                    <span class="text-2xl font-black text-slate-900 tracking-tight font-sans">Taalimu</span>
                </a>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed max-w-sm font-medium">
                    منصة متكاملة لإدارة المؤسسات التعليمية. تجمع كل ما تحتاجه في منصة واحدة ذكية واستثنائية.
                </p>
                {{-- Social Icons --}}
                <div class="flex items-center gap-2.5 pt-1">
                    <a href="#" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#2E8B83] text-slate-600 hover:text-white flex items-center justify-center transition-colors text-decoration-none" aria-label="Facebook">
                        <i class="fab fa-facebook-f text-xs"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#2E8B83] text-slate-600 hover:text-white flex items-center justify-center transition-colors text-decoration-none" aria-label="Instagram">
                        <i class="fab fa-instagram text-xs"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#2E8B83] text-slate-600 hover:text-white flex items-center justify-center transition-colors text-decoration-none" aria-label="YouTube">
                        <i class="fab fa-youtube text-xs"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#2E8B83] text-slate-600 hover:text-white flex items-center justify-center transition-colors text-decoration-none" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in text-xs"></i>
                    </a>
                </div>
            </div>

            {{-- Column 2: Компания / الشركة --}}
            <div>
                <h4 class="text-sm font-extrabold text-slate-900 mb-4">الشركة</h4>
                <ul class="space-y-2.5 text-xs sm:text-sm font-medium">
                    <li><a href="#portals" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">من نحن</a></li>
                    <li><a href="#" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">وظائف</a></li>
                    <li><a href="#" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">تواصل معنا</a></li>
                </ul>
            </div>

            {{-- Column 3: Resources / الموارد --}}
            <div>
                <h4 class="text-sm font-extrabold text-slate-900 mb-4">الموارد</h4>
                <ul class="space-y-2.5 text-xs sm:text-sm font-medium">
                    <li><a href="#" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">المدونة</a></li>
                    <li><a href="#" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">الأدلة</a></li>
                    <li><a href="#" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">الأسئلة الشائعة</a></li>
                </ul>
            </div>

            {{-- Column 4: Product / المنتج --}}
            <div>
                <h4 class="text-sm font-extrabold text-slate-900 mb-4">المنتج</h4>
                <ul class="space-y-2.5 text-xs sm:text-sm font-medium">
                    <li><a href="#capabilities" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">المميزات</a></li>
                    <li><a href="#capabilities" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">الأسعار</a></li>
                    <li><a href="#" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">التحديثات</a></li>
                </ul>
            </div>

        </div>

        {{-- Bottom Legal Bar --}}
        <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500">
            <p>
                &copy; {{ date('Y') }} Taalimu. جميع الحقوق محفوظة.
            </p>
            <div class="flex items-center gap-6">
                <a href="{{ route('privacy') }}" class="text-slate-500 hover:text-[#2E8B83] transition-colors text-decoration-none">سياسة الخصوصية</a>
                <a href="{{ route('terms') }}" class="text-slate-500 hover:text-[#2E8B83] transition-colors text-decoration-none">الشروط والأحكام</a>
            </div>
        </div>

    </div>
</footer>
