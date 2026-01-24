@extends('center::layouts.master')

@section('title', __('center::sales.new_sale'))

@section('content')
<div class="mb-4 d-flex align-items-center">
    <a href="{{ route('center.sales.index') }}" class="btn btn-light rounded-circle me-3">
        <i class="fas fa-arrow-right"></i>
    </a>
    <h2 class="fw-bold text-dark mb-0">{{ __('center::sales.new_sale') }}</h2>
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
                             onclick="addToCart({{ $course->id }}, '{{ $course->title }}', {{ $course->price }})"
                             style="cursor: pointer; transition: all 0.2s;">
                            <div class="card-body p-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 mb-3 d-inline-block">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <h6 class="fw-bold mb-2 text-dark">{{ $course->title }}</h6>
                                <div class="text-primary fw-bold">{{ number_format($course->price, 2) }} {{ __('center::sales.currency') }}</div>
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
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-shopping-cart me-2"></i> {{ __('center::sales.cart') }}</h5>
            </div>
            <div class="card-body p-4">
                <form id="posForm">
                    <div class="mb-4">
                        <label class="form-label fw-bold">{{ __('center::sales.student') }}</label>
                        <select name="student_id" id="student_id" class="form-select rounded-3 shadow-none p-2 border" required onchange="fetchStudentSummary(this.value)">
                            <option value="">{{ __('center::sales.select_student') }}</option>
                            @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->phone }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Student Summary Card (Hidden by default) -->
                    <div id="studentSummaryCard" class="bg-light rounded-4 p-3 mb-4 d-none">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0 text-dark">الملف المالي للطالب</h6>
                            <span id="summaryStatus" class="badge rounded-pill px-3">نشط</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-muted">إجمالي المديونية:</span>
                            <span id="summaryDebt" class="badge bg-danger">0.00 ج.م</span>
                        </div>
                        <hr class="my-2 opacity-25">
                        <div id="summaryCourses" class="small text-muted mb-3">
                            <!-- Courses will appear here -->
                        </div>
                        <div id="summaryInvoices" class="small mb-3">
                            <!-- Unpaid invoices will appear here -->
                        </div>
                        <div class="text-center">
                            <a id="summaryProfileLink" href="#" class="btn btn-sm btn-outline-primary w-100 rounded-pill small mb-2">
                                <i class="fas fa-user-circle me-1"></i> عرض ملف الطالب بالكامل
                            </a>
                        </div>
                    </div>

                    <div id="cartItems" class="mb-4 border-bottom pb-3">
                        <div class="text-center py-4 text-muted" id="emptyCartMsg">
                            <i class="fas fa-shopping-basket fa-3x mb-3 opacity-25"></i>
                            <p class="mb-0">{{ __('center::sales.empty_cart') }}</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded-4">
                        <span class="fw-bold text-muted">{{ __('center::sales.amount') }}:</span>
                        <span id="totalAmount" class="fs-4 fw-bold text-primary">0.00 {{ __('center::sales.currency') }}</span>
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
                            <span class="input-group-text bg-white border">{{ __('center::sales.currency') }}</span>
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

