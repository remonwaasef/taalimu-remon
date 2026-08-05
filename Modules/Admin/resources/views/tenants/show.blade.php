@extends('admin::layouts.app-next')

@section('title', 'تفاصيل المركز - ' . $tenant->name)

@section('panel-content')
@php
    $sub = $tenant->activeSubscription();
@endphp

<div class="container-fluid font-sans">
    {{-- Top Action Bar --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.tenants.index') }}" class="text-decoration-none text-muted">إدارة المراكز</a></li>
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">{{ $tenant->name }}</li>
                </ol>
            </nav>
            <h3 class="fw-bold mb-0 text-dark">تفاصيل الحساب</h3>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tenants.impersonate', $tenant->id) }}" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="bi bi-box-arrow-in-right me-2"></i> دخول كمسؤول
            </a>
            <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="btn btn-light border rounded-pill px-4 fw-bold">
                <i class="bi bi-pencil-square me-2"></i> تعديل البيانات
            </a>
            <form action="{{ route('admin.tenants.toggle-status', $tenant->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-{{ $tenant->status == 'active' ? 'danger' : 'success' }} rounded-pill px-4 fw-bold">
                    <i class="bi bi-power me-2"></i> {{ $tenant->status == 'active' ? 'تعطيل الحساب' : 'تفعيل الحساب' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Main Profile Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="p-4 p-md-5 bg-gradient-emerald text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #2E8B83 0%, #1E5E58 100%);">
                <div class="position-absolute top-0 end-0 p-5 opacity-10">
                    <i class="bi bi-building" style="font-size: 10rem;"></i>
                </div>
                <div class="row align-items-center position-relative">
                    <div class="col-auto">
                        @if($tenant->logo)
                            <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo" class="rounded-4 bg-white p-2 shadow" style="width: 120px; height: 120px; object-fit: contain;">
                        @else
                            <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center border border-white border-opacity-30 shadow-inner" style="width: 120px; height: 120px; font-size: 3rem;">
                                {{ mb_substr($tenant->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <h1 class="fw-bold mb-0">{{ $tenant->name }}</h1>
                            <span class="badge bg-white bg-opacity-20 rounded-pill px-3 py-1 border border-white border-opacity-30 small">
                                <i class="bi bi-{{ $tenant->type === 'instructor' ? 'person-badge' : 'building' }} me-1"></i>
                                {{ $tenant->type === 'instructor' ? 'مدرس مستقل' : 'مركز تعليمي' }}
                            </span>
                            <span class="badge bg-{{ $tenant->status == 'active' ? 'success' : 'danger' }} rounded-pill px-3 py-1">
                                {{ $tenant->status == 'active' ? 'نشط حالياً' : 'متوقف' }}
                            </span>
                        </div>
                        <div class="d-flex flex-wrap gap-4 text-white text-opacity-75">
                            <span><i class="bi bi-globe me-2"></i> {{ $tenant->domain }}.{{ config('app.tenant_domain') }}</span>
                            <span><i class="bi bi-calendar-check me-2"></i> انضم منذ: {{ $tenant->created_at->translatedFormat('d M Y') }}</span>
                            <span><i class="bi bi-envelope me-2"></i> {{ $tenant->email ?? 'لا يوجد بريد' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Navigation Tabs --}}
            <ul class="nav nav-pills nav-fill bg-light p-2 rounded-0 border-top" id="tenantTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold py-3 px-4 rounded-3" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                        <i class="bi bi-grid-1x2 me-2"></i> نظرة عامة
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-3 px-4 rounded-3" id="billing-tab" data-bs-toggle="tab" data-bs-target="#billing" type="button" role="tab">
                        <i class="bi bi-credit-card me-2"></i> الاشتراكات والمالية
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-3 px-4 rounded-3" id="staff-tab" data-bs-toggle="tab" data-bs-target="#staff" type="button" role="tab">
                        <i class="bi bi-people me-2"></i> المدرسين والنمو
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-3 px-4 rounded-3" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button" role="tab">
                        <i class="bi bi-shield-lock me-2"></i> إعدادات الإدارة
                    </button>
                </li>
            </ul>
        </div>
    </div>

    {{-- Tab Content --}}
    <div class="tab-content" id="tenantTabsContent">
        {{-- Overview Tab --}}
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 py-3">
                        <div class="card-body text-center">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3 rounded-circle" style="width: 60px; height: 60px; line-height: 60px; font-size: 1.5rem;">
                                <i class="bi bi-people"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-0">{{ $studentsCount }}</h3>
                            <div class="text-muted small fw-bold text-uppercase">الطلاب النشطين</div>
                            <div class="progress mt-3 mx-4" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: {{ ($limits['students']['used'] / max(1, $limits['students']['total'])) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 py-3">
                        <div class="card-body text-center">
                            <div class="icon-box bg-purple bg-opacity-10 text-purple mx-auto mb-3 rounded-circle" style="width: 60px; height: 60px; line-height: 60px; font-size: 1.5rem;">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-0">{{ $coursesCount }}</h3>
                            <div class="text-muted small fw-bold text-uppercase">الدورات التدريبية</div>
                            <div class="progress mt-3 mx-4" style="height: 6px;">
                                <div class="progress-bar bg-purple" style="width: {{ ($limits['courses']['used'] / max(1, $limits['courses']['total'])) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 py-3 border-start border-4 border-success">
                        <div class="card-body text-center">
                            <div class="icon-box bg-success bg-opacity-10 text-success mx-auto mb-3 rounded-circle" style="width: 60px; height: 60px; line-height: 60px; font-size: 1.5rem;">
                                <i class="bi bi-wallet2"></i>
                            </div>
                            <h3 class="fw-bold text-success mb-0">{{ number_format($totalRevenue) }}</h3>
                            <div class="text-muted small fw-bold text-uppercase">إجمالي الإيرادات</div>
                            <div class="small text-muted mt-2">عملة المركز: {{ $tenant->settings['financial']['currency'] ?? 'EGP' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 py-3 border-start border-4 border-info">
                        <div class="card-body text-center">
                            <div class="icon-box bg-info bg-opacity-10 text-info mx-auto mb-3 rounded-circle" style="width: 60px; height: 60px; line-height: 60px; font-size: 1.5rem;">
                                <i class="bi bi-star"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($tenant->ltv) }}</h3>
                            <div class="text-muted small fw-bold text-uppercase">صافي القيمة (LTV)</div>
                            <div class="small text-muted mt-2">المسدد للمنصة</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0">
                            <h6 class="fw-bold mb-0">بيانات المركز</h6>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold mb-1">وصف المركز</label>
                                    <p class="mb-0 text-dark lh-base">{{ $tenant->description ?: 'لا يوجد وصف متاح.' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small fw-bold mb-1">بيانات التواصل</label>
                                    <div class="vstack gap-2">
                                        <div class="small text-dark"><i class="bi bi-telephone text-primary me-2"></i> {{ $tenant->phone ?: 'غير محدد' }}</div>
                                        <div class="small text-dark"><i class="bi bi-geo-alt text-primary me-2"></i> {{ $tenant->address ?: 'غير محدد' }}</div>
                                        <div class="small text-dark"><i class="bi bi-link-45deg text-primary me-2"></i> {{ $tenant->domain }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom-0">
                            <h6 class="fw-bold mb-0">آخر النشاطات</h6>
                            <a href="{{ route('admin.activity-logs.index', ['tenant_id' => $tenant->id]) }}" class="small text-primary text-decoration-none fw-bold">عرض الكل</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <tbody>
                                        @forelse($activities as $activity)
                                            <tr>
                                                <td class="ps-4" style="width: 50px;">
                                                    <div class="icon-box rounded-circle bg-light text-muted d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                        <i class="bi bi-lightning"></i>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="small fw-bold text-dark">{{ $activity->description }}</div>
                                                    <div class="x-small text-muted">{{ $activity->created_at->diffForHumans() }}</div>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <span class="badge bg-light text-muted small px-2">{{ $activity->causer->name ?? 'النظام' }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="text-center py-4 text-muted small">لا يوجد نشاطات مؤخراً</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0">
                            <h6 class="fw-bold mb-0">المسؤول الرئيسي</h6>
                        </div>
                        <div class="card-body px-4 pb-4 pt-0">
                            @php $admin = $tenant->users->whereIn('role', ['center_admin', 'admin', 'instructor'])->first(); @endphp
                            @if($admin)
                                <div class="d-flex align-items-center gap-3 p-3 rounded-4 bg-light border border-light">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm" style="width: 50px; height: 50px; font-size: 1.25rem; color: #2E8B83;">
                                        {{ mb_substr($admin->name, 0, 1) }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <h6 class="fw-bold mb-0 text-dark text-truncate">{{ $admin->name }}</h6>
                                        <div class="text-muted small text-truncate">{{ $admin->email }}</div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-danger w-100 rounded-pill" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                                        <i class="bi bi-key me-1"></i> إعادة تعيين كلمة المرور
                                    </button>
                                </div>
                            @else
                                <div class="text-center py-3 text-muted small italic">لا يوجد مسؤول مسجل</div>
                            @endif
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0">
                            <h6 class="fw-bold mb-0">الحالة التقنية</h6>
                        </div>
                        <div class="card-body px-4 pb-4 pt-0 text-center">
                            @php
                                $recentActivityCount = $activities->count();
                                $score = min(100, $recentActivityCount * 20); 
                                $engColor = $score > 70 ? 'success' : ($score > 30 ? 'warning' : 'danger');
                            @endphp
                            <div class="display-6 fw-bold text-{{ $engColor }} mb-0">{{ $score }}%</div>
                            <div class="small fw-bold text-muted text-uppercase mb-3">درجة التفاعل</div>
                            <div class="progress rounded-pill mb-2" style="height: 8px;">
                                <div class="progress-bar bg-{{ $engColor }}" style="width: {{ $score }}%"></div>
                            </div>
                            <p class="x-small text-muted mb-0">بناءً على وتيرة الاستخدام في آخر 7 أيام</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Billing Tab --}}
        <div class="tab-pane fade" id="billing" role="tabpanel">
            <div class="row g-4">
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0">
                            <h6 class="fw-bold mb-0">الباقة الحالية</h6>
                        </div>
                        <div class="card-body px-4 pb-4 pt-0">
                            @if($sub)
                                <div class="p-4 rounded-4 text-white mb-3" style="background: linear-gradient(135deg, #4361EE 0%, #3A0CA3 100%);">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <div class="x-small fw-bold text-uppercase opacity-75">نوع الاشتراك</div>
                                            <h4 class="fw-bold mb-0 text-white">
                                                @if($sub->trial_ends_at && $sub->trial_ends_at->isFuture())
                                                    <i class="bi bi-gift me-2"></i> فترة تجريبية مجانية
                                                @elseif($sub->total_amount > 0)
                                                    <i class="bi bi-shield-check me-2"></i> اشتراك مدفوع مجدد
                                                @else
                                                    <i class="bi bi-info-circle me-2"></i> اشتراك أساسي
                                                @endif
                                            </h4>
                                        </div>
                                        <span class="badge bg-white bg-opacity-20 rounded-pill px-3">{{ $sub->billing_cycle == 'yearly' ? 'سنوي' : ($sub->billing_cycle == 'term' ? 'ترم' : 'شهري') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-end mt-4">
                                        <div>
                                            <div class="x-small fw-bold text-uppercase opacity-75">اسم الباقة</div>
                                            <div class="h5 fw-bold mb-0">{{ $sub->package->name ?? ($sub->type_label ?? 'مخصص') }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="x-small fw-bold text-uppercase opacity-75">القيمة</div>
                                            <div class="h4 fw-bold mb-0 text-white">{{ number_format($sub->total_amount) }} <small class="fs-6 opacity-75">ج.م</small></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vstack gap-3 border shadow-sm rounded-4 p-3 bg-white">
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">تاريخ البدء</span>
                                        <span class="fw-bold">{{ $sub->created_at->format('Y-m-d') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">تاريخ الانتهاء</span>
                                        <span class="fw-bold">{{ $sub->ends_at ? $sub->ends_at->format('Y-m-d') : 'مستمر' }}</span>
                                    </div>
                                    @if($sub->ends_at)
                                        @php
                                            $remainingDays = now()->diffInDays($sub->ends_at, false);
                                        @endphp
                                        <div class="text-center pt-2 mt-2 border-top">
                                            @if($remainingDays > 0)
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold">متبقي {{ (int)$remainingDays }} يوم</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-bold">منتهي منذ {{ abs((int)$remainingDays) }} يوم</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-5 bg-light rounded-4">
                                    <i class="bi bi-credit-card-2-front display-6 text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">لا يوجد اشتراك نشط حالياً</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0">
                            <h6 class="fw-bold mb-0">سجل المدفوعات والاشتراكات</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 border-0 small fw-bold text-muted">الباقة</th>
                                            <th class="border-0 small fw-bold text-muted">المبلغ</th>
                                            <th class="border-0 small fw-bold text-muted">التاريخ</th>
                                            <th class="pe-4 border-0 small fw-bold text-muted">الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($subscriptionHistory as $subHist)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="fw-bold text-dark">{{ $subHist->package_name ?? 'مخصص' }}</div>
                                                    <div class="x-small text-muted">{{ $subHist->billing_cycle == 'yearly' ? 'سنوي' : 'شهري' }}</div>
                                                </td>
                                                <td><span class="fw-bold text-dark">{{ number_format($subHist->amount ?? $subHist->total_amount ?? 0) }} ج.م</span></td>
                                                <td class="small text-muted">{{ $subHist->created_at->format('Y-m-d') }}</td>
                                                <td class="pe-4 text-center">
                                                    @php $logIsExpired = $subHist->ends_at && $subHist->ends_at->isPast(); @endphp
                                                    <span class="badge bg-{{ !$logIsExpired ? 'success' : 'danger' }} bg-opacity-10 text-{{ !$logIsExpired ? 'success' : 'danger' }} rounded-pill px-2">
                                                        {{ !$logIsExpired ? 'نشط' : 'منتهي' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center py-4 text-muted small">لا يوجد سجل مدفوعات</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Staff Tab --}}
        <div class="tab-pane fade" id="staff" role="tabpanel">
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0">
                            <h6 class="fw-bold mb-0">قائمة المدرسين والموظفين</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 border-0 small fw-bold text-muted">الاسم</th>
                                            <th class="border-0 small fw-bold text-muted">الدور</th>
                                            <th class="border-0 small fw-bold text-muted">رقم الهاتف</th>
                                            <th class="pe-4 border-0 small fw-bold text-muted text-end">الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($staff as $member)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold" style="width: 35px; height: 35px;">
                                                            {{ mb_substr($member->name, 0, 1) }}
                                                        </div>
                                                        <div class="fw-bold text-dark">{{ $member->name }}</div>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">{{ $member->role == 'instructor' ? 'مدرس' : 'موظف' }}</span></td>
                                                <td class="small">{{ $member->phone ?? '-' }}</td>
                                                <td class="pe-4 text-end"><span class="text-success small fw-bold">نشط</span></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center py-4 text-muted small">لا يوجد موظفين مسجلين</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0">
                            <h6 class="fw-bold mb-0">نمو الطلاب (30 يوم)</h6>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <canvas id="growthChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Admin Tab --}}
        <div class="tab-pane fade" id="admin" role="tabpanel">
            <div class="row g-4">
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0">
                            <h6 class="fw-bold mb-0">ملاحظات الإدارة السرية</h6>
                        </div>
                        <div class="card-body px-4 pb-4 pt-0">
                            <form action="{{ route('admin.tenants.notes', $tenant->id) }}" method="POST">
                                @csrf
                                <textarea name="admin_notes" class="form-control border-0 rounded-4 mb-3 p-4 bg-light" style="min-height: 180px;" placeholder="اكتب ملاحظاتك هنا... لا تظهر للمركز">{{ $tenant->admin_notes }}</textarea>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">حفظ الملاحظات</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0">
                            <h6 class="fw-bold mb-0">الهوية البصرية</h6>
                        </div>
                        <div class="card-body px-4 pb-4 pt-0">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-4 text-center border border-light">
                                        <div class="x-small fw-bold text-muted mb-2 text-uppercase">الشعار</div>
                                        @if($tenant->logo)
                                            <img src="{{ asset('storage/' . $tenant->logo) }}" class="rounded shadow-sm bg-white p-2" style="max-width: 100%; height: 60px; object-fit: contain;">
                                        @else
                                            <div class="rounded bg-white border d-flex align-items-center justify-content-center text-muted small mx-auto" style="height: 60px; width: 60px;">N/A</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-4 text-center border border-light">
                                        <div class="x-small fw-bold text-muted mb-2 text-uppercase">الأيقونة</div>
                                        @if($tenant->favicon)
                                            <img src="{{ asset('storage/' . $tenant->favicon) }}" class="rounded shadow-sm bg-white p-1" style="width: 40px; height: 40px; object-fit: contain;">
                                        @else
                                            <div class="rounded bg-white border d-flex align-items-center justify-content-center text-muted small mx-auto" style="height: 40px; width: 40px;">?</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 px-4 border-bottom-0">
                            <h6 class="fw-bold mb-0">إعدادات النظام الإضافية</h6>
                        </div>
                        <div class="card-body px-4 pb-4 pt-0">
                            <div class="vstack gap-2">
                                <div class="p-2 px-3 bg-light rounded-3 d-flex justify-content-between small">
                                    <span class="text-muted">العملة المستعملة</span>
                                    <span class="fw-bold">{{ $tenant->settings['financial']['currency'] ?? 'EGP' }}</span>
                                </div>
                                <div class="p-2 px-3 bg-light rounded-3 d-flex justify-content-between small">
                                    <span class="text-muted">نظام الدرجات</span>
                                    <span class="fw-bold text-dark">{{ $tenant->settings['academic']['grading'] ?? 'N/A' }}</span>
                                </div>
                                <div class="p-2 px-3 bg-light rounded-3 d-flex justify-content-between small">
                                    <span class="text-muted">حالة الدعم الفني</span>
                                    <span class="fw-bold text-success">متاح</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reset Password Modal --}}
@if($admin)
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 p-4 pb-0">
                <h5 class="fw-bold mb-0">إعادة تعيين كلمة المرور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.tenants.reset-password', $tenant->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">كلمة المرور الجديدة</label>
                        <input type="password" name="password" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" class="form-control rounded-3" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">تحديث كلمة المرور</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('growthChart').getContext('2d');
        const growthData = @json($growthData);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: growthData.map(d => d.date),
                datasets: [{
                    label: 'الطلاب الجدد',
                    data: growthData.map(d => d.count),
                    borderColor: '#2E8B83',
                    backgroundColor: 'rgba(46, 139, 131, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2E8B83',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endpush

<style>
    .bg-gradient-emerald {
        background: linear-gradient(135deg, #2E8B83 0%, #1E5E58 100%) !important;
    }
    .nav-pills .nav-link {
        color: #64748b;
        transition: all 0.2s;
        border-bottom: 3px solid transparent;
    }
    .nav-pills .nav-link:hover {
        background-color: rgba(0,0,0,0.02);
        color: #2E8B83;
    }
    .nav-pills .nav-link.active {
        background-color: transparent !important;
        color: #2E8B83 !important;
        border-bottom-color: #2E8B83;
        border-radius: 0 !important;
    }
    .font-sans { font-family: 'Inter', 'Noto Sans Arabic', sans-serif; }
    .x-small { font-size: 0.75rem; }
</style>
@endsection
