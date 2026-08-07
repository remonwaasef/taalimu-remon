                                <!-- Welcome Emails Sub Tab -->
                                <div class="tab-pane fade show active" id="welcome-emails" role="tabpanel">
                                    <div class="mb-4">
                                        <h4 class="fw-bold" style="color: #3A0CA3;"><i class="fas fa-envelope-open-text me-2"></i> <?php echo e(__('center::settings.tabs.email_templates')); ?></h4>
                                        <p class="text-muted"><?php echo e(__('center::settings.email_templates.desc')); ?></p>
                                    </div>
                            <?php
                                $emailSettings = $tenant->settings['email_templates'] ?? [];
                                $presets = config('email_templates.presets', []);
                                $defaultPresetKey = config('email_templates.default_preset', 'formal');
                                $defaultPreset = $presets[$defaultPresetKey] ?? [];
                            ?>
                            <form action="<?php echo e(route('center.settings.update', ['tenant' => $tenant->domain ?? 'center'])); ?>" method="POST">
                                <?php echo csrf_field(); ?>

                                
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-envelope me-2"></i> <?php echo e(__('center::settings.email_templates.title')); ?></h5>
                                </div>
                                <p class="text-muted small mb-4"><?php echo e(__('center::settings.email_templates.desc')); ?></p>

                                
                                <div class="card border bg-light shadow-none rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-magic me-2 text-warning"></i> <?php echo e(__('center::settings.email_templates.choose_preset')); ?></h6>
                                        <div class="row g-3">
                                            <?php $__currentLoopData = $presets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $preset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-md-4">
                                                    <div class="bg-white border rounded-3 p-3 h-100 text-center cursor-pointer preset-card" data-preset="<?php echo e($key); ?>" style="cursor: pointer; transition: all 0.2s;">
                                                        <div class="mb-2">
                                                            <i class="<?php echo e($preset['icon'] ?? 'fas fa-file-alt'); ?> fa-2x text-primary"></i>
                                                        </div>
                                                        <span class="fw-bold small"><?php echo e(__('center::settings.email_templates.presets.' . $key)); ?></span>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <div class="card border bg-white shadow-none rounded-4 mb-4">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <h6 class="fw-bold mb-0"><i class="fas fa-user-graduate me-2 text-info"></i> <?php echo e(__('center::settings.email_templates.student_welcome')); ?> (Multi-Lingual)</h6>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="settings[email_templates][welcome_student_enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="settings[email_templates][welcome_student_enabled]" value="1" id="studentEmailEnabled" <?php echo e(($emailSettings['welcome_student_enabled'] ?? true) ? 'checked' : ''); ?>>
                                                            <label class="form-check-label fw-bold small ms-2" for="studentEmailEnabled"><?php echo e(__('center::settings.email_templates.activate')); ?></label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row g-4">
                                                    <!-- Arabic -->
                                                    <div class="col-md-4 border-end">
                                                        <label class="form-label fw-bold small text-primary">العربية (ar)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_student_subject_ar]" class="form-control text-end mb-2" value="<?php echo e($emailSettings['welcome_student_subject_ar'] ?? $emailSettings['welcome_student_subject'] ?? $defaultPreset['student_subject'] ?? 'مرحباً بك في {center_name} - بيانات الدخول'); ?>" placeholder="الموضوع">
                                                            <textarea name="settings[email_templates][welcome_student_body_ar]" class="form-control text-end" rows="6" dir="rtl" placeholder="نص الرسالة"><?php echo e($emailSettings['welcome_student_body_ar'] ?? $emailSettings['welcome_student_body'] ?? $defaultPreset['student_body'] ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- English -->
                                                    <div class="col-md-4 border-end">
                                                        <label class="form-label fw-bold small text-primary">English (en)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_student_subject_en]" class="form-control text-start mb-2" value="<?php echo e($emailSettings['welcome_student_subject_en'] ?? $defaultPreset['student_subject_en'] ?? 'Welcome to {center_name} - Login Details'); ?>" placeholder="Subject">
                                                            <textarea name="settings[email_templates][welcome_student_body_en]" class="form-control text-start" rows="6" dir="ltr" placeholder="Message body"><?php echo e($emailSettings['welcome_student_body_en'] ?? $defaultPreset['student_body_en'] ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- French -->
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small text-primary">Français (fr)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_student_subject_fr]" class="form-control text-start mb-2" value="<?php echo e($emailSettings['welcome_student_subject_fr'] ?? $defaultPreset['student_subject_fr'] ?? 'Bienvenue à {center_name} - Identifiants de connexion'); ?>" placeholder="Objet">
                                                            <textarea name="settings[email_templates][welcome_student_body_fr]" class="form-control text-start" rows="6" dir="ltr" placeholder="Corps du message"><?php echo e($emailSettings['welcome_student_body_fr'] ?? $defaultPreset['student_body_fr'] ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-3 pt-3 border-top">
                                                    <small class="fw-bold text-muted d-block mb-2"><?php echo e(__('center::settings.email_templates.placeholders_title')); ?></small>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <?php
                                                            $vars = [
                                                                'student_name' => __('center::settings.email_templates.placeholders.student_name'),
                                                                'center_name' => __('center::settings.email_templates.placeholders.center_name'),
                                                                'login_link' => __('center::settings.email_templates.placeholders.login_link'),
                                                                'password' => __('center::settings.email_templates.placeholders.password'),
                                                                'phone' => __('center::settings.email_templates.placeholders.phone'),
                                                            ];
                                                        ?>
                                                        <?php $__currentLoopData = $vars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <span class="badge bg-light text-dark border py-2 px-3 rounded-pill small"><?php echo e('{' . $key . '}'); ?> : <?php echo e($label); ?></span>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="card border bg-white shadow-none rounded-4 mb-4">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <h6 class="fw-bold mb-0"><i class="fas fa-user-shield me-2 text-success"></i> <?php echo e(__('center::settings.email_templates.guardian_welcome')); ?> (Multi-Lingual)</h6>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="settings[email_templates][welcome_guardian_enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="settings[email_templates][welcome_guardian_enabled]" value="1" id="guardianEmailEnabled" <?php echo e(($emailSettings['welcome_guardian_enabled'] ?? true) ? 'checked' : ''); ?>>
                                                            <label class="form-check-label fw-bold small ms-2" for="guardianEmailEnabled"><?php echo e(__('center::settings.email_templates.activate')); ?></label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row g-4">
                                                    <!-- Arabic -->
                                                    <div class="col-md-4 border-end">
                                                        <label class="form-label fw-bold small text-success">العربية (ar)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_guardian_subject_ar]" class="form-control text-end mb-2" value="<?php echo e($emailSettings['welcome_guardian_subject_ar'] ?? $emailSettings['welcome_guardian_subject'] ?? $defaultPreset['guardian_subject'] ?? 'تم تسجيل {student_name} في {center_name}'); ?>" placeholder="الموضوع">
                                                            <textarea name="settings[email_templates][welcome_guardian_body_ar]" class="form-control text-end" rows="6" dir="rtl" placeholder="نص الرسالة"><?php echo e($emailSettings['welcome_guardian_body_ar'] ?? $emailSettings['welcome_guardian_body'] ?? $defaultPreset['guardian_body'] ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- English -->
                                                    <div class="col-md-4 border-end">
                                                        <label class="form-label fw-bold small text-success">English (en)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_guardian_subject_en]" class="form-control text-start mb-2" value="<?php echo e($emailSettings['welcome_guardian_subject_en'] ?? $defaultPreset['guardian_subject_en'] ?? '{student_name} has been registered at {center_name}'); ?>" placeholder="Subject">
                                                            <textarea name="settings[email_templates][welcome_guardian_body_en]" class="form-control text-start" rows="6" dir="ltr" placeholder="Message body"><?php echo e($emailSettings['welcome_guardian_body_en'] ?? $defaultPreset['guardian_body_en'] ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- French -->
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small text-success">Français (fr)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_guardian_subject_fr]" class="form-control text-start mb-2" value="<?php echo e($emailSettings['welcome_guardian_subject_fr'] ?? $defaultPreset['guardian_subject_fr'] ?? '{student_name} a été inscrit à {center_name}'); ?>" placeholder="Objet">
                                                            <textarea name="settings[email_templates][welcome_guardian_body_fr]" class="form-control text-start" rows="6" dir="ltr" placeholder="Corps du message"><?php echo e($emailSettings['welcome_guardian_body_fr'] ?? $defaultPreset['guardian_body_fr'] ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-3 pt-3 border-top">
                                                    <small class="fw-bold text-muted d-block mb-2"><?php echo e(__('center::settings.email_templates.placeholders_title')); ?></small>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <?php
                                                            $gVars = [
                                                                'student_name' => __('center::settings.email_templates.placeholders.student_name'),
                                                                'center_name' => __('center::settings.email_templates.placeholders.center_name'),
                                                                'parent_name' => __('center::settings.email_templates.placeholders.parent_name'),
                                                                'stage' => __('center::settings.email_templates.placeholders.stage'),
                                                            ];
                                                        ?>
                                                        <?php $__currentLoopData = $gVars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <span class="badge bg-light text-dark border py-2 px-3 rounded-pill small"><?php echo e('{' . $key . '}'); ?> : <?php echo e($label); ?></span>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-5">
                                        
                                        <div class="sticky-top" style="top: 2rem; z-index: 5;">
                                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                                <div class="card-header bg-dark py-3 px-4">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="d-flex gap-1">
                                                            <span class="rounded-circle bg-danger" style="width:10px; height:10px;"></span>
                                                            <span class="rounded-circle bg-warning" style="width:10px; height:10px;"></span>
                                                            <span class="rounded-circle bg-success" style="width:10px; height:10px;"></span>
                                                        </div>
                                                        <span class="text-white x-small opacity-50 ms-2"><?php echo e(__('center::settings.email_templates.preview_title')); ?></span>
                                                    </div>
                                                </div>
                                                <div class="card-body p-0 bg-white">
                                                    <div class="p-3 border-bottom bg-light">
                                                        <div class="small text-muted mb-1"><?php echo e(__('center::settings.email_templates.subject')); ?>:</div>
                                                        <div id="preview-subject" class="fw-bold">...</div>
                                                    </div>
                                                    <div class="p-4" style="min-height: 400px; font-family: sans-serif; line-height: 1.6;">
                                                        <div id="preview-body" style="white-space: pre-wrap;">...</div>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-light border-0 text-center py-3">
                                                    <span class="text-muted x-small italic"><i class="fas fa-magic me-1 text-primary"></i> <?php echo e(__('center::settings.email_templates.preview_help')); ?></span>
                                                </div>
                                            </div>

                                            <div class="mt-4 p-4 bg-primary-soft rounded-4 border border-primary border-opacity-10">
                                                <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-lightbulb me-2"></i> <?php echo e(__('center::settings.email_templates.pro_tip')); ?></h6>
                                                <p class="small text-dark mb-0"><?php echo e(__('center::settings.email_templates.pro_tip_desc')); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                                                </div> <!-- Close welcome-emails sub tab -->
<?php /**PATH D:\new project\antigravty\taalimu.com\taalimu.com\Modules/Center\resources/views/settings/partials/_reminders-welcome-emails.blade.php ENDPATH**/ ?>