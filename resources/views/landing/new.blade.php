@extends('layouts.landing-new')

@section('content')
    {{-- 1. Outcome-Driven Hero --}}
    @include('landing.partials.hero')

    {{-- 2. Social Proof & Readiness Strip --}}
    @include('landing.partials.trust')

    {{-- 3. Problem vs Solution Comparison Matrix --}}
    @include('landing.partials.problem')

    {{-- 4. Central Aha Moment: Student Lifecycle Automation (Scan QR -> Attendance -> Finance -> WhatsApp) --}}
    @include('landing.partials.qr-whatsapp-flow')


    {{-- 6. WhatsApp Power Feature ("ولي الأمر يعرف قبل أن يسألك") --}}
    @include('landing.partials.whatsapp-notifications')

    {{-- 7. Audience Switcher (Independent Tutor vs Center Owner) --}}
    @include('landing.partials.audience')

    {{-- 8. Condensed 6-Feature Bento Grid --}}
    @include('landing.partials.feature-bento')

    {{-- 9. Smart Interactive ROI Calculator --}}
    @include('landing.partials.roi-calculator')

    {{-- 10. Dynamic Pricing Plans --}}
    @include('landing.partials.pricing')

    {{-- 11. Security Infrastructure & FAQ --}}
    @include('landing.partials.security-faq')

    {{-- 12. Final High-Conversion CTA --}}
    @include('landing.partials.cta')
@endsection


@push('scripts')
<script>
  // Mobile menu toggle
  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');

  menuBtn?.addEventListener('click', () => {
    const open = mobileMenu.classList.toggle('open');
    menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  mobileMenu?.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      mobileMenu.classList.remove('open');
      menuBtn.setAttribute('aria-expanded', 'false');
    });
  });

  // Showcase tab switching
  const imageMap = {
    dashboard: {src: '{{ asset("images/landing_fixed/hero-dashboard.png") }}', alt: 'لوحة تحكم Taalimu'},
    attendance: {src: '{{ asset("images/landing_fixed/attendance.png") }}', alt: 'نظام حضور QR Code'},
    whatsapp: {src: '{{ asset("images/landing_fixed/whatsapp.png") }}', alt: 'إشعارات Taalimu عبر WhatsApp'}
  };

  document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.showcase-panel').forEach(p => p.classList.remove('active-panel'));
      tab.classList.add('active');

      const key = tab.dataset.image;
      document.querySelector(`[data-panel="${key}"]`)?.classList.add('active-panel');

      const image = imageMap[key];
      const img = document.getElementById('showcaseImage');
      if (image && img) {
        img.classList.add('swap');
        setTimeout(() => {
          img.src = image.src;
          img.alt = image.alt;
          img.classList.remove('swap');
        }, 120);
      }
    });
  });
</script>
@endpush