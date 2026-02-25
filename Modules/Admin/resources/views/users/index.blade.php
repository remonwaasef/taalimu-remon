@extends('admin::layouts.master')

@section('title', 'مستخدمو الإدارة')
@section('page-title', 'مستخدمو الإدارة')

@section('content')
<div class="row g-4 animate__animated animate__fadeIn">

    {{-- Header Stats --}}
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h4 class="fw-bold mb-1">👥 فريق الإدارة المركزية</h4>
                <p class="text-muted mb-0 small">إدارة مستخدمي لوحة التحكم الرئيسية وصلاحياتهم</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i> إضافة مستخدم جديد
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 mb-2">
            <div class="card-body p-3">
                <form method="GET" class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control rounded-pill" placeholder="🔍 ابحث بالاسم أو البريد..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="role" class="form-select rounded-pill">
                            <option value="">كل الأدوار</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary rounded-pill w-100">بحث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0 text-muted small fw-bold text-uppercase">المستخدم</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">الدور</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">آخر تسجيل</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase">الحالة</th>
                                <th class="border-0 text-muted small fw-bold text-uppercase text-end pe-4">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm"
                                            style="width:42px;height:42px;background:linear-gradient(135deg,#3A0CA3,#2A4DFF);font-size:1rem;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                                            <div class="text-muted small">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $roleColors = [
                                            'super_admin'     => 'danger',
                                            'support_agent'   => 'info',
                                            'finance_manager' => 'success',
                                            'content_manager' => 'warning',
                                        ];
                                        $color = $roleColors[$user->role] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }} border-opacity-25 px-3 py-2 rounded-pill">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    {{ $user->updated_at->diffForHumans() }}
                                </td>
                                <td>
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">أنت</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">نشط</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="btn btn-light btn-sm rounded-pill px-3 border">
                                            <i class="fas fa-edit text-warning me-1"></i> تعديل
                                        </a>
                                        @if($user->id !== auth()->id() && $user->role !== 'super_admin' || (User::whereNull('tenant_id')->where('role','super_admin')->count() > 1))
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-light btn-sm rounded-pill px-3 border text-danger"
                                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                                <i class="fas fa-trash me-1"></i> حذف
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-users fa-3x mb-3 d-block opacity-25"></i>
                                    لا يوجد مستخدمون
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($users->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
