@extends('center::layouts.master')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white p-4 text-center">
                    <h4 class="fw-bold mb-1"><i class="bi bi-person-check me-2"></i>تسجيل الحضور</h4>
                    <p class="mb-0">{{ $schedule->course->title }}</p>
                </div>
                <div class="card-body p-5">
                    @if(isset($message))
                        <div class="alert alert-warning text-center">
                            {{ $message }}
                        </div>
                    @else
                        <div class="alert alert-info text-center mb-4">
                            <i class="bi bi-info-circle me-1"></i> يرجى تسجيل الدخول بحساب الطالب لتأكيد الحضور.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('center.login.submit') }}">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                                تسجيل الدخول وتأكيد الحضور
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
