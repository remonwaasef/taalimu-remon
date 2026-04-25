@extends('center::layouts.hope-master')

@php
    if (!function_exists('sanitizePhoneForWhatsApp')) {
        function sanitizePhoneForWhatsApp($phone) {
            if (!$phone) return '';
            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (str_starts_with($phone, '0')) {
                $countryCode = app('tenant')->settings['default_country_code'] ?? '20';
                $phone = $countryCode . substr($phone, 1);
            }
            return $phone;
        }
    }
@endphp

@section('page-title', __('center::students.title'))

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('center.students.export') }}" class="btn btn-glass" id="export-students-btn">
            <i class="fas fa-file-export me-2"></i> {{ __('center::students.export_file') ?? __('center::messages.blade_0808') }}
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
                                $mailtoUrl = "mailto:" . session('student_email') . "?subject=" . urlencode(__('center::messages.blade_0744')) . "&body=" . rawurlencode($msg);
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
                const text = `{!! addslashes($msg) !!}`;
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
                    <input type="radio" class="btn-check financial-filter" name="finFilter" id="finAll" value="all" checked>
                    <label class="btn btn-sm btn-outline-primary border-0 rounded-pill px-3" for="finAll">الكل</label>
                    
                    <input type="radio" class="btn-check financial-filter" name="finFilter" id="finDebt" value="debt">
                    <label class="btn btn-sm btn-outline-danger border-0 rounded-pill px-3" for="finDebt">مديون</label>
                    
                    <input type="radio" class="btn-check financial-filter" name="finFilter" id="finPaid" value="paid">
                    <label class="btn btn-sm btn-outline-success border-0 rounded-pill px-3" for="finPaid">مسدد</label>
                </div>
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
                            <th class="border-0 bg-transparent d-none d-lg-table-cell">{{ __('center::students.email') }}</th>
                            <th class="border-0 bg-transparent">{{ __('center::students.grade') }}</th>
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
                                                <img src="{{ Storage::url($student->profile_photo) }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 42px; height: 42px; object-fit: cover;">
                                            @else
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                                                    <span class="fw-bold">{{ mb_substr($student->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0">
                                                {{ $student->name }}
                                                @if($student->total_balance > 500)
                                                    <span class="ms-1 text-danger small animate__animated animate__flash animate__infinite" title="مديونية مرتفعة!"><i class="fas fa-exclamation-triangle"></i></span>
                                                @endif
                                            </div>
                                            <div class="text-muted x-small d-lg-none">{{ $student->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="text-dark small fw-medium">{{ $student->phone }}</span>
                                            <a href="tel:{{ $student->phone }}" class="text-primary small"><i class="fas fa-phone-flip x-small"></i></a>
                                        </div>
                                        @if($student->parent_phone)
                                            <div class="d-flex align-items-center gap-1 text-muted x-small">
                                                <span>{{ __('center::students.parent_phone') }}: {{ $student->parent_phone }}</span>
                                                <a href="tel:{{ $student->parent_phone }}" class="text-muted"><i class="fas fa-phone-flip extra-small"></i></a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-muted small d-none d-lg-table-cell">{{ $student->email }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge bg-light text-dark fw-normal rounded-pill px-2 py-1 border mb-1">
                                            {{ $student->grade_level_name }}
                                        </span>
                                        @if($student->school_name || $student->section_type)
                                            <span class="text-muted extra-small">
                                                {{ $student->school_name }}{{ $student->school_name && $student->section_type ? ' - ' : '' }}{{ $student->section_type }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $student->status == 'active' ? 'success' : 'danger' }} rounded-pill px-3">
                                        <i class="fas {{ $student->status == 'active' ? 'fa-check' : 'fa-times' }} me-1 small"></i>
                                        {{ $student->status == 'active' ? __('center::students.active') : __('center::students.stopped') }}
                                    </span>
                                    <div class="mt-1">
                                        @if($student->total_balance > 0)
                                            <span class="badge bg-danger bg-opacity-10 text-danger x-small rounded-pill">{{ number_format($student->total_balance, 0) }} ج.م متبقي</span>
                                        @else
                                            <span class="badge bg-success bg-opacity-10 text-success x-small rounded-pill">مسدد بالكامل</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end px-4">
                                    <div class="d-inline-flex gap-1 me-2">
                                        @php
                                            $phoneForWa = sanitizePhoneForWhatsApp($student->phone);
                                            $reportMsg = "تقرير الطالب: {$student->name}\nالمبلغ المتبقي: " . number_format($student->total_balance, 0) . " ج.م\nشكراً لمتابعتكم.";
                                        @endphp
                                        <a href="https://api.whatsapp.com/send?phone={{ $phoneForWa }}" target="_blank" class="btn btn-sm btn-light rounded-circle text-success shadow-none p-2" title="واتساب">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-light rounded-circle text-primary shadow-none p-2 quick-pay-btn" 
                                                data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-balance="{{ $student->total_balance }}" title="تحصيل سريع">
                                            <i class="fas fa-dollar-sign"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light rounded-circle text-info shadow-none p-2 quick-enroll-btn" 
                                                data-id="{{ $student->id }}" data-name="{{ $student->name }}" title="تسجيل في كورس">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <a href="https://api.whatsapp.com/send?phone={{ $phoneForWa }}&text={{ urlencode($reportMsg) }}" target="_blank" class="btn btn-sm btn-light rounded-circle text-secondary shadow-none p-2" title="تقرير سريع">
                                            <i class="fas fa-share-nodes"></i>
                                        </a>
                                    </div>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-icon btn-light rounded-circle shadow-none" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-4">
                                            <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('center.students.show', $student->id) }}"><i class="fas fa-eye me-2 text-primary opacity-75"></i> {{ __('center::students.view_details') }}</a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('center.students.edit', $student->id) }}"><i class="fas fa-edit me-2 text-info opacity-75"></i> {{ __('center::students.edit') }}</a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1" href="javascript:window.print()"><i class="fas fa-print me-2 text-secondary opacity-75"></i> طباعة الكارنيه</a></li>
                                            <li><hr class="dropdown-divider opacity-10"></li>
                                            <li>
                                                <form action="{{ route('center.students.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('center::students.confirm_delete_student') }}');">
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
                                <td colspan="7" class="text-center py-5">
                                    <div class="mb-4">
                                        <div class="d-inline-flex p-4 rounded-circle mb-3" style="background: rgba(16, 185, 129, 0.05);">
                                            <i class="fas fa-user-graduate text-primary" style="font-size: 3rem; opacity: 0.5;"></i>
                                        </div>
                                    </div>
                                    <h5 class="text-muted fw-bold">{{ __('center::students.no_students') }}</h5>
                                    <p class="text-muted small">قم بإضافة طلاب جدد أو استيرادهم من ملف اكسيل للبدء.</p>
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

    {{-- Quick Payment Modal --}}
    <div class="modal fade" id="quickPayModal" tabindex="-1" aria-hidden="true">
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
                        <p class="text-muted small mb-4">تحصيل سريع للمستحقات</p>
                        
                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold small text-muted">المبلغ المحصل</label>
                            <div class="input-group">
                                <input type="number" name="amount" id="payAmountInput" class="form-control rounded-start-pill" required>
                                <span class="input-group-text rounded-end-pill">ج.م</span>
                            </div>
                            <div id="payBalanceHint" class="x-small text-danger mt-1"></div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold">تأكيد الاستلام</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Quick Enroll Modal --}}
    <div class="modal fade" id="quickEnrollModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <form id="enrollForm" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" id="enrollStudentId">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="fw-bold"><i class="fas fa-plus-circle me-2 text-info"></i>تسجيل الطالب في كورس</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="small text-muted mb-4">اختر الكورس الذي ترغب في تسجيل الطالب <span class="fw-bold text-dark" id="enrollStudentName"></span> به.</p>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">الكورسات المتاحة</label>
                            <select id="courseSelect" class="form-select rounded-pill" required>
                                <option value="">اختر الكورس...</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }} ({{ number_format($course->price, 0) }} ج.م)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="alert alert-soft-info x-small border-0 rounded-3">
                            سيتم إنشاء فاتورة "غير مدفوعة" تلقائياً لهذا الطالب.
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" id="submitEnrollBtn" class="btn btn-info text-white w-100 rounded-pill py-2 fw-bold">إتمام التسجيل</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Stage filter buttons
        const stageBtns = document.querySelectorAll('.stage-btn');
        const subGradeContainers = document.querySelectorAll('.sub-grades-container');
        const searchInput = document.getElementById('search-input');
        const finFilters = document.querySelectorAll('.financial-filter');
        let currentStageGrades = null;

        stageBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                stageBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                subGradeContainers.forEach(c => c.style.display = 'none');
                
                const stage = this.getAttribute('data-stage');
                if (stage === 'all') {
                    currentStageGrades = null;
                } else {
                    const gradesAttr = this.getAttribute('data-grades');
                    currentStageGrades = gradesAttr ? gradesAttr.split(',') : [];
                    const subGradeContainer = document.getElementById(stage + '-grades');
                    if (subGradeContainer) {
                        subGradeContainer.style.display = 'block';
                    }
                }
                filterStudents();
            });
        });

        finFilters.forEach(f => f.addEventListener('change', filterStudents));

        // Quick Payment Logic
        const payModalEl = document.getElementById('quickPayModal');
        const payModal = payModalEl ? new bootstrap.Modal(payModalEl) : null;
        document.querySelectorAll('.quick-pay-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (payModal) {
                    document.getElementById('payStudentId').value = this.dataset.id;
                    document.getElementById('payStudentName').textContent = this.dataset.name;
                    document.getElementById('payAmountInput').value = this.dataset.balance;
                    document.getElementById('payBalanceHint').textContent = 'المستحق الحالي: ' + this.dataset.balance + ' ج.م';
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
                    document.getElementById('enrollStudentId').value = this.dataset.id;
                    document.getElementById('enrollStudentName').textContent = this.dataset.name;
                    enrollModal.show();
                }
            });
        });

        document.getElementById('submitEnrollBtn').addEventListener('click', function() {
            const courseId = courseSelect.value;
            if (!courseId) return alert('برجاء اختيار كورس أولاً');
            enrollForm.action = `/center/courses/${courseId}/enroll`;
            enrollForm.submit();
        });

        // Grade filter buttons
        document.querySelectorAll('.grade-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.grade-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentStageGrades = [this.getAttribute('data-grade')];
                filterStudents();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', filterStudents);
        }

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

            // Filter function
            function filterStudents() {
                const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
                const activeFinFilterEl = document.querySelector('.financial-filter:checked');
                const activeFinFilter = activeFinFilterEl ? activeFinFilterEl.value : 'all';

                const studentRows = document.querySelectorAll('.student-row');
                const tbody = document.querySelector('tbody');
                let emptyRow = document.getElementById('empty-state-row');

                if (!emptyRow) {
                    emptyRow = document.createElement('tr');
                    emptyRow.id = 'empty-state-row';
                    emptyRow.innerHTML = `<td colspan="8" class="text-center py-5">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-muted opacity-50">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                            <line x1="11" y1="8" x2="11" y2="14"></line>
                                            <line x1="8" y1="11" x2="14" y2="11"></line>
                                        </svg>
                                    </div>
                                    <p class="text-muted mt-3 mb-0">{{ __('center::students.no_students') }}</p>
                                </td>`;
                }
                
                let visibleCount = 0;
                
                studentRows.forEach(row => {
                    const rowGrade = row.getAttribute('data-grade');
                    const finStatus = row.getAttribute('data-fin-status');
                    const text = row.textContent.toLowerCase();
                    
                    const gradeMatch = !currentStageGrades || currentStageGrades.includes(rowGrade);
                    const searchMatch = !searchTerm || text.includes(searchTerm);
                    const finMatch = activeFinFilter === 'all' || finStatus === activeFinFilter;
                    
                    if (gradeMatch && searchMatch && finMatch) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                const existingEmpty = document.getElementById('empty-state-row');
                if (visibleCount === 0) {
                    if (!existingEmpty) tbody.appendChild(emptyRow);
                    else existingEmpty.style.display = '';
                } else if (existingEmpty) {
                    existingEmpty.style.display = 'none';
                }
            }
        });
    </script>
    
    <style>
        .custom-table thead th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            padding-bottom: 15px;
        }

        .student-row {
            transition: all 0.2s ease;
        }

        .student-row:hover {
            background-color: #f8fbff;
        }

        .student-row td {
            height: 70px;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .x-small {
            font-size: 0.75rem;
        }

        .stage-btn.active {
            background-color: var(--bs-primary);
            color: white;
            border-color: var(--bs-primary);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }
        
        .grade-btn.active {
            background-color: var(--bs-secondary);
            color: white;
            border-color: var(--bs-secondary);
        }
        
        .sub-grades-container {
            padding: 10px 0;
            border-top: 1px dashed #dee2e6;
        }

        .animate__animated {
            --animate-duration: 0.5s;
        }
    </style>
@endsection
