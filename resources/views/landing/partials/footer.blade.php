{{-- Minimal Elegant Light Footer --}}
<footer class="bg-white text-slate-600 py-16 border-t border-slate-200/80">
    <div class="container mx-auto px-6 lg:px-12 max-w-7xl">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-12 pb-12 border-b border-slate-100">

            <!-- Brand Column (Span 4) -->
            <div class="lg:col-span-4">
                <a href="{{ route('home') }}" class="inline-block mb-4 text-decoration-none">
                    <img src="{{ asset('images/brand/logo-full.png') }}" alt="Taalimu" class="h-8 w-auto">
                </a>
                <p class="text-sm text-slate-500 font-medium leading-relaxed max-w-sm mb-6">
                    منصة متكاملة لإدارة المؤسسات التعليمية والمدرسين. كل ما تحتاجه لتنظيم طلابك وفصولك ومدفوعاتك في مكان واحد.
                </p>

                <!-- Social Icons -->
                <div class="flex items-center gap-3 text-slate-400">
                    <a href="#" class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-[#2E8B83] flex items-center justify-center text-xs transition-colors text-decoration-none">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-[#2E8B83] flex items-center justify-center text-xs transition-colors text-decoration-none">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-[#2E8B83] flex items-center justify-center text-xs transition-colors text-decoration-none">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-[#2E8B83] flex items-center justify-center text-xs transition-colors text-decoration-none">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Column 1: المنتج -->
            <div class="lg:col-span-2">
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4">
                    المنتج
                </h4>
                <ul class="space-y-3 text-xs font-semibold list-none p-0 m-0">
                    <li><a href="#hero" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">نظرة عامة</a></li>
                    <li><a href="#attendance" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">حضور QR Code</a></li>
                    <li><a href="#whatsapp" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">إشعارات WhatsApp</a></li>
                    <li><a href="#capabilities" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">المميزات والقدرات</a></li>
                </ul>
            </div>

            <!-- Column 2: الموارد -->
            <div class="lg:col-span-2">
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4">
                    الموارد
                </h4>
                <ul class="space-y-3 text-xs font-semibold list-none p-0 m-0">
                    <li><a href="#hero" @click.prevent="$dispatch('open-demo-modal')" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">العرض التوضيحي</a></li>
                    <li><a href="{{ route('login.portal') }}" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">مركز المساعدة</a></li>
                    <li><a href="{{ route('register') }}" class="text-slate-600 hover:text-[#2E8B83] transition-colors text-decoration-none">دليل البدء السريع</a></li>
                </ul>
            </div>

            <!-- Column 3: الشركة & النشرة البريدية (Span 4) -->
            <div class="lg:col-span-4">
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4">
                    النشرة البريدية
                </h4>
                <p class="text-xs text-slate-500 mb-3 font-medium">
                    اشترك لتصلك أحدث التحديثات ومقالات إدارة المؤسسات التعليمية.
                </p>
                <form class="flex items-center gap-2 max-w-sm" onsubmit="event.preventDefault(); alert('شكرًا لاشتراكك!');">
                    <input type="email" placeholder="بريدك الإلكتروني" required class="flex-grow px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#2E8B83]/30 bg-[#FAFBFB]">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#2E8B83] hover:bg-[#25746D] text-white font-bold text-xs shrink-0 transition-colors">
                        اشتراك
                    </button>
                </form>
            </div>

        </div>

        <!-- Copyright & Legal Bottom Bar -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-medium text-slate-400">
            <p>جميع الحقوق محفوظة لمنصة Taalimu © {{ date('Y') }}</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('privacy') }}" class="text-slate-500 hover:text-slate-700 transition-colors text-decoration-none">سياسة الخصوصية</a>
                <a href="{{ route('terms') }}" class="text-slate-500 hover:text-slate-700 transition-colors text-decoration-none">شروط الاستخدام</a>
            </div>
        </div>

    </div>
</footer>
