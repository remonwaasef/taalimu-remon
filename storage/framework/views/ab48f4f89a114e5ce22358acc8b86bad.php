<?php $__env->startPush('styles'); ?>
<style>
    .bg-info-soft { background-color: rgba(23, 162, 184, 0.1) !important; border-color: rgba(23, 162, 184, 0.2) !important; }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1) !important; border-color: rgba(220, 53, 69, 0.2) !important; }
    .active-reminder-info { background-color: rgba(0, 180, 216, 0.15) !important; border-color: #00b4d8 !important; }
    .active-reminder-danger { background-color: rgba(231, 76, 60, 0.15) !important; border-color: #e74c3c !important; }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('title', __('center::settings.title')); ?>

<?php $__env->startSection('page-title', __('center::settings.title')); ?>

<?php $__env->startSection('content'); ?>
    <?php $activeTab = request('tab', 'general'); ?>
    
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 p-0">
                    <ul class="nav nav-tabs nav-fill" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo e($activeTab == 'general' ? 'active' : ''); ?> py-3 fw-bold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-selected="<?php echo e($activeTab == 'general' ? 'true' : 'false'); ?>">
                                <i class="fas fa-info-circle me-2"></i> <?php echo e(__('center::settings.tabs.general')); ?>

                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo e($activeTab == 'academic' ? 'active' : ''); ?> py-3 fw-bold" id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic" type="button" role="tab" aria-selected="<?php echo e($activeTab == 'academic' ? 'true' : 'false'); ?>">
                                <i class="fas fa-graduation-cap me-2"></i> <?php echo e(__('center::settings.tabs.academic')); ?>

                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo e($activeTab == 'financial' ? 'active' : ''); ?> py-3 fw-bold" id="financial-tab" data-bs-toggle="tab" data-bs-target="#financial" type="button" role="tab" aria-selected="<?php echo e($activeTab == 'financial' ? 'true' : 'false'); ?>">
                                <i class="fas fa-coins me-2"></i> <?php echo e(__('center::settings.tabs.financial')); ?>

                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo e($activeTab == 'appearance' ? 'active' : ''); ?> py-3 fw-bold" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab" aria-selected="<?php echo e($activeTab == 'appearance' ? 'true' : 'false'); ?>">
                                <i class="fas fa-paint-brush me-2"></i> <?php echo e(__('center::settings.tabs.appearance')); ?>

                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo e($activeTab == 'whatsapp' ? 'active' : ''); ?> py-3 fw-bold" id="whatsapp-tab" data-bs-toggle="tab" data-bs-target="#whatsapp" type="button" role="tab" aria-selected="<?php echo e($activeTab == 'whatsapp' ? 'true' : 'false'); ?>">
                                <i class="fab fa-whatsapp me-2 text-success"></i> <?php echo e(__('center::settings.tabs.whatsapp')); ?>

                            </button>
                        </li>
                        
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo e(in_array($activeTab, ['reminders', 'email_templates']) ? 'active' : ''); ?> py-3 fw-bold" id="reminders-tab" data-bs-toggle="tab" data-bs-target="#reminders" type="button" role="tab" aria-selected="<?php echo e(in_array($activeTab, ['reminders', 'email_templates']) ? 'true' : 'false'); ?>">
                                <i class="fas fa-bullhorn me-2 text-warning"></i> <?php echo e(__('center::settings.tabs.email_templates')); ?> / <?php echo e(__('center::settings.tabs.reminders')); ?>

                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo e($activeTab == 'privacy' ? 'active' : ''); ?> py-3 fw-bold" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#privacy" type="button" role="tab" aria-selected="<?php echo e($activeTab == 'privacy' ? 'true' : 'false'); ?>">
                                <i class="fas fa-user-shield me-2 text-danger"></i> <?php echo e(__('center::settings.tabs.privacy')); ?>

                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4 fw-bold">
                            <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4 fw-bold">
                            <i class="fas fa-exclamation-triangle me-2"></i> <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                            <ul class="mb-0 small fw-bold">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="tab-content" id="settingsTabsContent">
                        <!-- General Settings -->
                        <div class="tab-pane fade <?php echo e($activeTab == 'general' ? 'show active' : ''); ?>" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <form action="<?php echo e(route('center.settings.update', ['tenant' => $tenant->domain ?? 'center'])); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="row mb-4">
                                <!-- Logo -->
                                <div class="col-md-6 text-center border-end">
                                    <div class="position-relative d-inline-block">
                                        <div class="avatar-xl rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold display-4 shadow-sm overflow-hidden" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                            <?php if($tenant->logo): ?>
                                                <img src="<?php echo e(asset('storage/' . $tenant->logo)); ?>" class="w-100 h-100 object-fit-contain p-2">
                                            <?php else: ?>
                                                <?php echo e(substr($tenant->name, 0, 1)); ?>

                                            <?php endif; ?>
                                        </div>
                                        <label for="logo" class="position-absolute bottom-0 end-0 bg-white shadow-sm p-2 rounded-circle cursor-pointer border">
                                            <i class="fas fa-camera text-muted small"></i>
                                        </label>
                                        <input type="file" id="logo" name="logo" class="d-none" accept="image/*">
                                    </div>
                                    <p class="text-muted small mt-2 mb-0 fw-bold"><?php echo e(__('center::settings.general.logo')); ?></p>
                                </div>
                                <!-- Favicon -->
                                <div class="col-md-6 text-center">
                                    <div class="position-relative d-inline-block">
                                        <div class="avatar-lg rounded bg-light d-flex align-items-center justify-content-center text-primary shadow-sm overflow-hidden" style="width: 60px; height: 60px; margin-top: 20px;">
                                            <?php if($tenant->favicon): ?>
                                                <img src="<?php echo e(asset('storage/' . $tenant->favicon)); ?>" class="w-100 h-100 object-fit-contain p-2">
                                            <?php else: ?>
                                                <i class="fas fa-globe fs-2"></i>
                                            <?php endif; ?>
                                        </div>
                                        <label for="favicon" class="position-absolute bottom-0 end-0 bg-white shadow-sm p-2 rounded-circle cursor-pointer border">
                                            <i class="fas fa-camera text-muted x-small"></i>
                                        </label>
                                        <input type="file" id="favicon" name="favicon" class="d-none" accept="image/*">
                                    </div>
                                    <p class="text-muted small mt-2 mb-0 fw-bold"><?php echo e(__('center::settings.general.favicon')); ?></p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.general.name')); ?></label>
                                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $tenant->name)); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.general.phone')); ?></label>
                                    <input type="tel" name="phone" class="form-control" value="<?php echo e(old('phone', $tenant->phone)); ?>" oninput="this.value = this.value.replace(/[^0-9\+\-\(\)\s]/g, '')" placeholder="01xxxxxxxxx">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.general.email')); ?></label>
                                    <input type="email" class="form-control bg-light" value="<?php echo e($tenant->email ?? ($tenant->users->first()?->email ?? 'N/A')); ?>" disabled>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.general.address')); ?></label>
                                    <input type="text" name="address" class="form-control" value="<?php echo e(old('address', $tenant->address)); ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.general.description')); ?></label>
                                    <textarea name="description" class="form-control" rows="3"><?php echo e(old('description', $tenant->description)); ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.general.timezone')); ?></label>
                                    <select name="timezone" class="form-select select2">
                                        <?php $__currentLoopData = timezone_identifiers_list(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timezone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($timezone); ?>" <?php echo e($tenant->timezone == $timezone ? 'selected' : ''); ?>>
                                                <?php echo e($timezone); ?> (<?php echo e(\Carbon\Carbon::now($timezone)->format('h:i A')); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small class="text-muted"><?php echo e(__('center::settings.general_timezone_help')); ?></small>
                                </div>

                                <!-- Social Media Links -->
                                <div class="col-12 mt-4">
                                    <h6 class="fw-bold text-primary mb-3"><i class="fas fa-share-alt me-2"></i><?php echo e(__('center::settings.general.social_links')); ?></h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-facebook text-primary"></i></span>
                                                <input type="url" name="facebook_url" class="form-control" value="<?php echo e(old('facebook_url', $tenant->facebook_url)); ?>" placeholder="<?php echo e(__('center::settings.general.facebook')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-instagram text-danger"></i></span>
                                                <input type="url" name="instagram_url" class="form-control" value="<?php echo e(old('instagram_url', $tenant->instagram_url)); ?>" placeholder="<?php echo e(__('center::settings.general.instagram')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-twitter text-info"></i></span>
                                                <input type="url" name="twitter_url" class="form-control" value="<?php echo e(old('twitter_url', $tenant->twitter_url)); ?>" placeholder="<?php echo e(__('center::settings.general.twitter')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-youtube text-danger"></i></span>
                                                <input type="url" name="youtube_url" class="form-control" value="<?php echo e(old('youtube_url', $tenant->youtube_url)); ?>" placeholder="<?php echo e(__('center::settings.general.youtube')); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> <?php echo e(__('center::settings.general.save')); ?>

                                    </button>
                                </div>
                            </form>
                        </div> <!-- Closes general tab -->

                        <!-- Combined Tab: Email Templates & Reminders -->
                        <div class="tab-pane fade <?php echo e(in_array($activeTab, ['reminders', 'email_templates']) ? 'show active' : ''); ?>" id="reminders" role="tabpanel" aria-labelledby="reminders-tab">
                            
                            <!-- Sub Tabs Nav -->
                            <ul class="nav nav-pills mb-4 bg-light p-2 rounded-4 d-flex justify-content-center gap-2" id="remindersSubTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active rounded-pill px-4 fw-bold" id="welcome-emails-tab" data-bs-toggle="pill" data-bs-target="#welcome-emails" type="button" role="tab">
                                        <i class="fas fa-handshake me-2"></i> <?php echo e(__('center::settings.reminders.sub_tabs.welcome')); ?>

                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill px-4 fw-bold" id="system-notifs-tab" data-bs-toggle="pill" data-bs-target="#system-notifs" type="button" role="tab">
                                        <i class="fas fa-bell me-2"></i> <?php echo e(__('center::settings.reminders.sub_tabs.system')); ?>

                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill px-4 fw-bold" id="payment-reminders-tab" data-bs-toggle="pill" data-bs-target="#payment-reminders" type="button" role="tab">
                                        <i class="fas fa-calendar-check me-2"></i> <?php echo e(__('center::settings.reminders.sub_tabs.payment')); ?>

                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="remindersSubTabsContent">
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
                                                            <input type="text" name="settings[email_templates][welcome_student_subject_ar]" class="form-control text-end mb-2" value="<?php echo e($emailSettings['welcome_student_subject_ar'] ?? $emailSettings['welcome_student_subject'] ?? ''); ?>" placeholder="الموضوع">
                                                            <textarea name="settings[email_templates][welcome_student_body_ar]" class="form-control text-end" rows="6" dir="rtl" placeholder="نص الرسالة"><?php echo e($emailSettings['welcome_student_body_ar'] ?? $emailSettings['welcome_student_body'] ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- English -->
                                                    <div class="col-md-4 border-end">
                                                        <label class="form-label fw-bold small text-primary">English (en)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_student_subject_en]" class="form-control text-start mb-2" value="<?php echo e($emailSettings['welcome_student_subject_en'] ?? ''); ?>" placeholder="Subject">
                                                            <textarea name="settings[email_templates][welcome_student_body_en]" class="form-control text-start" rows="6" dir="ltr" placeholder="Message body"><?php echo e($emailSettings['welcome_student_body_en'] ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- French -->
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small text-primary">Français (fr)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_student_subject_fr]" class="form-control text-start mb-2" value="<?php echo e($emailSettings['welcome_student_subject_fr'] ?? ''); ?>" placeholder="Objet">
                                                            <textarea name="settings[email_templates][welcome_student_body_fr]" class="form-control text-start" rows="6" dir="ltr" placeholder="Corps du message"><?php echo e($emailSettings['welcome_student_body_fr'] ?? ''); ?></textarea>
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
                                                            <input type="text" name="settings[email_templates][welcome_guardian_subject_ar]" class="form-control text-end mb-2" value="<?php echo e($emailSettings['welcome_guardian_subject_ar'] ?? $emailSettings['welcome_guardian_subject'] ?? ''); ?>" placeholder="الموضوع">
                                                            <textarea name="settings[email_templates][welcome_guardian_body_ar]" class="form-control text-end" rows="6" dir="rtl" placeholder="نص الرسالة"><?php echo e($emailSettings['welcome_guardian_body_ar'] ?? $emailSettings['welcome_guardian_body'] ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- English -->
                                                    <div class="col-md-4 border-end">
                                                        <label class="form-label fw-bold small text-success">English (en)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_guardian_subject_en]" class="form-control text-start mb-2" value="<?php echo e($emailSettings['welcome_guardian_subject_en'] ?? ''); ?>" placeholder="Subject">
                                                            <textarea name="settings[email_templates][welcome_guardian_body_en]" class="form-control text-start" rows="6" dir="ltr" placeholder="Message body"><?php echo e($emailSettings['welcome_guardian_body_en'] ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- French -->
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small text-success">Français (fr)</label>
                                                        <div class="mb-3">
                                                            <input type="text" name="settings[email_templates][welcome_guardian_subject_fr]" class="form-control text-start mb-2" value="<?php echo e($emailSettings['welcome_guardian_subject_fr'] ?? ''); ?>" placeholder="Objet">
                                                            <textarea name="settings[email_templates][welcome_guardian_body_fr]" class="form-control text-start" rows="6" dir="ltr" placeholder="Corps du message"><?php echo e($emailSettings['welcome_guardian_body_fr'] ?? ''); ?></textarea>
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
                                
                                <!-- System Notifications Sub Tab -->
                                <div class="tab-pane fade" id="system-notifs" role="tabpanel">
                                    
                                    
                                    
                                <div class="mt-5 pt-4 border-top">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-bell me-2"></i> <?php echo e(__('center::settings.email_templates.notif_title')); ?></h5>
                                    </div>
                                    <p class="text-muted small mb-4"><?php echo e(__('center::settings.email_templates.notif_desc')); ?></p>

                                    
                                    <div class="accordion" id="emailNotificationsAccordion">

                                        
                                        <div class="accordion-item border rounded-4 mb-3 overflow-hidden">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#notif_payment_reminder">
                                                    <span class="d-flex align-items-center gap-3 w-100">
                                                        <span class="rounded-circle bg-warning bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                            <i class="fas fa-clock text-warning"></i>
                                                        </span>
                                                        <span>
                                                            <span class="d-block"><?php echo e(__('center::settings.email_templates.payment_reminder')); ?></span>
                                                            <small class="text-muted fw-normal"><?php echo e(__('center::settings.email_templates.payment_reminder_desc')); ?></small>
                                                        </span>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="notif_payment_reminder" class="accordion-collapse collapse" data-bs-parent="#emailNotificationsAccordion">
                                                <div class="accordion-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fw-bold small text-muted"><?php echo e(__('center::settings.email_templates.notif_status')); ?></span>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 x-small reset-email-btn" data-subject-id="notif_payment_reminder_subject" data-body-id="notif_payment_reminder_body" data-default-subject="<?php echo e(__('center::settings.email_templates.defaults.payment_reminder_subject')); ?>" data-default-body="<?php echo e(__('center::settings.email_templates.defaults.payment_reminder_body')); ?>">
                                                                <i class="fas fa-undo"></i> <?php echo e(__('center::settings.email_templates.to_default')); ?>

                                                            </button>
                                                            <div class="form-check form-switch custom-switch mb-0">
                                                                <input type="hidden" name="settings[email_templates][notif_payment_reminder_enabled]" value="0">
                                                                <input class="form-check-input" type="checkbox" name="settings[email_templates][notif_payment_reminder_enabled]" value="1" id="notifPaymentReminder" <?php echo e(($emailSettings['notif_payment_reminder_enabled'] ?? false) ? 'checked' : ''); ?>>
                                                                <label class="form-check-label fw-bold small ms-2" for="notifPaymentReminder"><?php echo e(__('center::settings.email_templates.notif_active')); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.email_templates.subject')); ?></label>
                                                        <input type="text" id="notif_payment_reminder_subject" name="settings[email_templates][notif_payment_reminder_subject]" class="form-control bg-light border-0 rounded-3 py-2 template-input" value="<?php echo e($emailSettings['notif_payment_reminder_subject'] ?? __('center::settings.email_templates.defaults.payment_reminder_subject')); ?>">
                                                    </div>
                                                    <div class="mb-2 d-flex flex-wrap gap-1">
                                                        <?php $payVars = ['student_name' => __('center::settings.email_templates.placeholders.student_name'), 'center_name' => __('center::settings.email_templates.placeholders.center_name'), 'amount' => __('center::settings.email_templates.placeholders.amount'), 'due_date' => __('center::settings.email_templates.placeholders.due_date'), 'login_link' => __('center::settings.email_templates.placeholders.login_link')]; ?>
                                                        <?php $__currentLoopData = $payVars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <button type="button" class="btn btn-sm btn-outline-secondary border-dashed py-1 px-2 x-small var-btn" data-target="notif_payment_reminder_body" data-var="<?php echo e('{'.$k.'}'); ?>"><i class="fas fa-plus-circle me-1 opacity-50"></i> <?php echo e($l); ?></button>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.email_templates.body')); ?></label>
                                                        <textarea name="settings[email_templates][notif_payment_reminder_body]" id="notif_payment_reminder_body" class="form-control bg-light border-0 rounded-3 py-3 template-input" rows="5"><?php echo e($emailSettings['notif_payment_reminder_body'] ?? __('center::settings.email_templates.defaults.payment_reminder_body')); ?></textarea>
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
                                                            <span class="d-block"><?php echo e(__('center::settings.email_templates.group_enrollment')); ?> (Multi-Lingual)</span>
                                                            <small class="text-muted fw-normal"><?php echo e(__('center::settings.email_templates.group_enrollment_desc')); ?></small>
                                                        </span>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="notif_group_enrollment" class="accordion-collapse collapse" data-bs-parent="#emailNotificationsAccordion">
                                                <div class="accordion-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fw-bold small text-muted"><?php echo e(__('center::settings.email_templates.notif_status')); ?></span>
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="settings[email_templates][notif_group_enrollment_enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="settings[email_templates][notif_group_enrollment_enabled]" value="1" id="notifGroupEnrollment" <?php echo e(($emailSettings['notif_group_enrollment_enabled'] ?? false) ? 'checked' : ''); ?>>
                                                            <label class="form-check-label fw-bold small ms-2" for="notifGroupEnrollment"><?php echo e(__('center::settings.email_templates.notif_active')); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="row g-4">
                                                        <!-- Arabic -->
                                                        <div class="col-md-4 border-end">
                                                            <label class="form-label fw-bold small text-info">العربية (ar)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_group_enrollment_subject_ar]" class="form-control text-end mb-2" value="<?php echo e($emailSettings['notif_group_enrollment_subject_ar'] ?? $emailSettings['notif_group_enrollment_subject'] ?? ''); ?>" placeholder="الموضوع">
                                                                <textarea name="settings[email_templates][notif_group_enrollment_body_ar]" class="form-control text-end" rows="5" dir="rtl" placeholder="نص الرسالة"><?php echo e($emailSettings['notif_group_enrollment_body_ar'] ?? $emailSettings['notif_group_enrollment_body'] ?? ''); ?></textarea>
                                                            </div>
                                                        </div>
                                                        <!-- English -->
                                                        <div class="col-md-4 border-end">
                                                            <label class="form-label fw-bold small text-info">English (en)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_group_enrollment_subject_en]" class="form-control text-start mb-2" value="<?php echo e($emailSettings['notif_group_enrollment_subject_en'] ?? ''); ?>" placeholder="Subject">
                                                                <textarea name="settings[email_templates][notif_group_enrollment_body_en]" class="form-control text-start" rows="5" dir="ltr" placeholder="Message body"><?php echo e($emailSettings['notif_group_enrollment_body_en'] ?? ''); ?></textarea>
                                                            </div>
                                                        </div>
                                                        <!-- French -->
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold small text-info">Français (fr)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_group_enrollment_subject_fr]" class="form-control text-start mb-2" value="<?php echo e($emailSettings['notif_group_enrollment_subject_fr'] ?? ''); ?>" placeholder="Objet">
                                                                <textarea name="settings[email_templates][notif_group_enrollment_body_fr]" class="form-control text-start" rows="5" dir="ltr" placeholder="Corps du message"><?php echo e($emailSettings['notif_group_enrollment_body_fr'] ?? ''); ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 pt-3 border-top">
                                                        <small class="fw-bold text-muted d-block mb-2"><?php echo e(__('center::settings.email_templates.placeholders_title')); ?></small>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            <?php $grpVars = ['student_name' => __('center::settings.email_templates.placeholders.student_name'), 'center_name' => __('center::settings.email_templates.placeholders.center_name'), 'group_name' => __('center::settings.email_templates.placeholders.group_name'), 'course_price' => __('center::settings.email_templates.placeholders.course_price'), 'login_link' => __('center::settings.email_templates.placeholders.login_link')]; ?>
                                                            <?php $__currentLoopData = $grpVars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <span class="badge bg-light text-dark border py-2 px-3 rounded-pill small"><?php echo e('{'.$k.'}'); ?> : <?php echo e($l); ?></span>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
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
                                                            <span class="d-block"><?php echo e(__('center::settings.email_templates.payment_confirmation')); ?> (Multi-Lingual)</span>
                                                            <small class="text-muted fw-normal"><?php echo e(__('center::settings.email_templates.payment_confirmation_desc')); ?></small>
                                                        </span>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="notif_payment_confirmed" class="accordion-collapse collapse" data-bs-parent="#emailNotificationsAccordion">
                                                <div class="accordion-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fw-bold small text-muted"><?php echo e(__('center::settings.email_templates.notif_status')); ?></span>
                                                        <div class="form-check form-switch custom-switch mb-0">
                                                            <input type="hidden" name="settings[email_templates][notif_payment_confirmed_enabled]" value="0">
                                                            <input class="form-check-input" type="checkbox" name="settings[email_templates][notif_payment_confirmed_enabled]" value="1" id="notifPaymentConfirmed" <?php echo e(($emailSettings['notif_payment_confirmed_enabled'] ?? false) ? 'checked' : ''); ?>>
                                                            <label class="form-check-label fw-bold small ms-2" for="notifPaymentConfirmed"><?php echo e(__('center::settings.email_templates.notif_active')); ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="row g-4">
                                                        <!-- Arabic -->
                                                        <div class="col-md-4 border-end">
                                                            <label class="form-label fw-bold small text-success">العربية (ar)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_payment_confirmed_subject_ar]" class="form-control text-end mb-2" value="<?php echo e($emailSettings['notif_payment_confirmed_subject_ar'] ?? $emailSettings['notif_payment_confirmed_subject'] ?? ''); ?>" placeholder="الموضوع">
                                                                <textarea name="settings[email_templates][notif_payment_confirmed_body_ar]" class="form-control text-end" rows="5" dir="rtl" placeholder="نص الرسالة"><?php echo e($emailSettings['notif_payment_confirmed_body_ar'] ?? $emailSettings['notif_payment_confirmed_body'] ?? ''); ?></textarea>
                                                            </div>
                                                        </div>
                                                        <!-- English -->
                                                        <div class="col-md-4 border-end">
                                                            <label class="form-label fw-bold small text-success">English (en)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_payment_confirmed_subject_en]" class="form-control text-start mb-2" value="<?php echo e($emailSettings['notif_payment_confirmed_subject_en'] ?? ''); ?>" placeholder="Subject">
                                                                <textarea name="settings[email_templates][notif_payment_confirmed_body_en]" class="form-control text-start" rows="5" dir="ltr" placeholder="Message body"><?php echo e($emailSettings['notif_payment_confirmed_body_en'] ?? ''); ?></textarea>
                                                            </div>
                                                        </div>
                                                        <!-- French -->
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold small text-success">Français (fr)</label>
                                                            <div class="mb-3">
                                                                <input type="text" name="settings[email_templates][notif_payment_confirmed_subject_fr]" class="form-control text-start mb-2" value="<?php echo e($emailSettings['notif_payment_confirmed_subject_fr'] ?? ''); ?>" placeholder="Objet">
                                                                <textarea name="settings[email_templates][notif_payment_confirmed_body_fr]" class="form-control text-start" rows="5" dir="ltr" placeholder="Corps du message"><?php echo e($emailSettings['notif_payment_confirmed_body_fr'] ?? ''); ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 pt-3 border-top">
                                                        <small class="fw-bold text-muted d-block mb-2"><?php echo e(__('center::settings.email_templates.placeholders_title')); ?></small>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            <?php $confVars = ['student_name' => __('center::settings.email_templates.placeholders.student_name'), 'center_name' => __('center::settings.email_templates.placeholders.center_name'), 'amount_paid' => __('center::settings.email_templates.placeholders.amount_paid'), 'payment_date' => __('center::settings.email_templates.placeholders.payment_date'), 'المتبقي' => __('center::settings.email_templates.placeholders.remaining'), 'payment_method' => __('center::settings.email_templates.placeholders.payment_method')]; ?>
                                                            <?php $__currentLoopData = $confVars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <span class="badge bg-light text-dark border py-2 px-3 rounded-pill small"><?php echo e('{'.$k.'}'); ?> : <?php echo e($l); ?></span>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="text-start mt-4 pt-3 border-top d-flex gap-2">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i> <?php echo e(__('center::settings.email_templates.save')); ?>

                                    </button>
                                </div>
                            </form>
                            
                            <form action="<?php echo e(route('center.settings.reset-email-templates', ['tenant' => $tenant->domain ?? 'center'])); ?>" method="POST" class="d-inline-block mt-3" onsubmit="return confirm('<?php echo e(__('center::settings.email_templates.confirm_reset')); ?>');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-light text-danger rounded-pill px-4 fw-bold shadow-sm border">
                                    <i class="fas fa-undo me-2"></i> <?php echo e(__('center::settings.email_templates.reset')); ?>

                                </button>
                                                        </form>
                                </div> <!-- Close system-notifs sub tab -->
                                
                                <!-- Payment Reminders Sub Tab -->
                                <div class="tab-pane fade" id="payment-reminders" role="tabpanel">
                                    <?php
                                        $reminderPresets = __('center::settings.reminders.presets_data');
                                        if (!is_array($reminderPresets)) {
                                            $reminderPresets = [
                                                'email' => ['formal' => '', 'friendly' => '', 'urgent' => ''],
                                                'whatsapp' => ['formal' => '', 'friendly' => '', 'urgent' => '']
                                            ];
                                        }
                                    ?>
                                    <!-- Payment Reminder Scheduling -->
                                    <div class="mb-4 mt-2">
                                        <h4 class="fw-bold text-warning"><i class="fas fa-calendar-check me-2"></i> <?php echo e(__('center::settings.tabs.reminders')); ?></h4>
                                    </div>
                            <?php
                                $reminderSettings = $tenant->settings['payment_reminders'] ?? [];
                                $defaultDueDay = $reminderSettings['default_due_day'] ?? 1;
                                $defaultMonthlyFee = $reminderSettings['default_monthly_fee'] ?? '';
                                $emailReminders = $reminderSettings['email_reminders'] ?? [
                                    ['days_before' => 7, 'enabled' => true],
                                    ['days_before' => 3, 'enabled' => true],
                                    ['days_before' => 1, 'enabled' => true],
                                ];
                                $whatsappReminders = $reminderSettings['whatsapp_reminders'] ?? [
                                    ['days_after' => 1, 'enabled' => true],
                                    ['days_after' => 3, 'enabled' => true],
                                    ['days_after' => 7, 'enabled' => true],
                                ];
                                $whatsappBeforeDue = $reminderSettings['whatsapp_before_due'] ?? false;
                                $overdueRepeatEnabled = $reminderSettings['overdue_repeat_enabled'] ?? false;
                                $overdueRepeatInterval = $reminderSettings['overdue_repeat_interval'] ?? 7;
                                $overdueMaxReminders = $reminderSettings['overdue_max_reminders'] ?? '';
                                $emailTemplate = $reminderSettings['email_template'] ?? '';
                                $whatsappTemplate = $reminderSettings['whatsapp_template'] ?? '';
                                $currency = $tenant->settings['financial']['currency'] ?? 'EGP';
                            ?>

                            <form action="<?php echo e(route('center.settings.update-reminders', ['tenant' => $tenant->domain ?? 'center'])); ?>" method="POST">
                                <?php echo csrf_field(); ?>

                                <!-- Info Banner -->
                                <div class="alert border-0 rounded-4 mb-4" style="background: linear-gradient(135deg, #fef3cd 0%, #ffeaa7 100%); border-left: 4px solid #f39c12 !important;">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(243,156,18,0.15);">
                                            <i class="fas fa-robot text-warning fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1 text-dark"><i class="fas fa-info-circle me-1 text-warning"></i> <?php echo e(__('center::settings.reminders.title')); ?></h6>
                                            <p class="small mb-0 text-dark opacity-75"><?php echo e(__('center::settings.reminders.info_banner')); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- ====== Section 1: Default Settings ====== -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold text-dark mb-3">
                                            <i class="fas fa-cog me-2 text-primary"></i> <?php echo e(__('center::settings.reminders.default_settings')); ?>

                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.reminders.default_due_day')); ?></label>
                                                <select name="default_due_day" class="form-select rounded-3" id="reminderDueDay">
                                                    <?php for($d = 1; $d <= 28; $d++): ?>
                                                        <option value="<?php echo e($d); ?>" <?php echo e($defaultDueDay == $d ? 'selected' : ''); ?>><?php echo e($d); ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                                <small class="text-muted"><?php echo e(__('center::settings.reminders.default_due_day_hint')); ?></small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.reminders.default_monthly_fee')); ?> (<?php echo e($currency); ?>)</label>
                                                <input type="number" name="default_monthly_fee" class="form-control rounded-3" value="<?php echo e($defaultMonthlyFee); ?>" min="0" step="0.01" placeholder="0.00">
                                                <small class="text-muted"><?php echo e(__('center::settings.reminders.default_monthly_fee_hint')); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ====== Section 2: Pre-Due Email Reminders ====== -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-1">
                                                    <i class="fas fa-envelope me-2 text-info"></i> <?php echo e(__('center::settings.reminders.pre_due_title')); ?>

                                                </h6>
                                                <p class="small text-muted mb-0"><?php echo e(__('center::settings.reminders.pre_due_desc')); ?></p>
                                            </div>
                                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 fw-bold">
                                                <i class="fas fa-envelope me-1"></i> <?php echo e(__('center::settings.reminders.channel_email')); ?>

                                            </span>
                                        </div>

                                        <div class="reminder-timeline position-relative" style="padding-right: 20px;">
                                            <?php $__currentLoopData = $emailReminders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $reminder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 border <?php echo e($reminder['enabled'] ? 'active-reminder-info' : 'bg-light'); ?> transition-all" id="preReminder<?php echo e($index); ?>">
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="email_reminders[<?php echo e($index); ?>][enabled]" value="0">
                                                        <input class="form-check-input" type="checkbox" name="email_reminders[<?php echo e($index); ?>][enabled]" value="1" id="emailReminderToggle<?php echo e($index); ?>" <?php echo e($reminder['enabled'] ? 'checked' : ''); ?> style="width: 3em; height: 1.5em;" onchange="toggleReminderStyle(this, 'preReminder<?php echo e($index); ?>', 'active-reminder-info')">
                                                    </div>
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background: <?php echo e($reminder['enabled'] ? 'linear-gradient(135deg, #00b4d8, #0077b6)' : '#dee2e6'); ?>;">
                                                        <i class="fas fa-bell text-white"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold small">
                                                            <?php if($reminder['days_before'] == 0): ?>
                                                                <?php echo e(__('center::settings.reminders.on_due_day')); ?>

                                                            <?php else: ?>
                                                                <?php echo e(str_replace(':days', $reminder['days_before'], __('center::settings.reminders.days_before_due'))); ?>

                                                            <?php endif; ?>
                                                        </div>
                                                        <small class="text-muted"><?php echo e(__('center::settings.email_templates.payment_reminder')); ?></small>
                                                    </div>
                                                    <div style="width: 100px;">
                                                        <input type="number" name="email_reminders[<?php echo e($index); ?>][days_before]" class="form-control form-control-sm rounded-pill text-center fw-bold" value="<?php echo e($reminder['days_before']); ?>" min="0" max="30">
                                                        <small class="text-muted d-block text-center"><?php echo e(__('center::settings.reminders.days_before_due', ['days' => ''])); ?></small>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>

                                        <!-- Optional: WhatsApp before due -->
                                        <div class="mt-3 p-3 rounded-3 border bg-light">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="hidden" name="whatsapp_before_due" value="0">
                                                <input class="form-check-input" type="checkbox" name="whatsapp_before_due" value="1" id="whatsappBeforeDue" <?php echo e($whatsappBeforeDue ? 'checked' : ''); ?> style="width: 1.3em; height: 1.3em;">
                                                <label class="form-check-label fw-bold small" for="whatsappBeforeDue">
                                                    <i class="fab fa-whatsapp text-success me-1"></i> <?php echo e(__('center::settings.reminders.whatsapp_before_due')); ?>

                                                </label>
                                            </div>
                                            <small class="text-muted d-block mt-1 ms-4"><?php echo e(__('center::settings.reminders.whatsapp_before_due_warning')); ?></small>
                                        </div>
                                    </div>
                                </div>

                                <!-- ====== Section 3: Post-Due Reminders (Email + WhatsApp) ====== -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-1">
                                                    <i class="fas fa-exclamation-triangle me-2 text-danger"></i> <?php echo e(__('center::settings.reminders.post_due_title')); ?>

                                                </h6>
                                                <p class="small text-muted mb-0"><?php echo e(__('center::settings.reminders.post_due_desc')); ?></p>
                                            </div>
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-bold">
                                                <i class="fas fa-envelope me-1"></i> + <i class="fab fa-whatsapp me-1"></i> <?php echo e(__('center::settings.reminders.channel_both')); ?>

                                            </span>
                                        </div>

                                        <div class="reminder-timeline position-relative" style="padding-right: 20px;">
                                            <?php $__currentLoopData = $whatsappReminders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $reminder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 border <?php echo e($reminder['enabled'] ? 'active-reminder-danger' : 'bg-light'); ?> transition-all" id="postReminder<?php echo e($index); ?>">
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="whatsapp_reminders[<?php echo e($index); ?>][enabled]" value="0">
                                                        <input class="form-check-input" type="checkbox" name="whatsapp_reminders[<?php echo e($index); ?>][enabled]" value="1" id="whatsappReminderToggle<?php echo e($index); ?>" <?php echo e($reminder['enabled'] ? 'checked' : ''); ?> style="width: 3em; height: 1.5em;" onchange="toggleReminderStyle(this, 'postReminder<?php echo e($index); ?>', 'active-reminder-danger')">
                                                    </div>
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background: <?php echo e($reminder['enabled'] ? 'linear-gradient(135deg, #e74c3c, #c0392b)' : '#dee2e6'); ?>;">
                                                        <i class="fab fa-whatsapp text-white"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold small">
                                                            <?php echo e(str_replace(':days', $reminder['days_after'], __('center::settings.reminders.days_after_due'))); ?>

                                                        </div>
                                                        <small class="text-muted">
                                                            <i class="fas fa-envelope me-1"></i> <?php echo e(__('center::settings.reminders.channel_email')); ?>

                                                            +
                                                            <i class="fab fa-whatsapp me-1"></i> <?php echo e(__('center::settings.reminders.channel_whatsapp')); ?>

                                                        </small>
                                                    </div>
                                                    <div style="width: 100px;">
                                                        <input type="number" name="whatsapp_reminders[<?php echo e($index); ?>][days_after]" class="form-control form-control-sm rounded-pill text-center fw-bold" value="<?php echo e($reminder['days_after']); ?>" min="1" max="60">
                                                        <small class="text-muted d-block text-center"><?php echo e(__('center::settings.reminders.days_after_due', ['days' => ''])); ?></small>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- ====== Section 4: Auto-Repeat for Overdue ====== -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start gap-3 mb-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: linear-gradient(135deg, #6c5ce7, #a29bfe);">
                                                <i class="fas fa-sync-alt text-white fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold text-dark mb-1"><?php echo e(__('center::settings.reminders.overdue_auto_title')); ?></h6>
                                                <p class="small text-muted mb-0"><?php echo e(__('center::settings.reminders.overdue_auto_desc')); ?></p>
                                            </div>
                                        </div>

                                        <div class="p-3 rounded-3 border bg-light mb-3">
                                            <div class="form-check form-switch d-flex align-items-center gap-2">
                                                <input type="hidden" name="overdue_repeat_enabled" value="0">
                                                <input class="form-check-input" type="checkbox" name="overdue_repeat_enabled" value="1" id="overdueRepeatEnabled" <?php echo e($overdueRepeatEnabled ? 'checked' : ''); ?> style="width: 3em; height: 1.5em;" onchange="toggleOverdueSettings(this)">
                                                <label class="form-check-label fw-bold" for="overdueRepeatEnabled">
                                                    <?php echo e(__('center::settings.reminders.overdue_repeat_enabled')); ?>

                                                </label>
                                            </div>
                                        </div>

                                        <div id="overdueSettingsPanel" class="<?php echo e($overdueRepeatEnabled ? '' : 'd-none'); ?>">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.reminders.overdue_repeat_interval')); ?></label>
                                                    <div class="input-group">
                                                        <input type="number" name="overdue_repeat_interval" class="form-control rounded-3" value="<?php echo e($overdueRepeatInterval); ?>" min="1" max="30">
                                                        <span class="input-group-text bg-white rounded-3"><i class="fas fa-calendar-day text-primary"></i></span>
                                                    </div>
                                                    <small class="text-muted"><?php echo e(__('center::settings.reminders.overdue_repeat_interval_hint')); ?></small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.reminders.overdue_max_reminders')); ?></label>
                                                    <div class="input-group">
                                                        <input type="number" name="overdue_max_reminders" class="form-control rounded-3" value="<?php echo e($overdueMaxReminders); ?>" min="1" max="50" placeholder="∞">
                                                        <span class="input-group-text bg-white rounded-3"><i class="fas fa-hashtag text-primary"></i></span>
                                                    </div>
                                                    <small class="text-muted"><?php echo e(__('center::settings.reminders.overdue_max_reminders_hint')); ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ====== Section 5: Message Templates ====== -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold text-dark mb-3">
                                            <i class="fas fa-file-alt me-2 text-success"></i> <?php echo e(__('center::settings.reminders.email_template')); ?>

                                        </h6>

                                        <div class="mb-3">
                                            <div class="alert alert-light rounded-3 border mb-2 p-3">
                                                <div class="row g-3">
                                                    <div class="col-md-7 border-end">
                                                        <small class="fw-bold text-muted d-block mb-1"><?php echo e(__('center::settings.reminders.template_variables')); ?></small>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            <?php
                                                                $remVars = [
                                                                    'student_name' => __('center::settings.email_templates.placeholders.student_name'),
                                                                    'center_name' => __('center::settings.email_templates.placeholders.center_name'),
                                                                    'amount' => __('center::settings.email_templates.placeholders.amount'),
                                                                    'due_date' => __('center::settings.email_templates.placeholders.due_date'),
                                                                    'remaining' => __('center::settings.email_templates.placeholders.remaining'),
                                                                    'group_name' => __('center::settings.email_templates.placeholders.group_name'),
                                                                    'course_price' => __('center::settings.email_templates.placeholders.course_price'),
                                                                    'login_link' => __('center::settings.email_templates.placeholders.login_link'),
                                                                    'password' => __('center::settings.email_templates.placeholders.password'),
                                                                ];
                                                            ?>
                                                            <?php $__currentLoopData = $remVars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <button type="button" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-0 small" onclick="insertVariable(this, 'emailTemplateArea')" data-var="<?php echo e('{' . $key . '}'); ?>"><?php echo e($label); ?></button>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <small class="fw-bold text-muted d-block mb-1"><?php echo e(__('center::settings.reminders.quick_templates_email')); ?></small>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 small rounded-pill" onclick="fillPreset('emailTemplateArea', `<?php echo e($reminderPresets['email']['formal']); ?>`)"><?php echo e(__('center::settings.reminders.presets.formal')); ?></button>
                                                            <button type="button" class="btn btn-xs btn-outline-success py-0 px-2 small rounded-pill" onclick="fillPreset('emailTemplateArea', `<?php echo e($reminderPresets['email']['friendly']); ?>`)"><?php echo e(__('center::settings.reminders.presets.friendly')); ?></button>
                                                            <button type="button" class="btn btn-xs btn-outline-danger py-0 px-2 small rounded-pill" onclick="fillPreset('emailTemplateArea', `<?php echo e($reminderPresets['email']['urgent']); ?>`)"><?php echo e(__('center::settings.reminders.presets.urgent')); ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <textarea name="email_template" id="emailTemplateArea" class="form-control rounded-3" rows="5" dir="auto" placeholder="<?php echo e(__('center::settings.email_templates.defaults.payment_reminder_body')); ?>"><?php echo e($emailTemplate); ?></textarea>
                                        </div>

                                        <hr>

                                        <h6 class="fw-bold text-dark mb-3 mt-3">
                                            <i class="fab fa-whatsapp me-2 text-success"></i> <?php echo e(__('center::settings.reminders.whatsapp_template')); ?>

                                        </h6>

                                        <div class="mb-3">
                                            <div class="alert alert-light rounded-3 border mb-2 p-3">
                                                <div class="row g-3">
                                                    <div class="col-md-7 border-end">
                                                        <small class="fw-bold text-muted d-block mb-1"><?php echo e(__('center::settings.reminders.template_variables')); ?></small>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            <?php
                                                                $waVars = [
                                                                    'student_name' => __('center::settings.email_templates.placeholders.student_name'),
                                                                    'center_name' => __('center::settings.email_templates.placeholders.center_name'),
                                                                    'amount' => __('center::settings.email_templates.placeholders.amount'),
                                                                    'due_date' => __('center::settings.email_templates.placeholders.due_date'),
                                                                    'remaining' => __('center::settings.email_templates.placeholders.remaining'),
                                                                ];
                                                            ?>
                                                            <?php $__currentLoopData = $waVars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <button type="button" class="btn btn-xs btn-outline-success rounded-pill px-2 py-0 small" onclick="insertVariable(this, 'whatsappTemplateArea')" data-var="<?php echo e('{' . $key . '}'); ?>"><?php echo e($label); ?></button>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <small class="fw-bold text-muted d-block mb-1"><?php echo e(__('center::settings.reminders.quick_templates_whatsapp')); ?></small>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 small rounded-pill" onclick="fillPreset('whatsappTemplateArea', `<?php echo e($reminderPresets['whatsapp']['formal']); ?>`)"><?php echo e(__('center::settings.reminders.presets.formal')); ?></button>
                                                            <button type="button" class="btn btn-xs btn-outline-success py-0 px-2 small rounded-pill" onclick="fillPreset('whatsappTemplateArea', `<?php echo e($reminderPresets['whatsapp']['friendly']); ?>`)"><?php echo e(__('center::settings.reminders.presets.friendly')); ?></button>
                                                            <button type="button" class="btn btn-xs btn-outline-danger py-0 px-2 small rounded-pill" onclick="fillPreset('whatsappTemplateArea', `<?php echo e($reminderPresets['whatsapp']['urgent']); ?>`)"><?php echo e(__('center::settings.reminders.presets.urgent')); ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <textarea name="whatsapp_template" id="whatsappTemplateArea" class="form-control rounded-3" rows="4" dir="auto" placeholder="<?php echo e(__('center::settings.reminders.whatsapp_placeholder')); ?>"><?php echo e($whatsappTemplate); ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- ====== Visual Timeline Preview ====== -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold text-dark mb-3">
                                            <i class="fas fa-stream me-2 text-primary"></i> <?php echo e(__('center::settings.reminders.timeline_preview')); ?>

                                        </h6>
                                        <div class="position-relative" style="padding-right: 30px;">
                                            <div class="position-absolute" style="right: 14px; top: 0; bottom: 0; width: 3px; background: linear-gradient(to bottom, #00b4d8, #f39c12, #e74c3c); border-radius: 2px;"></div>

                                            <?php $__currentLoopData = $emailReminders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($r['enabled']): ?>
                                                    <div class="d-flex align-items-center gap-3 mb-2">
                                                        <div class="rounded-circle bg-info flex-shrink-0" style="width: 12px; height: 12px; position: relative; right: -22px; z-index: 1;"></div>
                                                        <div class="flex-grow-1 ps-3">
                                                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 small fw-bold">
                                                                <i class="fas fa-envelope me-1"></i>
                                                                <?php if($r['days_before'] == 0): ?>
                                                                    <?php echo e(__('center::settings.reminders.on_due_day')); ?>

                                                                <?php else: ?>
                                                                    <?php echo e(str_replace(':days', $r['days_before'], __('center::settings.reminders.days_before_due'))); ?>

                                                                <?php endif; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <div class="rounded-circle bg-warning flex-shrink-0" style="width: 16px; height: 16px; position: relative; right: -20px; z-index: 1; border: 2px solid #fff;"></div>
                                                <div class="flex-grow-1 ps-3">
                                                    <span class="badge bg-warning bg-opacity-25 text-dark rounded-pill px-3 py-2 small fw-bold">
                                                        <i class="fas fa-calendar-day me-1"></i> <?php echo e(__('center::settings.reminders.on_due_day')); ?> (<?php echo e(__('center::settings.reminders.default_due_day')); ?>: <?php echo e($defaultDueDay); ?>)
                                                    </span>
                                                </div>
                                            </div>

                                            <?php $__currentLoopData = $whatsappReminders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($r['enabled']): ?>
                                                    <div class="d-flex align-items-center gap-3 mb-2">
                                                        <div class="rounded-circle bg-danger flex-shrink-0" style="width: 12px; height: 12px; position: relative; right: -22px; z-index: 1;"></div>
                                                        <div class="flex-grow-1 ps-3">
                                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 small fw-bold">
                                                                <i class="fab fa-whatsapp me-1"></i>
                                                                <?php echo e(str_replace(':days', $r['days_after'], __('center::settings.reminders.days_after_due'))); ?>

                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            <?php if($overdueRepeatEnabled): ?>
                                                <div class="d-flex align-items-center gap-3 mb-2">
                                                    <div class="rounded-circle flex-shrink-0" style="width: 12px; height: 12px; position: relative; right: -22px; z-index: 1; background: #6c5ce7;"></div>
                                                    <div class="flex-grow-1 ps-3">
                                                        <span class="badge bg-opacity-10 text-dark rounded-pill px-3 py-2 small fw-bold" style="background: rgba(108,92,231,0.1);">
                                                            <i class="fas fa-sync-alt me-1" style="color: #6c5ce7;"></i>
                                                            <?php echo e(__('center::settings.reminders.overdue_repeat_interval')); ?>: <?php echo e($overdueRepeatInterval); ?>

                                                            <?php if($overdueMaxReminders): ?>
                                                                (<?php echo e(__('center::settings.reminders.overdue_max_reminders')); ?>: <?php echo e($overdueMaxReminders); ?>)
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-lg rounded-pill px-5 fw-bold shadow-sm" style="background: linear-gradient(135deg, #f39c12, #e67e22); color: #fff; border: none;">
                                        <i class="fas fa-save me-2"></i> <?php echo e(__('center::settings.reminders.save_settings')); ?>

                                    </button>
                                </div>
                                                        </form>
                                </div> <!-- Close payment-reminders sub tab -->
                            </div> <!-- Close tab-content -->
                        </div>

                        <!-- Academic Settings -->
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
                                                        <option value="<?php echo e($key); ?>"><?php echo e(__($template['name'])); ?></option>
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
                                        <input class="form-check-input" type="checkbox" name="settings[academic][attendance_alert]" value="1" id="attendanceAlert" <?php echo e(($tenant->settings['academic']['attendance_alert'] ?? false) ? 'checked' : ''); ?>>
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

                        <!-- Financial Settings -->
                        <div class="tab-pane fade <?php echo e($activeTab == 'financial' ? 'show active' : ''); ?>" id="financial" role="tabpanel" aria-labelledby="financial-tab">
                            <form action="<?php echo e(route('center.settings.update', ['tenant' => $tenant->domain ?? 'center'])); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <h6 class="fw-bold text-primary mb-3"><?php echo e(__('center::settings.financial.title')); ?></h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.financial.currency')); ?></label>
                                    <select name="settings[financial][currency]" class="form-select">
                                        <option value="EGP" <?php echo e(($tenant->settings['financial']['currency'] ?? '') == 'EGP' ? 'selected' : ''); ?>><?php echo e(__('center::settings.financial.currencies.egp')); ?></option>
                                        <option value="SAR" <?php echo e(($tenant->settings['financial']['currency'] ?? '') == 'SAR' ? 'selected' : ''); ?>><?php echo e(__('center::settings.financial.currencies.sar')); ?></option>
                                        <option value="USD" <?php echo e(($tenant->settings['financial']['currency'] ?? '') == 'USD' ? 'selected' : ''); ?>><?php echo e(__('center::settings.financial.currencies.usd')); ?></option>
                                        <option value="EUR" <?php echo e(($tenant->settings['financial']['currency'] ?? '') == 'EUR' ? 'selected' : ''); ?>><?php echo e(__('center::settings.financial.currencies.eur')); ?></option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.financial.tax_rate')); ?></label>
                                    <input type="number" name="settings[financial][tax_rate]" class="form-control" value="<?php echo e($tenant->settings['financial']['tax_rate'] ?? '0'); ?>" min="0" max="100">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.financial.invoice_prefix')); ?></label>
                                    <input type="text" name="settings[financial][invoice_prefix]" class="form-control" value="<?php echo e($tenant->settings['financial']['invoice_prefix'] ?? 'INV-'); ?>" placeholder="INV-">
                                </div>
                                </div>
                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> <?php echo e(__('center::settings.general.save')); ?>

                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Appearance Settings -->
                        <div class="tab-pane fade <?php echo e($activeTab == 'appearance' ? 'show active' : ''); ?>" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
                            <form action="<?php echo e(route('center.settings.update', ['tenant' => $tenant->domain ?? 'center'])); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <h6 class="fw-bold text-primary mb-3"><?php echo e(__('center::settings.appearance.title')); ?></h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.appearance.primary_color')); ?></label>
                                    <input type="color" name="settings[appearance][primary_color]" class="form-control form-control-color w-100" value="<?php echo e($tenant->settings['appearance']['primary_color'] ?? '#10b981'); ?>">
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch mt-3">
                                        <input type="hidden" name="settings[appearance][dark_mode]" value="0">
                                        <input class="form-check-input" type="checkbox" name="settings[appearance][dark_mode]" value="1" id="darkMode" <?php echo e(($tenant->settings['appearance']['dark_mode'] ?? false) ? 'checked' : ''); ?>>
                                        <label class="form-check-label user-select-none" for="darkMode"><?php echo e(__('center::settings.appearance.dark_mode')); ?></label>
                                    </div>
                                </div>
                                </div>
                                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> <?php echo e(__('center::settings.general.save')); ?>

                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- WhatsApp Settings -->
                        <div class="tab-pane fade <?php echo e($activeTab == 'whatsapp' ? 'show active' : ''); ?>" id="whatsapp" role="tabpanel" aria-labelledby="whatsapp-tab">
                            <form action="<?php echo e(route('center.settings.update', ['tenant' => $tenant->domain ?? 'center'])); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                                        <i class="fab fa-whatsapp fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1"><?php echo e(__('center::settings.whatsapp.title')); ?></h5>
                                        <p class="text-muted small mb-0"><?php echo e(__('center::settings.whatsapp.desc')); ?></p>
                                    </div>
                                </div>

                                <div class="card border bg-light shadow-none mb-4">
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-4">
                                            <input type="hidden" name="settings[whatsapp][enabled]" value="0">
                                            <input class="form-check-input" type="checkbox" name="settings[whatsapp][enabled]" value="1" id="whatsappEnabled" <?php echo e(($tenant->settings['whatsapp']['enabled'] ?? false) ? 'checked' : ''); ?>>
                                            <label class="form-check-label fw-bold" for="whatsappEnabled"><?php echo e(__('center::settings.whatsapp.enabled')); ?></label>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">Phone Number ID</label>
                                                <input type="text" name="settings[whatsapp][phone_number_id]" class="form-control" value="<?php echo e($tenant->settings['whatsapp']['phone_number_id'] ?? ''); ?>" placeholder="1234567890">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small text-muted">Access Token</label>
                                                <input type="password" name="settings[whatsapp][access_token]" class="form-control" value="<?php echo e($tenant->settings['whatsapp']['access_token'] ?? ''); ?>" placeholder="EAAG...">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label fw-bold small text-muted"><i class="fas fa-globe me-1"></i> <?php echo e(__('center::settings.whatsapp.default_country_code')); ?></label>
                                                <select name="settings[whatsapp][country_code]" class="form-select">
                                                    <?php $cc = $tenant->settings['whatsapp']['country_code'] ?? '20'; ?>
                                                    <option value="20"  <?php echo e($cc == '20'  ? 'selected' : ''); ?>>🇪🇬 (+20)</option>
                                                    <option value="966" <?php echo e($cc == '966' ? 'selected' : ''); ?>>🇸🇦 (+966)</option>
                                                </select>
                                                <small class="text-muted"><?php echo e(__('center::settings.whatsapp.default_country_code_help')); ?></small>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label fw-bold small text-muted">API Version</label>
                                                <input type="text" name="settings[whatsapp][api_version]" class="form-control" value="<?php echo e($tenant->settings['whatsapp']['api_version'] ?? 'v21.0'); ?>" placeholder="v21.0">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-success border-0 rounded-4 bg-opacity-10 py-3">
                                    <h6 class="fw-bold"><i class="fas fa-lightbulb me-2 text-success"></i> WhatsApp Official Setup</h6>
                                    <p class="small mb-0 mt-2">
                                        <?php echo e(__('center::settings.whatsapp.official_note') ?? 'Ensure templates are approved in Meta Business Suite.'); ?>

                                    </p>
                                </div>
                                <div class="alert alert-info border-0 rounded-4">
                                    <h6 class="fw-bold"><i class="fas fa-lightbulb me-2"></i><?php echo e(__('center::settings.whatsapp.info_title')); ?></h6>
                                    <ul class="small mb-0 mt-2">
                                        <li><strong><?php echo e(__('center::settings.whatsapp.attendance_msg')); ?></strong></li>
                                        <li><strong><?php echo e(__('center::settings.whatsapp.payment_msg')); ?></strong></li>
                                    </ul>
                                </div>


                                <h5 class="fw-bold mt-4 mb-3"><i class="fas fa-language me-2 text-primary"></i> قوالب الرسائل متعددة اللغات</h5>
                                <p class="text-muted small">يمكنك تخصيص رسائل الواتساب لكل لغة. اترك الحقل فارغاً لاستخدام النص الافتراضي للنظام.</p>
                                
                                <!-- Attendance Templates -->
                                <div class="card border bg-white shadow-sm mb-3">
                                    <div class="card-header bg-light fw-bold py-2">
                                        <i class="fas fa-user-check text-success me-2"></i> إشعار الحضور (Attendance)
                                        <div class="small fw-normal text-muted mt-1" dir="ltr" style="text-align: right;">Variables: :student_name, :course_name, :tenant_name</div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">العربية (ar)</label>
                                                <textarea name="settings[whatsapp][attendance_template_ar]" class="form-control text-end" rows="3" dir="rtl"><?php echo e($tenant->settings['whatsapp']['attendance_template_ar'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">English (en)</label>
                                                <textarea name="settings[whatsapp][attendance_template_en]" class="form-control text-start" rows="3" dir="ltr"><?php echo e($tenant->settings['whatsapp']['attendance_template_en'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">Français (fr)</label>
                                                <textarea name="settings[whatsapp][attendance_template_fr]" class="form-control text-start" rows="3" dir="ltr"><?php echo e($tenant->settings['whatsapp']['attendance_template_fr'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Templates -->
                                <div class="card border bg-white shadow-sm mb-3">
                                    <div class="card-header bg-light fw-bold py-2">
                                        <i class="fas fa-money-bill-wave text-success me-2"></i> إشعار الدفع (Payment)
                                        <div class="small fw-normal text-muted mt-1" dir="ltr" style="text-align: right;">Variables: :amount, :currency, :student_name, :remaining, :tenant_name</div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">العربية (ar)</label>
                                                <textarea name="settings[whatsapp][payment_template_ar]" class="form-control text-end" rows="3" dir="rtl"><?php echo e($tenant->settings['whatsapp']['payment_template_ar'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">English (en)</label>
                                                <textarea name="settings[whatsapp][payment_template_en]" class="form-control text-start" rows="3" dir="ltr"><?php echo e($tenant->settings['whatsapp']['payment_template_en'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">Français (fr)</label>
                                                <textarea name="settings[whatsapp][payment_template_fr]" class="form-control text-start" rows="3" dir="ltr"><?php echo e($tenant->settings['whatsapp']['payment_template_fr'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Debt Reminder Templates -->
                                <div class="card border bg-white shadow-sm mb-4">
                                    <div class="card-header bg-light fw-bold py-2">
                                        <i class="fas fa-exclamation-circle text-danger me-2"></i> تذكير بالديون (Debt Reminder)
                                        <div class="small fw-normal text-muted mt-1" dir="ltr" style="text-align: right;">Variables: :amount, :currency, :student_name, :tenant_name</div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">العربية (ar)</label>
                                                <textarea name="settings[whatsapp][debt_template_ar]" class="form-control text-end" rows="3" dir="rtl"><?php echo e($tenant->settings['whatsapp']['debt_template_ar'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">English (en)</label>
                                                <textarea name="settings[whatsapp][debt_template_en]" class="form-control text-start" rows="3" dir="ltr"><?php echo e($tenant->settings['whatsapp']['debt_template_en'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold small text-muted">Français (fr)</label>
                                                <textarea name="settings[whatsapp][debt_template_fr]" class="form-control text-start" rows="3" dir="ltr"><?php echo e($tenant->settings['whatsapp']['debt_template_fr'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> <?php echo e(__('center::settings.general.save')); ?>

                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- Privacy & GDPR Settings -->
                        <div class="tab-pane fade <?php echo e($activeTab == 'privacy' ? 'show active' : ''); ?>" id="privacy" role="tabpanel" aria-labelledby="privacy-tab">
                            <div class="alert alert-warning border-0 rounded-4 mb-4">
                                <h6 class="fw-bold"><i class="fas fa-shield-alt me-2"></i><?php echo e(__('center::settings.privacy.title')); ?></h6>
                                <p class="small mb-0 mt-1">
                                    <?php echo e(__('center::settings.privacy.desc')); ?>

                                </p>
                            </div>

                            <!-- Data Export -->
                            <div class="card border bg-light shadow-none mb-4">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="fw-bold mb-1"><?php echo e(__('center::settings.privacy.export_title')); ?></h6>
                                            <p class="text-muted small mb-0"><?php echo e(__('center::settings.privacy.export_desc')); ?></p>
                                        </div>
                                        <a href="<?php echo e(route('gdpr.export')); ?>" class="btn btn-outline-primary rounded-pill px-4">
                                            <i class="fas fa-download me-2"></i> <?php echo e(__('center::settings.privacy.export_btn')); ?>

                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Account -->
                            <div class="card border border-danger bg-danger bg-opacity-10 shadow-none">
                                <div class="card-body p-4">
                                    <h6 class="fw-bold text-danger mb-2"><?php echo e(__('center::settings.privacy.delete_title')); ?></h6>
                                    <p class="text-secondary small mb-3">
                                        <?php echo e(__('center::settings.privacy.delete_desc')); ?>

                                    </p>
                                    
                                    <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                        <i class="fas fa-trash-alt me-2"></i> <?php echo e(__('center::settings.privacy.delete_btn')); ?>

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
                <h5 class="modal-title fw-bold text-danger"><?php echo e(__('center::settings.privacy.confirm_delete_title')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('gdpr.delete')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p class="mb-3 text-muted"><?php echo e(__('center::settings.privacy.confirm_delete_desc')); ?></p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.privacy.current_password')); ?></label>
                        <input type="password" name="password" class="form-control bg-light border-0" required placeholder="********">
                    </div>

                    <div class="form-check custom-check p-0">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="confirm_delete" id="confirmDelete" required>
                        <label class="form-check-label small user-select-none text-danger fw-bold" for="confirmDelete">
                            <?php echo e(__('center::settings.privacy.understand_checkbox')); ?>

                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal"><?php echo e(__('center::settings.privacy.cancel')); ?></button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4"><?php echo e(__('center::settings.privacy.delete_perm')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const varBtns = document.querySelectorAll('.var-btn');
    varBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const textarea = document.getElementById(targetId);
            if (!textarea) return;
            const variable = this.dataset.var;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            textarea.value = text.substring(0, start) + variable + text.substring(end);
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = start + variable.length;
            
            if (typeof updatePreview === 'function') {
                updatePreview();
            }
        });
    });

    const presetCards = document.querySelectorAll('.preset-card');
    const presets = <?php echo json_encode(__('center::settings.email_templates.presets_data')); ?>;
    const defaultPresets = <?php echo json_encode(config('email_templates.presets', [])); ?>;
    
    presetCards.forEach(card => {
        card.addEventListener('click', function() {
            const presetKey = this.dataset.preset;
            let preset = (presets && presets[presetKey]) ? presets[presetKey] : (defaultPresets[presetKey] || null);
            
            if (preset) {
                const sSubj = document.getElementById('student_subject');
                const sBody = document.getElementById('student_body');
                const gSubj = document.getElementById('guardian_subject');
                const gBody = document.getElementById('guardian_body');

                if (sSubj) sSubj.value = preset.student_subject || '';
                if (sBody) sBody.value = preset.student_body || '';
                if (gSubj) gSubj.value = preset.guardian_subject || '';
                if (gBody) gBody.value = preset.guardian_body || '';
                
                presetCards.forEach(c => c.classList.remove('active-preset'));
                this.classList.add('active-preset');
                
                if (typeof updatePreview === 'function') {
                    updatePreview();
                }
            }
        });
    });

    const resetBtns = document.querySelectorAll('.reset-email-btn');
    resetBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const subjectInput = document.getElementById(this.dataset.subjectId);
            const bodyInput = document.getElementById(this.dataset.bodyId);
            if (subjectInput) subjectInput.value = this.dataset.defaultSubject;
            if (bodyInput) bodyInput.value = this.dataset.defaultBody;
            
            if (typeof updatePreview === 'function') {
                updatePreview();
            }
        });
    });

    if (typeof updatePreview === 'function') {
        updatePreview();
    }
});

// Reminder Scheduling Tab JS
function toggleReminderStyle(checkbox, elementId, colorClass) {
    const el = document.getElementById(elementId);
    if (!el) return;
    const classes = colorClass.split(' ');
    if (checkbox.checked) {
        el.classList.remove('bg-light');
        el.classList.add(...classes);
    } else {
        el.classList.add('bg-light');
        el.classList.remove(...classes);
    }
}

function toggleOverdueSettings(checkbox) {
    const panel = document.getElementById('overdueSettingsPanel');
    if (!panel) return;
    if (checkbox.checked) {
        panel.classList.remove('d-none');
    } else {
        panel.classList.add('d-none');
    }
}

function fillPreset(textareaId, text) {
    const textarea = document.getElementById(textareaId);
    if (!textarea) return;
    textarea.value = text;
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

function insertVariable(badge, textareaId) {
    const textarea = document.getElementById(textareaId);
    if (!textarea) return;
    const variable = badge.dataset.var || badge.textContent.trim();
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    textarea.value = text.substring(0, start) + variable + text.substring(end);
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + variable.length;
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\settings\index.blade.php ENDPATH**/ ?>