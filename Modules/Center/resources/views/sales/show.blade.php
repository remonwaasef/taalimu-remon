@extends('center::layouts.master')

@section('title', __('center::sales.view_invoice') . ' #' . $sale->id)

@section('content')
<div class="mb-4 d-flex align-items-center justify-content-between no-print">
    <div class="d-flex align-items-center">
        <a href="{{ route('center.sales.index') }}" class="btn btn-light rounded-circle me-3">
            <i class="fas fa-arrow-right"></i>
        </a>
        <h2 class="fw-bold text-dark mb-0">{{ __('center::sales.invoice_number') }} #{{ $sale->id }}</h2>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-primary rounded-pill px-4">
            <i class="fas fa-print me-2"></i> طباعة الفاتورة
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Invoice Details -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4 invoice-card">
            <div class="card-body p-5">
                <!-- Invoice Header -->
                <div class="row mb-5">
                    <div class="col-sm-6 text-start">
                        <h4 class="fw-bold text-primary mb-3">{{ $tenant->name }}</h4>
                        <div class="text-muted small">
                            <p class="mb-1"><i class="fas fa-envelope me-2"></i> {{ $tenant->email }}</p>
                            @if($tenant->phone) <p class="mb-1"><i class="fas fa-phone me-2"></i> {{ $tenant->phone }}</p> @endif
                            @if($tenant->address) <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> {{ $tenant->address }}</p> @endif
                        </div>
                    </div>
                    <div class="col-sm-6 text-end">
                        <div class="bg-light p-4 rounded-4 d-inline-block text-start" style="min-width: 250px;">
                            <h6 class="text-muted text-uppercase small fw-bold mb-3">تفاصيل الفاتورة</h6>
                            <div class="mb-2">
                                <span class="text-muted">التاريخ:</span>
                                <span class="fw-bold ms-2">{{ $sale->created_at->format('Y/m/d') }}</span>
                            </div>
                            <div class="mb-0">
                                <span class="text-muted">الحالة:</span>
                                <span class="badge bg-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger') }} ms-2 px-3 rounded-pill">
                                    {{ __('center::sales.status_' . ($sale->status == 'pending' ? 'unpaid' : $sale->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Billing To -->
                <div class="mb-5">
                    <h6 class="text-muted text-uppercase small fw-bold mb-3">مُوجهة إلى:</h6>
                    <h5 class="fw-bold text-dark mb-1">{{ $sale->student->name }}</h5>
                    <div class="text-muted small">
                        <p class="mb-1">{{ $sale->student->phone }}</p>
                        <p class="mb-0">{{ $sale->student->email }}</p>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="table-responsive mb-5">
                    <table class="table align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 rounded-start">البند / الكورس</th>
                                <th class="border-0 text-center">السعر</th>
                                <th class="border-0 text-center">الكمية</th>
                                <th class="border-0 text-end rounded-end">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $item)
                            <tr>
                                <td class="fw-bold">{{ $item->item->title ?? 'بند غير معروف' }}</td>
                                <td class="text-center">{{ number_format($item->price, 2) }} {{ __('center::sales.currency') }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end fw-bold">{{ number_format($item->price * $item->quantity, 2) }} {{ __('center::sales.currency') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <div class="bg-light rounded-4 p-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">الإجمالي الفرعي:</span>
                                <span>{{ number_format($sale->total_amount, 2) }} {{ __('center::sales.currency') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                <span class="text-muted">اجمالي المدفوع:</span>
                                <span class="text-success fw-bold">{{ number_format($sale->paid_amount, 2) }} {{ __('center::sales.currency') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold mb-0">المبلغ المتبقي:</h5>
                                <h4 class="fw-bold text-primary mb-0">{{ number_format($sale->total_amount - $sale->paid_amount, 2) }} {{ __('center::sales.currency') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History (Ledger) -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 no-print">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-history me-2"></i> سجل الحركات المالية (Ledger)</h5>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 ps-4">التاريخ</th>
                            <th class="border-0">المبلغ</th>
                            <th class="border-0">الوسيلة</th>
                            <th class="border-0">بواسطة</th>
                            <th class="border-0">ملاحظات</th>
                            <th class="border-0 text-end pe-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sale->payments as $payment)
                        <tr>
                            <td class="ps-4">{{ $payment->paid_at ? $payment->paid_at->format('Y/m/d H:i') : $payment->created_at->format('Y/m/d H:i') }}</td>
                            <td class="fw-bold text-success">+{{ number_format($payment->amount, 2) }} {{ __('center::sales.currency') }}</td>
                            <td><span class="badge bg-light text-dark rounded-pill px-3">{{ __('center::sales.' . $payment->payment_method) }}</span></td>
                            <td><small class="text-muted"><i class="fas fa-user-edit me-1"></i> {{ $payment->receiver->name ?? '-' }}</small></td>
                            <td><small>{{ $payment->notes }}</small></td>
                            <td class="text-end pe-4">
                                <a href="{{ route('center.payments.receipt', $payment->id) }}" class="btn btn-sm btn-light border-0 rounded-pill" title="تحميل الإيصال">
                                    <i class="fas fa-download text-primary"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">لا يوجد سجل مدفوعات لهذه الفاتورة.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div class="col-lg-4 no-print">
        <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 20px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-wallet me-2"></i> تحصيل دفعة</h5>
            </div>
            <div class="card-body p-4">
                @if($sale->status !== 'paid')
                    <p class="text-muted small mb-4">يمكنك تسجيل دفعة مالية جديدة لسداد المتبقي من الفاتورة.</p>
                    <form action="{{ route('center.sales.payment', $sale->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('center::sales.paid_amount') }}</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="amount" class="form-control rounded-3 shadow-none border" 
                                       placeholder="المبلغ" required max="{{ $sale->total_amount - $sale->paid_amount }}" 
                                       value="{{ $sale->total_amount - $sale->paid_amount }}">
                                <span class="input-group-text bg-white border">{{ __('center::sales.currency') }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('center::sales.payment_method') }}</label>
                            <select name="payment_method" class="form-select rounded-3 shadow-none border">
                                <option value="cash">{{ __('center::sales.cash') }}</option>
                                <option value="card">{{ __('center::sales.card') }}</option>
                                <option value="bank_transfer">{{ __('center::sales.bank_transfer') }}</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::sales.notes') }}</label>
                            <textarea name="notes" class="form-control rounded-3 shadow-none border" rows="2" placeholder="ملاحظات اختيارية..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i> تسجيل الدفعة
                        </button>
                    </form>
                @else
                    <div class="text-center py-4">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-check fa-2x"></i>
                        </div>
                        <h5 class="fw-bold text-success">الفاتورة مدفوعة بالكامل</h5>
                        <p class="text-muted small">لا توجد مبالغ متبقية للتحصيل.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        body { background-color: white !important; }
        .invoice-card { box-shadow: none !important; border: 0 !important; }
        .card-body { padding: 0 !important; }
        .invoice-card .p-5 { padding: 0 !important; }
        .sidebar, .navbar { display: none !important; }
        .main-content { margin-right: 0 !important; padding: 0 !important; width: 100% !important; }
    }
</style>
@endsection
