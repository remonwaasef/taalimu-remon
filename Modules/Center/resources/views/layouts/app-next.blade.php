@extends('layouts.app-next')

@section('sidebar')
    @include('center::partials._sidebar-next')
@endsection

@section('content')
    @hasSection('page-title')
        <x-ui.page-header>
            <x-slot name="title">@yield('page-title')</x-slot>
            @hasSection('page-subtitle')
                <x-slot name="subtitle">@yield('page-subtitle')</x-slot>
            @endif
            @hasSection('page-actions')
                <x-slot name="actions">@yield('page-actions')</x-slot>
            @endif
        </x-ui.page-header>
    @endif
    @yield('panel-content')
@endsection

@push('scripts')
    <script src="{{ asset('js/auto-save.js') }}"></script>
    <script src="{{ asset('js/instant-search.js') }}"></script>
    <script src="{{ asset('js/crash-recovery.js') }}"></script>
    <script src="{{ asset('js/status-indicators.js') }}"></script>
    <script src="{{ asset('js/image-compressor.js') }}"></script>
    <script src="{{ asset('js/keyboard-shortcuts.js') }}"></script>
    @include('center::partials.bug-report-widget')
    <script>
        document.addEventListener('submit', function(e) {
            if (e.target && e.target.tagName === 'FORM') {
                const submitBtn = e.target.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    if (!e.target.checkValidity()) return;
                    setTimeout(() => {
                        submitBtn.disabled = true;
                        const isDelete = e.target.querySelector('input[name="_method"][value="DELETE"]') != null;
                        const loadingText = isDelete ? 'جاري الحذف...' : 'جاري التنفيذ...';
                        submitBtn.style.minWidth = submitBtn.offsetWidth + 'px';
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mx-1"></i> ' + loadingText;
                    }, 0);
                }
            }
        });
    </script>
@endpush
