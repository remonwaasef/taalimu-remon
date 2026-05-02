<?php $__env->startSection('page-title', __('instructor::settings.title')); ?>
<?php $__env->startSection('page-subtitle', __('instructor::settings.subtitle') ?? 'Manage your account and subscription'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <ul class="nav nav-tabs border-0 bg-light p-1 rounded-pill" id="settingsTabs" role="tablist">
                        <li class="nav-item m-0" role="presentation">
                            <button class="nav-link active rounded-pill fw-bold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                                <i class="fas fa-info-circle me-2"></i> <?php echo e(__('instructor::settings.general_data')); ?>

                            </button>
                        </li>
                        <li class="nav-item m-0" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="whatsapp-tab" data-bs-toggle="tab" data-bs-target="#whatsapp" type="button" role="tab">
                                <i class="fab fa-whatsapp me-2"></i> <?php echo e(__('instructor::settings.whatsapp_settings')); ?>

                            </button>
                        </li>
                        <li class="nav-item m-0" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab">
                                <i class="fas fa-envelope me-2"></i> البريد الإلكتروني
                            </button>
                        </li>
                        <li class="nav-item m-0" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="reminders-tab" data-bs-toggle="tab" data-bs-target="#reminders" type="button" role="tab">
                                <i class="fas fa-bell me-2"></i> تذكيرات الدفع
                            </button>
                        </li>
                        <li class="nav-item m-0" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="subscription-tab" data-bs-toggle="tab" data-bs-target="#subscription" type="button" role="tab">
                                <i class="fas fa-credit-card me-2"></i> <?php echo e(__('instructor::settings.platform_subscription')); ?>

                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content" id="settingsTabsContent">
                        
                        
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <form action="<?php echo e(route('instructor.settings.update-general')); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="row g-4">
                                    <div class="col-md-3 text-center border-start">
                                        <div class="mb-3">
                                            <label class="form-label d-block fw-bold text-muted small"><?php echo e(__('instructor::settings.center_logo')); ?></label>
                                            <div class="position-relative d-inline-block">
                                                <img src="<?php echo e($tenant->logo ? asset('storage/' . $tenant->logo) : 'https://ui-avatars.com/api/?name=' . urlencode($tenant->name) . '&background=3A0CA3&color=fff&size=200'); ?>" 
                                                     alt="Logo" class="rounded-4 shadow-sm border" style="width: 150px; height: 150px; object-fit: contain; background: #f8fafc;">
                                                <label for="logoInput" class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0 shadow" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-camera"></i>
                                                </label>
                                                <input type="file" name="logo" id="logoInput" class="d-none" accept="image/*">
                                            </div>
                                            <div class="form-text x-small mt-2"><?php echo e(__('instructor::settings.logo_hint')); ?></div>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::settings.center_name')); ?></label>
                                                <input type="text" name="name" class="form-control bg-white border rounded-3" value="<?php echo e($tenant->name); ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::settings.general_phone')); ?></label>
                                                <input type="text" name="phone" class="form-control bg-white border rounded-3" value="<?php echo e($tenant->phone); ?>">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::settings.default_currency')); ?></label>
                                                <select name="currency" class="form-control bg-white border rounded-3">
                                                    <option value="ج.م" <?php echo e(($tenant->settings['currency'] ?? '') == 'ج.م' ? 'selected' : ''); ?>>جنيه مصري (ج.م)</option>
                                                    <option value="EGP" <?php echo e(($tenant->settings['currency'] ?? '') == 'EGP' ? 'selected' : ''); ?>>Egyptian Pound (EGP)</option>
                                                    <option value="SAR" <?php echo e(($tenant->settings['currency'] ?? '') == 'SAR' ? 'selected' : ''); ?>>Saudi Riyal (SAR)</option>
                                                    <option value="$" <?php echo e(($tenant->settings['currency'] ?? '') == '$' ? 'selected' : ''); ?>>US Dollar ($)</option>
                                                    <option value="€" <?php echo e(($tenant->settings['currency'] ?? '') == '€' ? 'selected' : ''); ?>>Euro (€)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::settings.address')); ?></label>
                                                <input type="text" name="address" class="form-control bg-white border rounded-3" value="<?php echo e($tenant->address); ?>">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::settings.description')); ?></label>
                                                <textarea name="description" class="form-control bg-white border rounded-3" rows="3"><?php echo e($tenant->description); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-start mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i> <?php echo e(__('instructor::settings.save_changes')); ?>

                                    </button>
                                </div>
                            </form>
                        </div>

                        
                        <div class="tab-pane fade" id="whatsapp" role="tabpanel">
                            <form action="<?php echo e(route('instructor.whatsapp.update')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h5 class="fw-bold mb-0 text-success"><i class="fab fa-whatsapp me-2"></i> <?php echo e(__('instructor::settings.whatsapp_connection')); ?></h5>
                                    <div class="form-check form-switch custom-switch">
                                        <input class="form-check-input" type="checkbox" name="enabled" id="whatsappEnabled" <?php echo e(($settings['enabled'] ?? false) ? 'checked' : ''); ?>>
                                        <label class="form-check-label fw-bold ms-2" for="whatsappEnabled"><?php echo e(__('instructor::settings.enable_service')); ?></label>
                                    </div>
                                </div>

                                <div class="row g-4 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::settings.default_country_code')); ?></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i class="fas fa-globe text-muted"></i></span>
                                            <input type="text" name="country_code" class="form-control bg-white border" value="<?php echo e($settings['country_code'] ?? '20'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::settings.instance_id')); ?></label>
                                        <input type="text" name="instance_id" class="form-control bg-white border" value="<?php echo e($settings['instance_id'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::settings.token')); ?></label>
                                        <input type="password" name="token" class="form-control bg-white border" value="<?php echo e($settings['token'] ?? ''); ?>">
                                    </div>
                                </div>

                                <hr class="my-4 opacity-50">

                                <h6 class="fw-bold mb-3"><i class="fas fa-comment-alt me-2 text-primary"></i> <?php echo e(__('instructor::settings.message_templates')); ?></h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::settings.attendance_msg')); ?></label>
                                        <textarea name="attendance_template" class="form-control bg-white border" rows="4"><?php echo e($settings['attendance_template'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::settings.payment_msg')); ?></label>
                                        <textarea name="payment_template" class="form-control bg-white border" rows="4"><?php echo e($settings['payment_template'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::settings.debt_msg')); ?></label>
                                        <textarea name="debt_template" class="form-control bg-white border" rows="4"><?php echo e($settings['debt_template'] ?? ''); ?></textarea>
                                    </div>
                                </div>

                                <div class="text-start mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">
                                        <i class="fas fa-check-circle me-2"></i> <?php echo e(__('instructor::settings.save_whatsapp')); ?>

                                    </button>
                                </div>
                            </form>
                        </div>

                        
                        <div class="tab-pane fade" id="email" role="tabpanel">
                            <?php
                                $emailSettings = $tenant->settings['email_templates'] ?? [];
                                $presets = config('email_templates.presets', []);
                                $defaultPresetKey = config('email_templates.default_preset', 'formal');
                                $defaultPreset = $presets[$defaultPresetKey] ?? [];
                            ?>
                            <form action="<?php echo e(route('instructor.email-templates.update')); ?>" method="POST">
                                <?php echo csrf_field(); ?>

                                
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-envelope me-2"></i> إعدادات البريد الإلكتروني</h5>
                                </div>
                                <p class="text-muted small mb-4">تحكم في رسائل الترحيب التلقائية التي يتم إرسالها عند تسجيل طالب جديد.</p>

                                
                                <div class="card border bg-light shadow-none rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-magic me-2 text-warning"></i> اختر قالب جاهز</h6>
                                        <div class="row g-3">
                                            <?php $__currentLoopData = $presets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $preset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-md-4">
                                                    <div class="bg-white border rounded-3 p-3 h-100 text-center cursor-pointer preset-card" data-preset="<?php echo e($key); ?>" style="cursor: pointer; transition: all 0.2s;">
                                                        <div class="mb-2">
                                                            <i class="<?php echo e($preset['icon'] ?? 'fas fa-file-alt'); ?> fa-2x text-primary"></i>
                                                        </div>
                                                        <span class="fw-bold small"><?php echo e($preset['name'] ?? $key); ?></span>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="row g-4">
                                    <div class="col-lg-7">
                                        <div class="card border bg-white shadow-none rounded-4 mb-4">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <h6 class="fw-bold mb-0"><i class="fas fa-user-graduate me-2 text-info"></i> رسالة ترحيب الطالب</h6>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 x-small reset-email-btn" data-subject-id="student_subject" data-body-id="student_body" data-default-subject="<?php echo e($defaultPreset['student_subject'] ?? ''); ?>" data-default-body="<?php echo e($defaultPreset['student_body'] ?? ''); ?>">
                                                            <i class="fas fa-undo"></i> للافتراضي
                                                        </button>
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="welcome_student_enabled" value="0">
                                                            <input class="form-check-input" type="checkbox" name="welcome_student_enabled" value="1" id="studentEmailEnabled" <?php echo e(($emailSettings['welcome_student_enabled'] ?? true) ? 'checked' : ''); ?>>
                                                            <label class="form-check-label fw-bold small ms-2" for="studentEmailEnabled">تفعيل</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                    <input type="text" name="welcome_student_subject" id="student_subject" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="<?php echo e($emailSettings['welcome_student_subject'] ?? $defaultPreset['student_subject'] ?? ''); ?>" placeholder="مرحباً بك في {اسم_المركز}">
                                                </div>

                                                <div class="mb-2 d-flex flex-wrap gap-1">
                                                    <?php
                                                        $vars = [
                                                            'اسم_الطالب' => 'اسم الطالب',
                                                            'اسم_المركز' => 'اسم المركز',
                                                            'رابط_الدخول' => 'رابط الدخول',
                                                            'كلمة_المرور' => 'كلمة المرور',
                                                            'رقم_الهاتف' => 'رقم الهاتف',
                                                        ];
                                                    ?>
                                                    <?php $__currentLoopData = $vars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="student_body" data-var="<?php echo e('{' . $key . '}'); ?>">
                                                            <i class="fas fa-plus-circle me-1 opacity-50"></i> <?php echo e($label); ?>

                                                        </button>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>

                                                <div class="mb-0">
                                                    <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                    <textarea name="welcome_student_body" id="student_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="8" placeholder="اكتب رسالة الترحيب هنا..."><?php echo e($emailSettings['welcome_student_body'] ?? $defaultPreset['student_body'] ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="card border bg-white shadow-none rounded-4 mb-4">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <h6 class="fw-bold mb-0"><i class="fas fa-user-shield me-2 text-success"></i> رسالة ترحيب ولي الأمر</h6>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 x-small reset-email-btn" data-subject-id="guardian_subject" data-body-id="guardian_body" data-default-subject="<?php echo e($defaultPreset['guardian_subject'] ?? ''); ?>" data-default-body="<?php echo e($defaultPreset['guardian_body'] ?? ''); ?>">
                                                            <i class="fas fa-undo"></i> للافتراضي
                                                        </button>
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="welcome_guardian_enabled" value="0">
                                                            <input class="form-check-input" type="checkbox" name="welcome_guardian_enabled" value="1" id="guardianEmailEnabled" <?php echo e(($emailSettings['welcome_guardian_enabled'] ?? true) ? 'checked' : ''); ?>>
                                                            <label class="form-check-label fw-bold small ms-2" for="guardianEmailEnabled">تفعيل</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                    <input type="text" name="welcome_guardian_subject" id="guardian_subject" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="<?php echo e($emailSettings['welcome_guardian_subject'] ?? $defaultPreset['guardian_subject'] ?? ''); ?>" placeholder="تم تسجيل {اسم_الطالب} في {اسم_المركز}">
                                                </div>

                                                <div class="mb-2 d-flex flex-wrap gap-1">
                                                    <?php
                                                        $gVars = array_merge($vars, ['اسم_ولي_الأمر' => 'اسم ولي الأمر', 'المرحلة' => 'المرحلة الدراسية']);
                                                    ?>
                                                    <?php $__currentLoopData = $gVars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="guardian_body" data-var="<?php echo e('{' . $key . '}'); ?>">
                                                            <i class="fas fa-plus-circle me-1 opacity-50"></i> <?php echo e($label); ?>

                                                        </button>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>

                                                <div class="mb-0">
                                                    <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                    <textarea name="welcome_guardian_body" id="guardian_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="8" placeholder="اكتب رسالة ولي الأمر هنا..."><?php echo e($emailSettings['welcome_guardian_body'] ?? $defaultPreset['guardian_body'] ?? ''); ?></textarea>
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

                                
                                
                                
                                <div class="mt-5 pt-4 border-top">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-bell me-2"></i> إشعارات البريد التلقائية</h5>
                                    </div>
                                    <p class="text-muted small mb-4">فعّل أو عطّل إرسال بريد إلكتروني تلقائي عند حدوث أحداث معينة. يمكنك تخصيص نص كل رسالة.</p>

                                    
                                    <div class="accordion" id="emailNotificationsAccordion">

                                        
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
                                                                <input type="hidden" name="notif_payment_reminder_enabled" value="0">
                                                                <input class="form-check-input" type="checkbox" name="notif_payment_reminder_enabled" value="1" id="notifPaymentReminder" <?php echo e(($emailSettings['notif_payment_reminder_enabled'] ?? false) ? 'checked' : ''); ?>>
                                                                <label class="form-check-label fw-bold small ms-2" for="notifPaymentReminder">مفعّل</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                        <input type="text" id="notif_payment_reminder_subject" name="notif_payment_reminder_subject" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="<?php echo e($emailSettings['notif_payment_reminder_subject'] ?? 'تذكير بسداد مصروفات {اسم_الطالب} - {اسم_المركز}'); ?>">
                                                    </div>
                                                    <div class="mb-2 d-flex flex-wrap gap-1">
                                                        <?php $payVars = ['اسم_الطالب'=>'اسم الطالب','اسم_المركز'=>'اسم المركز','المبلغ'=>'المبلغ المستحق','تاريخ_الاستحقاق'=>'تاريخ الاستحقاق','رابط_الدخول'=>'رابط الدخول']; ?>
                                                        <?php $__currentLoopData = $payVars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="notif_payment_reminder_body" data-var="<?php echo e('{'.$k.'}'); ?>"><i class="fas fa-plus-circle me-1 opacity-50"></i> <?php echo e($l); ?></button>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                        <textarea name="notif_payment_reminder_body" id="notif_payment_reminder_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="5"><?php echo e($emailSettings['notif_payment_reminder_body'] ?? "نذكركم بأن مصروفات الطالب/ة {اسم_الطالب} بمبلغ {المبلغ} مستحقة بتاريخ {تاريخ_الاستحقاق}.\n\nيرجى السداد في الموعد المحدد لضمان استمرار الخدمة.\n\nشكراً لتعاونكم,\n{اسم_المركز}"); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
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
                                                                <input type="hidden" name="notif_group_enrollment_enabled" value="0">
                                                                <input class="form-check-input" type="checkbox" name="notif_group_enrollment_enabled" value="1" id="notifGroupEnrollment" <?php echo e(($emailSettings['notif_group_enrollment_enabled'] ?? false) ? 'checked' : ''); ?>>
                                                                <label class="form-check-label fw-bold small ms-2" for="notifGroupEnrollment">مفعّل</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                        <input type="text" id="notif_group_enrollment_subject" name="notif_group_enrollment_subject" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="<?php echo e($emailSettings['notif_group_enrollment_subject'] ?? 'تم تسجيلك في مجموعة جديدة - {اسم_المركز}'); ?>">
                                                    </div>
                                                    <div class="mb-2 d-flex flex-wrap gap-1">
                                                        <?php $grpVars = ['اسم_الطالب'=>'اسم الطالب','اسم_المركز'=>'اسم المركز','اسم_المجموعة'=>'اسم المجموعة','سعر_الدورة'=>'سعر الدورة','رابط_الدخول'=>'رابط الدخول']; ?>
                                                        <?php $__currentLoopData = $grpVars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="notif_group_enrollment_body" data-var="<?php echo e('{'.$k.'}'); ?>"><i class="fas fa-plus-circle me-1 opacity-50"></i> <?php echo e($l); ?></button>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                        <textarea name="notif_group_enrollment_body" id="notif_group_enrollment_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="5"><?php echo e($emailSettings['notif_group_enrollment_body'] ?? "مرحباً {اسم_الطالب}،\n\nتم تسجيلك في مجموعة جديدة: {اسم_المجموعة}\n\nيمكنك الدخول للمنصة من خلال:\n{رابط_الدخول}\n\nنتمنى لك التوفيق!\n{اسم_المركز}"); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
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
                                                                <input type="hidden" name="notif_payment_confirmed_enabled" value="0">
                                                                <input class="form-check-input" type="checkbox" name="notif_payment_confirmed_enabled" value="1" id="notifPaymentConfirmed" <?php echo e(($emailSettings['notif_payment_confirmed_enabled'] ?? false) ? 'checked' : ''); ?>>
                                                                <label class="form-check-label fw-bold small ms-2" for="notifPaymentConfirmed">مفعّل</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">عنوان الرسالة</label>
                                                        <input type="text" id="notif_payment_confirmed_subject" name="notif_payment_confirmed_subject" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="<?php echo e($emailSettings['notif_payment_confirmed_subject'] ?? 'تأكيد استلام دفعة - {اسم_المركز}'); ?>">
                                                    </div>
                                                    <div class="mb-2 d-flex flex-wrap gap-1">
                                                        <?php $confVars = ['اسم_الطالب'=>'اسم الطالب','اسم_المركز'=>'اسم المركز','المبلغ_المدفوع'=>'المبلغ المدفوع','تاريخ_الدفع'=>'تاريخ الدفع','المتبقي'=>'المبلغ المتبقي','طريقة_الدفع'=>'طريقة الدفع']; ?>
                                                        <?php $__currentLoopData = $confVars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="notif_payment_confirmed_body" data-var="<?php echo e('{'.$k.'}'); ?>"><i class="fas fa-plus-circle me-1 opacity-50"></i> <?php echo e($l); ?></button>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label fw-bold small text-muted">نص الرسالة</label>
                                                        <textarea name="notif_payment_confirmed_body" id="notif_payment_confirmed_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="5"><?php echo e($emailSettings['notif_payment_confirmed_body'] ?? "مرحباً {اسم_الطالب}،\n\nنؤكد استلام دفعة مالية بالتفاصيل التالية:\n• المبلغ: {المبلغ_المدفوع}\n• التاريخ: {تاريخ_الدفع}\n• طريقة الدفع: {طريقة_الدفع}\n• المتبقي: {المتبقي}\n\nشكراً لالتزامكم.\n{اسم_المركز}"); ?></textarea>
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
                            
                            <form action="<?php echo e(route('instructor.email-templates.reset')); ?>" method="POST" class="d-inline-block mt-3" onsubmit="return confirm('هل أنت متأكد أنك تريد مسح جميع التعديلات وإعادة النصوص للوضع الافتراضي؟');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-light text-danger rounded-pill px-4 fw-bold shadow-sm border">
                                    <i class="fas fa-undo me-2"></i> إعادة الضبط للافتراضي
                                </button>
                            </form>
                        </div>

                        
                        <div class="tab-pane fade" id="reminders" role="tabpanel">
                            <?php $reminderSettings = $tenant->settings['payment_reminders'] ?? []; ?>
                            <form action="<?php echo e(route('instructor.reminders.update')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-bell me-2"></i> <?php echo e(__('instructor::reminders.title')); ?></h5>
                                </div>
                                <p class="text-muted small mb-4"><?php echo e(__('instructor::reminders.subtitle')); ?></p>

                                
                                <div class="card border bg-light shadow-none rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-cog me-2 text-muted"></i> الإعدادات الافتراضية</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::reminders.default_due_day')); ?></label>
                                                <select name="default_due_day" class="form-select bg-white">
                                                    <?php for($d = 1; $d <= 28; $d++): ?>
                                                        <option value="<?php echo e($d); ?>" <?php echo e(($reminderSettings['default_due_day'] ?? 25) == $d ? 'selected' : ''); ?>><?php echo e($d); ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                                <div class="form-text small"><?php echo e(__('instructor::reminders.default_due_day_hint')); ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::reminders.default_monthly_fee')); ?></label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="default_monthly_fee" class="form-control bg-white" value="<?php echo e($reminderSettings['default_monthly_fee'] ?? ''); ?>" placeholder="0.00">
                                                    <span class="input-group-text bg-white"><?php echo e($tenant->settings['currency'] ?? 'ج.م'); ?></span>
                                                </div>
                                                <div class="form-text small"><?php echo e(__('instructor::reminders.default_monthly_fee_hint')); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="card border bg-light shadow-none rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-1"><i class="fas fa-envelope me-2 text-info"></i> <?php echo e(__('instructor::reminders.email_section')); ?></h6>
                                        <p class="text-muted small mb-3"><?php echo e(__('instructor::reminders.email_section_hint')); ?></p>
                                        <div class="row g-3">
                                            <?php
                                                $emailDefaults = [
                                                    ['days_before' => 7, 'label' => __('instructor::reminders.days_before_due', ['days' => 7])],
                                                    ['days_before' => 3, 'label' => __('instructor::reminders.days_before_due', ['days' => 3])],
                                                    ['days_before' => 0, 'label' => __('instructor::reminders.on_due_day')],
                                                ];
                                                $emailReminders = $reminderSettings['email_reminders'] ?? [
                                                    ['days_before' => 7, 'enabled' => true],
                                                    ['days_before' => 3, 'enabled' => true],
                                                    ['days_before' => 0, 'enabled' => true],
                                                ];
                                            ?>
                                            <?php $__currentLoopData = $emailDefaults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $def): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-md-4">
                                                    <div class="bg-white border rounded-3 p-3 h-100">
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="email_reminders[<?php echo e($i); ?>][days_before]" value="<?php echo e($def['days_before']); ?>">
                                                            <input type="hidden" name="email_reminders[<?php echo e($i); ?>][enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="email_reminders[<?php echo e($i); ?>][enabled]" value="1" id="email_<?php echo e($i); ?>" <?php echo e(($emailReminders[$i]['enabled'] ?? true) ? 'checked' : ''); ?>>
                                                            <label class="form-check-label fw-bold small" for="email_<?php echo e($i); ?>"><?php echo e($def['label']); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="card border bg-light shadow-none rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-1"><i class="fab fa-whatsapp me-2 text-success"></i> <?php echo e(__('instructor::reminders.whatsapp_section')); ?></h6>
                                        <p class="text-muted small mb-3"><?php echo e(__('instructor::reminders.whatsapp_section_hint')); ?></p>
                                        <div class="row g-3">
                                            <?php
                                                $waDefaults = [
                                                    ['days_after' => 1, 'label' => __('instructor::reminders.days_after_due', ['days' => 1])],
                                                    ['days_after' => 3, 'label' => __('instructor::reminders.days_after_due', ['days' => 3])],
                                                    ['days_after' => 7, 'label' => __('instructor::reminders.days_after_due', ['days' => 7])],
                                                ];
                                                $waReminders = $reminderSettings['whatsapp_reminders'] ?? [
                                                    ['days_after' => 1, 'enabled' => true],
                                                    ['days_after' => 3, 'enabled' => true],
                                                    ['days_after' => 7, 'enabled' => false],
                                                ];
                                            ?>
                                            <?php $__currentLoopData = $waDefaults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $def): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-md-4">
                                                    <div class="bg-white border rounded-3 p-3 h-100">
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="whatsapp_reminders[<?php echo e($i); ?>][days_after]" value="<?php echo e($def['days_after']); ?>">
                                                            <input type="hidden" name="whatsapp_reminders[<?php echo e($i); ?>][enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="whatsapp_reminders[<?php echo e($i); ?>][enabled]" value="1" id="wa_<?php echo e($i); ?>" <?php echo e(($waReminders[$i]['enabled'] ?? false) ? 'checked' : ''); ?>>
                                                            <label class="form-check-label fw-bold small" for="wa_<?php echo e($i); ?>"><?php echo e($def['label']); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>

                                        <div class="mt-3">
                                            <div class="form-check form-switch">
                                                <input type="hidden" name="whatsapp_before_due" value="0">
                                                <input class="form-check-input" type="checkbox" name="whatsapp_before_due" value="1" id="waBefore" <?php echo e(($reminderSettings['whatsapp_before_due'] ?? false) ? 'checked' : ''); ?>>
                                                <label class="form-check-label fw-bold small" for="waBefore"><?php echo e(__('instructor::reminders.whatsapp_before_due')); ?></label>
                                            </div>
                                            <div class="form-text small text-warning"><?php echo e(__('instructor::reminders.whatsapp_before_due_warning')); ?></div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="card border bg-light shadow-none rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-comment-alt me-2 text-primary"></i> قوالب الرسائل</h6>
                                        <div class="alert alert-info border-0 shadow-none rounded-3 py-2 px-3 mb-3">
                                            <div class="small">
                                                <?php echo e(__('instructor::reminders.template_variables')); ?>

                                                <code>:student_name</code> ،
                                                <code>:amount</code> ،
                                                <code>:due_day</code> ،
                                                <code>:tenant_name</code> ،
                                                <code>:month</code> ،
                                                <code>:currency</code>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::reminders.email_template')); ?></label>
                                                <textarea name="email_template" class="form-control bg-white" rows="4" placeholder="اتركه فارغاً لاستخدام القالب الافتراضي"><?php echo e($reminderSettings['email_template'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::reminders.whatsapp_template')); ?></label>
                                                <textarea name="whatsapp_template" class="form-control bg-white" rows="4" placeholder="اتركه فارغاً لاستخدام القالب الافتراضي"><?php echo e($reminderSettings['whatsapp_template'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-start mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i> <?php echo e(__('instructor::reminders.save_settings')); ?>

                                    </button>
                                </div>
                            </form>
                        </div>

                        
                        <div class="tab-pane fade" id="subscription" role="tabpanel">
                            <?php
                                $subscription = $tenant->activeSubscription();
                                $package = $subscription ? $subscription->resolved_package : null;
                                $service = app(\App\Services\SubscriptionService::class);
                            ?>

                            
                            <div class="bg-light rounded-4 p-4 border mb-5">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="fw-bold mb-0 text-primary"><i class="fas fa-chart-pie me-2"></i> <?php echo e(__('instructor::settings.resource_consumption')); ?></h6>
                                    <?php if($subscription): ?>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-success py-2 px-3 rounded-pill shadow-sm"><i class="fas fa-check-circle me-1"></i> <?php echo e(__('instructor::settings.active_subscription')); ?></span>
                                            <?php if($subscription->ends_at): ?>
                                                <small class="text-muted fw-bold x-small"><?php echo e(__('instructor::settings.expires_on', ['date' => $subscription->ends_at->format('d/m/Y')])); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="row g-4">
                                    <?php
                                        $features = [
                                            ['code' => 'max_students', 'label' => __('instructor::settings.students'), 'icon' => 'fa-user-graduate'],
                                            ['code' => 'max_courses', 'label' => __('instructor::settings.groups'), 'icon' => 'fa-users'],
                                            ['code' => 'max_instructors', 'label' => __('instructor::settings.assistants'), 'icon' => 'fa-chalkboard-teacher'],
                                        ];
                                    ?>

                                    <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $limit = $service->getFeatureValue($tenant, $f['code']);
                                            $usage = 0;
                                            if($f['code'] == 'max_students') $usage = $tenant->users()->where('role', 'student')->count();
                                            if($f['code'] == 'max_courses') $usage = \App\Models\Course::where('tenant_id', $tenant->id)->count();
                                            if($f['code'] == 'max_instructors') $usage = \App\Models\Instructor::where('tenant_id', $tenant->id)->count();
                                            
                                            $isUnlimited = $limit === 'unlimited' || $limit == -1;
                                            $percent = 0;
                                            if (!$isUnlimited && is_numeric($limit) && $limit > 0) {
                                                $percent = min(100, ($usage / (float)$limit) * 100);
                                            } elseif (!$isUnlimited) {
                                                $percent = 100;
                                            }
                                            $color = $percent > 90 ? 'danger' : ($percent > 70 ? 'warning' : 'success');
                                        ?>
                                        <div class="col-md-4">
                                            <div class="bg-white rounded-4 p-3 border shadow-sm h-100">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                                            <i class="fas <?php echo e($f['icon']); ?> x-small"></i>
                                                        </div>
                                                        <span class="small fw-bold"><?php echo e($f['label']); ?></span>
                                                    </div>
                                                    <span class="x-small text-muted fw-bold" dir="ltr"><?php echo e($usage); ?> / <?php echo e($isUnlimited ? '∞' : $limit); ?></span>
                                                </div>
                                                <div class="progress rounded-pill shadow-none mb-1" style="height: 6px; background: #f1f5f9;">
                                                    <div class="progress-bar bg-<?php echo e($color); ?> rounded-pill" role="progressbar" style="width: <?php echo e($percent); ?>%"></div>
                                                </div>
                                                <div class="text-start">
                                                    <span class="x-small text-<?php echo e($color); ?> fw-bold"><?php echo e(round($percent)); ?>%</span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <div x-data="{ billingCycle: 'monthly' }">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fas fa-layer-group small"></i>
                                        </div>
                                        <h5 class="fw-bold mb-0"><?php echo e(__('instructor::settings.plans_and_upgrades')); ?></h5>
                                    </div>

                                    <!-- Cycle Switcher -->
                                    <div class="bg-light p-1 rounded-pill d-flex shadow-sm" style="width: 280px;">
                                        <button type="button" @click="billingCycle = 'monthly'" 
                                                class="btn btn-sm flex-grow-1 rounded-pill font-bold transition-all"
                                                :class="billingCycle === 'monthly' ? 'btn-primary shadow-sm' : 'btn-link text-muted text-decoration-none'">
                                            <?php echo e(__('instructor::settings.monthly')); ?>

                                        </button>
                                        <button type="button" @click="billingCycle = 'term'" 
                                                class="btn btn-sm flex-grow-1 rounded-pill font-bold transition-all"
                                                :class="billingCycle === 'term' ? 'btn-primary shadow-sm' : 'btn-link text-muted text-decoration-none'">
                                            <?php echo e(__('instructor::settings.term')); ?>

                                        </button>
                                        <button type="button" @click="billingCycle = 'yearly'" 
                                                class="btn btn-sm flex-grow-1 rounded-pill font-bold transition-all"
                                                :class="billingCycle === 'yearly' ? 'btn-primary shadow-sm' : 'btn-link text-muted text-decoration-none'">
                                            <?php echo e(__('instructor::settings.yearly')); ?>

                                        </button>
                                    </div>
                                </div>
                                
                                <div class="row g-4">
                                    <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pkg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $isCurrent = $package && $package->id == $pkg->id;
                                        ?>
                                        <div class="col-md-4">
                                            <div class="card border rounded-4 shadow-sm h-100 <?php echo e($isCurrent ? 'border-primary border-2' : ($pkg->is_featured ? 'border-primary' : '')); ?> position-relative overflow-hidden transition-all hover-shadow">
                                                
                                                <?php if($isCurrent): ?>
                                                    <div class="bg-primary text-white text-center py-2 fw-bold" style="font-size: 11px;">
                                                        <i class="fas fa-star me-1"></i> <?php echo e(__('instructor::settings.current_plan')); ?>

                                                    </div>
                                                <?php elseif($pkg->is_featured): ?>
                                                    <div class="bg-secondary text-white text-center py-1 position-absolute w-100" style="top: 0; left: 0; font-size: 10px; font-weight: bold; z-index: 10;">
                                                        <?php echo e(__('instructor::settings.recommended')); ?>

                                                    </div>
                                                <?php endif; ?>

                                                <div class="card-body p-4 <?php echo e($isCurrent ? 'pt-4' : 'pt-5'); ?>">
                                                    <h5 class="fw-bold mb-2"><?php echo e($pkg->name); ?></h5>
                                                     <div class="mb-4">
                                                        <div x-show="billingCycle === 'monthly'" class="animate-fade-in">
                                                            <div class="d-flex align-items-baseline gap-1">
                                                                <span class="fs-4 fw-bold text-primary"><?php echo e(number_format((float)$pkg->price)); ?></span>
                                                                <small class="text-muted x-small"><?php echo e(app('tenant')->settings['currency'] ?? 'EGP'); ?> / <?php echo e(__('instructor::settings.monthly')); ?></small>
                                                            </div>
                                                        </div>
                                                        <?php if($pkg->term_price): ?>
                                                        <div x-show="billingCycle === 'term'" class="animate-fade-in" style="display: none;">
                                                            <div class="d-flex align-items-baseline gap-1">
                                                                <span class="fs-4 fw-bold text-primary"><?php echo e(number_format((float)$pkg->term_price)); ?></span>
                                                                <small class="text-muted x-small"><?php echo e(app('tenant')->settings['currency'] ?? 'EGP'); ?> / <?php echo e(__('instructor::settings.term')); ?></small>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if($pkg->yearly_price): ?>
                                                        <div x-show="billingCycle === 'yearly'" class="animate-fade-in" style="display: none;">
                                                            <div class="d-flex align-items-baseline gap-1">
                                                                <span class="fs-4 fw-bold text-primary"><?php echo e(number_format((float)$pkg->yearly_price)); ?></span>
                                                                <small class="text-muted x-small"><?php echo e(app('tenant')->settings['currency'] ?? 'EGP'); ?> / <?php echo e(__('instructor::settings.yearly')); ?></small>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>
                                                     </div>
                                                    
                                                    <hr class="opacity-25 mb-4">

                                                    <ul class="list-unstyled mb-4">
                                                        <?php $__currentLoopData = $pkg->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                                $val = $feature->pivot->value;
                                                                $displayVal = $val;
                                                                if($val == '-1' || $val == 'unlimited') $displayVal = __('instructor::settings.unlimited');
                                                                
                                                                $icon = 'fa-check-circle text-success';
                                                                if($feature->type == 'boolean') {
                                                                    $displayVal = filter_var($val, FILTER_VALIDATE_BOOLEAN) ? __('instructor::settings.available') : __('instructor::settings.not_available');
                                                                    $icon = filter_var($val, FILTER_VALIDATE_BOOLEAN) ? 'fa-check-circle text-success' : 'fa-times-circle text-danger';
                                                                }
                                                            ?>
                                                            <li class="small mb-2 d-flex align-items-center gap-2">
                                                                <i class="fas <?php echo e($icon); ?>" style="font-size: 12px;"></i>
                                                                <span class="text-muted"><?php echo e($feature->name); ?>:</span>
                                                                <span class="fw-bold"><?php echo e($displayVal); ?></span>
                                                            </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </ul>

                                                    <?php if($isCurrent): ?>
                                                        <div class="alert alert-primary bg-opacity-10 border-0 mb-0 py-3 text-center rounded-4">
                                                            <span class="fw-bold small text-primary"><i class="fas fa-check-circle me-1"></i> <?php echo e(__('instructor::settings.active_subscription')); ?></span>
                                                            <?php if($subscription->ends_at): ?>
                                                                 <div class="x-small text-muted mt-1"><?php echo e(__('instructor::settings.expires_on', ['date' => $subscription->ends_at->format('d/m/Y')])); ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <a :href="'<?php echo e(route('center.subscription.checkout', ['package' => $pkg->id, 'tenant' => $tenant->domain ?? $tenant->id])); ?>?cycle=' + billingCycle" class="btn btn-outline-primary rounded-pill w-100 fw-bold py-2"><?php echo e(__('instructor::settings.subscribe_now')); ?></a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

<?php $__env->startPush('scripts'); ?>
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

    const presets = <?php echo json_encode(config('email_templates.presets', []), 512) ?>;
    
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('instructor::components.layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\settings.blade.php ENDPATH**/ ?>