                        <div >
                            @php $reminderSettings = ($tenant->settings ?? [])['payment_reminders'] ?? []; @endphp
                            <form action="{{ route('instructor.reminders.update') }}" method="POST">
                                @csrf
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-bell me-2"></i> {{ __('instructor::reminders.title') }}</h5>
                                </div>
                                <p class="text-muted small mb-4">{{ __('instructor::reminders.subtitle') }}</p>

                                {{-- Default Settings --}}
                                <div class="card border bg-light shadow-none rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-cog me-2 text-muted"></i> الإعدادات الافتراضية</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">{{ __('instructor::reminders.default_due_day') }}</label>
                                                <select name="default_due_day" class="form-select bg-white">
                                                    @for($d = 1; $d <= 28; $d++)
                                                        <option value="{{ $d }}" {{ ($reminderSettings['default_due_day'] ?? 25) == $d ? 'selected' : '' }}>{{ $d }}</option>
                                                    @endfor
                                                </select>
                                                <div class="form-text small">{{ __('instructor::reminders.default_due_day_hint') }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">{{ __('instructor::reminders.default_monthly_fee') }}</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="default_monthly_fee" class="form-control bg-white" value="{{ $reminderSettings['default_monthly_fee'] ?? '' }}" placeholder="0.00">
                                                    <span class="input-group-text bg-white">{{ ($tenant->settings ?? [])['currency'] ?? 'ج.م' }}</span>
                                                </div>
                                                <div class="form-text small">{{ __('instructor::reminders.default_monthly_fee_hint') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Email Reminders (Pre-Due) --}}
                                <div class="card border bg-light shadow-none rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-1"><i class="fas fa-envelope me-2 text-info"></i> {{ __('instructor::reminders.email_section') }}</h6>
                                        <p class="text-muted small mb-3">{{ __('instructor::reminders.email_section_hint') }}</p>
                                        <div class="row g-3">
                                            @php
                                                $emailDefaults = [
                                                    ['days_before' => 7, 'label' => __('instructor::reminders.days_before_due', ['days' => 7])],
                                                    ['days_before' => 3, 'label' => __('instructor::reminders.days_before_due', ['days' => 3])],
                                                    ['days_before' => 0, 'label' => __('instructor::reminders.on_due_day')],
                                                ];
                                                $emailReminders = $reminderSettings['email_reminders'] ?? [
                                                    ['days_before' => 7, 'enabled' => true],
                                                    ['days_before' => 3, 'enabled' => true],
                                                    ['days_before' => 0, 'enabled' => true],
                                                ];
                                            @endphp
                                            @foreach($emailDefaults as $i => $def)
                                                <div class="col-md-4">
                                                    <div class="bg-white border rounded-3 p-3 h-100">
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="email_reminders[{{ $i }}][days_before]" value="{{ $def['days_before'] }}">
                                                            <input type="hidden" name="email_reminders[{{ $i }}][enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="email_reminders[{{ $i }}][enabled]" value="1" id="email_{{ $i }}" {{ ($emailReminders[$i]['enabled'] ?? true) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold small" for="email_{{ $i }}">{{ $def['label'] }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- WhatsApp Reminders (Post-Due) --}}
                                <div class="card border bg-light shadow-none rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-1"><i class="fab fa-whatsapp me-2 text-success"></i> {{ __('instructor::reminders.whatsapp_section') }}</h6>
                                        <p class="text-muted small mb-3">{{ __('instructor::reminders.whatsapp_section_hint') }}</p>
                                        <div class="row g-3">
                                            @php
                                                $waDefaults = [
                                                    ['days_after' => 1, 'label' => __('instructor::reminders.days_after_due', ['days' => 1])],
                                                    ['days_after' => 3, 'label' => __('instructor::reminders.days_after_due', ['days' => 3])],
                                                    ['days_after' => 7, 'label' => __('instructor::reminders.days_after_due', ['days' => 7])],
                                                ];
                                                $waReminders = $reminderSettings['whatsapp_reminders'] ?? [
                                                    ['days_after' => 1, 'enabled' => true],
                                                    ['days_after' => 3, 'enabled' => true],
                                                    ['days_after' => 7, 'enabled' => false],
                                                ];
                                            @endphp
                                            @foreach($waDefaults as $i => $def)
                                                <div class="col-md-4">
                                                    <div class="bg-white border rounded-3 p-3 h-100">
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="whatsapp_reminders[{{ $i }}][days_after]" value="{{ $def['days_after'] }}">
                                                            <input type="hidden" name="whatsapp_reminders[{{ $i }}][enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="whatsapp_reminders[{{ $i }}][enabled]" value="1" id="wa_{{ $i }}" {{ ($waReminders[$i]['enabled'] ?? false) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold small" for="wa_{{ $i }}">{{ $def['label'] }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="mt-3">
                                            <div class="form-check form-switch">
                                                <input type="hidden" name="whatsapp_before_due" value="0">
                                                <input class="form-check-input" type="checkbox" name="whatsapp_before_due" value="1" id="waBefore" {{ ($reminderSettings['whatsapp_before_due'] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold small" for="waBefore">{{ __('instructor::reminders.whatsapp_before_due') }}</label>
                                            </div>
                                            <div class="form-text small text-warning">{{ __('instructor::reminders.whatsapp_before_due_warning') }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Message Templates --}}
                                <div class="card border bg-light shadow-none rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-comment-alt me-2 text-primary"></i> قوالب الرسائل</h6>
                                        <div class="alert alert-info border-0 shadow-none rounded-3 py-2 px-3 mb-3">
                                            <div class="small">
                                                {{ __('instructor::reminders.template_variables') }}
                                                <code>:student_name</code> ،
                                                <code>:amount</code> ،
                                                <code>:due_day</code> ،
                                                <code>:tenant_name</code> ،
                                                <code>:month</code> ،
                                                <code>:currency</code>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">{{ __('instructor::reminders.email_template') }}</label>
                                                <textarea name="email_template" class="form-control bg-white" rows="4" placeholder="اتركه فارغاً لاستخدام القالب الافتراضي">{{ $reminderSettings['email_template'] ?? '' }}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">{{ __('instructor::reminders.whatsapp_template') }}</label>
                                                <textarea name="whatsapp_template" class="form-control bg-white" rows="4" placeholder="اتركه فارغاً لاستخدام القالب الافتراضي">{{ $reminderSettings['whatsapp_template'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-start mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i> {{ __('instructor::reminders.save_settings') }}
                                    </button>
                                </div>
                            </form>
                        </div>
