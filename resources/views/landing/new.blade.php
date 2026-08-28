@extends('layouts.landing-new')

@section('content')
    {{-- 1. Hero --}}
    @include('landing.partials.hero')

    {{-- 2. Proof Bar + Problem (Before/After) + Workflow (4 Steps) --}}
    @include('landing.partials.trust')

    {{-- 3. Product Showcase (Tabs: Dashboard / QR / WhatsApp) --}}
    @include('landing.partials.showcase')

    {{-- 4. QR & WhatsApp Artwork Cards --}}
    @include('landing.partials.qr-registration')

    {{-- 5. Features (6 Cards Grid) --}}
    @include('landing.partials.feature-bento')

    {{-- 6. Portals (4 Cards: Admin, Teacher, Student, Parent) --}}
    @include('landing.partials.audience')

    {{-- 7. Pricing (Dynamic from DB) --}}
    @include('landing.partials.pricing')

    {{-- 8. Security + FAQ --}}
    @include('landing.partials.security-faq')

    {{-- 9. Final CTA --}}
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