@extends('center::layouts.hope-master')
@section('page-title', __('center::students.title'))

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('center.students.export') }}" class="btn btn-glass" id="export-students-btn">
            <i class="fas fa-file-export me-2"></i> {{ __('center::students.export_file') }}
        </a>
        <a href="{{ route('center.students.import') }}" class="btn btn-glass">
            <i class="fas fa-file-import me-2"></i> {{ __('center::students.import_file') }}
        </a>
        <a href="{{ route('center.students.create') }}" class="btn btn-glass">
            <span class="me-2">+</span> {{ __('center::students.add_new') }}
        </a>
    </div>
@endsection

@section('content')

    @if(session('generated_password'))
        <div class="premium-ticket-container mb-5 animate__animated animate__fadeIn">
            <div class="premium-ticket shadow-lg">
                <div class="row g-0">
                    <!-- Left Side: Student Info -->
                    <div class="col-md-8 p-4 bg-white rounded-start-4 position-relative overflow-hidden">
                        <div class="ticket-decoration"></div>
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-id-card fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0 text-dark">{{ __('center::students.registration_card') }}</h4>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 mt-1">{{ __('center::students.activated_successfully') }}</span>
                            </div>
                        </div>

                        <div class="row g-4 mt-2">
                            <div class="col-sm-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">{{ __('center::students.name') }}</label>
                                <span class="fw-bold fs-5">{{ session('student_name') }}</span>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">{{ __('center::students.phone') }}</label>
                                <span class="fw-bold text-dark">{{ session('student_phone') }}</span>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">{{ __('center::students.email') }}</label>
                                <span class="text-primary fw-bold">{{ session('student_email') }}</span>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">{{ __('center::students.temporary_password') }}</label>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-4 fw-bold text-danger font-monospace">{{ session('generated_password') }}</span>
                                    <button onclick="copyToClipboard('{{ session('generated_password') }}')" class="btn btn-sm btn-light rounded-circle" title="{{ __('center::students.copy') }}">
                                        <i class="fas fa-copy text-primary"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2">
                            @php
                                $msg = __('center::students.welcome_whatsapp_msg', [
                                    'name' => session('student_name'),
                                    'url' => url('/login'),
                                    'email' => session('student_email'),
                                    'password' => session('generated_password')
                                ]);
                                $whatsappUrl = "https://wa.me/" . sanitizePhoneForWhatsApp(session('student_phone')) . "?text=" . urlencode($msg);
                                $mailtoUrl = "mailto:" . session('student_email') . "?subject=" . urlencode(__('center::students.login_credentials_subject')) . "&body=" . rawurlencode($msg);
                            @endphp

                            <button onclick="copyAllDetails()" class="btn btn-outline-dark rounded-pill px-4">
                                <i class="fas fa-copy me-2"></i> {{ __('center::students.copy_all_data') }}
                            </button>
                            <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-success rounded-pill px-4">
                                <i class="fab fa-whatsapp me-2"></i> {{ __('center::students.send_whatsapp') }}
                            </a>
                            <a href="{{ $mailtoUrl }}" class="btn btn-light border rounded-pill px-4">
                                <i class="fas fa-envelope me-2"></i> {{ __('center::students.send_email') }}
                            </a>
                        </div>
                    </div>

                    <!-- Right Side: QR Code -->
                    <div class="col-md-4 p-4 text-center d-flex flex-column align-items-center justify-content-center bg-light rounded-end-4 border-start border-dashed position-relative">
                        <div class="ticket-stub-decoration top"></div>
                        <div class="ticket-stub-decoration bottom"></div>
                        
                        @if(session('student_email'))
                            @php
                                $studentForQr = \App\Models\Student::where('email', session('student_email'))->where('tenant_id', app('tenant')->id)->first();
                                $qrUrl = $studentForQr ? \Illuminate\Support\Facades\URL::signedRoute('center.login.magic', ['student' => $studentForQr->id, 'tenant' => app('tenant')->domain]) : url('/login');
                            @endphp
                            <div class="qr-container bg-white p-2 rounded-3 shadow-sm mb-3">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($qrUrl) }}" alt="QR Code" style="width: 140px; height: 140px;">
                            </div>
                        @else
                            <div class="qr-container bg-white p-2 rounded-3 shadow-sm mb-3">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/login')) }}" alt="QR Code" style="width: 140px; height: 140px;">
                            </div>
                        @endif
                        <p class="small text-muted mb-0">{{ __('center::students.scan_qr_tip') }}</p>
                        <div class="mt-3 text-secondary small">
                            <i class="fas fa-clock me-1"></i> {{ __('center::students.valid_unlimited') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function() {
                    const toast = document.createElement('div');
                    toast.className = 'position-fixed bottom-0 start-50 translate-middle-x mb-5 bg-dark text-white p-3 rounded-4 shadow animate__animated animate__fadeInUp';
                    toast.style.zIndex = '9999';
                    toast.innerHTML = '<i class="fas fa-check-circle text-success me-2"></i> {{ __('center::students.copy_success') }}';
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 2000);
                });
            }

            function copyAllDetails() {
                const text = @json($msg);
                navigator.clipboard.writeText(text).then(function() {
                    alert('{{ __('center::students.copy_all_success') }}');
                });
            }
        </script>

        <style>
            .premium-ticket {
                border-radius: 20px;
                overflow: hidden;
                position: relative;
            }
            .border-dashed {
                border-left: 2px dashed #dee2e6 !important;
            }
            .ticket-stub-decoration {
                position: absolute;
                width: 30px;
                height: 30px;
                background: #f8f9fa; /* Matches page bg or parent container */
                border-radius: 50%;
                left: -15px;
                z-index: 10;
            }
            .ticket-stub-decoration.top { top: -15px; }
            .ticket-stub-decoration.bottom { bottom: -15px; }
            
            .ticket-decoration {
                position: absolute;
                top: -50px;
                right: -50px;
                width: 150px;
                height: 150px;
                background: var(--bs-primary);
                opacity: 0.03;
                border-radius: 50%;
            }
            
            .qr-container { transition: transform 0.3s ease; }
            .qr-container:hover { transform: scale(1.05); }

            [dir="rtl"] .ticket-stub-decoration {
                left: auto;
                right: -15px;
            }
            [dir="rtl"] .border-dashed {
                border-left: none !important;
                border-right: 2px dashed #dee2e6 !important;
            }
        </style>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <!-- Search & Filter -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="position-relative">
                        <input 
                            type="text" 
                            id="search-input"
                            data-smart-search=".custom-table"
                            data-search-fields="name,phone"
                            data-search-highlight="true"
                            class="form-control ps-5 rounded-pill border-0 shadow-sm" 
                            placeholder="{{ __('center::students.search_placeholder') }}"
                            style="background-color: var(--color-light); height: 48px;"
                        >
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Stage & Financial Filter Buttons -->
            <div class="mb-3 d-flex flex-wrap gap-3 justify-content-between align-items-center">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-outline-primary rounded-pill px-4 stage-btn active" data-stage="all">
                        {{ __('center::students.all') }}
                    </button>
                    @foreach($stages as $stage)
                        <button class="btn btn-outline-primary rounded-pill px-4 stage-btn" data-stage="stage-{{ $stage->id }}" data-grades="{{ $stage->grades->pluck('id')->implode(',') }}">
                            {{ $stage->name }}
                        </button>
                    @endforeach
                </div>

                <div class="btn-group p-1 bg-light rounded-pill" role="group" style="min-width: 250px;">
                    <input type="radio" data-smart-filter=".custom-table" data-filter-key="fin-status" class="btn-check financial-filter" name="finFilter" id="finAll" value="all" checked>
                    <label class="btn btn-sm btn-outline-primary border-0 rounded-pill px-3" for="finAll">{{ __('center::students.all') }}</label>
                    
                    <input type="radio" data-smart-filter=".custom-table" data-filter-key="fin-status" class="btn-check financial-filter" name="finFilter" id="finDebt" value="debt">
                    <label class="btn btn-sm btn-outline-danger border-0 rounded-pill px-3" for="finDebt">{{ __('center::students.debtor') }}</label>
                    
                    <input type="radio" data-smart-filter=".custom-table" data-filter-key="fin-status" class="btn-check financial-filter" name="finFilter" id="finPaid" value="paid">
                    <label class="btn btn-sm btn-outline-success border-0 rounded-pill px-3" for="finPaid">{{ __('center::students.paid') }}</label>
                </div>
                <!-- Hidden input to link stage/grade buttons with smart search -->
                <input type="hidden" id="smartGradeFilter" data-smart-filter=".custom-table" data-filter-key="grade" value="all">
            </div>

            <!-- Sub-grade Buttons (Hidden by default) -->
            <div class="mb-4">
                @foreach($stages as $stage)
                    <div id="stage-{{ $stage->id }}-grades" class="sub-grades-container" style="display: none;">
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($stage->grades as $grade)
                                <button class="btn btn-sm btn-outline-secondary rounded-pill grade-btn" data-grade="{{ $grade->id }}">{{ $grade->name }}</button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Bulk Actions Toolbar (Hidden by default) -->
            <div id="bulk-actions-toolbar" class="bg-primary bg-opacity-10 p-3 rounded-4 mb-3 d-none animate__animated animate__fadeInDown">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary rounded-pill me-3" id="selected-count">0</span>
                        <span class="fw-bold text-primary">{{ __('center::students.selected_count') }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-success rounded-pill px-3" onclick="bulkAction('activate')">
                            <i class="fas fa-check me-1"></i> {{ __('center::students.activate') }}
                        </button>
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="bulkAction('deactivate')">
                            <i class="fas fa-times me-1"></i> {{ __('center::students.deactivate') }}
                        </button>
                        <button class="btn btn-sm btn-danger rounded-pill px-3" onclick="bulkAction('delete')">
                            <i class="fas fa-trash me-1"></i> {{ __('center::students.delete') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="table-responsive" style="min-height: 350px;">
                <table class="table align-middle custom-table">
                    <thead>
                        <tr>
                            <th class="border-0 bg-transparent px-3" style="width: 40px;">
                                <div class="form-check custom-check">
                                    <input class="form-check-input" type="checkbox" id="select-all">
                                </div>
                            </th>
                            <th class="border-0 bg-transparent">{{ __('center::students.name') }}</th>
                            <th class="border-0 bg-transparent">{{ __('center::students.phone') }}</th>
                            <th class="border-0 bg-transparent d-none d-lg-table-cell">{{ __('center::students.grade') }}</th>
                            <th class="border-0 bg-transparent">{{ __('center::students.status') }}</th>
                            <th class="border-0 bg-transparent text-end px-4">{{ __('center::students.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="border-0">
                        @forelse($students as $student)
                            <tr class="student-row align-middle border-bottom" data-grade="{{ $student->grade_id }}" data-fin-status="{{ $student->financial_status }}" data-name="{{ $student->name }}" data-phone="{{ $student->phone }}">
                                <td class="px-3">
                                    <div class="form-check custom-check">
                                        <input class="form-check-input student-checkbox" type="checkbox" value="{{ $student->id }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="student-avatar me-3">
                                            @if($student->profile_photo)
                                                <img src="{{ Storage::url($student->profile_photo) }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 38px; height: 38px; object-fit: cover;">
                                            @else
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;">
                                                    <span class="fw-bold small">{{ mb_substr($student->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small mb-0">{{ $student->name }}</div>
                                            <div class="text-muted extra-small d-lg-none">{{ $student->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="d-flex align-items-center text-dark small" dir="ltr">
                                            <i class="fas fa-mobile-screen-button me-2 text-primary opacity-50" style="font-size: 0.8rem;"></i>
                                            {{ $student->phone }}
                                        </div>
                                        @if($student->parent_phone)
                                            <div class="d-flex align-items-center text-muted extra-small" dir="ltr">
                                                <i class="fas fa-user-shield me-2 opacity-50" style="font-size: 0.7rem;"></i>
                                                {{ $student->parent_phone }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <div class="d-flex flex-column">
                                        <span class="badge bg-light text-dark fw-normal rounded-pill px-2 py-1 border mb-1" style="font-size: 0.75rem;">
                                            {{ $student->grade_level_name }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-start">
                                        <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $student->status == 'active' ? 'success' : 'danger' }} rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                            {{ $student->status == 'active' ? __('center::students.active') : __('center::students.stopped') }}
                                        </span>
                                        @if($student->total_balance > 0)
                                            <span class="text-danger extra-small fw-bold mt-1">{{ number_format($student->total_balance, 0) }} {{ get_currency_symbol() }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end px-4">
                                    <div class="d-inline-flex gap-1 me-2">
                                        @php
                                            $phoneForWa = sanitizePhoneForWhatsApp($student->phone);
                                            $reportMsg = "تقرير الطالب: {$student->name}\nالمبلغ المتبقي: " . number_format($student->total_balance, 0) . " " . get_currency_symbol() . "\nشكراً لمتابعتكم.";
                                        @endphp
                                        <a href="https://api.whatsapp.com/send?phone={{ $phoneForWa }}" target="_blank" class="btn btn-sm btn-light rounded-circle text-success shadow-none p-2" title="{{ __('center::students.whatsapp') }}">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-light rounded-circle text-primary shadow-none p-2 quick-pay-btn" 
                                                data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-balance="{{ $student->total_balance }}" title="{{ __('center::students.quick_pay') }}">
                                            <i class="fas fa-dollar-sign"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light rounded-circle text-info shadow-none p-2 quick-enroll-btn" 
                                                data-id="{{ $student->id }}" data-name="{{ $student->name }}" 
                                                data-enrolled="{{ $student->enrollments->pluck('course_id')->implode(',') }}"
                                                title="{{ __('center::students.enroll_in_course') }}">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <div class="dropdown d-inline-block">
                                            <button type="button" class="btn btn-sm btn-light rounded-circle text-secondary shadow-none p-2" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('center::students.quick_report') }}">
                                                <i class="fas fa-share-nodes"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3">
                                                <li>
                                                    <a class="dropdown-item rounded-3 mb-1 text-success" href="https://api.whatsapp.com/send?phone={{ $phoneForWa }}&text={{ urlencode($reportMsg) }}" target="_blank">
                                                        <i class="fab fa-whatsapp me-2"></i> {{ __('center::students.whatsapp') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item rounded-3 text-primary direct-email-btn" 
                                                            data-id="{{ $student->id }}" 
                                                            data-subject="{{ __('center::students.student_report_title', ['name' => $student->name]) }}" 
                                                            data-message="{{ $reportMsg }}">
                                                        <i class="fas fa-envelope me-2"></i> {{ __('center::students.direct_email') }}
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-icon btn-light rounded-circle shadow-none" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-4">
                                            <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('center.students.show', $student->id) }}"><i class="fas fa-eye me-2 text-primary opacity-75"></i> {{ __('center::students.view_details') }}</a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('center.students.edit', $student->id) }}"><i class="fas fa-edit me-2 text-info opacity-75"></i> {{ __('center::students.edit') }}</a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1" target="_blank" href="{{ route('center.students.id-card', $student->id) }}"><i class="fas fa-print me-2 text-secondary opacity-75"></i> {{ __('center::students.print_id_card') }}</a></li>
                                            <li><hr class="dropdown-divider opacity-10"></li>
                                            <li>
                                                <form action="{{ route('center.students.destroy', $student->id) }}" method="POST" class="d-inline delete-student-form" data-name="{{ $student->name }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item rounded-3 text-danger mb-0">
                                                        <i class="fas fa-trash-alt me-2 opacity-75"></i> {{ __('center::students.delete') }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 px-3">
                                    <div class="mb-3">
                                        <!-- Replace generic img with a nice icon if image doesn't exist, to be safe -->
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                            <i class="fas fa-user-graduate fa-2x text-primary opacity-75"></i>
                                        </div>
                                    </div>
                                    <h5 class="text-dark fw-bold mb-2">لا يوجد طلاب مسجلون بعد</h5>
                                    <p class="text-muted small px-3 mx-auto mb-4" style="max-width: 400px;">
                                        ابدأ رحلتك بإضافة أول طالب للمنصة لتتمكن من تسجيل الحضور وإدارة الفواتير.
                                    </p>
                                    @can('add students')
                                    <a href="{{ route('center.students.create', ['tenant' => $tenant->domain ?? 'center']) }}" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm transition-all hover-shadow-lg">
                                        <i class="fas fa-plus me-2"></i> إضافة أول طالب
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $students->links('components.ui.pagination') }}
            </div>
        </div>
    </div>
@endsection

@push('modals')
    {{-- Quick Payment Modal --}}
    <div class="modal fade" id="quickPayModal" tabindex="-1" aria-labelledby="quickPayModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <form action="{{ route('center.sales.mark-paid') }}" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" id="payStudentId">
                    <div class="modal-body p-4 text-center">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 mb-3 d-inline-block">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-1" id="payStudentName"></h5>
                        <p class="text-muted small mb-4">{{ __('center::students.quick_pay_desc') }}</p>
                        
                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold small text-muted">{{ __('center::students.collected_amount') }}</label>
                            <div class="input-group">
                                <input type="number" name="amount" id="payAmountInput" class="form-control rounded-start-pill" required>
                                <span class="input-group-text rounded-end-pill">{{ get_currency_symbol() }}</span>
                            </div>
                            <div id="payBalanceHint" class="x-small text-danger mt-1"></div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold">{{ __('center::students.confirm_payment') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Quick Enroll Modal --}}
    <div class="modal fade" id="quickEnrollModal" tabindex="-1" aria-labelledby="quickEnrollModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <form id="enrollForm" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" id="enrollStudentId">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="fw-bold"><i class="fas fa-plus-circle me-2 text-info"></i>{{ __('center::students.enroll_in_course') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="small text-muted mb-4">{{ __('center::students.quick_enroll_desc', ['name' => '<span class="fw-bold text-dark" id="enrollStudentName"></span>']) }}</p>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">{{ __('center::students.available_courses') }}</label>
                            <select id="courseSelect" class="form-select rounded-pill" required>
                                <option value="">{{ __('center::students.choose_course') }}</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }} ({{ number_format($course->price, 0) }} {{ get_currency_symbol() }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="alert alert-soft-info x-small border-0 rounded-3">
                            {{ __('center::students.auto_invoice_hint') }}
                        </div>
                        <div id="enrollWarning" class="alert alert-soft-danger x-small border-0 rounded-3 mt-2 d-none">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ __('center::students.already_enrolled_warning') }}
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" id="submitEnrollBtn" class="btn btn-info text-white w-100 rounded-pill py-2 fw-bold">{{ __('center::students.complete_enrollment') }}</button>
                    </div>
                </form>
            </div>
    </div>

    {{-- Hidden Form for Direct Email --}}
    <form id="directEmailForm" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="subject" id="directEmailSubject">
        <textarea name="message" id="directEmailMessage"></textarea>
    </form>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stageBtns = document.querySelectorAll('.stage-btn');
            const subGradeContainers = document.querySelectorAll('.sub-grades-container');
            const smartGradeFilter = document.getElementById('smartGradeFilter');

            function updateSmartGradeFilter(grades) {
                if (smartGradeFilter) {
                    smartGradeFilter.value = grades ? grades : 'all';
                    smartGradeFilter.dispatchEvent(new Event('change'));
                }
            }

            stageBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    stageBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    subGradeContainers.forEach(c => c.style.display = 'none');
                    
                    const stage = this.getAttribute('data-stage');
                    if (stage === 'all') {
                        updateSmartGradeFilter(null);
                    } else {
                        const gradesAttr = this.getAttribute('data-grades');
                        updateSmartGradeFilter(gradesAttr);
                        const subGradeContainer = document.getElementById(stage + '-grades');
                        if (subGradeContainer) {
                            subGradeContainer.style.display = 'block';
                        }
                    }
                });
            });

            // Quick Payment Logic
            const payModalEl = document.getElementById('quickPayModal');
            const payModal = payModalEl ? new bootstrap.Modal(payModalEl) : null;
            document.querySelectorAll('.quick-pay-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (payModal) {
                        document.getElementById('payStudentId').value = this.dataset.id;
                        document.getElementById('payStudentName').textContent = this.dataset.name;
                        document.getElementById('payAmountInput').value = this.dataset.balance;
                        document.getElementById('payBalanceHint').textContent = '{{ __('center::students.current_balance') }}: ' + this.dataset.balance + ' {{ get_currency_symbol() }}';
                        payModal.show();
                    }
                });
            });

            // Quick Enroll Logic
            const enrollModalEl = document.getElementById('quickEnrollModal');
            const enrollModal = enrollModalEl ? new bootstrap.Modal(enrollModalEl) : null;
            const courseSelect = document.getElementById('courseSelect');
            const enrollForm = document.getElementById('enrollForm');

            document.querySelectorAll('.quick-enroll-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (enrollModal) {
                        const enrolledIds = this.dataset.enrolled ? this.dataset.enrolled.split(',') : [];
                        document.getElementById('enrollStudentId').value = this.dataset.id;
                        document.getElementById('enrollStudentName').textContent = this.dataset.name;
                        
                        const options = courseSelect.querySelectorAll('option');
                        options.forEach(opt => {
                            if (opt.value && enrolledIds.includes(opt.value)) {
                                opt.setAttribute('data-enrolled', 'true');
                                if (!opt.textContent.includes('{{ __('center::students.already_enrolled_label') }}')) {
                                    opt.textContent = opt.textContent + ' {{ __('center::students.already_enrolled_label') }}';
                                }
                            } else {
                                opt.removeAttribute('data-enrolled');
                                opt.textContent = opt.textContent.replace(' {{ __('center::students.already_enrolled_label') }}', '');
                            }
                        });
                        
                        if ($.fn.select2) {
                            $(courseSelect).val("").trigger('change');
                        } else {
                            courseSelect.value = "";
                        }
                        
                        document.getElementById('enrollWarning').classList.add('d-none');
                        document.getElementById('submitEnrollBtn').disabled = false;
                        
                        enrollModal.show();
                    }
                });
            });

            $(courseSelect).on('change', function() {
                const selectedOpt = this.options[this.selectedIndex];
                const isEnrolled = selectedOpt && selectedOpt.getAttribute('data-enrolled') === 'true';
                const warning = document.getElementById('enrollWarning');
                const submitBtn = document.getElementById('submitEnrollBtn');
                
                if (isEnrolled) {
                    warning.classList.remove('d-none');
                    submitBtn.disabled = true;
                    submitBtn.classList.replace('btn-info', 'btn-secondary');
                } else {
                    warning.classList.add('d-none');
                    submitBtn.disabled = false;
                    submitBtn.classList.replace('btn-secondary', 'btn-info');
                }
            });

            document.getElementById('submitEnrollBtn').addEventListener('click', function() {
                const courseId = courseSelect.value;
                if (!courseId) return alert('{{ __('center::students.choose_course_first') }}');
                enrollForm.action = `/courses/${courseId}/enroll`;
                enrollForm.submit();
            });

            document.querySelectorAll('.grade-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.grade-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    updateSmartGradeFilter(this.getAttribute('data-grade'));
                });
            });

            // Bulk Action logic
            const selectAll = document.getElementById('select-all');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            const bulkToolbar = document.getElementById('bulk-actions-toolbar');
            const selectedCount = document.getElementById('selected-count');

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    studentCheckboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateBulkToolbar();
                });
            }

            studentCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkToolbar);
            });

            function updateBulkToolbar() {
                const checkedCount = Array.from(studentCheckboxes).filter(cb => cb.checked).length;
                if (checkedCount > 0) {
                    bulkToolbar.classList.remove('d-none');
                    selectedCount.textContent = checkedCount;
                } else {
                    bulkToolbar.classList.add('d-none');
                    if (selectAll) selectAll.checked = false;
                }
            }

            window.bulkAction = function(action) {
                const selectedIds = Array.from(studentCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
                if (selectedIds.length === 0) return;
                if (confirm('{{ __('center::students.bulk_confirm', ['count' => "'+selectedIds.length+'"]) }}'.replace("'+selectedIds.length+'", selectedIds.length))) {
                    alert('Processing [' + action + '] for IDs: ' + selectedIds.join(', '));
                }
            };

            // Direct Email Logic
            document.querySelectorAll('.direct-email-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const studentId = this.dataset.id;
                    const subject = this.dataset.subject;
                    const message = this.dataset.message;
                    
                    const form = document.getElementById('directEmailForm');
                    form.action = `/students/${studentId}/send-email`;
                    document.getElementById('directEmailSubject').value = subject;
                    document.getElementById('directEmailMessage').value = message;
                    
                    if (confirm('{{ __('center::students.send_report_confirm') }}')) {
                        form.submit();
                    }
                });
            });

            // Removed manual filterStudents() since it's handled by smart-search.js
        });

        // AJAX Deletion with Undo Functionality
        $(document).on('submit', '.delete-student-form', function(e) {
            e.preventDefault();
            const form = $(this);
            const studentName = form.data('name');
            const row = form.closest('.student-row');

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: `سيتم حذف الطالب ${studentName}. يمكنك التراجع عن هذا الإجراء.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hide row immediately for UX
                    row.fadeOut();

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'تم الحذف بنجاح',
                                    text: `تم نقل الطالب ${studentName} إلى سلة المهملات.`,
                                    icon: 'success',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: true,
                                    confirmButtonText: 'تراجع (Undo)',
                                    timer: 8000,
                                    timerProgressBar: true
                                }).then((undoResult) => {
                                    if (undoResult.isConfirmed) {
                                        // Trigger restore
                                        $.post(response.restore_url, { _token: '{{ csrf_token() }}' }, function(restoreRes) {
                                            if (restoreRes.success) {
                                                row.fadeIn();
                                                Swal.fire({
                                                    title: 'تمت الاستعادة',
                                                    text: 'تمت استعادة الطالب بنجاح.',
                                                    icon: 'success',
                                                    toast: true,
                                                    position: 'top-end',
                                                    timer: 3000
                                                });
                                            }
                                        });
                                    }
                                });
                            }
                        },
                        error: function() {
                            row.fadeIn();
                            Swal.fire('خطأ', 'حدث خطأ أثناء الحذف.', 'error');
                        }
                    });
                }
            });
        });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('#courseSelect').select2({
                    dropdownParent: $('#quickEnrollModal'),
                    width: '100%',
                    language: {
                        noResults: function() { return "{{ __('center::students.no_results') }}"; }
                    }
                });
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .custom-table thead th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            padding-bottom: 15px;
        }
        .student-row { transition: all 0.2s ease; }
        .student-row:hover { background-color: #f8fbff; }
        .student-row td { height: 70px; }
        .btn-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; padding: 0; }
        .x-small { font-size: 0.75rem; }
        .stage-btn.active {
            background-color: var(--bs-primary);
            color: white;
            border-color: var(--bs-primary);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }
        .grade-btn.active { background-color: var(--bs-secondary); color: white; border-color: var(--bs-secondary); }
        .sub-grades-container { padding: 10px 0; border-top: 1px dashed #dee2e6; }
        .animate__animated { --animate-duration: 0.5s; }
        
        /* Modal Fix for z-index issues */
        .modal { z-index: 1060 !important; }
        .modal-backdrop { z-index: 1050 !important; }

        /* Select2 Premium Emerald Styling */
        .select2-container--default .select2-selection--single {
            border-radius: 50px !important;
            height: 45px !important;
            border: 1px solid #eee !important;
            padding-top: 8px !important;
            padding-left: 15px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 10px !important;
            right: 15px !important;
        }
        .select2-dropdown {
            border-radius: 15px !important;
            border: 1px solid #eee !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05) !important;
            overflow: hidden !important;
        }
        .select2-search__field {
            border-radius: 50px !important;
            padding: 8px 15px !important;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
