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
            <i class="fas fa-print me-2"></i>{{ __('center::messages.blade_0623') }}</button>
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
                            <h6 class="text-muted text-uppercase small fw-bold mb-3">{{ __('center::messages.blade_0624') }}</h6>
                            <div class="mb-2">
                                <span class="text-muted">{{ __('center::messages.blade_0625') }}</span>
                                <span class="fw-bold ms-2">{{ $sale->created_at->format('Y/m/d') }}</span>
                            </div>
                            <div class="mb-0">
                                <span class="text-muted">{{ __('center::messages.blade_0626') }}</span>
                                <span class="badge bg-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger') }} ms-2 px-3 rounded-pill">
                                    {{ __('center::sales.status_' . ($sale->status == 'pending' ? 'unpaid' : $sale->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Billing To -->
                <div class="mb-5">
                    <h6 class="text-muted text-uppercase small fw-bold mb-3">{{ __('center::messages.blade_0627') }}</h6>
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
                                <th class="border-0 text-center">{{ __('center::messages.blade_0628') }}</th>
                                <th class="border-0 text-center">{{ __('center::messages.blade_0629') }}</th>
                                <th class="border-0 text-end rounded-end">{{ __('center::messages.blade_0630') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $item)
                            <tr>
                                <td class="fw-bold">{{ $item->item->title ?? __('center::messages.blade_0648') }}</td>
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
                                <span>{{ number_format($sale->subtotal_amount > 0 ? $sale->subtotal_amount : $sale->total_amount, 2) }} {{ __('center::sales.currency') }}</span>
                            </div>
                            @if($sale->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-danger">الخصم (Discount):</span>
                                <span class="text-danger">-{{ number_format($sale->discount_amount, 2) }} {{ __('center::sales.currency') }}</span>
                            </div>
                            @endif
                            @if($sale->tax_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">الضريبة (Tax):</span>
                                <span>+{{ number_format($sale->tax_amount, 2) }} {{ __('center::sales.currency') }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2 border-top pt-2 mt-2">
                                <span class="fw-bold">{{ __('center::messages.blade_0631') }}:</span>
                                <span class="fw-bold">{{ number_format($sale->total_amount, 2) }} {{ __('center::sales.currency') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                <span class="text-muted">{{ __('center::messages.blade_0632') }}:</span>
                                <span class="text-success fw-bold">{{ number_format($sale->paid_amount, 2) }} {{ __('center::sales.currency') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold mb-0">{{ __('center::messages.blade_0633') }}</h5>
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
                            <th class="border-0 ps-4">{{ __('center::messages.blade_0634') }}</th>
                            <th class="border-0">{{ __('center::messages.blade_0635') }}</th>
                            <th class="border-0">{{ __('center::messages.blade_0636') }}</th>
                            <th class="border-0">{{ __('center::messages.blade_0637') }}</th>
                            <th class="border-0">{{ __('center::messages.blade_0638') }}</th>
                            <th class="border-0 text-end pe-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->payments as $payment)
                        <tr>
                            <td class="ps-4">{{ $payment->paid_at ? $payment->paid_at->format('Y/m/d H:i') : $payment->created_at->format('Y/m/d H:i') }}</td>
                            <td class="fw-bold text-success">+{{ number_format($payment->amount, 2) }} {{ __('center::sales.currency') }}</td>
                            <td><span class="badge bg-light text-dark rounded-pill px-3">{{ __('center::sales.' . $payment->payment_method) }}</span></td>
                            <td><small class="text-muted"><i class="fas fa-user-edit me-1"></i> {{ $payment->receiver->name ?? '-' }}</small></td>
                            <td><small>{{ $payment->notes }}</small></td>
                            <td class="text-end pe-4">
                                <a href="{{ route('center.payments.receipt', $payment->id) }}" class="btn btn-sm btn-light border-0 rounded-pill" title="{{ __('center::messages.blade_0647') }}">
                                    <i class="fas fa-download text-primary"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach

                        @foreach($sale->refunds as $refund)
                        <tr>
                            <td class="ps-4 text-muted">{{ $refund->created_at->format('Y/m/d H:i') }}</td>
                            <td class="fw-bold text-danger">-{{ number_format($refund->amount, 2) }} {{ __('center::sales.currency') }}</td>
                            <td><span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">استرداد (Refund)</span></td>
                            <td><small class="text-muted"><i class="fas fa-user-shield me-1"></i> {{ $refund->processor->name ?? '-' }}</small></td>
                            <td><small class="text-danger">{{ $refund->reason }}</small></td>
                            <td class="text-end pe-4"></td>
                        </tr>
                        @endforeach

                        @if($sale->payments->isEmpty() && $sale->refunds->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">{{ __('center::messages.blade_0639') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div class="col-lg-4 no-print">
        <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 20px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-wallet me-2"></i>{{ __('center::messages.blade_0640') }}</h5>
            </div>
            <div class="card-body p-4">
                @if($sale->status !== 'paid')
                    <p class="text-muted small mb-4">{{ __('center::messages.blade_0641') }}</p>
                    <form action="{{ route('center.sales.payment', $sale->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('center::sales.paid_amount') }}</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="amount" class="form-control rounded-3 shadow-none border" 
                                       placeholder="{{ __('center::messages.blade_0645') }}" required max="{{ $sale->total_amount - $sale->paid_amount }}" 
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
                            <textarea name="notes" class="form-control rounded-3 shadow-none border" rows="2" placeholder="{{ __('center::messages.blade_0646') }}"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold">
                            <i class="fas fa-check-circle me-2"></i> {{ __('center::messages.blade_0645') }}
                        </button>
                    </form>

                    <!-- Electronic Payment Link -->
                    <div class="mt-4 pt-3 border-top text-center">
                        <p class="small text-muted mb-2"><i class="fas fa-link me-1"></i> رابط الدفع السريع (Online Pay):</p>
                        <a href="{{ route('center.sales.checkout', $sale->id) }}" class="btn btn-outline-primary w-100 rounded-pill fs-6 py-2 fw-bold">
                            <i class="fas fa-credit-card me-2"></i> الدفع باستخدام البطاقة
                        </a>
                    </div>
                @else
                    <div class="alert alert-success border-0 rounded-4 text-center p-4">
                        <i class="fas fa-check-circle fa-2x mb-2 d-block mx-auto"></i>
                        <h5 class="fw-bold text-success">{{ __('center::messages.blade_0643') }}</h5>
                        <p class="text-muted small">{{ __('center::messages.blade_0644') }}</p>
                    </div>
                @endif

                @if($sale->paid_amount > 0)
                    <hr class="my-4 opacity-10">
                    <button type="button" class="btn btn-outline-danger w-100 rounded-pill py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#refundModal">
                        <i class="fas fa-undo-alt me-2"></i> إجراء استرداد مبلغ (Refund)
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div class="modal fade" id="refundModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('center.sales.refund', $sale->id) }}" method="POST">
                @csrf
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0">إجراء عملية استرداد (Refund)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning border-0 rounded-4 small mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        تحذير: هذه العملية ستقلل من قيمة المبيعات المدفوعة وقد تؤثر على عمولات المدرسين المرتبطة بهذه الفاتورة.
                    </div>

                    <div class="mb-4 text-center p-3 bg-light rounded-3">
                        <small class="text-muted d-block mb-1">إجمالي المبلغ القابل للاسترداد</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($sale->paid_amount, 2) }} {{ __('center::sales.currency') }}</h4>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">المبلغ المسترد</label>
                        <div class="input-group">
                            <input type="number" name="amount" step="0.01" class="form-control rounded-start-3" 
                                max="{{ $sale->paid_amount }}" min="0.01" value="{{ $sale->paid_amount }}" required>
                            <span class="input-group-text bg-light border-start-0 rounded-end-3">EGP</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">طريقة الاسترداد</label>
                        <select name="refund_method" class="form-select rounded-3" required>
                            <option value="cash">نقدي</option>
                            <option value="bank_transfer">تحويل بنكي</option>
                            <option value="online">أونلاين (إرجاع للبطاقة)</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">السبب</label>
                        <textarea name="reason" class="form-control rounded-3" rows="2" placeholder="أدخل سبب الاسترداد..."></textarea>
                    </div>

                    <div class="form-check form-switch p-0 mt-4">
                        <div class="bg-light p-3 rounded-4 d-flex align-items-center justify-content-between">
                            <div>
                                <label class="form-check-label fw-bold d-block mb-1" for="unenrollSwitch">إلغاء تسجيل الطالب</label>
                                <small class="text-muted d-block">سيتم حذف الطالب من الدورات التعليمية المرتبطة بهذه الفاتورة.</small>
                            </div>
                            <input class="form-check-input ms-0" type="checkbox" name="unenroll_student" value="1" id="unenrollSwitch">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">تأكيد الاسترداد</button>
                </div>
            </form>
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
