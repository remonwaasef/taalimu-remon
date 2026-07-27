@extends('center::layouts.app-next')

@section('title', 'Enable Two-Factor Authentication')
@section('page-title', 'Enable Two-Factor Authentication')

@section('panel-content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Enable Two-Factor Authentication</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Setup 2FA</h6>
                </div>
                <div class="card-body">
                    <p>Scan this QR code with the Google Authenticator app on your phone:</p>
                    
                    <div class="text-center mb-4">
                        {!! $qrCodeSvg !!}
                    </div>

                    <div class="alert alert-info">
                        <strong>Secret Key:</strong> <code>{{ $secret }}</code>
                        <br><small>Save this key in a safe place. You can use it to recover your account if you lose access to your phone.</small>
                    </div>

                    <form action="{{ route('2fa.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Enter the 6-digit code from your app:</label>
                            <input type="text" name="one_time_password" class="form-control" maxlength="6" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">Enable 2FA</button>
                        <a href="{{ route('center.dashboard') }}" class="btn btn-secondary btn-block">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
