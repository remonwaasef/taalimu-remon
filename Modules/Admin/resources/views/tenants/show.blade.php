@extends('admin::layouts.master')

@section('title', 'تفاصيل المركز - ' . $tenant->name)

@section('content')
@php
    $sub = $tenant->activeSubscription();
@endphp
<style>
    /* Premium Design System */
    :root {
        --primary-gradient: var(--gradient-hero);
        --success-gradient: linear-gradient(135deg, #059669 0%, #34d399 100%);
        --danger-gradient: linear-gradient(135deg, #dc2626 0%, #f87171 100%);
        --card-bg: #ffffff;
        --card-border: #f1f5f9;
        --card-shadow: var(--shadow-md);
        --card-hover-shadow: var(--shadow-lg);
    }

    .premium-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .premium-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--card-hover-shadow);
        border-color: #e2e8f0;
    }

    .bg-gradient-primary {
        background: var(--primary-gradient) !important;
    }

    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
        transition: transform 0.2s;
    }

    .premium-card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .stat-value {
        font-family: 'Outfit', sans-serif; /* Assuming font is loaded */
        font-weight: 700;
        letter-spacing: -0.5px;
    }
    
    .progress-bar-premium {
        background: var(--primary-gradient);
        border-radius: 10px;
    }

    .table-premium thead th {
        background-color: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .table-premium tbody tr {
        transition: background-color 0.2s;
    }

    .table-premium tbody tr:hover {
        background-color: #f8fafc;
    }

    .avatar-placeholder {
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        color: #4338ca;
        font-weight: bold;
    }

    .btn-glass {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }

    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }
</style>

