{{-- Professional Footer with Trust & Security Badges --}}
<footer class="footer bg-slate-900 text-slate-400 pt-16 pb-12 border-t border-slate-800">
  <div class="container">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 pb-12 border-b border-slate-800">
      
      {{-- Brand & About --}}
      <div class="lg:col-span-4 text-start">
        <a class="brand flex items-center gap-2.5 font-black text-xl text-white tracking-tight mb-4" href="{{ route('home') }}">
          <span class="w-8 h-8 rounded-xl bg-[#2E8B83] text-white flex items-center justify-center font-black text-base">T</span>
          <span>Taalimu</span>
        </a>
        <p class="text-xs text-slate-400 leading-relaxed max-w-sm mb-6">
          المنصة السحابية المتكاملة لإدارة المراكز والسناتر التعليمية والمدرسين المستقلين: رصد حضور بـ QR، إشعارات WhatsApp، ومتابعة مالية دقيقة بدون أخطاء Excel.
        </p>
        <div class="flex items-center gap-3">
          <a href="https://wa.me/201000000000" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-emerald-600 text-slate-300 hover:text-white flex items-center justify-center text-sm transition-colors" aria-label="WhatsApp">
            <i class="fab fa-whatsapp"></i>
          </a>
          <a href="https://facebook.com" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-blue-600 text-slate-300 hover:text-white flex items-center justify-center text-sm transition-colors" aria-label="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="https://t.me" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-sky-500 text-slate-300 hover:text-white flex items-center justify-center text-sm transition-colors" aria-label="Telegram">
            <i class="fab fa-telegram-plane"></i>
          </a>
        </div>
      </div>

      {{-- Quick Links 1: Platform --}}
      <div class="lg:col-span-2 text-start">
        <h4 class="text-xs font-black text-white uppercase tracking-wider mb-4">المنصة والحلول</h4>
        <ul class="space-y-2.5 text-xs">
          <li><a href="#problem" class="hover:text-teal-400 transition-colors">مقارنة الحلول</a></li>
          <li><a href="#whatsapp" class="hover:text-teal-400 transition-colors">إشعارات الواتساب</a></li>
          <li><a href="#audience" class="hover:text-teal-400 transition-colors">حلول السناتر التعليمية</a></li>
          <li><a href="#audience" class="hover:text-teal-400 transition-colors">حلول المدرس المستقل</a></li>
          <li><a href="#features" class="hover:text-teal-400 transition-colors">الميزات التشغيلية</a></li>
        </ul>
      </div>

      {{-- Quick Links 2: Pricing & Support --}}
      <div class="lg:col-span-2 text-start">
        <h4 class="text-xs font-black text-white uppercase tracking-wider mb-4">الخطط والدعم</h4>
        <ul class="space-y-2.5 text-xs">
          <li><a href="#pricing" class="hover:text-teal-400 transition-colors">باقات الأسعار</a></li>
          <li><a href="#roi-calculator" class="hover:text-teal-400 transition-colors">حاسبة العائد والوقت</a></li>
          <li><a href="#faq" class="hover:text-teal-400 transition-colors">الأسئلة الشائعة</a></li>
          <li><a href="{{ route('login.portal') }}" class="hover:text-teal-400 transition-colors">بوابة الدخول</a></li>
          <li><a href="{{ route('register') }}" class="hover:text-teal-400 transition-colors">تسجيل حساب جديد</a></li>
        </ul>
      </div>

      {{-- Security & Guarantee --}}
      <div class="lg:col-span-4 text-start bg-slate-800/60 p-5 rounded-2xl border border-slate-700/60">
        <h4 class="text-xs font-black text-white uppercase tracking-wider mb-2 flex items-center gap-2">
          <i class="fas fa-shield-alt text-teal-400"></i>
          <span>حماية وسرية البيانات</span>
        </h4>
        <p class="text-[11px] text-slate-400 leading-relaxed mb-4">
          نضمن لك حماية بياناتك وفق معايير الأمان العالمية، مع نسخ احتياطي يومي مشفر واستضافة سحابية عالية الاستقرار بنسبة 99.9% Uptime.
        </p>
        <div class="flex flex-wrap gap-2 text-[10px] text-slate-300 font-bold">
          <span class="bg-slate-700/80 px-2.5 py-1 rounded-md border border-slate-600">✓ SSL 256-Bit</span>
          <span class="bg-slate-700/80 px-2.5 py-1 rounded-md border border-slate-600">✓ Meta Verified API</span>
          <span class="bg-slate-700/80 px-2.5 py-1 rounded-md border border-slate-600">✓ Isolated Multi-Tenant</span>
        </div>
      </div>

    </div>

    {{-- Bottom Bar --}}
    <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 font-medium">
      <p class="m-0">© {{ date('Y') }} منصة Taalimu. جميع الحقوق محفوظة.</p>
      <div class="flex items-center gap-4 text-xs">
        <span>صُنعت بعناية لخدمة التعليم العربي ❤️</span>
      </div>
    </div>
  </div>
</footer>
