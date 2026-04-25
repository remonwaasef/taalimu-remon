@extends('center::layouts.hope-master')

@section('title', __('center::messages.blade_0937'))
@section('page-title', __('center::messages.blade_0938'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">{{ __('center::messages.blade_0927') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('center.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('center::messages.blade_0928') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('center::messages.blade_0929') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">{{ __('center::messages.blade_0930') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('center::messages.blade_0931') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">{{ __('center::messages.blade_0932') }}</label>
                        @php
                            $roleData = [
                                'center_admin' => ['title' => 'مدير المركز', 'desc' => 'صلاحيات كاملة على كل النظام (الإعدادات، التقارير، حذف وتعديل أي شيء).'],
                                'instructor' => ['title' => 'محاضر / مدرس', 'desc' => 'إدارة الدورات الخاصة به فقط، متابعة طلابه، وإضافة حصص واختبارات.'],
                                'student' => ['title' => 'طالب', 'desc' => 'تصفح دوراته، حضور الحصص، وأداء الاختبارات (لا يمكنه الدخول كإداري).'],
                                'secretary' => ['title' => 'سكرتارية', 'desc' => 'إضافة طلاب، تحصيل مدفوعات، تسجيل حضور وغياب.'],
                                'accountant' => ['title' => 'محاسب', 'desc' => 'إدارة الشؤون المالية، تسجيل المصروفات، متابعة الإيرادات والفواتير.'],
                                'staff' => ['title' => 'موظف عام', 'desc' => 'صلاحيات محدودة للمهام الأساسية (استعلامات بسيطة).'],
                                'support_agent' => ['title' => 'دعم فني', 'desc' => 'الرد على استفسارات وتذاكر الطلاب.'],
                                'finance_manager' => ['title' => 'مدير مالي', 'desc' => 'الاطلاع على تقارير الربح والخسارة، التحليلات المالية، والمصروفات.'],
                                'content_manager' => ['title' => 'مدير محتوى', 'desc' => 'إنشاء دورات، إضافة فيديوهات وبنك أسئلة (بدون صلاحيات مالية).'],
                            ];
                        @endphp
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" onchange="showRoleDescription(this)">
                            <option value="" selected disabled>{{ __('center::messages.blade_0933') }}</option>
                            @foreach($roles as $r)
                                @php
                                    $normalizedName = strtolower(str_replace(' ', '_', $r->name));
                                    $title = $roleData[$normalizedName]['title'] ?? ucfirst(str_replace('_', ' ', $r->name));
                                    $desc = $roleData[$normalizedName]['desc'] ?? 'صلاحيات هذا المستخدم تحدد بناء على الدور المختار.';
                                @endphp
                                <option value="{{ $r->name }}" data-desc="{{ $desc }}" {{ old('role') == $r->name ? 'selected' : '' }}>
                                    {{ $title }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text mt-2 p-2 rounded bg-light border border-info border-start border-4 text-dark fw-bold" id="roleDescription" style="display: none;">
                            <i class="fas fa-info-circle text-info me-2"></i> <span id="roleDescText"></span>
                        </div>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4" id="permissionsSection" style="display: none;">
                        <label class="form-label fw-bold mb-3"><i class="fas fa-shield-alt text-warning me-2"></i>تخصيص صلاحيات إضافية (اختياري)</label>
                        <div class="row g-3 p-3 bg-light rounded border">
                            @php
                                $groupedPermissions = $permissions->groupBy(function($perm) {
                                    return explode(' ', $perm->name)[1] ?? 'other';
                                });
                                
                                $translations = [
                                    'view' => 'عرض',
                                    'create' => 'إضافة',
                                    'update' => 'تعديل',
                                    'delete' => 'حذف',
                                    'manage' => 'إدارة',
                                    'edit' => 'تعديل',
                                    'suspend' => 'إيقاف',
                                    'publish' => 'نشر',
                                    'students' => 'الطلاب',
                                    'courses' => 'الدورات',
                                    'users' => 'المستخدمين',
                                    'roles' => 'الأدوار',
                                    'settings' => 'الإعدادات',
                                    'sales' => 'المبيعات',
                                    'expenses' => 'المصروفات',
                                    'reports' => 'التقارير',
                                    'attendance' => 'الحضور',
                                    'centers' => 'المراكز',
                                    'instructors' => 'المحاضرين',
                                    'billing' => 'الفواتير والاشتراكات',
                                    'other' => 'أخرى',
                                ];
                                
                                function translatePerm($name, $translations) {
                                    $parts = explode(' ', $name);
                                    $action = $translations[$parts[0]] ?? $parts[0];
                                    $resource = $translations[$parts[1] ?? ''] ?? ($parts[1] ?? '');
                                    return $action . ' ' . $resource;
                                }
                            @endphp

                            @foreach($groupedPermissions as $group => $perms)
                                <div class="col-md-4 col-sm-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-white py-2 border-bottom">
                                            <span class="fw-bold text-primary small">{{ $translations[$group] ?? ucfirst($group) }}</span>
                                        </div>
                                        <div class="card-body p-2">
                                            @foreach($perms as $permission)
                                                <div class="form-check form-switch mb-1">
                                                    <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}" {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label small text-dark" for="perm_{{ $permission->id }}">
                                                        {{ translatePerm($permission->name, $translations) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-text mt-2 text-muted small"><i class="fas fa-info-circle me-1"></i> يتم تحديد الصلاحيات الأساسية تلقائياً بناءً على الدور المختار. يمكنك تخصيص الصلاحيات بزيادتها أو إنقاصها باستخدام هذه المربعات.</div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('center.users.index') }}" class="btn btn-outline-secondary">{{ __('center::messages.blade_0935') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('center::messages.blade_0936') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const rolePermissions = @json($rolePermissions ?? []);

    function showRoleDescription(selectElement) {
        const descBox = document.getElementById('roleDescription');
        const descText = document.getElementById('roleDescText');
        const permissionsSection = document.getElementById('permissionsSection');
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const roleName = selectedOption.value;
        
        if (roleName) {
            descText.textContent = selectedOption.getAttribute('data-desc');
            descBox.style.display = 'block';
            permissionsSection.style.display = 'block';
            
            // Check default permissions for this role
            const permissions = rolePermissions[roleName] || [];
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            
            checkboxes.forEach(cb => {
                cb.checked = permissions.includes(cb.value);
            });
            
        } else {
            descBox.style.display = 'none';
            permissionsSection.style.display = 'none';
        }
    }

    // Trigger on load if old value exists
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        if (roleSelect && roleSelect.value) {
            showRoleDescription(roleSelect);
        }
    });
</script>
@endpush
@endsection
