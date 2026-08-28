{{-- Dual Visual Features (WhatsApp & QR Attendance) matching user reference specification --}}
<section id="features-split" class="visual-features">
  <div class="container feature-grid">

    {{-- Card 1: WhatsApp Notifications --}}
    <article class="feature-banner">
      <div class="feature-text">
        <span class="mini-label">تواصل فوري وفعال</span>
        <h2>إشعارات عبر WhatsApp</h2>
        <p>إشعارات فورية لكل ما يهمك: مواعيد الحضور، الدرجات، الرسوم، والفعاليات المهمة.</p>
        <div class="pills">
          <span>متكاملة</span>
          <span>آمنة</span>
          <span>موثوقة</span>
          <span>فورية</span>
        </div>
        <a class="btn btn-outline" href="{{ route('register') }}">تعرف على الإشعارات ←</a>
      </div>
      <img src="{{ asset('images/automation/step3.webp') }}" alt="إشعارات عبر WhatsApp — تواصل فوري وفعال" loading="lazy">
    </article>

    {{-- Card 2: QR Attendance --}}
    <article class="feature-banner">
      <div class="feature-text">
        <span class="mini-label">حضور ذكي وسريع</span>
        <h2>نظام حضور QR Code</h2>
        <p>سجل حضور وانصراف الطلاب بسهولة وسرعة باستخدام رمز QR، بدون ورق وبطريقة موثوقة.</p>
        <div class="pills">
          <span>دقة عالية</span>
          <span>سريع وسهل</span>
          <span>تقارير فورية</span>
          <span>آمن وموثوق</span>
        </div>
        <a class="btn btn-outline" href="{{ route('register') }}">تعرف على نظام الحضور ←</a>
      </div>
      <img src="{{ asset('images/automation/step1.webp') }}" alt="نظام حضور QR Code — حضور ذكي وسريع" loading="lazy">
    </article>

  </div>
</section>