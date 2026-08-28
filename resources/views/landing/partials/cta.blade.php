{{-- Final CTA Section matching user reference specification --}}
<section id="final-cta" class="cta">
  <div class="container cta-box">
    <div>
      <span class="mini-label">جاهز للارتقاء بإدارة مؤسستك التعليمية؟</span>
      <h2>ابدأ رحلتك المجانية الآن</h2>
      <p>اكتشف كيف يمكن لـ Taalimu تبسيط عملك وتنظيم مؤسستك.</p>
    </div>
    <div class="hero-actions">
      <a class="btn btn-primary btn-lg" href="{{ route('register') }}" data-track="cta_primary">ابدأ مجاناً الآن ←</a>
      <button type="button" @click="$dispatch('open-demo-modal')" class="btn btn-outline btn-lg">احجز عرض توضيحي 📅</button>
    </div>
  </div>
</section>