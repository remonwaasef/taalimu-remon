<?php $__env->startSection('title', 'إدارة النسخ الاحتياطي'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">نظام النسخ الاحتياطي</h3>
            <p class="text-muted small">تأمين قاعدة بيانات المنصة والملفات المرفوعة</p>
        </div>
        <div>
            <form action="<?php echo e(route('admin.backups.create')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-primary px-4 shadow-sm rounded-pill">
                    <i class="fas fa-plus-circle me-2"></i> إنشاء نسخة احتياطية فورية
                </button>
            </form>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold mb-0">الأرشيف الحالي</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-muted fw-bold small text-uppercase">اسم الملف</th>
                            <th class="border-0 py-3 text-muted fw-bold small text-uppercase">الحجم</th>
                            <th class="border-0 py-3 text-muted fw-bold small text-uppercase">تاريخ الإنشاء</th>
                            <th class="border-0 text-end px-4 py-3 text-muted fw-bold small text-uppercase">عمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $backups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $backup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-file-archive"></i>
                                    </div>
                                    <div class="fw-bold text-dark"><?php echo e($backup['file_name']); ?></div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">
                                    <?php echo e($backup['file_size']); ?>

                                </span>
                            </td>
                            <td>
                                <span class="text-muted small"><?php echo e($backup['last_modified']); ?></span>
                            </td>
                            <td class="text-end px-4">
                                <div class="btn-group">
                                    <a href="<?php echo e(route('admin.backups.download', ['file' => $backup['file_name']])); ?>" class="btn btn-light btn-sm rounded-pill px-3 me-2 border">
                                        <i class="fas fa-download me-1 text-primary"></i> تحميل
                                    </a>
                                    <form action="<?php echo e(route('admin.backups.delete', $backup['file_name'])); ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه النسخة الاحتياطية؟')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-light btn-sm rounded-pill px-3 border text-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="opacity-50 mb-3">
                                    <i class="fas fa-database fa-3x"></i>
                                </div>
                                <p class="text-muted">لا توجد نسخ احتياطية مسجلة حالياً.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4 p-4 bg-info bg-opacity-10 rounded-4 border-start border-info border-5">
        <h6 class="fw-bold text-info"><i class="fas fa-info-circle me-2"></i> جاري تنبيهك:</h6>
        <p class="text-muted small mb-0">النسخ الاحتياطية يتم حفظها محلياً في مجلد `storage/app/backups`. لضمان أمان قصوى، يفضل ربط النظام بخدمة تخزين سحابي مثل Google Drive.</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Admin\resources\views\backups\index.blade.php ENDPATH**/ ?>