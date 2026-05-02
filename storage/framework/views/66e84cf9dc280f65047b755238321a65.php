<?php $__env->startSection('page-title', __('instructor::students.student_profile')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row g-4">
        <!-- Student Info Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <?php if($student->user && $student->user->qr_identifier): ?>
                            <div class="d-flex flex-column align-items-center">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo e(urlencode($student->user->qr_identifier)); ?>" alt="QR Code" class="img-fluid rounded-4 shadow-sm mb-2" style="max-width: 150px;">
                                <span class="badge bg-light text-dark border user-select-all fs-6 font-monospace"><?php echo e($student->user->qr_identifier); ?></span>
                            </div>
                        <?php else: ?>
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">
                                <i class="fas fa-user-graduate fa-3x"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3 class="fw-bold mb-1"><?php echo e($student->name); ?></h3>
                    <p class="text-muted mb-4"><?php echo e($student->user->email ?? $student->email); ?></p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <?php
                            $portalUrl = route('student.portal', $student->user->qr_identifier ?? 'invalid');
                            $shareMsg = __('instructor::dashboard.student_portal_share_msg', [
                                'name' => $student->name,
                                'url' => $portalUrl
                            ]);
                            // Format phone: remove any non-digits, and if starts with 0, replace with 20
                            $cleanPhone = preg_replace('/[^0-9]/', '', $student->phone);
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '20' . substr($cleanPhone, 1);
                            }
                        ?>
                        <a href="https://api.whatsapp.com/send?phone=<?php echo e($cleanPhone); ?>&text=<?php echo e(urlencode($shareMsg)); ?>" target="_blank" class="btn btn-success rounded-pill px-4">
                            <i class="fab fa-whatsapp me-2"></i> <?php echo e(__('instructor::students.student_portal') ?? 'بوابة الطالب'); ?>

                        </a>
                        <button onclick="copyPortalLink('<?php echo e($portalUrl); ?>')" class="btn btn-light rounded-pill px-3" title="نسخ رابط البوابة">
                            <i class="fas fa-link"></i>
                        </button>
                        <a href="tel:<?php echo e($student->phone); ?>" class="btn btn-light rounded-pill px-3">
                            <i class="fas fa-phone"></i>
                        </a>
                        <button type="button" class="btn btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#sendEmailModal" title="<?php echo e(__('instructor::students.send_email') ?? 'إرسال بريد إلكتروني'); ?>">
                            <i class="fas fa-envelope"></i>
                        </button>
                    </div>

                    <hr class="opacity-10 my-4">

                    <div class="text-start">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted small"><?php echo e(__('instructor::students.registration_date') ?? 'تاريخ الانضمام'); ?>:</span>
                            <span class="fw-bold small"><?php echo e($student->created_at->format('Y/m/d')); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted small"><?php echo e(__('instructor::students.phone')); ?>:</span>
                            <span class="fw-bold small" dir="ltr"><?php echo e($student->phone); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-0">
                            <span class="text-muted small"><?php echo e(__('instructor::students.status')); ?>:</span>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><?php echo e(__('instructor::students.active')); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-money-check-alt me-2 text-primary"></i> <?php echo e(__('instructor::reminders.student_payment_section')); ?></h6>
                    <form action="<?php echo e(route('instructor.students.update-payment', $student->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::reminders.student_monthly_fee')); ?></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="monthly_fee" class="form-control bg-white" value="<?php echo e($student->monthly_fee); ?>" placeholder="<?php echo e(__('instructor::reminders.student_payment_hint')); ?>">
                                    <span class="input-group-text bg-white"><?php echo e(app('tenant')->settings['currency'] ?? 'ج.م'); ?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::reminders.student_due_day')); ?></label>
                                <select name="payment_due_day" class="form-select bg-white">
                                    <option value=""><?php echo e(__('instructor::reminders.student_payment_hint')); ?></option>
                                    <?php for($d = 1; $d <= 28; $d++): ?>
                                        <option value="<?php echo e($d); ?>" <?php echo e($student->payment_due_day == $d ? 'selected' : ''); ?>><?php echo e($d); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted"><?php echo e(__('instructor::reminders.student_parent_email')); ?></label>
                                <input type="email" name="parent_email" class="form-control bg-white" value="<?php echo e($student->parent_email); ?>" placeholder="parent@example.com">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">
                                    <i class="fas fa-save me-1"></i> <?php echo e(__('instructor::reminders.save_settings')); ?>

                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-8">
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 text-white" style="background: var(--primary-gradient);">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="bg-white bg-opacity-20 p-2 rounded-3">
                                    <i class="fas fa-calendar-check fa-lg"></i>
                                </div>
                                <span class="badge bg-white bg-opacity-20 rounded-pill"><?php echo e(__('instructor::students.attendance_rate')); ?></span>
                            </div>
                            <?php
                                $attendanceTotal = $attendances->count();
                                $attendanceRate = $attendanceTotal > 0 ? round(($attendances->where('status', 'present')->count() / $attendanceTotal) * 100) : 0;
                            ?>
                            <h2 class="fw-bold mb-0"><?php echo e($attendanceRate); ?>%</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 bg-success text-white">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="bg-white bg-opacity-20 p-2 rounded-3">
                                    <i class="fas fa-money-bill-wave fa-lg"></i>
                                </div>
                                <span class="badge bg-white bg-opacity-20 rounded-pill"><?php echo e(__('instructor::students.amount_paid')); ?></span>
                            </div>
                            <h2 class="fw-bold mb-0"><?php echo e(number_format($student->sales->sum('paid_amount'), 2)); ?> <small class="fs-6">ج.م</small></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php
                        $totalDue = $student->enrollments->sum(fn($e) => $e->course->price ?? 0);
                        $totalPaid = $student->sales->sum('paid_amount');
                        $balance = $totalDue - $totalPaid;
                    ?>
                    <div class="card border-0 shadow-sm rounded-4 text-white <?php echo e($balance > 0 ? 'bg-danger' : 'bg-dark'); ?>">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="bg-white bg-opacity-20 p-2 rounded-3">
                                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                                </div>
                                <span class="badge bg-white bg-opacity-20 rounded-pill"><?php echo e(__('instructor::students.balance')); ?></span>
                            </div>
                            <h2 class="fw-bold mb-0"><?php echo e(number_format($balance, 2)); ?> <small class="fs-6">ج.م</small></h2>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($balance > 0): ?>
                <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4">
                    <div class="d-flex align-items-center justify-content-between p-2">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark"><?php echo e(__('instructor::students.payment_reminder_title') ?? 'تذكير بسداد المصروفات'); ?></h6>
                            <p class="text-muted small mb-0"><?php echo e(__('instructor::students.balance_due_msg', ['amount' => number_format($balance, 2)])); ?></p>
                        </div>
                        <?php
                            $msg = __('instructor::dashboard.payment_reminder_msg', [
                                'name' => $student->name,
                                'amount' => number_format($balance, 2),
                                'instructor' => auth()->user()->name ?? 'المعلم'
                            ]);
                            $cleanPhone = preg_replace('/[^0-9]/', '', $student->phone);
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '20' . substr($cleanPhone, 1);
                            }
                        ?>
                        <a href="https://api.whatsapp.com/send?phone=<?php echo e($cleanPhone); ?>&text=<?php echo e(urlencode($msg)); ?>" target="_blank" class="btn btn-warning rounded-pill px-4 fw-bold">
                            <i class="fab fa-whatsapp me-2"></i> <?php echo e(__('instructor::students.send_reminder') ?? 'إرسال تذكير'); ?>

                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tabs for Details -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-0">
                    <ul class="nav nav-tabs nav-fill border-0" id="studentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active border-0 py-3 fw-bold" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance-panel" type="button" role="tab"><?php echo e(__('instructor::students.attendance')); ?></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link border-0 py-3 fw-bold" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments-panel" type="button" role="tab"><?php echo e(__('instructor::students.payments')); ?></button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content" id="studentTabsContent">
                        <!-- Attendance Panel -->
                        <div class="tab-pane fade show active" id="attendance-panel" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 text-center">
                                    <thead class="bg-light">
                                        <tr>
                                            <th><?php echo e(__('instructor::dashboard.date')); ?></th>
                                            <th><?php echo e(__('instructor::students.groups')); ?></th>
                                            <th><?php echo e(__('instructor::dashboard.time') ?? 'الموعد'); ?></th>
                                            <th><?php echo e(__('instructor::students.status')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($attendance->session_date->format('Y/m/d')); ?></td>
                                                <td><?php echo e($attendance->course->title); ?></td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo e($attendance->schedule ? \Carbon\Carbon::parse($attendance->schedule->start_time)->format('h:i A') : '-'); ?>

                                                    </small>
                                                </td>
                                                <td>
                                                    <?php if($attendance->status == 'present'): ?>
                                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><?php echo e(__('instructor::students.present')); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3"><?php echo e(__('instructor::students.absent')); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="4" class="py-5 text-muted">لا يوجد سجل حضور مسجل حالياً.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Payments Panel -->
                        <div class="tab-pane fade" id="payments-panel" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 text-center">
                                    <thead class="bg-light">
                                        <tr>
                                            <th><?php echo e(__('instructor::dashboard.date')); ?></th>
                                            <th><?php echo e(__('instructor::students.amount')); ?></th>
                                            <th><?php echo e(__('instructor::students.payment_method')); ?></th>
                                            <th><?php echo e(__('instructor::students.notes')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $student->sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($sale->created_at->format('Y/m/d')); ?></td>
                                                <td class="fw-bold"><?php echo e(number_format($sale->paid_amount, 2)); ?> ج.م</td>
                                                <td>
                                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">
                                                        <?php echo e($sale->payment_method == 'cash' ? __('instructor::students.cash') : __('instructor::students.other')); ?>

                                                    </span>
                                                </td>
                                                <td><small class="text-muted"><?php echo e($sale->notes ?: '-'); ?></small></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="4" class="py-5 text-muted">لا يوجد سجل مدفوعات مسجل حالياً.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Email Modal -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 bg-light p-4 rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-envelope me-2 text-primary"></i> إرسال بريد إلكتروني للطالب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('instructor.students.send-email', $student->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    <?php if(!$student->email && !($student->user->email ?? null)): ?>
                        <div class="alert alert-warning small border-0 shadow-sm">
                            <i class="fas fa-exclamation-triangle me-1"></i> هذا الطالب لا يمتلك بريداً إلكترونياً مسجلاً. قد لا ينجح الإرسال.
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">موضوع الرسالة (Subject) <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control bg-light border-0" required placeholder="مثال: تنبيه غياب، تحديث بيانات، أو تحية">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">نص الرسالة <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control bg-light border-0" rows="6" required placeholder="اكتب محتوى رسالتك هنا..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fas fa-paper-plane me-2"></i> إرسال الآن
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .nav-tabs .nav-link {
        color: #64748b;
        transition: all 0.3s;
        border-bottom: 2px solid transparent !important;
    }
    .nav-tabs .nav-link:hover {
        background: transparent;
        color: var(--primary-color);
    }
    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        background: transparent;
        border-bottom: 2px solid var(--primary-color) !important;
    }
</style>
<script>
    function copyPortalLink(url) {
        navigator.clipboard.writeText(url).then(() => {
            alert("<?php echo e(__('instructor::dashboard.portal_link_copied') ?? 'تم نسخ رابط بوابة الطالب بنجاح!'); ?>");
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('instructor::components.layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\students\show.blade.php ENDPATH**/ ?>