@push('scripts')
<script>
    let cart = [];
    const currency = '{{ __('center::sales.currency') }}';

    function fetchStudentSummary(studentId) {
        if (!studentId) {
            document.getElementById('studentSummaryCard').classList.add('d-none');
            return;
        }

        fetch(`/sales/student-summary/${studentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const card = document.getElementById('studentSummaryCard');
                    const coursesDiv = document.getElementById('summaryCourses');
                    const invoicesDiv = document.getElementById('summaryInvoices');
                    const debtEl = document.getElementById('summaryDebt');
                    const statusEl = document.getElementById('summaryStatus');
                    const profileLink = document.getElementById('summaryProfileLink');

                    card.classList.remove('d-none');
                    debtEl.innerText = data.total_debt + ' ' + currency;
                    profileLink.href = `/students/${studentId}`;

                    // Subscription Status
                    if (data.student.status === 'active') {
                        statusEl.innerText = 'حساب نشط';
                        statusEl.className = 'badge bg-success bg-opacity-10 text-success rounded-pill px-3';
                    } else {
                        statusEl.innerText = 'حساب غير نشط/منتهي';
                        statusEl.className = 'badge bg-danger bg-opacity-10 text-danger rounded-pill px-3';
                    }

                    // Courses
                    coursesDiv.innerHTML = '<div class="fw-bold mb-1 small text-dark"><i class="fas fa-book me-1"></i> الكورسات الحالية:</div>';
                    if (data.courses.length > 0) {
                        data.courses.forEach(c => {
                            coursesDiv.innerHTML += `<div class="ms-2">• ${c.title} <span class="badge bg-light text-dark py-0 small">${c.status}</span></div>`;
                        });
                    } else {
                        coursesDiv.innerHTML += '<div class="ms-2">لا يوجد كورسات حالياً</div>';
                    }

                    // Unpaid Invoices
                    invoicesDiv.innerHTML = '<div class="fw-bold mb-1 small text-danger"><i class="fas fa-exclamation-circle me-1"></i> مديونيات سابقة:</div>';
                    if (data.unpaid_invoices.length > 0) {
                        data.unpaid_invoices.forEach(inv => {
                            invoicesDiv.innerHTML += `
                                <div class="d-flex justify-content-between align-items-center mb-2 ms-2 p-2 bg-white rounded-3 border">
                                    <span class="small">فاتورة #${inv.id} (${inv.remaining.toFixed(2)})</span>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-xs btn-success py-0 px-2 small rounded-pill" onclick="quickPay(${inv.id}, ${inv.remaining}, ${studentId})">سداد</button>
                                        <a href="/sales/${inv.id}" target="_blank" class="btn btn-xs btn-light py-0 px-2 small rounded-pill border">عرض</a>
                                    </div>
                                </div>`;
                        });
                    } else {
                        invoicesDiv.innerHTML += '<div class="ms-2">لا يوجد مديونيات سابقة</div>';
                    }
                }
            });
    }

    function quickPay(saleId, remaining, studentId) {
        Swal.fire({
            title: 'تحصيل دفعة - فاتورة #' + saleId,
            text: 'المبلغ المتبقي: ' + remaining.toFixed(2) + ' ' + currency,
            input: 'number',
            inputAttributes: {
                min: 0.01,
                max: remaining,
                step: 0.01
            },
            inputValue: remaining,
            showCancelButton: true,
            confirmButtonText: 'تأكيد التحصيل',
            cancelButtonText: 'إلغاء',
            showLoaderOnConfirm: true,
            preConfirm: (amount) => {
                const data = {
                    amount: amount,
                    payment_method: 'cash', // Default to cash for quick pay
                    notes: 'تحصيل سريع من شاشة المبيعات',
                    _token: '{{ csrf_token() }}'
                };
                return fetch(`/sales/${saleId}/payment`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) throw new Error(response.statusText);
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(`فشل الطلب: ${error}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ icon: 'success', title: 'تم التحصيل بنجاح' });
                fetchStudentSummary(studentId); // Refresh summary
            }
        });
    }

    function addToCart(id, title, price) {
        const existing = cart.find(item => item.id === id);
        if (existing) {
            Swal.fire({
                icon: 'info',
                text: '{{ __('center::sales.item_already_in_cart') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }

        cart.push({ id, title, price });
        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cartItems');
        const emptyMsg = document.getElementById('emptyCartMsg');
        const totalEl = document.getElementById('totalAmount');
        const paidInput = document.getElementById('paid_amount');

        container.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            emptyMsg.style.display = 'block';
            container.appendChild(emptyMsg);
        } else {
            emptyMsg.style.display = 'none';
            cart.forEach((item, index) => {
                total += item.price;
                container.innerHTML += `
                    <div class="d-flex justify-content-between align-items-center mb-3 bg-white p-2 rounded-3 border">
                        <div class="ps-2">
                            <h6 class="mb-0 fw-bold small">${item.title}</h6>
                            <span class="text-primary fw-bold small">${item.price.toFixed(2)} ${currency}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle p-2" onclick="removeFromCart(${index})">
                            <i class="fas fa-trash fa-xs"></i>
                        </button>
                    </div>
                `;
            });
        }

        totalEl.innerText = total.toFixed(2) + ' ' + currency;
        paidInput.value = total.toFixed(2);
    }

    function submitSale() {
        const studentId = document.getElementById('student_id').value;
        const paymentMethod = document.getElementById('payment_method').value;
        const paidAmount = document.getElementById('paid_amount').value;
        const notes = document.getElementById('notes').value;

        if (!studentId) {
            Swal.fire({ icon: 'error', text: '{{ __('center::sales.please_select_student') }}' });
            return;
        }
        if (cart.length === 0) {
            Swal.fire({ icon: 'error', text: '{{ __('center::sales.empty_cart') }}' });
            return;
        }

        const data = {
            student_id: studentId,
            items: cart,
            payment_method: paymentMethod,
            paid_amount: paidAmount,
            notes: notes,
            _token: '{{ csrf_token() }}'
        };

        Swal.fire({
            title: 'جاري الحفظ...',
            didOpen: () => { Swal.showLoading(); }
        });

        fetch('{{ route("center.sales.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'تمت العملية!',
                    text: 'تم تسجيل عملية البيع وتنسيب الطالب للكورسات المختارة بنجاح.',
                    confirmButtonText: 'حسناً'
                }).then(() => {
                    window.location.href = '{{ route("center.sales.index") }}';
                });
            } else {
                Swal.fire({ icon: 'error', text: 'خطأ: ' + (data.message || 'لم ينجح الحفظ') });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({ icon: 'error', text: 'حدث خطأ أثناء الاتصال بالخادم' });
        });
    }
</script>
@endpush
@endsection
