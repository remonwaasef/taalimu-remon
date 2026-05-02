

<?php $__env->startSection('title', __('center::analytics.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold"><?php echo e(__('center::analytics.stats_overview')); ?></h1>
            <p class="text-muted mb-0"><?php echo e(__('center::analytics.stats_subtitle')); ?></p>
        </div>
        <div>
            <button class="btn btn-sm btn-primary shadow-sm rounded-pill px-3" onclick="window.print()">
                <i class="fas fa-file-download fa-sm text-white-50 me-2"></i> <?php echo e(__('center::analytics.download_pdf_report')); ?>

            </button>
        </div>
    </div>

    <!-- 1. Summary Cards -->
    <div class="row g-4 mb-4">
        <!-- Row 1: Academic & Operational -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 rounded-4 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1"><?php echo e(__('center::analytics.total_students')); ?></div>
                            <div class="h3 mb-0 fw-bold text-gray-800"><?php echo e($totalStudents); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 rounded-4 border-start border-4 border-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1"><?php echo e(__('center::analytics.total_courses')); ?></div>
                            <div class="h3 mb-0 fw-bold text-gray-800"><?php echo e($totalCourses); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 rounded-4 border-start border-4 border-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1"><?php echo e(__('center::analytics.total_due')); ?></div>
                            <div class="h3 mb-0 fw-bold text-gray-800"><?php echo e(format_price($totalDue)); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Financial (This Month) -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 rounded-4 border-start border-4 border-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1"><?php echo e(__('center::analytics.current_month_revenue')); ?></div>
                            <div class="h3 mb-0 fw-bold text-gray-800"><?php echo e(format_price($monthlyRevenueSum)); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sack-dollar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 rounded-4 border-start border-4 border-danger">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1"><?php echo e(__('center::analytics.current_month_expenses')); ?></div>
                            <div class="h3 mb-0 fw-bold text-gray-800"><?php echo e(format_price($totalExpenses)); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 rounded-4 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1"><?php echo e(__('center::analytics.monthly_net_profit')); ?></div>
                            <div class="h3 mb-0 fw-bold text-<?php echo e(($monthlyRevenueSum - $totalExpenses) >= 0 ? 'success' : 'danger'); ?>"><?php echo e(format_price($monthlyRevenueSum - $totalExpenses)); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optional: All-Time Stats (Small Row) -->
        <div class="col-12 mt-2">
            <div class="card border-0 shadow-sm rounded-4 bg-light border-0">
                <div class="card-body py-2 px-4 d-flex justify-content-between align-items-center overflow-auto">
                    <div class="small text-muted"><i class="fas fa-history me-1"></i> <?php echo e(__('center::analytics.total_revenue_all_time')); ?>: <span class="fw-bold"><?php echo e(format_price($totalRevenue)); ?></span></div>
                                <div class="small text-muted"><i class="fas fa-coins me-1"></i> <?php echo e(__('center::analytics.total_profit_all_time')); ?>: <span class="fw-bold text-success"><?php echo e(format_price($totalRevenue - $totalExpenses)); ?></span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Charts Row 1 -->
    <div class="row g-4 mb-4">
        <!-- Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white border-0 rounded-top-4">
                    <h6 class="m-0 fw-bold text-primary"><?php echo e(__('center::analytics.revenue_growth')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 320px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white border-0 rounded-top-4">
                    <h6 class="m-0 fw-bold text-primary"><?php echo e(__('center::analytics.daily_attendance_ratio')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-2 pb-2" style="height: 250px;">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                    <div class="mt-3 text-center small d-flex justify-content-center gap-3">
                        <span class="mr-2"><i class="fas fa-circle text-success big-dot"></i><?php echo e(__('center::analytics.present')); ?></span>
                        <span class="mr-2"><i class="fas fa-circle text-warning big-dot"></i><?php echo e(__('center::analytics.late')); ?></span>
                        <span class="mr-2"><i class="fas fa-circle text-danger big-dot"></i><?php echo e(__('center::analytics.absent')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Charts Row 2 -->
    <div class="row g-4 mb-4">
        <!-- Popular Courses -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header py-3 bg-white border-0 rounded-top-4">
                    <h6 class="m-0 fw-bold text-primary"><?php echo e(__('center::analytics.popular_courses')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar" style="height: 300px;">
                        <canvas id="popularCoursesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Growth -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header py-3 bg-white border-0 rounded-top-4">
                    <h6 class="m-0 fw-bold text-primary"><?php echo e(__('center::analytics.student_growth_chart')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar" style="height: 300px;">
                        <canvas id="studentGrowthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Recent Sales Table -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header py-3 bg-white border-0 rounded-top-4">
            <h6 class="m-0 fw-bold text-primary"><?php echo e(__('center::analytics.recent_sales')); ?></h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 p-3"><?php echo e(__('center::analytics.transaction_id')); ?></th>
                            <th class="border-0 p-3"><?php echo e(__('center::analytics.student_name')); ?></th>
                            <th class="border-0 p-3"><?php echo e(__('center::analytics.total_amount')); ?></th>
                            <th class="border-0 p-3"><?php echo e(__('center::analytics.paid_amount')); ?></th>
                            <th class="border-0 p-3"><?php echo e(__('center::analytics.status')); ?></th>
                            <th class="border-0 p-3"><?php echo e(__('center::analytics.date')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="p-3 fw-bold text-primary">#<?php echo e($sale->id); ?></td>
                                <td class="p-3"><?php echo e($sale->student->name); ?></td>
                                <td class="p-3 fw-bold"><?php echo e(format_price($sale->total_amount)); ?></td>
                                <td class="p-3 text-success"><?php echo e(format_price($sale->paid_amount)); ?></td>
                                <td class="p-3">
                                    <span class="badge rounded-pill bg-<?php echo e($sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger')); ?> bg-opacity-10 text-<?php echo e($sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger')); ?> px-3">
                                        <?php echo e($sale->status == 'paid' ? __('center::analytics.paid_full') : ($sale->status == 'partial' ? __('center::analytics.paid_partial') : __('center::analytics.not_paid'))); ?>

                                    </span>
                                </td>
                                <td class="p-3 text-muted small"><?php echo e($sale->created_at->format('Y-m-d h:i A')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted"><?php echo e(__('center::analytics.no_recent_sales')); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Cairo', 'Nunito', sans-serif";
    Chart.defaults.color = '#858796';

    // 1. Revenue Chart (Line)
    new Chart(document.getElementById("revenueChart"), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($revenueLabels, 15, 512) ?>,
            datasets: [{
                label: "<?php echo e(__('center::analytics.revenue')); ?>",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: <?php echo json_encode($revenueData, 15, 512) ?>,
            }],
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return '<?php echo e(get_currency_symbol()); ?> ' + value; }
                    }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 2. Attendance Chart (Doughnut)
    new Chart(document.getElementById("attendanceChart"), {
        type: 'doughnut',
        data: {
            labels: ["<?php echo e(__('center::analytics.present')); ?>", "<?php echo e(__('center::analytics.late')); ?>", "<?php echo e(__('center::analytics.absent')); ?>"],
            datasets: [{
                data: <?php echo json_encode($attendanceData, 15, 512) ?>,
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                hoverBackgroundColor: ['#17a673', '#dda20a', '#be2617'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false }
            }
        },
    });

    // 3. Popular Courses (Bar)
    new Chart(document.getElementById("popularCoursesChart"), {
        type: 'bar', // or 'horizontalBar' in older chart.js, but v3+ uses indexAxis
        data: {
            labels: <?php echo json_encode($popularCoursesLabels, 15, 512) ?>,
            datasets: [{
                label: "<?php echo e(__('center::subscription.features.max_students')); ?>",
                backgroundColor: "#4e73df",
                hoverBackgroundColor: "#2e59d9",
                borderColor: "#4e73df",
                data: <?php echo json_encode($popularCoursesData, 15, 512) ?>,
                barThickness: 30,
            }],
        },
        options: {
            indexAxis: 'y', // Makes it horizontal
            maintainAspectRatio: false,
            scales: {
                x: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 4. Student Growth (Bar)
    new Chart(document.getElementById("studentGrowthChart"), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($growthLabels, 15, 512) ?>,
            datasets: [{
                label: "<?php echo e(__('center::analytics.new_students')); ?>",
                backgroundColor: "#36b9cc",
                hoverBackgroundColor: "#2c9faf",
                borderColor: "#36b9cc",
                data: <?php echo json_encode($growthData, 15, 512) ?>,
                barThickness: 40,
            }],
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
<style>
    .rounded-4 { border-radius: 1rem !important; }
    .big-dot { font-size: 0.8rem; vertical-align: middle; }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\analytics\index.blade.php ENDPATH**/ ?>