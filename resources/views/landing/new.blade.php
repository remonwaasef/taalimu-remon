@extends('layouts.landing-new')

@section('content')
    {{-- 1. Hero Section --}}
    @include('landing.partials.hero')

    {{-- 2. Problem / Pain Points Section --}}
    @include('landing.partials.pain-points')

    {{-- 3. Transformation (Before vs After) --}}
    @include('landing.partials.outcome')

    {{-- 4. Automated WhatsApp Communication --}}
    @include('landing.partials.whatsapp-killer')

    {{-- 5. Payments & Attendance Management --}}
    @include('landing.partials.payments-attendance')

    {{-- 6. Seamless Excel Migration --}}
    @include('landing.partials.excel-migration')

    {{-- 7. Platform Showcase --}}
    @include('landing.partials.product-showcase')

    {{-- 8. How It Works --}}
    @include('landing.partials.how-it-works')

    {{-- 9. Trust & Security --}}
    @include('landing.partials.trust')

    {{-- 10. Pricing Plans --}}
    @include('landing.partials.pricing')

    {{-- 11. FAQ --}}
    @include('landing.partials.faq')

    {{-- 12. Final Call to Action --}}
    @include('landing.partials.cta')
@endsection