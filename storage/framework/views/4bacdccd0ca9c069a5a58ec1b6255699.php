

<?php $__env->startSection('title', 'تعديل المستخدم الإداري'); ?>
<?php $__env->startSection('page-title', 'تعديل المستخدم الإداري'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center animate__animated animate__fadeIn">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-4 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm"
                         style="width:48px;height:48px;background:linear-gradient(135deg,#3A0CA3,#2A4DFF);font-size:1.2rem;">
                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">تعديل: <?php echo e($user->name); ?></h5>
                        <p class="text-muted small mb-0"><?php echo e($user->email); ?></p>
                    </div>
                </div>
            </div>
            <div class="card-body px-4 pb-4">
                <form method="POST" action="<?php echo e(route('admin.users.update', $user->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">الاسم الكامل</label>
                        <input type="text" name="name" class="form-control rounded-3 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('name', $user->name)); ?>" required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control rounded-3 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('email', $user->email)); ?>" required>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">كلمة مرور جديدة <span class="text-muted fw-normal">(اتركه فارغاً لعدم التغيير)</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="passwordField"
                                   class="form-control rounded-start-3 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="اتركه فارغاً لعدم التغيير">
                            <button type="button" class="btn btn-outline-secondary rounded-end-3" onclick="togglePassword()">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">الدور الوظيفي</label>
                        <?php if($user->id === auth()->id()): ?>
                            <div class="alert alert-info rounded-3 small py-2">
                                <i class="fas fa-info-circle me-1"></i>
                                لا يمكنك تغيير دورك الخاص. تواصل مع مشرف آخر للقيام بذلك.
                            </div>
                            <input type="hidden" name="role" value="<?php echo e($user->role); ?>">
                            <input type="text" class="form-control rounded-3 bg-light" value="<?php echo e($user->role); ?>" disabled>
                        <?php else: ?>
                        <select name="role" class="form-select rounded-3 <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $roleLabels = [
                                        'super_admin'     => '👑 مشرف عام - صلاحيات كاملة',
                                        'support_agent'   => '🎧 وكيل دعم - يدير تذاكر الدعم',
                                        'finance_manager' => '💰 مدير مالي - يدير الاشتراكات والمدفوعات',
                                        'content_manager' => '📝 مدير محتوى - يدير إعدادات المنصة',
                                    ];
                                ?>
                                <option value="<?php echo e($role->name); ?>" <?php echo e(old('role', $user->role) === $role->name ? 'selected' : ''); ?>>
                                    <?php echo e($roleLabels[$role->name] ?? $role->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php endif; ?>
                    </div>

                    
                    <div class="p-3 bg-light rounded-3 mb-4 small text-muted">
                        <div class="row g-2">
                            <div class="col-6"><i class="fas fa-calendar-alt me-1"></i> أُنشئ: <?php echo e($user->created_at->format('d/m/Y')); ?></div>
                            <div class="col-6"><i class="fas fa-clock me-1"></i> آخر تحديث: <?php echo e($user->updated_at->diffForHumans()); ?></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="fas fa-save me-2"></i> حفظ التغييرات
                        </button>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-light rounded-pill px-4 border">
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const field = document.getElementById('passwordField');
    const icon  = document.getElementById('eyeIcon');
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Admin\resources\views\users\edit.blade.php ENDPATH**/ ?>