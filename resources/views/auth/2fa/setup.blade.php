@extends('center::layouts.hope-master')

@section('title', __('Security: 2FA Setup'))

@section('content')
<div class="row justify-content-center align-items-center tfa-container">
    <div class="col-md-6">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-user-lock me-2"></i> {{ __('Mandatory Security: 2FA Setup') }}</h5>
            </div>
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <p class="text-muted">{{ __('To ensure the highest level of security, your account requires Two-Factor Authentication. Please scan the QR code below with your preferred authenticator app (Google Authenticator, Authy, etc.).') }}</p>
                </div>

                <div class="d-flex justify-content-center mb-4">
                    <div class="p-3 bg-white border rounded shadow-sm">
                        <img src="{{ $qrCodeUrl }}" alt="QR Code" class="img-fluid">
                    </div>
                </div>

                <div class="alert alert-info border-0 bg-light text-dark mb-4">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-info-circle fa-2x text-info"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">{{ __('Enter Authenticator Code') }}</h6>
                            <p class="small mb-0">{{ __('After scanning, enter the 6-digit code from your app to verify and enable 2FA.') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('2fa.setup.confirm', ['tenant' => $tenant->domain]) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="one_time_password" class="form-label fw-bold">{{ __('Verification Code') }}</label>
                        <input type="text" 
                               name="one_time_password" 
                               id="one_time_password" 
                               class="form-control form-control-lg text-center font-monospace @error('one_time_password') is-invalid @enderror" 
                               placeholder="000000" 
                               required 
                               maxlength="6">
                        @error('one_time_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                            <i class="fas fa-check-circle me-1"></i> {{ __('Complete Setup & Secure Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <form action="{{ route('center.logout', ['tenant' => $tenant->domain]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link text-secondary text-decoration-none">
                    <i class="fas fa-sign-out-alt me-1"></i> {{ __('Logout and setup later') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
