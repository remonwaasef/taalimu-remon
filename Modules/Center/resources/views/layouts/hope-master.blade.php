@extends('layouts.hope-master')

@section('title')@yield('title', __('center::dashboard.header.dashboard_title'))@endsection

@section('favicon')
    @if(isset($tenant) && $tenant->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $tenant->favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/hope-ui/images/favicon.ico') }}">
    @endif
@endsection

@section('sidebar')
    @include('center::layouts.hope-sidebar')
@endsection

@section('header')
    @include('center::layouts.hope-header')
@endsection

@section('sub-header')
    <div class="iq-navbar-header">
        <div class="container-fluid iq-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="flex-wrap d-flex justify-content-between align-items-center pt-2 pb-5">
                        @hasSection('page-title')
                        <div>
                            <h1 class="text-white">@yield('page-title')</h1>
                            <p class="mb-0 text-white opacity-75">@yield('page-subtitle')</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            @yield('page-actions')
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts_extra')
    <!-- Enterprise Reliability Scripts (versioned via filemtime) -->
    <script src="{{ asset('js/auto-save.js') . '?v=' . (file_exists(public_path('js/auto-save.js')) ? filemtime(public_path('js/auto-save.js')) : '1') }}"></script>
    <script src="{{ asset('js/instant-search.js') . '?v=' . (file_exists(public_path('js/instant-search.js')) ? filemtime(public_path('js/instant-search.js')) : '1') }}"></script>
    <script src="{{ asset('js/crash-recovery.js') . '?v=' . (file_exists(public_path('js/crash-recovery.js')) ? filemtime(public_path('js/crash-recovery.js')) : '1') }}"></script>
    <script src="{{ asset('js/status-indicators.js') . '?v=' . (file_exists(public_path('js/status-indicators.js')) ? filemtime(public_path('js/status-indicators.js')) : '1') }}"></script>
    <script src="{{ asset('js/image-compressor.js') . '?v=' . (file_exists(public_path('js/image-compressor.js')) ? filemtime(public_path('js/image-compressor.js')) : '1') }}"></script>
    <script src="{{ asset('js/keyboard-shortcuts.js') . '?v=' . (file_exists(public_path('js/keyboard-shortcuts.js')) ? filemtime(public_path('js/keyboard-shortcuts.js')) : '1') }}"></script>
    
    <!-- Beta Bug Report Widget -->
    @include('center::partials.bug-report-widget')
    <!-- Global Double Submit Prevention -->
    <script>
        document.addEventListener('submit', function(e) {
            if (e.target && e.target.tagName === 'FORM') {
                const submitBtn = e.target.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    // Prevent default only if the form is invalid
                    if (!e.target.checkValidity()) {
                        return;
                    }
                    
                    setTimeout(() => {
                        submitBtn.disabled = true;
                        const isDelete = e.target.querySelector('input[name="_method"][value="DELETE"]') != null;
                        const loadingText = isDelete ? 'جاري الحذف...' : 'جاري التنفيذ...';
                        
                        // Keep original width to avoid layout shift
                        submitBtn.style.minWidth = submitBtn.offsetWidth + 'px';
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mx-1"></i> ' + loadingText;
                    }, 0);
                }
            }
        });
    </script>
@endsection
