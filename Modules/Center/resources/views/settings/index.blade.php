@extends('center::layouts.hope-master')

@section('title', __('center::settings.title'))

@section('page-title', __('center::settings.title'))

@section('content')
    @php $activeTab = request('tab', 'general'); @endphp
    
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 p-0">
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
                            <button class="nav-link {{ $activeTab == 'email_templates' ? 'active' : '' }} py-3 fw-bold" id="email_templates-tab" data-bs-toggle="tab" data-bs-target="#email_templates" type="button" role="tab" aria-selected="{{ $activeTab == 'email_templates' ? 'true' : 'false' }}">
                                <i class="fas fa-envelope me-2 text-primary"></i> قوالب البريد
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
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted">{{ __('center::settings.general.timezone') }}</label>
                                    <select name="timezone" class="form-select select2">
                                        @foreach(timezone_identifiers_list() as $timezone)
                                            <option value="{{ $timezone }}" {{ $tenant->timezone == $timezone ? 'selected' : '' }}>
                                                {{ $timezone }} ({{ \Carbon\Carbon::now($timezone)->format('h:i A') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">{{ __('center::settings.general.timezone_help') ?? __('center::messages.blade_0709') }}</small>
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

                            <div class="mt-5 mb-3 border-top pt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold text-danger mb-0"><i class="fas fa-clock me-2"></i>{{ __('center::settings.academic.attendance_rules') }}</h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="addLateLevel()">
                                        <i class="fas fa-plus me-1"></i> {{ __('center::settings.academic.add_level') }}
                                    </button>
                                </div>
                                <p class="text-muted small mb-3">{{ __('center::settings.academic.late_levels_help') }}</p>
                                
                                <div id="late-levels-container">
                                    @php 
                                        $hasCustomLevels = isset($tenant->settings['academic']['late_levels']);
                                        $lateLevels = $tenant->settings['academic']['late_levels'] ?? config('academic.late_rules.defaults', []); 
                                    @endphp
                                    
                                    @if(!$hasCustomLevels)
                                        <div class="alert alert-info py-2 px-3 small border-0 mb-3 bg-opacity-10 text-info" id="system-defaults-alert">
                                            <i class="fas fa-info-circle me-2"></i>{{ __('center::messages.blade_0705') }}</div>
                                    @endif

                                    @foreach($lateLevels as $lIndex => $level)
                                        <div class="late-level-item d-flex align-items-center gap-2 mb-2 bg-light p-2 rounded-3">
                                            <input type="number" name="settings[academic][late_levels][{{ $lIndex }}][minutes]" class="form-control form-control-sm" style="width: 100px;" value="{{ $level['minutes'] }}" placeholder="{{ __('center::settings.academic.threshold_minutes') }}" required>
                                            <input type="text" name="settings[academic][late_levels][{{ $lIndex }}][label]" class="form-control form-control-sm" value="{{ $level['label'] }}" placeholder="{{ __('center::settings.academic.level_label') }}" required>
                                            <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeLateLevel(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-start mt-2">
                                    <button type="button" class="btn btn-link btn-sm text-muted p-0" onclick="restoreLateDefaults()">
                                        <i class="fas fa-undo-alt me-1"></i>{{ __('center::messages.blade_0706') }}</button>
                                </div>
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
                                    <input type="color" name="settings[appearance][primary_color]" class="form-control form-control-color w-100" value="{{ $tenant->settings['appearance']['primary_color'] ?? '#10b981' }}">
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


                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> {{ __('center::settings.general.save') }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Email Templates Settings -->
{{-- Tab 3: Email Templates --}}
                        <div class="tab-pane fade {{ $activeTab == 'email_templates' ? 'show active' : '' }}" id="email_templates" role="tabpanel">
                            @php
                                $emailSettings = $tenant->settings['email_templates'] ?? [];
                                $presets = config('email_templates.presets', []);
                                $defaultPresetKey = config('email_templates.default_preset', 'formal');
                                $defaultPreset = $presets[$defaultPresetKey] ?? [];
                            @endphp
                            <form action="{{ route('center.settings.update', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST">
                                @csrf

                                {{-- Header --}}
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-envelope me-2"></i> إعدادات البريد الإلكتروني</h5>
                                </div>
                                <p class="text-muted small mb-4">تحكم في رسائل الترحيب التلقائية التي يتم إرسالها عند تسجيل طالب جديد.</p>

                                {{-- Quick Preset Selector --}}
                                <div class="card border bg-light shadow-none rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-magic me-2 text-warning"></i> اختر قالب جاهز</h6>
                                        <div class="row g-3">
                                            @foreach($presets as $key => $preset)
                                                <div class="col-md-4">
                                                    <div class="bg-white border rounded-3 p-3 h-100 text-center cursor-pointer preset-card" data-preset="{{ $key }}" style="cursor: pointer; transition: all 0.2s;">
                                                        <div class="mb-2">
                                                            <i class="{{ $preset['icon'] ?? 'fas fa-file-alt' }} fa-2x text-primary"></i>
                                                        </div>
                                                        <span class="fw-bold small">{{ $preset['name'] ?? $key }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Student Welcome Email --}}
                                <div class="row g-4">
                                    <div class="col-lg-7">
                                        <div class="card border bg-white shadow-none rounded-4 mb-4">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <h6 class="fw-bold mb-0"><i class="fas fa-user-graduate me-2 text-info"></i> رسالة ترحيب الطالب</h6>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 x-small reset-email-btn" data-subject-id="student_subject" data-body-id="student_body" data-default-subject="{{ $defaultPreset['student_subject'] ?? '' }}" data-default-body="{{ $defaultPreset['student_body'] ?? '' }}">
                                                            <i class="fas fa-undo"></i> للافتراضي
                                                        </button>
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="settings[email_templates][welcome_student_enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="settings[email_templates][welcome_student_enabled]" value="1" id="studentEmailEnabled" {{ ($emailSettings['welcome_student_enabled'] ?? true) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold small ms-2" for="studentEmailEnabled">تفعيل</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                    <input type="text" name="settings[email_templates][welcome_student_subject]" id="student_subject" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="{{ $emailSettings['welcome_student_subject'] ?? $defaultPreset['student_subject'] ?? '' }}" placeholder="مرحباً بك في {اسم_المركز}">
                                                </div>

                                                <div class="mb-2 d-flex flex-wrap gap-1">
                                                    @php
                                                        $vars = [
                                                            'اسم_الطالب' => 'اسم الطالب',
                                                            'اسم_المركز' => 'اسم المركز',
                                                            'رابط_الدخول' => 'رابط الدخول',
                                                            'كلمة_المرور' => 'كلمة المرور',
                                                            'رقم_الهاتف' => 'رقم الهاتف',
                                                        ];
                                                    @endphp
                                                    @foreach($vars as $key => $label)
                                                        <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="student_body" data-var="{{ '{' . $key . '}' }}">
                                                            <i class="fas fa-plus-circle me-1 opacity-50"></i> {{ $label }}
                                                        </button>
                                                    @endforeach
                                                </div>

                                                <div class="mb-0">
                                                    <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                    <textarea name="settings[email_templates][welcome_student_body]" id="student_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="8" placeholder="اكتب رسالة الترحيب هنا...">{{ $emailSettings['welcome_student_body'] ?? $defaultPreset['student_body'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Guardian Welcome Email --}}
                                        <div class="card border bg-white shadow-none rounded-4 mb-4">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <h6 class="fw-bold mb-0"><i class="fas fa-user-shield me-2 text-success"></i> رسالة ترحيب ولي الأمر</h6>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 x-small reset-email-btn" data-subject-id="guardian_subject" data-body-id="guardian_body" data-default-subject="{{ $defaultPreset['guardian_subject'] ?? '' }}" data-default-body="{{ $defaultPreset['guardian_body'] ?? '' }}">
                                                            <i class="fas fa-undo"></i> للافتراضي
                                                        </button>
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="settings[email_templates][welcome_guardian_enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="settings[email_templates][welcome_guardian_enabled]" value="1" id="guardianEmailEnabled" {{ ($emailSettings['welcome_guardian_enabled'] ?? true) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold small ms-2" for="guardianEmailEnabled">تفعيل</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                    <input type="text" name="settings[email_templates][welcome_guardian_subject]" id="guardian_subject" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="{{ $emailSettings['welcome_guardian_subject'] ?? $defaultPreset['guardian_subject'] ?? '' }}" placeholder="تم تسجيل {اسم_الطالب} في {اسم_المركز}">
                                                </div>

                                                <div class="mb-2 d-flex flex-wrap gap-1">
                                                    @php
                                                        $gVars = array_merge($vars, ['اسم_ولي_الأمر' => 'اسم ولي الأمر', 'المرحلة' => 'المرحلة الدراسية']);
                                                    @endphp
                                                    @foreach($gVars as $key => $label)
                                                        <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="guardian_body" data-var="{{ '{' . $key . '}' }}">
                                                            <i class="fas fa-plus-circle me-1 opacity-50"></i> {{ $label }}
                                                        </button>
                                                    @endforeach
                                                </div>

                                                <div class="mb-0">
                                                    <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                    <textarea name="settings[email_templates][welcome_guardian_body]" id="guardian_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="8" placeholder="اكتب رسالة ولي الأمر هنا...">{{ $emailSettings['welcome_guardian_body'] ?? $defaultPreset['guardian_body'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-5">
                                        {{-- Live Preview --}}
                                        <div class="sticky-top" style="top: 2rem; z-index: 5;">
                                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                                <div class="card-header bg-dark py-3 px-4">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="d-flex gap-1">
                                                            <span class="rounded-circle bg-danger" style="width:10px; height:10px;"></span>
                                                            <span class="rounded-circle bg-warning" style="width:10px; height:10px;"></span>
                                                            <span class="rounded-circle bg-success" style="width:10px; height:10px;"></span>
                                                        </div>
                                                        <span class="text-white x-small opacity-50 ms-2">معاينة الرسالة (الآن)</span>
                                                    </div>
                                                </div>
                                                <div class="card-body p-0 bg-white">
                                                    <div class="p-3 border-bottom bg-light">
                                                        <div class="small text-muted mb-1">الموضوع:</div>
                                                        <div id="preview-subject" class="fw-bold">...</div>
                                                    </div>
                                                    <div class="p-4" style="min-height: 400px; font-family: sans-serif; line-height: 1.6;">
                                                        <div id="preview-body" style="white-space: pre-wrap;">...</div>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-light border-0 text-center py-3">
                                                    <span class="text-muted x-small italic"><i class="fas fa-magic me-1 text-primary"></i> تظهر الرموز في المعاينة كبيانات تجريبية للتوضيح فقط</span>
                                                </div>
                                            </div>

                                            <div class="mt-4 p-4 bg-primary-soft rounded-4 border border-primary border-opacity-10">
                                                <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-lightbulb me-2"></i> نصيحة احترافية</h6>
                                                <p class="small text-dark mb-0">استخدم الرموز التلقائية لجعل رسائلك شخصية أكثر. الرسائل التي تبدأ باسم الطالب تحقق تفاعلاً أعلى بنسبة 40%!</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ═══════════════════════════════════════════════ --}}
                                {{-- Section 2: Event-Based Email Notifications    --}}
                                {{-- ═══════════════════════════════════════════════ --}}
                                <div class="mt-5 pt-4 border-top">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-bell me-2"></i> إشعارات البريد التلقائية</h5>
                                    </div>
                                    <p class="text-muted small mb-4">فعّل أو عطّل إرسال بريد إلكتروني تلقائي عند حدوث أحداث معينة. يمكنك تخصيص نص كل رسالة.</p>

                                    {{-- Accordion for each notification type --}}
                                    <div class="accordion" id="emailNotificationsAccordion">

                                        {{-- 1. Payment Reminder --}}
                                        <div class="accordion-item border rounded-4 mb-3 overflow-hidden">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#notif_payment_reminder">
                                                    <span class="d-flex align-items-center gap-3 w-100">
                                                        <span class="rounded-circle bg-warning bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                            <i class="fas fa-clock text-warning"></i>
                                                        </span>
                                                        <span>
                                                            <span class="d-block">تذكير بموعد الدفع</span>
                                                            <small class="text-muted fw-normal">يُرسل للطالب أو ولي الأمر قبل موعد السداد</small>
                                                        </span>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="notif_payment_reminder" class="accordion-collapse collapse" data-bs-parent="#emailNotificationsAccordion">
                                                <div class="accordion-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fw-bold small text-muted">حالة الإشعار</span>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 x-small reset-email-btn" data-subject-id="notif_payment_reminder_subject" data-body-id="notif_payment_reminder_body" data-default-subject="تذكير بسداد مصروفات {اسم_الطالب} - {اسم_المركز}" data-default-body="نذكركم بأن مصروفات الطالب/ة {اسم_الطالب} بمبلغ {المبلغ} مستحقة بتاريخ {تاريخ_الاستحقاق}.\n\nيرجى السداد في الموعد المحدد لضمان استمرار الخدمة.\n\nشكراً لتعاونكم,\n{اسم_المركز}">
                                                                <i class="fas fa-undo"></i> للافتراضي
                                                            </button>
                                                            <div class="form-check form-switch custom-switch mb-0">
                                                                <input type="hidden" name="settings[email_templates][notif_payment_reminder_enabled]" value="0">
                                                                <input class="form-check-input" type="checkbox" name="settings[email_templates][notif_payment_reminder_enabled]" value="1" id="notifPaymentReminder" {{ ($emailSettings['notif_payment_reminder_enabled'] ?? false) ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold small ms-2" for="notifPaymentReminder">مفعّل</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                        <input type="text" id="notif_payment_reminder_subject" name="settings[email_templates][notif_payment_reminder_subject]" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="{{ $emailSettings['notif_payment_reminder_subject'] ?? 'تذكير بسداد مصروفات {اسم_الطالب} - {اسم_المركز}' }}">
                                                    </div>
                                                    <div class="mb-2 d-flex flex-wrap gap-1">
                                                        @php $payVars = ['اسم_الطالب'=>'اسم الطالب','اسم_المركز'=>'اسم المركز','المبلغ'=>'المبلغ المستحق','تاريخ_الاستحقاق'=>'تاريخ الاستحقاق','رابط_الدخول'=>'رابط الدخول']; @endphp
                                                        @foreach($payVars as $k=>$l)
                                                            <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="notif_payment_reminder_body" data-var="{{ '{'.$k.'}' }}"><i class="fas fa-plus-circle me-1 opacity-50"></i> {{ $l }}</button>
                                                        @endforeach
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                        <textarea name="settings[email_templates][notif_payment_reminder_body]" id="notif_payment_reminder_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="5">{{ $emailSettings['notif_payment_reminder_body'] ?? "نذكركم بأن مصروفات الطالب/ة {اسم_الطالب} بمبلغ {المبلغ} مستحقة بتاريخ {تاريخ_الاستحقاق}.\n\nيرجى السداد في الموعد المحدد لضمان استمرار الخدمة.\n\nشكراً لتعاونكم,\n{اسم_المركز}" }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 2. New Group Enrollment --}}
                                        <div class="accordion-item border rounded-4 mb-3 overflow-hidden">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#notif_group_enrollment">
                                                    <span class="d-flex align-items-center gap-3 w-100">
                                                        <span class="rounded-circle bg-info bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                            <i class="fas fa-user-plus text-info"></i>
                                                        </span>
                                                        <span>
                                                            <span class="d-block">الاشتراك في مجموعة جديدة</span>
                                                            <small class="text-muted fw-normal">يُرسل عند إضافة طالب لمجموعة أو كورس جديد</small>
                                                        </span>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="notif_group_enrollment" class="accordion-collapse collapse" data-bs-parent="#emailNotificationsAccordion">
                                                <div class="accordion-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fw-bold small text-muted">حالة الإشعار</span>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 x-small reset-email-btn" data-subject-id="notif_group_enrollment_subject" data-body-id="notif_group_enrollment_body" data-default-subject="تم تسجيلك في مجموعة جديدة - {اسم_المركز}" data-default-body="مرحباً {اسم_الطالب}،\n\nتم تسجيلك في مجموعة جديدة: {اسم_المجموعة}\n\nيمكنك الدخول للمنصة من خلال:\n{رابط_الدخول}\n\nنتمنى لك التوفيق!\n{اسم_المركز}">
                                                                <i class="fas fa-undo"></i> للافتراضي
                                                            </button>
                                                            <div class="form-check form-switch custom-switch mb-0">
                                                                <input type="hidden" name="settings[email_templates][notif_group_enrollment_enabled]" value="0">
                                                                <input class="form-check-input" type="checkbox" name="settings[email_templates][notif_group_enrollment_enabled]" value="1" id="notifGroupEnrollment" {{ ($emailSettings['notif_group_enrollment_enabled'] ?? false) ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold small ms-2" for="notifGroupEnrollment">مفعّل</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                        <input type="text" id="notif_group_enrollment_subject" name="settings[email_templates][notif_group_enrollment_subject]" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="{{ $emailSettings['notif_group_enrollment_subject'] ?? 'تم تسجيلك في مجموعة جديدة - {اسم_المركز}' }}">
                                                    </div>
                                                    <div class="mb-2 d-flex flex-wrap gap-1">
                                                        @php $grpVars = ['اسم_الطالب'=>'اسم الطالب','اسم_المركز'=>'اسم المركز','اسم_المجموعة'=>'اسم المجموعة','سعر_الدورة'=>'سعر الدورة','رابط_الدخول'=>'رابط الدخول']; @endphp
                                                        @foreach($grpVars as $k=>$l)
                                                            <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="notif_group_enrollment_body" data-var="{{ '{'.$k.'}' }}"><i class="fas fa-plus-circle me-1 opacity-50"></i> {{ $l }}</button>
                                                        @endforeach
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                        <textarea name="settings[email_templates][notif_group_enrollment_body]" id="notif_group_enrollment_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="5">{{ $emailSettings['notif_group_enrollment_body'] ?? "مرحباً {اسم_الطالب}،\n\nتم تسجيلك في مجموعة جديدة: {اسم_المجموعة}\n\nيمكنك الدخول للمنصة من خلال:\n{رابط_الدخول}\n\nنتمنى لك التوفيق!\n{اسم_المركز}" }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 3. Payment Confirmation --}}
                                        <div class="accordion-item border rounded-4 mb-3 overflow-hidden">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#notif_payment_confirmed">
                                                    <span class="d-flex align-items-center gap-3 w-100">
                                                        <span class="rounded-circle bg-success bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                            <i class="fas fa-check-circle text-success"></i>
                                                        </span>
                                                        <span>
                                                            <span class="d-block">تأكيد استلام مبلغ</span>
                                                            <small class="text-muted fw-normal">يُرسل عند تسجيل دفعة مالية جديدة للطالب</small>
                                                        </span>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="notif_payment_confirmed" class="accordion-collapse collapse" data-bs-parent="#emailNotificationsAccordion">
                                                <div class="accordion-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fw-bold small text-muted">حالة الإشعار</span>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 x-small reset-email-btn" data-subject-id="notif_payment_confirmed_subject" data-body-id="notif_payment_confirmed_body" data-default-subject="تأكيد استلام دفعة - {اسم_المركز}" data-default-body="مرحباً {اسم_الطالب}،\n\nنؤكد استلام دفعة مالية بالتفاصيل التالية:\n• المبلغ: {المبلغ_المدفوع}\n• التاريخ: {تاريخ_الدفع}\n• طريقة الدفع: {طريقة_الدفع}\n• المتبقي: {المتبقي}\n\nشكراً لالتزامكم.\n{اسم_المركز}">
                                                                <i class="fas fa-undo"></i> للافتراضي
                                                            </button>
                                                            <div class="form-check form-switch custom-switch mb-0">
                                                                <input type="hidden" name="settings[email_templates][notif_payment_confirmed_enabled]" value="0">
                                                                <input class="form-check-input" type="checkbox" name="settings[email_templates][notif_payment_confirmed_enabled]" value="1" id="notifPaymentConfirmed" {{ ($emailSettings['notif_payment_confirmed_enabled'] ?? false) ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold small ms-2" for="notifPaymentConfirmed">مفعّل</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                        <input type="text" id="notif_payment_confirmed_subject" name="settings[email_templates][notif_payment_confirmed_subject]" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="{{ $emailSettings['notif_payment_confirmed_subject'] ?? 'تأكيد استلام دفعة - {اسم_المركز}' }}">
                                                    </div>
                                                    <div class="mb-2 d-flex flex-wrap gap-1">
                                                        @php $confVars = ['اسم_الطالب'=>'اسم الطالب','اسم_المركز'=>'اسم المركز','المبلغ_المدفوع'=>'المبلغ المدفوع','تاريخ_الدفع'=>'تاريخ الدفع','المتبقي'=>'المبلغ المتبقي','طريقة_الدفع'=>'طريقة الدفع']; @endphp
                                                        @foreach($confVars as $k=>$l)
                                                            <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="notif_payment_confirmed_body" data-var="{{ '{'.$k.'}' }}"><i class="fas fa-plus-circle me-1 opacity-50"></i> {{ $l }}</button>
                                                        @endforeach
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                        <textarea name="settings[email_templates][notif_payment_confirmed_body]" id="notif_payment_confirmed_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="5">{{ $emailSettings['notif_payment_confirmed_body'] ?? "مرحباً {اسم_الطالب}،\n\nنؤكد استلام دفعة مالية بالتفاصيل التالية:\n• المبلغ: {المبلغ_المدفوع}\n• التاريخ: {تاريخ_الدفع}\n• طريقة الدفع: {طريقة_الدفع}\n• المتبقي: {المتبقي}\n\nشكراً لالتزامكم.\n{اسم_المركز}" }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="text-start mt-4 pt-3 border-top d-flex gap-2">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i> حفظ إعدادات البريد
                                    </button>
                                </div>
                            </form>
                            
                            <form action="{{ route('center.settings.reset-email-templates', ['tenant' => $tenant->domain ?? 'center']) }}" method="POST" class="d-inline-block mt-3" onsubmit="return confirm('هل أنت متأكد أنك تريد مسح جميع التعديلات وإعادة النصوص للوضع الافتراضي؟');">
                                @csrf
                                <button type="submit" class="btn btn-light text-danger rounded-pill px-4 fw-bold shadow-sm border">
                                    <i class="fas fa-undo me-2"></i> إعادة الضبط للافتراضي
                                </button>
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

<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .nav-tabs .nav-link {
        color: #64748b;
        background: #f8fafc;
        border-bottom: 2px solid transparent !important;
        transition: all 0.3s;
    }
    .nav-tabs .nav-link:hover {
        background: #f1f5f9;
        color: var(--primary-color);
    }
    .nav-tabs .nav-link.active {
        background: white !important;
        color: var(--primary-color) !important;
        border-bottom: 3px solid var(--primary-color) !important;
    }
    .form-control:focus {
        background: white !important;
        box-shadow: 0 0 0 4px rgba(58, 12, 163, 0.1);
    }
    .custom-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
    }
    .custom-switch .form-check-input:checked {
        background-color: #22c55e;
        border-color: #22c55e;
    }
    .x-small { font-size: 0.75rem; }
    .preset-card:hover {
        border-color: var(--primary-color, #3A0CA3) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(58, 12, 163, 0.15);
    }
    .preset-card.active-preset {
        border-color: var(--primary-color, #3A0CA3) !important;
        border-width: 2px;
        background: #f0f0ff !important;
    }
    .bg-primary-soft { background-color: rgba(58, 12, 163, 0.05) !important; }
    .border-dashed { border-style: dashed !important; }
    .italic { font-style: italic; }
    .var-btn:hover {
        background-color: var(--primary-color) !important;
        color: white !important;
        border-color: var(--primary-color) !important;
    }
    #preview-body span.text-primary {
        background: rgba(58, 12, 163, 0.1);
        padding: 0 4px;
        border-radius: 4px;
    }
    .accordion-button:not(.collapsed) {
        background-color: #f8f9ff !important;
        color: var(--primary-color) !important;
        box-shadow: none;
    }
    .accordion-button:focus { box-shadow: none; }
    .accordion-item { border-color: #e2e8f0 !important; }
</style>

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

    function removeLateLevel(btn) {
        btn.closest('.late-level-item').remove();
        // Show defaults alert if empty (optional enhancement)
        const container = document.getElementById('late-levels-container');
        if (container.querySelectorAll('.late-level-item').length === 0) {
            // We could show a message or just leave it empty
        }
    }

    const systemLateDefaults = @json(config('academic.late_rules.defaults', []));

    function restoreLateDefaults() {
        if (!confirm('{{ __('center::messages.blade_0708') }}')) {
            return;
        }

        const container = document.getElementById('late-levels-container');
        container.innerHTML = '';

        // Remove the system-defaults-alert if it exists
        const alert = document.getElementById('system-defaults-alert');
        if (alert) alert.remove();

        systemLateDefaults.forEach((level, index) => {
            const html = `
                <div class="late-level-item d-flex align-items-center gap-2 mb-2 bg-light p-2 rounded-3">
                    <input type="number" name="settings[academic][late_levels][${index}][minutes]" class="form-control form-control-sm" style="width: 100px;" value="${level.minutes}" required>
                    <input type="text" name="settings[academic][late_levels][${index}][label]" class="form-control form-control-sm" value="${level.label}" required>
                    <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeLateLevel(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });
    }

    function addLateLevel() {
        const container = document.getElementById('late-levels-container');
        const index = container.querySelectorAll('.late-level-item').length;
        
        // Remove the system-defaults-alert if it exists (first customization)
        const alert = document.getElementById('system-defaults-alert');
        if (alert) alert.remove();

        const html = `
            <div class="late-level-item d-flex align-items-center gap-2 mb-2 bg-light p-2 rounded-3">
                <input type="number" name="settings[academic][late_levels][${index}][minutes]" class="form-control form-control-sm" style="width: 100px;" placeholder="{{ __('center::settings.academic.threshold_minutes') }}" required>
                <input type="text" name="settings[academic][late_levels][${index}][label]" class="form-control form-control-sm" placeholder="{{ __('center::settings.academic.level_label') }}" required>
                <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeLateLevel(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    // Tab Activation Fix
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab) {
            const tabEl = document.querySelector(`#${tab}-tab`);
            if (tabEl) {
                // Ensure Bootstrap is available
                const bsTab = new bootstrap.Tab(tabEl);
                bsTab.show();
            }
        }
    });

    // Email Template Presets
    const emailPresets = @json(config('email_templates.presets', []));

    function applyEmailPreset() {
        const selector = document.getElementById('emailPresetSelector');
        const key = selector.value;
        if (!key || !emailPresets[key]) {
            alert('يرجى اختيار قالب أولاً.');
            return;
        }
        if (!confirm('سيتم استبدال المحتوى الحالي بالقالب المختار. هل أنت متأكد؟')) {
            return;
        }
        const preset = emailPresets[key];
        document.getElementById('studentSubject').value = preset.student_subject || '';
        document.getElementById('studentBody').value = preset.student_body || '';
        document.getElementById('guardianSubject').value = preset.guardian_subject || '';
        document.getElementById('guardianBody').value = preset.guardian_body || '';

        // Flash success
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 start-50 translate-middle-x mb-5 bg-success text-white p-3 rounded-4 shadow animate__animated animate__fadeInUp fw-bold';
        toast.style.zIndex = '9999';
        toast.innerHTML = '<i class="fas fa-check-circle me-2"></i> تم تطبيق القالب: ' + preset.name;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const previewSubject = document.getElementById('preview-subject');
    const previewBody = document.getElementById('preview-body');
    const templateInputs = document.querySelectorAll('.template-input');
    const varBtns = document.querySelectorAll('.var-btn');
    const presetCards = document.querySelectorAll('.preset-card');
    
    const sampleData = {
        '{اسم_الطالب}': 'أحمد محمد علي',
        '{اسم_المركز}': 'أكاديمية التعليم',
        '{رابط_الدخول}': 'https://taalimu.com/login',
        '{كلمة_المرور}': '123456',
        '{رقم_الهاتف}': '01012345678',
        '{اسم_ولي_الأمر}': 'أستاذ محمد علي',
        '{المرحلة}': 'الصف الأول الثانوي'
    };

    function updatePreview() {
        const activeField = document.activeElement;
        let isGuardian = activeField && activeField.id.includes('guardian');
        
        let subject = document.getElementById(isGuardian ? 'guardian_subject' : 'student_subject').value;
        let body = document.getElementById(isGuardian ? 'guardian_body' : 'student_body').value;

        Object.keys(sampleData).forEach(key => {
            const regex = new RegExp(key.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g');
            subject = subject.replace(regex, `<span class="text-primary">${sampleData[key]}</span>`);
            body = body.replace(regex, `<span class="text-primary">${sampleData[key]}</span>`);
        });

        previewSubject.innerHTML = subject || '<span class="text-muted italic">بدون عنوان...</span>';
        previewBody.innerHTML = body || '<span class="text-muted italic">اكتب نص الرسالة لتظهر المعاينة هنا...</span>';
    }

    templateInputs.forEach(input => {
        input.addEventListener('input', updatePreview);
        input.addEventListener('focus', updatePreview);
    });

    varBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const variable = this.dataset.var;
            const textarea = document.getElementById(targetId);
            
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            
            textarea.value = text.substring(0, start) + variable + text.substring(end);
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = start + variable.length;
            
            updatePreview();
        });
    });

    const presets = @json(config('email_templates.presets', []));
    
    presetCards.forEach(card => {
        card.addEventListener('click', function() {
            const presetKey = this.dataset.preset;
            const preset = presets[presetKey];
            
            if (preset) {
                document.getElementById('student_subject').value = preset.student_subject;
                document.getElementById('student_body').value = preset.student_body;
                document.getElementById('guardian_subject').value = preset.guardian_subject;
                document.getElementById('guardian_body').value = preset.guardian_body;
                
                presetCards.forEach(c => c.classList.remove('active-preset'));
                this.classList.add('active-preset');
                
                updatePreview();
            }
        });
    });

    updatePreview();
});
</script>
@endpush
