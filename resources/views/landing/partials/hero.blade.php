{{-- Hero — Redesigned Outcome-Driven Reference --}}
<section class="hero">
  <div class="container hero-grid">
    <div class="hero-copy">
      <div class="eyebrow"><span></span> منصة إدارة المراكز التعليمية والمدرسين المتكاملة</div>

      <h1>
        ودّع الدفاتر وExcel
        <span>ومتابعة أولياء الأمور يدويًا.</span>
      </h1>

      <p class="hero-lead">
        Taalimu تدير مركزك التعليمي بالكامل من مكان واحد: سجّل الطلاب بـ QR، سجّل الحضور في ثوانٍ، تابع الأقساط والمدفوعات، وأرسل إشعارات WhatsApp لأولياء الأمور تلقائيًا.
      </p>

      <div class="hero-actions">
        <a class="btn btn-primary btn-xl" href="{{ route('register') }}" data-track="hero_primary_cta">
          {{ __('landing.pricing.cta_free') }} <b>←</b>
        </a>
        <button type="button" class="btn btn-outline btn-xl" onclick="window.dispatchEvent(new CustomEvent('open-demo-modal'))">
          <span class="play">▶</span> {{ __('landing.hero.cta_secondary') }}
        </button>
      </div>

      <div class="trust-row">
        <span><i>✓</i> {{ __('landing.hero.check_nocard') }}</span>
        <span><i>✓</i> {{ __('landing.hero.check_setup') }}</span>
        <span><i>✓</i> {{ __('landing.hero.check_trial') }}</span>
      </div>
    </div>

    <div class="hero-visual">
      <div class="hero-orb orb-a"></div>
      <div class="hero-orb orb-b"></div>
      <div class="dashboard-frame">
        <div class="frame-bar"><span></span><span></span><span></span></div>
        <img src="{{ asset('images/landing_fixed/hero-dashboard.png') }}" alt="لوحة تحكم Taalimu" loading="eager">
      </div>
      <div class="floating-card floating-card-one">
        <strong>+24</strong>
        <span>حضور فوري بـ QR</span>
      </div>
      <div class="floating-card floating-card-two">
        <strong>WhatsApp ✓</strong>
        <span>تم إشعار ولي الأمر</span>
      </div>
    </div>
  </div>
</section>