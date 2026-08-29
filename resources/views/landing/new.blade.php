@extends('layouts.landing-new')

@section('content')
    {{-- 1. Outcome-Driven Hero with Live Scanner Simulation --}}
    @include('landing.partials.hero')

    {{-- 2. Social Proof & Animated Readiness Strip --}}
    @include('landing.partials.trust')

    {{-- 3. Problem vs Solution Comparison Matrix --}}
    @include('landing.partials.problem')

    {{-- 4. Central Aha Moment: Student Lifecycle Automation (Scan QR -> Attendance -> Finance -> WhatsApp) --}}
    @include('landing.partials.qr-whatsapp-flow')

    {{-- 5. WhatsApp Power Feature ("ولي الأمر يعرف قبل أن يسألك") --}}
    @include('landing.partials.whatsapp-notifications')

    {{-- 6. Audience Switcher (Independent Tutor vs Center Owner) --}}
    @include('landing.partials.audience')

    {{-- 7. Condensed 6-Feature Bento Grid --}}
    @include('landing.partials.feature-bento')

    {{-- 8. Smart Interactive ROI Calculator --}}
    @include('landing.partials.roi-calculator')

    {{-- 9. Real Testimonials & Social Proof --}}
    @include('landing.partials.testimonials')

    {{-- 10. Dynamic Pricing Plans with Billing Switcher --}}
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
    const isHidden = mobileMenu.classList.toggle('hidden');
    menuBtn.setAttribute('aria-expanded', !isHidden ? 'true' : 'false');
  });

  mobileMenu?.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      mobileMenu.classList.add('hidden');
      menuBtn.setAttribute('aria-expanded', 'false');
    });
  });
</script>
@endpush