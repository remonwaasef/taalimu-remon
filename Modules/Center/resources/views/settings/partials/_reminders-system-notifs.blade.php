                                <!-- System Notifications Sub Tab -->
                                <div class="tab-pane fade" id="system-notifs" role="tabpanel">
                                    {{-- ═══════════════════════════════════════════════ --}}
                                    {{-- Section 2: Event-Based Email Notifications    --}}
                                    {{-- ═══════════════════════════════════════════════ --}}
                                <div class="mt-5 pt-4 border-top">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-bell me-2"></i> {{ __('center::settings.email_templates.notif_title') }}</h5>
                                    </div>
                                    <p class="text-muted small mb-4">{{ __('center::settings.email_templates.notif_desc') }}</p>

                                    {{-- Accordion for each notification type --}}
                                    <div class="accordion" id="emailNotificationsAccordion">

                                        {{-- 1. Payment Reminder --}}
                                        <div class="accordion-item border rounded-4 mb-3 overflow-hidden">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#notif_payment_reminder">
                                                    <span class="d-flex align-items-center gap-3 w-100">
                                                        <span class="rounded-circle bg-warning bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                            <i class="fas fa-clock text-warning"></i>
                                                        </span>
                                                        <span>
                                                            <span class="d-block">{{ __('center::settings.email_templates.payment_reminder') }}</span>
                                                            <small class="text-muted fw-normal">{{ __('center::settings.email_templates.payment_reminder_desc') }}</small>
                                                        </span>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="notif_payment_reminder" class="accordion-collapse collapse" data-bs-parent="#emailNotificationsAccordion">
                                                <div class="accordion-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fw-bold small text-muted">{{ __('center::settings.email_templates.notif_status') }}</span>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 x-small reset-email-btn" data-subject-id="notif_payment_reminder_subject" data-body-id="notif_payment_reminder_body" data-default-subject="{{ __('center::settings.email_templates.defaults.payment_reminder_subject') }}" data-default-body="{{ __('center::settings.email_templates.defaults.payment_reminder_body') }}">
                                                                <i class="fas fa-undo"></i> {{ __('center::settings.email_templates.to_default') }}
                                                            </button>
                                                            <div class="form-check form-switch custom-switch mb-0">
                                                                <input type="hidden" name="settings[email_templates][notif_payment_reminder_enabled]" value="0">
                                                                <input class="form-check-input" type="checkbox" name="settings[email_templates][notif_payment_reminder_enabled]" value="1" id="notifPaymentReminder" {{ ($emailSettings['notif_payment_reminder_enabled'] ?? true) ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold small ms-2" for="notifPaymentReminder">{{ __('center::settings.email_templates.notif_active') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">{{ __('center::settings.email_templates.subject') }}</label>
                                                        <input type="text" id="notif_payment_reminder_subject" name="settings[email_templates][notif_payment_reminder_subject]" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="{{ $emailSettings['notif_payment_reminder_subject'] ?? __('center::settings.email_templates.defaults.payment_reminder_subject') }}">
                                                    </div>
                                                    <div class="mb-2 d-flex flex-wrap gap-1">
                                                        @php $payVars = ['student_name' => __('center::settings.email_templates.placeholders.student_name'), 'center_name' => __('center::settings.email_templates.placeholders.center_name'), 'amount' => __('center::settings.email_templates.placeholders.amount'), 'due_date' => __('center::settings.email_templates.placeholders.due_date'), 'login_link' => __('center::settings.email_templates.placeholders.login_link')]; @endphp
                                                        @foreach($payVars as $k=>$l)
                                                            <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="notif_payment_reminder_body" data-var="{{ '{'.$k.'}' }}"><i class="fas fa-plus-circle me-1 opacity-50"></i> {{ $l }}</button>
                                                        @endforeach
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label fw-bold small text-muted">{{ __('center::settings.email_templates.body') }}</label>
                                                        <textarea name="settings[email_templates][notif_payment_reminder_body]" id="notif_payment_reminder_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="5">{{ $emailSettings['notif_payment_reminder_body'] ?? __('center::settings.email_templates.defaults.payment_reminder_body') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Group Enrollment Multi-Lingual --}}
                                        <div class="accordion-item border rounded-4 mb-3 overflow-hidden">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#notif_group_enrollment">
                                                    <span class="d-flex align-items-center gap-3 w-100">
                                                        <span class="rounded-circle bg-info bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                            <i class="fas fa-user-plus text-info"></i>
                                                        </span>
                                                        <span>
                                                            <span class="d-block">{{ __('center::settings.email_templates.group_enrollment') }} (Multi-Lingual)</span>
                                                            <small class="text-muted fw-normal">{{ __('center::settings.email_templates.group_enrollment_desc') }}</small>
                                                        </span>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="notif_group_enrollment" class="accordion-collapse collapse" data-bs-parent="#emailNotificationsAccordion">
                                                <div class="accordion-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fw-bold small text-muted">{{ __('center::settings.email_templates.notif_status') }}</span>
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="settings[email_templates][notif_group_enrollment_enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="settings[email_templates][notif_group_enrollment_enabled]" value="1" id="notifGroupEnrollment" {{ ($emailSettings['notif_group_enrollment_enabled'] ?? true) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold small ms-2" for="notifGroupEnrollment">{{ __('center::settings.email_templates.notif_active') }}</label>
                                                        </div>
                                                    </div>

                                                    <div class="row g-4">
                                                        <!-- Arabic -->
                                                        <div class="col-md-4 border-end">
                                                            <label class="form-label fw-bold small text-info">العربية (ar)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_group_enrollment_subject_ar]" class="form-control text-end mb-2" value="{{ $emailSettings['notif_group_enrollment_subject_ar'] ?? $emailSettings['notif_group_enrollment_subject'] ?? 'تم تسجيلك في مجموعة جديدة' }}" placeholder="الموضوع">
                                                                <textarea name="settings[email_templates][notif_group_enrollment_body_ar]" class="form-control text-end" rows="5" dir="rtl" placeholder="نص الرسالة">{{ $emailSettings['notif_group_enrollment_body_ar'] ?? $emailSettings['notif_group_enrollment_body'] ?? "مرحباً {student_name}،\n\nلقد تم تسجيلك بنجاح في {group_name}.\nنتمنى لك التوفيق!\n\n{center_name}" }}</textarea>
                                                            </div>
                                                        </div>
                                                        <!-- English -->
                                                        <div class="col-md-4 border-end">
                                                            <label class="form-label fw-bold small text-info">English (en)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_group_enrollment_subject_en]" class="form-control text-start mb-2" value="{{ $emailSettings['notif_group_enrollment_subject_en'] ?? 'You have been enrolled in a new group' }}" placeholder="Subject">
                                                                <textarea name="settings[email_templates][notif_group_enrollment_body_en]" class="form-control text-start" rows="5" dir="ltr" placeholder="Message body">{{ $emailSettings['notif_group_enrollment_body_en'] ?? "Hello {student_name},\n\nYou have been successfully enrolled in {group_name}.\nWe wish you the best of luck!\n\n{center_name}" }}</textarea>
                                                            </div>
                                                        </div>
                                                        <!-- French -->
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold small text-info">Français (fr)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_group_enrollment_subject_fr]" class="form-control text-start mb-2" value="{{ $emailSettings['notif_group_enrollment_subject_fr'] ?? 'Vous avez été inscrit dans un nouveau groupe' }}" placeholder="Objet">
                                                                <textarea name="settings[email_templates][notif_group_enrollment_body_fr]" class="form-control text-start" rows="5" dir="ltr" placeholder="Corps du message">{{ $emailSettings['notif_group_enrollment_body_fr'] ?? "Bonjour {student_name},\n\nVous avez été inscrit avec succès dans {group_name}.\nNous vous souhaitons bonne chance !\n\n{center_name}" }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 pt-3 border-top">
                                                        <small class="fw-bold text-muted d-block mb-2">{{ __('center::settings.email_templates.placeholders_title') }}</small>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @php $grpVars = ['student_name' => __('center::settings.email_templates.placeholders.student_name'), 'center_name' => __('center::settings.email_templates.placeholders.center_name'), 'group_name' => __('center::settings.email_templates.placeholders.group_name'), 'course_price' => __('center::settings.email_templates.placeholders.course_price'), 'login_link' => __('center::settings.email_templates.placeholders.login_link')]; @endphp
                                                            @foreach($grpVars as $k=>$l)
                                                                <span class="badge bg-light text-dark border py-2 px-3 rounded-pill small">{{ '{'.$k.'}' }} : {{ $l }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 3. Payment Confirmation --}}
                                        {{-- Payment Confirmation Multi-Lingual --}}
                                        <div class="accordion-item border rounded-4 mb-3 overflow-hidden">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#notif_payment_confirmed">
                                                    <span class="d-flex align-items-center gap-3 w-100">
                                                        <span class="rounded-circle bg-success bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                            <i class="fas fa-check-circle text-success"></i>
                                                        </span>
                                                        <span>
                                                            <span class="d-block">{{ __('center::settings.email_templates.payment_confirmation') }} (Multi-Lingual)</span>
                                                            <small class="text-muted fw-normal">{{ __('center::settings.email_templates.payment_confirmation_desc') }}</small>
                                                        </span>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="notif_payment_confirmed" class="accordion-collapse collapse" data-bs-parent="#emailNotificationsAccordion">
                                                <div class="accordion-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fw-bold small text-muted">{{ __('center::settings.email_templates.notif_status') }}</span>
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="settings[email_templates][notif_payment_confirmed_enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="settings[email_templates][notif_payment_confirmed_enabled]" value="1" id="notifPaymentConfirmed" {{ ($emailSettings['notif_payment_confirmed_enabled'] ?? true) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold small ms-2" for="notifPaymentConfirmed">{{ __('center::settings.email_templates.notif_active') }}</label>
                                                        </div>
                                                    </div>

                                                    <div class="row g-4">
                                                        <!-- Arabic -->
                                                        <div class="col-md-4 border-end">
                                                            <label class="form-label fw-bold small text-success">العربية (ar)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_payment_confirmed_subject_ar]" class="form-control text-end mb-2" value="{{ $emailSettings['notif_payment_confirmed_subject_ar'] ?? $emailSettings['notif_payment_confirmed_subject'] ?? 'تأكيد استلام دفعة' }}" placeholder="الموضوع">
                                                                <textarea name="settings[email_templates][notif_payment_confirmed_body_ar]" class="form-control text-end" rows="5" dir="rtl" placeholder="نص الرسالة">{{ $emailSettings['notif_payment_confirmed_body_ar'] ?? $emailSettings['notif_payment_confirmed_body'] ?? "مرحباً {student_name}،\n\nنؤكد استلام دفعة مالية بقيمة {paid_amount}.\nطريقة الدفع: {payment_method}\nالمبلغ المتبقي: {remaining}\n\nشكراً لك،\n{center_name}" }}</textarea>
                                                            </div>
                                                        </div>
                                                        <!-- English -->
                                                        <div class="col-md-4 border-end">
                                                            <label class="form-label fw-bold small text-success">English (en)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_payment_confirmed_subject_en]" class="form-control text-start mb-2" value="{{ $emailSettings['notif_payment_confirmed_subject_en'] ?? 'Payment Confirmation' }}" placeholder="Subject">
                                                                <textarea name="settings[email_templates][notif_payment_confirmed_body_en]" class="form-control text-start" rows="5" dir="ltr" placeholder="Message body">{{ $emailSettings['notif_payment_confirmed_body_en'] ?? "Hello {student_name},\n\nWe confirm the receipt of {paid_amount}.\nPayment Method: {payment_method}\nRemaining Balance: {remaining}\n\nThank you,\n{center_name}" }}</textarea>
                                                            </div>
                                                        </div>
                                                        <!-- French -->
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold small text-success">Français (fr)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_payment_confirmed_subject_fr]" class="form-control text-start mb-2" value="{{ $emailSettings['notif_payment_confirmed_subject_fr'] ?? 'Confirmation de paiement' }}" placeholder="Objet">
                                                                <textarea name="settings[email_templates][notif_payment_confirmed_body_fr]" class="form-control text-start" rows="5" dir="ltr" placeholder="Corps du message">{{ $emailSettings['notif_payment_confirmed_body_fr'] ?? "Bonjour {student_name},\n\nNous confirmons la réception d'un paiement de {paid_amount}.\nMéthode de paiement: {payment_method}\nSolde restant: {remaining}\n\nMerci,\n{center_name}" }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 pt-3 border-top">
                                                        <small class="fw-bold text-muted d-block mb-2">{{ __('center::settings.email_templates.placeholders_title') }}</small>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @php $confVars = ['student_name' => __('center::settings.email_templates.placeholders.student_name'), 'center_name' => __('center::settings.email_templates.placeholders.center_name'), 'amount_paid' => __('center::settings.email_templates.placeholders.amount_paid'), 'payment_date' => __('center::settings.email_templates.placeholders.payment_date'), 'المتبقي' => __('center::settings.email_templates.placeholders.remaining'), 'payment_method' => __('center::settings.email_templates.placeholders.payment_method')]; @endphp
                                                            @foreach($confVars as $k=>$l)
                                                                <span class="badge bg-light text-dark border py-2 px-3 rounded-pill small">{{ '{'.$k.'}' }} : {{ $l }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 4. Attendance Notification --}}
                                        <div class="accordion-item border rounded-4 mb-3 overflow-hidden">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#notif_attendance">
                                                    <span class="d-flex align-items-center gap-3 w-100">
                                                        <span class="rounded-circle bg-primary bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                            <i class="fas fa-user-check text-primary"></i>
                                                        </span>
                                                        <span>
                                                            <span class="d-block">{{ __('center::settings.email_templates.attendance_notif') }} (Multi-Lingual)</span>
                                                            <small class="text-muted fw-normal">{{ __('center::settings.email_templates.attendance_notif_desc') }}</small>
                                                        </span>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="notif_attendance" class="accordion-collapse collapse" data-bs-parent="#emailNotificationsAccordion">
                                                <div class="accordion-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fw-bold small text-muted">{{ __('center::settings.email_templates.notif_status') }}</span>
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="settings[email_templates][notif_attendance_enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="settings[email_templates][notif_attendance_enabled]" value="1" id="notifAttendance" {{ ($emailSettings['notif_attendance_enabled'] ?? true) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold small ms-2" for="notifAttendance">{{ __('center::settings.email_templates.notif_active') }}</label>
                                                        </div>
                                                    </div>

                                                    <div class="row g-4">
                                                        <!-- Arabic -->
                                                        <div class="col-md-4 border-end">
                                                            <label class="form-label fw-bold small text-primary">العربية (ar)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_attendance_subject_ar]" class="form-control text-end mb-2" value="{{ $emailSettings['notif_attendance_subject_ar'] ?? 'إشعار حضور حصة - {center_name}' }}" placeholder="الموضوع">
                                                                <textarea name="settings[email_templates][notif_attendance_body_ar]" class="form-control text-end" rows="5" dir="rtl" placeholder="نص الرسالة">{{ $emailSettings['notif_attendance_body_ar'] ?? "مرحباً {student_name}،\n\nنود إبلاغك بأنه تم تسجيل حضورك لحصة {course_name} بنجاح.\nالحالة: {status}\n\nنتمنى لك التوفيق،\n{center_name}" }}</textarea>
                                                            </div>
                                                        </div>
                                                        <!-- English -->
                                                        <div class="col-md-4 border-end">
                                                            <label class="form-label fw-bold small text-primary">English (en)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_attendance_subject_en]" class="form-control text-start mb-2" value="{{ $emailSettings['notif_attendance_subject_en'] ?? 'Attendance Notification - {center_name}' }}" placeholder="Subject">
                                                                <textarea name="settings[email_templates][notif_attendance_body_en]" class="form-control text-start" rows="5" dir="ltr" placeholder="Message body">{{ $emailSettings['notif_attendance_body_en'] ?? "Hello {student_name},\n\nWe would like to inform you that your attendance for {course_name} has been recorded.\nStatus: {status}\n\nBest regards,\n{center_name}" }}</textarea>
                                                            </div>
                                                        </div>
                                                        <!-- French -->
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold small text-primary">Français (fr)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_attendance_subject_fr]" class="form-control text-start mb-2" value="{{ $emailSettings['notif_attendance_subject_fr'] ?? 'Notification de présence - {center_name}' }}" placeholder="Objet">
                                                                <textarea name="settings[email_templates][notif_attendance_body_fr]" class="form-control text-start" rows="5" dir="ltr" placeholder="Corps du message">{{ $emailSettings['notif_attendance_body_fr'] ?? "Bonjour {student_name},\n\nVous avez été inscrit avec succès dans {group_name}.\nNous vous souhaitons bonne chance !\n\n{center_name}" }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 pt-3 border-top">
                                                        <small class="fw-bold text-muted d-block mb-2">{{ __('center::settings.email_templates.placeholders_title') }}</small>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @php $attVars = ['student_name' => __('center::settings.email_templates.placeholders.student_name'), 'center_name' => __('center::settings.email_templates.placeholders.center_name'), 'course_name' => __('center::settings.email_templates.placeholders.course_name'), 'status' => __('center::settings.email_templates.placeholders.status'), 'date' => __('center::settings.email_templates.placeholders.date')]; @endphp
                                                            @foreach($attVars as $k=>$l)
                                                                <span class="badge bg-light text-dark border py-2 px-3 rounded-pill small">{{ '{'.$k.'}' }} : {{ $l }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="text-start mt-4 pt-3 border-top d-flex gap-2">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i> {{ __('center::settings.email_templates.save') }}
                                    </button>
                                </div>
                            </form>
                            
                            <form action="{{ route('center.settings.reset-email-templates', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST" class="d-inline-block mt-3" id="deleteRowForm_1">
                                @csrf
                                <button type="button" data-confirm-delete data-form="deleteRowForm_1" class="btn btn-light text-danger rounded-pill px-4 fw-bold shadow-sm border">
                                    <i class="fas fa-undo me-2"></i> {{ __('center::settings.email_templates.reset') }}
                                </button>
                                                        </form>
                                </div> <!-- Close system-notifs sub tab -->
