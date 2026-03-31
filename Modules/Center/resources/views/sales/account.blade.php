@extends('center::layouts.hope-master')

@section('page-title', __('center::messages.blade_0558'))
@section('page-subtitle', __('center::messages.blade_0559'))

@section('content')

<div class="premium-dashboard-container animate__animated animate__fadeIn">
    <!-- Header Section with Hero Orbs -->
    <div class="premium-header position-relative rounded-5 overflow-hidden mb-5">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="glass-header p-5 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            <div class="header-info">
                <span class="badge bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-3 py-2 rounded-pill mb-3 animate__animated animate__fadeInDown">
                    <i class="fas fa-chart-line me-2"></i>{{ __('center::messages.blade_0558') }}
                </span>
                <h1 class="display-5 fw-black text-white mb-2">{{ __('center::messages.blade_0558') }}</h1>
                <p class="text-slate-400 mb-0">{{ __('center::messages.blade_0559') }}</p>
            </div>
            
            <!-- Modern Search Pill -->
            <div class="search-wrap flex-grow-1 max-w-xl">
                <div class="glass-card p-2 rounded-pill border-slate-700/50 shadow-2xl">
                    <select id="studentSelector" class="form-select border-0 bg-transparent text-white" placeholder="{{ __('center::messages.blade_0561') }}">
                        <option value="">{{ __('center::messages.blade_0561') }}</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} | {{ $student->phone }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Bento Grid -->
    <div class="row g-4 mb-5">
        <!-- Student Identity Card -->
        <div id="studentHeader" class="col-lg-4 d-none animate__animated animate__fadeInLeft">
            <div class="glass-card h-full p-4 rounded-5 border-slate-800/50 flex flex-column justify-content-center">
                <div class="d-flex align-items-center gap-4 mb-4">
                    <div class="avatar-ring p-1 rounded-circle bg-gradient-to-tr from-emerald-500 to-teal-400">
                        <div class="bg-slate-900 rounded-circle d-flex align-items-center justify-content-center text-white fw-black" style="width: 80px; height: 80px; font-size: 2.5rem;">
                            <span id="studentInitial">S</span>
                        </div>
                    </div>
                    <div>
                        <h2 id="studentNameDisplay" class="fw-black text-white h3 mb-1">Student Name</h2>
                        <span id="studentStatusBadge" class="badge bg-emerald-500/10 text-emerald-400 px-3 py-1 rounded-pill small">Active</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="d-flex align-items-center gap-3 text-slate-400">
                        <div class="w-10 h-10 rounded-3 bg-slate-800/50 d-flex align-items-center justify-content-center"><i class="fas fa-phone-alt"></i></div>
                        <span id="studentPhoneDisplay" class="fw-medium text-slate-200">00000000</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 text-slate-400">
                        <div class="w-10 h-10 rounded-3 bg-slate-800/50 d-flex align-items-center justify-content-center"><i class="fas fa-graduation-cap"></i></div>
                        <span id="studentGradeDisplay" class="fw-medium text-slate-200">Grade Level</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Bento -->
        <div class="col-lg-8">
            <div id="statsSection" class="row g-4 h-full d-none">
                <div class="col-md-6">
                    <div class="glass-card p-4 rounded-5 border-emerald-500/10 bg-emerald-500/5 hover-emerald transition-all animate__animated animate__fadeInUp">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded-4 bg-emerald-500/10 text-emerald-400"><i class="fas fa-money-bill-wave fa-xl"></i></div>
                            <span class="text-xs font-bold text-emerald-500/50 uppercase tracking-widest">Balance</span>
                        </div>
                        <div class="text-slate-400 small mb-1">{{ __('center::messages.blade_0562') }}</div>
                        <h3 id="debtStat" class="display-6 fw-black text-emerald-400 mb-0">0.00</h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="glass-card p-4 rounded-5 border-slate-800/50 hover-indigo transition-all animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded-4 bg-indigo-500/10 text-indigo-400"><i class="fas fa-check-circle fa-xl"></i></div>
                            <span class="text-xs font-bold text-indigo-500/50 uppercase tracking-widest">Settled</span>
                        </div>
                        <div class="text-slate-400 small mb-1">{{ __('center::messages.blade_0582', ['default' => 'إجمالي المسدد']) }}</div>
                        <h3 id="paidStat" class="display-6 fw-black text-indigo-400 mb-0">0.00</h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="glass-card p-4 rounded-5 border-slate-800/50 hover-cyan transition-all animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded-4 bg-cyan-500/10 text-cyan-400"><i class="fas fa-calendar-check fa-xl"></i></div>
                            <span class="text-xs font-bold text-cyan-500/50 uppercase tracking-widest">Attendance</span>
                        </div>
                        <div class="text-slate-400 small mb-1">{{ __('center::messages.blade_0583', ['default' => 'نسبة الحضور']) }}</div>
                        <h3 id="attendanceStat" class="display-6 fw-black text-cyan-400 mb-0">0%</h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="glass-card p-4 rounded-5 border-slate-800/50 hover-amber transition-all animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-3 rounded-4 bg-amber-500/10 text-amber-400"><i class="fas fa-book-open fa-xl"></i></div>
                            <span class="text-xs font-bold text-amber-500/50 uppercase tracking-widest">Activity</span>
                        </div>
                        <div class="text-slate-400 small mb-1">{{ __('center::messages.blade_0584', ['default' => 'الكورسات المشتركة']) }}</div>
                        <h3 id="coursesStat" class="display-6 fw-black text-amber-400 mb-0">0</h3>
                    </div>
                </div>
            </div>

            <!-- Empty State for Stats -->
            <div id="emptyState" class="glass-card h-full rounded-5 p-5 d-flex flex-column align-items-center justify-content-center text-center">
                <div class="p-4 rounded-circle bg-slate-800/50 mb-4 animate__animated animate__pulse animate__infinite">
                    <i class="fas fa-user-plus fa-3x text-slate-500"></i>
                </div>
                <h3 class="text-white fw-bold">{{ __('center::messages.blade_0569') }}</h3>
                <p class="text-slate-400 max-w-sm">{{ __('center::messages.blade_0570') }}</p>
            </div>
        </div>
    </div>

    <!-- Details Tabs Section -->
    <div id="accountTabs" class="d-none animate__animated animate__fadeInUp">
        <div class="glass-card rounded-5 overflow-hidden border-slate-800/50">
            <div class="premium-tabs border-bottom border-slate-800/50 p-2">
                <ul class="nav nav-pills nav-justified gap-2" id="myTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active rounded-4 p-3 font-bold transition-all" data-bs-toggle="tab" data-bs-target="#financialsTab">
                            <i class="fas fa-wallet me-2"></i>{{ __('center::sales.ledger') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-4 p-3 font-bold transition-all" data-bs-toggle="tab" data-bs-target="#coursesTab">
                            <i class="fas fa-graduation-cap me-2"></i>{{ __('center::messages.blade_0020') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-4 p-3 font-bold transition-all" data-bs-toggle="tab" data-bs-target="#attendanceTab">
                            <i class="fas fa-user-check me-2"></i>{{ __('center::messages.blade_0130') }}
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="card-body p-5">
                <div class="tab-content">
                    <!-- Financials Tab -->
                    <div class="tab-pane fade show active" id="financialsTab">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="text-white fw-black mb-0"><i class="fas fa-file-invoice-dollar text-emerald-500 me-2"></i>{{ __('center::messages.blade_0567') }}</h4>
                            <button class="btn btn-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-pill px-4 btn-sm">
                                <i class="fas fa-download me-2"></i>Export Ledger
                            </button>
                        </div>
                        <div id="unpaidInvoicesList" class="space-y-4">
                            <!-- Dynamic Content -->
                        </div>
                    </div>

                    <!-- Courses Tab -->
                    <div class="tab-pane fade" id="coursesTab">
                        <div id="coursesListSection" class="row g-4">
                            <!-- Dynamic Content -->
                        </div>
                    </div>

                    <!-- Attendance Tab -->
                    <div class="tab-pane fade" id="attendanceTab">
                        <div class="glass-table-wrap rounded-4 overflow-hidden border border-slate-800/50">
                            <table class="table table-dark table-hover mb-0 align-middle">
                                <thead class="bg-slate-800/50">
                                    <tr class="border-slate-800/50">
                                        <th class="p-4 text-slate-400 uppercase tracking-tighter small">{{ __('center::messages.blade_0677', ['default' => 'التاريخ']) }}</th>
                                        <th class="p-4 text-slate-400 uppercase tracking-tighter small">{{ __('center::messages.blade_0650', ['default' => 'الكورس']) }}</th>
                                        <th class="p-4 text-slate-400 uppercase tracking-tighter small">{{ __('center::messages.blade_0434', ['default' => 'الحالة']) }}</th>
                                    </tr>
                                </thead>
                                <tbody id="attendanceTableBody" class="border-slate-800/50">
                                    <!-- Dynamic Content -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;900&family=Cairo:wght@400;700;900&display=swap');

    :root {
        --premium-bg: #030712;
        --glass-bg: rgba(15, 23, 42, 0.6);
        --emerald-500: #10b981;
        --indigo-500: #6366f1;
        --slate-900: #0f172a;
    }

    body {
        background-color: var(--premium-bg) !important;
        font-family: 'Outfit', 'Cairo', sans-serif !important;
    }

    .fw-black { font-weight: 900; }
    .glass-card { background: var(--glass-bg); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.05); }
    
    /* Header Premium Style */
    .premium-header { 
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
    }
    .orb { position: absolute; border-radius: 50%; filter: blur(60px); opacity: 0.15; }
    .orb-1 { width: 300px; height: 300px; background: var(--emerald-500); top: -100px; left: -50px; }
    .orb-2 { width: 250px; height: 250px; background: var(--indigo-500); bottom: -50px; right: -50px; }

    /* TomSelect Premium Redesign */
    .ts-control { 
        background: transparent !important; 
        border: none !important; 
        color: white !important;
        font-size: 1.1rem !important;
        padding: 0.75rem 1rem !important;
    }
    .ts-dropdown { background: #1e293b !important; color: white !important; border: 1px solid #334155 !important; border-radius: 1rem !important; top: 10px !important; }
    .ts-dropdown .option { padding: 10px 20px !important; }
    .ts-dropdown .active { background: var(--emerald-500) !important; }

    /* Bento Grid Effects */
    .hover-emerald:hover { border-color: var(--emerald-500) !important; box-shadow: 0 0 30px rgba(16, 185, 129, 0.15); transform: translateY(-3px); }
    .hover-indigo:hover { border-color: var(--indigo-500) !important; box-shadow: 0 0 30px rgba(99, 102, 241, 0.15); transform: translateY(-3px); }
    /* More colors as needed */

    /* Premium Tabs */
    .premium-tabs .nav-link { color: #94a3b8; border: none; background: transparent; }
    .premium-tabs .nav-link.active { background: var(--emerald-500) !important; color: white !important; box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3); }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: var(--premium-bg); }
    ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #334155; }

    .space-y-3 > * + * { margin-top: 0.75rem; }
    .space-y-4 > * + * { margin-top: 1rem; }
    .max-w-sm { max-width: 24rem; }
    .max-w-xl { max-width: 36rem; }
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
                    
                    // Update Header Bento
                    document.getElementById('studentInitial').innerText = s.name.charAt(0).toUpperCase();
                    document.getElementById('studentNameDisplay').innerText = s.name;
                    document.getElementById('studentPhoneDisplay').innerText = s.phone;
                    document.getElementById('studentGradeDisplay').innerText = s.grade || '-';
                    
                    const statusBadge = document.getElementById('studentStatusBadge');
                    statusBadge.innerText = s.status === 'active' ? '{{ __('center::messages.blade_0583') }}' : '{{ __('center::messages.blade_0085') }}';
                    statusBadge.className = `badge ${s.status === 'active' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-danger/10 text-danger'} px-3 py-1 rounded-pill small`;
                    
                    // Update Stats with Counting Animation logic (optional but nice)
                    document.getElementById('debtStat').innerText = data.stats.total_debt + ' ' + currency;
                    document.getElementById('paidStat').innerText = data.stats.total_paid + ' ' + currency;
                    document.getElementById('attendanceStat').innerText = data.stats.attendance_rate + '%';
                    document.getElementById('coursesStat').innerText = data.stats.course_count;

                    // Update Financials Tab (Premium List Items)
                    const invoiceList = document.getElementById('unpaidInvoicesList');
                    invoiceList.innerHTML = '';
                    if (data.unpaid_invoices.length > 0) {
                        data.unpaid_invoices.forEach(inv => {
                            invoiceList.innerHTML += `
                                <div class="glass-card p-4 rounded-4 d-flex justify-content-between align-items-center hover-emerald transition-all border-slate-800/50">
                                    <div class="d-flex align-items-center gap-4">
                                        <div class="bg-emerald-500/10 text-emerald-400 rounded-3 d-flex align-items-center justify-content-center" style="width:55px; height:55px;">
                                            <i class="fas fa-file-invoice-dollar fa-lg"></i>
                                        </div>
                                        <div>
                                            <div class="fw-black text-white mb-0 h6">{{ __('center::sales.invoice_id_prefix') }}${inv.id}</div>
                                            <div class="small text-slate-400 font-mono uppercase tracking-tighter">${inv.created_at.split('T')[0]}</div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-emerald-400 fw-black h5 mb-1">${inv.remaining.toFixed(2)} ${currency}</div>
                                        <a href="/sales/${inv.id}" target="_blank" class="text-xs font-bold text-slate-500 hover-white transition-all uppercase tracking-widest decoration-none">
                                            <i class="fas fa-external-link-alt me-1"></i> {{ __('center::messages.blade_0573') }}
                                        </a>
                                    </div>
                                </div>`;
                        });
                    } else {
                        invoiceList.innerHTML = `
                            <div class="text-center py-5 glass-card rounded-4 border-dashed border-slate-800">
                                <div class="text-emerald-500 mb-3"><i class="fas fa-check-double fa-3x"></i></div>
                                <h5 class="text-white fw-bold">{{ __('center::messages.blade_0574') }}</h5>
                                <p class="text-slate-500 mb-0">All accounts are settled for this student.</p>
                            </div>`;
                    }

                    // Update Courses Tab (Premium Grid)
                    const coursesGrid = document.getElementById('coursesListSection');
                    coursesGrid.innerHTML = '';
                    if (data.courses.length > 0) {
                        data.courses.forEach(c => {
                            coursesGrid.innerHTML += `
                                <div class="col-md-6">
                                    <div class="glass-card p-4 rounded-4 border-slate-800/50 h-full">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="p-3 rounded-3 bg-indigo-500/10 text-indigo-400"><i class="fas fa-book-bookmark"></i></div>
                                            <div>
                                                <h6 class="fw-black text-white h6 mb-1">${c.title}</h6>
                                                <div class="text-xs text-slate-500 mb-3">Enrolled: ${c.enrolled_at || '-'}</div>
                                                <span class="badge bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-pill px-3 py-1 font-bold small">${c.status}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                        });
                    } else {
                        coursesGrid.innerHTML = `<div class="col-12 text-center py-5 text-slate-500 glass-card rounded-4 border-dashed">{{ __('center::messages.blade_0571') }}</div>`;
                    }

                    // Update Attendance Tab
                    const attTable = document.getElementById('attendanceTableBody');
                    attTable.innerHTML = '';
                    if (data.recent_attendance.length > 0) {
                        data.recent_attendance.forEach(a => {
                            let badgeStyle = 'bg-slate-800/50 text-slate-400';
                            if(a.status === 'present') badgeStyle = 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
                            if(a.status === 'absent') badgeStyle = 'bg-danger/10 text-danger border border-danger/20';
                            
                            attTable.innerHTML += `
                                <tr class="border-slate-800/50">
                                    <td class="p-4 text-white fw-bold"><i class="far fa-calendar me-2 text-slate-500"></i>${a.date}</td>
                                    <td class="p-4 text-slate-300 font-medium">${a.course}</td>
                                    <td class="p-4"><span class="badge ${badgeStyle} rounded-pill px-3 py-1 font-bold small uppercase shadow-none">${a.status}</span></td>
                                </tr>`;
                        });
                    } else {
                        attTable.innerHTML = `<tr><td colspan="3" class="text-center py-5 text-slate-500 glass-card border-0">{{ __('center::messages.blade_0571') }}</td></tr>`;
                    }
                }
            });
    }

</script>
@endpush
@endsection
