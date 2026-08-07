                        <div class="tab-pane fade <?php echo e($activeTab == 'academic' ? 'show active' : ''); ?>" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                            
                            <!-- 1. Templates Section (STANDALONE FORM) -->
                            <div class="card border-0 bg-primary bg-opacity-10 mb-4 rounded-4">
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <h6 class="fw-bold text-primary mb-1"><i class="fas fa-magic me-2"></i><?php echo e(__('center::settings.academic.templates_title')); ?></h6>
                                            <p class="text-muted small mb-0"><?php echo e(__('center::settings.academic.templates_desc')); ?></p>
                                        </div>
                                        <div class="col-md-5">
                                            <form action="<?php echo e(route('center.settings.apply-template', ['tenant' => $tenant->domain ?? 'center'])); ?>" method="POST" id="applyTemplateForm" class="d-flex gap-2">
                                                <?php echo csrf_field(); ?>
                                                <select name="template_key" class="form-select form-select-sm rounded-pill" required>
                                                    <option value=""><?php echo e(__('center::settings.academic.select_template')); ?></option>
                                                    <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key); ?>" <?php echo e(($tenant->settings['education_system'] ?? '') == $key ? 'selected' : ''); ?>><?php echo e(__($template['name'])); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 text-nowrap" onclick="confirmTemplate()">
                                                    <?php echo e(__('center::settings.academic.apply')); ?>

                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Main Academic Settings Form -->
                            <form action="<?php echo e(route('center.settings.update-academic', ['tenant' => $tenant->domain ?? 'center'])); ?>" method="POST" id="academicStructureForm">
                                <?php echo csrf_field(); ?>
                                <h6 class="fw-bold text-primary mb-3"><?php echo e(__('center::settings.academic.year_grading')); ?></h6>
                                <div class="row g-3 pb-4 border-bottom mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.academic.current_year')); ?></label>
                                    <select name="settings[academic][year]" class="form-select">
                                        <option value="2024-2025" <?php echo e(($tenant->settings['academic']['year'] ?? '') == '2024-2025' ? 'selected' : ''); ?>>2024-2025</option>
                                        <option value="2025-2026" <?php echo e(($tenant->settings['academic']['year'] ?? '') == '2025-2026' ? 'selected' : ''); ?>>2025-2026</option>
                                        <option value="2026-2027" <?php echo e(($tenant->settings['academic']['year'] ?? '') == '2026-2027' ? 'selected' : ''); ?>>2026-2027</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.academic.grading_system')); ?></label>
                                    <select name="settings[academic][grading]" class="form-select">
                                        <option value="100" <?php echo e(($tenant->settings['academic']['grading'] ?? '') == '100' ? 'selected' : ''); ?>><?php echo e(__('center::settings.academic.percentage')); ?></option>
                                        <option value="GPA" <?php echo e(($tenant->settings['academic']['grading'] ?? '') == 'GPA' ? 'selected' : ''); ?>><?php echo e(__('center::settings.academic.gpa')); ?></option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch mt-3">
                                        <input type="hidden" name="settings[academic][attendance_alert]" value="0">
                                        <input class="form-check-input" type="checkbox" name="settings[academic][attendance_alert]" value="1" id="attendanceAlert" <?php echo e(($tenant->settings['academic']['attendance_alert'] ?? true) ? 'checked' : ''); ?>>
                                        <label class="form-check-label user-select-none" for="attendanceAlert"><?php echo e(__('center::settings.academic.attendance_alert')); ?></label>
                                    </div>
                                </div>
                            </div>


                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-primary mb-0"><i class="fas fa-layer-group me-2"></i><?php echo e(__('center::settings.academic.structure_title')); ?></h6>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addStage()">
                                    <i class="fas fa-plus me-1"></i> <?php echo e(__('center::settings.academic.add_stage')); ?>

                                </button>
                            </div>

                            <div id="stages-container">
                                <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sIndex => $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="stage-card card border bg-light mb-3 rounded-3 overflow-hidden shadow-none" data-index="<?php echo e($sIndex); ?>">
                                        <div class="card-header bg-white d-flex align-items-center gap-3 py-2 border-bottom">
                                            <input type="hidden" name="stages[<?php echo e($sIndex); ?>][id]" value="<?php echo e($stage->id); ?>">
                                            
                                            <!-- Collapse trigger chevron -->
                                            <button type="button" class="btn btn-sm btn-link text-muted p-0 me-1 btn-collapse-chevron collapsed" data-bs-toggle="collapse" data-bs-target="#stage-collapse-<?php echo e($sIndex); ?>" aria-expanded="false" aria-controls="stage-collapse-<?php echo e($sIndex); ?>" style="text-decoration: none;">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>

                                            <input type="text" name="stages[<?php echo e($sIndex); ?>][name]" class="form-control form-control-sm fw-bold border-0 bg-light" value="<?php echo e($stage->name); ?>" placeholder="<?php echo e(__('center::settings.academic.stage_name_placeholder')); ?>">
                                            <div class="ms-auto d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-light text-primary" onclick="addGrade(<?php echo e($sIndex); ?>)" title="<?php echo e(__('center::settings.academic.add_grade')); ?>">
                                                    <i class="fas fa-plus-circle"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeStage(this, <?php echo e($stage->id); ?>)" title="<?php echo e(__('center::settings.academic.remove_stage')); ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="collapse" id="stage-collapse-<?php echo e($sIndex); ?>">
                                            <div class="card-body p-3">
                                                <div class="grades-container d-flex flex-wrap gap-2">
                                                    <?php $__currentLoopData = $stage->grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gIndex => $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="grade-item d-flex align-items-center bg-white border rounded-pill px-3 py-1 shadow-sm">
                                                            <input type="hidden" name="stages[<?php echo e($sIndex); ?>][grades][<?php echo e($gIndex); ?>][id]" value="<?php echo e($grade->id); ?>">
                                                            <input type="text" name="stages[<?php echo e($sIndex); ?>][grades][<?php echo e($gIndex); ?>][name]" class="form-control form-control-sm border-0 p-0 text-center" style="width: 100px; font-size: 0.85rem;" value="<?php echo e($grade->name); ?>" placeholder="<?php echo e(__('center::settings.academic.grade_name_placeholder')); ?>">
                                                            <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" onclick="removeGrade(this, <?php echo e($grade->id); ?>)">
                                                                <i class="fas fa-times-circle"></i>
                                                            </button>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="mt-5 mb-3 border-top pt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold text-danger mb-0"><i class="fas fa-clock me-2"></i><?php echo e(__('center::settings.academic.attendance_rules')); ?></h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="addLateLevel()">
                                        <i class="fas fa-plus me-1"></i> <?php echo e(__('center::settings.academic.add_level')); ?>

                                    </button>
                                </div>
                                <p class="text-muted small mb-3"><?php echo e(__('center::settings.academic.late_levels_help')); ?></p>
                                
                                <div id="late-levels-container">
                                    <?php 
                                        $hasCustomLevels = isset($tenant->settings['academic']['late_levels']);
                                        $lateLevels = $tenant->settings['academic']['late_levels'] ?? config('academic.late_rules.defaults', []); 
                                    ?>
                                    
                                    <?php if(!$hasCustomLevels): ?>
                                        <div class="alert alert-info py-2 px-3 small border-0 mb-3 bg-opacity-10 text-info" id="system-defaults-alert">
                                            <i class="fas fa-info-circle me-2"></i><?php echo e(__('center::settings.academic_system_defaults_alert')); ?></div>
                                    <?php endif; ?>

                                    <?php $__currentLoopData = $lateLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lIndex => $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="late-level-item d-flex align-items-center gap-2 mb-2 bg-light p-2 rounded-3">
                                            <input type="number" name="settings[academic][late_levels][<?php echo e($lIndex); ?>][minutes]" class="form-control form-control-sm" style="width: 100px;" value="<?php echo e($level['minutes']); ?>" placeholder="<?php echo e(__('center::settings.academic.threshold_minutes')); ?>" required>
                                            <input type="text" name="settings[academic][late_levels][<?php echo e($lIndex); ?>][label]" class="form-control form-control-sm" value="<?php echo e(__($level['label'])); ?>" placeholder="<?php echo e(__('center::settings.academic.level_label')); ?>" required>
                                            <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeLateLevel(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <div class="text-start mt-2">
                                    <button type="button" class="btn btn-link btn-sm text-muted p-0" onclick="restoreLateDefaults()">
                                        <i class="fas fa-undo-alt me-1"></i><?php echo e(__('center::settings.academic_restore_defaults')); ?></button>
                                </div>
                            </div>


                                <div id="deletion-inputs"></div>

                                <div class="mt-4 text-center">
                                    <button type="submit" form="academicStructureForm" class="btn btn-primary px-5 rounded-pill shadow-sm">
                                        <i class="fas fa-save me-2"></i> <?php echo e(__('center::settings.academic.save_structure')); ?>

                                    </button>
                                </div>
                            </form>
                        </div>
<?php /**PATH D:\new project\antigravty\taalimu.com\taalimu.com\Modules/Center\resources/views/settings/partials/_tab-academic.blade.php ENDPATH**/ ?>