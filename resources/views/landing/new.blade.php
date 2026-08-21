@extends('layouts.landing-new')

@section('content')
    {{-- 1. Hero Section: Hook, Dashboard & WhatsApp Preview, 30-Day Trial CTA --}}
    @include('landing.partials.hero')

    {{-- 2. Outcome: Before vs After Transformation --}}
    @include('landing.partials.outcome')

    {{-- 3. Killer Feature: WhatsApp Automation Phone Simulation --}}
    @include('landing.partials.whatsapp-killer')

    {{-- 4. Fast Migration & Features: 1-Click Excel Import & Smart Tools --}}
    @include('landing.partials.excel-migration')

    {{-- 5. Transparent Regional Pricing --}}
    @include('landing.partials.pricing')

    {{-- 6. FAQ: Addressing Core Objections --}}
    @include('landing.partials.faq')

    {{-- 7. Final High-Converting CTA Banner --}}
    @include('landing.partials.cta')
@endsection
