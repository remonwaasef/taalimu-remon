        <!-- Student Header Card -->
        <div class="col-12">
            <div class="card border-0 shadow-elite rounded-5 overflow-hidden position-relative mb-4" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-4 p-md-5 position-relative" style="z-index: 2;">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start" style="text-align: right !important;">
                        <!-- Profile Image Section -->
                        <div class="position-relative flex-shrink-0">
                            @if($student->profile_photo)
                                <img src="{{ asset('storage/' . $student->profile_photo) }}" alt="{{ $student->name }}" class="rounded-circle shadow-lg border border-4 border-white" style="width: 130px; height: 130px; object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg border border-4 border-white" style="width: 130px; height: 130px; font-size: 3.5rem;">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="position-absolute bottom-0 end-0 bg-success border border-white border-4 rounded-circle p-2 pulse-success" title="{{ __('center::students.active') }}"></div>
                        </div>

                        <!-- Main Info Section -->
                        <div class="flex-grow-1">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                                <div class="mb-3 mb-md-0">
                                    <h1 class="fw-bold text-dark mb-2 display-6">{{ $student->name }}</h1>
                                    <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start mb-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold">
                                            <i class="fas fa-graduation-cap me-1"></i> {{ $student->grade_level_name }}
                                        </span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 fw-bold">
                                            <i class="fas fa-barcode me-1"></i> {{ $student->code }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start">
                                        @if($student->school_name)
                                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-1">
                                                <i class="fas fa-school me-1"></i> {{ $student->school_name }}
                                            </span>
                                        @endif
                                        @if($student->section_type)
                                            <span class="badge bg-dark bg-opacity-10 text-dark rounded-pill px-3 py-1">
                                                <i class="fas fa-shapes me-1"></i> {{ $student->section_type }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @php
                                    $reminderMsg = __('center::students.debt_reminder_msg', ['name' => $student->name]);
                                    $guardianPhone = sanitizePhoneForWhatsApp($student->guardian?->phone ?? $student->parent_phone);
                                    $whatsappUrl = "https://web.whatsapp.com/send?phone=" . $guardianPhone . "&text=" . urlencode($reminderMsg);
                                @endphp
                                <div class="d-flex gap-2">
                                    <a href="{{ $whatsappUrl }}" onclick="openSmartWhatsApp('{{ $guardianPhone }}', @json($reminderMsg)); return false;" target="_blank" class="btn btn-success rounded-pill px-4 shadow-sm hover-lift fw-bold">
                                        <i class="fab fa-whatsapp me-2"></i>{{ __('center::students.profile.send_whatsapp') }}</a>
                                    <a href="{{ route('center.students.edit', $student->id) }}" class="btn btn-white border rounded-pill px-4 shadow-sm hover-lift text-dark fw-bold">
                                        <i class="fas fa-edit me-2"></i>{{ __('center::students.profile.edit_profile') }}</a>
                                    <button type="button" class="btn btn-outline-primary bg-white border rounded-pill px-4 shadow-sm hover-lift text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#sendEmailModal" title="{{ __('center::students.send_email') ?? 'إرسال بريد إلكتروني' }}">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Stats Strip (Redesigned) -->
                            <div class="row g-3">
                                <!-- Attendance Card -->
                                <div class="col-6 col-lg-3">
                                    <div class="stats-item bg-white shadow-sm rounded-4 p-3 border-start border-4 border-primary h-100">
                                        <div class="text-muted small mb-2">{{ __('center::students.attendance_stats_header') }}</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <div class="fw-bold text-primary fs-5">{{ $stats['attendance_pct'] }}%</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">{{ __('center::students.attendance_pct') }}</div>
                                            </div>
                                            <div class="text-center border-start border-end px-2">
                                                <div class="fw-bold text-success">{{ $stats['attendance_count'] }}</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">{{ __('center::students.present') }}</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-bold text-danger">{{ $stats['absent_count'] }}</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">{{ __('center::students.absent') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Points Card -->
                                <div class="col-6 col-lg-3">
                                    <div class="stats-item bg-white shadow-sm rounded-4 p-3 border-start border-4 border-indigo h-100">
                                        <div class="text-muted small mb-2">{{ __('center::students.points_stats_header') }}</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <div class="fw-bold text-indigo fs-5">{{ $stats['points'] }}</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">{{ __('center::students.net') }}</div>
                                            </div>
                                            <div class="text-center border-start border-end px-2">
                                                <div class="fw-bold text-success">{{ $stats['points_earned'] }}</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">{{ __('center::students.earned') }}</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-bold text-danger">{{ $stats['points_spent'] }}</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">{{ __('center::students.spent') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quiz Stats Card -->
                                <div class="col-6 col-lg-3">
                                    <div class="stats-item bg-white shadow-sm rounded-4 p-3 border-start border-4 border-success h-100">
                                        <div class="text-muted small mb-2">{{ __('center::students.quizzes_stats_header') }}</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <div class="fw-bold text-success fs-5">{{ $stats['avg_quiz_score'] }}%</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">{{ __('center::students.avg') }}</div>
                                            </div>
                                            <div class="text-center border-start border-end px-2">
                                                <div class="fw-bold text-dark">{{ $stats['quiz_count'] }}</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">{{ __('center::students.count_stat') }}</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-bold text-primary">{{ $stats['highest_score'] }}%</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">{{ __('center::students.highest') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sessions Card -->
                                <div class="col-6 col-lg-3">
                                    <div class="stats-item bg-white shadow-sm rounded-4 p-3 border-start border-4 border-warning h-100">
                                        <div class="text-muted small mb-2">{{ __('center::students.sessions_stats_header') }}</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <div class="fw-bold text-dark fs-5">{{ $stats['total_sessions'] }}</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">{{ __('center::students.total') }}</div>
                                            </div>
                                            <div class="text-center border-start border-end px-2">
                                                <div class="fw-bold text-success">{{ $stats['attendance_count'] }}</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">{{ __('center::students.present') }}</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-bold text-warning">{{ $stats['remaining_sessions_count'] }}</div>
                                                <div style="font-size: 0.65rem;" class="text-muted">{{ __('center::students.remaining') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Subtle Watermark -->
                <div class="position-absolute bottom-0 start-0 p-4 opacity-05 d-none d-lg-block" style="z-index: 1;">
                    <i class="fas fa-user-graduate" style="font-size: 180px; transform: rotate(15deg) translateY(40px);"></i>
                </div>
            </div>
        </div>
