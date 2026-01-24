@extends('admin::layouts.master')

@section('title', 'إدارة المراكز')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">إدارة المراكز التعليمية</h2>
            <p class="text-muted small mb-0">نظرة عامة على المراكز والطلاب والنشاط العام</p>
        </div>
        <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> إضافة مركز جديد
        </a>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-right: 4px solid #4361EE !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">إجمالي المراكز</div>
                            <div class="h3 fw-bold mb-0 text-dark">{{ $stats['total_count'] }}</div>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-building fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-right: 4px solid #10b981 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">المراكز النشطة</div>
                            <div class="h3 fw-bold mb-0 text-success">{{ $stats['active_count'] }}</div>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-check-circle fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-right: 4px solid #f59e0b !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">غير مفعلة</div>
                            <div class="h3 fw-bold mb-0 text-warning">{{ $stats['inactive_count'] }}</div>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-pause-circle fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #fff7ed 100%); border-right: 4px solid #4361EE !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">إجمالي الطلاب</div>
                            <div class="h3 fw-bold mb-0 text-dark">{{ number_format($stats['total_students']) }}</div>
                        </div>
                        <div class="icon-box bg-primary text-white rounded-3 shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #4361EE 0%, #4895ef 100%);">
                            <i class="bi bi-mortarboard fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & List -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <form action="{{ route('admin.tenants.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0 x-small" placeholder="بحث باسم المركز أو النطاق..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select bg-light border-0 x-small" onchange="this.form.submit()">
                        <option value="">كل الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark rounded-pill w-100 x-small fw-bold">تصفية</button>
                </div>
                @if(request()->anyFilled(['search', 'status']))
                    <div class="col-md-2">
                        <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline-secondary rounded-pill w-100 x-small border-dashed">إعادة تعيين</a>
                    </div>
                @endif
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-secondary small text-uppercase">
                            <th class="py-3 ps-4 border-0">المركز والمسؤول</th>
                            <th class="py-3 border-0">النطاق والنشاط</th>
                            <th class="py-3 border-0 text-center">الطلاب</th>
                            <th class="py-3 border-0 text-center">الحالة</th>
                            <th class="py-3 pe-4 border-0 text-end">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($tenants as $tenant)
                            @php
                                $studentsCount = \App\Models\Student::where('tenant_id', $tenant->id)->count();
                                $admin = $tenant->users->first();
                                $statusClass = $tenant->status == 'active' ? 'success' : 'danger';
                                $statusLabel = $tenant->status == 'active' ? 'نشط' : 'معطل';
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative me-3">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-weight: bold; font-size: 1.1rem; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                                @if($tenant->logo)
                                                    <img src="{{ asset('storage/' . $tenant->logo) }}" class="rounded-circle w-100 h-100 object-fit-contain p-1">
                                                @else
                                                    {{ substr($tenant->name, 0, 1) }}
                                                @endif
                                            </div>
                                            @if($tenant->status == 'active')
                                                <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle" style="width: 12px; height: 12px;"></span>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="fw-bold text-dark text-decoration-none d-block">
                                                {{ $tenant->name }}
                                            </a>
                                            <span class="text-muted x-small">
                                                <i class="bi bi-person me-1"></i> {{ $admin->name ?? 'غير محدد' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="text-muted small mb-1">
                                            <i class="bi bi-link-45deg"></i> {{ $tenant->domain }}
                                        </div>
                                        <span class="x-small text-muted italic">انضم في {{ $tenant->created_at->format('Y/m/d') }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="h6 mb-0 fw-bold">{{ number_format($studentsCount) }}</span>
                                        <span class="x-small text-muted">طالب</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} rounded-pill px-3 py-2 border border-{{ $statusClass }} border-opacity-10">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle shadow-none" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2" style="min-width: 180px;">
                                            <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('admin.tenants.show', $tenant->id) }}"><i class="bi bi-eye me-2 text-primary"></i> عرض التفاصيل</a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('admin.tenants.edit', $tenant->id) }}"><i class="bi bi-pencil me-2 text-info"></i> تعديل البيانات</a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('admin.tenants.impersonate', $tenant->id) }}"><i class="bi bi-box-arrow-in-right me-2 text-success"></i> دخول كمسؤول</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المركز؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item rounded-3 text-danger">
                                                        <i class="bi bi-trash me-2"></i> حذف المركز
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center">
                                    <div class="py-5">
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                                            <i class="bi bi-search fs-1 opacity-25"></i>
                                        </div>
                                        <h5 class="fw-bold text-dark">لا يوجد نتائج</h5>
                                        <p class="text-muted">لم نجد أي مراكز تتطابق مع معايير البحث الحالية.</p>
                                        <a href="{{ route('admin.tenants.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">عرض كل المراكز</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($tenants->hasPages())
            <div class="card-footer bg-white border-0 p-4 pt-0">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
@endsection
