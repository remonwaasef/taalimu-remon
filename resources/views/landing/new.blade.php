@extends('layouts.landing-new')

@section('content')
    {{-- 1. Hero Section --}}
    @include('landing.partials.hero')

    {{-- 2. Problem / Pain Points Section --}}
    @include('landing.partials.pain-points')

    {{-- 3. QR Code Registration Section --}}
    @include('landing.partials.qr-registration')

    {{-- 4. Meta WhatsApp Notifications Section --}}
    @include('landing.partials.whatsapp-notifications')

    {{-- 5. Master Story Timeline ("من أول Scan... إلى أول Notification") --}}
    @include('landing.partials.qr-whatsapp-flow')

    {{-- 6. Portals & Audience Section (Teacher, Center, Student, Parent) --}}
    @include('landing.partials.audience')

    {{-- 7. Central Platform Visual Ecosystem --}}
    @include('landing.partials.platform-visual')

    {{-- 8. Real Product UI Showcase --}}
    @include('landing.partials.product-showcase')

    {{-- 9. Bento Grid Feature Overview --}}
    @include('landing.partials.feature-bento')

    {{-- 10. Seamless Excel Student Import Migration --}}
    @include('landing.partials.excel-migration')

    {{-- 11. Interactive ROI & Time Savings Calculator --}}
    @include('landing.partials.roi-calculator')

    {{-- 12. How It Works (4 Steps) --}}
    @include('landing.partials.how-it-works')

    {{-- 12. Security & Trust Section --}}
    @include('landing.partials.trust')

    {{-- 13. Dynamic Pricing Plans --}}
    @include('landing.partials.pricing')

    {{-- 14. Accessible FAQ Accordion --}}
    @include('landing.partials.faq')

    {{-- 15. Final Call to Action --}}
    @include('landing.partials.cta')
@endsection