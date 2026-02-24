@extends('center::layouts.master')

@section('title', 'بوابة الدفع الإلكتروني')

@section('content')
<div class="container mt-5 pt-5 pb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow rounded-4 border-0 text-center">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-credit-card fa-4x text-primary p-4 bg-light rounded-circle"></i>
                    </div>
                    <h3 class="fw-bold mb-3">بوابة الدفع الإلكتروني</h3>
                    <p class="text-muted mb-4">
                        يرجى مراجعة تفاصيل الفاتورة قبل إتمام عملية الدفع.
                    </p>

                    <div class="bg-light rounded-3 p-3 mb-4 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">رقم الفاتورة:</span>
                            <span class="fw-bold text-dark">#{{ $sale->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">الطالب:</span>
                            <span class="fw-bold text-dark">{{ $sale->student->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">إجمالي الفاتورة:</span>
                            <span class="fw-bold text-dark">{{ number_format($sale->total_amount, 2) }} {{ get_currency_symbol() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                            <span class="text-muted">المبلغ المدفوع:</span>
                            <span class="fw-bold text-success">{{ number_format($sale->paid_amount, 2) }} {{ get_currency_symbol() }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-danger">المطلوب سداده الآن:</span>
                            <h4 class="fw-bold text-danger mb-0">{{ number_format($sale->total_amount - $sale->paid_amount, 2) }} {{ get_currency_symbol() }}</h4>
                        </div>
                    </div>

                    <a href="{{ route('center.sales.checkout.success', $sale->id) }}" class="btn btn-primary btn-lg rounded-pill w-100 fw-bold shadow-sm d-flex justify-content-center align-items-center py-3">
                        <i class="fas fa-lock me-2"></i> تأكيد ودفع الآن (نموذج اختباري)
                    </a>
                    
                    <div class="mt-4">
                        <a href="{{ route('center.sales.show', $sale->id) }}" class="text-muted text-decoration-none hover-primary">
                            <i class="fas fa-arrow-right me-1"></i> العودة للفاتورة
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4 text-muted small">
                <i class="fas fa-shield-alt text-success me-1"></i> دفع إلكتروني مؤمن بواسطة بوابة دفع تجريبية. سيتم تحديث حالة الفاتورة تلقائياً.
            </div>
        </div>
    </div>
</div>

<style>
    .hover-primary:hover {
        color: var(--bs-primary) !important;
    }
</style>
@endsection
