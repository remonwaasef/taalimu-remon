{{-- Header — Redesigned Reference --}}
<header class="site-header" id="top">
  <div class="container nav">
    <a class="brand" href="{{ route('home') }}" aria-label="Taalimu">
      <span class="brand-mark">+</span>
      <span>Taalimu</span>
    </a>

    <nav class="desktop-nav" aria-label="التنقل الرئيسي">
      <a href="#how">كيف تعمل</a>
      <a href="#product">المنصة</a>
      <a href="#features">المميزات</a>
      <a href="#pricing">الأسعار</a>
      <a href="#faq">الأسئلة الشائعة</a>
    </nav>

    <div class="nav-actions">
      <a class="btn btn-ghost" href="{{ route('login.portal') }}">تسجيل الدخول</a>
      <a class="btn btn-primary" href="{{ route('register') }}">ابدأ مجانًا</a>
    </div>

    <button class="menu-btn" id="menuBtn" aria-label="فتح القائمة" aria-expanded="false">☰</button>
  </div>

  <div class="mobile-menu" id="mobileMenu">
    <a href="#how">كيف تعمل</a>
    <a href="#product">المنصة</a>
    <a href="#features">المميزات</a>
    <a href="#pricing">الأسعار</a>
    <a href="#faq">الأسئلة الشائعة</a>
    <a href="{{ route('login.portal') }}">تسجيل الدخول</a>
    <a class="btn btn-primary" href="{{ route('register') }}">ابدأ مجانًا</a>
  </div>
</header>