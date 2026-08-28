{{-- Footer — Matching Reference Image --}}
<footer class="bg-slate-900 text-white pt-14 pb-6">
    <div class="container mx-auto px-5 lg:px-12 max-w-7xl">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">

            {{-- Column 1: Brand + Description --}}
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 text-decoration-none mb-4">
                    <img src="{{ asset('images/brand/logo-icon.png') }}" alt="Taalimu" class="w-8 h-8 object-contain brightness-0 invert">
                    <span class="text-xl font-black text-white tracking-tight">Taalimu</span>
                </a>
                <p class="text-sm text-slate-400 leading-relaxed mb-5 max-w-sm">
                    منصة متكاملة لإدارة المؤسسات التعليمية. نوفر لك كل ما تحتاجه في منصة واحدة وسهلة.
                </p>
                {{-- Social Links --}}
                <div class="flex items-center gap-3">
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-[#2E8B83] flex items-center justify-center text-slate-400 hover:text-white transition-all text-decoration-none" aria-label="Facebook">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-[#2E8B83] flex items-center justify-center text-slate-400 hover:text-white transition-all text-decoration-none" aria-label="Instagram">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-[#2E8B83] flex items-center justify-center text-slate-400 hover:text-white transition-all text-decoration-none" aria-label="YouTube">
                        <i class="fab fa-youtube text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-[#2E8B83] flex items-center justify-center text-slate-400 hover:text-white transition-all text-decoration-none" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in text-sm"></i>
                    </a>
                </div>
            </div>

            {{-- Column 2: Product --}}
            <div>
                <h4 class="text-sm font-bold text-white mb-4">المنتج</h4>
                <ul class="space-y-2.5">
                    <li><a href="#capabilities" class="text-sm text-slate-400 hover:text-[#2E8B83] transition-colors text-decoration-none">المميزات</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-[#2E8B83] transition-colors text-decoration-none">الأسعار</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-[#2E8B83] transition-colors text-decoration-none">العروض التوضيحية</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-[#2E8B83] transition-colors text-decoration-none">التحديثات</a></li>
                </ul>
            </div>

            {{-- Column 3: Resources --}}
            <div>
                <h4 class="text-sm font-bold text-white mb-4">الموارد</h4>
                <ul class="space-y-2.5">
                    <li><a href="#" class="text-sm text-slate-400 hover:text-[#2E8B83] transition-colors text-decoration-none">المساعدة</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-[#2E8B83] transition-colors text-decoration-none">المدونة</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-[#2E8B83] transition-colors text-decoration-none">الأدلة</a></li>
                    <li><a href="#" class="text-sm text-slate-400 hover:text-[#2E8B83] transition-colors text-decoration-none">الأسئلة الشائعة</a></li>
                </ul>
            </div>

            {{-- Column 4: Newsletter --}}
            <div>
                <h4 class="text-sm font-bold text-white mb-2">اشترك في نشرتنا البريدية</h4>
                <p class="text-xs text-slate-400 mb-4">احصل على آخر التحديثات والأخبار</p>
                <form action="#" method="POST" class="space-y-2.5">
                    @csrf
                    <input type="email"
                           placeholder="بريدك الإلكتروني"
                           required
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#2E8B83] focus:ring-1 focus:ring-[#2E8B83] transition-colors">
                    <button type="submit"
                            class="w-full px-4 py-2.5 rounded-xl text-white text-sm font-bold transition-all hover:opacity-90"
                            style="background-color: #2E8B83;">
                        اشترك الآن
                    </button>
                </form>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="pt-6 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-slate-500">
                &copy; {{ date('Y') }} Taalimu. جميع الحقوق محفوظة.
            </p>
            <div class="flex items-center gap-4">
                <a href="#" class="text-xs text-slate-500 hover:text-slate-300 transition-colors text-decoration-none">سياسة الخصوصية</a>
                <a href="#" class="text-xs text-slate-500 hover:text-slate-300 transition-colors text-decoration-none">الشروط والأحكام</a>
            </div>
        </div>
    </div>
</footer>
