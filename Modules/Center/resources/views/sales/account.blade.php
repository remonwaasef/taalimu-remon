@extends('center::layouts.hope-master')

@section('page-title', __('center::messages.blade_0558'))
@section('page-subtitle', __('center::messages.blade_0559'))

@section('content')

<div class="row g-4">
    <!-- Student Header Dashboard (Hidden until search) -->
    <div id="studentHeader" class="col-lg-12 d-none animate__animated animate__fadeIn">
        <div class="card border-0 shadow-sm rounded-4 text-white overflow-hidden" style="background: linear-gradient(135deg, #059669 0%, #0d9488 100%);">
            <div class="card-body p-4 position-relative">
                <div class="position-absolute top-0 end-0 p-4 opacity-10">
                    <i class="fas fa-user-graduate fa-6x"></i>
                </div>
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-lg" style="width: 90px; height: 90px; font-size: 2.5rem;">
                            <span id="studentInitial">S</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center gap-3 mb-1">
                            <h2 id="studentNameDisplay" class="fw-bold mb-0">Student Name</h2>
                            <span id="studentStatusBadge" class="badge bg-white bg-opacity-25 rounded-pill px-3">Status</span>
                        </div>
                        <div class="d-flex flex-wrap gap-4 small opacity-75 mb-3">
                            <span><i class="fas fa-phone-alt me-1"></i> <span id="studentPhoneDisplay">00000000</span></span>
                            <span><i class="fas fa-graduation-cap me-1"></i> <span id="studentGradeDisplay">Grade</span></span>
                            <span><i class="fas fa-id-card me-1"></i> ID: <span id="studentIdDisplay">#0</span></span>
                        </div>
                        <div class="d-flex gap-2">
                            <a id="whatsappBtn" href="#" target="_blank" class="btn btn-white btn-sm rounded-pill px-3 fw-bold text-success shadow-sm">
                                <i class="fab fa-whatsapp me-1"></i> واتساب
                            </a>
                            <a id="callBtn" href="#" class="btn btn-white btn-sm rounded-pill px-3 fw-bold text-primary shadow-sm">
                                <i class="fas fa-phone-alt me-1"></i> اتصال
                            </a>
                            <button onclick="downloadStatement()" class="btn btn-white btn-sm rounded-pill px-3 fw-bold text-danger shadow-sm">
                                <i class="fas fa-file-pdf me-1"></i> كشف حساب
                            </button>
                            <button id="quickPayBtn" onclick="openQuickPayModal()" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold shadow-sm d-none">
                                <i class="fas fa-bolt me-1"></i> {{ __('center::sales.quick_pay') }}
                            </button>
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
                <div class="card border-0 shadow-sm rounded-4 animate__animated animate__fadeInUp bg-white">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-2"><i class="fas fa-money-bill-wave"></i></div>
                            <div class="text-muted small fw-bold">{{ __('center::messages.blade_0562') }}</div>
                        </div>
                        <h3 id="debtStat" class="mb-0 fw-bold text-danger">0.00</h3>
                        <div class="mt-2 small text-muted"><i class="fas fa-info-circle me-1"></i> مبالغ لم يتم تسويتها</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 animate__animated animate__fadeInUp bg-white" style="animation-delay: 0.1s;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-2"><i class="fas fa-check-circle"></i></div>
                            <div class="text-muted small fw-bold">إجمالي المسدد</div>
                        </div>
                        <h3 id="paidStat" class="mb-0 fw-bold text-success">0.00</h3>
                        <div class="mt-2 small text-muted"><i class="fas fa-arrow-up text-success me-1"></i> تحصيلات نقدية/بنكية</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 animate__animated animate__fadeInUp bg-white" style="animation-delay: 0.2s;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="bg-info bg-opacity-10 text-info rounded-3 p-2"><i class="fas fa-calendar-check"></i></div>
                            <div class="text-muted small fw-bold">نسبة الحضور</div>
                        </div>
                        <h3 id="attendanceStat" class="mb-0 fw-bold text-info">0%</h3>
                        <div class="progress mt-3" style="height: 6px;">
                            <div id="attendanceProgress" class="progress-bar bg-info" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 animate__animated animate__fadeInUp bg-white" style="animation-delay: 0.3s;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="bg-purple bg-opacity-10 text-purple rounded-3 p-2" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;"><i class="fas fa-book-open"></i></div>
                            <div class="text-muted small fw-bold">الدورات النشطة</div>
                        </div>
                        <h3 id="coursesStat" class="mb-0 fw-bold" style="color: #8b5cf6;">0</h3>
                        <div class="mt-2 small text-muted"><i class="fas fa-graduation-cap me-1"></i> دورات مسجل بها الطالب</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Developed Student Selector -->
    <div class="col-lg-12">
        <div class="card border-0 shadow rounded-4 overlay-container overflow-hidden search-bar-container">
            <div class="card-body p-1">
                <div class="input-group input-group-lg search-input-group">
                    <span class="input-group-text bg-transparent border-0 ps-4 pe-2 text-muted">
                        <i class="fas fa-search"></i>
                    </span>
                    <select id="studentSelector" class="form-select border-0 shadow-none border-radius-0" placeholder="ابدأ بكتابة اسم الطالب، رقم الهاتف، أو الكود..."></select>
                    <span class="input-group-text bg-transparent border-0 pe-4 ps-2 text-muted d-none d-md-flex">
                        <kbd class="bg-light border text-dark ms-2 opacity-50">/</kbd>
                    </span>
                </div>
            </div>
            <div id="searchProgressLine" class="position-absolute bottom-0 start-0 w-0 h-2 bg-primary transition-all d-none"></div>
        </div>
    </div>

    <!-- Main Content Tabs (Hidden initially) -->
    <div id="accountTabs" class="col-lg-12 d-none animate__animated animate__fadeIn">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 p-0">
                <ul class="nav nav-tabs nav-justified border-bottom" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-3" data-bs-toggle="tab" data-bs-target="#ledgerTab" type="button">
                            <i class="fas fa-history me-2"></i> السجل المالي (Timeline)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3" data-bs-toggle="tab" data-bs-target="#invoicesTab" type="button">
                            <i class="fas fa-file-invoice-dollar me-2"></i> المديونيات المعلقة
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3" data-bs-toggle="tab" data-bs-target="#coursesTab" type="button">
                            <i class="fas fa-graduation-cap me-2"></i> الدورات المسجلة
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3" data-bs-toggle="tab" data-bs-target="#attendanceTab" type="button">
                            <i class="fas fa-user-check me-2"></i> سجل الحضور
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4 position-relative card-content-area">
                <div id="tabsLoader" class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-none align-items-center justify-content-center" style="z-index: 10;">
                    <div class="text-center">
                        <div class="spinner-border text-primary mb-2" role="status"></div>
                        <div class="small fw-bold text-primary">جاري تحميل البيانات...</div>
                    </div>
                </div>

                <div class="tab-content" id="myTabContent">
                    <!-- Ledger Tab -->
                    <div class="tab-pane fade show active" id="ledgerTab">
                        <div class="timeline-container px-2">
                            <div id="ledgerTimeline">
                                <!-- Timeline items dynamic -->
                            </div>
                        </div>
                    </div>

                    <!-- Invoices Tab -->
                    <div class="tab-pane fade" id="invoicesTab">
                        <div id="unpaidInvoicesList">
                            <!-- Unpaid invoices dynamic -->
                        </div>
                    </div>

                    <!-- Courses Tab -->
                    <div class="tab-pane fade" id="coursesTab">
                        <div id="coursesListSection" class="row g-3">
                            <!-- Courses dynamic -->
                        </div>
                    </div>

                    <!-- Attendance Tab -->
                    <div class="tab-pane fade" id="attendanceTab">
                        <div class="table-responsive rounded-3 border">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">التاريخ</th>
                                        <th>الكورس</th>
                                        <th class="text-center">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody id="attendanceTableBody">
                                    <!-- Attendance dynamic -->
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
        <div class="bg-white rounded-4 shadow-sm p-5 d-inline-block border w-100" style="max-width: 600px;">
            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 120px; height: 120px;">
                <img src="https://cdni.iconscout.com/illustration/premium/thumb/searching-student-data-8703478-7058866.png" class="img-fluid opacity-75" style="max-width: 80px;" alt="">
            </div>
            <h4 class="fw-bold text-dark mb-2">ابدأ بالبحث عن طالب</h4>
            <p class="text-muted mb-4 mx-auto" style="max-width: 400px;">قم باختيار طالب من المحرك أعلاه لمشاهدة تفاصيل حسابه المالي، سجله الأكاديمي، وإدارة التحصيلات بسرعة وكفاءة.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <div class="small text-muted"><i class="fas fa-check-circle text-success me-1"></i> متابعة المديونيات</div>
                <div class="small text-muted"><i class="fas fa-check-circle text-success me-1"></i> سجل سداد كامل</div>
                <div class="small text-muted"><i class="fas fa-check-circle text-success me-1"></i> التحصيل السريع</div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    :root {
        --bs-primary: #059669;
        --bs-primary-rgb: 5, 150, 105;
    }
    
    .search-bar-container {
        border: 2px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #fff;
    }
    
    .search-bar-container:focus-within {
        border-color: var(--bs-primary);
        box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.15) !important;
        transform: translateY(-2px);
    }

    .ts-control { 
        border-radius: 0 !important; 
        padding: 1rem 0.5rem !important; 
        border: none !important; 
        box-shadow: none !important;
        font-size: 1.15rem !important;
        font-weight: 500;
        background: transparent !important;
    }
    
    .ts-wrapper.single .ts-control {
        min-height: 60px;
        display: flex;
        align-items: center;
    }
    
    .ts-dropdown {
        border-radius: 0 0 1rem 1rem !important;
        border: none !important;
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        padding: 0.5rem !important;
        margin-top: 5px !important;
    }
    
    .ts-dropdown .option {
        padding: 0.75rem 1rem !important;
        border-radius: 0.75rem !important;
        margin-bottom: 2px;
        transition: all 0.2s;
    }
    
    .ts-dropdown .active {
        background-color: rgba(5, 150, 105, 0.08) !important;
        color: var(--bs-primary) !important;
    }

    /* Result Rendering UI */
    .search-result-item { display: flex; align-items: center; gap: 12px; }
    .search-result-avatar { width: 40px; height: 40px; border-radius: 10px; background: rgba(5, 150, 105, 0.1); color: var(--bs-primary); display: flex; align-items: center; justify-content: center; font-weight: bold; }
    .search-result-info { flex: 1; }
    .search-result-name { font-weight: 600; font-size: 0.95rem; display: block; margin-bottom: 1px; }
    .search-result-meta { font-size: 0.75rem; color: #64748b; }

    .nav-tabs .nav-link { border: none; font-weight: 600; color: #64748b; border-bottom: 3px solid transparent; transition: all 0.2s; }
    .nav-tabs .nav-link.active { color: var(--bs-primary); border-bottom-color: var(--bs-primary); background: rgba(5, 150, 105, 0.03); }
    
    .btn-white { background: white; border: none; transition: all 0.3s; }
    .btn-white:hover { background: #f8fafc; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    
    .ledger-item { position: relative; padding-bottom: 1.5rem; }
    .ledger-item:not(:last-child)::before { content: ''; position: absolute; top: 10px; right: 23px; width: 2px; height: 100%; background: #f1f5f9; }
    .ledger-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; z-index: 1; }
    
    .h-2 { height: 3px; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const currency = '{{ __('center::sales.currency') }}';
    let studentSelector;

    document.addEventListener('DOMContentLoaded', function() {
        // Advanced TomSelect with AJAX - Optimized for Live Loading
        studentSelector = new TomSelect('#studentSelector', {
            valueField: 'id',
            labelField: 'name',
            searchField: ['name', 'phone', 'code'],
            openOnFocus: true,
            loadThrottle: 300,
            maxResults: 15,
            shouldLoad: function(query) {
                return query.length > 0;
            },
            score: function(search) {
                // Return a constant score so TomSelect shows all server results 
                // exactly as they are returned, without its own internal filtering.
                return function(item) { return 1; };
            },
            options: [],
            render: {
                no_results: function(data, escape) {
                    return `<div class="no-results p-3 text-muted text-center"><i class="fas fa-search me-2"></i>لا توجد نتائج مطابقة لـ "${escape(data.input)}"</div>`;
                },
                option: function(item, escape) {
                    return `
                        <div class="search-result-item">
                            <div class="search-result-avatar">${escape(item.initial)}</div>
                            <div class="search-result-info">
                                <span class="search-result-name">${escape(item.name)}</span>
                                <div class="search-result-meta">
                                    <span class="me-2"><i class="fas fa-phone-alt me-1"></i>${escape(item.phone)}</span>
                                    <span><i class="fas fa-graduation-cap me-1"></i>${escape(item.grade)}</span>
                                </div>
                            </div>
                            <div class="text-muted small">#${escape(item.code)}</div>
                        </div>`;
                },
                item: function(item, escape) {
                    return `<div class="fw-bold">${escape(item.name)} <span class="text-muted small ms-1">(${escape(item.phone)})</span></div>`;
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                
                const progressLine = document.getElementById('searchProgressLine');
                progressLine.classList.remove('d-none');
                progressLine.style.width = '40%';

                fetch(`/sales/students/lookup?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(json => {
                        progressLine.style.width = '100%';
                        setTimeout(() => { progressLine.classList.add('d-none'); progressLine.style.width = '0'; }, 300);
                        callback(json);
                    }).catch(() => {
                        progressLine.classList.add('d-none');
                        callback();
                    });
            },
            onChange: function(value) {
                if (value) loadStudentAccount(value);
            }
        });

        // Keyboard Shortcut (/) to focus search
        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                studentSelector.focus();
            }
        });

        // Load if ID in URL
        const urlParams = new URLSearchParams(window.location.search);
        const studentId = urlParams.get('student_id');
        if (studentId) {
            // Since options are remote, we might need to manually add this one if it's not and then set it
            fetch(`/sales/student-summary/${studentId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        studentSelector.addOption({
                            id: data.student.id,
                            name: data.student.name,
                            phone: data.student.phone,
                            code: data.student.code || 'N/A',
                            grade: data.student.grade,
                            initial: data.student.name.charAt(0)
                        });
                        studentSelector.setValue(studentId);
                    }
                });
        }
    });

    function downloadStatement() {
        const studentId = studentSelector.getValue();
        if (studentId) {
            window.open(`/sales/student-statement/${studentId}`, '_blank');
        }
    }

    let currentUnpaidInvoices = [];

    function loadStudentAccount(studentId) {
        if (!studentId) {
            document.querySelectorAll('#studentHeader, #statsSection, #accountTabs').forEach(el => el.classList.add('d-none'));
            document.getElementById('emptyState').classList.remove('d-none');
            return;
        }

        document.getElementById('emptyState').classList.add('d-none');
        document.querySelectorAll('#studentHeader, #statsSection, #accountTabs').forEach(el => el.classList.remove('d-none'));
        document.getElementById('tabsLoader').classList.replace('d-none', 'd-flex');

        fetch(`/sales/student-summary/${studentId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('tabsLoader').classList.replace('d-flex', 'd-none');
                if (data.success) {
                    const s = data.student;
                    currentUnpaidInvoices = data.unpaid_invoices;
                    
                    // Update Header
                    document.getElementById('studentInitial').innerText = s.name.charAt(0).toUpperCase();
                    document.getElementById('studentNameDisplay').innerText = s.name;
                    document.getElementById('studentPhoneDisplay').innerText = s.phone;
                    document.getElementById('studentGradeDisplay').innerText = s.grade || '-';
                    document.getElementById('studentIdDisplay').innerText = '#' + s.id;
                    
                    document.getElementById('studentStatusBadge').innerText = s.status === 'active' ? 'نشط' : 'غير نشط';
                    document.getElementById('studentStatusBadge').className = `badge bg-white bg-opacity-25 rounded-pill px-3 ${s.status === 'active' ? '' : 'text-danger'}`;

                    // Quick buttons
                    document.getElementById('whatsappBtn').href = `https://wa.me/2${s.phone}`;
                    document.getElementById('callBtn').href = `tel:${s.phone}`;

                    // Toggle Quick Pay Button
                    const quickPayBtn = document.getElementById('quickPayBtn');
                    if (currentUnpaidInvoices.length > 0) {
                        quickPayBtn.classList.remove('d-none');
                    } else {
                        quickPayBtn.classList.add('d-none');
                    }

                    // Update Stats
                    document.getElementById('debtStat').innerText = data.stats.total_debt + ' ' + currency;
                    document.getElementById('paidStat').innerText = data.stats.total_paid + ' ' + currency;
                    document.getElementById('attendanceStat').innerText = data.stats.attendance_rate + '%';
                    document.getElementById('attendanceProgress').style.width = data.stats.attendance_rate + '%';
                    document.getElementById('coursesStat').innerText = data.stats.course_count;

                    // Update Ledger (Timeline)
                    const timeline = document.getElementById('ledgerTimeline');
                    timeline.innerHTML = '';
                    if (data.ledger && data.ledger.length > 0) {
                        data.ledger.forEach(item => {
                            let icon, bg, color;
                            if (item.type === 'invoice') { icon = 'fa-file-invoice-dollar'; bg = 'rgba(239, 68, 68, 0.1)'; color = '#ef4444'; }
                            else if (item.type === 'payment') { icon = 'fa-cash-register'; bg = 'rgba(16, 185, 129, 0.1)'; color = '#10b981'; }
                            else { icon = 'fa-undo'; bg = 'rgba(245, 158, 11, 0.1)'; color = '#f59e0b'; }

                            timeline.innerHTML += `
                                <div class="ledger-item d-flex gap-4">
                                    <div class="ledger-icon flex-shrink-0" style="background: ${bg}; color: ${color};"><i class="fas ${icon} fa-lg"></i></div>
                                    <div class="flex-grow-1 pt-1 pb-3">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="fw-bold mb-0 text-dark">${item.description}</h6>
                                            <span class="small fw-bold text-dark">${item.type === 'invoice' ? '-' : '+'}${item.amount.toFixed(2)} ${currency}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="x-small text-muted"><i class="far fa-calendar-alt me-1"></i> ${item.date}</span>
                                            ${item.type === 'invoice' ? `<span class="badge ${item.status === 'paid' ? 'bg-success' : (item.status === 'partial' ? 'bg-warning' : 'bg-danger')} bg-opacity-10 text-reset small">${item.status}</span>` : ''}
                                        </div>
                                        ${item.type === 'invoice' ? `
                                            <div class="mt-2">
                                                <a href="/sales/${item.id}" target="_blank" class="x-small text-primary fw-bold text-decoration-none">عرض الفاتورة <i class="fas fa-external-link-alt ms-1"></i></a>
                                            </div>` : ''}
                                    </div>
                                </div>`;
                        });
                    } else {
                        timeline.innerHTML = `<div class="text-center py-5 text-muted"><i class="fas fa-ghost fa-2x mb-2 opacity-50"></i><p>لا يوجد سجلات مالية بعد.</p></div>`;
                    }

                    // Update Unpaid Invoices
                    const invList = document.getElementById('unpaidInvoicesList');
                    invList.innerHTML = '';
                    if (data.unpaid_invoices.length > 0) {
                        data.unpaid_invoices.forEach(inv => {
                            invList.innerHTML += `
                                <div class="bg-white border rounded-4 p-4 mb-3 d-flex justify-content-between align-items-center hover-scale shadow-none">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                            <i class="fas fa-file-invoice-dollar fa-lg"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-1">فاتورة مبيعات #${inv.id}</div>
                                            <div class="small text-muted mb-1"><i class="far fa-clock me-1"></i> ${inv.created_at.split('T')[0]}</div>
                                            <div class="text-danger fw-bold">متبقي: ${inv.remaining.toFixed(2)} ${currency}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="/sales/${inv.id}" target="_blank" class="btn btn-outline-light text-dark btn-sm rounded-pill px-4 border shadow-none">عرض</a>
                                        <button onclick="openGenericQuickPayModal(${inv.id}, ${inv.remaining})" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm">تحصيل</button>
                                    </div>
                                </div>`;
                        });
                    } else {
                        invList.innerHTML = `<div class="text-center py-5 text-muted"><div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px; height:80px;"><i class="fas fa-check-double fa-2x"></i></div><h6 class="fw-bold">رائع! لا توجد مديونيات معلقة</h6><p class="small">هذا الطالب ملتزم بسداد جميع المستحقات المالية.</p></div>`;
                    }

                    // Update Courses Grid
                    const coursesGrid = document.getElementById('coursesListSection');
                    coursesGrid.innerHTML = '';
                    if (data.courses.length > 0) {
                        data.courses.forEach(c => {
                            coursesGrid.innerHTML += `
                                <div class="col-md-6">
                                    <div class="card border border-light-subtle shadow-none rounded-4 bg-light bg-opacity-25 h-100">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="fw-bold text-dark mb-0">${c.title}</h6>
                                                <span class="badge bg-primary rounded-pill px-3">${c.status}</span>
                                            </div>
                                            <div class="small text-muted mb-0"><i class="far fa-calendar-alt me-1"></i> تاريخ الالتحاق: ${c.enrolled_at || '-'}</div>
                                        </div>
                                    </div>
                                </div>`;
                        });
                    } else {
                        coursesGrid.innerHTML = `<div class="col-12 text-center py-5 text-muted"><p>الطالب غير مسجل في أي دورات حالياً.</p></div>`;
                    }

                    // Update Attendance
                    const attTable = document.getElementById('attendanceTableBody');
                    attTable.innerHTML = '';
                    if (data.recent_attendance.length > 0) {
                        data.recent_attendance.forEach(a => {
                            let badgeClass = 'bg-secondary';
                            if(a.status === 'present') badgeClass = 'bg-success';
                            if(a.status === 'absent') badgeClass = 'bg-danger';
                            
                            attTable.innerHTML += `
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">${a.date}</td>
                                    <td>${a.course}</td>
                                    <td class="text-center"><span class="badge ${badgeClass} rounded-pill px-3">${a.status}</span></td>
                                </tr>`;
                        });
                    } else {
                        attTable.innerHTML = `<tr><td colspan="3" class="text-center py-5 text-muted">لا يوجد سجلات حضور مسجلة.</td></tr>`;
                    }
                }
            })
            .catch((err) => {
                console.error(err);
                document.getElementById('tabsLoader').classList.replace('d-none', 'd-flex');
                Swal.fire({ icon: 'error', title: 'خطأ في تحميل البيانات' });
            });
    }

    function openGenericQuickPayModal(saleId, amount) {
        const modalHtml = `
            <div class="modal fade" id="quickPayModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow">
                        <div class="modal-header border-0 shadow-sm p-4">
                            <h5 class="fw-bold mb-0"><i class="fas fa-bolt text-warning me-2"></i>تحصيل سريع للدين</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="p-3 bg-light rounded-4 mb-4 text-center">
                                <div class="text-muted small">رقم الفاتورة</div>
                                <div class="fw-bold fs-5">#${saleId}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">المبلغ المراد تحصيله</label>
                                <div class="input-group">
                                    <input type="number" id="quickAmount" step="0.01" class="form-control rounded-start-3" value="${amount.toFixed(2)}">
                                    <span class="input-group-text bg-light border-start-0 rounded-end-3">${currency}</span>
                                </div>
                                <div class="form-text">المبلغ المتبقي الكلي: <strong>${amount.toFixed(2)}</strong></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">طريقة السداد</label>
                                <select id="quickMethod" class="form-select rounded-3">
                                    <option value="cash">نقداً (Cash)</option>
                                    <option value="card">بطاقة إئتمان</option>
                                    <option value="bank_transfer">تحويل بنكي / محفظة</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                            <button type="button" id="confirmQuickPay" onclick="submitQuickPay(${saleId})" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                <i class="fas fa-check-circle me-1"></i> إتمام عملية السداد
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
            
        const oldModal = document.getElementById('quickPayModal');
        if (oldModal) oldModal.remove();
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const modal = new bootstrap.Modal(document.getElementById('quickPayModal'));
        modal.show();
    }

    function submitQuickPay(saleId) {
        const amount = document.getElementById('quickAmount').value;
        const method = document.getElementById('quickMethod').value;
        const btn = document.getElementById('confirmQuickPay');
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري الحفظ...';

        fetch(`/sales/${saleId}/payment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ amount: amount, payment_method: method })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('quickPayModal')).hide();
                Swal.fire({ icon: 'success', title: 'تم التحصيل بنجاح', timer: 1500, showConfirmButton: false });
                loadStudentAccount(studentSelector.getValue());
            } else {
                throw new Error(data.message);
            }
        })
        .catch((err) => {
            Swal.fire({ icon: 'error', title: 'فشل التحصيل', text: err.message });
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle me-1"></i> إتمام عملية السداد';
        });
    }

</script>
@endpush
@endsection
