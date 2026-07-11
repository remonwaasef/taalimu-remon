                    <div class="tab-pane fade" id="security" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="form-check form-switch p-0 d-flex align-items-center justify-content-between bg-light rounded-4 p-3 border border-light mb-3">
                                    <div>
                                        <label class="form-check-label fw-bold mb-0">{{ __('admin.enable_2fa') }}</label>
                                        <div class="text-muted small">{{ __('admin.2fa_note') }}</div>
                                    </div>
                                    <input class="form-check-input ms-0 mt-0" type="checkbox" name="enable_2fa" value="1" {{ \App\Models\SiteSetting::get('enable_2fa', true) ? 'checked' : '' }} style="width: 50px; height: 25px;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.session_lifetime') }}</label>
                                <input type="number" class="form-control rounded-4 shadow-sm border-light" name="session_lifetime" value="{{ \App\Models\SiteSetting::get('session_lifetime', 120) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.max_login_attempts') }}</label>
                                <input type="number" class="form-control rounded-4 shadow-sm border-light" name="max_login_attempts" value="{{ \App\Models\SiteSetting::get('max_login_attempts', 5) }}">
                            </div>
                        </div>
                    </div>
