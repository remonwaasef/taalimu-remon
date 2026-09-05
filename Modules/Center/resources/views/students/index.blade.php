@extends('center::layouts.app-next')
@section('page-title', __('center::students.title'))

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('center.students.export') }}" class="btn btn-glass" id="export-students-btn">
            <i class="fas fa-file-export me-2"></i> {{ __('center::students.export_file') }}
        </a>
        <a href="{{ route('center.students.import') }}" class="btn btn-glass">
            <i class="fas fa-file-import me-2"></i> {{ __('center::students.import_file') }}
        </a>
        <a href="{{ route('center.students.create') }}" class="btn btn-primary">
            <span class="me-2">+</span> {{ __('center::students.add_new') }}
        </a>
    </div>
@endsection

@section('panel-content')

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

                        @php
                            $email = session('student_email');
                            $hasCustomEmail = $email && !preg_match('/^std\d+\./', $email);
                            $cleanPhone = sanitizePhoneForWhatsApp(session('student_phone'));
                            $tenantName = app('tenant')->name ?? 'المركز';
                            $loginUrl = url('/login');

                            // Concise, essential message only
                            $whatsappText = "مرحباً " . session('student_name') . "، تم تسجيلك بنجاح في {$tenantName}! 🎉\n\nبيانات تسجيل الدخول لحسابك:\n📱 رقم الهاتف: " . session('student_phone') . "\n🔑 كلمة المرور: " . session('generated_password') . "\n🌐 رابط المنصة: " . $loginUrl;
                        @endphp

                        <div class="row g-4 mt-2">
                            <div class="col-sm-{{ $hasCustomEmail ? '3' : '4' }}">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">{{ __('center::students.name') }}</label>
                                <span class="fw-bold fs-5 text-dark">{{ session('student_name') }}</span>
                            </div>
                            <div class="col-sm-{{ $hasCustomEmail ? '3' : '4' }}">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">{{ __('center::students.phone') }}</label>
                                <span class="fw-bold text-dark fs-5 font-monospace">{{ session('student_phone') }}</span>
                            </div>
                            @if($hasCustomEmail)
                            <div class="col-sm-3">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">{{ __('center::students.email') }}</label>
                                <span class="text-primary fw-bold">{{ $email }}</span>
                            </div>
                            @endif
                            <div class="col-sm-{{ $hasCustomEmail ? '3' : '4' }}">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">{{ __('center::students.temporary_password') }}</label>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-4 fw-bold text-danger font-monospace">{{ session('generated_password') }}</span>
                                    <button type="button" onclick="copyToClipboard('{{ session('generated_password') }}')" class="btn btn-sm btn-light rounded-circle shadow-sm" title="{{ __('center::students.copy') }}">
                                        <i class="fas fa-copy text-primary"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex flex-wrap align-items-center gap-2">
                            <!-- Send WhatsApp Direct Button -->
                            <button type="button" onclick="openSmartWhatsApp('{{ $cleanPhone }}')" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm">
                                <i class="fab fa-whatsapp me-2 fs-5"></i> {{ __('center::students.send_whatsapp') }}
                            </button>

                            <!-- Copy Details Button -->
                            <button type="button" onclick="copyAllDetails()" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold shadow-sm">
                                <i class="fas fa-copy me-2"></i> {{ __('center::students.copy_all_data') }}
                            </button>
                        </div>
                    </div>

                    <!-- Right Side: QR Code -->
                    <div class="col-md-4 p-4 text-center d-flex flex-column align-items-center justify-content-center bg-light rounded-end-4 border-start border-dashed position-relative">
                        <div class="ticket-stub-decoration top"></div>
                        <div class="ticket-stub-decoration bottom"></div>
                        
                        @php
                            $studentForQr = session('student_email')
                                ? \App\Models\Student::where('email', session('student_email'))->where('tenant_id', app('tenant')->id)->first()
                                : null;

                            $qrUrl = $studentForQr
                                ? \Illuminate\Support\Facades\URL::temporarySignedRoute('center.login.magic', now()->addHours(24), ['student' => $studentForQr->id, 'tenant' => app('tenant')->domain])
                                : url('/login');
                        @endphp
                        <div class="qr-container bg-white p-2 rounded-3 shadow-sm mb-3">
                            <div class="student-local-qr d-flex justify-content-center" style="width: 140px; height: 140px;" data-qr="{{ $qrUrl }}"></div>
                        </div>
                        @push('scripts')
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                document.querySelectorAll('.student-local-qr').forEach(function (el) {
                                    if (typeof QRCode !== 'undefined' && el.dataset.qr) {
                                        new QRCode(el, { text: el.dataset.qr, width: 130, height: 130, correctLevel: QRCode.CorrectLevel.H });
                                    }
                                });
                            });
                        </script>
                        @endpush
                        <p class="small text-muted mb-0">{{ __('center::students.scan_qr_tip') }}</p>
                        <div class="mt-3 text-secondary small">
                            <i class="fas fa-clock me-1"></i> {{ __('center::students.qr_expires_15') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function() {
                    showSuccessToast('{{ __('center::students.copy_success') }}');
                });
            }

            function copyAllDetails() {
                const text = @json($whatsappText);
                navigator.clipboard.writeText(text).then(function() {
                    showSuccessToast('{{ __('center::students.copy_all_success') }}');
                });
            }

            function openSmartWhatsApp(phone) {
                const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
                const msg = @json($whatsappText);
                const encoded = encodeURIComponent(msg);

                if (isMobile) {
                    window.open('https://api.whatsapp.com/send?phone=' + phone + '&text=' + encoded, '_blank');
                } else {
                    // Open WhatsApp Web directly on desktop
                    window.open('https://web.whatsapp.com/send?phone=' + phone + '&text=' + encoded, '_blank');
                }
            }

            function showSuccessToast(message) {
                const toast = document.createElement('div');
                toast.className = 'position-fixed bottom-0 start-50 translate-middle-x mb-5 bg-dark text-white py-2 px-4 rounded-pill shadow-lg animate__animated animate__fadeInUp';
                toast.style.zIndex = '99999';
                toast.innerHTML = '<i class="fas fa-check-circle text-success me-2"></i> ' + message;
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.classList.remove('animate__fadeInUp');
                    toast.classList.add('animate__fadeOutDown');
                    setTimeout(() => toast.remove(), 400);
                }, 2500);
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

    <div class="card border border-slate-200/90 dark:border-slate-800 shadow-xs rounded-3xl bg-white dark:bg-slate-900 overflow-hidden">
        <div class="card-body p-5 sm:p-6">
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
                            class="form-control ps-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/60 text-slate-800 dark:text-slate-100 shadow-2xs focus:bg-white" 
                            placeholder="{{ __('center::students.search_placeholder') }}"
                            style="height: 48px;"
                        >
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Stage & Financial Filter Buttons -->
            <div class="mb-4 d-flex flex-wrap gap-3 justify-content-between align-items-center">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-outline-primary rounded-xl px-4 stage-btn active font-semibold text-xs" data-stage="all">
                        {{ __('center::students.all') }}
                    </button>
                    @foreach($stages as $stage)
                        <button class="btn btn-outline-primary rounded-xl px-4 stage-btn font-semibold text-xs" data-stage="stage-{{ $stage->id }}" data-grades="{{ $stage->grades->pluck('id')->implode(',') }}">
                            {{ $stage->name }}
                        </button>
                    @endforeach
                </div>

                <div class="btn-group p-1 bg-slate-100 dark:bg-slate-800 rounded-xl border border-slate-200/80 dark:border-slate-700" role="group" style="min-width: 250px;">
                    <input type="radio" data-smart-filter=".custom-table" data-filter-key="fin-status" class="btn-check financial-filter" name="finFilter" id="finAll" value="all" checked>
                    <label class="btn btn-sm btn-outline-primary border-0 rounded-lg px-3 font-semibold text-xs" for="finAll">{{ __('center::students.all') }}</label>
                    
                    <input type="radio" data-smart-filter=".custom-table" data-filter-key="fin-status" class="btn-check financial-filter" name="finFilter" id="finDebt" value="debt">
                    <label class="btn btn-sm btn-outline-danger border-0 rounded-lg px-3 font-semibold text-xs" for="finDebt">{{ __('center::students.debtor') }}</label>
                    
                    <input type="radio" data-smart-filter=".custom-table" data-filter-key="fin-status" class="btn-check financial-filter" name="finFilter" id="finPaid" value="paid">
                    <label class="btn btn-sm btn-outline-success border-0 rounded-lg px-3 font-semibold text-xs" for="finPaid">{{ __('center::students.paid') }}</label>
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
                                <button class="btn btn-sm btn-outline-secondary rounded-xl grade-btn text-xs font-semibold" data-grade="{{ $grade->id }}">{{ $grade->name }}</button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Bulk Actions Toolbar (Hidden by default) -->
            <div id="bulk-actions-toolbar" class="bg-brand-50 dark:bg-brand-900/30 border border-brand-200/60 dark:border-brand-800/40 p-3 rounded-2xl mb-3 d-none animate__animated animate__fadeInDown">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-brand-primary rounded-pill me-3" id="selected-count">0</span>
                        <span class="fw-bold text-brand-primary dark:text-brand-300 small">{{ __('center::students.selected_count') }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-success rounded-xl px-3" onclick="bulkAction('activate')">
                            <i class="fas fa-check me-1"></i> {{ __('center::students.activate') }}
                        </button>
                        <button class="btn btn-sm btn-outline-secondary rounded-xl px-3" onclick="bulkAction('deactivate')">
                            <i class="fas fa-times me-1"></i> {{ __('center::students.deactivate') }}
                        </button>
                        <button class="btn btn-sm btn-danger rounded-xl px-3" onclick="bulkAction('delete')">
                            <i class="fas fa-trash me-1"></i> {{ __('center::students.delete') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="table-responsive rounded-2xl border border-slate-200 dark:border-slate-800" data-mobile-cards style="min-height: 350px;">
                <table class="table align-middle custom-table mb-0">
                    <thead class="bg-slate-50/95 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 text-[11px] uppercase font-bold text-slate-600 dark:text-slate-300 tracking-wider">
                        <tr>
                            <th class="px-3" style="width: 40px;">
                                <div class="form-check custom-check">
                                    <input class="form-check-input" type="checkbox" id="select-all">
                                </div>
                            </th>
                            <th class="px-4 py-3">{{ __('center::students.name') }}</th>
                            <th class="px-4 py-3">{{ __('center::students.phone') }}</th>
                            <th class="px-4 py-3 d-none d-lg-table-cell">{{ __('center::students.grade') }}</th>
                            <th class="px-4 py-3">{{ __('center::students.status') }}</th>
                            <th class="px-4 py-3 text-end">{{ __('center::students.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse($students as $student)
                            <tr class="student-row align-middle hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors" data-grade="{{ $student->grade_id }}" data-fin-status="{{ $student->financial_status }}" data-name="{{ $student->name }}" data-phone="{{ $student->phone }}">
                                <td class="px-3">
                                    <div class="form-check custom-check">
                                        <input class="form-check-input student-checkbox" type="checkbox" value="{{ $student->id }}">
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="d-flex align-items-center">
                                        <div class="student-avatar me-3">
                                            @if($student->profile_photo)
                                                <img src="{{ Storage::url($student->profile_photo) }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 38px; height: 38px; object-fit: cover;">
                                            @else
                                                <div class="bg-brand-50 text-brand-primary dark:bg-brand-900/40 dark:text-brand-300 rounded-circle d-flex align-items-center justify-content-center shadow-xs font-bold" style="width: 38px; height: 38px;">
                                                    <span class="small">{{ mb_substr($student->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-slate-900 dark:text-slate-100 small mb-0">{{ $student->name }}</div>
                                            <div class="text-muted extra-small d-lg-none">{{ $student->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="d-flex flex-column gap-1">
                                        <div class="d-flex align-items-center text-dark small" dir="ltr">
                                            <i class="fas fa-mobile-screen-button me-2 text-brand-primary opacity-60" style="font-size: 0.8rem;"></i>
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
                                <td class="px-4 py-3.5 d-none d-lg-table-cell">
                                    <div class="d-flex flex-column">
                                        <span class="badge bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 fw-semibold rounded-lg px-2.5 py-1 border border-slate-200 dark:border-slate-700" style="font-size: 0.75rem;">
                                            {{ $student->grade_level_name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="d-flex flex-column align-items-start">
                                        <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $student->status == 'active' ? 'success' : 'danger' }} rounded-pill px-2.5 py-1" style="font-size: 0.7rem;">
                                            {{ $student->status == 'active' ? __('center::students.active') : __('center::students.stopped') }}
                                        </span>
                                        @if($student->total_balance > 0)
                                            <span class="text-danger extra-small fw-bold mt-1">{{ number_format($student->total_balance, 0) }} {{ get_currency_symbol() }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-end">
                                    <div class="d-inline-flex gap-1 me-2">
                                        @php
                                            $phoneForWa = sanitizePhoneForWhatsApp($student->phone);
                                            $tenantName = app('tenant')->name ?? 'المركز';
                                            $currency = get_currency_symbol();
                                            if ($student->total_balance > 0) {
                                                $smartWaMsg = __('center::students.quick_wa_balance_msg', [
                                                    'name' => $student->name,
                                                    'center' => $tenantName,
                                                    'balance' => number_format($student->total_balance, 0),
                                                    'currency' => $currency,
                                                ]);
                                            } else {
                                                $smartWaMsg = __('center::students.quick_wa_checkin_msg', [
                                                    'name' => $student->name,
                                                    'center' => $tenantName,
                                                ]);
                                            }
                                            $reportMsg = "تقرير الطالب: {$student->name}\nالمبلغ المتبقي: " . number_format($student->total_balance, 0) . " " . $currency . "\nشكراً لمتابعتكم.";
                                        @endphp
                                        <a href="https://web.whatsapp.com/send?phone={{ $phoneForWa }}&text={{ urlencode($smartWaMsg) }}" onclick="openDirectWhatsApp('{{ $phoneForWa }}', @json($smartWaMsg)); return false;" target="_blank" class="btn btn-sm btn-light rounded-circle text-success shadow-none p-2" title="{{ __('center::students.whatsapp') }}">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-light rounded-circle text-primary shadow-none p-2 quick-pay-btn" 
                                                data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-balance="{{ $student->total_balance }}" title="{{ __('center::students.quick_pay') }}">
                                            <i class="fas fa-hand-holding-dollar"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light rounded-circle text-info shadow-none p-2 quick-enroll-btn" 
                                                data-id="{{ $student->id }}" data-name="{{ $student->name }}" 
                                                data-enrolled="{{ $student->enrollments->pluck('course_id')->implode(',') }}"
                                                onclick="openQuickEnrollModal('{{ $student->id }}', @json($student->name), '{{ $student->enrollments->pluck('course_id')->implode(',') }}')"
                                                title="{{ __('center::students.enroll_in_course') }}">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <div class="dropdown d-inline-block">
                                            <button type="button" class="btn btn-sm btn-light rounded-circle text-secondary shadow-none p-2" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('center::students.quick_report') }}">
                                                <i class="fas fa-share-nodes"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3">
                                                <li>
                                                    <a class="dropdown-item rounded-3 mb-1 text-success" href="https://web.whatsapp.com/send?phone={{ $phoneForWa }}&text={{ urlencode($reportMsg) }}" onclick="openDirectWhatsApp('{{ $phoneForWa }}', @json($reportMsg)); return false;" target="_blank">
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
                                                <form id="delete-student-form-{{ $student->id }}" action="{{ route('center.students.destroy', $student->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button" class="dropdown-item rounded-3 text-danger mb-0"
                                                        data-confirm-delete
                                                        data-form="delete-student-form-{{ $student->id }}"
                                                        data-title="{{ __('center::students.delete_confirm_title') ?? 'هل أنت متأكد من حذف الطالب؟' }}"
                                                        data-text="{{ $student->name }}">
                                                    <i class="fas fa-trash-alt me-2 opacity-75"></i> {{ __('center::students.delete') }}
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 px-3">
                                    <div class="mb-3">
                                        <div class="rounded-2xl bg-brand-50 text-brand-primary dark:bg-brand-900/30 dark:text-brand-300 d-inline-flex align-items-center justify-content-center mb-3 shadow-xs" style="width: 72px; height: 72px;">
                                            <i class="fas fa-user-graduate text-3xl"></i>
                                        </div>
                                    </div>
                                    <h5 class="text-slate-900 dark:text-slate-100 fw-bold mb-2 text-base">لا يوجد طلاب مسجلون بعد</h5>
                                    <p class="text-slate-500 dark:text-slate-400 small px-3 mx-auto mb-4" style="max-width: 400px;">
                                        ابدأ رحلتك بإضافة أول طالب للمنصة لتتمكن من تسجيل الحضور وإدارة الفواتير.
                                    </p>
                                    @can('add students')
                                    <a href="{{ route('center.students.create', ['tenant' => $tenant->domain ?? 'center']) }}" class="btn btn-primary rounded-xl px-5 py-2.5 font-bold shadow-xs">
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
    <x-ui.modal id="quickPayModal" title="{{ __('center::students.quick_pay_title') }}" size="sm">
        <form action="{{ route('center.sales.mark-paid') }}" method="POST">
            @csrf
            <input type="hidden" name="student_id" id="payStudentId">
            <div class="text-center">
                <div class="rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 p-3 mb-3 inline-flex">
                    <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
                <h3 class="font-semibold mb-1" id="payStudentName"></h3>
                <p class="text-slate-500 text-sm mb-4">{{ __('center::students.quick_pay_desc') }}</p>
                
                <div class="mb-4 text-start">
                    <label class="block font-medium text-sm text-slate-500 mb-1">{{ __('center::students.collected_amount') }}</label>
                    <div class="flex gap-2">
                        <input type="number" name="amount" id="payAmountInput" class="flex-1 px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-brand-primary focus:border-transparent" required>
                        <span class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-500">{{ get_currency_symbol() }}</span>
                    </div>
                    <div id="payBalanceHint" class="text-xs text-red-500 mt-1"></div>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl transition-colors">{{ __('center::students.confirm_payment') }}</button>
            </div>
        </form>
    </x-ui.modal>

    {{-- Quick Enroll Modal --}}
    <x-ui.modal id="quickEnrollModal" title="{{ __('center::students.enroll_in_course') }}" size="md">
        <form id="enrollForm" method="POST" onsubmit="return handleEnrollSubmit(event)">
            @csrf
            <input type="hidden" name="student_id" id="enrollStudentId">
            <div class="text-center">
                <div class="rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 p-3 mb-3 inline-flex">
                    <i class="fas fa-graduation-cap fa-2x"></i>
                </div>
                <h3 class="font-semibold mb-1 text-slate-900 dark:text-slate-100" id="enrollStudentName"></h3>
                <p class="text-slate-500 text-sm mb-4">{{ __('center::students.quick_enroll_desc_short') }}</p>
                <div class="mb-4 text-start">
                    <label class="block font-medium text-sm text-slate-700 dark:text-slate-300 mb-1.5">{{ __('center::students.available_courses') }}</label>
                    <select id="courseSelect" name="course_id" onchange="handleCourseSelectChange(this)" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-brand-primary focus:border-transparent text-sm" required>
                        <option value="">-- {{ __('center::students.choose_course') }} --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" data-original-text="{{ $course->title }} ({{ number_format($course->price, 0) }} {{ get_currency_symbol() }})">{{ $course->title }} ({{ number_format($course->price, 0) }} {{ get_currency_symbol() }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4 p-3 bg-brand-50 dark:bg-brand-900/30 border border-brand-200 dark:border-brand-800 rounded-xl text-sm text-brand-700 dark:text-brand-300 text-start">
                    <i class="fas fa-info-circle me-1.5"></i> {{ __('center::students.auto_invoice_hint') }}
                </div>
                <div id="enrollWarning" class="mb-4 p-3 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-xl text-sm text-amber-800 dark:text-amber-200 text-start d-none">
                    <i class="fas fa-info-circle me-1.5"></i> <span id="enrollWarningText">{{ __('center::students.already_enrolled_warning') }}</span>
                </div>
                <div class="flex gap-2 justify-end pt-2">
                    <button type="button" class="btn btn-light rounded-xl px-4 py-2" @click="show = false">{{ __('center::students.close') }}</button>
                    <button type="submit" id="submitEnrollBtn" class="btn btn-primary rounded-xl px-5 py-2 fw-bold">
                        <i class="fas fa-check-circle me-1.5"></i> {{ __('center::students.complete_enrollment') }}
                    </button>
                </div>
            </div>
        </form>
    </x-ui.modal>

    {{-- Hidden Form for Direct Email --}}
    <form id="directEmailForm" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="subject" id="directEmailSubject">
        <textarea name="message" id="directEmailMessage"></textarea>
    </form>
@endpush

@push('scripts')
    @include('center::students.partials._index-scripts')
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
