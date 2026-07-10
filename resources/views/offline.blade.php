@extends('layouts.landing-new')

@section('title', 'You are Offline')

@section('content')
<div class="container py-5 mt-5 text-center">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 bg-white p-5 rounded shadow-sm text-dark text-center">
            <h1 class="display-1 text-muted">🔌</h1>
            <h2 class="mt-4 fw-bold">You are currently offline</h2>
            <p class="text-muted mt-3">It seems your device has lost internet connection. Taalimu has saved your actions locally and will sync them automatically once the connection is restored.</p>
            <button class="btn btn-primary mt-4 px-4 py-2" onclick="window.location.reload()">
                <i class="fas fa-sync-alt me-2"></i> Try Again
            </button>
        </div>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
    window.addEventListener('online', () => {
        window.location.href = '/';
    });
</script>
@endsection
