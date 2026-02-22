@extends('center::layouts.master')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white p-4 text-center">
                    <h4 class="fw-bold mb-1"><i class="bi bi-person-check me-2"></i>{{ __('center::messages.blade_0125') }}</h4>
                    <p class="mb-0">{{ $schedule->course->title }}</p>
                </div>
                <div class="card-body p-5">
                    @if(isset($message))
                        <div class="alert alert-warning text-center">
                            {{ $message }}
                        </div>
                    @else
                        <div class="alert alert-info text-center mb-4">
                            <i class="bi bi-info-circle me-1"></i>{{ __('center::messages.blade_0126') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger text-center">
                            @foreach($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('center.attendance.loginAndMark', $schedule) }}">
                        @csrf
                        <input type="hidden" name="qr_url" value="{{ $qrUrl ?? request()->fullUrl() }}">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('center::messages.blade_0127') }}</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('center::messages.blade_0128') }}</label>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                                <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('center::messages.blade_0129') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
