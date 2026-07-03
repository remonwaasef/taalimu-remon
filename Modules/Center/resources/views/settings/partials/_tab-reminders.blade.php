                        <div class="tab-pane fade {{ in_array($activeTab, ['reminders', 'email_templates']) ? 'show active' : '' }}" id="reminders" role="tabpanel" aria-labelledby="reminders-tab">
                            
                            <!-- Sub Tabs Nav -->
                            <ul class="nav nav-pills mb-4 bg-light p-2 rounded-4 d-flex justify-content-center gap-2" id="remindersSubTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active rounded-pill px-4 fw-bold" id="welcome-emails-tab" data-bs-toggle="pill" data-bs-target="#welcome-emails" type="button" role="tab">
                                        <i class="fas fa-handshake me-2"></i> {{ __('center::settings.reminders.sub_tabs.welcome') }}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill px-4 fw-bold" id="system-notifs-tab" data-bs-toggle="pill" data-bs-target="#system-notifs" type="button" role="tab">
                                        <i class="fas fa-bell me-2"></i> {{ __('center::settings.reminders.sub_tabs.system') }}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill px-4 fw-bold" id="payment-reminders-tab" data-bs-toggle="pill" data-bs-target="#payment-reminders" type="button" role="tab">
                                        <i class="fas fa-calendar-check me-2"></i> {{ __('center::settings.reminders.sub_tabs.payment') }}
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="remindersSubTabsContent">
                                <!-- Welcome Emails Sub Tab -->
                                <div class="tab-pane fade show active" id="welcome-emails" role="tabpanel">
                                    <div class="mb-4">
                                        <h4 class="fw-bold" style="color: #3A0CA3;"><i class="fas fa-envelope-open-text me-2"></i> {{ __('center::settings.tabs.email_templates') }}</h4>
                                        <p class="text-muted">{{ __('center::settings.email_templates.desc') }}</p>
                                    </div>
                            @php
                                $emailSettings = $tenant->settings['email_templates'] ?? [];
                                $presets = config('email_templates.presets', []);
                                $defaultPresetKey = config('email_templates.default_preset', 'formal');
                                $defaultPreset = $presets[$defaultPresetKey] ?? [];
                            @endphp
                            <form action="{{ route('center.settings.update', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST">
                                @csrf

                                {{-- Header --}}
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-envelope me-2"></i> {{ __('center::settings.email_templates.title') }}</h5>
                                </div>
                                <p class="text-muted small mb-4">{{ __('center::settings.email_templates.desc') }}</p>

                                {{-- Quick Preset Selector --}}
                                <div class="card border bg-light shadow-none rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-magic me-2 text-warning"></i> {{ __('center::settings.email_templates.choose_preset') }}</h6>
                                        <div class="row g-3">
                                            @foreach($presets as $key => $preset)
                                                <div class="col-md-4">
                                                    <div class="bg-white border rounded-3 p-3 h-100 text-center cursor-pointer preset-card" data-preset="{{ $key }}" style="cursor: pointer; transition: all 0.2s;">
                                                        <div class="mb-2">
                                                            <i class="{{ $preset['icon'] ?? 'fas fa-file-alt' }} fa-2x text-primary"></i>
                                                        </div>
                                                        <span class="fw-bold small">{{ __('center::settings.email_templates.presets.' . $key) }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Student Welcome Email --}}
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <div class="card border bg-white shadow-none rounded-4 mb-4">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <h6 class="fw-bold mb-0"><i class="fas fa-user-graduate me-2 text-info"></i> {{ __('center::settings.email_templates.student_welcome') }} (Multi-Lingual)</h6>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="settings[email_templates][welcome_student_enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="settings[email_templates][welcome_student_enabled]" value="1" id="studentEmailEnabled" {{ ($emailSettings['welcome_student_enabled'] ?? true) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold small ms-2" for="studentEmailEnabled">{{ __('center::settings.email_templates.activate') }}</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row g-4">
                                                    <!-- Arabic -->
                                                    <div class="col-md-4 border-end">
                                                        <label class="form-label fw-bold small text-primary">العربية (ar)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_student_subject_ar]" class="form-control text-end mb-2" value="{{ $emailSettings['welcome_student_subject_ar'] ?? $emailSettings['welcome_student_subject'] ?? $defaultPreset['student_subject'] ?? 'مرحباً بك في {center_name} - بيانات الدخول' }}" placeholder="الموضوع">
                                                            <textarea name="settings[email_templates][welcome_student_body_ar]" class="form-control text-end" rows="6" dir="rtl" placeholder="نص الرسالة">{{ $emailSettings['welcome_student_body_ar'] ?? $emailSettings['welcome_student_body'] ?? $defaultPreset['student_body'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <!-- English -->
                                                    <div class="col-md-4 border-end">
                                                        <label class="form-label fw-bold small text-primary">English (en)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_student_subject_en]" class="form-control text-start mb-2" value="{{ $emailSettings['welcome_student_subject_en'] ?? $defaultPreset['student_subject_en'] ?? 'Welcome to {center_name} - Login Details' }}" placeholder="Subject">
                                                            <textarea name="settings[email_templates][welcome_student_body_en]" class="form-control text-start" rows="6" dir="ltr" placeholder="Message body">{{ $emailSettings['welcome_student_body_en'] ?? $defaultPreset['student_body_en'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <!-- French -->
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small text-primary">Français (fr)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_student_subject_fr]" class="form-control text-start mb-2" value="{{ $emailSettings['welcome_student_subject_fr'] ?? $defaultPreset['student_subject_fr'] ?? 'Bienvenue à {center_name} - Identifiants de connexion' }}" placeholder="Objet">
                                                            <textarea name="settings[email_templates][welcome_student_body_fr]" class="form-control text-start" rows="6" dir="ltr" placeholder="Corps du message">{{ $emailSettings['welcome_student_body_fr'] ?? $defaultPreset['student_body_fr'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-3 pt-3 border-top">
                                                    <small class="fw-bold text-muted d-block mb-2">{{ __('center::settings.email_templates.placeholders_title') }}</small>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @php
                                                            $vars = [
                                                                'student_name' => __('center::settings.email_templates.placeholders.student_name'),
                                                                'center_name' => __('center::settings.email_templates.placeholders.center_name'),
                                                                'login_link' => __('center::settings.email_templates.placeholders.login_link'),
                                                                'password' => __('center::settings.email_templates.placeholders.password'),
                                                                'phone' => __('center::settings.email_templates.placeholders.phone'),
                                                            ];
                                                        @endphp
                                                        @foreach($vars as $key => $label)
                                                            <span class="badge bg-light text-dark border py-2 px-3 rounded-pill small">{{ '{' . $key . '}' }} : {{ $label }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Guardian Welcome Email Multi-Lingual --}}
                                        <div class="card border bg-white shadow-none rounded-4 mb-4">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <h6 class="fw-bold mb-0"><i class="fas fa-user-shield me-2 text-success"></i> {{ __('center::settings.email_templates.guardian_welcome') }} (Multi-Lingual)</h6>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="settings[email_templates][welcome_guardian_enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="settings[email_templates][welcome_guardian_enabled]" value="1" id="guardianEmailEnabled" {{ ($emailSettings['welcome_guardian_enabled'] ?? true) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold small ms-2" for="guardianEmailEnabled">{{ __('center::settings.email_templates.activate') }}</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row g-4">
                                                    <!-- Arabic -->
                                                    <div class="col-md-4 border-end">
                                                        <label class="form-label fw-bold small text-success">العربية (ar)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_guardian_subject_ar]" class="form-control text-end mb-2" value="{{ $emailSettings['welcome_guardian_subject_ar'] ?? $emailSettings['welcome_guardian_subject'] ?? $defaultPreset['guardian_subject'] ?? 'تم تسجيل {student_name} في {center_name}' }}" placeholder="الموضوع">
                                                            <textarea name="settings[email_templates][welcome_guardian_body_ar]" class="form-control text-end" rows="6" dir="rtl" placeholder="نص الرسالة">{{ $emailSettings['welcome_guardian_body_ar'] ?? $emailSettings['welcome_guardian_body'] ?? $defaultPreset['guardian_body'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <!-- English -->
                                                    <div class="col-md-4 border-end">
                                                        <label class="form-label fw-bold small text-success">English (en)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_guardian_subject_en]" class="form-control text-start mb-2" value="{{ $emailSettings['welcome_guardian_subject_en'] ?? $defaultPreset['guardian_subject_en'] ?? '{student_name} has been registered at {center_name}' }}" placeholder="Subject">
                                                            <textarea name="settings[email_templates][welcome_guardian_body_en]" class="form-control text-start" rows="6" dir="ltr" placeholder="Message body">{{ $emailSettings['welcome_guardian_body_en'] ?? $defaultPreset['guardian_body_en'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <!-- French -->
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small text-success">Français (fr)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_guardian_subject_fr]" class="form-control text-start mb-2" value="{{ $emailSettings['welcome_guardian_subject_fr'] ?? $defaultPreset['guardian_subject_fr'] ?? '{student_name} a été inscrit à {center_name}' }}" placeholder="Objet">
                                                            <textarea name="settings[email_templates][welcome_guardian_body_fr]" class="form-control text-start" rows="6" dir="ltr" placeholder="Corps du message">{{ $emailSettings['welcome_guardian_body_fr'] ?? $defaultPreset['guardian_body_fr'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-3 pt-3 border-top">
                                                    <small class="fw-bold text-muted d-block mb-2">{{ __('center::settings.email_templates.placeholders_title') }}</small>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @php
                                                            $gVars = [
                                                                'student_name' => __('center::settings.email_templates.placeholders.student_name'),
                                                                'center_name' => __('center::settings.email_templates.placeholders.center_name'),
                                                                'parent_name' => __('center::settings.email_templates.placeholders.parent_name'),
                                                                'stage' => __('center::settings.email_templates.placeholders.stage'),
                                                            ];
                                                        @endphp
                                                        @foreach($gVars as $key => $label)
                                                            <span class="badge bg-light text-dark border py-2 px-3 rounded-pill small">{{ '{' . $key . '}' }} : {{ $label }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-5">
                                        {{-- Live Preview --}}
                                        <div class="sticky-top" style="top: 2rem; z-index: 5;">
                                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                                <div class="card-header bg-dark py-3 px-4">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="d-flex gap-1">
                                                            <span class="rounded-circle bg-danger" style="width:10px; height:10px;"></span>
                                                            <span class="rounded-circle bg-warning" style="width:10px; height:10px;"></span>
                                                            <span class="rounded-circle bg-success" style="width:10px; height:10px;"></span>
                                                        </div>
                                                        <span class="text-white x-small opacity-50 ms-2">{{ __('center::settings.email_templates.preview_title') }}</span>
                                                    </div>
                                                </div>
                                                <div class="card-body p-0 bg-white">
                                                    <div class="p-3 border-bottom bg-light">
                                                        <div class="small text-muted mb-1">{{ __('center::settings.email_templates.subject') }}:</div>
                                                        <div id="preview-subject" class="fw-bold">...</div>
                                                    </div>
                                                    <div class="p-4" style="min-height: 400px; font-family: sans-serif; line-height: 1.6;">
                                                        <div id="preview-body" style="white-space: pre-wrap;">...</div>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-light border-0 text-center py-3">
                                                    <span class="text-muted x-small italic"><i class="fas fa-magic me-1 text-primary"></i> {{ __('center::settings.email_templates.preview_help') }}</span>
                                                </div>
                                            </div>

                                            <div class="mt-4 p-4 bg-primary-soft rounded-4 border border-primary border-opacity-10">
                                                <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-lightbulb me-2"></i> {{ __('center::settings.email_templates.pro_tip') }}</h6>
                                                <p class="small text-dark mb-0">{{ __('center::settings.email_templates.pro_tip_desc') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                                                </div> <!-- Close welcome-emails sub tab -->
                                
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
                            
                            <form action="{{ route('center.settings.reset-email-templates', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST" class="d-inline-block mt-3" onsubmit="return confirm('{{ __('center::settings.email_templates.confirm_reset') }}');">
                                @csrf
                                <button type="submit" class="btn btn-light text-danger rounded-pill px-4 fw-bold shadow-sm border">
                                    <i class="fas fa-undo me-2"></i> {{ __('center::settings.email_templates.reset') }}
                                </button>
                                                        </form>
                                </div> <!-- Close system-notifs sub tab -->
                                
                                <!-- Payment Reminders Sub Tab -->
                                <div class="tab-pane fade" id="payment-reminders" role="tabpanel">
                                    @php
                                        $reminderPresets = __('center::settings.reminders.presets_data');
                                        if (!is_array($reminderPresets)) {
                                            $reminderPresets = [
                                                'email' => ['formal' => '', 'friendly' => '', 'urgent' => ''],
                                                'whatsapp' => ['formal' => '', 'friendly' => '', 'urgent' => '']
                                            ];
                                        }
                                    @endphp
                                    <!-- Payment Reminder Scheduling -->
                                    <div class="mb-4 mt-2">
                                        <h4 class="fw-bold text-warning"><i class="fas fa-calendar-check me-2"></i> {{ __('center::settings.tabs.reminders') }}</h4>
                                    </div>
                            @php
                                $reminderSettings = $tenant->settings['payment_reminders'] ?? [];
                                $defaultDueDay = $reminderSettings['default_due_day'] ?? 1;
                                $defaultMonthlyFee = $reminderSettings['default_monthly_fee'] ?? '';
                                $emailReminders = $reminderSettings['email_reminders'] ?? [
                                    ['days_before' => 7, 'enabled' => true],
                                    ['days_before' => 3, 'enabled' => true],
                                    ['days_before' => 1, 'enabled' => true],
                                ];
                                $whatsappReminders = $reminderSettings['whatsapp_reminders'] ?? [
                                    ['days_after' => 1, 'enabled' => true],
                                    ['days_after' => 3, 'enabled' => true],
                                    ['days_after' => 7, 'enabled' => true],
                                ];
                                $whatsappBeforeDue = $reminderSettings['whatsapp_before_due'] ?? false;
                                $overdueRepeatEnabled = $reminderSettings['overdue_repeat_enabled'] ?? false;
                                $overdueRepeatInterval = $reminderSettings['overdue_repeat_interval'] ?? 7;
                                $overdueMaxReminders = $reminderSettings['overdue_max_reminders'] ?? '';
                                $emailTemplate = $reminderSettings['email_template'] ?? '';
                                $whatsappTemplate = $reminderSettings['whatsapp_template'] ?? '';
                                $currency = $tenant->settings['financial']['currency'] ?? 'EGP';
                            @endphp

                            <form action="{{ route('center.settings.update-reminders', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST">
                                @csrf

                                <!-- Info Banner -->
                                <div class="alert border-0 rounded-4 mb-4" style="background: linear-gradient(135deg, #fef3cd 0%, #ffeaa7 100%); border-left: 4px solid #f39c12 !important;">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(243,156,18,0.15);">
                                            <i class="fas fa-robot text-warning fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1 text-dark"><i class="fas fa-info-circle me-1 text-warning"></i> {{ __('center::settings.reminders.title') }}</h6>
                                            <p class="small mb-0 text-dark opacity-75">{{ __('center::settings.reminders.info_banner') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- ====== Section 1: Default Settings ====== -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold text-dark mb-3">
                                            <i class="fas fa-cog me-2 text-primary"></i> {{ __('center::settings.reminders.default_settings') }}
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">{{ __('center::settings.reminders.default_due_day') }}</label>
                                                <select name="default_due_day" class="form-select rounded-3" id="reminderDueDay">
                                                    @for ($d = 1; $d <= 28; $d++)
                                                        <option value="{{ $d }}" {{ $defaultDueDay == $d ? 'selected' : '' }}>{{ $d }}</option>
                                                    @endfor
                                                </select>
                                                <small class="text-muted">{{ __('center::settings.reminders.default_due_day_hint') }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">{{ __('center::settings.reminders.default_monthly_fee') }} ({{ $currency }})</label>
                                                <input type="number" name="default_monthly_fee" class="form-control rounded-3" value="{{ $defaultMonthlyFee }}" min="0" step="0.01" placeholder="0.00">
                                                <small class="text-muted">{{ __('center::settings.reminders.default_monthly_fee_hint') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ====== Section 2: Pre-Due Email Reminders ====== -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-1">
                                                    <i class="fas fa-envelope me-2 text-info"></i> {{ __('center::settings.reminders.pre_due_title') }}
                                                </h6>
                                                <p class="small text-muted mb-0">{{ __('center::settings.reminders.pre_due_desc') }}</p>
                                            </div>
                                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 fw-bold">
                                                <i class="fas fa-envelope me-1"></i> {{ __('center::settings.reminders.channel_email') }}
                                            </span>
                                        </div>

                                        <div class="reminder-timeline position-relative" style="padding-right: 20px;">
                                            @foreach ($emailReminders as $index => $reminder)
                                                <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 border {{ $reminder['enabled'] ? 'active-reminder-info' : 'bg-light' }} transition-all" id="preReminder{{ $index }}">
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="email_reminders[{{ $index }}][enabled]" value="0">
                                                        <input class="form-check-input" type="checkbox" name="email_reminders[{{ $index }}][enabled]" value="1" id="emailReminderToggle{{ $index }}" {{ $reminder['enabled'] ? 'checked' : '' }} style="width: 3em; height: 1.5em;" onchange="toggleReminderStyle(this, 'preReminder{{ $index }}', 'active-reminder-info')">
                                                    </div>
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background: {{ $reminder['enabled'] ? 'linear-gradient(135deg, #00b4d8, #0077b6)' : '#dee2e6' }};">
                                                        <i class="fas fa-bell text-white"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold small">
                                                            @if ($reminder['days_before'] == 0)
                                                                {{ __('center::settings.reminders.on_due_day') }}
                                                            @else
                                                                {{ str_replace(':days', $reminder['days_before'], __('center::settings.reminders.days_before_due')) }}
                                                            @endif
                                                        </div>
                                                        <small class="text-muted">{{ __('center::settings.email_templates.payment_reminder') }}</small>
                                                    </div>
                                                    <div style="width: 100px;">
                                                        <input type="number" name="email_reminders[{{ $index }}][days_before]" class="form-control form-control-sm rounded-pill text-center fw-bold" value="{{ $reminder['days_before'] }}" min="0" max="30">
                                                        <small class="text-muted d-block text-center">{{ __('center::settings.reminders.days_before_due', ['days' => '']) }}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Optional: WhatsApp before due -->
                                        <div class="mt-3 p-3 rounded-3 border bg-light">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="hidden" name="whatsapp_before_due" value="0">
                                                <input class="form-check-input" type="checkbox" name="whatsapp_before_due" value="1" id="whatsappBeforeDue" {{ $whatsappBeforeDue ? 'checked' : '' }} style="width: 1.3em; height: 1.3em;">
                                                <label class="form-check-label fw-bold small" for="whatsappBeforeDue">
                                                    <i class="fab fa-whatsapp text-success me-1"></i> {{ __('center::settings.reminders.whatsapp_before_due') }}
                                                </label>
                                            </div>
                                            <small class="text-muted d-block mt-1 ms-4">{{ __('center::settings.reminders.whatsapp_before_due_warning') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- ====== Section 3: Post-Due Reminders (Email + WhatsApp) ====== -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-1">
                                                    <i class="fas fa-exclamation-triangle me-2 text-danger"></i> {{ __('center::settings.reminders.post_due_title') }}
                                                </h6>
                                                <p class="small text-muted mb-0">{{ __('center::settings.reminders.post_due_desc') }}</p>
                                            </div>
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-bold">
                                                <i class="fas fa-envelope me-1"></i> + <i class="fab fa-whatsapp me-1"></i> {{ __('center::settings.reminders.channel_both') }}
                                            </span>
                                        </div>

                                        <div class="reminder-timeline position-relative" style="padding-right: 20px;">
                                            @foreach ($whatsappReminders as $index => $reminder)
                                                <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 border {{ $reminder['enabled'] ? 'active-reminder-danger' : 'bg-light' }} transition-all" id="postReminder{{ $index }}">
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="whatsapp_reminders[{{ $index }}][enabled]" value="0">
                                                        <input class="form-check-input" type="checkbox" name="whatsapp_reminders[{{ $index }}][enabled]" value="1" id="whatsappReminderToggle{{ $index }}" {{ $reminder['enabled'] ? 'checked' : '' }} style="width: 3em; height: 1.5em;" onchange="toggleReminderStyle(this, 'postReminder{{ $index }}', 'active-reminder-danger')">
                                                    </div>
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background: {{ $reminder['enabled'] ? 'linear-gradient(135deg, #e74c3c, #c0392b)' : '#dee2e6' }};">
                                                        <i class="fab fa-whatsapp text-white"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold small">
                                                            {{ str_replace(':days', $reminder['days_after'], __('center::settings.reminders.days_after_due')) }}
                                                        </div>
                                                        <small class="text-muted">
                                                            <i class="fas fa-envelope me-1"></i> {{ __('center::settings.reminders.channel_email') }}
                                                            +
                                                            <i class="fab fa-whatsapp me-1"></i> {{ __('center::settings.reminders.channel_whatsapp') }}
                                                        </small>
                                                    </div>
                                                    <div style="width: 100px;">
                                                        <input type="number" name="whatsapp_reminders[{{ $index }}][days_after]" class="form-control form-control-sm rounded-pill text-center fw-bold" value="{{ $reminder['days_after'] }}" min="1" max="60">
                                                        <small class="text-muted d-block text-center">{{ __('center::settings.reminders.days_after_due', ['days' => '']) }}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- ====== Section 4: Auto-Repeat for Overdue ====== -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start gap-3 mb-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: linear-gradient(135deg, #6c5ce7, #a29bfe);">
                                                <i class="fas fa-sync-alt text-white fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold text-dark mb-1">{{ __('center::settings.reminders.overdue_auto_title') }}</h6>
                                                <p class="small text-muted mb-0">{{ __('center::settings.reminders.overdue_auto_desc') }}</p>
                                            </div>
                                        </div>

                                        <div class="p-3 rounded-3 border bg-light mb-3">
                                            <div class="form-check form-switch d-flex align-items-center gap-2">
                                                <input type="hidden" name="overdue_repeat_enabled" value="0">
                                                <input class="form-check-input" type="checkbox" name="overdue_repeat_enabled" value="1" id="overdueRepeatEnabled" {{ $overdueRepeatEnabled ? 'checked' : '' }} style="width: 3em; height: 1.5em;" onchange="toggleOverdueSettings(this)">
                                                <label class="form-check-label fw-bold" for="overdueRepeatEnabled">
                                                    {{ __('center::settings.reminders.overdue_repeat_enabled') }}
                                                </label>
                                            </div>
                                        </div>

                                        <div id="overdueSettingsPanel" class="{{ $overdueRepeatEnabled ? '' : 'd-none' }}">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted">{{ __('center::settings.reminders.overdue_repeat_interval') }}</label>
                                                    <div class="input-group">
                                                        <input type="number" name="overdue_repeat_interval" class="form-control rounded-3" value="{{ $overdueRepeatInterval }}" min="1" max="30">
                                                        <span class="input-group-text bg-white rounded-3"><i class="fas fa-calendar-day text-primary"></i></span>
                                                    </div>
                                                    <small class="text-muted">{{ __('center::settings.reminders.overdue_repeat_interval_hint') }}</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted">{{ __('center::settings.reminders.overdue_max_reminders') }}</label>
                                                    <div class="input-group">
                                                        <input type="number" name="overdue_max_reminders" class="form-control rounded-3" value="{{ $overdueMaxReminders }}" min="1" max="50" placeholder="∞">
                                                        <span class="input-group-text bg-white rounded-3"><i class="fas fa-hashtag text-primary"></i></span>
                                                    </div>
                                                    <small class="text-muted">{{ __('center::settings.reminders.overdue_max_reminders_hint') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ====== Section 5: Message Templates ====== -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold text-dark mb-3">
                                            <i class="fas fa-file-alt me-2 text-success"></i> {{ __('center::settings.reminders.email_template') }}
                                        </h6>

                                        <div class="mb-3">
                                            <div class="alert alert-light rounded-3 border mb-2 p-3">
                                                <div class="row g-3">
                                                    <div class="col-md-7 border-end">
                                                        <small class="fw-bold text-muted d-block mb-1">{{ __('center::settings.reminders.template_variables') }}</small>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            @php
                                                                $remVars = [
                                                                    'student_name' => __('center::settings.email_templates.placeholders.student_name'),
                                                                    'center_name' => __('center::settings.email_templates.placeholders.center_name'),
                                                                    'amount' => __('center::settings.email_templates.placeholders.amount'),
                                                                    'due_date' => __('center::settings.email_templates.placeholders.due_date'),
                                                                    'remaining' => __('center::settings.email_templates.placeholders.remaining'),
                                                                    'group_name' => __('center::settings.email_templates.placeholders.group_name'),
                                                                    'course_price' => __('center::settings.email_templates.placeholders.course_price'),
                                                                    'login_link' => __('center::settings.email_templates.placeholders.login_link'),
                                                                    'password' => __('center::settings.email_templates.placeholders.password'),
                                                                ];
                                                            @endphp
                                                            @foreach ($remVars as $key => $label)
                                                                <button type="button" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-0 small" onclick="insertVariable(this, 'emailTemplateArea')" data-var="{{ '{' . $key . '}' }}">{{ $label }}</button>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <small class="fw-bold text-muted d-block mb-1">{{ __('center::settings.reminders.quick_templates_email') }}</small>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 small rounded-pill" onclick="fillPreset('emailTemplateArea', `{{ $reminderPresets['email']['formal'] }}`)">{{ __('center::settings.reminders.presets.formal') }}</button>
                                                            <button type="button" class="btn btn-xs btn-outline-success py-0 px-2 small rounded-pill" onclick="fillPreset('emailTemplateArea', `{{ $reminderPresets['email']['friendly'] }}`)">{{ __('center::settings.reminders.presets.friendly') }}</button>
                                                            <button type="button" class="btn btn-xs btn-outline-danger py-0 px-2 small rounded-pill" onclick="fillPreset('emailTemplateArea', `{{ $reminderPresets['email']['urgent'] }}`)">{{ __('center::settings.reminders.presets.urgent') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <textarea name="email_template" id="emailTemplateArea" class="form-control rounded-3" rows="5" dir="auto" placeholder="{{ __('center::settings.email_templates.defaults.payment_reminder_body') }}">{{ $emailTemplate }}</textarea>
                                        </div>

                                        <hr>

                                        <h6 class="fw-bold text-dark mb-3 mt-3">
                                            <i class="fab fa-whatsapp me-2 text-success"></i> {{ __('center::settings.reminders.whatsapp_template') }}
                                        </h6>

                                        <div class="mb-3">
                                            <div class="alert alert-light rounded-3 border mb-2 p-3">
                                                <div class="row g-3">
                                                    <div class="col-md-7 border-end">
                                                        <small class="fw-bold text-muted d-block mb-1">{{ __('center::settings.reminders.template_variables') }}</small>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            @php
                                                                $waVars = [
                                                                    'student_name' => __('center::settings.email_templates.placeholders.student_name'),
                                                                    'center_name' => __('center::settings.email_templates.placeholders.center_name'),
                                                                    'amount' => __('center::settings.email_templates.placeholders.amount'),
                                                                    'due_date' => __('center::settings.email_templates.placeholders.due_date'),
                                                                    'remaining' => __('center::settings.email_templates.placeholders.remaining'),
                                                                ];
                                                            @endphp
                                                            @foreach ($waVars as $key => $label)
                                                                <button type="button" class="btn btn-xs btn-outline-success rounded-pill px-2 py-0 small" onclick="insertVariable(this, 'whatsappTemplateArea')" data-var="{{ '{' . $key . '}' }}">{{ $label }}</button>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <small class="fw-bold text-muted d-block mb-1">{{ __('center::settings.reminders.quick_templates_whatsapp') }}</small>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 small rounded-pill" onclick="fillPreset('whatsappTemplateArea', `{{ $reminderPresets['whatsapp']['formal'] }}`)">{{ __('center::settings.reminders.presets.formal') }}</button>
                                                            <button type="button" class="btn btn-xs btn-outline-success py-0 px-2 small rounded-pill" onclick="fillPreset('whatsappTemplateArea', `{{ $reminderPresets['whatsapp']['friendly'] }}`)">{{ __('center::settings.reminders.presets.friendly') }}</button>
                                                            <button type="button" class="btn btn-xs btn-outline-danger py-0 px-2 small rounded-pill" onclick="fillPreset('whatsappTemplateArea', `{{ $reminderPresets['whatsapp']['urgent'] }}`)">{{ __('center::settings.reminders.presets.urgent') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <textarea name="whatsapp_template" id="whatsappTemplateArea" class="form-control rounded-3" rows="4" dir="auto" placeholder="{{ __('center::settings.reminders.whatsapp_placeholder') }}">{{ $whatsappTemplate }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- ====== Visual Timeline Preview ====== -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold text-dark mb-3">
                                            <i class="fas fa-stream me-2 text-primary"></i> {{ __('center::settings.reminders.timeline_preview') }}
                                        </h6>
                                        <div class="position-relative" style="padding-right: 30px;">
                                            <div class="position-absolute" style="right: 14px; top: 0; bottom: 0; width: 3px; background: linear-gradient(to bottom, #00b4d8, #f39c12, #e74c3c); border-radius: 2px;"></div>

                                            @foreach ($emailReminders as $r)
                                                @if ($r['enabled'])
                                                    <div class="d-flex align-items-center gap-3 mb-2">
                                                        <div class="rounded-circle bg-info flex-shrink-0" style="width: 12px; height: 12px; position: relative; right: -22px; z-index: 1;"></div>
                                                        <div class="flex-grow-1 ps-3">
                                                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 small fw-bold">
                                                                <i class="fas fa-envelope me-1"></i>
                                                                @if ($r['days_before'] == 0)
                                                                    {{ __('center::settings.reminders.on_due_day') }}
                                                                @else
                                                                    {{ str_replace(':days', $r['days_before'], __('center::settings.reminders.days_before_due')) }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <div class="rounded-circle bg-warning flex-shrink-0" style="width: 16px; height: 16px; position: relative; right: -20px; z-index: 1; border: 2px solid #fff;"></div>
                                                <div class="flex-grow-1 ps-3">
                                                    <span class="badge bg-warning bg-opacity-25 text-dark rounded-pill px-3 py-2 small fw-bold">
                                                        <i class="fas fa-calendar-day me-1"></i> {{ __('center::settings.reminders.on_due_day') }} ({{ __('center::settings.reminders.default_due_day') }}: {{ $defaultDueDay }})
                                                    </span>
                                                </div>
                                            </div>

                                            @foreach ($whatsappReminders as $r)
                                                @if ($r['enabled'])
                                                    <div class="d-flex align-items-center gap-3 mb-2">
                                                        <div class="rounded-circle bg-danger flex-shrink-0" style="width: 12px; height: 12px; position: relative; right: -22px; z-index: 1;"></div>
                                                        <div class="flex-grow-1 ps-3">
                                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 small fw-bold">
                                                                <i class="fab fa-whatsapp me-1"></i>
                                                                {{ str_replace(':days', $r['days_after'], __('center::settings.reminders.days_after_due')) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                            @if ($overdueRepeatEnabled)
                                                <div class="d-flex align-items-center gap-3 mb-2">
                                                    <div class="rounded-circle flex-shrink-0" style="width: 12px; height: 12px; position: relative; right: -22px; z-index: 1; background: #6c5ce7;"></div>
                                                    <div class="flex-grow-1 ps-3">
                                                        <span class="badge bg-opacity-10 text-dark rounded-pill px-3 py-2 small fw-bold" style="background: rgba(108,92,231,0.1);">
                                                            <i class="fas fa-sync-alt me-1" style="color: #6c5ce7;"></i>
                                                            {{ __('center::settings.reminders.overdue_repeat_interval') }}: {{ $overdueRepeatInterval }}
                                                            @if ($overdueMaxReminders)
                                                                ({{ __('center::settings.reminders.overdue_max_reminders') }}: {{ $overdueMaxReminders }})
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-lg rounded-pill px-5 fw-bold shadow-sm" style="background: linear-gradient(135deg, #f39c12, #e67e22); color: #fff; border: none;">
                                        <i class="fas fa-save me-2"></i> {{ __('center::settings.reminders.save_settings') }}
                                    </button>
                                </div>
                                                        </form>
                                </div> <!-- Close payment-reminders sub tab -->
                            </div> <!-- Close tab-content -->
                        </div>
