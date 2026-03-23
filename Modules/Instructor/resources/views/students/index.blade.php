@extends('instructor::components.layouts.master')

@section('page-title', __('instructor::students.title'))

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h3 class="fw-bold mb-0">{{ __('instructor::students.manage_students') }}</h3>
            <p class="text-muted small">{{ __('instructor::students.subtitle') }}</p>
        </div>
        <div class="col-auto d-flex gap-2">
            <button type="button" class="btn btn-outline-primary rounded-pill px-4 shadow-sm fw-bold border-2" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-import me-2"></i> {{ __('instructor::students.import') }}
            </button>
            <a href="{{ route('instructor.students.export') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm fw-bold border-2">
                <i class="fas fa-file-export me-2"></i> {{ __('instructor::students.export') }}
            </a>
            <a href="{{ route('instructor.students.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold border-0" style="background: var(--primary-color);">
                <i class="fas fa-user-plus me-2"></i> {{ __('instructor::students.add_new') }}
            </a>
        </div>
    </div>

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
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="studentSearchInput" class="form-control border-start-0 rounded-end-pill" placeholder="{{ __('instructor::students.search_placeholder') }}">
                    </div>
                </div>
                <div class="col-md-4">
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
                <div class="col-md-3 text-end">
                    <span id="studentResultCount" class="badge rounded-pill px-3 py-2" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
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
                        @endphp
                        <tr class="student-row" data-name="{{ $student->name }}" data-phone="{{ $student->phone }}" data-groups="{{ json_encode($courseIds) }}">
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
                                        </div>
                                        <div class="text-muted small"><i class="fas fa-mobile-alt me-1"></i> {{ $student->phone }}</div>
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
                                    <a href="https://api.whatsapp.com/send?phone={{ $phoneForWa }}" target="_blank" class="btn btn-light btn-sm rounded-circle p-2 mx-1 text-success shadow-sm" title="مراسلة ولي الأمر">
                                        <i class="fab fa-whatsapp fa-lg"></i>
                                    </a>
                                    
                                    {{-- Management Dropdown --}}
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-light btn-sm rounded-circle p-2 mx-1 text-dark shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-lg"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 overflow-hidden">
                                            <li>
                                                <button type="button" class="dropdown-item py-2 show-qr-btn" data-name="{{ $student->name }}" data-qr="{{ $qrUrl }}" data-portal="{{ $portalUrl }}">
                                                    <i class="fas fa-qrcode me-2 text-primary"></i> {{ __('instructor::students.qr_and_portal') }}
                                                </button>
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
                                <img src="https://illustrations.popsy.co/gray/fogg-searching.png" alt="No data" style="width: 150px;" class="mb-3 opacity-50">
                                <h6 class="text-muted">{{ __('instructor::students.no_students') }}</h6>
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
</div>

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
                        <input type="text" id="portalUrlInput" class="form-control text-ltr" readonly>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('studentSearchInput');
    const groupFilter = document.getElementById('groupFilter');
    const rows = document.querySelectorAll('.student-row');
    const noResults = document.getElementById('noStudentsResults');
    const resultCount = document.getElementById('studentResultCount');
    const table = document.getElementById('studentsTable');

    const bulkBar = document.getElementById('bulkActionsBar');
    const selectedCountSpan = document.getElementById('selectedCount');
    const selectAllCheckbox = document.getElementById('selectAllStudents');
    const studentCheckboxes = document.querySelectorAll('.student-checkbox');
    const bulkWhatsAppBtn = document.getElementById('bulkWhatsAppBtn');
    const cancelSelectionBtn = document.getElementById('cancelSelection');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.student-checkbox:checked');
        const count = checked.length;
        
        if (count > 0) {
            bulkBar.classList.remove('d-none');
            selectedCountSpan.textContent = count;
        } else {
            bulkBar.classList.add('d-none');
        }
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = count === studentCheckboxes.length && count > 0;
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            studentCheckboxes.forEach(cb => {
                const row = cb.closest('tr');
                if (row.style.display !== 'none') {
                    cb.checked = isChecked;
                }
            });
            updateBulkBar();
        });
    }

    studentCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBar);
    });

    if (cancelSelectionBtn) {
        cancelSelectionBtn.addEventListener('click', function() {
            studentCheckboxes.forEach(cb => cb.checked = false);
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            updateBulkBar();
        });
    }

    if (bulkWhatsAppBtn) {
        bulkWhatsAppBtn.addEventListener('click', function() {
            const selectedPhones = [];
            document.querySelectorAll('.student-checkbox:checked').forEach(cb => {
                const row = cb.closest('tr');
                const phone = row.dataset.phone;
                if (phone) {
                    let cleanPhone = phone.replace(/[^0-9]/g, '');
                    if (cleanPhone.startsWith('0')) cleanPhone = '20' + cleanPhone.substring(1);
                    selectedPhones.push(cleanPhone);
                }
            });

            if (selectedPhones.length > 0) {
                // WhatsApp bulk is limited by URL length, so we usually open one by one or use a tool.
                // For now, we'll open the first one and alert if multiple.
                const first = selectedPhones[0];
                window.open(`https://api.whatsapp.com/send?phone=${first}`, '_blank');
                if (selectedPhones.length > 1) {
                    alert('{{ __('instructor::students.whatsapp_bulk_alert') }}');
                }
            }
        });
    }

    function applyStudentFilters() {
        const query = searchInput.value.trim().toLowerCase();
        const filterGroupId = groupFilter.value;
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.dataset.name.toLowerCase();
            const phone = row.dataset.phone.toLowerCase();
            const groups = JSON.parse(row.dataset.groups);

            let matchSearch = !query || name.includes(query) || phone.includes(query);
            let matchGroup = filterGroupId === 'all' || groups.includes(parseInt(filterGroupId));

            if (matchSearch && matchGroup) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
                const cb = row.querySelector('.student-checkbox');
                if (cb) cb.checked = false;
            }
        });

        updateBulkBar();
        if (resultCount) resultCount.textContent = visibleCount + ' {{ __('instructor::students.student_count') }}';
        
        if (noResults) {
            noResults.classList.toggle('d-none', visibleCount > 0 || rows.length === 0);
        }
    }

    // QR & Portal Modal Logic
    const qrModalEl = document.getElementById('qrModal');
    const qrModal = qrModalEl ? new bootstrap.Modal(qrModalEl) : null;
    const qrModalImg = document.getElementById('qrModalImg');
    const qrModalName = document.getElementById('qrModalName');
    const portalUrlInput = document.getElementById('portalUrlInput');
    const openPortalBtn = document.getElementById('openPortalBtn');
    const copyPortalBtn = document.getElementById('copyPortalBtn');

    document.querySelectorAll('.show-qr-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (qrModal) {
                qrModalName.textContent = this.dataset.name;
                qrModalImg.src = this.dataset.qr;
                portalUrlInput.value = this.dataset.portal;
                openPortalBtn.href = this.dataset.portal;
                qrModal.show();
            }
        });
    });

    if (copyPortalBtn) {
        copyPortalBtn.addEventListener('click', function() {
            portalUrlInput.select();
            document.execCommand('copy');
            const originalIcon = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i>';
            this.classList.replace('btn-outline-primary', 'btn-success');
            setTimeout(() => {
                this.innerHTML = originalIcon;
                this.classList.replace('btn-success', 'btn-outline-primary');
            }, 2000);
        });
    }

    // Transfer Modal
    const transferModalEl = document.getElementById('transferModal');
    const transferModal = transferModalEl ? new bootstrap.Modal(transferModalEl) : null;
    const transferForm = document.getElementById('transferForm');
    const transferStudentName = document.getElementById('transferStudentName');
    const fromCourseIdInput = document.getElementById('fromCourseId');

    document.querySelectorAll('.transfer-student-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const groups = JSON.parse(this.dataset.groups);
            transferStudentName.textContent = '{{ __('instructor::students.transfer_student_prefix') }}' + this.dataset.name;
            transferForm.action = `/instructor/students/${id}/transfer`;
            fromCourseIdInput.value = groups[0] || ''; // Pick first group as from
            transferModal.show();
        });
    });

    // Notes Modal
    const notesModalEl = document.getElementById('notesModal');
    const notesModal = notesModalEl ? new bootstrap.Modal(notesModalEl) : null;
    const notesForm = document.getElementById('notesForm');
    const studentNotesText = document.getElementById('studentNotesText');

    document.querySelectorAll('.edit-notes-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            studentNotesText.value = this.dataset.notes || '';
            notesForm.action = `/instructor/students/${id}/update-notes`;
            notesModal.show();
        });
    });

    if (searchInput) searchInput.addEventListener('input', applyStudentFilters);
    if (groupFilter) groupFilter.addEventListener('change', applyStudentFilters);

    // Initial count
    applyStudentFilters();
});
</script>

<style>
    .hover-primary-link:hover {
        color: var(--primary-color) !important;
        text-decoration: underline !important;
    }
</style>
@endsection
