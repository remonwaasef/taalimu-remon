

<?php $__env->startSection('title', 'الاشتراكات'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">إدارة الاشتراكات</h2>
            <p class="text-muted small mb-0">نظرة عامة على اشتراكات المراكز والتحصيل المالي</p>
        </div>
        <a href="<?php echo e(route('admin.settings.index')); ?>#plans" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-patch-check me-2"></i>
            الخطط والأسعار
        </a>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-right: 4px solid #4361EE !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">إجمالي الاشتراكات</div>
                            <div class="h3 fw-bold mb-0"><?php echo e($stats['total_count']); ?></div>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-people fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-right: 4px solid #10b981 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">الاشتراكات النشطة</div>
                            <div class="h3 fw-bold mb-0 text-success"><?php echo e($stats['active_count']); ?></div>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-check-circle fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-right: 4px solid #f59e0b !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">تنتهي قريباً</div>
                            <div class="h3 fw-bold mb-0 text-warning"><?php echo e($stats['expiring_soon']); ?></div>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-hourglass-split fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%); border-right: 4px solid #059669 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">إجمالي الإيرادات</div>
                            <div class="h3 fw-bold mb-0 text-success"><?php echo e(number_format($stats['total_revenue'], 2)); ?> <small class="fs-6">ج.م</small></div>
                        </div>
                        <div class="icon-box bg-success text-white rounded-3 shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #059669 0%, #34d399 100%);">
                            <i class="bi bi-wallet2 fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & List -->
    <div class="card border-0 shadow-sm rounded-4" style="overflow: visible;">
        <div class="card-header bg-white border-0 p-4">
            <form action="<?php echo e(route('admin.subscriptions.index')); ?>" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0 x-small" placeholder="بحث عن اسم المركز..." value="<?php echo e(request('search')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select bg-light border-0 x-small" onchange="this.form.submit()">
                        <option value="">كل الحالات</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>النشطة فقط</option>
                        <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>المنتهية فقط</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark rounded-pill w-100 x-small fw-bold">تصفية</button>
                </div>
                <?php if(request()->anyFilled(['search', 'status'])): ?>
                    <div class="col-md-2">
                        <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="btn btn-outline-secondary rounded-pill w-100 x-small border-dashed">إعادة تعيين</a>
                    </div>
                <?php endif; ?>
            </form>
        </div>
        <div class="card-body p-0" style="overflow: visible !important;">
            <div class="table-responsive" style="overflow: visible !important;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-secondary small text-uppercase">
                             <th class="py-3 border-0">المركز والتواصل</th>
                            <th class="py-3 border-0">الباقة والتحصيل</th>
                            <th class="py-3 border-0 text-center">البداية / التجديد</th>
                            <th class="py-3 border-0 text-center">الحالة</th>
                            <th class="py-3 pe-4 border-0 text-end">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $isExpired = $subscription->ends_at && $subscription->ends_at->isPast();
                                $isLifetime = !$subscription->ends_at;
                                $type = $subscription->package ? $subscription->package->name : $subscription->type_label;
                                $price = $subscription->package ? number_format($subscription->package->price, 0) . ' ج.م' : '0 ج.م';
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">
                                            <?php echo e(substr($subscription->tenant->name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <a href="<?php echo e(route('admin.tenants.show', $subscription->tenant->id)); ?>" class="fw-bold text-dark text-decoration-none d-block">
                                                <?php echo e($subscription->tenant->name); ?>

                                            </a>
                                            <div class="x-small text-muted mt-1">
                                                <div class="d-flex align-items-center gap-1"><i class="bi bi-person"></i> <?php echo e($admin->name ?? '---'); ?></div>
                                                <div class="d-flex align-items-center gap-1"><i class="bi bi-envelope"></i> <?php echo e($subscription->tenant->email ?: ($admin->email ?? '---')); ?></div>
                                                <div class="d-flex align-items-center gap-1"><i class="bi bi-telephone"></i> <?php echo e($subscription->tenant->phone ?: ($admin->phone ?? '---')); ?></div>
                                                <div class="d-flex align-items-center gap-1 text-primary"><i class="bi bi-link-45deg"></i> <?php echo e($subscription->tenant->domain); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="fw-bold text-dark small"><?php echo e($type); ?></span>
                                            <?php if($subscription->billing_cycle): ?>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary x-small rounded-pill">
                                                    <?php echo e($subscription->billing_cycle == 'yearly' ? 'سنوي' : 'شهري'); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="x-small text-muted fw-medium"><?php echo e(number_format($subscription->base_price ?: ($subscription->package->price ?? 0), 0)); ?> ج.م</span>
                                            <?php if($subscription->coupon_code && $subscription->discount_amount > 0): ?>
                                                <span class="badge bg-info bg-opacity-10 text-info x-small" title="كوبون: <?php echo e($subscription->coupon_code); ?>">
                                                    <i class="bi bi-ticket-perforated"></i> %<?php echo e((int)$subscription->discount_amount); ?>

                                                </span>
                                            <?php endif; ?>
                                            <?php
                                                $isFreeTrial = ($subscription->package && $subscription->package->slug === 'free-trial') || ($subscription->stripe_price === 'price_free');
                                                $displayPrice = $subscription->total_amount ?: ($subscription->base_price ?: ($subscription->package->price ?? 0));
                                            ?>
                                            <?php if($isFreeTrial): ?>
                                                <span class="x-small text-primary fw-bold"><i class="bi bi-gift"></i> تجربة مجانية</span>
                                            <?php elseif($subscription->stripe_status == 'active'): ?>
                                                <span class="x-small text-success fw-bold"><i class="bi bi-shield-check"></i> <?php echo e(number_format($displayPrice, 0)); ?> ج.م</span>
                                            <?php else: ?>
                                                <span class="x-small text-warning"><i class="bi bi-clock"></i> معلق</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if($isLifetime): ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">مدى الحياة</span>
                                    <?php else: ?>
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="x-small text-muted italic mb-1">بدأ: <?php echo e($subscription->created_at->format('Y-m-d')); ?></span>
                                            <span class="fw-bold small <?php echo e($isExpired ? 'text-danger' : 'text-dark'); ?>">
                                                تجديد: <?php echo e($subscription->ends_at->format('Y-m-d')); ?>

                                            </span>
                                            <?php $daysRem = (int)now()->diffInDays($subscription->ends_at, false); ?>
                                            <span class="x-small text-muted mt-1">
                                                <?php if($daysRem > 0): ?>
                                                    متبقي <?php echo e($daysRem); ?> يوم
                                                <?php else: ?>
                                                    منتهي منذ <?php echo e(abs($daysRem)); ?> يوم
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-<?php echo e($isExpired ? 'danger' : ($isLifetime ? 'primary' : 'success')); ?> bg-opacity-10 text-<?php echo e($isExpired ? 'danger' : ($isLifetime ? 'primary' : 'success')); ?> rounded-pill px-3 py-2 border border-<?php echo e($isExpired ? 'danger' : ($isLifetime ? 'primary' : 'success')); ?> border-opacity-10">
                                        <i class="bi bi-<?php echo e($isExpired ? 'exclamation-circle' : 'circle-fill'); ?> me-1" style="<?php echo e(!$isExpired ? 'font-size: 8px;' : ''); ?>"></i>
                                        <?php echo e($isExpired ? 'منتهي' : ($isLifetime ? 'مستمر' : 'نشط')); ?>

                                    </span>
                                </td>
                                <td class="text-end pe-4" style="position: relative; pointer-events: auto;">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle shadow-none border dropdown-toggle-custom" type="button" onclick="toggleCustomDropdown(event, this)">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2">
                                            <li><a class="dropdown-item rounded-3 mb-1" href="<?php echo e(route('admin.tenants.show', $subscription->tenant_id)); ?>"><i class="bi bi-eye me-2"></i> عرض المركز</a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1" href="<?php echo e(route('admin.subscriptions.edit', $subscription->id)); ?>"><i class="bi bi-pencil me-2"></i> تعديل الاشتراك</a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1" href="<?php echo e(route('admin.tenants.impersonate', $subscription->tenant_id)); ?>"><i class="bi bi-box-arrow-in-right me-2"></i> دخول كمسؤول</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="<?php echo e(route('admin.subscriptions.destroy', $subscription->id)); ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الاشتراك؟')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="dropdown-item rounded-3 text-danger">
                                                        <i class="bi bi-trash me-2"></i> حذف الاشتراك
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="py-5 text-center">
                                    <div class="py-5">
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                                            <i class="bi bi-search fs-1 opacity-25"></i>
                                        </div>
                                        <h5 class="fw-bold text-dark">لا يوجد نتائج</h5>
                                        <p class="text-muted">لم نجد أي اشتراكات تتطابق مع معايير البحث الحالية.</p>
                                        <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="btn btn-primary rounded-pill px-4 mt-2">عرض كل الاشتراكات</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($subscriptions->hasPages()): ?>
            <div class="card-footer bg-white border-0 p-4 pt-0">
                <?php echo e($subscriptions->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->startPush('scripts'); ?>
<style>
    /* Ensure dropdowns are always on top and visible */
    .dropdown-menu {
        z-index: 99999 !important;
        display: none; /* Default hidden */
        position: absolute;
        inset: auto 0 auto auto;
        margin: 0;
        transform: translate(0, 10px);
    }
    .dropdown-menu.show {
        display: block !important; /* Force show */
    }
    .table-responsive {
        overflow: visible !important;
    }
    /* Hide the default Bootstrap caret */
    .dropdown-toggle-custom::after {
        display: none !important;
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules/Admin\resources/views/subscriptions/index.blade.php ENDPATH**/ ?>