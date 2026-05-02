

<?php $__env->startSection('title', __('admin::admin.sidebar.cookie_reports')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-1">📊 <?php echo e(__('admin::admin.consent_report.title')); ?></h2>
        <a href="<?php echo e(route('consent.export')); ?>" class="btn btn-primary">
            <i class="bi bi-download me-2"></i> <?php echo e(__('admin::admin.consent_report.export_csv')); ?>

        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1"><?php echo e(__('admin::admin.consent_report.stats.total')); ?></h6>
                    <h2 class="fw-bold text-primary mb-0"><?php echo e($stats['total_consents']); ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1"><?php echo e(__('admin::admin.consent_report.stats.analytics')); ?></h6>
                    <h2 class="fw-bold text-success mb-0"><?php echo e($stats['analytics_accepted']); ?></h2>
                    <small class="text-muted fs-6">
                        <?php echo e(__('admin::admin.consent_report.stats.of_total', ['percent' => $stats['total_consents'] > 0 ? round(($stats['analytics_accepted'] / $stats['total_consents']) * 100) : 0])); ?>

                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1"><?php echo e(__('admin::admin.consent_report.stats.marketing')); ?></h6>
                    <h2 class="fw-bold text-info mb-0"><?php echo e($stats['marketing_accepted']); ?></h2>
                    <small class="text-muted fs-6">
                        <?php echo e(__('admin::admin.consent_report.stats.of_total', ['percent' => $stats['total_consents'] > 0 ? round(($stats['marketing_accepted'] / $stats['total_consents']) * 100) : 0])); ?>

                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1"><?php echo e(__('admin::admin.consent_report.stats.today')); ?></h6>
                    <h2 class="fw-bold text-warning mb-0"><?php echo e($stats['today_consents']); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Consents Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="fw-bold mb-0"><?php echo e(__('admin::admin.consent_report.recent_title')); ?></h5>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-end">ID</th>
                            <th class="px-4 py-3 border-0"><?php echo e(__('admin::admin.consent_report.table.user')); ?></th>
                            <th class="px-4 py-3 border-0"><?php echo e(__('admin::admin.consent_report.table.ip')); ?></th>
                            <th class="px-4 py-3 border-0 text-center"><?php echo e(__('admin::admin.consent_report.table.analytics')); ?></th>
                            <th class="px-4 py-3 border-0 text-center"><?php echo e(__('admin::admin.consent_report.table.marketing')); ?></th>
                            <th class="px-4 py-3 border-0 text-end"><?php echo e(__('admin::admin.consent_report.table.date')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recent_consents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $consent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-end text-muted">#<?php echo e($consent->id); ?></td>
                            <td class="text-end">
                                <?php if($consent->user_id): ?>
                                    <div class="text-muted small italic"><?php echo e(__('admin::admin.consent_report.table.user_unit', ['id' => $consent->user_id])); ?></div>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e(__('admin::admin.consent_report.table.guest')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <code class="text-muted"><?php echo e($consent->ip_address); ?></code>
                            </td>
                            <td class="text-center">
                                <?php if($consent->analytics_consent): ?>
                                    <span class="badge bg-success">✓ <?php echo e(__('admin::admin.consent_report.table.yes')); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger">✗ <?php echo e(__('admin::admin.consent_report.table.no')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($consent->marketing_consent): ?>
                                    <span class="badge bg-success">✓ <?php echo e(__('admin::admin.consent_report.table.yes')); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger">✗ <?php echo e(__('admin::admin.consent_report.table.no')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div><?php echo e(\Carbon\Carbon::parse($consent->created_at)->format('Y-m-d H:i')); ?></div>
                                <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($consent->created_at)->diffForHumans()); ?></small>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    <h4 class="fw-bold mb-2"><?php echo e(__('admin::admin.consent_report.no_results.title')); ?></h4>
                                    <p class="text-muted mb-0"><?php echo e(__('admin::admin.consent_report.no_results.description')); ?></p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views\admin\consent-report.blade.php ENDPATH**/ ?>