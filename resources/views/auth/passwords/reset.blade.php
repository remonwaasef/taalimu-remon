@extends('layouts.auth-minimal')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="card shadow-lg" style="width: 100%; max-width: 450px; border-radius: 15px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h4>إعادة تعيين كلمة المرور</h4>
                <p class="text-muted">أدخل كلمة المرور الجديدة</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ $email ?? old('email') }}" required readonly>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور الجديدة</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                           name="password" required minlength="8" autofocus>
                    <div class="form-text">يجب أن تكون 8 أحرف على الأقل</div>
                </div>

                <div class="mb-3">
                    <label for="password-confirm" class="form-label">تأكيد كلمة المرور</label>
                    <input id="password-confirm" type="password" class="form-control"
                           name="password_confirmation" required minlength="8">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">تغيير كلمة المرور</button>
            </form>
        </div>
    </div>
</div>
@endsection
