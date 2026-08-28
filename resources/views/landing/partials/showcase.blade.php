{{-- Product Showcase — Redesigned Reference --}}
<section class="section product" id="product">
  <div class="container">
    <div class="section-head">
      <span class="kicker">داخل المنصة</span>
      <h2>كل شيء واضح <span>من أول نظرة</span></h2>
      <p>واجهة مصممة لتساعد مدير المركز على معرفة ما يحدث واتخاذ القرار بسرعة.</p>
    </div>

    <div class="showcase">
      <div class="showcase-copy">
        <div class="showcase-tabs">
          <button class="tab active" data-image="dashboard">لوحة التحكم</button>
          <button class="tab" data-image="attendance">الحضور QR</button>
          <button class="tab" data-image="whatsapp">WhatsApp</button>
        </div>

        <div class="showcase-panel active-panel" data-panel="dashboard">
          <span class="mini-label">لوحة المركز</span>
          <h3>اعرف وضع مركزك في نظرة واحدة</h3>
          <p>إحصائيات الطلاب والحضور والتحصيل والتقارير في لوحة واحدة سهلة القراءة.</p>
          <div class="check-grid">
            <span>✓ إجمالي الطلاب</span>
            <span>✓ حضور اليوم</span>
            <span>✓ الإيرادات</span>
            <span>✓ المتأخرات</span>
          </div>
        </div>

        <div class="showcase-panel" data-panel="attendance">
          <span class="mini-label">حضور ذكي</span>
          <h3>قلّل وقت تسجيل الحضور</h3>
          <p>نظام QR يجعل تسجيل الحضور أسرع وأكثر موثوقية، مع تحديث فوري للبيانات.</p>
          <div class="check-grid">
            <span>✓ بدون ورق</span>
            <span>✓ تسجيل سريع</span>
            <span>✓ تقارير دقيقة</span>
            <span>✓ متابعة الغياب</span>
          </div>
        </div>

        <div class="showcase-panel" data-panel="whatsapp">
          <span class="mini-label">تواصل أسرع</span>
          <h3>أرسل المعلومة في وقتها</h3>
          <p>حوّل الإشعارات المهمة إلى تواصل واضح مع أولياء الأمور عبر WhatsApp.</p>
          <div class="check-grid">
            <span>✓ إشعارات الحضور</span>
            <span>✓ تنبيهات الرسوم</span>
            <span>✓ نتائج وتقييمات</span>
            <span>✓ رسائل مهمة</span>
          </div>
        </div>
      </div>

      <div class="showcase-visual">
        <img id="showcaseImage" src="{{ asset('images/landing_fixed/hero-dashboard.png') }}" alt="لوحة تحكم Taalimu">
      </div>
    </div>
  </div>
</section>
