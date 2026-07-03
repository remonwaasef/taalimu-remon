                        <div class="tab-pane fade {{ $activeTab == 'whatsapp' ? 'show active' : '' }}" id="whatsapp" role="tabpanel" aria-labelledby="whatsapp-tab">
                            <form action="{{ route('center.settings.update', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST">
                                @csrf
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                                        <i class="fab fa-whatsapp fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">{{ __('center::settings.whatsapp.title') }}</h5>
                                        <p class="text-muted small mb-0">{{ __('center::settings.whatsapp.desc') }}</p>
                                    </div>
                                </div>

                                <div class="card border bg-light shadow-none mb-4">
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-4">
                                            <input type="hidden" name="settings[whatsapp][enabled]" value="0">
                                            <input class="form-check-input" type="checkbox" name="settings[whatsapp][enabled]" value="1" id="whatsappEnabled" {{ ($tenant->settings['whatsapp']['enabled'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="whatsappEnabled">{{ __('center::settings.whatsapp.enabled') }}</label>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">Phone Number ID</label>
                                                <input type="text" name="settings[whatsapp][phone_number_id]" class="form-control" value="{{ $tenant->settings['whatsapp']['phone_number_id'] ?? '' }}" placeholder="1234567890">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">Access Token</label>
                                                <input type="password" name="settings[whatsapp][access_token]" class="form-control" value="{{ $tenant->settings['whatsapp']['access_token'] ?? '' }}" placeholder="EAAG...">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label fw-bold small text-muted"><i class="fas fa-globe me-1"></i> {{ __('center::settings.whatsapp.default_country_code') }}</label>
                                                <select name="settings[whatsapp][country_code]" class="form-select">
                                                    @php $cc = $tenant->settings['whatsapp']['country_code'] ?? '20'; @endphp
                                                    <option value="20"  {{ $cc == '20'  ? 'selected' : '' }}>🇪🇬 (+20)</option>
                                                    <option value="966" {{ $cc == '966' ? 'selected' : '' }}>🇸🇦 (+966)</option>
                                                </select>
                                                <small class="text-muted">{{ __('center::settings.whatsapp.default_country_code_help') }}</small>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label fw-bold small text-muted">API Version</label>
                                                <input type="text" name="settings[whatsapp][api_version]" class="form-control" value="{{ $tenant->settings['whatsapp']['api_version'] ?? 'v21.0' }}" placeholder="v21.0">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-success border-0 rounded-4 bg-opacity-10 py-3">
                                    <h6 class="fw-bold"><i class="fas fa-lightbulb me-2 text-success"></i> WhatsApp Official Setup</h6>
                                    <p class="small mb-0 mt-2">
                                        {{ __('center::settings.whatsapp.official_note') ?? 'Ensure templates are approved in Meta Business Suite.' }}
                                    </p>
                                </div>
                                <div class="alert alert-info border-0 rounded-4">
                                    <h6 class="fw-bold"><i class="fas fa-lightbulb me-2"></i>{{ __('center::settings.whatsapp.info_title') }}</h6>
                                    <ul class="small mb-0 mt-2">
                                        <li><strong>{{ __('center::settings.whatsapp.attendance_msg') }}</strong></li>
                                        <li><strong>{{ __('center::settings.whatsapp.payment_msg') }}</strong></li>
                                    </ul>
                                </div>


                                <h5 class="fw-bold mt-4 mb-3"><i class="fas fa-language me-2 text-primary"></i> قوالب الرسائل متعددة اللغات</h5>
                                <p class="text-muted small">يمكنك تخصيص رسائل الواتساب لكل لغة. اترك الحقل فارغاً لاستخدام النص الافتراضي للنظام.</p>
                                
                                <!-- Attendance Templates -->
                                <div class="card border bg-white shadow-sm mb-3">
                                    <div class="card-header bg-light fw-bold py-2">
                                        <i class="fas fa-user-check text-success me-2"></i> إشعار الحضور (Attendance)
                                        <div class="small fw-normal text-muted mt-1" dir="ltr" style="text-align: right;">Variables: :student_name, :course_name, :tenant_name</div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">العربية (ar)</label>
                                                <textarea name="settings[whatsapp][attendance_template_ar]" class="form-control text-end" rows="3" dir="rtl">{{ $tenant->settings['whatsapp']['attendance_template_ar'] ?? '' }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">English (en)</label>
                                                <textarea name="settings[whatsapp][attendance_template_en]" class="form-control text-start" rows="3" dir="ltr">{{ $tenant->settings['whatsapp']['attendance_template_en'] ?? '' }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">Français (fr)</label>
                                                <textarea name="settings[whatsapp][attendance_template_fr]" class="form-control text-start" rows="3" dir="ltr">{{ $tenant->settings['whatsapp']['attendance_template_fr'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Templates -->
                                <div class="card border bg-white shadow-sm mb-3">
                                    <div class="card-header bg-light fw-bold py-2">
                                        <i class="fas fa-money-bill-wave text-success me-2"></i> إشعار الدفع (Payment)
                                        <div class="small fw-normal text-muted mt-1" dir="ltr" style="text-align: right;">Variables: :amount, :currency, :student_name, :remaining, :tenant_name</div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">العربية (ar)</label>
                                                <textarea name="settings[whatsapp][payment_template_ar]" class="form-control text-end" rows="3" dir="rtl">{{ $tenant->settings['whatsapp']['payment_template_ar'] ?? '' }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">English (en)</label>
                                                <textarea name="settings[whatsapp][payment_template_en]" class="form-control text-start" rows="3" dir="ltr">{{ $tenant->settings['whatsapp']['payment_template_en'] ?? '' }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">Français (fr)</label>
                                                <textarea name="settings[whatsapp][payment_template_fr]" class="form-control text-start" rows="3" dir="ltr">{{ $tenant->settings['whatsapp']['payment_template_fr'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Debt Reminder Templates -->
                                <div class="card border bg-white shadow-sm mb-4">
                                    <div class="card-header bg-light fw-bold py-2">
                                        <i class="fas fa-exclamation-circle text-danger me-2"></i> تذكير بالديون (Debt Reminder)
                                        <div class="small fw-normal text-muted mt-1" dir="ltr" style="text-align: right;">Variables: :amount, :currency, :student_name, :tenant_name</div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">العربية (ar)</label>
                                                <textarea name="settings[whatsapp][debt_template_ar]" class="form-control text-end" rows="3" dir="rtl">{{ $tenant->settings['whatsapp']['debt_template_ar'] ?? '' }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">English (en)</label>
                                                <textarea name="settings[whatsapp][debt_template_en]" class="form-control text-start" rows="3" dir="ltr">{{ $tenant->settings['whatsapp']['debt_template_en'] ?? '' }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">Français (fr)</label>
                                                <textarea name="settings[whatsapp][debt_template_fr]" class="form-control text-start" rows="3" dir="ltr">{{ $tenant->settings['whatsapp']['debt_template_fr'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> {{ __('center::settings.general.save') }}
                                    </button>
                                </div>
                            </form>
                        </div>
