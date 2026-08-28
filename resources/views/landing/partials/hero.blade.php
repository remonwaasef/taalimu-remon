{{-- Hero Section matching C:\Users\new\Downloads\Taalimu_Landing_Page_HTML_CSS_Fixed --}}
<section class="hero">
  <div class="container hero-grid">
    <div class="hero-copy">
      <span class="eyebrow"><i></i> منصة متكاملة لإدارة المؤسسات التعليمية</span>
      <h1>كل ما تحتاجه لإدارة<br><span>مؤسستك التعليمية</span><br>في منصة واحدة</h1>
      <p>إدارة الطلبة والمعلمين والفصول والدرجات والمدفوعات والتقارير والتواصل... بسهولة من أي مكان وفي أي وقت.</p>
      <div class="hero-actions">
        <a class="btn btn-primary btn-lg" href="{{ route('register') }}" data-track="hero_primary_cta">ابدأ مجاناً الآن <b>←</b></a>
        <button type="button" @click="$dispatch('open-demo-modal')" class="btn btn-outline btn-lg">احجز عرض توضيحي <b>📅</b></button>
      </div>
      <div class="trust-row">
        <span>✓ بدون بطاقة ائتمان</span>
        <span>⚡ دعم فني 24/7</span>
        <span>◉ إعداد سريع خلال دقائق</span>
      </div>
    </div>

    <div class="hero-visual">
      <div class="glow"></div>
      <img src="{{ asset('images/landing_fixed/hero-dashboard.png') }}" alt="لوحة تحكم Taalimu" loading="eager">
    </div>
  </div>
</section>