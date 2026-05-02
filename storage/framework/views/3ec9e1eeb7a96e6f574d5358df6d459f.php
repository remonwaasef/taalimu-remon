<?php $__env->startSection('page-title', __('instructor::dashboard.title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .stats-card {
        background-color: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.03);
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
    }
    .hover-lift {
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03);
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.1);
    }
    .action-card {
        background: #ffffff !important;
        border: 1px solid #f1f5f9 !important;
    }
    .action-card:hover {
        border-color: rgba(16, 185, 129, 0.2) !important;
    }
    .hover-lift i {
        background: #f8fafc;
        padding: 12px;
        border-radius: 12px;
    }
    .empty-state-container {
        background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        border-radius: 1rem;
        border: 1px dashed #cbd5e1;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-4 mb-5">
        <div class="col-md-4" id="tour-stats-students">
            <div class="stats-card p-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-1"><?php echo e(__('instructor::dashboard.total_students')); ?></h6>
                        <h2 class="fw-bold mb-0 count-up text-primary"><?php echo e(number_format($totalStudents)); ?></h2>
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(16, 185, 129, 0.08);">
                        <i class="fas fa-user-graduate text-primary fs-4"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 4px; background: rgba(16, 185, 129, 0.05);">
                    <div class="progress-bar bg-primary" style="width: 70%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4" id="tour-stats-groups">
            <div class="stats-card p-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-1"><?php echo e(__('instructor::dashboard.active_groups')); ?></h6>
                        <h2 class="fw-bold mb-0 count-up text-success"><?php echo e(number_format($totalCourses)); ?></h2>
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(34, 197, 94, 0.08);">
                        <i class="fas fa-users text-success fs-4"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 4px; background: rgba(34, 197, 94, 0.05);">
                    <div class="progress-bar bg-success" style="width: 45%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card p-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-1"><?php echo e(__('instructor::dashboard.monthly_revenue')); ?></h6>
                        <h2 class="fw-bold mb-0">
                            <span class="count-up text-info"><?php echo e(number_format($monthlyRevenue)); ?></span>
                            <small class="fs-6 fw-normal text-muted"><?php echo e(app('tenant')->settings['currency'] ?? 'EGP'); ?></small>
                        </h2>
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(13, 202, 240, 0.08);">
                        <i class="fas fa-wallet text-info fs-4"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 4px; background: rgba(13, 202, 240, 0.05);">
                    <div class="progress-bar bg-info" style="width: 60%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="stats-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0"><?php echo e(__('instructor::dashboard.attendance_analytics')); ?></h5>
                    <span class="badge bg-light text-primary rounded-pill px-3"><?php echo e(__('instructor::dashboard.last_7_days')); ?></span>
                </div>
                <div style="position: relative; height: 280px; width: 100%;">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4" id="tour-quick-links">
            <div class="stats-card p-4 h-100">
                <h5 class="fw-bold mb-4"><?php echo e(__('instructor::dashboard.quick_links')); ?></h5>
                <div class="d-grid gap-3">
                    <a href="<?php echo e(route('instructor.students.create')); ?>" class="btn btn-light action-card text-start p-3 rounded-4 border-0 hover-lift">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-plus-circle text-primary me-3 fs-5"></i>
                            <div>
                                <div class="fw-bold text-dark"><?php echo e(__('instructor::dashboard.add_new_student')); ?></div>
                            </div>
                        </div>
                    </a>
                    <a href="<?php echo e(route('instructor.groups.create')); ?>" class="btn btn-light action-card text-start p-3 rounded-4 border-0 hover-lift">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-folder-plus text-success me-3 fs-5"></i>
                            <div>
                                <div class="fw-bold text-dark"><?php echo e(__('instructor::dashboard.create_new_group')); ?></div>
                            </div>
                        </div>
                    </a>
                    <a href="<?php echo e(route('instructor.attendance.index')); ?>" class="btn btn-light action-card text-start p-3 rounded-4 border-0 hover-lift">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-qrcode text-info me-3 fs-5"></i>
                            <div>
                                <div class="fw-bold text-dark"><?php echo e(__('instructor::dashboard.smart_attendance')); ?></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Groups Section -->
    <div class="stats-card p-4 mb-5" id="tour-groups-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0"><?php echo e(__('instructor::dashboard.groups_and_registration')); ?></h5>
        </div>
        
        <?php if($courses->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 rounded-start-3 px-4"><?php echo e(__('instructor::dashboard.group')); ?></th>
                        <th class="border-0"><?php echo e(__('instructor::dashboard.students')); ?></th>
                        <th class="border-0"><?php echo e(__('instructor::dashboard.registration_link')); ?></th>
                        <th class="border-0 rounded-end-3 text-end px-4"><?php echo e(__('instructor::dashboard.actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-4">
                            <div class="fw-bold text-dark"><?php echo e($course->title); ?></div>
                            <small class="text-muted"><?php echo e($course->schedules->count()); ?> <?php echo e(__('instructor::dashboard.attendees_count')); ?></small>
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">
                                <?php echo e($course->enrollments_count ?? 0); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($course->registration_token): ?>
                            <div class="input-group input-group-sm" style="max-width: 250px;">
                                <input type="text" class="form-control bg-white" value="<?php echo e(route('student.portal', $course->registration_token)); ?>" id="link-<?php echo e($course->id); ?>" readonly>
                                <button class="btn btn-primary px-3" onclick="copyLink('link-<?php echo e($course->id); ?>')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <?php else: ?>
                            <span class="text-muted small"><?php echo e(__('instructor::dashboard.no_link')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end px-4">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                    <li><a class="dropdown-item py-2" href="<?php echo e(route('instructor.scanner', $course->id)); ?>"><i class="fas fa-qrcode me-2 text-primary"></i> <?php echo e(__('instructor::dashboard.qr_scanner')); ?></a></li>
                                    <li><a class="dropdown-item py-2" href="<?php echo e(route('instructor.groups.edit', $course->id)); ?>"><i class="fas fa-edit me-2 text-success"></i> <?php echo e(__('instructor::dashboard.edit_data')); ?></a></li>
                                    <li>
                                        <form action="<?php echo e(route('instructor.groups.duplicate', $course->id)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="dropdown-item py-2"><i class="fas fa-copy me-2 text-info"></i> <?php echo e(__('instructor::dashboard.duplicate_group')); ?></button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="<?php echo e(route('instructor.groups.rotate-link', $course->id)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('instructor::dashboard.confirm_rotate_link')); ?>')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="dropdown-item py-2 text-warning"><i class="fas fa-sync-alt me-2"></i> <?php echo e(__('instructor::dashboard.generate_new_link')); ?></button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="<?php echo e(route('instructor.groups.destroy', $course->id)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('instructor::dashboard.confirm_delete_group')); ?>')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="dropdown-item py-2 text-danger"><i class="fas fa-trash-alt me-2"></i> <?php echo e(__('instructor::dashboard.delete_group')); ?></button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5 empty-state-container">
            <div class="mb-4">
                <div class="d-inline-flex p-4 rounded-circle mb-3" style="background: rgba(16, 185, 129, 0.05);">
                    <i class="fas fa-layer-group text-primary" style="font-size: 3.5rem; opacity: 0.8;"></i>
                </div>
            </div>
            <h4 class="fw-bold text-dark mb-2"><?php echo e(__('instructor::dashboard.no_groups')); ?></h4>
            <p class="text-muted mb-4 mx-auto" style="max-width: 400px;">
                <?php echo e(__('instructor::dashboard.no_groups_desc')); ?>

            </p>
            <a href="<?php echo e(route('instructor.groups.create')); ?>" class="btn btn-primary px-4 py-2 rounded-4 hover-lift fw-bold shadow-sm">
                <i class="fas fa-plus me-2"></i> <?php echo e(__('instructor::dashboard.create_new_group')); ?>

            </a>
        </div>
        <?php endif; ?>
    </div>
<?php $__env->startPush('scripts'); ?>
<!-- Driver.js CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
<?php
    $isRtl = app()->getLocale() == 'ar';
?>
<style>
    /* Custom style for driver js */
    .driver-popover {
        font-family: inherit !important;
        text-align: <?php echo e($isRtl ? 'right' : 'left'); ?>;
        direction: <?php echo e($isRtl ? 'rtl' : 'ltr'); ?>;
    }
    .driver-popover-title {
        color: #10b981 !important;
        font-weight: 700 !important;
        margin-bottom: 10px !important;
    }
    .driver-popover-progress-text {
        direction: ltr; /* Always LTR for numbers like 1 / 4 */
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Interactive Tour Setup
    const tourKey = 'instructor_tour_v3_<?php echo e(auth()->id()); ?>';
    if (!localStorage.getItem(tourKey)) {
        const driver = window.driver.js.driver;
        const driverObj = driver({
            showProgress: true,
            progressText: '<?php echo __("instructor::dashboard.tour.progress", ["current" => "{{current}}", "total" => "{{total}}"]); ?>',
            nextBtnText: '<?php echo e(__('instructor::dashboard.tour.next')); ?>',
            prevBtnText: '<?php echo e(__('instructor::dashboard.tour.prev')); ?>',
            doneBtnText: '<?php echo e(__('instructor::dashboard.tour.done')); ?>',
            popoverClass: 'driverjs-theme',
            allowClose: false,
            steps: [
                { 
                    popover: { 
                        title: '<?php echo e(__('instructor::dashboard.tour.welcome_title')); ?>', 
                        description: '<?php echo e(__('instructor::dashboard.tour.welcome_desc')); ?>' 
                    } 
                },
                { 
                    element: '#tour-groups-section', 
                    popover: { 
                        title: '<?php echo e(__('instructor::dashboard.tour.groups_title')); ?>', 
                        description: '<?php echo e(__('instructor::dashboard.tour.groups_desc')); ?>',
                    }
                },
                { 
                    element: '#tour-quick-links', 
                    popover: { 
                        title: '<?php echo e(__('instructor::dashboard.tour.quick_links_title')); ?>', 
                        description: '<?php echo e(__('instructor::dashboard.tour.quick_links_desc')); ?>',
                    }
                },
                { 
                    element: '#tour-stats-students', 
                    popover: { 
                        title: '<?php echo e(__('instructor::dashboard.tour.stats_title')); ?>', 
                        description: '<?php echo e(__('instructor::dashboard.tour.stats_desc')); ?>' 
                    } 
                }
            ],
            onDestroyStarted: () => {
                if (!driverObj.hasNextStep() || confirm("<?php echo e(__('instructor::dashboard.tour.skip_confirm')); ?>")) {
                    localStorage.setItem(tourKey, 'true');
                    driverObj.destroy();
                }
            },
        });
        
        setTimeout(() => {
            driverObj.drive();
        }, 1200);
    }
});

function copyLink(id) {
    var copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    
    // Change button icon to checkmark temporarily
    var btn = copyText.nextElementSibling;
    var originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i>';
    btn.classList.add('btn-success');
    btn.classList.remove('btn-primary');
    
    setTimeout(function() {
        btn.innerHTML = originalHTML;
        btn.classList.remove('btn-success');
        btn.classList.add('btn-primary');
    }, 2000);
}

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($days); ?>,
                datasets: [{
                    label: '<?php echo e(__('instructor::dashboard.attendees_count')); ?>',
                    data: <?php echo json_encode($attendanceData); ?>,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#10b981',
                    borderWidth: 3,
                    pointHoverRadius: 7,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#0f172a',
                        padding: 12,
                        titleFont: { family: 'Cairo', size: 14 },
                        bodyFont: { family: 'Cairo', size: 13 },
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                        ticks: { 
                            stepSize: 1,
                            font: { family: 'Cairo' }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Cairo' } }
                    }
                }
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('instructor::components.layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\index.blade.php ENDPATH**/ ?>