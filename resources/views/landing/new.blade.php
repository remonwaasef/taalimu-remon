@extends('layouts.landing-new')

@section('content')
    {{-- 1. Hero Section --}}
    @include('landing.partials.hero')

    {{-- 2. Problem Section --}}
    @include('landing.partials.problem')

    {{-- 3. QR Registration Feature --}}
    @include('landing.partials.qr-registration')

    {{-- 4. WhatsApp Notifications Feature --}}
    @include('landing.partials.whatsapp-notifications')

    {{-- 5. QR + WhatsApp Master Flow --}}
    @include('landing.partials.qr-whatsapp-flow')

    {{-- 6. Audience Section --}}
    @include('landing.partials.audience')

    {{-- 7. Platform Visual --}}
    @include('landing.partials.platform-visual')

    {{-- 8. Product Showcase --}}
    @include('landing.partials.product-showcase')

    {{-- 9. Feature Bento --}}
    @include('landing.partials.feature-bento')

    {{-- 10. Benefits Section --}}
    @include('landing.partials.benefits')

    {{-- 11. How It Works --}}
    @include('landing.partials.how-it-works')

    {{-- 12. Trust Section --}}
    @include('landing.partials.trust')

    {{-- 13. FAQ --}}
    @include('landing.partials.faq')

    {{-- 14. Final CTA --}}
    @include('landing.partials.cta')
@endsection