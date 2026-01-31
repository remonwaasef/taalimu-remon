

<?php $__env->startSection('content'); ?>
    <div class="mb-4">
        <h2 class="fw-bold text-dark">متابعة الحضور والغياب</h2>
        <p class="text-muted">إدارة حضور الطلاب بناءً على جدول الحصص اليومي.</p>
    </div>

    <div class="row g-4">
        <!-- Today's Sessions -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>حصص اليوم (<?php echo e(now()->format('Y-m-d')); ?>)</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">الحصة / الوقت</th>
                                    <th class="border-0">المعلم / القاعة</th>
                                    <th class="border-0 text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $todaySessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?php echo e($session->course->title); ?></div>
                                            <small class="badge bg-primary bg-opacity-10 text-primary">
                                                <?php echo e(\Carbon\Carbon::parse($session->start_time)->format('h:i A')); ?> - 
                                                <?php echo e(\Carbon\Carbon::parse($session->end_time)->format('h:i A')); ?>

                                            </small>
                                        </td>
                                        <td>
                                            <div class="small mb-1"><i class="bi bi-person me-1"></i><?php echo e($session->instructor->name ?? 'معلم الدورة'); ?></div>
                                            <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i><?php echo e($session->classroom->name ?? __('center::schedules.classroom')); ?></div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="<?php echo e(route('center.attendance.show', $session)); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    <i class="bi bi-card-checklist me-1"></i> التحضير اليدوي
                                                </a>
                                                <a href="<?php echo e(route('center.attendance.qr', $session)); ?>" class="btn btn-sm btn-primary rounded-pill px-3">
                                                    <i class="bi bi-qr-code me-1"></i> عرض الـ QR
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">لا يوجد حصص مجدولة لهذا اليوم</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <?php echo e($todaySessions->links()); ?>

                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-ui-checks me-2 text-success"></i>آخر التحضيرات</h5>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        <?php $__empty_1 = true; $__currentLoopData = $recentAttendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="list-group-item px-0 border-0 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-<?php echo e($record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : 'danger')); ?> bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person-check text-<?php echo e($record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : 'danger')); ?>"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="fw-bold small"><?php echo e($record->student->name); ?></div>
                                        <div class="text-muted" style="font-size: 0.75rem;"><?php echo e($record->course->title); ?></div>
                                        <small class="text-muted" style="font-size: 0.7rem;"><?php echo e($record->check_in_time->diffForHumans()); ?></small>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge bg-<?php echo e($record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : 'danger')); ?> bg-opacity-10 text-<?php echo e($record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : 'danger')); ?> rounded-pill" style="font-size: 0.65rem;">
                                            <?php echo e($record->status == 'present' ? 'حاضر' : ($record->status == 'late' ? 'متأخر' : 'غائب')); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-4 text-muted small">لا يوجد نشاطات مؤخراً</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules/Center\resources/views/attendance/index.blade.php ENDPATH**/ ?>