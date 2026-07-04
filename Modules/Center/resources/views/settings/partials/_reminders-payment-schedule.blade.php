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
