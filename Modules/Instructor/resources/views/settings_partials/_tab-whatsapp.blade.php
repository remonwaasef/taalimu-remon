                        <div class="tab-pane fade" id="whatsapp" role="tabpanel">
                            <form action="{{ route('instructor.whatsapp.update') }}" method="POST">
                                @csrf
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h5 class="fw-bold mb-0 text-success"><i class="fab fa-whatsapp me-2"></i> {{ __('instructor::settings.whatsapp_connection') }}</h5>
                                    <div class="form-check form-switch custom-switch">
                                        <input class="form-check-input" type="checkbox" name="enabled" id="whatsappEnabled" {{ ($settings['enabled'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold ms-2" for="whatsappEnabled">{{ __('instructor::settings.enable_service') }}</label>
                                    </div>
                                </div>

                                <div class="row g-4 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted">{{ __('instructor::settings.default_country_code') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i class="fas fa-globe text-muted"></i></span>
                                            <input type="text" name="country_code" class="form-control bg-white border" value="{{ $settings['country_code'] ?? '20' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted">{{ __('instructor::settings.instance_id') }}</label>
                                        <input type="text" name="instance_id" class="form-control bg-white border" value="{{ $settings['instance_id'] ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted">{{ __('instructor::settings.token') }}</label>
                                        <input type="password" name="token" class="form-control bg-white border" value="{{ $settings['token'] ?? '' }}">
                                    </div>
                                </div>

                                <hr class="my-4 opacity-50">

                                <h6 class="fw-bold mb-3"><i class="fas fa-comment-alt me-2 text-primary"></i> {{ __('instructor::settings.message_templates') }}</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted">{{ __('instructor::settings.attendance_msg') }}</label>
                                        <textarea name="attendance_template" class="form-control bg-white border" rows="4">{{ $settings['attendance_template'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted">{{ __('instructor::settings.payment_msg') }}</label>
                                        <textarea name="payment_template" class="form-control bg-white border" rows="4">{{ $settings['payment_template'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted">{{ __('instructor::settings.debt_msg') }}</label>
                                        <textarea name="debt_template" class="form-control bg-white border" rows="4">{{ $settings['debt_template'] ?? '' }}</textarea>
                                    </div>
                                </div>

                                <div class="text-start mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">
                                        <i class="fas fa-check-circle me-2"></i> {{ __('instructor::settings.save_whatsapp') }}
                                    </button>
                                </div>
                            </form>
                        </div>
