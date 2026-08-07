                    <div class="tab-pane fade" id="plans" role="tabpanel">
                        <div class="row g-4 overflow-hidden px-1">
                            @foreach($packages as $package)
                            @php
                                $planTheme = match($package->slug) {
                                    'pro' => 'plan-pro',
                                    'basic' => 'plan-basic',
                                    default => 'plan-free'
                                };
                            @endphp
                            <div class="col-xl-6 col-md-12">
                                <div class="card h-100 rounded-4 border-0 shadow-sm premium-plan-card {{ $planTheme }}">
                                    <!-- Plan Header Accent -->
                                    <div class="plan-accent-bar"></div>
                                    
                                    <div class="card-body p-4 pt-5">
                                        <!-- Header Section -->
                                        <div class="d-flex justify-content-between align-items-start mb-4">
                                            <div class="d-flex align-items-center gap-3 w-100">
                                                <div class="package-icon rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                                    <i class="bi bi-box-seam text-primary fs-4"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="row g-2">
                                                        <div class="col-md-3">
                                                            <label class="small text-muted mb-0">Slug</label>
                                                            <input type="text" class="form-control form-control-sm bg-light fw-bold" name="packages[{{ $package->id }}][slug]" value="{{ $package->slug }}">
                                                        </div>
                                                        <div class="col-md-9" x-data="{ 
                                                            activeLang: '{{ app()->getLocale() }}',
                                                            arVal: {{ Js::from($package->name) }},
                                                            enVal: {{ Js::from($package->name_en) }},
                                                            frVal: {{ Js::from($package->name_fr) }}
                                                        }">
                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                <label class="small text-muted mb-0" x-text="activeLang === 'ar' ? '{{ __('admin.package_name_ar') }}' : (activeLang === 'en' ? '{{ __('admin.package_name_en') }}' : 'Nom (FR)')"></label>
                                                                <div class="d-flex gap-1 bg-light p-1 rounded-pill border">
                                                                    <button type="button" @click="activeLang = 'ar'" :class="activeLang === 'ar' ? 'btn-primary shadow-sm' : 'btn-light text-muted border-0'" class="btn btn-sm py-0 px-2 rounded-pill position-relative transition-all" style="font-size: 0.65rem; font-weight: 700;">
                                                                        AR
                                                                        <span x-show="!arVal" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" title="{{ __('admin.missing_translation') }}"></span>
                                                                    </button>
                                                                    <button type="button" @click="activeLang = 'en'" :class="activeLang === 'en' ? 'btn-primary shadow-sm' : 'btn-light text-muted border-0'" class="btn btn-sm py-0 px-2 rounded-pill position-relative transition-all" style="font-size: 0.65rem; font-weight: 700;">
                                                                        EN
                                                                        <span x-show="!enVal" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" title="{{ __('admin.missing_translation') }}"></span>
                                                                    </button>
                                                                    <button type="button" @click="activeLang = 'fr'" :class="activeLang === 'fr' ? 'btn-primary shadow-sm' : 'btn-light text-muted border-0'" class="btn btn-sm py-0 px-2 rounded-pill position-relative transition-all" style="font-size: 0.65rem; font-weight: 700;">
                                                                        FR
                                                                        <span x-show="!frVal" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" title="{{ __('admin.missing_translation') }}"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div x-show="activeLang === 'ar'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                                                <input type="text" class="form-control form-control-sm" name="packages[{{ $package->id }}][name]" x-model="arVal">
                                                            </div>
                                                            <div x-show="activeLang === 'en'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display: none;">
                                                                <input type="text" class="form-control form-control-sm text-end" dir="ltr" name="packages[{{ $package->id }}][name_en]" x-model="enVal">
                                                            </div>
                                                            <div x-show="activeLang === 'fr'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display: none;">
                                                                <input type="text" class="form-control form-control-sm text-end" dir="ltr" name="packages[{{ $package->id }}][name_fr]" x-model="frVal">
                                                            </div>
                                                        </div>
                                                    </div>
 streams.
                                                    <span class="badge bg-light text-muted border mt-1" style="font-size: 0.65rem;">{{ $package->slug }}</span>
                                                </div>
                                            </div>
                                            <div class="text-end ms-3">
                                                <div class="form-check form-switch p-0 m-0 d-flex flex-column align-items-center">
                                                    <input class="form-check-input premium-switch ms-0" type="checkbox" name="packages[{{ $package->id }}][is_active]" id="active_{{ $package->id }}" {{ $package->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label small fw-bold mt-1 toggle-label" for="active_{{ $package->id }}">{{ __('admin.active') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Base Pricing -->
                                        <div class="bg-light p-3 rounded-4 mb-4">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label small fw-bold text-primary mb-0"><i class="bi bi-tag"></i> {{ __('admin::admin.base_price') }}</label>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-3">
                                                    <label class="small text-muted mb-0">{{ __('admin::admin.price_monthly') }}</label>
                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][price]" value="{{ $package->price }}">
                                                </div>
                                                <div class="col-3">
                                                    <label class="small text-muted mb-0">سعر الترم</label>
                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][term_price]" value="{{ $package->term_price }}">
                                                </div>
                                                <div class="col-3">
                                                    <label class="small text-muted mb-0">{{ __('admin::admin.price_yearly') }}</label>
                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][yearly_price]" value="{{ $package->yearly_price }}">
                                                </div>
                                                <div class="col-3">
                                                    <label class="small text-muted mb-0">{{ __('admin::admin.old_price') }}</label>
                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][old_price]" value="{{ $package->old_price }}">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Regional Pricing (Smart Pricing) -->
                                        <div class="bg-light p-3 rounded-4 mb-4">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label small fw-bold text-primary mb-0"><i class="bi bi-globe-americas"></i> {{ __('admin::admin.regional_prices') }} (Smart Pricing)</label>
                                                <span class="badge bg-white text-muted border">{{ __('admin::admin.auto_detected') }}</span>
                                            </div>
                                            
                                            <div class="mb-3" id="regionalAccordion{{ $package->id }}" x-data="{ activeRegion: null }">
                                                @php $regional = $package->regional_prices ?? []; @endphp
                                                
                                                <!-- Region: Egypt -->
                                                <div class="border rounded-3 bg-white mb-2 overflow-hidden">
                                                    <button class="w-100 d-flex align-items-center justify-content-between p-2 px-3 border-0 bg-transparent small fw-bold text-start" type="button" @click="activeRegion = (activeRegion === 'eg' ? null : 'eg')">
                                                        <span>🇪🇬 Egypt (EGP)</span>
                                                        <i class="bi" :class="activeRegion === 'eg' ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                                    </button>
                                                    <div x-show="activeRegion === 'eg'" class="p-3 border-top bg-light" style="display: none;">
                                                        <div class="row g-2 d-none">
                                                            <div class="col-3">
                                                                <label class="small text-muted mb-0">Slug (ID Unique)</label>
                                                                <input type="text" class="form-control form-control-sm rounded-3 fw-bold bg-light" name="packages[{{ $package->id }}][slug]" value="{{ $package->slug }}" disabled>
                                                            </div>
                                                            <div class="col-3">
                                                                <label class="small text-muted mb-0">Nom du Forfait (EN)</label>
                                                                <input type="text" class="form-control form-control-sm rounded-3" name="packages[{{ $package->id }}][name_en]" value="{{ $package->name_en }}" disabled>
                                                            </div>
                                                            <div class="col-3">
                                                                <label class="small text-muted mb-0">Nom du Forfait (FR)</label>
                                                                <input type="text" class="form-control form-control-sm rounded-3" name="packages[{{ $package->id }}][name_fr]" value="{{ $package->name_fr }}" disabled>
                                                            </div>
                                                            <div class="col-3">
                                                                <label class="small text-muted mb-0">اسم الباقة (AR)</label>
                                                                <input type="text" class="form-control form-control-sm rounded-3" name="packages[{{ $package->id }}][name]" value="{{ $package->name }}" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="row g-2 mt-1">
                                                            <input type="hidden" name="packages[{{ $package->id }}][regional_prices][EG][currency]" value="EGP">
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">{{ __('admin::admin.price_monthly') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][EG][amount]" value="{{ $regional['EG']['amount'] ?? '' }}">
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">سعر الترم</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][EG][term_price]" value="{{ $regional['EG']['term_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">{{ __('admin.price_yearly') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][EG][yearly_price]" value="{{ $regional['EG']['yearly_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="small text-muted mb-0">{{ __('admin.old_price') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][EG][old_price]" value="{{ $regional['EG']['old_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="small text-muted mb-0">{{ __('admin.discount_label') }}</label>
                                                                <input type="text" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][EG][discount_label]" value="{{ $regional['EG']['discount_label'] ?? '' }}" placeholder="وفر 20%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Region: Saudi -->
                                                <div class="border rounded-3 bg-white mb-2 overflow-hidden">
                                                    <button class="w-100 d-flex align-items-center justify-content-between p-2 px-3 border-0 bg-transparent small fw-bold text-start" type="button" @click="activeRegion = (activeRegion === 'sa' ? null : 'sa')">
                                                        <span>🇸🇦 Saudi Arabia (SAR)</span>
                                                        <i class="bi" :class="activeRegion === 'sa' ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                                    </button>
                                                    <div x-show="activeRegion === 'sa'" class="p-3 border-top bg-light" style="display: none;">
                                                        <div class="row g-2">
                                                            <input type="hidden" name="packages[{{ $package->id }}][regional_prices][SA][currency]" value="SAR">
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">{{ __('admin.price_monthly') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][SA][amount]" value="{{ $regional['SA']['amount'] ?? '' }}">
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">سعر الترم</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][SA][term_price]" value="{{ $regional['SA']['term_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">{{ __('admin.price_yearly') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][SA][yearly_price]" value="{{ $regional['SA']['yearly_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="small text-muted mb-0">{{ __('admin.old_price') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][SA][old_price]" value="{{ $regional['SA']['old_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="small text-muted mb-0">{{ __('admin.discount_label') }}</label>
                                                                <input type="text" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][SA][discount_label]" value="{{ $regional['SA']['discount_label'] ?? '' }}" placeholder="Save 20%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Region: UAE -->
                                                <div class="border rounded-3 bg-white mb-2 overflow-hidden">
                                                    <button class="w-100 d-flex align-items-center justify-content-between p-2 px-3 border-0 bg-transparent small fw-bold text-start" type="button" @click="activeRegion = (activeRegion === 'ae' ? null : 'ae')">
                                                        <span>🇦🇪 UAE (AED)</span>
                                                        <i class="bi" :class="activeRegion === 'ae' ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                                    </button>
                                                    <div x-show="activeRegion === 'ae'" class="p-3 border-top bg-light" style="display: none;">
                                                        <div class="row g-2">
                                                            <input type="hidden" name="packages[{{ $package->id }}][regional_prices][AE][currency]" value="AED">
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">{{ __('admin.price_monthly') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][AE][amount]" value="{{ $regional['AE']['amount'] ?? '' }}">
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">سعر الترم</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][AE][term_price]" value="{{ $regional['AE']['term_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">{{ __('admin.price_yearly') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][AE][yearly_price]" value="{{ $regional['AE']['yearly_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="small text-muted mb-0">{{ __('admin.old_price') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][AE][old_price]" value="{{ $regional['AE']['old_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="small text-muted mb-0">{{ __('admin.discount_label') }}</label>
                                                                <input type="text" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][AE][discount_label]" value="{{ $regional['AE']['discount_label'] ?? '' }}" placeholder="Save 20%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Region: Europe -->
                                                <div class="border rounded-3 bg-white mb-2 overflow-hidden">
                                                    <button class="w-100 d-flex align-items-center justify-content-between p-2 px-3 border-0 bg-transparent small fw-bold text-start" type="button" @click="activeRegion = (activeRegion === 'eu' ? null : 'eu')">
                                                        <span>🇪🇺 Europe (EUR)</span>
                                                        <i class="bi" :class="activeRegion === 'eu' ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                                    </button>
                                                    <div x-show="activeRegion === 'eu'" class="p-3 border-top bg-light" style="display: none;">
                                                        <div class="row g-2">
                                                            <input type="hidden" name="packages[{{ $package->id }}][regional_prices][FR][currency]" value="EUR">
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">{{ __('admin.price_monthly') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][FR][amount]" value="{{ $regional['FR']['amount'] ?? '' }}">
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">سعر الترم</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][FR][term_price]" value="{{ $regional['FR']['term_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">{{ __('admin.price_yearly') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][FR][yearly_price]" value="{{ $regional['FR']['yearly_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="small text-muted mb-0">{{ __('admin.old_price') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][FR][old_price]" value="{{ $regional['FR']['old_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="small text-muted mb-0">{{ __('admin.discount_label') }}</label>
                                                                <input type="text" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][FR][discount_label]" value="{{ $regional['FR']['discount_label'] ?? '' }}" placeholder="OFF 20%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Region: United States -->
                                                <div class="border rounded-3 bg-white mb-2 overflow-hidden">
                                                    <button class="w-100 d-flex align-items-center justify-content-between p-2 px-3 border-0 bg-transparent small fw-bold text-start" type="button" @click="activeRegion = (activeRegion === 'us' ? null : 'us')">
                                                        <span>🇺🇸 United States (USD)</span>
                                                        <i class="bi" :class="activeRegion === 'us' ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                                    </button>
                                                    <div x-show="activeRegion === 'us'" class="p-3 border-top bg-light" style="display: none;">
                                                        <div class="row g-2">
                                                            <input type="hidden" name="packages[{{ $package->id }}][regional_prices][US][currency]" value="USD">
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">{{ __('admin.price_monthly') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][US][amount]" value="{{ $regional['US']['amount'] ?? '' }}">
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">سعر الترم</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][US][term_price]" value="{{ $regional['US']['term_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="small text-muted mb-0">{{ __('admin.price_yearly') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][US][yearly_price]" value="{{ $regional['US']['yearly_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="small text-muted mb-0">{{ __('admin.old_price') }}</label>
                                                                <input type="number" step="0.01" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][US][old_price]" value="{{ $regional['US']['old_price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="small text-muted mb-0">{{ __('admin.discount_label') }}</label>
                                                                <input type="text" class="form-control form-control-sm text-dark bg-white" name="packages[{{ $package->id }}][regional_prices][US][discount_label]" value="{{ $regional['US']['discount_label'] ?? '' }}" placeholder="Save 20%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row g-3 mb-4">
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-muted mb-1">{{ __('admin.duration_days') }}</label>
                                                <input type="number" class="form-control rounded-3" name="packages[{{ $package->id }}][duration_in_days]" value="{{ $package->duration_in_days }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-muted mb-1">{{ __('admin.trial_days') }}</label>
                                                <input type="number" class="form-control rounded-3" name="packages[{{ $package->id }}][trial_days]" value="{{ $package->trial_days }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-muted mb-1">{{ __('admin.sort_order') }}</label>
                                                <input type="number" class="form-control rounded-3" name="packages[{{ $package->id }}][sort_order]" value="{{ $package->sort_order }}">
                                            </div>
                                        </div>

                                        <!-- Custom CTA & Payment IDs -->
                                        <div class="bg-light p-3 rounded-4 mb-4">
                                            <div class="mb-4 mt-3" x-data="{ 
                                                activeLang: '{{ app()->getLocale() }}',
                                                arVal: {{ Js::from($package->description) }},
                                                enVal: {{ Js::from($package->description_en) }},
                                                frVal: {{ Js::from($package->description_fr) }}
                                            }">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <label class="form-label small fw-bold text-primary mb-0"><i class="bi bi-card-text"></i> {{ __('admin.description') ?? 'Description' }} (<span x-text="activeLang.toUpperCase()"></span>)</label>
                                                    <div class="d-flex gap-1 bg-light p-1 rounded-pill border">
                                                        <button type="button" @click="activeLang = 'ar'" :class="activeLang === 'ar' ? 'btn-primary shadow-sm' : 'btn-light text-muted border-0'" class="btn btn-sm py-0 px-2 rounded-pill position-relative transition-all" style="font-size: 0.65rem; font-weight: 700;">
                                                            AR
                                                            <span x-show="!arVal" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" title="{{ __('admin.missing_translation') }}"></span>
                                                        </button>
                                                        <button type="button" @click="activeLang = 'en'" :class="activeLang === 'en' ? 'btn-primary shadow-sm' : 'btn-light text-muted border-0'" class="btn btn-sm py-0 px-2 rounded-pill position-relative transition-all" style="font-size: 0.65rem; font-weight: 700;">
                                                            EN
                                                            <span x-show="!enVal" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" title="{{ __('admin.missing_translation') }}"></span>
                                                        </button>
                                                        <button type="button" @click="activeLang = 'fr'" :class="activeLang === 'fr' ? 'btn-primary shadow-sm' : 'btn-light text-muted border-0'" class="btn btn-sm py-0 px-2 rounded-pill position-relative transition-all" style="font-size: 0.65rem; font-weight: 700;">
                                                            FR
                                                            <span x-show="!frVal" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" title="{{ __('admin.missing_translation') }}"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <div x-show="activeLang === 'ar'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                                    <textarea class="form-control rounded-4 shadow-sm" name="packages[{{ $package->id }}][description]" rows="3" x-model="arVal"></textarea>
                                                </div>
                                                <div x-show="activeLang === 'en'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display: none;">
                                                    <textarea class="form-control rounded-4 shadow-sm text-end" dir="ltr" name="packages[{{ $package->id }}][description_en]" rows="3" x-model="enVal"></textarea>
                                                </div>
                                                <div x-show="activeLang === 'fr'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display: none;">
                                                    <textarea class="form-control rounded-4 shadow-sm text-end" dir="ltr" name="packages[{{ $package->id }}][description_fr]" rows="3" x-model="frVal"></textarea>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6 border-end border-light">
                                                    <label class="form-label small fw-bold text-primary mb-2"><i class="bi bi-link-45deg"></i> {{ __('admin.custom_cta_section') }}</label>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <input type="text" class="form-control form-control-sm rounded-3" name="packages[{{ $package->id }}][custom_cta_text]" value="{{ $package->custom_cta_text }}" placeholder="{{ __('admin.cta_text_placeholder') }}">
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="text" class="form-control form-control-sm rounded-3" name="packages[{{ $package->id }}][custom_cta_link]" value="{{ $package->custom_cta_link }}" placeholder="{{ __('admin.cta_link_placeholder') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold text-primary mb-2"><i class="bi bi-paypal"></i> PayPal & Stripe IDs</label>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <input type="text" class="form-control form-control-sm rounded-3" name="packages[{{ $package->id }}][paypal_plan_id]" value="{{ $package->paypal_plan_id }}" placeholder="PayPal Plan ID">
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="text" class="form-control form-control-sm rounded-3" name="packages[{{ $package->id }}][stripe_price_id]" value="{{ $package->stripe_price_id }}" placeholder="Stripe Price ID">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Features Accordion -->
                                        <div class="mb-4" id="package_acc_{{ $package->id }}" x-data="{ openMarketing: false, openLimits: false }">
                                            <!-- Marketing Features -->
                                            <div class="border rounded-4 mb-2 overflow-hidden bg-light">
                                                <button class="w-100 d-flex align-items-center justify-content-between p-3 border-0 bg-transparent fw-bold text-start" type="button" @click="openMarketing = !openMarketing">
                                                    <span class="text-primary"><i class="bi bi-megaphone me-2"></i> {{ __('admin.marketing_features') }}</span>
                                                    <i class="bi" :class="openMarketing ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                                </button>
                                                <div x-show="openMarketing" class="p-3 pt-0 border-top bg-light" style="display: none;">
                                                    <textarea class="form-control rounded-3 bg-white text-dark mt-2" name="packages[{{ $package->id }}][display_features]" rows="4">{{ is_array($package->display_features) ? implode("\n", $package->display_features) : '' }}</textarea>
                                                    <small class="text-muted d-block mt-1">{{ __('admin.one_feature_per_line') }}</small>
                                                </div>
                                            </div>

                                            <!-- System Limits -->
                                            <div class="border rounded-4 overflow-hidden bg-light">
                                                <button class="w-100 d-flex align-items-center justify-content-between p-3 border-0 bg-transparent fw-bold text-start" type="button" @click="openLimits = !openLimits">
                                                    <span class="text-primary"><i class="bi bi-gear-wide-connected me-2"></i> {{ __('admin.system_limits') }}</span>
                                                    <i class="bi" :class="openLimits ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                                </button>
                                                <div x-show="openLimits" class="p-3 pt-0 border-top bg-light" style="display: none;">
                                                    <div class="row g-2 mt-1">
                                                        @foreach($features as $feature)
                                                        @php
                                                            $packageFeature = $package->features->where('id', $feature->id)->first();
                                                            $value = $packageFeature ? $packageFeature->pivot->value : '';
                                                        @endphp
                                                        <div class="col-6">
                                                            <div class="p-2 bg-white rounded-3 border text-center h-100 shadow-sm">
                                                                <label class="d-block small text-dark fw-bold mb-1" style="font-size: 0.7rem;">
                                                                    {{ app()->getLocale() === 'ar' ? $feature->name : $feature->name_en }}
                                                                </label>
                                                                @if($feature->type === 'limit')
                                                                    <input type="text" class="form-control form-control-sm border bg-light rounded-2 text-center text-dark font-monospace" name="packages[{{ $package->id }}][limits][{{ $feature->id }}]" value="{{ $value }}" placeholder="-1">
                                                                @else
                                                                    <div class="form-check form-switch p-0 m-0 d-flex justify-content-center">
                                                                        <input type="hidden" name="packages[{{ $package->id }}][limits][{{ $feature->id }}]" value="false">
                                                                        <input class="form-check-input premium-switch ms-0" type="checkbox" name="packages[{{ $package->id }}][limits][{{ $feature->id }}]" value="true" {{ $value == 'true' ? 'checked' : '' }}>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3" @if(app()->getLocale() != 'ar') style="display:none;" @endif>
                                            <label class="form-label small fw-bold text-muted mb-1">{{ __('admin.description_ar') }}</label>
                                            <textarea class="form-control rounded-3" name="packages[{{ $package->id }}][description]" rows="2" disabled>{{ $package->description }}</textarea>
                                        </div>

                                        <div class="mb-4" @if(app()->getLocale() != 'en') style="display:none;" @endif>
                                            <label class="form-label small fw-bold text-muted mb-1">{{ __('admin.description_en') }}</label>
                                            <textarea class="form-control rounded-3 text-end" dir="ltr" name="packages[{{ $package->id }}][description_en]" rows="2" disabled>{{ $package->description_en }}</textarea>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center bg-light/50 p-2 rounded-3 border border-dashed">
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" name="packages[{{ $package->id }}][is_default]" id="def_{{ $package->id }}" {{ $package->is_default ? 'checked' : '' }}>
                                                <label class="form-check-label small fw-bold" for="def_{{ $package->id }}">{{ __('admin.is_default') }}</label>
                                            </div>
                                            <button type="button" class="btn btn-link text-danger btn-sm p-0 text-decoration-none" onclick="if(confirm('{{ __('admin.delete_package_confirm') }}')) document.getElementById('delete-package-{{ $package->id }}').submit()">
                                                <i class="bi bi-trash"></i> {{ __('admin.delete_package') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                            <div class="col-12 mt-4">
                                <div class="alert alert-info rounded-4 border-0 shadow-sm d-flex align-items-center gap-3">
                                    <i class="bi bi-info-circle fs-4"></i>
                                    <div>
                                        <strong>{{ __('admin.note') }}:</strong> {{ __('admin.notes_warning') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
