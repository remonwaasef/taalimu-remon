@extends('center::layouts.hope-master')

@section('page-title', __('center::messages.blade_0558'))
@section('page-subtitle', __('center::messages.blade_0559'))

@section('content')

<div class="row g-4">
    <!-- Student Header Dashboard (Hidden until search) -->
    <div id="studentHeader" class="col-lg-12 d-none animate__animated animate__fadeIn">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white overflow-hidden">
            <div class="card-body p-4 position-relative">
                <div class="position-absolute top-0 end-0 p-4 opacity-10">
                    <i class="fas fa-user-graduate fa-6x"></i>
                </div>
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-lg" style="width: 80px; height: 80px; font-size: 2rem;">
                            <span id="studentInitial">S</span>
                        </div>
                    </div>
                    <div class="col">
                        <h2 id="studentNameDisplay" class="fw-bold mb-1">Student Name</h2>
                        <div class="d-flex gap-3 small opacity-75">
                            <span><i class="fas fa-phone-alt me-1"></i> <span id="studentPhoneDisplay">00000000</span></span>
                            <span><i class="fas fa-graduation-cap me-1"></i> <span id="studentGradeDisplay">Grade</span></span>
                            <span><i class="fas fa-circle me-1" id="statusDot"></i> <span id="studentStatusDisplay">Status</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards (Hidden until search) -->
    <div id="statsSection" class="col-lg-12 d-none">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 animate__animated animate__fadeInUp">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-4 p-3"><i class="fas fa-money-bill-wave fa-lg"></i></div>
                        <div>
                            <div class="text-muted small fw-bold">{{ __('center::messages.blade_0562') }}</div>
                            <h4 id="debtStat" class="mb-0 fw-bold text-danger">0.00</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-4 p-3"><i class="fas fa-check-circle fa-lg"></i></div>
                        <div>
                            <div class="text-muted small fw-bold">{{ __('center::messages.blade_0582', ['default' => 'إجمالي المسدد']) }}</div>
                            <h4 id="paidStat" class="mb-0 fw-bold text-success">0.00</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-info bg-opacity-10 text-info rounded-4 p-3"><i class="fas fa-calendar-check fa-lg"></i></div>
                        <div>
                            <div class="text-muted small fw-bold">{{ __('center::messages.blade_0583', ['default' => 'نسبة الحضور']) }}</div>
                            <h4 id="attendanceStat" class="mb-0 fw-bold text-info">0%</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-4 p-3"><i class="fas fa-book-open fa-lg"></i></div>
                        <div>
                            <div class="text-muted small fw-bold">{{ __('center::messages.blade_0584', ['default' => 'الكورسات المشتركة']) }}</div>
                            <h4 id="coursesStat" class="mb-0 fw-bold text-warning">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Selector -->
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <label class="form-label fw-bold text-dark mb-3"><i class="fas fa-search me-1 text-primary"></i>{{ __('center::messages.blade_0560') }}</label>
                        <select id="studentSelector" class="form-select form-select-lg" placeholder="{{ __('center::messages.blade_0561') }}">
                            <option value="">{{ __('center::messages.blade_0561') }}</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} | {{ $student->phone }} | {{ $student->code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs (Hidden initially) -->
    <div id="accountTabs" class="col-lg-12 d-none animate__animated animate__fadeIn">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 p-0">
                <ul class="nav nav-tabs nav-justified border-bottom" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#financialsTab" type="button"><i class="fas fa-wallet me-2"></i>{{ __('center::messages.blade_0585', ['default' => 'الماليات والتحصيل']) }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#coursesTab" type="button"><i class="fas fa-graduation-cap me-2"></i>{{ __('center::messages.blade_0586', ['default' => 'الكورسات والاشتراكات']) }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#attendanceTab" type="button"><i class="fas fa-user-check me-2"></i>{{ __('center::messages.blade_0587', ['default' => 'سجل الحضور']) }}</button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content" id="myTabContent">
                    <!-- Financials Tab -->
                    <div class="tab-pane fade show active" id="financialsTab">
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3 d-flex align-items-center">
                                <span class="bg-danger bg-opacity-10 text-danger rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width:35px; height:35px;"><i class="fas fa-exclamation-circle small"></i></span>
                                {{ __('center::messages.blade_0567') }}
                            </h5>
                            <div id="unpaidInvoicesList">
                                <!-- Dynamic dynamic -->
                            </div>
                        </div>
                    </div>

                    <!-- Courses Tab -->
                    <div class="tab-pane fade" id="coursesTab">
                        <div id="coursesListSection" class="row g-3">
                            <!-- Dynamic dynamic -->
                        </div>
                    </div>

                    <!-- Attendance Tab -->
                    <div class="tab-pane fade" id="attendanceTab">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>{{ __('center::messages.blade_0677', ['default' => 'التاريخ']) }}</th>
                                        <th>{{ __('center::messages.blade_0650', ['default' => 'الكورس']) }}</th>
                                        <th>{{ __('center::messages.blade_0434', ['default' => 'الحالة']) }}</th>
                                    </tr>
                                </thead>
                                <tbody id="attendanceTableBody">
                                    <!-- Dynamic dynamic -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="col-lg-12 text-center py-5">
        <div class="bg-white rounded-4 shadow-sm p-5 d-inline-block" style="max-width: 500px;">
            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 100px; height: 100px;">
                <i class="fas fa-user-search fa-3x text-muted"></i>
            </div>
            <h4 class="fw-bold text-dark">{{ __('center::messages.blade_0569') }}</h4>
            <p class="text-muted mb-0">{{ __('center::messages.blade_0570') }}</p>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .ts-control { border-radius: 1rem !important; padding: 0.75rem 1.25rem !important; border: 1px solid #eee !important; transition: all 0.3s; }
    .ts-control:focus { border-color: var(--bs-primary) !important; box-shadow: 0 0 0 0.25rem rgba(5, 150, 105, 0.1) !important; }
    .nav-tabs .nav-link { padding: 1.25rem; border: none; font-weight: 600; color: #64748b; border-bottom: 3px solid transparent; }
    .nav-tabs .nav-link.active { color: var(--bs-primary); border-bottom-color: var(--bs-primary); background: transparent; }
    .transition-all { transition: all 0.3s ease; }
    .hover-shadow:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; border-color: var(--bs-primary) !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const currency = '{{ __('center::sales.currency') }}';
    let studentSelector;

    document.addEventListener('DOMContentLoaded', function() {
        studentSelector = new TomSelect('#studentSelector', {
            sortField: { field: "text", direction: "asc" },
            onChange: function(value) { loadStudentAccount(value); }
        });

        // Load if ID in URL
        const urlParams = new URLSearchParams(window.location.search);
        const studentId = urlParams.get('student_id');
        if (studentId) {
            studentSelector.setValue(studentId);
        }
    });

    function loadStudentAccount(studentId) {
        if (!studentId) {
            document.querySelectorAll('#studentHeader, #statsSection, #accountTabs').forEach(el => el.classList.add('d-none'));
            document.getElementById('emptyState').classList.remove('d-none');
            return;
        }

        document.getElementById('emptyState').classList.add('d-none');
        document.querySelectorAll('#studentHeader, #statsSection, #accountTabs').forEach(el => el.classList.remove('d-none'));

        fetch(`/sales/student-summary/${studentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const s = data.student;
                    
                    // Update Header
                    document.getElementById('studentInitial').innerText = s.name.charAt(0).toUpperCase();
                    document.getElementById('studentNameDisplay').innerText = s.name;
                    document.getElementById('studentPhoneDisplay').innerText = s.phone;
                    document.getElementById('studentGradeDisplay').innerText = s.grade || '-';
                    document.getElementById('studentStatusDisplay').innerText = s.status === 'active' ? 'نشط' : 'غير نشط';
                    document.getElementById('statusDot').className = `fas fa-circle me-1 ${s.status === 'active' ? 'text-success' : 'text-danger'}`;
                    
                    // Update Stats
                    document.getElementById('debtStat').innerText = data.stats.total_debt + ' ' + currency;
                    document.getElementById('paidStat').innerText = data.stats.total_paid + ' ' + currency;
                    document.getElementById('attendanceStat').innerText = data.stats.attendance_rate + '%';
                    document.getElementById('coursesStat').innerText = data.stats.course_count;

                    // Update Financials Tab
                    const invoiceList = document.getElementById('unpaidInvoicesList');
                    invoiceList.innerHTML = '';
                    if (data.unpaid_invoices.length > 0) {
                        data.unpaid_invoices.forEach(inv => {
                            invoiceList.innerHTML += `
                                <div class="bg-white border rounded-4 p-3 mb-3 d-flex justify-content-between align-items-center hover-shadow transition-all shadow-sm">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                                            <i class="fas fa-file-invoice"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0">{{ __('center::sales.invoice_id_prefix') }}${inv.id}</div>
                                            <div class="x-small text-muted">${inv.created_at.split('T')[0]}</div>
                                            <div class="text-danger fw-bold small mt-1">${inv.remaining.toFixed(2)} ${currency}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="/sales/${inv.id}" target="_blank" class="btn btn-outline-light text-dark btn-sm rounded-pill px-3 border shadow-none"><i class="fas fa-eye me-1 text-primary"></i> {{ __('center::messages.blade_0573') }}</a>
                                    </div>
                                </div>`;
                        });
                    } else {
                        invoiceList.innerHTML = `<div class="text-center py-4 text-muted"><i class="fas fa-check-circle text-success me-2"></i>{{ __('center::messages.blade_0574') }}</div>`;
                    }

                    // Update Courses Tab
                    const coursesGrid = document.getElementById('coursesListSection');
                    coursesGrid.innerHTML = '';
                    if (data.courses.length > 0) {
                        data.courses.forEach(c => {
                            coursesGrid.innerHTML += `
                                <div class="col-md-6">
                                    <div class="card border border-light-subtle shadow-none rounded-4 bg-light bg-opacity-25">
                                        <div class="card-body p-3">
                                            <h6 class="fw-bold text-dark mb-1">${c.title}</h6>
                                            <div class="small text-muted mb-2">تاريخ الاشتراك: ${c.enrolled_at || '-'}</div>
                                            <span class="badge bg-primary rounded-pill px-3">${c.status}</span>
                                        </div>
                                    </div>
                                </div>`;
                        });
                    } else {
                        coursesGrid.innerHTML = `<div class="col-12 text-center py-4 text-muted">{{ __('center::messages.blade_0571') }}</div>`;
                    }

                    // Update Attendance Tab
                    const attTable = document.getElementById('attendanceTableBody');
                    attTable.innerHTML = '';
                    if (data.recent_attendance.length > 0) {
                        data.recent_attendance.forEach(a => {
                            let badgeClass = 'bg-secondary';
                            if(a.status === 'present') badgeClass = 'bg-success';
                            if(a.status === 'absent') badgeClass = 'bg-danger';
                            
                            attTable.innerHTML += `
                                <tr>
                                    <td class="fw-bold">${a.date}</td>
                                    <td>${a.course}</td>
                                    <td><span class="badge ${badgeClass} rounded-pill px-3">${a.status}</span></td>
                                </tr>`;
                        });
                    } else {
                        attTable.innerHTML = `<tr><td colspan="3" class="text-center py-4 text-muted">{{ __('center::messages.blade_0571') }}</td></tr>`;
                    }
                }
            });
    }

</script>
@endpush
@endsection
