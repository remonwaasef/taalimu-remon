{{-- Header matching user reference specification --}}
<header class="header">
  <div class="container nav">
    <a class="brand" href="{{ route('home') }}">
      <span class="brand-mark">✦</span>
      <span>Taalimu</span>
    </a>

    <nav class="nav-links">
      <a href="#capabilities">المميزات</a>
      <a href="#capabilities">الأسعار</a>
      <a href="#features-split">الموارد</a>
      <a href="#portals">من نحن</a>
    </nav>

    <div class="nav-actions">
      <a class="btn btn-outline" href="{{ route('login.portal') }}">تسجيل الدخول</a>
      <a class="btn btn-primary" href="{{ route('register') }}">ابدأ مجانًا</a>
    </div>
  </div>
</header>