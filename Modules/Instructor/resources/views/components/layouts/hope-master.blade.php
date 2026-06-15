@extends('layouts.hope-master')

@section('title')@yield('title', 'لوحة تحكم المعلم') - {{ config('app.name', 'Taalimu') }}@endsection

@section('favicon')
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/hope-ui/images/favicon.ico') }}">
@endsection

@section('head_extra')
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection

@section('sidebar')
    @include('instructor::components.layouts.hope-sidebar')
@endsection

@section('header')
    @include('instructor::components.layouts.hope-header')
@endsection

@section('sub-header')
    <div class="iq-navbar-header">
        <div class="container-fluid iq-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="flex-wrap d-flex justify-content-between align-items-center pt-2 pb-5">
                        @hasSection('page-title')
                        <div>
                            <h1 class="text-white display-5 mb-1 fw-bold">@yield('page-title')</h1>
                            <p class="text-white opacity-75 mb-0 fw-medium">
                                @yield('page-subtitle')
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            @yield('page-actions')
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="iq-header-img">
             <img src="{{ asset('assets/hope-ui/images/dashboard/top-header.png') }}" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">
        </div>
    </div>
@endsection

@section('scripts_extra')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
