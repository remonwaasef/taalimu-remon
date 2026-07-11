@extends('center::layouts.hope-master')

@section('title', __('Security: 2FA Verification'))

@section('content')
<div class="row justify-content-center align-items-center tfa-container">
    <div class="col-md-5">
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-shield-alt me-2"></i> {{ __('Two-Factor Authentication') }}</h5>
            </div>
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3 tfa-icon">
                        <i class="fas fa-lock fa-2x text-primary"></i>
                    </div>
                    <h6>{{ __('Authentication Required') }}</h6>
                    <p class="text-muted small">{{ __('Please enter the 6-digit code from your authenticator app to continue.') }}</p>
                </div>

                <form action="{{ route('2fa.verify.post', ['tenant' => $tenant->domain]) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <input type="text" 
                               name="one_time_password" 
                               id="one_time_password" 
                               class="form-control form-control-lg text-center font-monospace @error('one_time_password') is-invalid @enderror" 
                               placeholder="000 000" 
                               required 
                               maxlength="6"
                               autofocus>
                        @error('one_time_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                            {{ __('Verify & Continue') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <form action="{{ route('center.logout', ['tenant' => $tenant->domain]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link text-secondary text-decoration-none">
                    <i class="fas fa-sign-out-alt me-1"></i> {{ __('Logout') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
