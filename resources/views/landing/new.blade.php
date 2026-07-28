@extends('layouts.landing-new')

@section('content')
    @include('landing.partials.hero')
    @include('landing.partials.pain-points')
    @include('landing.partials.automation')
    @include('landing.partials.features')
    @include('landing.partials.pricing')
    @include('landing.partials.faq')
    @include('landing.partials.cta')
@endsection
