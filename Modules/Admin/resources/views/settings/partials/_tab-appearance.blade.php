                    <div class="tab-pane fade" id="appearance" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.primary_color') }}</label>
                                <div class="d-flex gap-3 align-items-center">
                                    <input type="color" class="form-control form-control-color rounded-circle border-0 shadow-sm" name="primary_color" value="{{ \App\Models\SiteSetting::get('primary_color', '#3A0CA3') }}" style="width: 50px; height: 50px;">
                                    <span class="text-muted small">Deep Indigo (Brand Primary)</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.secondary_color') }}</label>
                                <div class="d-flex gap-3 align-items-center">
                                    <input type="color" class="form-control form-control-color rounded-circle border-0 shadow-sm" name="secondary_color" value="{{ \App\Models\SiteSetting::get('secondary_color', '#4361EE') }}" style="width: 50px; height: 50px;">
                                    <span class="text-muted small">Royal Blue (Brand Secondary)</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch p-0 d-flex align-items-center justify-content-between bg-light rounded-4 p-3 border border-light">
                                    <div>
                                        <label class="form-check-label fw-bold mb-0" for="darkModeSwitch">{{ __('admin.enable_dark_mode') }}</label>
                                        <div class="text-muted small">{{ __('admin.dark_mode_note') }}</div>
                                    </div>
                                    <input class="form-check-input ms-0 mt-0" type="checkbox" name="default_dark_mode" id="darkModeSwitch" value="1" {{ \App\Models\SiteSetting::get('default_dark_mode') ? 'checked' : '' }} style="width: 50px; height: 25px;">
                                </div>
                            </div>
                        </div>
                    </div>
