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
                                                        <option value="{{ $key }}" {{ ($tenant->settings['education_system'] ?? '') == $key ? 'selected' : '' }}>{{ __($template['name']) }}</option>
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
                                        <input class="form-check-input" type="checkbox" name="settings[academic][attendance_alert]" value="1" id="attendanceAlert" {{ ($tenant->settings['academic']['attendance_alert'] ?? true) ? 'checked' : '' }}>
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
                                            
                                            <!-- Collapse trigger chevron -->
                                            <button type="button" class="btn btn-sm btn-link text-muted p-0 me-1 btn-collapse-chevron collapsed" data-bs-toggle="collapse" data-bs-target="#stage-collapse-{{ $sIndex }}" aria-expanded="false" aria-controls="stage-collapse-{{ $sIndex }}" style="text-decoration: none;">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>

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
                                        <div class="collapse" id="stage-collapse-{{ $sIndex }}">
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
                                            <i class="fas fa-info-circle me-2"></i>{{ __('center::settings.academic_system_defaults_alert') }}</div>
                                    @endif

                                    @foreach($lateLevels as $lIndex => $level)
                                        <div class="late-level-item d-flex align-items-center gap-2 mb-2 bg-light p-2 rounded-3">
                                            <input type="number" name="settings[academic][late_levels][{{ $lIndex }}][minutes]" class="form-control form-control-sm" style="width: 100px;" value="{{ $level['minutes'] }}" placeholder="{{ __('center::settings.academic.threshold_minutes') }}" required>
                                            <input type="text" name="settings[academic][late_levels][{{ $lIndex }}][label]" class="form-control form-control-sm" value="{{ __($level['label']) }}" placeholder="{{ __('center::settings.academic.level_label') }}" required>
                                            <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeLateLevel(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-start mt-2">
                                    <button type="button" class="btn btn-link btn-sm text-muted p-0" onclick="restoreLateDefaults()">
                                        <i class="fas fa-undo-alt me-1"></i>{{ __('center::settings.academic_restore_defaults') }}</button>
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