<div class="row g-4 font-sans">
    <!-- Header -->
    <div class="col-12">
        <div class="premium-card border-0">
            <div class="card-body p-0">
                <div class="bg-gradient-primary p-5 text-white">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                        <div class="d-flex align-items-center gap-4">
                            @if($tenant->logo)
                                <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo" class="rounded-4 bg-white p-2 shadow-sm" style="width: 90px; height: 90px; object-fit: contain;">
                            @else
                                <div class="rounded-4 bg-white bg-opacity-20 d-flex align-items-center justify-content-center text-white fw-bold fs-1 shadow-inner" style="width: 90px; height: 90px;">
                                    {{ substr($tenant->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <h1 class="fw-bold mb-0 text-white" style="letter-spacing: -0.5px;">{{ $tenant->name }}</h1>
                                    @if($tenant->type === 'instructor')
                                        <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 border border-white border-opacity-30 small backdrop-blur">
                                            <i class="bi bi-person-badge me-1"></i> مدرس مستقل
                                        </span>
                                    @else
                                        <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 border border-white border-opacity-30 small backdrop-blur">
                                            <i class="bi bi-building me-1"></i> مركز تعليمي
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex flex-wrap align-items-center gap-3 text-white text-opacity-90">
                                    <span class="d-flex align-items-center gap-1 bg-white bg-opacity-10 px-3 py-1 rounded-pill small backdrop-blur">
                                        <i class="bi bi-clock"></i> 
                                        <span>انضم: {{ $tenant->created_at->format('Y-m-d') }}</span>
                                    </span>
                                    <span class="d-flex align-items-center gap-1 bg-white bg-opacity-10 px-3 py-1 rounded-pill small backdrop-blur">
                                        <i class="bi bi-link-45deg"></i>
                                        <span>{{ $tenant->domain }}.{{ config('app.url_base', 'localhost') }}</span>
                                    </span>
                                    <span class="d-flex align-items-center gap-1 bg-white bg-opacity-10 px-3 py-1 rounded-pill small backdrop-blur">
                                        <span class="opacity-75">ID:</span> #{{ $tenant->id }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <form action="{{ route('admin.tenants.toggle-status', $tenant->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-glass rounded-pill px-4 fw-bold shadow-sm {{ $tenant->status == 'active' ? 'bg-danger text-white border-danger' : 'bg-success text-white border-success' }}">
                                    <i class="bi bi-power me-2"></i> {{ $tenant->status == 'active' ? 'تعطيل المركز' : 'تفعيل المركز' }}
                                </button>
                            </form>
                            <a href="{{ route('admin.tenants.impersonate', $tenant->id) }}" class="btn btn-glass rounded-pill px-4 fw-bold shadow-sm">
                                <i class="bi bi-box-arrow-in-right me-2"></i> دخول كمسؤول
                            </a>
                            <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="btn btn-white bg-white text-primary rounded-pill px-4 fw-bold shadow-sm border-0">
                                <i class="bi bi-pencil-square me-2"></i> تعديل
                            </a>
                            <a href="{{ route('admin.tenants.index') }}" class="btn btn-dark bg-black bg-opacity-20 border-0 text-white rounded-pill px-4 fw-bold">
                                <i class="bi bi-arrow-right me-2"></i> عودة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="col-md-3">
        <div class="premium-card h-100">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="icon-box" style="background-color: rgba(58, 12, 163, 0.1); color: #3A0CA3;">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium mb-1">عدد المستخدمين</div>
                    <div class="stat-value fs-4 text-dark">{{ $tenant->users->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="premium-card h-100">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="icon-box {{ $tenant->status == 'active' ? 'bg-success text-success' : 'bg-danger text-danger' }} bg-opacity-10">
                    <i class="bi bi-activity"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium mb-1">حالة المركز</div>
                    <div class="stat-value fs-4 {{ $tenant->status == 'active' ? 'text-success' : 'text-danger' }}">
                        {{ $tenant->status == 'active' ? 'نشط' : 'غير نشط' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="premium-card h-100">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="icon-box" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium mb-1">النمو (30 يوم)</div>
                    <div class="stat-value fs-4 text-success">+{{ $growthData->sum('count') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="premium-card h-100 overflow-hidden">
            <div class="card-body p-4 position-relative">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="icon-box" style="background-color: rgba(67, 97, 238, 0.15); color: #4361EE;">
                        <i class="bi bi-stars"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted x-small fw-bold text-uppercase tracking-wider">الباقة الحالية</div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="stat-value fs-4 text-dark">{{ $sub->package->name ?? ($sub->type_label ?? 'مخصص') }}</span>
                            @if($sub)
                                @php
                                    $isExpired = $sub->ends_at && $sub->ends_at->isPast();
                                    $isLifetime = !$sub->ends_at;
                                @endphp
                                <span class="badge bg-{{ $isExpired ? 'danger' : ($isLifetime ? 'primary' : 'success') }} bg-opacity-10 text-{{ $isExpired ? 'danger' : ($isLifetime ? 'primary' : 'success') }} rounded-pill px-2 x-small border border-{{ $isExpired ? 'danger' : ($isLifetime ? 'primary' : 'success') }} border-opacity-10">
                                    {{ $isExpired ? 'منتهي' : ($isLifetime ? 'مستمر' : 'نشط') }}
                                </span>
                            @endif
                        </div>
                        <div class="x-small text-muted mb-1">
                            <i class="bi bi-arrow-repeat me-1"></i>
                            {{ $sub->billing_cycle === 'yearly' ? 'اشتراك سنوي' : ($sub->billing_cycle === 'term' ? 'اشتراك ترم' : 'اشتراك شهري') }}
                            - <span class="fw-bold text-primary">{{ number_format($sub->total_amount, 0) }} ج.م</span>
                        </div>
                    </div>
                </div>

                @if($sub)
                    <div class="bg-light bg-opacity-50 p-3 rounded-4 border border-light">
                        <div class="d-flex justify-content-between x-small text-muted mb-2">
                            <span title="تاريخ البدء"><i class="bi bi-calendar-event me-1"></i> {{ $sub->created_at->format('Y/m/d') }}</span>
                            <span title="تاريخ الانتهاء"><i class="bi bi-calendar-x me-1"></i> {{ $sub->ends_at ? $sub->ends_at->format('Y/m/d') : '∞' }}</span>
                        </div>

                        @if($sub->ends_at)
                            @php
                                $totalDays = max(1, $sub->created_at->diffInDays($sub->ends_at));
                                $passedDays = $sub->created_at->diffInDays(now());
                                $percent = min(100, max(0, ($passedDays / $totalDays) * 100));
                                $remainingDays = now()->diffInDays($sub->ends_at, false);
                            @endphp
                            <div class="progress rounded-pill mb-2" style="height: 6px; background-color: rgba(0,0,0,0.05);">
                                <div class="progress-bar rounded-pill {{ $percent > 90 ? 'bg-danger shadow-sm' : ($percent > 75 ? 'bg-warning' : 'bg-primary') }}" 
                                     role="progressbar" 
                                     style="width: {{ $percent }}%; transition: width 1s ease-in-out;"></div>
                            </div>
                            <div class="d-flex justify-content-center">
                                @if($remainingDays > 0)
                                    <div class="badge bg-white text-dark shadow-sm border rounded-pill px-3 py-2 small fw-bold">
                                        <i class="bi bi-hourglass-split me-1 text-primary"></i> 
                                        متبقي {{ (int)$remainingDays }} يوم
                                    </div>
                                @else
                                    <div class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 small fw-bold">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        انتهى منذ {{ abs((int)$remainingDays) }} يوم
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="progress bg-primary bg-opacity-10 rounded-pill mb-2" style="height: 6px;">
                                <div class="progress-bar bg-primary rounded-pill progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                            </div>
                            <div class="text-center x-small fw-bold text-primary text-uppercase tracking-wider">اشتراك مدى الحياة نـشط</div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-2">
                        <span class="x-small text-muted italic">لا يوجد سجل اشتراكات متاح</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Enhanced Stats & Usage -->
    <div class="col-md-4">
        <div class="premium-card h-100">
            <div class="card-body p-4 position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 p-4 opacity-10">
                    <i class="bi bi-mortarboard" style="font-size: 8rem; color: #3A0CA3;"></i>
                </div>
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="icon-box" style="color: #3A0CA3; background-color: rgba(58, 12, 163, 0.1);">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold">الطلاب النشطين</div>
                        <div class="stat-value fs-3 text-dark">{{ $studentsCount }}</div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="d-flex justify-content-between text-muted x-small mb-1">
                        <span>الحد المسموح</span>
                        <span>{{ $limits['students']['used'] }} / {{ $limits['students']['total'] }}</span>
                    </div>
                    @php $studPercent = ($limits['students']['used'] / $limits['students']['total']) * 100; @endphp
                    <div class="progress bg-light rounded-pill" style="height: 6px;">
                        <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $studPercent }}%; background-color: #3A0CA3;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="premium-card h-100">
            <div class="card-body p-4 position-relative overflow-hidden">
                 <div class="position-absolute top-0 end-0 p-4 opacity-10">
                    <i class="bi bi-journal-text" style="font-size: 8rem; color: #4361EE;"></i>
                </div>
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="icon-box" style="color: #4361EE; background-color: rgba(67, 97, 238, 0.1);">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold">الدورات التدريبية</div>
                        <div class="stat-value fs-3 text-dark">{{ $coursesCount }}</div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="d-flex justify-content-between text-muted x-small mb-1">
                        <span>الحد المسموح</span>
                        <span>{{ $limits['courses']['used'] }} / {{ $limits['courses']['total'] }}</span>
                    </div>
                    @php $coursePercent = ($limits['courses']['used'] > 0 && $limits['courses']['total'] > 0) ? ($limits['courses']['used'] / $limits['courses']['total']) * 100 : 0; @endphp
                    <div class="progress bg-light rounded-pill" style="height: 6px;">
                        <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $coursePercent }}%; background-color: #4361EE;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="premium-card h-100 border-success border-opacity-25" style="background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%);">
            <div class="card-body p-4 d-flex flex-column justify-content-center text-center">
                <div class="icon-box bg-success bg-opacity-10 text-success mx-auto mb-3 rounded-circle" style="width: 64px; height: 64px; font-size: 2rem;">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="text-muted small fw-bold mb-1">إجمالي الإيرادات</div>
                <div class="stat-value fs-2 text-success">{{ number_format($totalRevenue, 2) }} <small class="fs-6 text-muted">ج.م</small></div>
                <div class="mt-2 text-success small bg-success bg-opacity-10 px-2 py-1 rounded-pill mx-auto d-inline-block">
                    <i class="bi bi-graph-up me-1"></i> أداء مالي جيد
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Financial Health -->
        <div class="premium-card mb-4 overflow-hidden" style="border-right: 4px solid #10b981 !important; background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold mb-0 text-dark">الصحة المالية (LTV)</h6>
                    <div class="icon-box bg-success bg-opacity-10 text-success rounded-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
                <div class="h4 fw-bold text-success mb-1">{{ number_format($tenant->ltv, 0) }} ج.م</div>
                <div class="x-small text-muted">إجمالي المبالغ المسددة فعلياً للمنصة</div>
                
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex justify-content-between x-small mb-1">
                        <span class="text-muted">متوسط الاشتراك الشهري</span>
                        <span class="fw-bold text-dark">
                            @php
                                $monthsActive = max(1, $tenant->created_at->diffInMonths(now()));
                                $avgMonthly = $tenant->ltv / $monthsActive;
                            @endphp
                            {{ number_format($avgMonthly, 0) }} ج.م
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Engagement Score -->
        <div class="premium-card mb-4 overflow-hidden" style="border-right: 4px solid #f59e0b !important; background: linear-gradient(135deg, #ffffff 0%, #fffbeb 100%);">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold mb-0 text-dark">درجة التفاعل</h6>
                    <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-lightning-charge"></i>
                    </div>
                </div>
                @php
                    $recentActivityCount = $activities->count();
                    $score = min(100, $recentActivityCount * 20); 
                    $engStatus = $score > 70 ? 'مرتفع' : ($score > 30 ? 'متوسط' : 'منخفض');
                    $engColor = $score > 70 ? 'success' : ($score > 30 ? 'warning' : 'danger');
                @endphp
                <div class="h4 fw-bold text-{{ $engColor }} mb-1">{{ $score }}% <small class="fw-normal text-muted fs-6">({{ $engStatus }})</small></div>
                <div class="progress mt-2" style="height: 6px; background-color: rgba(0,0,0,0.05);">
                    <div class="progress-bar bg-{{ $engColor }}" role="progressbar" style="width: {{ $score }}%"></div>
                </div>
                <div class="x-small text-muted mt-2">بناءً على وتيرة استخدام النظام مؤخراً</div>
            </div>
        </div>
        <!-- Contact Info -->
        <div class="premium-card mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-building text-primary"></i>
                    بيانات التواصل
                </h6>
                @php $admin = $tenant->users->first(); @endphp
                <div class="vstack gap-3">
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                        <div class="icon-box bg-white text-primary shadow-sm" style="width: 40px; height: 40px; font-size: 1.1rem;">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div class="text-muted x-small">البريد الإلكتروني</div>
                            <div class="fw-bold text-dark text-truncate">{{ $tenant->email ?? ($admin->email ?? 'غير محدد') }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-white text-primary shadow-sm" style="width: 40px; height: 40px; font-size: 1.1rem;">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div>
                                <div class="text-muted x-small">رقم الهاتف</div>
                                <div class="fw-bold text-dark">{{ $tenant->phone ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                        @if($tenant->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->phone) }}" target="_blank" class="btn btn-success btn-sm rounded-circle" title="واتساب">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                        <div class="icon-box bg-white text-primary shadow-sm" style="width: 40px; height: 40px; font-size: 1.1rem;">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div>
                            <div class="text-muted x-small">العنوان</div>
                            <div class="fw-bold text-dark">{{ $tenant->address ?? 'غير محدد' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                        <div class="icon-box bg-white text-primary shadow-sm" style="width: 40px; height: 40px; font-size: 1.1rem;">
                            <i class="bi bi-globe"></i>
                        </div>
                        <div>
                            <div class="text-muted x-small">النطاق</div>
                            <a href="{{ tenant_url('', $tenant) }}" target="_blank" class="fw-bold text-primary text-decoration-none">
                                {{ $tenant->domain }} <i class="bi bi-box-arrow-up-right small ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Info -->
        <div class="premium-card mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                         <i class="bi bi-person-shield text-primary"></i>
                        المسؤول الرئيسي
                    </h6>
                    @if($admin)
                        <button type="button" class="btn btn-sm btn-light text-danger rounded-pill px-3 border-0" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                            <i class="bi bi-key me-1"></i>
                        </button>
                    @endif
                </div>
                @if($admin)
                    <div class="d-flex align-items-center gap-3 p-3 rounded-4 border" style="background-color: #f8fafc; border-color: #e2e8f0;">
                         <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; font-size: 1.25rem; background: linear-gradient(135deg, #3A0CA3 0%, #2A4DFF 100%); color: white;">
                            {{ substr($admin->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="fw-bold mb-0 text-dark text-truncate">{{ $admin->name }}</h6>
                            <div class="text-muted small text-truncate" style="color: #64748b !important;">{{ $admin->email }}</div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted py-3 bg-light rounded-3">
                        <i class="bi bi-person-x fs-4 d-block mb-1 opacity-50"></i>
                        لا يوجد مسؤول مسجل
                    </div>
                @endif
            </div>
        </div>

        <!-- Social Media -->
        <div class="premium-card mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-share text-primary"></i>
                    روابط التواصل
                </h6>
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $socials = [
                            'facebook_url' => ['icon' => 'facebook', 'label' => 'Facebook', 'color' => '#1877F2', 'bg' => '#1877F215'],
                            'instagram_url' => ['icon' => 'instagram', 'label' => 'Instagram', 'color' => '#E4405F', 'bg' => '#E4405F15'],
                            'twitter_url' => ['icon' => 'twitter-x', 'label' => 'Twitter', 'color' => '#000000', 'bg' => '#00000015'],
                            'youtube_url' => ['icon' => 'youtube', 'label' => 'YouTube', 'color' => '#FF0000', 'bg' => '#FF000015'],
                            'linkedin_url' => ['icon' => 'linkedin', 'label' => 'LinkedIn', 'color' => '#0A66C2', 'bg' => '#0A66C215'],
                        ];
                    @endphp

                    @foreach($socials as $field => $data)
                        @if($tenant->$field)
                            <a href="{{ $tenant->$field }}" target="_blank" class="btn btn-sm d-flex align-items-center gap-2 rounded-pill px-3 border-0 transition" style="background-color: {{ $data['bg'] }}; color: {{ $data['color'] }};">
                                <i class="bi bi-{{ $data['icon'] }}"></i>
                                <span>{{ $data['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                    
                    @if(collect($socials)->every(fn($s, $k) => !$tenant->$k))
                         <div class="text-center text-muted small w-100 py-2">لا يوجد روابط تواصل اجتماعي</div>
                    @endif
                </div>
            </div>
        </div>

         <!-- Branding -->
         <div class="premium-card mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4 text-dark d-flex align-items-center gap-2">
                     <i class="bi bi-palette text-primary"></i>
                    الهوية البصرية
                </h6>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-4 text-center border border-light">
                            <div class="x-small fw-bold text-muted mb-2 text-uppercase tracking-wider">الشعار</div>
                            @if($tenant->logo)
                                <img src="{{ asset('storage/' . $tenant->logo) }}" class="rounded shadow-sm bg-white p-2" style="max-width: 100%; height: 60px; object-fit: contain;">
                            @else
                                <div class="rounded bg-white border d-flex align-items-center justify-content-center text-muted small mx-auto" style="height: 60px; width: 60px;">N/A</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-4 text-center border border-light">
                            <div class="x-small fw-bold text-muted mb-2 text-uppercase tracking-wider">الأيقونة</div>
                            @if($tenant->favicon)
                                <img src="{{ asset('storage/' . $tenant->favicon) }}" class="rounded shadow-sm bg-white p-1" style="width: 32px; height: 32px; object-fit: contain;">
                            @else
                                <div class="rounded bg-white border d-flex align-items-center justify-content-center text-muted small mx-auto" style="height: 32px; width: 32px;">?</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column (Main Details) -->
    <div class="col-lg-8">
         <!-- Admin Notes -->
         <div class="premium-card mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-sticky" style="color: #3A0CA3;"></i>
                    ملاحظات الإدارة
                    <span class="badge x-small" style="background-color: rgba(58, 12, 163, 0.1); color: #3A0CA3;">خاص</span>
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.tenants.notes', $tenant->id) }}" method="POST">
                    @csrf
                    <div class="position-relative">
                        <textarea name="admin_notes" class="form-control border-0 rounded-4 mb-3 p-4 text-dark" style="min-height: 120px; font-size: 0.95rem; line-height: 1.6; background-color: #f8fafc; border: 1px solid #eef2f6 !important;" placeholder="اكتب ملاحظات إدارية عن هذا المركز... (لا تظهر للمركز)">{{ $tenant->admin_notes }}</textarea>
                        <i class="bi bi-pencil-fill position-absolute bottom-0 end-0 m-4 opacity-30" style="color: #3A0CA3;"></i>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn rounded-pill px-4 fw-bold shadow-sm text-white" style="background: linear-gradient(135deg, #3A0CA3 0%, #2A4DFF 100%);">
                            <i class="bi bi-check-lg me-1"></i> حفظ الملاحظات
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Center Description -->
        <div class="premium-card mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle text-primary"></i>
                    عن المركز
                </h6>
            </div>
            <div class="card-body p-4">
                <p class="text-secondary lh-lg mb-0 text-justify">
                    {{ $tenant->description ?? 'لا يوجد وصف متاح لهذا المركز حالياً.' }}
                </p>
            </div>
        </div>

        <!-- Subscriptions History -->
        <div class="premium-card mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-credit-card text-primary"></i>
                    سجل الاشتراكات الكامل
                </h6>
            </div>
            <div class="card-body p-0 mt-3">
                <div class="table-responsive">
                    <table class="table table-premium align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">الباقة</th>
                                <th>النوع</th>
                                <th>الدورة</th>
                                <th>المبلغ</th>
                                <th>تاريخ الاشتراك</th>
                                <th class="pe-4">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptionHistory as $subHist)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $subHist->package_name ?? 'مخصص' }}</div>
                                        <div class="small text-muted">{{ $subHist->package_slug ?? '' }}</div>
                                    </td>
                                    <td>
                                        @if($subHist->operation_type === 'upgrade')
                                            <span class="badge bg-purple bg-opacity-10 text-purple rounded-pill px-3 x-small">ترقية</span>
                                        @elseif($subHist->operation_type === 'renewal')
                                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 x-small">تجديد</span>
                                        @else
                                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 x-small">اشتراك</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark rounded-pill border px-3">
                                            @if($subHist->billing_cycle == 'yearly')
                                                سنوي
                                            @elseif($subHist->billing_cycle == 'term')
                                                ترم
                                            @else
                                                شهري
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">{{ number_format($subHist->amount ?? $subHist->total_amount ?? 0, 2) }}</span>
                                    </td>
                                    <td class="small text-muted">
                                        {{ $subHist->created_at->format('Y/m/d') }}
                                    </td>
                                    <td class="pe-4">
                                        @php
                                            $logIsExpired = $subHist->ends_at && $subHist->ends_at->isPast();
                                        @endphp
                                        @if(($subHist->status ?? 'active') == 'active' && !$logIsExpired)
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">نشط</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">منتهي</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-4 text-muted">لا يوجد سجل اشتراكات</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Student Growth Growth Chart -->
        <div class="premium-card mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-graph-up-arrow text-primary"></i>
                    نمو الطلاب (أخر 30 يوم)
                </h6>
            </div>
            <div class="card-body p-4">
                <canvas id="growthChart" height="150"></canvas>
            </div>
        </div>

        <!-- Staff List -->
        <div class="premium-card mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill text-primary"></i>
                    قائمة المدرسين والموظفين
                </h6>
            </div>
            <div class="card-body p-0 mt-3">
                <div class="table-responsive">
                    <table class="table table-premium align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">الموظف</th>
                                <th>الدور</th>
                                <th>رقم الهاتف</th>
                                <th class="pe-4 text-end">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staff as $member)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                {{ substr($member->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $member->name }}</div>
                                                <div class="x-small text-muted">{{ $member->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">
                                            {{ $member->role == 'instructor' ? 'مدرس' : 'موظف' }}
                                        </span>
                                    </td>
                                    <td>{{ $member->phone ?? 'N/A' }}</td>
                                    <td class="pe-4 text-end">
                                        <span class="text-success small fw-bold">نشط</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">لا يوجد موظفين مسجلين</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row g-4">
             <!-- Global Settings -->
            <div class="col-md-6">
                <div class="premium-card h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                         <h6 class="fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-gear text-primary"></i>
                            إعدادات النظام
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-3">
                            @php
                                $settings = [
                                    ['icon' => 'currency-dollar', 'label' => 'العملة', 'value' => $tenant->settings['financial']['currency'] ?? 'EGP', 'color' => 'success'],
                                    ['icon' => 'calendar-event', 'label' => 'السنة الدراسية', 'value' => $tenant->settings['academic']['year'] ?? 'N/A', 'color' => 'primary'],
                                    ['icon' => 'brush', 'label' => 'المظهر', 'value' => ($tenant->settings['appearance']['dark_mode'] ?? false) ? 'ليلي' : 'نهاري', 'color' => 'purple'],
                                    ['icon' => 'award', 'label' => 'نظام الدرجات', 'value' => $tenant->settings['academic']['grading'] ?? 'N/A', 'color' => 'warning'],
                                    ['icon' => 'bell', 'label' => 'تنبيهات الغياب', 'value' => ($tenant->settings['academic']['attendance_alert'] ?? false) ? 'مفعلة' : 'معطلة', 'color' => 'danger'],
                                ];
                            @endphp

                            @foreach($settings as $setting)
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-4 border border-light">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; color: #3A0CA3;">
                                            <i class="bi bi-{{ $setting['icon'] }}"></i>
                                        </div>
                                        <span class="text-muted small fw-bold">{{ $setting['label'] }}</span>
                                    </div>
                                    <div class="fw-bold text-dark">{{ $setting['value'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="col-md-6">
                <div class="premium-card h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h6 class="fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-primary"></i>
                            النشاطات الحديثة
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="timeline position-relative">
                            <div class="position-absolute h-100 border-start" style="right: 15px; top: 0; z-index: 0; border-color: #f1f5f9 !important;"></div>
                            @forelse($activities as $activity)
                                <div class="position-relative d-flex gap-3 mb-4 last:mb-0" style="z-index: 1;">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-circle bg-white border border-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px; border-color: #3A0CA3 !important;">
                                            <i class="bi bi-circle-fill" style="font-size: 8px; color: #3A0CA3;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 bg-light p-3 rounded-4 border border-light">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            @php
                                                $actionMap = [
                                                    'created' => 'إضافة',
                                                    'updated' => 'تحديث',
                                                    'deleted' => 'حذف',
                                                ];
                                                $subjectMap = [
                                                    'App\Models\Student' => 'طالب',
                                                    'App\Models\Course' => 'دورة',
                                                    'App\Models\User' => 'مستخدم',
                                                    'App\Models\Enrollment' => 'اشتراك',
                                                    'App\Models\Tenant' => 'مركز',
                                                    'Modules\Center\Models\Attendance' => 'حضور',
                                                ];
                                                $action = $actionMap[$activity->description] ?? $activity->description;
                                                $subject = $subjectMap[$activity->subject_type] ?? class_basename($activity->subject_type);
                                            @endphp
                                            <p class="mb-0 text-dark fw-bold small">
                                                {{ $action }} {{ $subject }}
                                                @if($activity->subject)
                                                    <span class="text-primary opacity-75">({{ $activity->subject->name ?? $activity->subject->title ?? '#' . $activity->subject->id }})</span>
                                                @endif
                                            </p>
                                            <small class="text-muted x-small ms-2 flex-shrink-0">{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="d-flex align-items-center gap-1 text-muted x-small">
                                            <i class="bi bi-person"></i>
                                            {{ $activity->causer ? $activity->causer->name : 'System' }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-calendar-x fs-1 opacity-25 d-block mb-2"></i>
                                    لا يوجد نشاطات مسجلة مؤخراً.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($admin)
<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 p-4 bg-light">
                <h5 class="modal-title fw-bold" id="resetPasswordModalLabel">تغيير كلمة المرور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.tenants.reset-password', $tenant->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ substr($admin->name, 0, 1) }}
                        </div>
                        <h6 class="fw-bold">{{ $admin->name }}</h6>
                        <div class="text-muted small">{{ $admin->email }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">كلمة المرور الجديدة</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-4 ps-3"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control border-start-0 rounded-end-4 bg-light shadow-none py-2" required minlength="8" placeholder="********">
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-muted small fw-bold">تأكيد كلمة المرور</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-4 ps-3"><i class="bi bi-lock-fill text-muted"></i></span>
                            <input type="password" name="password_confirmation" class="form-control border-start-0 rounded-end-4 bg-light shadow-none py-2" required minlength="8" placeholder="********">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">تحديث كلمة المرور</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if(session('success'))
<div class="position-fixed bottom-0 end-0 p-4" style="z-index: 1060">
    <div class="toast show align-items-center text-white border-0 rounded-4 shadow-lg overflow-hidden" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex p-3 bg-success bg-gradient">
            <div class="toast-body fs-6 fw-bold">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

@if($errors->any())
<div class="position-fixed bottom-0 end-0 p-4" style="z-index: 1060">
    @foreach($errors->all() as $error)
    <div class="toast show align-items-center text-white border-0 rounded-4 shadow-lg overflow-hidden mb-3" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex p-3 bg-danger bg-gradient">
            <div class="toast-body fs-6 fw-bold">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> {{ $error }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    </div>
    @endforeach
</div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Growth Chart
        const ctx = document.getElementById('growthChart')?.getContext('2d');
        if (ctx) {
            const labels = {!! json_encode($growthData->pluck('date')) !!};
            const data = {!! json_encode($growthData->pluck('count')) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'عدد الطلاب المسجلين',
                        data: data,
                        borderColor: '#3A0CA3',
                        backgroundColor: 'rgba(58, 12, 163, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#3A0CA3',
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: '#1e293b',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            borderRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                            ticks: { font: { size: 11 }, stepSize: 1 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        }
    });

    setTimeout(function() {
        var toasts = document.querySelectorAll('.toast');
        toasts.forEach(function(toast) {
            toast.classList.remove('show');
        });
    }, 5000);
</script>
@endpush
@endsection
