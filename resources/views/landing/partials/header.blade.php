{{-- Professional Header with Glassmorphism & Fast Navigation --}}
<header class="site-header sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200/80" id="top">
  <div class="container nav flex items-center justify-between min-h-[72px]">
    
    {{-- Brand Logo --}}
    <a class="brand flex items-center gap-2.5 font-black text-xl text-slate-900 tracking-tight" href="{{ route('home') }}" aria-label="Taalimu">
      <span class="w-8 h-8 rounded-xl bg-[#2E8B83] text-white flex items-center justify-center font-black text-base shadow-sm">T</span>
      <span>Taalimu</span>
    </a>

    {{-- Desktop Navigation Links --}}
    <nav class="desktop-nav hidden lg:flex items-center gap-8 text-xs font-bold text-slate-600" aria-label="التنقل الرئيسي">
      <a href="#problem" class="hover:text-[#2E8B83] transition-colors">الحلول والمشاكل</a>
      <a href="#whatsapp" class="hover:text-[#2E8B83] transition-colors">إشعارات WhatsApp</a>
      <a href="#audience" class="hover:text-[#2E8B83] transition-colors">لمن المنصة</a>
      <a href="#features" class="hover:text-[#2E8B83] transition-colors">المميزات</a>
      <a href="#roi-calculator" class="hover:text-[#2E8B83] transition-colors">حاسبة التوفير</a>
      <a href="#pricing" class="hover:text-[#2E8B83] transition-colors">الأسعار</a>
      <a href="#faq" class="hover:text-[#2E8B83] transition-colors">الأسئلة الشائعة</a>
    </nav>

    {{-- CTA & Portal Actions --}}
    <div class="nav-actions flex items-center gap-3">
      <a class="btn btn-ghost font-bold text-xs hidden sm:inline-flex" href="{{ route('login.portal') }}">
        <i class="fas fa-sign-in-alt text-slate-500 ms-1"></i> تسجيل الدخول
      </a>
      <a class="btn btn-primary font-bold text-xs shadow-md shadow-teal-700/20" href="{{ route('register') }}">
        <span>ابدأ مجانًا</span>
        <i class="fas fa-arrow-left text-[10px]"></i>
      </a>

      {{-- Mobile Menu Trigger --}}
      <button class="menu-btn lg:hidden p-2 text-slate-700 text-xl font-bold" id="menuBtn" aria-label="فتح القائمة" aria-expanded="false">
        <i class="fas fa-bars"></i>
      </button>
    </div>
  </div>

  {{-- Mobile Slide-Down Menu --}}
  <div class="mobile-menu hidden bg-white border-b border-slate-200 px-5 py-4 space-y-2 lg:hidden text-xs font-bold text-slate-700 shadow-xl" id="mobileMenu">
    <a href="#problem" class="block py-2 hover:text-[#2E8B83]">الحلول والمشاكل</a>
    <a href="#whatsapp" class="block py-2 hover:text-[#2E8B83]">إشعارات WhatsApp</a>
    <a href="#audience" class="block py-2 hover:text-[#2E8B83]">لمن المنصة</a>
    <a href="#features" class="block py-2 hover:text-[#2E8B83]">المميزات</a>
    <a href="#roi-calculator" class="block py-2 hover:text-[#2E8B83]">حاسبة التوفير</a>
    <a href="#pricing" class="block py-2 hover:text-[#2E8B83]">الأسعار</a>
    <a href="#faq" class="block py-2 hover:text-[#2E8B83]">الأسئلة الشائعة</a>
    <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
      <a class="btn btn-outline text-center py-2" href="{{ route('login.portal') }}">تسجيل الدخول</a>
      <a class="btn btn-primary text-center py-2" href="{{ route('register') }}">ابدأ التجربة المجانية</a>
    </div>
  </div>
</header>