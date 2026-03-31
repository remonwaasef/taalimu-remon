@extends('center::layouts.hope-master')

@section('page-title', __('center::messages.blade_0558'))
@section('page-subtitle', __('center::messages.blade_0559'))

@section('content')

<div class="row g-4 position-relative">
    <!-- Redesign: Hero Background Orbs (Subtle) -->
    <div class="position-absolute top-0 start-50 translate-middle-x overflow-hidden w-100 h-100" style="z-index: -1; pointer-events: none;">
        <div class="position-absolute top-0 start-0 bg-emerald-500/10 blur-[120px] rounded-full w-96 h-96 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="position-absolute bottom-0 end-0 bg-slate-900/[0.02] blur-[100px] rounded-full w-80 h-80 translate-x-1/3 translate-y-1/3"></div>
    </div>

    <!-- Student Header Dashboard (Hidden until search) -->
    <div id="studentHeader" class="col-lg-12 d-none animate__animated animate__fadeIn">
        <div class="card border-0 shadow-2xl rounded-[2.5rem] bg-slate-900 text-white overflow-hidden position-relative">
            <div class="card-body p-5 position-relative z-10">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="bg-emerald-500 text-white rounded-[2rem] d-flex align-items-center justify-content-center fw-black shadow-[0_20px_50px_-12px_rgba(16,185,129,0.35)]" style="width: 100px; height: 100px; font-size: 2.5rem;">
                            <span id="studentInitial">S</span>
                        </div>
                    </div>
                    <div class="col">
                        <h1 id="studentNameDisplay" class="fw-black mb-2 tracking-tight display-6">Student Name</h1>
                        <div class="d-flex flex-wrap gap-4 opacity-90">
                            <span class="d-flex align-items-center gap-2 px-3 py-2 bg-white/10 rounded-pill"><i class="fa-solid fa-phone-volume text-emerald-400"></i> <span id="studentPhoneDisplay" class="fw-bold">00000000</span></span>
                            <span class="d-flex align-items-center gap-2 px-3 py-2 bg-white/10 rounded-pill"><i class="fa-solid fa-graduation-cap text-emerald-400"></i> <span id="studentGradeDisplay" class="fw-bold">Grade</span></span>
                            <span class="d-flex align-items-center gap-2 px-3 py-2 bg-white/10 rounded-pill"><i class="fa-solid fa-circle text-emerald-400" id="statusDot"></i> <span id="studentStatusDisplay" class="fw-bold">Status</span></span>
                        </div>
                    </div>
                    <div class="col-auto d-none d-md-block">
                        <i class="fa-solid fa-fingerprint fa-6x opacity-10"></i>
                    </div>
                </div>
            </div>
            <!-- Decorative line -->
            <div class="position-absolute bottom-0 start-0 w-100 h-1 bg-gradient-to-r from-emerald-500 to-emerald-400"></div>
        </div>
    </div>

    <!-- Redesign: Premium Search Container -->
    <div class="col-lg-12">
        <div class="card border-0 shadow-xl rounded-[2rem] bg-white transform hover:-translate-y-1 transition-all duration-300">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-emerald-100 text-emerald-600 p-2 rounded-xl">
                                <i class="fa-solid fa-users-viewfinder fs-4"></i>
                            </div>
                            <h5 class="fw-black text-slate-900 mb-0">{{ __('center::messages.blade_0560') }}</h5>
                        </div>
                        <div class="premium-search-wrapper">
                            <select id="studentSelector" class="form-select form-select-lg border-0 bg-slate-50 rounded-2xl" placeholder="{{ __('center::messages.blade_0561') }}">
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
    </div>

    <!-- Stats Cards (Hidden until search) -->
    <div id="statsSection" class="col-lg-12 d-none">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-lg rounded-[2rem] bg-white animate__animated animate__fadeInUp border-l-4 border-rose-500">
                    <div class="card-body p-4 d-flex align-items-center gap-4">
                        <div class="bg-rose-50 text-rose-500 rounded-2xl p-3 shadow-inner"><i class="fa-solid fa-receipt fs-3"></i></div>
                        <div>
                            <div class="text-slate-500 small fw-black text-uppercase tracking-wider">{{ __('center::messages.blade_0562') }}</div>
                            <h3 id="debtStat" class="mb-0 fw-black text-rose-600">0.00</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-lg rounded-[2rem] bg-white animate__animated animate__fadeInUp" style="animation-delay: 0.1s; border-left: 4px solid #10b981;">
                    <div class="card-body p-4 d-flex align-items-center gap-4">
                        <div class="bg-emerald-50 text-emerald-500 rounded-2xl p-3 shadow-inner"><i class="fa-solid fa-circle-check fs-3"></i></div>
                        <div>
                            <div class="text-slate-500 small fw-black text-uppercase tracking-wider">{{ __('center::messages.blade_0582', ['default' => 'إجمالي المسدد']) }}</div>
                            <h3 id="paidStat" class="mb-0 fw-black text-emerald-600">0.00</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-lg rounded-[2rem] bg-white animate__animated animate__fadeInUp" style="animation-delay: 0.2s; border-left: 4px solid #06b6d4;">
                    <div class="card-body p-4 d-flex align-items-center gap-4">
                        <div class="bg-cyan-50 text-cyan-500 rounded-2xl p-3 shadow-inner"><i class="fa-solid fa-calendar-check fs-3"></i></div>
                        <div>
                            <div class="text-slate-500 small fw-black text-uppercase tracking-wider">{{ __('center::messages.blade_0583', ['default' => 'نسبة الحضور']) }}</div>
                            <h3 id="attendanceStat" class="mb-0 fw-black text-cyan-600">0%</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-lg rounded-[2rem] bg-white animate__animated animate__fadeInUp" style="animation-delay: 0.3s; border-left: 4px solid #f59e0b;">
                    <div class="card-body p-4 d-flex align-items-center gap-4">
                        <div class="bg-amber-50 text-amber-500 rounded-2xl p-3 shadow-inner"><i class="fa-solid fa-book-bookmark fs-3"></i></div>
                        <div>
                            <div class="text-slate-500 small fw-black text-uppercase tracking-wider">{{ __('center::messages.blade_0584', ['default' => 'الدورات']) }}</div>
                            <h3 id="coursesStat" class="mb-0 fw-black text-amber-600">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs (Hidden initially) -->
    <div id="accountTabs" class="col-lg-12 d-none animate__animated animate__fadeIn">
        <div class="card border-0 shadow-2xl rounded-[2.5rem] bg-white overflow-hidden">
            <div class="card-header bg-slate-50 border-0 p-0">
                <ul class="nav nav-pills nav-justified p-2 gap-2" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-2xl py-3 fw-black text-uppercase" data-bs-toggle="tab" data-bs-target="#financialsTab" type="button"><i class="fa-solid fa-wallet me-2"></i>{{ __('center::sales.ledger') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-2xl py-3 fw-black text-uppercase" data-bs-toggle="tab" data-bs-target="#coursesTab" type="button"><i class="fa-solid fa-scroll me-2"></i>{{ __('center::messages.blade_0020') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-2xl py-3 fw-black text-uppercase" data-bs-toggle="tab" data-bs-target="#attendanceTab" type="button"><i class="fa-solid fa-clock-rotate-left me-2"></i>{{ __('center::messages.blade_0130') }}</button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-5">
                <div class="tab-content" id="myTabContent">
                    <!-- Financials Tab -->
                    <div class="tab-pane fade show active" id="financialsTab">
                        <div class="mb-0">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="fw-black text-slate-900 mb-0 d-flex align-items-center gap-3">
                                    <span class="bg-rose-100 text-rose-600 rounded-xl p-2 d-flex align-items-center justify-content-center" style="width:40px; height:40px;"><i class="fa-solid fa-file-invoice-dollar fs-5"></i></span>
                                    {{ __('center::messages.blade_0567') }}
                                </h4>
                                <button onclick="window.print()" class="btn btn-outline-slate rounded-pill px-4 fw-bold shadow-sm"><i class="fa-solid fa-print me-2"></i>طباعة كشف حساب</button>
                            </div>
                            <div id="unpaidInvoicesList" class="row g-3">
                                <!-- Dynamic dynamic -->
                            </div>
                        </div>
                    </div>

                    <!-- Courses Tab -->
                    <div class="tab-pane fade" id="coursesTab">
                        <div id="coursesListSection" class="row g-4">
                            <!-- Dynamic dynamic -->
                        </div>
                    </div>

                    <!-- Attendance Tab -->
                    <div class="tab-pane fade" id="attendanceTab">
                        <div class="table-responsive rounded-3xl border">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-slate-50 border-bottom">
                                    <tr>
                                        <th class="py-4 ps-4 fw-black text-slate-500 uppercase small tracking-widest">{{ __('center::messages.blade_0677', ['default' => 'التاريخ']) }}</th>
                                        <th class="py-4 fw-black text-slate-500 uppercase small tracking-widest">{{ __('center::messages.blade_0650', ['default' => 'الدورة']) }}</th>
                                        <th class="py-4 fw-black text-slate-500 uppercase small tracking-widest text-center">{{ __('center::messages.blade_0434', ['default' => 'الحالة']) }}</th>
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
        <div class="premium-glass p-5 d-inline-block rounded-[3rem] shadow-2xl border border-white/50" style="max-width: 600px; backdrop-filter: blur(20px); background: rgba(255,255,255,0.7);">
            <div class="bg-emerald-50 rounded-full d-flex align-items-center justify-content-center mx-auto mb-4 floating-icon" style="width: 140px; height: 140px;">
                <i class="fa-solid fa-user-gear fa-4x text-emerald-500"></i>
            </div>
            <h2 class="fw-black text-slate-900 mb-3 display-6 tracking-tighter">{{ __('center::messages.blade_0569') }}</h2>
            <p class="text-slate-500 fs-5 mb-0 leading-relaxed">{{ __('center::messages.blade_0570') }}</p>
        </div>
    </div>
