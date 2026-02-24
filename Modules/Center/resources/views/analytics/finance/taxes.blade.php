@extends('center::layouts.master')
@section('page-title', 'تحليلات الضرائب المحصلة')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-file-invoice-dollar text-primary me-2"></i>إجمالي الضرائب (Taxes)</h1>
            <p class="text-muted mb-0 mt-1">سجل تفصيلي بجميع مبالغ الضرائب المحصلة على الفواتير</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('center.analytics.finance') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للوحة المالية
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden border-left-dark">
                <div class="card-body p-4 position-relative">
                    <div class="bg-dark bg-opacity-10 text-dark rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-4" style="width: 48px; height: 48px;">
                        <i class="fas fa-coins fa-lg"></i>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">إجمالي الضرائب</p>
                    <h3 class="fw-bold text-dark mb-0">{{ format_price($totalTaxes) }}</h3>
                </div>
                <div class="bg-dark" style="height: 4px; width: 100%;"></div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">سجل الفواتير الضريبية</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>الطالب</th>
                            <th>قيمة الضريبة</th>
                            <th>الإجمالي الفرعي</th>
                            <th>النهائي (مع الضريبة)</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($taxes as $sale)
                            <tr>
                                <td><a href="{{ route('center.sales.show', $sale->id) }}" class="fw-bold">#{{ $sale->id }}</a></td>
                                <td>{{ $sale->student->name ?? 'طالب غير محدد' }}</td>
                                <td class="fw-bold">{{ format_price($sale->tax_amount) }}</td>
                                <td class="text-muted">{{ format_price($sale->subtotal_amount > 0 ? $sale->subtotal_amount : $sale->total_amount - $sale->tax_amount) }}</td>
                                <td class="fw-bold text-success">{{ format_price($sale->total_amount) }}</td>
                                <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-receipt fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">لا توجد ضرائب مطبقة حتى الآن</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $taxes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
