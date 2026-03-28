@extends('admin::layouts.hope-master')

@section('title', 'تعديل المستخدم الإداري')
@section('page-title', 'تعديل المستخدم الإداري')

@section('content')
<div class="row justify-content-center animate__animated animate__fadeIn">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-4 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm"
                         style="width:48px;height:48px;background:linear-gradient(135deg,#3A0CA3,#2A4DFF);font-size:1.2rem;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">تعديل: {{ $user->name }}</h5>
                        <p class="text-muted small mb-0">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
            <div class="card-body px-4 pb-4">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-semibold">الاسم الكامل</label>
                        <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">كلمة مرور جديدة <span class="text-muted fw-normal">(اتركه فارغاً لعدم التغيير)</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="passwordField"
                                   class="form-control rounded-start-3 @error('password') is-invalid @enderror"
                                   placeholder="اتركه فارغاً لعدم التغيير">
                            <button type="button" class="btn btn-outline-secondary rounded-end-3" onclick="togglePassword()">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">الدور الوظيفي</label>
                        @if($user->id === auth()->id())
                            <div class="alert alert-info rounded-3 small py-2">
                                <i class="fas fa-info-circle me-1"></i>
                                لا يمكنك تغيير دورك الخاص. تواصل مع مشرف آخر للقيام بذلك.
                            </div>
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <input type="text" class="form-control rounded-3 bg-light" value="{{ $user->role }}" disabled>
                        @else
                        <select name="role" class="form-select rounded-3 @error('role') is-invalid @enderror" required>
                            @foreach($roles as $role)
                                @php
                                    $roleLabels = [
                                        'super_admin'     => '👑 مشرف عام - صلاحيات كاملة',
                                        'support_agent'   => '🎧 وكيل دعم - يدير تذاكر الدعم',
                                        'finance_manager' => '💰 مدير مالي - يدير الاشتراكات والمدفوعات',
                                        'content_manager' => '📝 مدير محتوى - يدير إعدادات المنصة',
                                    ];
                                @endphp
                                <option value="{{ $role->name }}" {{ old('role', $user->role) === $role->name ? 'selected' : '' }}>
                                    {{ $roleLabels[$role->name] ?? $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @endif
                    </div>

                    {{-- Account Info --}}
                    <div class="p-3 bg-light rounded-3 mb-4 small text-muted">
                        <div class="row g-2">
                            <div class="col-6"><i class="fas fa-calendar-alt me-1"></i> أُنشئ: {{ $user->created_at->format('d/m/Y') }}</div>
                            <div class="col-6"><i class="fas fa-clock me-1"></i> آخر تحديث: {{ $user->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="fas fa-save me-2"></i> حفظ التغييرات
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
