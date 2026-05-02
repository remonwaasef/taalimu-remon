<?php $__env->startSection('page-title', __('center::analytics.finance_reports')); ?>

<?php $__env->startSection('page-actions'); ?>
    <div class="d-flex gap-2">
        <form action="<?php echo e(route('center.analytics.finance')); ?>" method="GET" class="d-flex gap-2 align-items-center">
            <select name="year" class="form-select form-select-sm rounded-pill px-3 shadow-sm border-0" onchange="this.form.submit()" style="background: rgba(255,255,255,0.9);">
                <?php for($y = now()->year; $y >= now()->year - 5; $y--): ?>
                    <option value="<?php echo e($y); ?>" <?php echo e($year == $y ? 'selected' : ''); ?>><?php echo e($y); ?> <?php echo e(__('center::analytics.year')); ?></option>
                <?php endfor; ?>
            </select>
        </form>
        <a href="<?php echo e(route('center.analytics.index')); ?>" class="btn btn-glass shadow-sm">
            <i class="fas fa-arrow-right me-2"></i><?php echo e(__('center::analytics.general')); ?>

        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Yearly Summary Cards -->
    <div class="row g-3 mb-5 animate__animated animate__fadeIn">
        <!-- Revenue -->
        <div class="col-xl col-md-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100 border-bottom border-success border-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="fas fa-dollar-sign small"></i>
                        </div>
                        <span class="text-muted fw-bold x-small"><?php echo e(__('center::analytics.revenue')); ?></span>
                    </div>
                    <h4 class="fw-bold text-dark mb-0"><?php echo e(format_price($totalYearlyRevenue)); ?></h4>
                </div>
            </div>
        </div>

        <!-- Commissions -->
        <div class="col-xl col-md-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100 border-bottom border-info border-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="fas fa-chalkboard-teacher small"></i>
                        </div>
                        <span class="text-muted fw-bold x-small"><?php echo e(__('center::analytics.instructor_commissions')); ?></span>
                    </div>
                    <h4 class="fw-bold text-dark mb-0"><?php echo e(format_price($totalYearlyCommissions)); ?></h4>
                </div>
            </div>
        </div>

        <!-- Operating Expenses -->
        <div class="col-xl col-md-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100 border-bottom border-danger border-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="fas fa-receipt small"></i>
                        </div>
                        <span class="text-muted fw-bold x-small"><?php echo e(__('center::analytics.operating_expenses')); ?></span>
                    </div>
                    <h4 class="fw-bold text-dark mb-0"><?php echo e(format_price($totalYearlyOpExpenses)); ?></h4>
                </div>
            </div>
        </div>

        <!-- Delayed Revenue (Due) -->
        <div class="col-xl col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100 border-bottom border-warning border-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="fas fa-exclamation-triangle small"></i>
                        </div>
                        <span class="text-muted fw-bold x-small"><?php echo e(__('center::analytics.delayed_revenue')); ?></span>
                    </div>
                    <h4 class="fw-bold text-dark mb-0"><?php echo e(format_price($totalYearlyDue)); ?></h4>
                </div>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="col-xl col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100 border-bottom border-<?php echo e($totalYearlyProfit >= 0 ? 'primary' : 'warning'); ?> border-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="bg-<?php echo e($totalYearlyProfit >= 0 ? 'primary' : 'warning'); ?> bg-opacity-10 text-<?php echo e($totalYearlyProfit >= 0 ? 'primary' : 'warning'); ?> rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="fas fa-chart-line small"></i>
                        </div>
                        <span class="text-muted fw-bold x-small"><?php echo e(__('center::analytics.net_result')); ?></span>
                    </div>
                    <h4 class="fw-bold text-dark mb-0"><?php echo e(format_price($totalYearlyProfit)); ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <!-- Monthly Breakdown Table -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-5 overflow-hidden h-100 bg-white">
                <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-calendar-alt text-primary me-2"></i><?php echo e(__('center::analytics.monthly_breakdown')); ?> (<?php echo e($year); ?>)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3"><?php echo e(__('center::analytics.month')); ?></th>
                                    <th class="border-0"><?php echo e(__('center::analytics.revenue')); ?></th>
                                    <th class="border-0"><?php echo e(__('center::analytics.operating_expenses')); ?></th>
                                    <th class="border-0"><?php echo e(__('center::analytics.instructor_commissions')); ?></th>
                                    <th class="border-0 px-4 text-end"><?php echo e(__('center::analytics.net_result')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($data['revenue'] > 0 || $data['op_expenses'] > 0 || $data['commissions'] > 0): ?>
                                    <tr>
                                        <td class="px-4 fw-bold text-dark"><?php echo e($data['month_name']); ?></td>
                                        <td class="text-success fw-bold"><?php echo e(format_price($data['revenue'])); ?></td>
                                        <td class="text-muted small"><?php echo e(format_price($data['op_expenses'])); ?></td>
                                        <td class="text-muted small"><?php echo e(format_price($data['commissions'])); ?></td>
                                        <td class="px-4 text-end">
                                            <span class="badge <?php echo e($data['profit'] >= 0 ? 'bg-success' : 'bg-danger'); ?> bg-opacity-10 text-<?php echo e($data['profit'] >= 0 ? 'success' : 'danger'); ?> rounded-pill px-3 py-2 fw-bold">
                                                <?php echo e(format_price($data['profit'])); ?>

                                            </span>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expense Categories -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-5 h-100 bg-white">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-pie-chart text-danger me-2"></i><?php echo e(__('center::analytics.operating_expenses')); ?> (<?php echo e($year); ?>)</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column gap-3">
                        <?php $__empty_1 = true; $__currentLoopData = $expenseCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="p-3 rounded-4 bg-light border border-white hover-lift shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-dark small"><?php echo e($cat->category ?: __('center::analytics.undefined')); ?></span>
                                    <span class="fw-bold text-danger small"><?php echo e(format_price($cat->total)); ?></span>
                                </div>
                                <div class="progress rounded-pill bg-white" style="height: 5px;">
                                    <?php
                                        $totalExp = $expenseCategories->sum('total');
                                        $pct = $totalExp > 0 ? ($cat->total / $totalExp) * 100 : 0;
                                    ?>
                                    <div class="progress-bar bg-danger rounded-pill" style="width: <?php echo e($pct); ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-info-circle mb-2 d-block fs-3"></i>
                                <?php echo e(__('center::analytics.no_data_available')); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row g-4 mb-5">
        <!-- Recent Sales -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-5 overflow-hidden bg-white">
                <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-shopping-cart text-success me-2"></i><?php echo e(__('center::sidebar.sales')); ?></h5>
                    <a href="<?php echo e(route('center.sales.account')); ?>" class="btn btn-sm btn-light rounded-pill px-3"><?php echo e(__('center::analytics.view_details')); ?></a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4"><?php echo e(__('center::analytics.student')); ?></th>
                                    <th><?php echo e(__('center::analytics.invoice_date')); ?></th>
                                    <th><?php echo e(__('center::analytics.status')); ?></th>
                                    <th class="px-4 text-end"><?php echo e(__('center::analytics.subtotal')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-2 me-2">
                                                    <i class="fas fa-user text-primary small"></i>
                                                </div>
                                                <span class="fw-bold small"><?php echo e($sale->student->name); ?></span>
                                            </div>
                                        </td>
                                        <td class="small"><?php echo e($sale->created_at->format('Y-m-d')); ?></td>
                                        <td>
                                            <?php if($sale->paid_amount >= $sale->total_amount): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2"><?php echo e(__('center::analytics.paid_f')); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2"><?php echo e(__('center::analytics.due_f')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 text-end fw-bold"><?php echo e(format_price($sale->paid_amount)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 border-top">
                        <?php echo e($sales->links()); ?>

                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Expenses & Commissions Side Panel -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-5 overflow-hidden bg-white mb-4">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark small"><i class="fas fa-receipt text-danger me-2"></i><?php echo e(__('center::analytics.operating_expenses')); ?> <?php echo e(__('center::analytics.recent_count', ['count' => 5])); ?></h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $recentExpenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item border-0 p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="mb-0 fw-bold small text-dark"><?php echo e($exp->category); ?></p>
                                        <span class="text-muted x-small" style="font-size: 0.7rem;"><?php echo e($exp->date->format('Y-m-d')); ?></span>
                                    </div>
                                    <span class="text-danger fw-bold small"><?php echo e(format_price($exp->amount)); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-5 overflow-hidden bg-white">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark small"><i class="fas fa-chalkboard-teacher text-info me-2"></i><?php echo e(__('center::analytics.instructor_commissions')); ?> <?php echo e(__('center::analytics.recent_count', ['count' => 5])); ?></h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $recentCommissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item border-0 p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="mb-0 fw-bold small text-dark"><?php echo e($comm->instructor->name); ?></p>
                                        <span class="text-muted x-small" style="font-size: 0.7rem;"><?php echo e($comm->created_at->format('Y-m-d')); ?></span>
                                    </div>
                                    <span class="text-info fw-bold small"><?php echo e(format_price($comm->amount)); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .x-small { font-size: 0.75rem; }
    .hover-lift { transition: transform 0.2s ease; }
    .hover-lift:hover { transform: translateY(-3px); }
    .btn-glass { background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); color: white; border: 1px solid rgba(255,255,255,0.2); }
    .btn-glass:hover { background: rgba(255, 255, 255, 0.25); color: white; }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\analytics\finance.blade.php ENDPATH**/ ?>