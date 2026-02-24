@extends('center::layouts.master')
@section('page-title', 'تحليلات عمولات المعلمين')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-chalkboard-teacher text-primary me-2"></i>عمولات المعلمين (Commissions)</h1>
            <p class="text-muted mb-0 mt-1">تفصيل كامل لعمولات المعلمين المستحقة والمدفوعة</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('center.analytics.finance') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للوحة المالية
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden border-left-info">
                <div class="card-body p-4 position-relative">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-4" style="width: 48px; height: 48px;">
                        <i class="fas fa-money-bill-wave fa-lg"></i>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">إجمالي العمولات</p>
                    <h3 class="fw-bold text-dark mb-0">{{ format_price($totalCommissions) }}</h3>
                </div>
                <div class="bg-info" style="height: 4px; width: 100%;"></div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">سجل العمولات</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>رقم المعاملة</th>
                            <th>المعلم</th>
                            <th>رقم الفاتورة (الطالب)</th>
                            <th>قيمة العمولة</th>
                            <th>تاريخ الاستحقاق</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissions as $commission)
                            <tr>
                                <td>#{{ $commission->id }}</td>
                                <td>
                                    @if($commission->instructor)
                                        <a href="{{ route('center.instructors.show', $commission->instructor_id) }}" class="fw-bold text-primary">
                                            {{ $commission->instructor->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">غير محدد</span>
                                    @endif
                                </td>
                                <td>
                                    @if($commission->sale)
                                        <a href="{{ route('center.sales.show', $commission->sale_id) }}">#{{ $commission->sale_id }}</a>
                                        <small class="d-block text-muted">{{ $commission->sale->student->name ?? '' }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="fw-bold text-info">{{ format_price($commission->amount) }}</td>
                                <td>{{ $commission->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if($commission->status == 'paid')
                                        <span class="badge bg-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i>مدفوعة</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fas fa-clock me-1"></i>مستحقة</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-info-circle fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">لا توجد عمولات مسجلة حتى الآن</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $commissions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
