@extends('layouts.landing-new')

@section('content')
    {{-- 1. Hero Section --}}
    @include('landing.partials.hero')

    {{-- 2. User Portals Section (3 Cards: Parent, Student, Teacher) --}}
    @include('landing.partials.audience')

    {{-- 3. Visual Features Section (WhatsApp & QR Code Artwork Cards) --}}
    @include('landing.partials.qr-registration')

    {{-- 4. Key Capabilities (6 All-in-one Tools) --}}
    @include('landing.partials.feature-bento')

    {{-- 5. Pricing Section (Packages & Billing Cycles) --}}
    @include('landing.partials.pricing')

    {{-- 6. Final Call to Action --}}
    @include('landing.partials.cta')
@endsection