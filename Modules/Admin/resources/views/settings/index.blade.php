@extends('admin::layouts.master')

@section('title', __('admin::admin.title'))
@section('page-title', __('admin::admin.title'))

@section('content')
<div class="container-fluid">
    <div class="premium-card">
        <div class="card-body p-0">
            <!-- Settings Tabs Navigation -->
            <div class="border-bottom px-4 pt-4">
                <ul class="nav nav-tabs border-0" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active border-0 px-4 py-3 position-relative" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-selected="true">
                            <i class="bi bi-gear me-2"></i> {{ __('admin::admin.general_settings') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 px-4 py-3 position-relative" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-palette me-2"></i> {{ __('admin::admin.appearance') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 px-4 py-3 position-relative" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-shield-lock me-2"></i> {{ __('admin::admin.security') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 px-4 py-3 position-relative" id="plans-tab" data-bs-toggle="tab" data-bs-target="#plans" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-card-checklist me-2"></i> {{ __('admin::admin.plans_pricing') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 px-4 py-3 position-relative" id="features-tab" data-bs-toggle="tab" data-bs-target="#system-features" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-list-check me-2"></i> {{ __('admin::admin.system_features_tab') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 px-4 py-3 position-relative" id="coupons-tab" data-bs-toggle="tab" data-bs-target="#coupons" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-ticket-perforated me-2"></i> {{ __('admin::admin.coupons_discounts') }}
                        </button>
                    </li>
                </ul>
                <div class="d-flex gap-2">
                     <button type="button" class="btn btn-outline-primary rounded-pill btn-sm px-3" data-bs-toggle="modal" data-bs-target="#addPackageModal">
                        <i class="bi bi-plus-lg me-1"></i> {{ __('admin::admin.new_package') }}
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill btn-sm px-4" form="mainSettingsForm">
                        <i class="bi bi-check2-circle me-1"></i> {{ __('admin::admin.save_all') }}
                    </button>
                </div>
            </div>

            <!-- Settings Content -->
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="mainSettingsForm">
                @csrf
                <div class="tab-content p-4" id="settingsTabsContent">
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin::admin.site_name') }}</label>
                                <input type="text" class="form-control rounded-4 shadow-sm border-light" name="site_name" value="{{ \App\Models\SiteSetting::get('site_name', 'EduCentral') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin::admin.admin_email') }}</label>
                                <input type="email" class="form-control rounded-4 shadow-sm border-light" name="admin_email" value="{{ \App\Models\SiteSetting::get('admin_email', 'admin@educentral.com') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('admin::admin.site_description') }} (العربية)</label>
                                <textarea class="form-control rounded-4 shadow-sm border-light mb-3" name="site_description_ar" rows="2" dir="rtl">{{ \App\Models\SiteSetting::get('site_description_ar', \App\Models\SiteSetting::get('site_description', __('landing.hero.subtitle', [], 'ar'))) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('admin::admin.site_description') }} (English)</label>
                                <textarea class="form-control rounded-4 shadow-sm border-light mb-3" name="site_description_en" rows="2" dir="ltr">{{ \App\Models\SiteSetting::get('site_description_en', __('landing.hero.subtitle', [], 'en')) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('admin::admin.site_description') }} (Français)</label>
                                <textarea class="form-control rounded-4 shadow-sm border-light" name="site_description_fr" rows="2" dir="ltr">{{ \App\Models\SiteSetting::get('site_description_fr', __('landing.hero.subtitle', [], 'fr')) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">مدة باقة الترم (بالأيام)</label>
                                <input type="number" class="form-control rounded-4 shadow-sm border-light" name="term_duration_days" value="{{ \App\Models\SiteSetting::get('term_duration_days', 150) }}">
                                <small class="text-muted">الافتراضي: 150 يوم</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('admin::admin.currency_symbol') }}</label>
                                <input type="text" class="form-control rounded-4 shadow-sm border-light" name="currency_symbol" value="{{ \App\Models\SiteSetting::get('currency_symbol', 'جنيه') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('admin::admin.currency_code') }}</label>
                                <input type="text" class="form-control rounded-4 shadow-sm border-light" name="currency_code" value="{{ \App\Models\SiteSetting::get('currency_code', 'EGP') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Appearance Settings -->
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

                    <!-- Security Settings -->
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

                    <!-- Plans Settings -->
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
                                                        <div class="col-6">
                                                            <label class="small text-muted mb-0">{{ __('admin.package_name_ar') }}</label>
                                                            <input type="text" class="form-control form-control-sm" name="packages[{{ $package->id }}][name]" value="{{ $package->name }}">
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="small text-muted mb-0">{{ __('admin.package_name_en') }}</label>
                                                            <input type="text" class="form-control form-control-sm text-end" dir="ltr" name="packages[{{ $package->id }}][name_en]" value="{{ $package->name_en }}">
                                                        </div>
                                                    </div>
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
                                            
                                            <div class="accordion accordion-flush" id="regionalAccordion{{ $package->id }}">
                                                @php $regional = $package->regional_prices ?? []; @endphp
                                                
                                                <!-- Region: Egypt -->
                                                <div class="accordion-item bg-white border rounded-3 mb-2">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#reg_eg_{{ $package->id }}">
                                                            🇪🇬 Egypt (EGP)
                                                        </button>
                                                    </h2>
                                                    <div id="reg_eg_{{ $package->id }}" class="accordion-collapse collapse" data-bs-parent="#regionalAccordion{{ $package->id }}">
                                                        <div class="accordion-body p-3">
                                                            <div class="row g-2">
                                                                <input type="hidden" name="packages[{{ $package->id }}][regional_prices][EG][currency]" value="EGP">
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">{{ __('admin::admin.price_monthly') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][EG][amount]" value="{{ $regional['EG']['amount'] ?? '' }}">
                                                                </div>
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">سعر الترم</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][EG][term_price]" value="{{ $regional['EG']['term_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">{{ __('admin.price_yearly') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][EG][yearly_price]" value="{{ $regional['EG']['yearly_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted mb-0">{{ __('admin.old_price') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][EG][old_price]" value="{{ $regional['EG']['old_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted mb-0">{{ __('admin.discount_label') }}</label>
                                                                    <input type="text" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][EG][discount_label]" value="{{ $regional['EG']['discount_label'] ?? '' }}" placeholder="وفر 20%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Region: Saudi -->
                                                <div class="accordion-item bg-white border rounded-3 mb-2">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#reg_sa_{{ $package->id }}">
                                                            🇸🇦 Saudi Arabia (SAR)
                                                        </button>
                                                    </h2>
                                                    <div id="reg_sa_{{ $package->id }}" class="accordion-collapse collapse" data-bs-parent="#regionalAccordion{{ $package->id }}">
                                                        <div class="accordion-body p-3">
                                                            <div class="row g-2">
                                                                <input type="hidden" name="packages[{{ $package->id }}][regional_prices][SA][currency]" value="SAR">
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">{{ __('admin.price_monthly') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][SA][amount]" value="{{ $regional['SA']['amount'] ?? '' }}">
                                                                </div>
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">سعر الترم</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][SA][term_price]" value="{{ $regional['SA']['term_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">{{ __('admin.price_yearly') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][SA][yearly_price]" value="{{ $regional['SA']['yearly_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted mb-0">{{ __('admin.old_price') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][SA][old_price]" value="{{ $regional['SA']['old_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted mb-0">{{ __('admin.discount_label') }}</label>
                                                                    <input type="text" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][SA][discount_label]" value="{{ $regional['SA']['discount_label'] ?? '' }}" placeholder="Save 20%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Region: UAE -->
                                                <div class="accordion-item bg-white border rounded-3 mb-2">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#reg_ae_{{ $package->id }}">
                                                            🇦🇪 UAE (AED)
                                                        </button>
                                                    </h2>
                                                    <div id="reg_ae_{{ $package->id }}" class="accordion-collapse collapse" data-bs-parent="#regionalAccordion{{ $package->id }}">
                                                        <div class="accordion-body p-3">
                                                            <div class="row g-2">
                                                                <input type="hidden" name="packages[{{ $package->id }}][regional_prices][AE][currency]" value="AED">
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">{{ __('admin.price_monthly') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][AE][amount]" value="{{ $regional['AE']['amount'] ?? '' }}">
                                                                </div>
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">سعر الترم</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][AE][term_price]" value="{{ $regional['AE']['term_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">{{ __('admin.price_yearly') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][AE][yearly_price]" value="{{ $regional['AE']['yearly_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted mb-0">{{ __('admin.old_price') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][AE][old_price]" value="{{ $regional['AE']['old_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted mb-0">{{ __('admin.discount_label') }}</label>
                                                                    <input type="text" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][AE][discount_label]" value="{{ $regional['AE']['discount_label'] ?? '' }}" placeholder="Save 20%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Region: Europe -->
                                                <div class="accordion-item bg-white border rounded-3 mb-2">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#reg_eu_{{ $package->id }}">
                                                            🇪🇺 Europe (EUR)
                                                        </button>
                                                    </h2>
                                                    <div id="reg_eu_{{ $package->id }}" class="accordion-collapse collapse" data-bs-parent="#regionalAccordion{{ $package->id }}">
                                                        <div class="accordion-body p-3">
                                                            <div class="row g-2">
                                                                <input type="hidden" name="packages[{{ $package->id }}][regional_prices][FR][currency]" value="EUR">
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">{{ __('admin.price_monthly') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][FR][amount]" value="{{ $regional['FR']['amount'] ?? '' }}">
                                                                </div>
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">سعر الترم</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][FR][term_price]" value="{{ $regional['FR']['term_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">{{ __('admin.price_yearly') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][FR][yearly_price]" value="{{ $regional['FR']['yearly_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted mb-0">{{ __('admin.old_price') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][FR][old_price]" value="{{ $regional['FR']['old_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted mb-0">{{ __('admin.discount_label') }}</label>
                                                                    <input type="text" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][FR][discount_label]" value="{{ $regional['FR']['discount_label'] ?? '' }}" placeholder="OFF 20%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Region: United States -->
                                                <div class="accordion-item bg-white border rounded-3 mb-2">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#reg_us_{{ $package->id }}">
                                                            🇺🇸 United States (USD)
                                                        </button>
                                                    </h2>
                                                    <div id="reg_us_{{ $package->id }}" class="accordion-collapse collapse" data-bs-parent="#regionalAccordion{{ $package->id }}">
                                                        <div class="accordion-body p-3">
                                                            <div class="row g-2">
                                                                <input type="hidden" name="packages[{{ $package->id }}][regional_prices][US][currency]" value="USD">
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">{{ __('admin.price_monthly') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][US][amount]" value="{{ $regional['US']['amount'] ?? '' }}">
                                                                </div>
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">سعر الترم</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][US][term_price]" value="{{ $regional['US']['term_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-4">
                                                                    <label class="small text-muted mb-0">{{ __('admin.price_yearly') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][US][yearly_price]" value="{{ $regional['US']['yearly_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted mb-0">{{ __('admin.old_price') }}</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][US][old_price]" value="{{ $regional['US']['old_price'] ?? '' }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="small text-muted mb-0">{{ __('admin.discount_label') }}</label>
                                                                    <input type="text" class="form-control form-control-sm" name="packages[{{ $package->id }}][regional_prices][US][discount_label]" value="{{ $regional['US']['discount_label'] ?? '' }}" placeholder="Save 20%">
                                                                </div>
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
                                            <div class="mb-4 mt-3">
                                                <label class="form-label small fw-bold text-primary mb-2"><i class="bi bi-card-text"></i> Description (AR)</label>
                                                <textarea class="form-control rounded-4 shadow-sm" name="packages[{{ $package->id }}][description]" rows="3">{{ $package->description }}</textarea>

                                                <label class="form-label small fw-bold text-primary mb-2 mt-3"><i class="bi bi-card-text"></i> Description (EN)</label>
                                                <textarea class="form-control rounded-4 shadow-sm" name="packages[{{ $package->id }}][description_en]" rows="2">{{ $package->description_en }}</textarea>

                                                <label class="form-label small fw-bold text-primary mb-2 mt-3"><i class="bi bi-card-text"></i> Description (FR)</label>
                                                <textarea class="form-control rounded-4 shadow-sm" name="packages[{{ $package->id }}][description_fr]" rows="2">{{ $package->description_fr }}</textarea>
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
                                        <div class="accordion premium-accordion mb-4" id="package_acc_{{ $package->id }}">
                                            <!-- Marketing Features -->
                                            <div class="accordion-item border-0 mb-2 rounded-4 overflow-hidden">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed py-2 rounded-4 bg-light fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_marketing_{{ $package->id }}">
                                                        <i class="bi bi-megaphone me-2 text-primary"></i> {{ __('admin.marketing_features') }}
                                                    </button>
                                                </h2>
                                                <div id="collapse_marketing_{{ $package->id }}" class="accordion-collapse collapse" data-bs-parent="#package_acc_{{ $package->id }}">
                                                    <div class="accordion-body bg-light pt-0">
                                                        <textarea class="form-control rounded-3 bg-white" name="packages[{{ $package->id }}][display_features]" rows="4">{{ is_array($package->display_features) ? implode("\n", $package->display_features) : '' }}</textarea>
                                                        <small class="text-muted">{{ __('admin.one_feature_per_line') }}</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- System Limits -->
                                            <div class="accordion-item border-0 rounded-4 overflow-hidden">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed py-2 rounded-4 bg-light fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_limits_{{ $package->id }}">
                                                        <i class="bi bi-gear-wide-connected me-2 text-primary"></i> {{ __('admin.system_limits') }}
                                                    </button>
                                                </h2>
                                                <div id="collapse_limits_{{ $package->id }}" class="accordion-collapse collapse" data-bs-parent="#package_acc_{{ $package->id }}">
                                                    <div class="accordion-body bg-light pt-0">
                                                        <div class="row g-2 mt-1">
                                                            @foreach($features as $feature)
                                                            @php
                                                                $packageFeature = $package->features->where('id', $feature->id)->first();
                                                                $value = $packageFeature ? $packageFeature->pivot->value : '';
                                                            @endphp
                                                            <div class="col-6">
                                                                <div class="p-2 bg-white rounded-3 border border-light text-center h-100 shadow-sm">
                                                                    <label class="d-block small text-muted mb-1" style="font-size: 0.6rem;">{{ __('features.' . $feature->code) }}</label>
                                                                    @if($feature->type === 'limit')
                                                                        <input type="text" class="form-control form-control-sm border-0 bg-light rounded-2 text-center" name="packages[{{ $package->id }}][limits][{{ $feature->id }}]" value="{{ $value }}" placeholder="-1">
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
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-muted mb-1">{{ __('admin.description_ar') }}</label>
                                            <textarea class="form-control rounded-3" name="packages[{{ $package->id }}][description]" rows="2">{{ $package->description }}</textarea>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label small fw-bold text-muted mb-1">{{ __('admin.description_en') }}</label>
                                            <textarea class="form-control rounded-3 text-end" dir="ltr" name="packages[{{ $package->id }}][description_en]" rows="2">{{ $package->description_en }}</textarea>
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

                    <!-- System Features Tab -->
                    <div class="tab-pane fade" id="system-features" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1">{{ __('admin.system_features_management') }}</h5>
                                <p class="text-muted small mb-0">{{ __('admin.system_features_note') }}</p>
                            </div>
                            <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addFeatureModal">
                                <i class="bi bi-plus-lg me-1"></i> {{ __('admin.new_feature') }}
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="rounded-start-3">{{ __('admin.feature_name') }}</th>
                                        <th>{{ __('admin.feature_code') }}</th>
                                        <th>{{ __('admin.type') }}</th>
                                        <th>{{ __('admin.category') }}</th>
                                        <th>{{ __('admin.sort_order') }}</th>
                                        <th class="rounded-end-3 text-center">{{ __('admin.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($features as $feature)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $feature->name }}</div>
                                            <div class="small text-muted" dir="ltr">{{ $feature->name_en }}</div>
                                        </td>
                                        <td><code class="bg-light px-2 py-1 rounded">{{ $feature->code }}</code></td>
                                        <td>
                                            <span class="badge {{ $feature->type == 'limit' ? 'bg-info-subtle text-info' : 'bg-success-subtle text-success' }} rounded-pill">
                                                {{ $feature->type == 'limit' ? 'رقمي' : 'نعم/لا' }}
                                            </span>
                                        </td>
                                        <td>{{ $feature->category }}</td>
                                        <td>{{ $feature->sort_order }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editFeatureModal{{ $feature->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" onclick="if(confirm('حذف هذه الميزة سيؤدي لحذف قيمها من جميع الباقات. هل أنت متأكد؟')) document.getElementById('delete-feature-{{ $feature->id }}').submit()">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Coupons Tab -->
                    <div class="tab-pane fade" id="coupons" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1">{{ __('admin.coupons_management') }}</h5>
                                <p class="text-muted small mb-0">{{ __('admin.coupons_note') }}</p>
                            </div>
                            <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addCouponModal">
                                <i class="bi bi-plus-lg me-2"></i> {{ __('admin.new_coupon') }}
                            </button>
                        </div>

                        <!-- Coupons Table -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="rounded-start-3">{{ __('admin.coupon_code') }}</th>
                                        <th>{{ __('admin.coupon_name') }}</th>
                                        <th>{{ __('admin.discount_value') }}</th>
                                        <th>{{ __('admin.target_plan') }}</th>
                                        <th>{{ __('admin.valid_until') }}</th>
                                        <th>{{ __('admin.max_uses') }}</th>
                                        <th>{{ __('admin.active_status') }}</th>
                                        <th class="rounded-end-3 text-center">{{ __('admin.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($coupons ?? [] as $coupon)
                                    <tr>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded-2 fw-bold">{{ $coupon->code }}</code>
                                        </td>
                                        <td>{{ $coupon->name ?? '-' }}</td>
                                        <td>
                                            @if($coupon->type == 'percentage')
                                                <span class="badge bg-success-subtle text-success rounded-pill">{{ $coupon->value }}%</span>
                                            @else
                                                <span class="badge bg-primary-subtle text-primary rounded-pill">{{ number_format($coupon->value, 0) }} ر.س</span>
                                            @endif
                                        </td>
                                        <td>{{ $coupon->package ? $coupon->package->name : __('admin.all_plans') }}</td>
                                        <td>
                                            @if($coupon->expires_at)
                                                @if($coupon->expires_at->isPast())
                                                    <span class="text-danger small"><i class="bi bi-x-circle me-1"></i>{{ __('admin.expired') }}</span>
                                                @else
                                                    <span class="text-muted small">{{ __('admin.valid_until') }} {{ $coupon->expires_at->format('Y-m-d') }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted small">{{ __('admin.unlimited_uses') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark rounded-pill">
                                                {{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($coupon->is_active && $coupon->isValid())
                                                <span class="badge bg-success rounded-pill">{{ __('admin.active_status') }}</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill">{{ __('admin.inactive_status') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary rounded-start-pill" data-bs-toggle="modal" data-bs-target="#editCouponModal{{ $coupon->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger rounded-end-pill" onclick="if(confirm('{{ __('admin.delete_confirm') }}')) document.getElementById('deleteCoupon{{ $coupon->id }}').submit()">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-ticket-perforated fs-1 mb-3 d-block opacity-50"></i>
                                                <p class="mb-0">{{ __('admin.no_coupons') }}</p>
                                                <small>{{ __('admin.no_coupons_hint') }}</small>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="card-footer bg-white border-top p-4 d-flex justify-content-end gap-3 rounded-bottom-4">
                    <button type="reset" class="btn btn-light rounded-pill px-4">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">{{ __('admin.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($coupons ?? [] as $coupon)
    <!-- Delete Coupon Form (Hidden) -->
    <form id="deleteCoupon{{ $coupon->id }}" action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    <!-- Edit Coupon Modal -->
    <div class="modal fade" id="editCouponModal{{ $coupon->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil me-2 text-primary"></i> تعديل الكوبون
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.coupon_code') }}</label>
                                <input type="text" class="form-control rounded-3" name="code" value="{{ $coupon->code }}" style="text-transform: uppercase;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.coupon_name') }}</label>
                                <input type="text" class="form-control rounded-3" name="name" value="{{ $coupon->name }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('admin.discount_type') }}</label>
                                <select class="form-select rounded-3" name="type">
                                    <option value="percentage" {{ $coupon->type == 'percentage' ? 'selected' : '' }}>نسبة مئوية (%)</option>
                                    <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>مبلغ ثابت (ر.س)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('admin.discount_value') }}</label>
                                <input type="number" step="0.01" class="form-control rounded-3" name="value" value="{{ $coupon->value }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('admin.target_plan') }}</label>
                                <select class="form-select rounded-3" name="package_id">
                                    <option value="">{{ __('admin.all_plans') }}</option>
                                    @foreach($packages as $pkg)
                                        <option value="{{ $pkg->id }}" {{ $coupon->package_id == $pkg->id ? 'selected' : '' }}>{{ $pkg->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.start_date') }}</label>
                                <input type="date" class="form-control rounded-3" name="starts_at" value="{{ $coupon->starts_at?->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.end_date') }}</label>
                                <input type="date" class="form-control rounded-3" name="expires_at" value="{{ $coupon->expires_at?->format('Y-m-d') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin.max_uses') }}</label>
                                <input type="number" class="form-control rounded-3" name="max_uses" value="{{ $coupon->max_uses }}">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check form-switch p-0 m-0">
                                    <input class="form-check-input premium-switch ms-0" type="checkbox" name="is_active" id="editCouponActive{{ $coupon->id }}" {{ $coupon->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold toggle-label ms-1" for="editCouponActive{{ $coupon->id }}">{{ __('admin.coupon_active') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-check-lg me-1"></i> {{ __('admin.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Add Coupon Modal -->
<div class="modal fade" id="addCouponModal" tabindex="-1" aria-labelledby="addCouponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addCouponModalLabel">
                    <i class="bi bi-ticket-perforated me-2 text-primary"></i> {{ __('admin.new_coupon') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.coupon_code') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control rounded-3" name="code" placeholder="مثال: WELCOME20" style="text-transform: uppercase;">
                                <button type="button" class="btn btn-outline-secondary rounded-end-3" onclick="this.previousElementSibling.value = 'PROMO' + Math.random().toString(36).substring(2, 8).toUpperCase()">
                                    <i class="bi bi-dice-5"></i> {{ __('admin.generate') }}
                                </button>
                            </div>
                            <small class="text-muted">{{ __('admin.coupon_code_hint') }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.coupon_name') }}</label>
                            <input type="text" class="form-control rounded-3" name="name" placeholder="مثال: خصم الترحيب">
                            <small class="text-muted">{{ __('admin.coupon_name_hint') }}</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('admin.discount_type') }}</label>
                            <select class="form-select rounded-3" name="type">
                                <option value="percentage">{{ __('admin.percentage') }}</option>
                                <option value="fixed">{{ __('admin.fixed_amount') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('admin.discount_value') }}</label>
                            <input type="number" step="0.01" class="form-control rounded-3" name="value" placeholder="20">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('admin.target_plan') }}</label>
                            <select class="form-select rounded-3" name="package_id">
                                <option value="">{{ __('admin.all_plans') }}</option>
                                @foreach($packages as $pkg)
                                    <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.start_date') }}</label>
                            <input type="date" class="form-control rounded-3" name="starts_at">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.end_date') }}</label>
                            <input type="date" class="form-control rounded-3" name="expires_at">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('admin.max_uses') }}</label>
                            <input type="number" class="form-control rounded-3" name="max_uses" placeholder="{{ __('admin.max_uses_hint') }}">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input premium-switch ms-0" type="checkbox" name="is_active" id="couponActiveSwitch" checked>
                                <label class="form-check-label fw-bold toggle-label ms-1" for="couponActiveSwitch">{{ __('admin.coupon_active') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-check-lg me-1"></i> {{ __('admin.new_coupon') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($packages as $package)
    <form id="delete-package-{{ $package->id }}" action="{{ route('admin.settings.packages.destroy', $package->id) }}" method="POST" class="d-none">
        @csrf @method('DELETE')
    </form>
@endforeach

@foreach($features as $feature)
    <form id="delete-feature-{{ $feature->id }}" action="{{ route('admin.settings.features.destroy', $feature->id) }}" method="POST" class="d-none">
        @csrf @method('DELETE')
    </form>
@endforeach

<!-- Add Package Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header">
                <h5 class="fw-bold">{{ __('admin.new_package') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.settings.packages.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">الاسم (AR)</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name (EN)</label>
                        <input type="text" class="form-control" name="name_en">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.slug') }}</label>
                        <input type="text" class="form-control" name="slug" placeholder="{{ __('admin.slug_placeholder') }}">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">{{ __('admin.price_monthly') }}</label>
                            <input type="number" step="0.01" class="form-control" name="price">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">{{ __('admin.duration_days') }}</label>
                            <input type="number" class="form-control" name="duration_in_days" value="30">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('admin.create') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Feature Modal -->
<div class="modal fade" id="addFeatureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header">
                <h5 class="fw-bold">{{ __('admin.new_feature') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.settings.features.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.feature_name') }} (AR)</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.feature_name') }} (EN)</label>
                        <input type="text" class="form-control" name="name_en">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.feature_code') }}</label>
                        <input type="text" class="form-control" name="code" placeholder="max_students">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.type') }}</label>
                        <select class="form-select" name="type">
                            <option value="limit">{{ __('admin.limit_type') }}</option>
                            <option value="boolean">{{ __('admin.boolean_type') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.category') }}</label>
                        <select class="form-select" name="category">
                            <option value="core">{{ __('admin.category_core') }}</option>
                            <option value="smart">{{ __('admin.category_smart') }}</option>
                            <option value="analysis">{{ __('admin.category_analysis') }}</option>
                            <option value="academic">{{ __('admin.category_academic') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('admin.add') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($features as $f)
<!-- Edit Feature Modal -->
<div class="modal fade" id="editFeatureModal{{ $f->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header">
                <h5 class="fw-bold">{{ __('admin.edit_role') }}: {{ $f->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.settings.features.update', $f->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.feature_name') }} (AR)</label>
                        <input type="text" class="form-control" name="name" value="{{ $f->name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.feature_name') }} (EN)</label>
                        <input type="text" class="form-control" name="name_en" value="{{ $f->name_en }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.sort_order') }}</label>
                        <input type="number" class="form-control" name="sort_order" value="{{ $f->sort_order }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.category') }}</label>
                        <select class="form-select" name="category">
                            <option value="core" @if($f->category == 'core') selected @endif>{{ __('admin.category_core') }}</option>
                            <option value="smart" @if($f->category == 'smart') selected @endif>{{ __('admin.category_smart') }}</option>
                            <option value="analysis" @if($f->category == 'analysis') selected @endif>{{ __('admin.category_analysis') }}</option>
                            <option value="academic" @if($f->category == 'academic') selected @endif>{{ __('admin.category_academic') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('admin.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<style>
    /* Custom Tab Styling for Settings */
    #settingsTabs .nav-link {
        color: var(--text-muted);
        font-weight: 600;
        transition: all 0.3s ease;
    }

    #settingsTabs .nav-link.active {
        color: var(--primary-purple) !important;
        background: transparent !important;
    }

    #settingsTabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-primary);
        border-radius: 3px 3px 0 0;
    }

    .premium-card {
        border-radius: 20px;
        border: none;
        box-shadow: var(--shadow-md);
        background: white;
        overflow: hidden;
    }

    .form-control:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.1);
    }

    .form-switch .form-check-input:checked {
        background-color: var(--primary-purple);
        border-color: var(--primary-purple);
    }

    /* Premium Plans Styling */
    .premium-plan-card {
        border-radius: 20px;
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .premium-plan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }

    .plan-accent-bar {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 8px;
        background: var(--gradient-primary);
    }

    .plan-pro .plan-accent-bar { background: linear-gradient(90deg, #3A0CA3, #4361EE); }
    .plan-basic .plan-accent-bar { background: linear-gradient(90deg, #4361EE, #5BE7C4); }
    .plan-free .plan-accent-bar { background: linear-gradient(90deg, #cbd5e1, #94a3b8); }

    .plan-pro .plan-name { color: #3A0CA3; }
    .plan-basic .plan-name { color: #4361EE; }
    
    .fw-black { font-weight: 900; }

    .premium-accordion .accordion-button {
        box-shadow: none !important;
        border-radius: 12px !important;
        transition: all 0.2s ease;
    }

    .premium-accordion .accordion-button:not(.collapsed) {
        background: rgba(42, 77, 255, 0.05) !important;
        color: var(--primary-purple) !important;
    }

    .premium-accordion .accordion-button::after {
        background-size: 1rem;
    }

    .premium-accordion .accordion-item {
        background: transparent;
    }

    .font-monospace {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace !important;
    }

    /* Premium Switches */
    .premium-switch {
        cursor: pointer;
        width: 3.2em !important;
        height: 1.6em !important;
        background-color: #e2e8f0;
        border-color: #cbd5e1;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    }

    .premium-switch:checked {
        background-color: #10b981 !important; /* Success Green */
        border-color: #059669 !important;
        background-position: right center;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.4) !important;
    }

    .premium-switch:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.1) !important;
    }

    .toggle-label {
        transition: color 0.3s ease;
        color: #94a3b8;
    }

    .premium-switch:checked + .toggle-label {
        color: #10b981 !important;
        font-weight: 700 !important;
    }

    [dir="rtl"] .premium-switch:checked {
        background-position: left center;
    }

    /* RTL Switches Fix */
    [dir="rtl"] .form-switch .premium-switch {
        margin-right: -2.5em;
        margin-left: 0;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    if (tab) {
        let tabEl;
        if (tab === 'coupons') {
            tabEl = document.querySelector('#coupons-tab');
        } else if (tab === 'features') {
            tabEl = document.querySelector('#features-tab');
        } else if (tab === 'plans') {
            tabEl = document.querySelector('#plans-tab');
        }
        
        if (tabEl) {
            const bootstrapTab = new bootstrap.Tab(tabEl);
            bootstrapTab.show();
        }
    }

    @if(isset($errors) && $errors->any())
        var addCouponModal = new bootstrap.Modal(document.getElementById('addCouponModal'));
        addCouponModal.show();
        
        // Also switch to coupon tab
        var couponTab = new bootstrap.Tab(document.querySelector('#coupons-tab'));
        couponTab.show();
    @endif
});
</script>
@endsection