</div>

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    :root {
        --emerald-500: #10b981;
        --emerald-600: #059669;
        --slate-900: #0f172a;
    }
    body { background-color: #f8fafc; font-family: 'Outfit', 'Cairo', sans-serif !important; }
    .fw-black { font-weight: 900 !important; }
    
    /* TomSelect Premium Override */
    .ts-control { 
        border: 2px solid transparent !important;
        background: #f1f5f9 !important;
        border-radius: 1.5rem !important;
        padding: 1.25rem 1.5rem !important;
        font-weight: 700 !important;
        font-size: 1.1rem !important;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .ts-control:focus { 
        background: white !important;
        border-color: var(--emerald-500) !important;
        box-shadow: 0 10px 40px -10px rgba(16,185,129,0.15) !important;
        transform: scale(1.01);
    }
    
    /* Premium Nav Pills */
    .nav-pills .nav-link { color: #64748b; background: transparent; transition: all 0.4s; }
    .nav-pills .nav-link.active { 
        background: var(--emerald-500) !important; 
        color: white !important;
        box-shadow: 0 10px 25px -5px rgba(16,185,129,0.4) !important;
    }
    
    /* Animations */
    .floating-icon { animation: float 6s ease-in-out infinite; }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    
    .hover-scale { transition: all 0.3s; }
    .hover-scale:hover { transform: scale(1.02); }
    
    /* Custom Utilities */
    .tracking-widest { letter-spacing: 0.1em; }
    .rounded-2xl { border-radius: 1rem !important; }
    .rounded-3xl { border-radius: 1.5rem !important; }
    .display-6 { font-size: 2.25rem; }
    .blur-\[120px\] { filter: blur(120px); }
    .blur-\[100px\] { filter: blur(100px); }
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
                    document.getElementById('studentStatusDisplay').innerText = s.status === 'active' ? '{{ __('center::messages.blade_0583') }}' : '{{ __('center::messages.blade_0085') }}';
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
                                <div class="col-md-6 animate__animated animate__fadeIn">
                                    <div class="bg-white border-2 border-slate-50 rounded-[2rem] p-4 d-flex justify-content-between align-items-center hover-scale transition-all shadow-sm">
                                        <div class="d-flex align-items-center gap-4">
                                            <div class="bg-rose-50 text-rose-500 rounded-2xl d-flex align-items-center justify-content-center shadow-inner" style="width:55px; height:55px;">
                                                <i class="fa-solid fa-file-invoice-dollar fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="fw-black text-slate-900 mb-0 fs-5">{{ __('center::sales.invoice_id_prefix') }}${inv.id}</div>
                                                <div class="small fw-bold text-slate-400 mb-2">${inv.created_at.split('T')[0]}</div>
                                                <div class="bg-rose-50 text-rose-600 fw-black px-3 py-1 rounded-pill d-inline-block small">
                                                    ${inv.remaining.toFixed(2)} ${currency}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="/sales/${inv.id}" target="_blank" class="btn btn-slate-900 text-white btn-sm rounded-xl px-4 py-2 fw-bold shadow-lg"><i class="fa-solid fa-arrow-up-right-from-square me-2"></i> {{ __('center::messages.blade_0573') }}</a>
                                        </div>
                                    </div>
                                </div>`;
                        });
                    } else {
                        invoiceList.innerHTML = `<div class="col-12 text-center py-5"><div class="bg-emerald-50 text-emerald-600 rounded-full d-inline-flex align-items-center justify-content-center mb-3" style="width:70px; height:70px;"><i class="fa-solid fa-circle-check fa-2x"></i></div><h5 class="fw-black text-slate-900">{{ __('center::messages.blade_0574') }}</h5></div>`;
                    }

                    // Update Courses Tab
                    const coursesGrid = document.getElementById('coursesListSection');
                    coursesGrid.innerHTML = '';
                    if (data.courses.length > 0) {
                        data.courses.forEach(c => {
                            let statusColor = c.status === 'active' ? 'emerald' : 'slate';
                            coursesGrid.innerHTML += `
                                <div class="col-md-6 animate__animated animate__fadeIn">
                                    <div class="card border-0 shadow-lg rounded-[2.25rem] bg-white overflow-hidden">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <div class="bg-${statusColor}-50 text-${statusColor}-500 rounded-xl p-2">
                                                    <i class="fa-solid fa-book-bookmark fs-5"></i>
                                                </div>
                                                <h5 class="fw-black text-slate-900 mb-0">${c.title}</h5>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="small fw-bold text-slate-400">تاريخ الاشتراك: ${c.enrolled_at || '-'}</div>
                                                <span class="badge bg-${statusColor}-500 rounded-pill px-4 py-2 fw-black text-uppercase shadow-sm">${c.status}</span>
                                            </div>
                                        </div>
                                        <div class="bg-slate-50 py-2 border-top"></div>
                                    </div>
                                </div>`;
                        });
                    } else {
                        coursesGrid.innerHTML = `<div class="col-12 text-center py-5 text-slate-400 font-bold fs-5">{{ __('center::messages.blade_0571') }}</div>`;
                    }

                    // Update Attendance Tab
                    const attTable = document.getElementById('attendanceTableBody');
                    attTable.innerHTML = '';
                    if (data.recent_attendance.length > 0) {
                        data.recent_attendance.forEach(a => {
                            let badgeClass = 'bg-slate-100 text-slate-600';
                            if(a.status === 'present') badgeClass = 'bg-emerald-100 text-emerald-600';
                            if(a.status === 'absent') badgeClass = 'bg-rose-100 text-rose-600';
                            
                            attTable.innerHTML += `
                                <tr class="animate__animated animate__fadeIn">
                                    <td class="py-4 ps-4 fw-black text-slate-900 fs-5">${a.date}</td>
                                    <td class="py-4 fw-bold text-slate-600">${a.course}</td>
                                    <td class="py-4 text-center">
                                        <span class="badge ${badgeClass} rounded-pill px-4 py-2 fw-black text-uppercase shadow-sm">${a.status}</span>
                                    </td>
                                </tr>`;
                        });
                    } else {
                        attTable.innerHTML = `<tr><td colspan="3" class="text-center py-5 text-slate-400 fw-bold fs-5">{{ __('center::messages.blade_0571') }}</td></tr>`;
                    }
                }
            });
    }

</script>
@endpush
@endsection
