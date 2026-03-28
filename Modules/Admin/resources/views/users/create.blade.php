@extends('admin::layouts.hope-master')

@section('title', 'إضافة مستخدم إداري')
@section('page-title', 'إضافة مستخدم إداري')

@section('content')
<div class="row justify-content-center animate__animated animate__fadeIn">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-4 px-4">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-user-plus text-primary me-2"></i>
                    إضافة عضو جديد لفريق الإدارة
                </h5>
                <p class="text-muted small mb-0 mt-1">سيتمكن هذا الشخص من الوصول إلى لوحة التحكم المركزية بناءً على دوره.</p>
            </div>
            <div class="card-body px-4 pb-4">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-semibold">الاسم الكامل</label>
                        <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="مثال: أحمد محمد" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="admin@example.com" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">كلمة المرور</label>
                        <div class="input-group">
                            <input type="password" name="password" id="passwordField"
                                   class="form-control rounded-start-3 @error('password') is-invalid @enderror"
                                   placeholder="8 أحرف على الأقل" required>
                            <button type="button" class="btn btn-outline-secondary rounded-end-3"
                                    onclick="togglePassword()">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        <div class="form-text">يجب أن تحتوي على 8 أحرف على الأقل، أحرف وأرقام.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">الدور الوظيفي</label>
                        <select name="role" class="form-select rounded-3 @error('role') is-invalid @enderror" required>
                            <option value="">اختر الدور...</option>
                            @foreach($roles as $role)
                                @php
                                    $roleLabels = [
                                        'super_admin'     => '👑 مشرف عام - صلاحيات كاملة',
                                        'support_agent'   => '🎧 وكيل دعم - يدير تذاكر الدعم',
                                        'finance_manager' => '💰 مدير مالي - يدير الاشتراكات والمدفوعات',
                                        'content_manager' => '📝 مدير محتوى - يدير إعدادات المنصة',
                                    ];
                                @endphp
                                <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                    {{ $roleLabels[$role->name] ?? $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="fas fa-save me-2"></i> إنشاء المستخدم
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-pill px-4 border">
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const field = document.getElementById('passwordField');
    const icon  = document.getElementById('eyeIcon');
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endsection
