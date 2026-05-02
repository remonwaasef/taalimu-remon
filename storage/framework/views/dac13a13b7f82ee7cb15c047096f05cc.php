

<?php $__env->startSection('title', __('center::sidebar.profile')); ?>

<?php $__env->startSection('page-title', __('center::sidebar.profile')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Account Information -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-user-circle me-2 text-primary"></i><?php echo e(__('center::sidebar.profile')); ?>

                    </h5>
                    <p class="text-muted small mb-0"><?php echo e(__('center::profile.update_profile_description')); ?></p>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo e(route('center.profile.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted"><?php echo e(__('center::profile.full_name')); ?></label>
                                <input type="text" name="name" class="form-control rounded-3 border-2" value="<?php echo e($user->name); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted"><?php echo e(__('center::profile.email')); ?></label>
                                <input type="email" name="email" class="form-control rounded-3 border-2" value="<?php echo e($user->email); ?>" required>
                            </div>
                            <hr class="my-4 opacity-50">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fas fa-lock me-2 text-warning"></i><?php echo e(__('center::profile.change_password')); ?>

                            </h6>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted"><?php echo e(__('center::profile.new_password')); ?></label>
                                <input type="password" name="password" class="form-control rounded-3 border-2" placeholder="********">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted"><?php echo e(__('center::profile.confirm_password')); ?></label>
                                <input type="password" name="password_confirmation" class="form-control rounded-3 border-2" placeholder="********">
                            </div>
                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                                    <i class="fas fa-save me-2"></i><?php echo e(__('center::profile.save_changes')); ?>

                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preferences (Language) -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-globe me-2 text-info"></i><?php echo e(__('center::profile.system_language')); ?>

                    </h5>
                    <p class="text-muted small mb-0"><?php echo e(__('center::profile.language_description')); ?></p>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="<?php echo e(route('lang.switch', 'ar')); ?>" class="btn w-100 py-3 rounded-4 border-2 <?php echo e(app()->getLocale() == 'ar' ? 'btn-primary border-primary shadow' : 'bg-light border-light text-dark'); ?> d-flex flex-column align-items-center gap-2 transition-all">
                                <span class="fs-2">🇸🇦</span>
                                <span class="fw-bold"><?php echo e(__('center::profile.arabic')); ?></span>
                                <?php if(app()->getLocale() == 'ar'): ?>
                                    <i class="fas fa-check-circle position-absolute top-0 end-0 m-2 text-white"></i>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo e(route('lang.switch', 'fr')); ?>" class="btn w-100 py-3 rounded-4 border-2 <?php echo e(app()->getLocale() == 'fr' ? 'btn-primary border-primary shadow' : 'bg-light border-light text-dark'); ?> d-flex flex-column align-items-center gap-2 transition-all">
                                <span class="fs-2">🇫🇷</span>
                                <span class="fw-bold"><?php echo e(__('center::profile.french')); ?></span>
                                <?php if(app()->getLocale() == 'fr'): ?>
                                    <i class="fas fa-check-circle position-absolute top-0 end-0 m-2 text-white"></i>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\users\profile.blade.php ENDPATH**/ ?>