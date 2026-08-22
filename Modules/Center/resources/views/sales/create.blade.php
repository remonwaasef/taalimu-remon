@extends('center::layouts.app-next')

@section('title', __('center::sales.new_sale'))

@section('panel-content')
<div class="mb-4 d-flex align-items-center">
    <a href="{{ route('center.sales.index') }}" class="btn btn-light rounded-circle me-3">
        <i class="fas fa-arrow-right"></i>
    </a>
    <h2 class="fw-bold text-dark mb-0">{{ __('center::sales.new_sale') }} <span class="badge bg-info bg-opacity-10 text-info fs-6 fw-normal rounded-pill ms-2">{{ __('center::sales.dev_env') }}</span></h2>
</div>

<div class="row g-4">
    <!-- Products Selection -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-book-open me-2"></i> {{ __('center::sales.select_courses') }}</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    @foreach($courses as $course)
                    <div class="col-md-4">
                        <div class="card h-100 border shadow-none rounded-4 course-card hover-lift" 
                             onclick="addToCart({{ $course->id }}, '{{ $course->title }}', {{ $course->price }}, {{ $course->sessions_count ?? 0 }})"
                             style="cursor: pointer; transition: all 0.2s;">
                            <div class="card-body p-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 mb-3 d-inline-block">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <h6 class="fw-bold mb-2 text-dark">{{ $course->title }}</h6>
                                <div class="text-primary fw-bold">{{ number_format($course->price, 2) }} {{ get_currency_symbol() }}</div>
                                @if($course->sessions_count > 0)
                                <div class="text-muted small mt-1">
                                    <i class="fas fa-layer-group me-1"></i> {{ $course->sessions_count }} {{ __('center::sales.session') }}
                                    <span class="text-success">({{ number_format($course->price / $course->sessions_count, 2) }} {{ get_currency_symbol() }} / {{ __('center::sales.session') }})</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Cart & Checkout -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-shopping-cart me-2"></i> {{ __('center::sales.cart') }}</h5>
                <i class="fas fa-question-circle text-muted" data-bs-toggle="tooltip" title="{{ __('center::sales.cart_help') }}"></i>
            </div>
            <div class="card-body p-4">
                <form id="posForm">
                    <div class="mb-4">
                        <label class="form-label fw-bold">{{ __('center::sales.student') }}</label>
                        <select name="student_id" id="student_id" class="form-select rounded-3 shadow-none p-2 border" required>
                            <option value="">{{ __('center::sales.select_student') }}</option>
                        </select>
                    </div>

                    <!-- Student Summary Card (Hidden by default) -->
                    <div id="studentSummaryCard" class="bg-light rounded-4 p-3 mb-4 d-none">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0 text-dark">{{ __('center::sales.student_summary') }}</h6>
                            <span id="summaryStatus" class="badge rounded-pill px-3">{{ __('center::sales.account_status') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-muted">{{ __('center::sales.total_debt_label') }}</span>
                            <span id="summaryDebt" class="badge bg-danger">{{ __('center::sales.current_balance_hint') }}{{ get_currency_symbol() }}</span>
                        </div>
                        <hr class="my-2 opacity-25">
                        <div id="summaryCourses" class="small text-muted mb-3">
                            <!-- Courses will appear here -->
                        </div>
                        <div id="summaryInvoices" class="small mb-3">
                            <!-- Unpaid invoices will appear here -->
                        </div>
                        <div class="text-center">
                            <a id="summaryProfileLink" href="{{ route('center.students.index') }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill small mb-2">
                                <i class="fas fa-user-circle me-1"></i>{{ __('center::sales.view_profile') }}</a>
                        </div>
                    </div>

                    <div id="cartItems" class="mb-4 border-bottom pb-3">
                        <div class="text-center py-4 text-muted" id="emptyCartMsg">
                            <i class="fas fa-shopping-basket fa-3x mb-3 opacity-25"></i>
                            <p class="mb-0">{{ __('center::sales.empty_cart') }}</p>
                        </div>
                    </div>

                    <div class="mb-3 bg-light rounded-4 p-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">{{ __('center::sales.subtotal') }}:</span>
                            <span id="subtotalAmountDisp" class="fw-bold">0.00 {{ get_currency_symbol() }}</span>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small text-muted mb-1">{{ __('center::sales.discount') }}</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" name="discount_amount" id="discount_amount" class="form-control rounded-3 border" value="0.00" onchange="calculateTotal()" onkeyup="calculateTotal()">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted mb-1">{{ __('center::sales.tax') }}</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" name="tax_amount" id="tax_amount" class="form-control rounded-3 border" value="0.00" onchange="calculateTotal()" onkeyup="calculateTotal()">
                                </div>
                            </div>
                        </div>
                        <hr class="my-2 opacity-25">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">{{ __('center::sales.total') }}:</span>
                            <span id="totalAmount" class="fs-4 fw-bold text-primary">0.00 {{ get_currency_symbol() }}</span>
                        </div>
                    </div>

                    <!-- Installment Mode Toggle -->
                    <div id="installmentSection" class="mb-3 d-none">
                        <div class="bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-4 p-3">
                            <div class="form-check form-switch d-flex align-items-center justify-content-between p-0 mb-2">
                                <div>
                                    <label class="form-check-label fw-bold small text-dark" for="installmentToggle">
                                        <i class="fas fa-calendar-alt me-1 text-warning"></i> {{ __('center::sales.installment_mode') }}
                                    </label>
                                </div>
                                <input class="form-check-input ms-0" type="checkbox" id="installmentToggle" onchange="toggleInstallment()">
                            </div>
                            <div id="installmentDetails" class="d-none">
                                <div class="bg-white rounded-3 p-2 small">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">{{ __('center::sales.sessions_count_label') }}</span>
                                        <span id="installmentSessions" class="fw-bold">-</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">{{ __('center::sales.installment_per_session') }}</span>
                                        <span id="installmentPerSession" class="fw-bold text-success">-</span>
                                    </div>
                                    <hr class="my-1 opacity-25">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">{{ __('center::sales.remaining_after_first_pay') }}</span>
                                        <span id="installmentRemaining" class="fw-bold text-danger">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('center::sales.payment_method') }}</label>
                        <select name="payment_method" id="payment_method" class="form-select rounded-3 shadow-none border">
                            <option value="cash">{{ __('center::sales.cash') }}</option>
                            <option value="card">{{ __('center::sales.card') }}</option>
                            <option value="bank_transfer">{{ __('center::sales.bank_transfer') }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('center::sales.paid_amount') }}</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="paid_amount" id="paid_amount" class="form-control rounded-3 shadow-none border" value="0.00">
                            <span class="input-group-text bg-white border">{{ get_currency_symbol() }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">{{ __('center::sales.notes') }}</label>
                        <textarea name="notes" id="notes" class="form-control rounded-3 shadow-none border" rows="2"></textarea>
                    </div>

                    <button type="button" onclick="submitSale()" class="btn btn-primary w-100 rounded-pill py-3 fw-bold">
                        <i class="fas fa-check-circle me-2"></i> {{ __('center::sales.complete_sale') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        border-color: var(--bs-primary) !important;
    }
    .hover-lift {
        border-style: dashed !important;
    }
</style>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@include('center::sales.partials._create-scripts')
@endpush
@endsection
