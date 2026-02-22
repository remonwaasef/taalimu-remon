@extends('center::layouts.master')

@section('title', __('center::messages.blade_0616'))

@section('content')
<div class="mb-4 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
            <i class="fas fa-user-invoice fa-lg"></i>
        </div>
        <div>
            <h2 class="fw-bold text-dark mb-0">{{ __('center::messages.blade_0598') }}</h2>
            <p class="text-muted small mb-0">{{ __('center::messages.blade_0599') }}</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Student Selector -->
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="row align-items-end g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold text-dark"><i class="fas fa-search me-1"></i>{{ __('center::messages.blade_0600') }}</label>
                        <select id="studentSelector" class="form-select form-select-lg rounded-pill shadow-none border @if(app()->getLocale() == 'ar') text-end @endif" onchange="loadStudentAccount(this.value)">
                            <option value="">{{ __('center::messages.blade_0601') }}</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->phone }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div id="quickStats" class="d-none animate__animated animate__fadeIn">
                            <div class="bg-danger bg-opacity-10 rounded-pill p-3 border border-danger border-opacity-25 d-flex justify-content-between align-items-center">
                                <span class="text-danger fw-bold">{{ __('center::messages.blade_0602') }}</span>
                                <h4 id="totalDebtDisplay" class="mb-0 fw-bold text-danger">0.00</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Details (Hidden initially) -->
    <div id="accountDashboard" class="col-lg-12 d-none">
        <div class="row g-4">
            <!-- Courses List -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-book me-2"></i>{{ __('center::messages.blade_0603') }}</h5>
                        <span id="courseCount" class="badge bg-primary rounded-pill px-3">0</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 ps-4">{{ __('center::messages.blade_0604') }}</th>
                                        <th class="border-0">{{ __('center::messages.blade_0605') }}</th>
                                        <th class="border-0">{{ __('center::messages.blade_0606') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="coursesTableBody">
                                    <!-- Dynamic rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoices & Collections -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold mb-0 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>{{ __('center::messages.blade_0607') }}</h5>
                    </div>
                    <div class="card-body p-4" id="invoicesList">
                        <!-- Dynamic content -->
                    </div>
                    <div class="card-footer bg-white border-0 py-3 text-center">
                        <a id="fullProfileBtn" href="#" class="btn btn-light rounded-pill px-4 border">
                            <i class="fas fa-user-circle me-1"></i>{{ __('center::messages.blade_0608') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="col-lg-12 text-center py-5">
        <div class="bg-white rounded-4 shadow-sm p-5 d-inline-block" style="max-width: 500px;">
            <i class="fas fa-user-check fa-4x text-light mb-4"></i>
            <h4 class="fw-bold text-dark">{{ __('center::messages.blade_0609') }}</h4>
            <p class="text-muted">{{ __('center::messages.blade_0610') }}</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const currency = '{{ __('center::sales.currency') }}';

    function loadStudentAccount(studentId) {
        if (!studentId) {
            document.getElementById('accountDashboard').classList.add('d-none');
            document.getElementById('emptyState').classList.remove('d-none');
            document.getElementById('quickStats').classList.add('d-none');
            return;
        }

        // Show loading state or elements
        document.getElementById('emptyState').classList.add('d-none');
        document.getElementById('accountDashboard').classList.remove('d-none');
        document.getElementById('quickStats').classList.remove('d-none');

        fetch(`/sales/student-summary/${studentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update stats
                    document.getElementById('totalDebtDisplay').innerText = data.total_debt + ' ' + currency;
                    document.getElementById('fullProfileBtn').href = `/students/${studentId}`;

                    // Update Courses
                    const tableBody = document.getElementById('coursesTableBody');
                    tableBody.innerHTML = '';
                    document.getElementById('courseCount').innerText = data.courses.length;
                    
                    if (data.courses.length > 0) {
                        data.courses.forEach(c => {
                            tableBody.innerHTML += `
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">${c.title}</td>
                                    <td class="text-muted">${c.enrolled_at || '-'}</td>
                                    <td><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">${c.status}</span></td>
                                </tr>`;
                        });
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-muted">{{ __('center::messages.blade_0611') }}</td></tr>';
                    }

                    // Update Invoices
                    const list = document.getElementById('invoicesList');
                    list.innerHTML = '';
                    if (data.unpaid_invoices.length > 0) {
                        data.unpaid_invoices.forEach(inv => {
                            list.innerHTML += `
                                <div class="bg-white border rounded-4 p-3 mb-3 d-flex justify-content-between align-items-center hover-shadow transition-all">
                                    <div>
                                        <div class="fw-bold text-dark mb-1">فاتورة #${inv.id}</div>
                                        <div class="small text-muted">تاريخ: ${inv.created_at.split('T')[0]}</div>
                                        <div class="text-danger fw-bold mt-1">${inv.remaining.toFixed(2)} ${currency}</div>
                                    </div>
                                    <div class="d-flex flex-column gap-2">
                                        <button class="btn btn-success btn-sm rounded-pill px-3" onclick="collectDebt(${inv.id}, ${inv.remaining}, ${studentId})">{{ __('center::messages.blade_0612') }}</button>
                                        <a href="/sales/${inv.id}" target="_blank" class="btn btn-outline-light text-dark btn-sm rounded-pill px-3 border small">{{ __('center::messages.blade_0613') }}</a>
                                    </div>
                                </div>`;
                        });
                    } else {
                        list.innerHTML = `
                            <div class="text-center py-4">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-check fa-2x"></i>
                                </div>
                                <h6 class="fw-bold text-success">{{ __('center::messages.blade_0614') }}</h6>
                                <p class="text-muted small mb-0">{{ __('center::messages.blade_0615') }}</p>
                            </div>`;
                    }
                }
            });
    }

    function collectDebt(saleId, remaining, studentId) {
        Swal.fire({
            title: __('center::messages.blade_0617'),
            text: 'الفاتورة #' + saleId + ' | المتبقي: ' + remaining.toFixed(2) + ' ' + currency,
            input: 'number',
            inputAttributes: { min: 0.01, max: remaining, step: 0.01 },
            inputValue: remaining,
            showCancelButton: true,
            confirmButtonText: __('center::messages.blade_0618'),
            cancelButtonText: __('center::messages.blade_0619'),
            showLoaderOnConfirm: true,
            preConfirm: (amount) => {
                const data = {
                    amount: amount,
                    payment_method: 'cash',
                    notes: __('center::messages.blade_0620'),
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
                .catch(error => { Swal.showValidationMessage(`فشل العملية: ${error}`); });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ icon: 'success', title: __('center::messages.blade_0621') });
                loadStudentAccount(studentId);
            }
        });
    }
</script>

<style>
    .transition-all { transition: all 0.3s ease; }
    .hover-shadow:hover { box-shadow: 0 10px 20px rgba(0,0,0,0.05); border-color: var(--bs-primary) !important; }
    .nav-tabs .nav-link { border: none; font-weight: 600; color: #64748b; padding: 1rem 1.5rem; }
    .nav-tabs .nav-link.active { color: var(--bs-primary); border-bottom: 3px solid var(--bs-primary); background: transparent; }
</style>
@endpush
@endsection
