<?php $__env->startSection('page-title', __('admin::admin.dashboard.title')); ?>
<?php $__env->startSection('page-subtitle', date('Y-m-d')); ?>

<?php $__env->startSection('content'); ?>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary me-3">
                        <i class="bi bi-building fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2"><?php echo e(__('admin::admin.dashboard.stats.total_centers')); ?></h6>
                         <h3 class="fw-bold mb-0"><?php echo e($totalTenants); ?></h3>
                    </div>
                    <a href="<?php echo e(route('admin.tenants.index')); ?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success me-3">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2"><?php echo e(__('admin::admin.dashboard.stats.active_centers')); ?></h6>
                         <h3 class="fw-bold mb-0"><?php echo e($activeTenants); ?></h3>
                    </div>
                    <a href="<?php echo e(route('admin.tenants.index')); ?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning me-3">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2"><?php echo e(__('admin::admin.dashboard.stats.expiring_soon')); ?></h6>
                         <h3 class="fw-bold mb-0"><?php echo e($expiringSoon); ?></h3>
                    </div>
                    <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info me-3">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2"><?php echo e(__('admin::admin.dashboard.stats.total_students')); ?></h6>
                         <h3 class="fw-bold mb-0"><?php echo e($totalStudents); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue and Support Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success me-3">
                        <i class="bi bi-cash-stack fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2"><?php echo e(__('admin::admin.dashboard.stats.total_revenue')); ?></h6>
                        <h3 class="fw-bold mb-0"><?php echo e(number_format($totalRevenue, 2)); ?> <small class="fs-6 text-muted"><?php echo e(__('admin::admin.egp') ?? 'ج.م'); ?></small></h3>
                    </div>
                    <!-- Assuming revenue details might be in subscriptions for now -->
                     <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary me-3">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2"><?php echo e(__('admin::admin.dashboard.stats.this_month_revenue')); ?></h6>
                        <h3 class="fw-bold mb-0"><?php echo e(number_format($thisMonthRevenue, 2)); ?> <small class="fs-6 text-muted"><?php echo e(__('admin::admin.egp') ?? 'ج.م'); ?></small></h3>
                    </div>
                     <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning me-3">
                        <i class="bi bi-ticket-perforated fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2"><?php echo e(__('admin::admin.dashboard.stats.open_tickets')); ?></h6>
                         <h3 class="fw-bold mb-0"><?php echo e($openTickets); ?></h3>
                    </div>
                    <a href="<?php echo e(route('admin.tickets.index')); ?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-secondary bg-opacity-10 p-3 rounded-circle text-secondary me-3">
                        <i class="bi bi-life-preserver fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2"><?php echo e(__('admin::admin.dashboard.stats.total_tickets')); ?></h6>
                         <h3 class="fw-bold mb-0"><?php echo e($totalTickets); ?></h3>
                    </div>
                    <a href="<?php echo e(route('admin.tickets.index')); ?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Subscription Analytics -->
    <div class="mb-5">
        <h5 class="fw-bold mb-4 text-dark d-flex align-items-center">
            <i class="bi bi-pie-chart-fill me-2 text-primary"></i>
            تحليل باقات الاشتراك
        </h5>
        <div class="row g-4">
            <?php $__currentLoopData = $planAnalytics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                                    <?php echo e($plan['name']); ?>

                                </span>
                                <?php if($plan['badge']): ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-2" style="font-size: 0.7rem;">
                                        <?php echo e($plan['badge']); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="row g-0 align-items-center">
                                <div class="col-6 border-end">
                                    <div class="px-2">
                                        <div class="text-muted small mb-1">المراكز</div>
                                        <div class="h4 fw-bold mb-0 text-dark"><?php echo e($plan['centers_count']); ?></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="px-2 text-end">
                                        <div class="text-muted small mb-1">صافي الأرباح</div>
                                        <div class="h4 fw-bold mb-0 text-success">
                                            <?php echo e(number_format($plan['total_profits'], 0)); ?>

                                            <span class="small fw-normal text-muted" style="font-size: 0.7rem;"><?php echo e(__('admin::admin.egp')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-top">
                                <div class="progress" style="height: 6px;">
                                    <?php
                                        $percentage = $totalTenants > 0 ? ($plan['centers_count'] / $totalTenants) * 100 : 0;
                                    ?>
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo e($percentage); ?>%" aria-valuenow="<?php echo e($percentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-muted">نسبة الاستحواذ</small>
                                    <small class="fw-bold"><?php echo e(number_format($percentage, 1)); ?>%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Recent Tenants -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><?php echo e(__('admin::admin.dashboard.recent_tenants')); ?></h5>
            <a href="<?php echo e(route('admin.tenants.index')); ?>" class="btn btn-sm btn-link"><?php echo e(__('admin::admin.view_all')); ?></a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0"><?php echo e(__('admin::admin.tenants.table.name')); ?></th>
                        <th class="px-4 py-3 border-0"><?php echo e(__('admin::admin.tenants.table.domain')); ?></th>
                        <th class="px-4 py-3 border-0"><?php echo e(__('admin::admin.tenants.table.joined_on')); ?></th>
                        <th class="px-4 py-3 border-0"><?php echo e(__('admin::admin.tenants.table.status')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = \App\Models\Tenant::latest()->take(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-4 position-relative">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <?php echo e(substr($tenant->name, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <a href="<?php echo e(route('admin.tenants.show', $tenant->id)); ?>" class="fw-bold text-decoration-none text-dark stretched-link"><?php echo e($tenant->name); ?></a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 text-muted"><?php echo e($tenant->domain); ?></td>
                            <td class="px-4 text-muted"><?php echo e($tenant->created_at->format('Y-m-d')); ?></td>
                            <td class="px-4">
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><?php echo e($tenant->status); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                             <td colspan="4" class="text-center py-5 text-muted"><?php echo e(__('admin::admin.dashboard.no_tenants')); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Support Tickets -->
    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><?php echo e(__('admin::admin.dashboard.recent_tickets')); ?></h5>
            <a href="<?php echo e(route('admin.tickets.index')); ?>" class="btn btn-sm btn-link"><?php echo e(__('admin::admin.view_all')); ?></a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0"><?php echo e(__('admin::admin.tickets.subject')); ?></th>
                        <th class="px-4 py-3 border-0"><?php echo e(__('admin::admin.tickets.user')); ?></th>
                        <th class="px-4 py-3 border-0"><?php echo e(__('admin::admin.tickets.center')); ?></th>
                        <th class="px-4 py-3 border-0"><?php echo e(__('admin::admin.tickets.date')); ?></th>
                        <th class="px-4 py-3 border-0"><?php echo e(__('admin::admin.tickets.status')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-4 fw-bold position-relative">
                                <a href="<?php echo e(route('admin.tickets.show', $ticket->id)); ?>" class="text-decoration-none text-dark stretched-link"><?php echo e($ticket->subject); ?></a>
                            </td>
                            <td class="px-4"><?php echo e($ticket->user ? $ticket->user->name : 'غير محدد'); ?></td>
                            <td class="px-4 text-muted"><?php echo e($ticket->tenant ? $ticket->tenant->name : 'نظام'); ?></td>
                            <td class="px-4 text-muted"><?php echo e($ticket->created_at->format('Y-m-d')); ?></td>
                            <td class="px-4">
                                <?php if($ticket->status == 'open'): ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">مفتوحة</span>
                                <?php elseif($ticket->status == 'pending'): ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">قيد الانتظار</span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">مغلقة</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                             <td colspan="5" class="text-center py-5 text-muted"><?php echo e(__('admin.dashboard.no_tickets')); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Admin\resources\views\index.blade.php ENDPATH**/ ?>