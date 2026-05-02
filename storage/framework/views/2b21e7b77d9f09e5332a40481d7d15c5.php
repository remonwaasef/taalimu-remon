<?php $__env->startSection('title', 'تعديل اشتراك ' . $subscription->tenant->name); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4 x-small fw-bold">
        <i class="bi bi-arrow-right me-2"></i> العودة لقائمة الاشتراكات
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold" style="width: 50px; height: 50px; font-size: 1.2rem;">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark mb-1">تعديل اشتراك: <?php echo e($subscription->tenant->name); ?></h4>
                        <p class="text-muted small mb-0">تحكم كامل في مدة الاشتراك والأسعار والحالة</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4 border-top">
                <form action="<?php echo e(route('admin.subscriptions.update', $subscription->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="row g-4">
                        <!-- Plan Status -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">حالة الاشتراك</label>
                            <select name="status" class="form-select border-0 bg-light rounded-3 p-3">
                                <option value="active" <?php echo e($subscription->status === 'active' ? 'selected' : ''); ?>>نشط (Active)</option>
                                <option value="inactive" <?php echo e($subscription->status === 'inactive' ? 'selected' : ''); ?>>غير نشط (Inactive)</option>
                                <option value="expired" <?php echo e($subscription->status === 'expired' ? 'selected' : ''); ?>>منتهي (Expired)</option>
                            </select>
                            <div class="form-text x-small">تغيير الحالة سيؤثر على إمكانية دخول المركز للنظام.</div>
                        </div>

                        <!-- Package -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">الباقة المرتبطة</label>
                            <select name="package_id" class="form-select border-0 bg-light rounded-3 p-3">
                                <option value="">بدون باقة محددة</option>
                                <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($package->id); ?>" <?php echo e($subscription->package_id == $package->id ? 'selected' : ''); ?>>
                                        <?php echo e($package->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Billing Cycle -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">دورة الفوترة</label>
                            <div class="d-flex gap-4 p-3 bg-light rounded-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="billing_cycle" id="cycleMonthly" value="monthly" <?php echo e($subscription->billing_cycle === 'monthly' ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="cycleMonthly">شهري</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="billing_cycle" id="cycleTerm" value="term" <?php echo e($subscription->billing_cycle === 'term' ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="cycleTerm">ترم</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="billing_cycle" id="cycleYearly" value="yearly" <?php echo e($subscription->billing_cycle === 'yearly' ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="cycleYearly">سنوي</label>
                                </div>
                            </div>
                        </div>

                        <!-- Expiry Date -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">تاريخ التجديد/الانتهاء</label>
                            <input type="date" name="ends_at" class="form-control border-0 bg-light rounded-3 p-3" 
                                   value="<?php echo e($subscription->ends_at ? $subscription->ends_at->format('Y-m-d') : ''); ?>">
                            <div class="form-text x-small text-primary">اترك الحقل فارغاً للاشتراكات "مدى الحياة".</div>
                        </div>

                        <!-- Prices -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">السعر الأساسي (ج.م)</label>
                            <input type="number" name="base_price" step="0.01" class="form-control border-0 bg-light rounded-3 p-3" 
                                   value="<?php echo e($subscription->base_price ?: ($subscription->package->price ?? 0)); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small">المبلغ المطلوب تحصيله (ج.م)</label>
                            <input type="number" name="total_amount" step="0.01" class="form-control border-0 bg-light rounded-3 p-3" 
                                   value="<?php echo e($subscription->total_amount ?: ($subscription->base_price ?: 0)); ?>">
                            <div class="form-text x-small">المبلغ بعد تطبيق أي خصومات يدوية.</div>
                        </div>

                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill w-100 py-3 fw-bold shadow-sm">
                                <i class="bi bi-check-circle me-2"></i> حفظ التغييرات
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm rounded-4 mt-4 bg-light">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i> ملاحظات الإدارة:</h6>
                <ul class="small text-muted mb-0">
                    <li>تغيير الباقة لا يغير الصلاحيات تلقائياً إلا عند انتهاء الجلسة الحالية للمستخدم.</li>
                    <li>في حال جعل الاشتراك "منتهي"، سيظهر تنبيه للمركز بضرورة التجديد.</li>
                    <li>الأسعار المسجلة هنا تظهر في "سجل الفواتير" للمركز.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Admin\resources\views\subscriptions\edit.blade.php ENDPATH**/ ?>