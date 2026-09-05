        <!-- Student Header Card -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative mb-4 bg-white">
                <div class="card-body p-4 position-relative" style="z-index: 2;">
                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4 text-center text-md-start" style="text-align: right !important;">
                        <!-- Profile Image Section -->
                        <div class="position-relative flex-shrink-0">
                            @if($student->profile_photo)
                                <img src="{{ asset('storage/' . $student->profile_photo) }}" alt="{{ $student->name }}" class="rounded-circle shadow-sm border border-3 border-white" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center border border-2 border-primary border-opacity-25 shadow-xs" style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: 800;">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="position-absolute bottom-0 end-0 bg-success border border-white border-3 rounded-circle p-2" title="{{ __('center::students.active') }}"></div>
                        </div>

                        <!-- Main Info Section -->
                        <div class="flex-grow-1 w-100">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center align-items-md-start gap-3 mb-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap mb-1">
                                        <h2 class="fw-black text-dark mb-0 fs-3">{{ $student->name }}</h2>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 text-xs fw-bold">
                                            <i class="fas fa-check-circle me-1"></i> {{ __('center::students.active') }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap mt-1">
                                        <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1 text-xs">
                                            <i class="fas fa-graduation-cap me-1 text-primary"></i> {{ $student->grade_level_name }}
                                        </span>
                                        <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1 text-xs font-monospace">
                                            <i class="fas fa-barcode me-1 text-muted"></i> #{{ $student->code }}
                                        </span>
                                        @if($student->school_name)
                                            <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1 text-xs">
                                                <i class="fas fa-school me-1 text-muted"></i> {{ $student->school_name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                @php
                                    $reminderMsg = __('center::students.debt_reminder_msg', ['name' => $student->name]);
                                    $guardianPhone = sanitizePhoneForWhatsApp($student->guardian?->phone ?? $student->parent_phone);
                                    $whatsappUrl = "https://web.whatsapp.com/send?phone=" . $guardianPhone . "&text=" . urlencode($reminderMsg);
                                @endphp
                                <div class="d-flex align-items-center gap-2 shrink-0">
                                    <a href="{{ $whatsappUrl }}" onclick="openSmartWhatsApp('{{ $guardianPhone }}', @json($reminderMsg)); return false;" target="_blank" class="btn btn-success rounded-xl px-3 py-2 text-xs fw-bold shadow-xs">
                                        <i class="fab fa-whatsapp me-1.5"></i>{{ __('center::students.profile.send_whatsapp') }}
                                    </a>
                                    <a href="{{ route('center.students.edit', $student->id) }}" class="btn btn-outline-secondary rounded-xl px-3 py-2 text-xs fw-bold bg-white shadow-xs">
                                        <i class="fas fa-edit me-1.5"></i>{{ __('center::students.profile.edit_profile') }}
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary rounded-xl px-2.5 py-2 text-xs bg-white shadow-xs" data-bs-toggle="modal" data-bs-target="#sendEmailModal" title="{{ __('center::students.send_email') ?? 'إرسال بريد إلكتروني' }}">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Clean High-Signal Metric Badges (Decluttered) -->
                            @php
                                $enrolledCount = $student->enrollments()->where('status', 'active')->count();
                                $studentBalance = $student->balance ?? 0;
                            @endphp
                            <div class="d-flex align-items-center gap-2 flex-wrap pt-2 border-top border-light">
                                <!-- 1. Attendance Rate -->
                                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-xl bg-light border text-xs">
                                    <i class="fas fa-calendar-check text-primary"></i>
                                    <span class="text-muted">{{ __('center::students.attendance_pct') }}:</span>
                                    <strong class="text-primary fw-bold">{{ $stats['attendance_pct'] }}%</strong>
                                    <span class="text-muted">({{ $stats['attendance_count'] }} {{ __('center::students.present') }})</span>
                                </div>

                                <!-- 2. Active Courses -->
                                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-xl bg-light border text-xs">
                                    <i class="fas fa-book-open text-info"></i>
                                    <span class="text-muted">{{ __('center::students.profile.tabs.courses') }}:</span>
                                    <strong class="text-dark fw-bold">{{ $enrolledCount }}</strong>
                                </div>

                                <!-- 3. Balance -->
                                @if($studentBalance > 0)
                                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-xl bg-danger bg-opacity-10 border border-danger border-opacity-25 text-xs text-danger">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ __('center::students.current_balance') }}:</span>
                                        <strong class="fw-bold">{{ number_format($studentBalance) }} {{ __('center::dashboard.currency') }}</strong>
                                    </div>
                                @else
                                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-xl bg-success bg-opacity-10 border border-success border-opacity-25 text-xs text-success">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="fw-bold">{{ __('center::students.paid') }} (لا توجد متأخرات)</span>
                                    </div>
                                @endif

                                <!-- 4. Quick ID Card Print Trigger -->
                                <button type="button" onclick="printIDCard()" class="btn btn-link text-decoration-none text-muted text-xs p-0 ms-auto d-none d-md-inline-flex align-items-center gap-1 hover:text-primary">
                                    <i class="fas fa-print"></i>
                                    <span>{{ __('center::students.print_id_card') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
