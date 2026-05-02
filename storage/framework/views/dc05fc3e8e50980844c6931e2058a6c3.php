

<?php $__env->startSection('title', __('center::analytics.student_analytics_title')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold"><?php echo e(__('center::analytics.student_analytics_title')); ?></h1>
            <p class="text-muted mb-0"><?php echo e(__('center::analytics.student_analytics_subtitle')); ?></p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-white border shadow-sm rounded-pill px-3" onclick="window.print()">
                <i class="fas fa-print me-2"></i><?php echo e(__('center::analytics.print_report')); ?></button>
            <a href="<?php echo e(route('center.analytics.index')); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                <i class="fas fa-arrow-left me-2"></i><?php echo e(__('center::messages.back')); ?></a>
        </div>
    </div>

    <!-- 1. Summary Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Students -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 rounded-4 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1"><?php echo e(__('center::analytics.total_students')); ?></div>
                            <div class="h3 mb-0 fw-bold text-gray-800"><?php echo e($totalStudents); ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-user-graduate fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Students -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 rounded-4 border-start border-4 border-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1"><?php echo e(__('center::analytics.active_students')); ?></div>
                            <div class="h3 mb-0 fw-bold text-gray-800"><?php echo e($activeStudents); ?></div>
                            <small class="text-muted"><?php echo e(__('center::analytics.active_students_hint')); ?></small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success bg-opacity-10 text-success">
                                <i class="fas fa-user-check fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inactive Students -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 rounded-4 border-start border-4 border-danger">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1"><?php echo e(__('center::analytics.inactive_students')); ?></div>
                            <div class="h3 mb-0 fw-bold text-gray-800"><?php echo e($inactiveStudents); ?></div>
                            <small class="text-muted"><?php echo e(__('center::analytics.inactive_students_hint')); ?></small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-danger bg-opacity-10 text-danger">
                                <i class="fas fa-user-slash fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Growth Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header py-3 bg-white border-0 rounded-top-4 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary"><?php echo e(__('center::analytics.student_growth')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 320px;">
                        <canvas id="studentGrowthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demographics / Status Chart -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header py-3 bg-white border-0 rounded-top-4">
                    <h6 class="m-0 fw-bold text-primary"><?php echo e(__('center::analytics.grade_distribution')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-2 pb-2" style="height: 250px;">
                        <canvas id="gradeDistributionChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small text-muted"><?php echo e(__('center::analytics.grade_distribution_hint')); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Tables Row -->
    <div class="row g-4">
        <!-- Top Spenders -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header py-3 bg-white border-0 rounded-top-4">
                    <h6 class="m-0 fw-bold text-success">
                        <i class="fas fa-crown me-2"></i><?php echo e(__('center::analytics.top_spenders')); ?></h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 p-3"><?php echo e(__('center::analytics.student_name') ?? __('center::messages.blade_0091')); ?></th>
                                    <th class="border-0 p-3 text-end"><?php echo e(__('center::analytics.total_payments')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $topStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary text-white me-2">
                                                    <?php echo e(substr($student->name, 0, 1)); ?>

                                                </div>
                                                <div>
                                                    <a href="<?php echo e(route('center.students.show', $student->id)); ?>" class="fw-bold text-decoration-none text-gray-800 hover-primary">
                                                        <?php echo e($student->name); ?>

                                                    </a>
                                                    <small class="text-muted d-block"><?php echo e($student->email); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-3 text-end fw-bold text-success">
                                            <?php echo e(format_price($student->sales_count > 0 ? $student->sales->sum('paid_amount') : 0)); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="2" class="text-center py-4 text-muted"><?php echo e(__('center::messages.blade_0093')); ?></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debtors -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header py-3 bg-white border-0 rounded-top-4">
                    <h6 class="m-0 fw-bold text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo e(__('center::analytics.debtors')); ?></h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 p-3"><?php echo e(__('center::analytics.student_name') ?? __('center::messages.blade_0095')); ?></th>
                                    <th class="border-0 p-3 text-end"><?php echo e(__('center::analytics.due_amount')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $debtorStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-danger text-white me-2">
                                                    <?php echo e(substr($student->name, 0, 1)); ?>

                                                </div>
                                                <div>
                                                    <a href="<?php echo e(route('center.students.show', $student->id)); ?>" class="fw-bold text-decoration-none text-gray-800 hover-primary">
                                                        <?php echo e($student->name); ?>

                                                    </a>
                                                    <small class="text-muted d-block"><?php echo e($student->phone); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-3 text-end fw-bold text-danger">
                                            <?php echo e(format_price($student->total_debt)); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="2" class="text-center py-4 text-muted"><?php echo e(__('center::analytics.no_debtors')); ?></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .icon-circle {
        width: 48px; height: 48px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }
    .avatar-circle {
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; font-weight: bold;
    }
    .card-header { border-bottom: 1px solid rgba(0,0,0,0.05) !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Cairo', 'Nunito', sans-serif";
    
    // 1. Growth Chart
    var ctxGrowth = document.getElementById("studentGrowthChart");
    new Chart(ctxGrowth, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($studentGrowth->pluck('months'), 15, 512) ?>,
            datasets: [{
                label: "<?php echo e(__('center::analytics.new_students_chart_label')); ?>",
                backgroundColor: "#4e73df",
                hoverBackgroundColor: "#2e59d9",
                borderRadius: 5,
                data: <?php echo json_encode($studentGrowth->pluck('count'), 15, 512) ?>,
                barThickness: 30,
            }],
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2], drawBorder: false } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // 2. Grade Distribution Chart
    var ctxGrade = document.getElementById("gradeDistributionChart");
    
    // Prepare data
    var gradeLabels = [];
    var gradeData = [];
    
    <?php $__currentLoopData = $studentsByGrade; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        // Use centralized grade names from academic lang file
        var label = "<?php echo e(__('center::academic.grades.' . $grade->grade_level)); ?>"; 
        
        gradeLabels.push(label);
        gradeData.push(<?php echo e($grade->count); ?>);
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    new Chart(ctxGrade, {
        type: 'doughnut',
        data: {
            labels: gradeLabels,
            datasets: [{
                data: gradeData,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#2c9faf', '#17a673'],
                borderWidth: 0
            }],
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\analytics\students.blade.php ENDPATH**/ ?>