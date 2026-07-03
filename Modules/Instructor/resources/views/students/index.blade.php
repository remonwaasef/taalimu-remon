@extends('instructor::components.layouts.hope-master')

@section('page-title', __('instructor::students.title'))
@section('page-subtitle', __('instructor::students.subtitle'))

@section('page-actions')
    <button type="button" class="btn btn-glass" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="fas fa-file-import me-2"></i> {{ __('instructor::students.import') }}
    </button>
    <a href="{{ route('instructor.students.export') }}" class="btn btn-glass">
        <i class="fas fa-file-export me-2"></i> {{ __('instructor::students.export') }}
    </a>
    <a href="{{ route('instructor.students.create') }}" class="btn btn-glass" style="background: rgba(255,255,255,0.25);">
        <i class="fas fa-user-plus me-2"></i> {{ __('instructor::students.add_new') }}
    </a>
@endsection

@section('content')
    {{-- Top Metrics Section --}}

    {{-- Top Metrics Section --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="border-right: 4px solid var(--primary-color) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3" style="background: rgba(58, 12, 163, 0.1); color: var(--primary-color);">
                        <i class="fas fa-users-viewfinder fa-xl"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">{{ __('instructor::students.total_students') }}</h6>
                        <h4 class="fw-bold mb-0 text-dark">{{ $students->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="border-right: 4px solid #4CC9F0 !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3" style="background: rgba(76, 201, 240, 0.1); color: #4CC9F0;">
                        <i class="fas fa-layer-group fa-xl"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">{{ __('instructor::students.currently_enrolled') }}</h6>
                        <h4 class="fw-bold mb-0 text-dark">{{ $students->sum(fn($s) => $s->enrollments->count()) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        @php
            $todayEnrollments = $students->filter(fn($s) => $s->created_at?->isToday())->count();
        @endphp
        <div class="col-md-3">
            @php
                $totalRevenue = \App\Models\Sale::whereIn('student_id', $students->pluck('id'))->sum('paid_amount');
            @endphp
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="border-right: 4px solid #9C27B0 !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3" style="background: rgba(156, 39, 176, 0.1); color: #9C27B0;">
                        <i class="fas fa-hand-holding-dollar fa-xl"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">{{ __('instructor::students.total_collected') }}</h6>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalRevenue, 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="border-right: 4px solid #4CAF50 !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3" style="background: rgba(76, 175, 80, 0.1); color: #4CAF50;">
                        <i class="fas fa-user-plus fa-xl"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">{{ __('instructor::students.registered_today') }}</h6>
                        <h4 class="fw-bold mb-0 text-dark">{{ $todayEnrollments }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bulk Actions Bar (Hidden by default) --}}
    <div id="bulkActionsBar" class="card border-0 shadow-sm rounded-4 mb-3 d-none animate__animated animate__fadeInUp" style="background: var(--primary-color); color: white;">
        <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <span class="fw-bold"><span id="selectedCount">0</span> {{ __('instructor::students.student') }}</span>
                <div class="vr mx-2 opacity-50"></div>
                <button type="button" id="bulkWhatsAppBtn" class="btn btn-light btn-sm rounded-pill px-3">
                    <i class="fab fa-whatsapp me-1"></i> {{ __('instructor::students.bulk_whatsapp') }}
                </button>
            </div>
            <button type="button" id="cancelSelection" class="btn btn-link text-white text-decoration-none p-0">{{ __('instructor::students.cancel') }}</button>
        </div>
    </div>

    {{-- Search & Group Filter Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="studentSearchInput" class="form-control border-start-0 rounded-end-pill" placeholder="{{ __('instructor::students.search_placeholder') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="groupFilter" class="form-select rounded-pill">
                        <option value="all">{{ __('instructor::students.all_groups') }}</option>
                        @php
                            $uniqueCourses = collect();
                            foreach($students as $student) {
                                foreach($student->enrollments as $enrollment) {
                                    if($enrollment->course) {
                                        $uniqueCourses->put($enrollment->course->id, $enrollment->course->title);
                                    }
                                }
                            }
                        @endphp
                        @foreach($uniqueCourses as $id => $title)
                            <option value="{{ $id }}">{{ $title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="btn-group w-100 p-1 bg-light rounded-pill" role="group">
                        <input type="radio" class="btn-check financial-filter" name="finFilter" id="finAll" value="all" checked>
                        <label class="btn btn-sm btn-outline-primary border-0 rounded-pill px-3" for="finAll">الكل</label>
                        
                        <input type="radio" class="btn-check financial-filter" name="finFilter" id="finDebt" value="debt">
                        <label class="btn btn-sm btn-outline-danger border-0 rounded-pill px-3" for="finDebt">مديون</label>
                        
                        <input type="radio" class="btn-check financial-filter" name="finFilter" id="finPaid" value="paid">
                        <label class="btn btn-sm btn-outline-success border-0 rounded-pill px-3" for="finPaid">مسدد</label>
                    </div>
                </div>
                <div class="col-md-2 text-end">
                    <span id="studentResultCount" class="badge rounded-pill px-3 py-2" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="studentsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3" style="width: 40px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllStudents">
                                </div>
                            </th>
                            <th class="border-0 py-3">{{ __('instructor::students.student') }}</th>
                            <th class="border-0">{{ __('instructor::students.parent') }}</th>
                            <th class="border-0">{{ __('instructor::students.groups') }}</th>
                            <th class="border-0 text-center">{{ __('instructor::students.attendance_rate') }}</th>
                            <th class="border-0">{{ __('instructor::students.financials') }}</th>
                            <th class="border-0">{{ __('instructor::students.status') }}</th>
                            <th class="border-0 text-center">{{ __('instructor::students.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        @php
                            $courseIds = $student->enrollments->pluck('course_id')->filter()->toArray();
                            
                            // Pre-calculate status for filtering
                            $tDue = $student->enrollments->sum(fn($e) => $e->course->price ?? 0);
                            $tPaid = $student->sales->sum('paid_amount');
                            $bal = $tDue - $tPaid;
                            $finStatus = $bal <= 0 ? 'paid' : 'debt';
                        @endphp
                        <tr class="student-row" data-name="{{ $student->name }}" data-phone="{{ $student->phone }}" data-groups="{{ json_encode($courseIds) }}" data-fin-status="{{ $finStatus }}">
                            <td class="px-4">
                                <div class="form-check">
                                    <input class="form-check-input student-checkbox" type="checkbox" value="{{ $student->id }}">
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">
                                        {{ mb_substr($student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">
                                            <a href="{{ route('instructor.students.show', $student->id) }}" class="text-decoration-none text-dark" style="color: var(--primary-color) !important;">
                                                {{ $student->name }}
                                            </a>
                                            @if($attendanceRate < 40 && $totalSessions > 0)
                                                <span class="ms-1 text-danger animate__animated animate__flash animate__infinite" title="غياب متكرر!"><i class="fas fa-exclamation-circle"></i></span>
                                            @endif
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-mobile-alt me-1"></i> {{ $student->phone }}
                                            <a href="tel:{{ $student->phone }}" class="ms-1 text-primary"><i class="fas fa-phone-flip small"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($student->parent_phone)
                                    <div class="small fw-bold text-muted">{{ $student->parent_phone }}</div>
                                    <div class="x-small text-muted opacity-50">{{ __('instructor::students.parent_phone') }}</div>
                                @else
                                    <span class="text-muted small">--</span>
                                @endif
                            </td>
                            <td>
                                @foreach($student->enrollments as $enrollment)
                                    @if($enrollment->course)
                                        <span class="badge bg-light text-dark fw-normal rounded-pill border">{{ $enrollment->course->title }}</span>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @php
                                    $totalDue = $student->enrollments->sum(function($e) {
                                        return $e->course->price ?? 0;
                                    });
                                    $totalPaid = $student->sales->sum('paid_amount');
                                    $balance = $totalDue - $totalPaid;

                                    // Attendance Rate
                                    // Total targeted sessions for this student
                                    $totalSessions = $student->enrollments->sum(fn($e) => $e->course->sessions_count ?? 0);
                                    $attendedSessions = \Modules\Center\Models\Attendance::where('student_id', $student->id)
                                        ->where('status', 'present')
                                        ->count();
                                    $attendanceRate = $totalSessions > 0 ? round(($attendedSessions / $totalSessions) * 100) : 0;
                                @endphp
                                
                                <div class="text-center">
                                    <div class="progress rounded-pill shadow-sm mb-1" style="height: 6px; width: 60px; margin: 0 auto;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $attendanceRate }}%; background: {{ $attendanceRate > 70 ? '#4CAF50' : ($attendanceRate > 40 ? '#FF9800' : '#F44336') }};" 
                                             aria-valuenow="{{ $attendanceRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="small fw-black {{ $attendanceRate > 70 ? 'text-success' : ($attendanceRate > 40 ? 'text-warning' : 'text-danger') }}">{{ $attendanceRate }}%</span>
                                </div>
                            </td>
                            <td>
                                @if($balance <= 0)
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i> {{ __('instructor::students.paid') }}
                                    </span>
                                @elseif($totalPaid > 0)
                                    <div class="d-flex flex-column">
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-1 mb-1">
                                            {{ __('instructor::students.remaining', ['amount' => number_format($balance, 0)]) }}
                                        </span>
                                        <span class="x-small text-muted text-center">{{ __('instructor::students.total_due', ['amount' => number_format($totalDue, 0)]) }}</span>
                                    </div>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">
                                        {{ __('instructor::students.due', ['amount' => number_format($balance, 0)]) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('instructor::students.active') }}</span>
                                <div class="x-small text-muted mt-1">{{ __('instructor::students.registered_on', ['date' => $student->created_at?->format('Y-m-d')]) }}</div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    @php
                                        $phoneForWa = preg_replace('/[^0-9]/', '', ($student->parent_phone ?: $student->phone));
                                        if (str_starts_with($phoneForWa, '0')) {
                                            $phoneForWa = '20' . substr($phoneForWa, 1);
                                        }
                                        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($student->user->qr_identifier ?? '');
                                        $portalUrl = route('student.portal', ['identifier' => $student->user->qr_identifier ?? '']);
                                    @endphp
                                    <a href="https://api.whatsapp.com/send?phone={{ $phoneForWa }}" target="_blank" class="btn btn-light btn-sm rounded-circle p-2 mx-1 text-success shadow-sm" title="{{ __('instructor::students.whatsapp_parent') }}">
                                        <i class="fab fa-whatsapp fa-lg"></i>
                                    </a>

                                    <button type="button" class="btn btn-light btn-sm rounded-circle p-2 mx-1 text-primary shadow-sm quick-pay-btn" 
                                            data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-balance="{{ $balance }}" title="تسجيل دفع سريع">
                                        <i class="fas fa-dollar-sign fa-lg"></i>
                                    </button>

                                    <button type="button" class="btn btn-light btn-sm rounded-circle p-2 mx-1 text-info shadow-sm quick-enroll-btn" 
                                            data-id="{{ $student->id }}" data-name="{{ $student->name }}" title="إضافة لمجموعة">
                                        <i class="fas fa-plus-circle fa-lg"></i>
                                    </button>

                                    @php
                                        $reportMsg = "تقرير الطالب: {$student->name}\nحالة الحضور: {$attendanceRate}%\nالمبلغ المتبقي: " . number_format($balance, 0) . " ج.م\nشكراً لمتابعتكم.";
                                    @endphp
                                    <a href="https://api.whatsapp.com/send?phone={{ $phoneForWa }}&text={{ urlencode($reportMsg) }}" target="_blank" class="btn btn-light btn-sm rounded-circle p-2 mx-1 text-secondary shadow-sm" title="مشاركة تقرير سريع">
                                        <i class="fas fa-share-nodes fa-lg"></i>
                                    </a>
                                    
                                    {{-- Management Dropdown --}}
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-light btn-sm rounded-circle p-2 mx-1 text-dark shadow-sm" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-lg"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4">
                                            <li>
                                                <button type="button" class="dropdown-item py-2 show-qr-btn" data-name="{{ $student->name }}" data-qr="{{ $qrUrl }}" data-portal="{{ $portalUrl }}">
                                                    <i class="fas fa-qrcode me-2 text-primary"></i> {{ __('instructor::students.qr_and_portal') }}
                                                </button>
                                            </li>
                                            <li>
                                                <a href="javascript:window.print()" class="dropdown-item py-2">
                                                    <i class="fas fa-print me-2 text-secondary"></i> طباعة الكارنيه
                                                </a>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item py-2 transfer-student-btn" data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-groups="{{ json_encode($student->enrollments->pluck('course_id')) }}">
                                                    <i class="fas fa-exchange-alt me-2 text-info"></i> {{ __('instructor::students.transfer_to_group') }}
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item py-2 edit-notes-btn" data-id="{{ $student->id }}" data-notes="{{ $student->notes }}">
                                                    <i class="fas fa-file-signature me-2 text-warning"></i> {{ __('instructor::students.private_notes') }}
                                                </button>
                                            </li>
                                            <li>
                                                <form action="{{ route('instructor.students.toggle-status', $student->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item py-2">
                                                        @if($student->status === 'active')
                                                            <i class="fas fa-snowflake me-2 text-secondary"></i> {{ __('instructor::students.freeze_account') }}
                                                        @else
                                                            <i class="fas fa-play me-2 text-success"></i> {{ __('instructor::students.activate_account') }}
                                                        @endif
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a href="{{ route('instructor.students.show', $student->id) }}" class="dropdown-item py-2 text-primary">
                                                    <i class="fas fa-id-card me-2"></i> {{ __('instructor::students.detailed_profile') }}
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('instructor.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('{{ __('instructor::students.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                                        <i class="fas fa-trash-alt me-2"></i> {{ __('instructor::students.delete_student') }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="8" class="text-center py-5">
                                <div class="mb-4">
                                    <div class="d-inline-flex p-4 rounded-circle mb-3" style="background: rgba(16, 185, 129, 0.05);">
                                        <i class="fas fa-users-slash text-primary" style="font-size: 3rem; opacity: 0.5;"></i>
                                    </div>
                                </div>
                                <h6 class="text-muted fw-bold">{{ __('instructor::students.no_students') }}</h6>
                                <p class="text-muted small">{{ __('instructor::students.no_students_desc') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="noStudentsResults" class="text-center py-5 d-none">
                <i class="fas fa-user-slash display-4 text-light mb-3"></i>
                <p class="text-muted">{{ __('instructor::students.no_results') }}</p>
            </div>
        </div>
    </div>

@push('modals')
{{-- QR & Portal Modal --}}
<div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-body text-center p-4">
                <h5 class="fw-bold mb-3" id="qrModalName"></h5>
                <div class="bg-light p-3 rounded-4 mb-3 d-inline-block shadow-inner">
                    <img id="qrModalImg" src="" alt="QR" style="width: 180px; height: 180px;">
                </div>
                
                <div class="mb-3">
                    <label class="form-label small text-muted">{{ __('instructor::students.portal_link') }}</label>
                    <div class="input-group">
                        <input type="text" id="portalUrlInput" class="form-control text-ltr" readonly onclick="this.select()" style="cursor: pointer;" title="{{ __('instructor::students.click_to_select') ?? 'Click to select' }}">
                        <button class="btn btn-outline-primary" type="button" id="copyPortalBtn">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <a href="" id="openPortalBtn" target="_blank" class="btn btn-primary flex-grow-1 rounded-pill border-0" style="background: var(--primary-color);">{{ __('instructor::students.open_portal') }}</a>
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('instructor::students.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('instructor.students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold">{{ __('instructor::students.import_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info small border-0 rounded-3">
                        <i class="fas fa-info-circle me-2"></i> {{ __('instructor::students.import_hint') }}
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('instructor::students.select_group') }}</label>
                        <select name="course_id" class="form-select rounded-pill" required>
                            @foreach($uniqueCourses as $id => $title)
                                <option value="{{ $id }}">{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('instructor::students.csv_file') }}</label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv, .txt" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill border-0" style="background: var(--primary-color);">{{ __('instructor::students.start_import') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Transfer Modal --}}
<div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form id="transferForm" method="POST">
                @csrf
                <div class="modal-body p-4 text-center">
                    <div class="rounded-circle bg-info bg-opacity-10 text-info p-3 mb-3 d-inline-block">
                        <i class="fas fa-exchange-alt fa-2x"></i>
                    </div>
                    <h5 class="fw-bold" id="transferStudentName">{{ __('instructor::students.transfer_student') }}</h5>
                    <p class="text-muted small mb-4">{{ __('instructor::students.transfer_hint') }}</p>
                    
                    <input type="hidden" name="from_course_id" id="fromCourseId">
                    
                    <div class="mb-4 text-start">
                        <label class="form-label fw-bold">{{ __('instructor::students.new_group') }}</label>
                        <select name="to_course_id" class="form-select rounded-pill" required>
                            @foreach($uniqueCourses as $id => $title)
                                <option value="{{ $id }}">{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>
 
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-info text-white flex-grow-1 rounded-pill">{{ __('instructor::students.confirm_transfer') }}</button>
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('instructor::students.cancel') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Quick Payment Modal --}}
<div class="modal fade" id="quickPayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('instructor.students.mark-paid') }}" method="POST">
                @csrf
                <input type="hidden" name="student_id" id="payStudentId">
                <div class="modal-body p-4 text-center">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 mb-3 d-inline-block">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-1" id="payStudentName"></h5>
                    <p class="text-muted small mb-4">تسجيل دفعة نقدية سريعة</p>
                    
                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold small text-muted">المبلغ المدفوع</label>
                        <div class="input-group">
                            <input type="number" name="amount" id="payAmountInput" class="form-control rounded-start-pill" required>
                            <span class="input-group-text rounded-end-pill">ج.م</span>
                        </div>
                        <div id="payBalanceHint" class="x-small text-danger mt-1"></div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold">تأكيد الدفع</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Quick Enroll Modal --}}
<div class="modal fade" id="quickEnrollModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('instructor.students.store') }}" method="POST">
                @csrf
                <input type="hidden" name="student_id" id="enrollStudentId">
                <input type="hidden" name="is_quick_enroll" value="1">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold"><i class="fas fa-plus-circle me-2 text-info"></i>إلحاق بمجموعة إضافية</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted mb-4">اختر المجموعة التي ترغب في إضافة الطالب <span class="fw-bold text-dark" id="enrollStudentName"></span> إليها.</p>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">المجموعة المستهدفة</label>
                        <select name="course_ids[]" class="form-select rounded-pill" required>
                            @foreach($uniqueCourses as $id => $title)
                                <option value="{{ $id }}">{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-soft-info x-small border-0 rounded-3">
                        سيتم إنشاء فاتورة جديدة للطالب بهذا الكورس تلقائياً.
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-info text-white w-100 rounded-pill py-2 fw-bold">إتمام الإلحاق</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Notes Modal --}}
<div class="modal fade" id="notesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form id="notesForm" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold">{{ __('instructor::students.notes_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <textarea name="notes" id="studentNotesText" class="form-control rounded-4 shadow-inner" rows="5" placeholder="{{ __('instructor::students.notes_placeholder') }}"></textarea>
                    <p class="x-small text-muted mt-2"><i class="fas fa-lock me-1"></i> {{ __('instructor::students.notes_hint') }}</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill border-0" style="background: var(--primary-color);">{{ __('instructor::students.save_notes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush


@include('instructor::students.partials._index-scripts')

<style>
    .hover-primary-link:hover {
        color: var(--primary-color) !important;
        text-decoration: underline !important;
    }
</style>
@endsection
