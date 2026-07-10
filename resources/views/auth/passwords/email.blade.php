@extends('layouts.auth-minimal')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="card shadow-lg" style="width: 100%; max-width: 450px; border-radius: 15px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h4>نسيت كلمة المرور</h4>
                <p class="text-muted">أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة التعيين</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success text-center">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 mb-3">إرسال رابط إعادة التعيين</button>

                <div class="text-center">
                    <a href="{{ route('login.portal') }}" class="text-decoration-none">العودة إلى تسجيل الدخول</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
