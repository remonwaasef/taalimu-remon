                        <div>
                            <form action="{{ route('instructor.settings.update-general') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-3 text-center border-start">
                                        <div class="mb-3">
                                            <label class="form-label d-block fw-bold text-muted small">{{ __('instructor::settings.center_logo') }}</label>
                                            <div class="position-relative d-inline-block">
                                                <img src="{{ $tenant->logo ? asset('storage/' . $tenant->logo) : 'https://ui-avatars.com/api/?name=' . urlencode($tenant->name) . '&background=3A0CA3&color=fff&size=200' }}" 
                                                     alt="Logo" class="rounded-4 shadow-sm border" style="width: 150px; height: 150px; object-fit: contain; background: #f8fafc;">
                                                <label for="logoInput" class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0 shadow" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-camera"></i>
                                                </label>
                                                <input type="file" name="logo" id="logoInput" class="d-none" accept="image/*">
                                            </div>
                                            <div class="form-text x-small mt-2">{{ __('instructor::settings.logo_hint') }}</div>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">{{ __('instructor::settings.center_name') }}</label>
                                                <input type="text" name="name" class="form-control bg-white border rounded-3" value="{{ $tenant->name }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">{{ __('instructor::settings.general_phone') }}</label>
                                                <input type="text" name="phone" class="form-control bg-white border rounded-3" value="{{ $tenant->phone }}">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold small text-muted">{{ __('instructor::settings.default_currency') }}</label>
                                                <select name="currency" class="form-control bg-white border rounded-3">
                                                    <option value="ج.م" {{ (($tenant->settings ?? [])['currency'] ?? '') == 'ج.م' ? 'selected' : '' }}>جنيه مصري (ج.م)</option>
                                                    <option value="EGP" {{ (($tenant->settings ?? [])['currency'] ?? '') == 'EGP' ? 'selected' : '' }}>Egyptian Pound (EGP)</option>
                                                    <option value="SAR" {{ (($tenant->settings ?? [])['currency'] ?? '') == 'SAR' ? 'selected' : '' }}>Saudi Riyal (SAR)</option>
                                                    <option value="$" {{ (($tenant->settings ?? [])['currency'] ?? '') == '$' ? 'selected' : '' }}>US Dollar ($)</option>
                                                    <option value="€" {{ (($tenant->settings ?? [])['currency'] ?? '') == '€' ? 'selected' : '' }}>Euro (€)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold small text-muted">
                                                    <i class="fas fa-video text-primary me-1"></i> رابط البث المباشر الافتراضي (Google Meet / Zoom)
                                                </label>
                                                <input type="url" name="default_meeting_link" class="form-control bg-white border rounded-3" 
                                                       value="{{ ($tenant->settings ?? [])['default_meeting_link'] ?? (auth()->user()->instructor?->default_meeting_link ?? '') }}" 
                                                       placeholder="https://meet.google.com/xxx-xxxx-xxx أو رابط اجتماع Zoom الدائم">
                                                <div class="form-text x-small text-muted">
                                                    <i class="fas fa-info-circle text-info me-1"></i> سيتم إدراج هذا الرابط تلقائياً عند جدولة أي حصة أونلاين جديدة دون الحاجة لكتابته كل مرة.
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold small text-muted">{{ __('instructor::settings.address') }}</label>
                                                <input type="text" name="address" class="form-control bg-white border rounded-3" value="{{ $tenant->address }}">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold small text-muted">{{ __('instructor::settings.description') }}</label>
                                                <textarea name="description" class="form-control bg-white border rounded-3" rows="3">{{ $tenant->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-start mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i> {{ __('instructor::settings.save_changes') }}
                                    </button>
                                </div>
                            </form>
                        </div>
