                        <div class="tab-pane fade {{ $activeTab == 'financial' ? 'show active' : '' }}" id="financial" role="tabpanel" aria-labelledby="financial-tab">
                            <form action="{{ route('center.settings.update', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST">
                                @csrf
                                <h6 class="fw-bold text-primary mb-3">{{ __('center::settings.financial.title') }}</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted">{{ __('center::settings.financial.currency') }}</label>
                                    <select name="settings[financial][currency]" class="form-select">
                                        <option value="EGP" {{ ($tenant->settings['financial']['currency'] ?? '') == 'EGP' ? 'selected' : '' }}>{{ __('center::settings.financial.currencies.egp') }}</option>
                                        <option value="SAR" {{ ($tenant->settings['financial']['currency'] ?? '') == 'SAR' ? 'selected' : '' }}>{{ __('center::settings.financial.currencies.sar') }}</option>
                                        <option value="USD" {{ ($tenant->settings['financial']['currency'] ?? '') == 'USD' ? 'selected' : '' }}>{{ __('center::settings.financial.currencies.usd') }}</option>
                                        <option value="EUR" {{ ($tenant->settings['financial']['currency'] ?? '') == 'EUR' ? 'selected' : '' }}>{{ __('center::settings.financial.currencies.eur') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted">{{ __('center::settings.financial.tax_rate') }}</label>
                                    <input type="number" name="settings[financial][tax_rate]" class="form-control" value="{{ $tenant->settings['financial']['tax_rate'] ?? '0' }}" min="0" max="100">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted">{{ __('center::settings.financial.invoice_prefix') }}</label>
                                    <input type="text" name="settings[financial][invoice_prefix]" class="form-control" value="{{ $tenant->settings['financial']['invoice_prefix'] ?? 'INV-' }}" placeholder="INV-">
                                </div>
                                </div>
                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> {{ __('center::settings.general.save') }}
                                    </button>
                                </div>
                            </form>
                        </div>
