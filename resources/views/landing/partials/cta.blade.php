{{-- Final CTA — Redesigned Reference --}}
<section class="final-cta">
  <div class="container final-box">
    <div>
      <span class="mini-label">جاهز لتبسيط إدارة مركزك؟</span>
      <h2>ابدأ تجربتك المجانية اليوم</h2>
      <p>رتّب عملياتك، وفّر وقت فريقك، واجعل كل شيء واضحًا من مكان واحد.</p>
    </div>
    <div class="hero-actions">
      <a class="btn btn-primary btn-xl" href="{{ route('register') }}" data-track="cta_primary">ابدأ مجانًا الآن ←</a>
      <a class="btn btn-outline btn-xl" href="#" @click.prevent="$dispatch('open-demo-modal')">احجز عرضًا توضيحيًا</a>
    </div>
  </div>
</section>