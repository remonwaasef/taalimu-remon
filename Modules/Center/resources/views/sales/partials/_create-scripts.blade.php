<script>
    $(document).ready(function() {
        if ($.fn.select2) {
            $('#student_id').select2({
                ajax: {
                    url: '{{ route("center.sales.lookup") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name + ' (' + item.phone + ')',
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                },
                placeholder: '{{ __("center::sales.select_student") }}',
                minimumInputLength: 1
            }).on('select2:select', function (e) {
                fetchStudentSummary(e.params.data.id);
            });
        }
    });

    let cart = [];
    let subtotalAmount = 0;
    let installmentMode = false;
    const currency = '{{ get_currency_symbol() }}';

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

                    const status = data.student.status;
                    if (status === 'active' || status === 'verified') {
                        statusEl.innerText = '{{ __('center::sales.status_paid') }} / {{ __('center::sales.status_active') }}';
                        statusEl.className = 'badge bg-success bg-opacity-10 text-success rounded-pill px-3';
                    } else if (status === 'pending' || status === 'new') {
                        statusEl.innerText = '{{ __('center::sales.status_new_pending') }}';
                        statusEl.className = 'badge bg-warning bg-opacity-10 text-warning rounded-pill px-3';
                    } else {
                        statusEl.innerText = '{{ __('center::sales.status_inactive_expired') }}';
                        statusEl.className = 'badge bg-danger bg-opacity-10 text-danger rounded-pill px-3';
                    }

                    coursesDiv.innerHTML = '<div class="fw-bold mb-1 small text-dark"><i class="fas fa-book me-1"></i>{{ __('center::sales.enrolled_courses') }}</div>';
                    if (data.courses.length > 0) {
                        data.courses.forEach(c => {
                            coursesDiv.innerHTML += `<div class="ms-2">• ${c.title} <span class="badge bg-light text-dark py-0 small">${c.status}</span></div>`;
                        });
                    } else {
                        coursesDiv.innerHTML += '<div class="ms-2">{{ __('center::sales.no_courses') }}</div>';
                    }

                    invoicesDiv.innerHTML = '<div class="fw-bold mb-1 small text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ __('center::sales.unpaid_invoices_list') }}</div>';
                    if (data.unpaid_invoices.length > 0) {
                        data.unpaid_invoices.forEach(inv => {
                            invoicesDiv.innerHTML += `
                                <div class="d-flex justify-content-between align-items-center mb-2 ms-2 p-2 bg-white rounded-3 border">
                                    <span class="small">{{ __('center::sales.invoice_id') }}{{ inv.id }} (${inv.remaining.toFixed(2)})</span>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-xs btn-success py-0 px-2 small rounded-pill" onclick="quickPay(${inv.id}, ${inv.remaining}, ${studentId})">{{ __('center::sales.pay') }}</button>
                                        <a href="/sales/${inv.id}" target="_blank" class="btn btn-xs btn-light py-0 px-2 small rounded-pill border">{{ __('center::sales.view') }}</a>
                                    </div>
                                </div>`;
                        });
                    } else {
                        invoicesDiv.innerHTML += '<div class="ms-2">{{ __('center::sales.no_outstanding_debts') }}</div>';
                    }
                }
            });
    }

    function quickPay(saleId, remaining, studentId) {
        Swal.fire({
            title: '{{ __('center::sales.pay') }} - {{ __('center::sales.invoice_id') }}' + saleId,
            text: 'هل أنت متأكد من تحصيل المبلغ المتبقي؟ ' + remaining.toFixed(2) + ' ' + currency,
            input: 'number',
            inputAttributes: {
                min: 0.01,
                max: remaining,
                step: 0.01
            },
            inputValue: remaining,
            showCancelButton: true,
            confirmButtonText: '{{ __('center::sales.confirm_pay_now_btn') }}',
            cancelButtonText: '{{ __('center::sales.cancel') }}',
            showLoaderOnConfirm: true,
            preConfirm: (amount) => {
                const data = {
                    amount: amount,
                    payment_method: 'cash',
                    notes: '{{ __('center::sales.quick_pay_notes') }}',
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
                    Swal.showValidationMessage(`{{ __('center::sales.request_failed') }} ${error}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ icon: 'success', title: '{{ __('center::sales.success') }}' });
                fetchStudentSummary(studentId);
            }
        });
    }

    function addToCart(id, title, price, sessionsCount) {
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

        cart.push({ id, title, price, sessionsCount: sessionsCount || 0 });
        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function getTotalSessions() {
        let totalSessions = 0;
        cart.forEach(item => {
            if (item.sessionsCount > 0) totalSessions += item.sessionsCount;
        });
        return totalSessions;
    }

    function hasSessionCourses() {
        return cart.some(item => item.sessionsCount > 0);
    }

    function toggleInstallment() {
        installmentMode = document.getElementById('installmentToggle').checked;
        document.getElementById('installmentDetails').classList.toggle('d-none', !installmentMode);
        calculateTotal();
    }

    function updateInstallmentInfo(total) {
        const section = document.getElementById('installmentSection');
        if (hasSessionCourses()) {
            section.classList.remove('d-none');
        } else {
            section.classList.add('d-none');
            installmentMode = false;
            document.getElementById('installmentToggle').checked = false;
            document.getElementById('installmentDetails').classList.add('d-none');
            return;
        }

        if (installmentMode && total > 0) {
            const totalSessions = getTotalSessions();
            const perSession = total / totalSessions;
            document.getElementById('installmentSessions').innerText = totalSessions + ' {{ __('center::sales.session') }}';
            document.getElementById('installmentPerSession').innerText = perSession.toFixed(2) + ' ' + currency;
            document.getElementById('installmentRemaining').innerText = (total - perSession).toFixed(2) + ' ' + currency;
        }
    }

    function renderCart() {
        const container = document.getElementById('cartItems');
        const emptyMsg = document.getElementById('emptyCartMsg');

        container.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            emptyMsg.style.display = 'block';
            container.appendChild(emptyMsg);
        } else {
            emptyMsg.style.display = 'none';
            cart.forEach((item, index) => {
                total += item.price;
                let sessionInfo = '';
                if (item.sessionsCount > 0) {
                    const perSession = (item.price / item.sessionsCount).toFixed(2);
                    sessionInfo = `<div class="text-muted" style="font-size: 0.7rem;"><i class="fas fa-layer-group me-1"></i>${item.sessionsCount} {{ __('center::sales.session') }} (${perSession} ${currency}/{{ __('center::sales.session') }})</div>`;
                }
                container.innerHTML += `
                    <div class="d-flex justify-content-between align-items-center mb-3 bg-white p-2 rounded-3 border">
                        <div class="ps-2">
                            <h6 class="mb-0 fw-bold small">${item.title}</h6>
                            <span class="text-primary fw-bold small">${item.price.toFixed(2)} ${currency}</span>
                            ${sessionInfo}
                        </div>
                        <button type="button" class="btn btn-sm btn-light text-danger rounded-circle p-2" onclick="removeFromCart(${index})">
                            <i class="fas fa-trash fa-xs"></i>
                        </button>
                    </div>
                `;
            });
        }

        subtotalAmount = total;
        document.getElementById('subtotalAmountDisp').innerText = subtotalAmount.toFixed(2) + ' ' + currency;
        calculateTotal();
    }

    function calculateTotal() {
        const discountInput = document.getElementById('discount_amount');
        const taxInput = document.getElementById('tax_amount');
        const totalEl = document.getElementById('totalAmount');
        const paidInput = document.getElementById('paid_amount');

        let discount = parseFloat(discountInput.value) || 0;
        let tax = parseFloat(taxInput.value) || 0;

        let total = subtotalAmount - discount + tax;
        if (total < 0) total = 0;

        totalEl.innerText = total.toFixed(2) + ' ' + currency;

        // Update installment info
        updateInstallmentInfo(total);

        // Set paid amount based on installment mode
        if (installmentMode && hasSessionCourses() && total > 0) {
            const totalSessions = getTotalSessions();
            const perSession = total / totalSessions;
            paidInput.value = perSession.toFixed(2);
        } else {
            paidInput.value = total.toFixed(2);
        }
    }

    function submitSale() {
        const studentId = document.getElementById('student_id').value;
        const paymentMethod = document.getElementById('payment_method').value;
        const paidAmount = document.getElementById('paid_amount').value;
        const discountAmount = document.getElementById('discount_amount').value;
        const taxAmount = document.getElementById('tax_amount').value;
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
            discount_amount: discountAmount,
            tax_amount: taxAmount,
            notes: notes,
            _token: '{{ csrf_token() }}'
        };

        Swal.fire({
            title: '{{ __('center::sales.saving') }}',
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
                    title: '{{ __('center::sales.success') }}',
                    text: '{{ __('center::sales.sale_recorded_success') }}',
                    confirmButtonText: '{{ __('center::sales.ok') }}'
                }).then(() => {
                    window.location.href = '{{ route("center.sales.index") }}';
                });
            } else {
                Swal.fire({ icon: 'error', text: '{{ __('center::sales.save_failed') }} ' + (data.message || '{{ __('center::sales.unknown_error') }}') });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({ icon: 'error', text: '{{ __('center::sales.unexpected_error') }}' });
        });
    }
</script>
