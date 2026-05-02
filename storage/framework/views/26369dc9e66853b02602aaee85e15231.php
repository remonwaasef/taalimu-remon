

<?php $__env->startSection('title', 'إضافة مستخدم إداري'); ?>
<?php $__env->startSection('page-title', 'إضافة مستخدم إداري'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center animate__animated animate__fadeIn">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-4 px-4">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-user-plus text-primary me-2"></i>
                    إضافة عضو جديد لفريق الإدارة
                </h5>
                <p class="text-muted small mb-0 mt-1">سيتمكن هذا الشخص من الوصول إلى لوحة التحكم المركزية بناءً على دوره.</p>
            </div>
            <div class="card-body px-4 pb-4">
                <form method="POST" action="<?php echo e(route('admin.users.store')); ?>">
                    <?php echo csrf_field(); ?>

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
                               value="<?php echo e(old('name')); ?>" placeholder="مثال: أحمد محمد" required>
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
                               value="<?php echo e(old('email')); ?>" placeholder="admin@example.com" required>
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
                        <label class="form-label fw-semibold">كلمة المرور</label>
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
                                   placeholder="8 أحرف على الأقل" required>
                            <button type="button" class="btn btn-outline-secondary rounded-end-3"
                                    onclick="togglePassword()">
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
                        <div class="form-text">يجب أن تحتوي على 8 أحرف على الأقل، أحرف وأرقام.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">الدور الوظيفي</label>
                        <select name="role" class="form-select rounded-3 <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">اختر الدور...</option>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $roleLabels = [
                                        'super_admin'     => '👑 مشرف عام - صلاحيات كاملة',
                                        'support_agent'   => '🎧 وكيل دعم - يدير تذاكر الدعم',
                                        'finance_manager' => '💰 مدير مالي - يدير الاشتراكات والمدفوعات',
                                        'content_manager' => '📝 مدير محتوى - يدير إعدادات المنصة',
                                    ];
                                ?>
                                <option value="<?php echo e($role->name); ?>" <?php echo e(old('role') === $role->name ? 'selected' : ''); ?>>
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
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="fas fa-save me-2"></i> إنشاء المستخدم
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

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Admin\resources\views\users\create.blade.php ENDPATH**/ ?>