{{-- Audience Section — Segmented Target Choice --}}
<section class="section portals" id="audience">
  <div class="container">
    <div class="section-head center">
      <span class="kicker">{{ __('landing.audiences.badge') }}</span>
      <h2>لمن صُممت <span>منصة Taalimu؟</span></h2>
      <p>سواء كنت مدرساً مستقلاً تدير مجموعاتك الخاصة أو صاحب مركز تعليمي متعدد الفروع، Taalimu تلبي احتياجاتك بدقة.</p>
    </div>

    <div class="portal-grid" style="grid-template-columns: repeat(2, 1fr); max-width: 900px; margin: 0 auto; gap: 24px;">
      {{-- Independent Tutor --}}
      <article class="portal-card" style="border: 2px solid #B2DDD9; background: #FAFDFD; padding: 30px; border-radius: 20px; position: relative;">
        <div style="font-size: 2.2rem; margin-bottom: 12px;">👨‍🏫</div>
        <span class="badge" style="background: #E6F4F3; color: #2E8B83; font-weight: 800; padding: 4px 10px; border-radius: 999px; font-size: 11px;">
          {{ __('landing.audiences.teacher.role') }}
        </span>
        <h3 style="font-size: 20px; font-weight: 900; color: #0e1b2f; margin: 12px 0 6px;">{{ __('landing.audiences.teacher.title') }}</h3>
        <p style="font-size: 13px; color: #5d6f81; line-height: 1.8; margin-bottom: 16px;">
          إدارة كاملة لمجموعاتك الخاصة، حضور الطلاب بـ QR، رصد درجات الاختبارات، وتنبيهات WhatsApp الرسمية للأهل دون الحاجة لمساعدين.
        </p>
        <ul style="margin-bottom: 24px; font-size: 12px; color: #334155; line-height: 2;">
          @foreach(__('landing.audiences.teacher.features') as $feat)
            <li>✓ {{ $feat }}</li>
          @endforeach
        </ul>
        <a class="btn btn-primary" href="{{ route('register', ['type' => 'instructor']) }}" style="width: 100%; text-align: center;">
          تسجيل كـ مدرس مستقل ←
        </a>
      </article>

      {{-- Educational Center Owner --}}
      <article class="portal-card" style="border: 2px solid #2E8B83; background: #FFFFFF; padding: 30px; border-radius: 20px; position: relative; box-shadow: 0 12px 35px rgba(46,139,131,0.12);">
        <span style="position: absolute; top: -12px; left: 24px; background: #2E8B83; color: #fff; border-radius: 999px; padding: 4px 12px; font-size: 10px; font-weight: 900;">
          {{ __('landing.mockups.audience_center_badge') }}
        </span>
        <div style="font-size: 2.2rem; margin-bottom: 12px;">🏢</div>
        <span class="badge" style="background: #2E8B83; color: #FFFFFF; font-weight: 800; padding: 4px 10px; border-radius: 999px; font-size: 11px;">
          {{ __('landing.audiences.center.role') }}
        </span>
        <h3 style="font-size: 20px; font-weight: 900; color: #0e1b2f; margin: 12px 0 6px;">{{ __('landing.audiences.center.title') }}</h3>
        <p style="font-size: 13px; color: #5d6f81; line-height: 1.8; margin-bottom: 16px;">
          إدارة تشغيلية ومالية شاملة: فروع متعددة، حساب نسبة كل مدرس تلقائياً، نقطة بيع POS، وصلاحيات محكمة للموظفين والمساعدين.
        </p>
        <ul style="margin-bottom: 24px; font-size: 12px; color: #334155; line-height: 2;">
          @foreach(__('landing.audiences.center.features') as $feat)
            <li>✓ {{ $feat }}</li>
          @endforeach
        </ul>
        <a class="btn btn-primary" href="{{ route('register', ['type' => 'center']) }}" style="width: 100%; text-align: center; background: #1E5E58; border-color: #1E5E58;">
          تسجيل كـ مركز تعليمي ←
        </a>
      </article>
    </div>
  </div>
</section>