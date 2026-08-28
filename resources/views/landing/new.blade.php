@extends('layouts.landing-new')

@section('content')
    {{-- 1. Hero Section --}}
    @include('landing.partials.hero')

    {{-- 2. User Portals Section (Teacher, Student, Parent) --}}
    @include('landing.partials.audience')

    {{-- 3. Smart Attendance with QR Code --}}
    @include('landing.partials.qr-registration')

    {{-- 4. WhatsApp Notifications --}}
    @include('landing.partials.whatsapp-notifications')

    {{-- 5. Key Capabilities (6 Essential Pillars) --}}
    @include('landing.partials.feature-bento')

    {{-- 6. Final Call to Action --}}
    @include('landing.partials.cta')
@endsection