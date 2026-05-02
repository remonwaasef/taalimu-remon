

<?php $__env->startSection('content'); ?>
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-2">سجل الحضور</h2>
            <p class="text-muted mb-0">تابع أيام حضورك وانضباطك في الدورات التعليمية</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-ultra overflow-hidden">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-0">بيانات الحضور الحديثة</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="p-4 border-0">الدورة التدريبية</th>
                                <th class="p-4 border-0">التاريخ</th>
                                <th class="p-4 border-0">وقت الحضور</th>
                                <th class="p-4 border-0">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="p-4 border-light fw-bold text-dark"><?php echo e($attendance->course->name); ?></td>
                                    <td class="p-4 border-light text-muted"><?php echo e($attendance->session_date->format('Y-m-d')); ?></td>
                                    <td class="p-4 border-light text-muted">
                                        <?php if($attendance->check_in_time): ?>
                                            <?php echo e(\Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A')); ?>

                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 border-light">
                                        <?php if($attendance->status == 'present'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">حاضر</span>
                                        <?php elseif($attendance->status == 'late'): ?>
                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">متأخر</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">غائب</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="mb-3 fs-1 opacity-25">📅</div>
                                        <p class="text-muted">لا يوجد سجلات حضور مسجلة حتى الآن.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if($attendances->hasPages()): ?>
                    <div class="card-footer bg-white border-0 p-4">
                        <?php echo e($attendances->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('campus::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Campus\resources\views\attendance.blade.php ENDPATH**/ ?>