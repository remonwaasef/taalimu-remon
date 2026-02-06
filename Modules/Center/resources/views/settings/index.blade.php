@extends('center::layouts.master')

@section('title', __('center::settings.title'))

@section('page-title', __('center::settings.title'))

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 p-0">
                    @php $activeTab = request('tab', 'general'); @endphp
                    <ul class="nav nav-tabs nav-fill" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab == 'general' ? 'active' : '' }} py-3 fw-bold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-selected="{{ $activeTab == 'general' ? 'true' : 'false' }}">
                                <i class="fas fa-info-circle me-2"></i> {{ __('center::settings.tabs.general') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab == 'academic' ? 'active' : '' }} py-3 fw-bold" id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic" type="button" role="tab" aria-selected="{{ $activeTab == 'academic' ? 'true' : 'false' }}">
                                <i class="fas fa-graduation-cap me-2"></i> {{ __('center::settings.tabs.academic') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab == 'financial' ? 'active' : '' }} py-3 fw-bold" id="financial-tab" data-bs-toggle="tab" data-bs-target="#financial" type="button" role="tab" aria-selected="{{ $activeTab == 'financial' ? 'true' : 'false' }}">
                                <i class="fas fa-coins me-2"></i> {{ __('center::settings.tabs.financial') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab == 'appearance' ? 'active' : '' }} py-3 fw-bold" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab" aria-selected="{{ $activeTab == 'appearance' ? 'true' : 'false' }}">
                                <i class="fas fa-paint-brush me-2"></i> {{ __('center::settings.tabs.appearance') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab == 'whatsapp' ? 'active' : '' }} py-3 fw-bold" id="whatsapp-tab" data-bs-toggle="tab" data-bs-target="#whatsapp" type="button" role="tab" aria-selected="{{ $activeTab == 'whatsapp' ? 'true' : 'false' }}">
                                <i class="fab fa-whatsapp me-2 text-success"></i> {{ __('center::settings.tabs.whatsapp') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab == 'privacy' ? 'active' : '' }} py-3 fw-bold" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#privacy" type="button" role="tab" aria-selected="{{ $activeTab == 'privacy' ? 'true' : 'false' }}">
                                <i class="fas fa-user-shield me-2 text-danger"></i> {{ __('center::settings.tabs.privacy') }}
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4 fw-bold">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4 fw-bold">
                            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                            <ul class="mb-0 small fw-bold">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="tab-content" id="settingsTabsContent">
                        <!-- General Settings -->
                        <div class="tab-pane fade {{ $activeTab == 'general' ? 'show active' : '' }}" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <form action="{{ route('center.settings.update', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                <!-- Logo -->
                                <div class="col-md-6 text-center border-end">
                                    <div class="position-relative d-inline-block">
                                        <div class="avatar-xl rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold display-4 shadow-sm overflow-hidden" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                            @if($tenant->logo)
                                                <img src="{{ asset('storage/' . $tenant->logo) }}" class="w-100 h-100 object-fit-contain p-2">
                                            @else
                                                {{ substr($tenant->name, 0, 1) }}
                                            @endif
                                        </div>
                                        <label for="logo" class="position-absolute bottom-0 end-0 bg-white shadow-sm p-2 rounded-circle cursor-pointer border">
                                            <i class="fas fa-camera text-muted small"></i>
                                        </label>
                                        <input type="file" id="logo" name="logo" class="d-none" accept="image/*">
                                    </div>
                                    <p class="text-muted small mt-2 mb-0 fw-bold">{{ __('center::settings.general.logo') }}</p>
                                </div>
                                <!-- Favicon -->
                                <div class="col-md-6 text-center">
                                    <div class="position-relative d-inline-block">
                                        <div class="avatar-lg rounded bg-light d-flex align-items-center justify-content-center text-primary shadow-sm overflow-hidden" style="width: 60px; height: 60px; margin-top: 20px;">
                                            @if($tenant->favicon)
                                                <img src="{{ asset('storage/' . $tenant->favicon) }}" class="w-100 h-100 object-fit-contain p-2">
                                            @else
                                                <i class="fas fa-globe fs-2"></i>
                                            @endif
                                        </div>
                                        <label for="favicon" class="position-absolute bottom-0 end-0 bg-white shadow-sm p-2 rounded-circle cursor-pointer border">
                                            <i class="fas fa-camera text-muted x-small"></i>
                                        </label>
                                        <input type="file" id="favicon" name="favicon" class="d-none" accept="image/*">
                                    </div>
                                    <p class="text-muted small mt-2 mb-0 fw-bold">{{ __('center::settings.general.favicon') }}</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted">{{ __('center::settings.general.name') }}</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $tenant->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">{{ __('center::settings.general.phone') }}</label>
                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $tenant->phone) }}" placeholder="01xxxxxxxxx">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">{{ __('center::settings.general.email') }}</label>
                                    <input type="email" class="form-control bg-light" value="{{ $tenant->email ?? ($tenant->users->first()?->email ?? 'N/A') }}" disabled>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted">{{ __('center::settings.general.address') }}</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $tenant->address) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted">{{ __('center::settings.general.description') }}</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $tenant->description) }}</textarea>
                                </div>

                                <!-- Social Media Links -->
                                <div class="col-12 mt-4">
                                    <h6 class="fw-bold text-primary mb-3"><i class="fas fa-share-alt me-2"></i>{{ __('center::settings.general.social_links') }}</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-facebook text-primary"></i></span>
                                                <input type="url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $tenant->facebook_url) }}" placeholder="{{ __('center::settings.general.facebook') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-instagram text-danger"></i></span>
                                                <input type="url" name="instagram_url" class="form-control" value="{{ old('instagram_url', $tenant->instagram_url) }}" placeholder="{{ __('center::settings.general.instagram') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-twitter text-info"></i></span>
                                                <input type="url" name="twitter_url" class="form-control" value="{{ old('twitter_url', $tenant->twitter_url) }}" placeholder="{{ __('center::settings.general.twitter') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-youtube text-danger"></i></span>
                                                <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $tenant->youtube_url) }}" placeholder="{{ __('center::settings.general.youtube') }}">
                                            </div>
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

                        <!-- Academic Settings -->
                        <div class="tab-pane fade {{ $activeTab == 'academic' ? 'show active' : '' }}" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                            
                            <!-- 1. Templates Section (STANDALONE FORM) -->
                            <div class="card border-0 bg-primary bg-opacity-10 mb-4 rounded-4">
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <h6 class="fw-bold text-primary mb-1"><i class="fas fa-magic me-2"></i>{{ __('center::settings.academic.templates_title') }}</h6>
                                            <p class="text-muted small mb-0">{{ __('center::settings.academic.templates_desc') }}</p>
                                        </div>
                                        <div class="col-md-5">
                                            <form action="{{ route('center.settings.apply-template', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST" id="applyTemplateForm" class="d-flex gap-2">
                                                @csrf
                                                <select name="template_key" class="form-select form-select-sm rounded-pill" required>
                                                    <option value="">{{ __('center::settings.academic.select_template') }}</option>
                                                    @foreach($templates as $key => $template)
                                                        <option value="{{ $key }}">{{ __($template['name']) }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 text-nowrap" onclick="confirmTemplate()">
                                                    {{ __('center::settings.academic.apply') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Main Academic Settings Form -->
                            <form action="{{ route('center.settings.update-academic', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST" id="academicStructureForm">
                                @csrf
                                <h6 class="fw-bold text-primary mb-3">{{ __('center::settings.academic.year_grading') }}</h6>
                                <div class="row g-3 pb-4 border-bottom mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">{{ __('center::settings.academic.current_year') }}</label>
                                    <select name="settings[academic][year]" class="form-select">
                                        <option value="2024-2025" {{ ($tenant->settings['academic']['year'] ?? '') == '2024-2025' ? 'selected' : '' }}>2024-2025</option>
                                        <option value="2025-2026" {{ ($tenant->settings['academic']['year'] ?? '') == '2025-2026' ? 'selected' : '' }}>2025-2026</option>
                                        <option value="2026-2027" {{ ($tenant->settings['academic']['year'] ?? '') == '2026-2027' ? 'selected' : '' }}>2026-2027</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">{{ __('center::settings.academic.grading_system') }}</label>
                                    <select name="settings[academic][grading]" class="form-select">
                                        <option value="100" {{ ($tenant->settings['academic']['grading'] ?? '') == '100' ? 'selected' : '' }}>{{ __('center::settings.academic.percentage') }}</option>
                                        <option value="GPA" {{ ($tenant->settings['academic']['grading'] ?? '') == 'GPA' ? 'selected' : '' }}>{{ __('center::settings.academic.gpa') }}</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch mt-3">
                                        <input type="hidden" name="settings[academic][attendance_alert]" value="0">
                                        <input class="form-check-input" type="checkbox" name="settings[academic][attendance_alert]" value="1" id="attendanceAlert" {{ ($tenant->settings['academic']['attendance_alert'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label user-select-none" for="attendanceAlert">{{ __('center::settings.academic.attendance_alert') }}</label>
                                    </div>
                                </div>
                            </div>


                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-primary mb-0"><i class="fas fa-layer-group me-2"></i>{{ __('center::settings.academic.structure_title') }}</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addStage()">
                                    <i class="fas fa-plus me-1"></i> {{ __('center::settings.academic.add_stage') }}
                                </button>
                            </div>

                            <div id="stages-container">
                                    @foreach($stages as $sIndex => $stage)
                                        <div class="stage-card card border bg-light mb-3 rounded-3 overflow-hidden shadow-none" data-index="{{ $sIndex }}">
                                            <div class="card-header bg-white d-flex align-items-center gap-3 py-2 border-bottom">
                                                <input type="hidden" name="stages[{{ $sIndex }}][id]" value="{{ $stage->id }}">
                                                <input type="text" name="stages[{{ $sIndex }}][name]" class="form-control form-control-sm fw-bold border-0 bg-light" value="{{ $stage->name }}" placeholder="{{ __('center::settings.academic.stage_name_placeholder') }}">
                                                <div class="ms-auto d-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-light text-primary" onclick="addGrade({{ $sIndex }})" title="{{ __('center::settings.academic.add_grade') }}">
                                                        <i class="fas fa-plus-circle"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeStage(this, {{ $stage->id }})" title="{{ __('center::settings.academic.remove_stage') }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body p-3">
                                                <div class="grades-container d-flex flex-wrap gap-2">
                                                    @foreach($stage->grades as $gIndex => $grade)
                                                        <div class="grade-item d-flex align-items-center bg-white border rounded-pill px-3 py-1 shadow-sm">
                                                            <input type="hidden" name="stages[{{ $sIndex }}][grades][{{ $gIndex }}][id]" value="{{ $grade->id }}">
                                                            <input type="text" name="stages[{{ $sIndex }}][grades][{{ $gIndex }}][name]" class="form-control form-control-sm border-0 p-0 text-center" style="width: 100px; font-size: 0.85rem;" value="{{ $grade->name }}" placeholder="{{ __('center::settings.academic.grade_name_placeholder') }}">
                                                            <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" onclick="removeGrade(this, {{ $grade->id }})">
                                                                <i class="fas fa-times-circle"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div id="deletion-inputs"></div>

                                <div class="mt-4 text-center">
                                    <button type="submit" form="academicStructureForm" class="btn btn-primary px-5 rounded-pill shadow-sm">
                                        <i class="fas fa-save me-2"></i> {{ __('center::settings.academic.save_structure') }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Financial Settings -->
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

                        <!-- Appearance Settings -->
                        <div class="tab-pane fade {{ $activeTab == 'appearance' ? 'show active' : '' }}" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
                            <form action="{{ route('center.settings.update', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST">
                                @csrf
                                <h6 class="fw-bold text-primary mb-3">{{ __('center::settings.appearance.title') }}</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">{{ __('center::settings.appearance.primary_color') }}</label>
                                    <input type="color" name="settings[appearance][primary_color]" class="form-control form-control-color w-100" value="{{ $tenant->settings['appearance']['primary_color'] ?? '#140342' }}">
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch mt-3">
                                        <input type="hidden" name="settings[appearance][dark_mode]" value="0">
                                        <input class="form-check-input" type="checkbox" name="settings[appearance][dark_mode]" value="1" id="darkMode" {{ ($tenant->settings['appearance']['dark_mode'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label user-select-none" for="darkMode">{{ __('center::settings.appearance.dark_mode') }}</label>
                                    </div>
                                </div>
                                </div>
                                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> {{ __('center::settings.general.save') }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- WhatsApp Settings -->
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
                                                <label class="form-label fw-bold small text-muted">{{ __('center::settings.whatsapp.instance_id') }}</label>
                                                <input type="text" name="settings[whatsapp][instance_id]" class="form-control" value="{{ $tenant->settings['whatsapp']['instance_id'] ?? '' }}" placeholder="مثل: instance12345">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">{{ __('center::settings.whatsapp.token') }}</label>
                                                <input type="text" name="settings[whatsapp][token]" class="form-control" value="{{ $tenant->settings['whatsapp']['token'] ?? '' }}" placeholder="رمز الوصول الخاص بك">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info border-0 rounded-4">
                                    <h6 class="fw-bold"><i class="fas fa-lightbulb me-2"></i>{{ __('center::settings.whatsapp.info_title') }}</h6>
                                    <ul class="small mb-0 mt-2">
                                        <li><strong>{{ __('center::settings.whatsapp.attendance_msg') }}</strong></li>
                                        <li><strong>{{ __('center::settings.whatsapp.payment_msg') }}</strong></li>
                                    </ul>
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> {{ __('center::settings.general.save') }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Privacy & GDPR Settings -->
                        <div class="tab-pane fade {{ $activeTab == 'privacy' ? 'show active' : '' }}" id="privacy" role="tabpanel" aria-labelledby="privacy-tab">
                            <div class="alert alert-warning border-0 rounded-4 mb-4">
                                <h6 class="fw-bold"><i class="fas fa-shield-alt me-2"></i>{{ __('center::settings.privacy.title') }}</h6>
                                <p class="small mb-0 mt-1">
                                    {{ __('center::settings.privacy.desc') }}
                                </p>
                            </div>

                            <!-- Data Export -->
                            <div class="card border bg-light shadow-none mb-4">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="fw-bold mb-1">{{ __('center::settings.privacy.export_title') }}</h6>
                                            <p class="text-muted small mb-0">{{ __('center::settings.privacy.export_desc') }}</p>
                                        </div>
                                        <a href="{{ route('gdpr.export') }}" class="btn btn-outline-primary rounded-pill px-4">
                                            <i class="fas fa-download me-2"></i> {{ __('center::settings.privacy.export_btn') }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Account -->
                            <div class="card border border-danger bg-danger bg-opacity-10 shadow-none">
                                <div class="card-body p-4">
                                    <h6 class="fw-bold text-danger mb-2">{{ __('center::settings.privacy.delete_title') }}</h6>
                                    <p class="text-secondary small mb-3">
                                        {{ __('center::settings.privacy.delete_desc') }}
                                    </p>
                                    
                                    <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                        <i class="fas fa-trash-alt me-2"></i> {{ __('center::settings.privacy.delete_btn') }}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal moved to root for stability -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-danger">{{ __('center::settings.privacy.confirm_delete_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('gdpr.delete') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3 text-muted">{{ __('center::settings.privacy.confirm_delete_desc') }}</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">{{ __('center::settings.privacy.current_password') }}</label>
                        <input type="password" name="password" class="form-control bg-light border-0" required placeholder="********">
                    </div>

                    <div class="form-check custom-check p-0">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="confirm_delete" id="confirmDelete" required>
                        <label class="form-check-label small user-select-none text-danger fw-bold" for="confirmDelete">
                            {{ __('center::settings.privacy.understand_checkbox') }}
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('center::settings.privacy.cancel') }}</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">{{ __('center::settings.privacy.delete_perm') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let stageCount = {{ count($stages) }};

    function addStage() {
        const container = document.getElementById('stages-container');
        const stageHtml = `
            <div class="stage-card card border bg-light mb-3 rounded-3 overflow-hidden shadow-none" data-index="${stageCount}">
                <div class="card-header bg-white d-flex align-items-center gap-3 py-2 border-bottom">
                    <input type="text" name="stages[${stageCount}][name]" class="form-control form-control-sm fw-bold border-0 bg-light" placeholder="{{ __('center::settings.academic.stage_name_placeholder') }}">
                    <div class="ms-auto d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-light text-primary" onclick="addGrade(${stageCount})" title="{{ __('center::settings.academic.add_grade') }}">
                            <i class="fas fa-plus-circle"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeStage(this)" title="{{ __('center::settings.academic.remove_stage') }}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="grades-container d-flex flex-wrap gap-2">
                        <!-- Grades will be added here -->
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', stageHtml);
        stageCount++;
    }

    function addGrade(stageIndex) {
        const stageCard = document.querySelector(`.stage-card[data-index="${stageIndex}"]`);
        const container = stageCard.querySelector('.grades-container');
        const gradeIndex = container.children.length;
        const gradeHtml = `
            <div class="grade-item d-flex align-items-center bg-white border rounded-pill px-3 py-1 shadow-sm">
                <input type="text" name="stages[${stageIndex}][grades][${gradeIndex}][name]" class="form-control form-control-sm border-0 p-0 text-center" style="width: 100px; font-size: 0.85rem;" placeholder="{{ __('center::settings.academic.grade_name_placeholder') }}">
                <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" onclick="removeGrade(this)">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', gradeHtml);
    }

    function removeStage(btn, id = null) {
        if (confirm("{{ __('center::settings.academic.confirm_delete_stage') }}")) {
            if (id) {
                const deletionInputs = document.getElementById('deletion-inputs');
                deletionInputs.insertAdjacentHTML('beforeend', `<input type="hidden" name="deleted_stages[]" value="${id}">`);
            }
            btn.closest('.stage-card').remove();
        }
    }

    function confirmTemplate() {
        const select = document.querySelector('select[name="template_key"]');
        const form = document.getElementById('applyTemplateForm');
        
        if (!select || select.value === "") {
            alert("{{ __('center::settings.academic.select_template_first') }}");
            return;
        }
        
        console.log('Applying template:', select.value);
        
        if (confirm("{{ __('center::settings.academic.confirm_template') }}")) {
            if (form) {
                form.submit();
            } else {
                console.error('Form applyTemplateForm not found!');
                alert("{{ __('center::settings.academic.tech_error') }}");
            }
        }
    }

    function removeGrade(btn, id = null) {
        if (id) {
            const deletionInputs = document.getElementById('deletion-inputs');
            deletionInputs.insertAdjacentHTML('beforeend', `<input type="hidden" name="deleted_grades[]" value="${id}">`);
        }
        btn.closest('.grade-item').remove();
    }
</script>
@endpush
