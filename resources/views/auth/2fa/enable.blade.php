@extends('center::layouts.app-next')

@section('title', __('auth.2fa.enable_title'))
@section('page-title', __('auth.2fa.enable_title'))

@section('panel-content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('auth.2fa.enable_title') }}</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('auth.2fa.setup') }}</h6>
                </div>
                <div class="card-body">
                    <p>{{ __('auth.2fa.scan_qr') }}</p>
                    
                    <div class="text-center mb-4">
                        {!! $qrCodeSvg !!}
                    </div>

                    <div class="alert alert-info">
                        <strong>{{ __('auth.2fa.secret_key') }}:</strong> <code>{{ $secret }}</code>
                        <br><small>{{ __('auth.2fa.save_key_hint') }}</small>
                    </div>

                    <form action="{{ route('2fa.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>{{ __('auth.2fa.enter_code') }}</label>
                            <input type="text" name="one_time_password" class="form-control" maxlength="6" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">{{ __('auth.2fa.enable_button') }}</button>
                        <a href="{{ route('center.dashboard') }}" class="btn btn-secondary btn-block">{{ __('auth.2fa.cancel') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
