@extends('layouts.landing-new')

@section('content')
    {{-- 1. Hero Section --}}
    @include('landing.partials.hero')

    {{-- 2. User Portals Section (3 Cards: Teacher, Student, Parent) --}}
    @include('landing.partials.audience')

    {{-- 3. Dual Split Feature Cards (QR Code Attendance & WhatsApp Notifications) --}}
    @include('landing.partials.qr-registration')

    {{-- 4. Key Capabilities (6 All-in-one Tools) --}}
    @include('landing.partials.feature-bento')

    {{-- 5. Pricing Section (Packages & Billing Cycles) --}}
    @include('landing.partials.pricing')

    {{-- 6. Final Call to Action --}}
    @include('landing.partials.cta')
@endsection