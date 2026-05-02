<?php
    $steps = [
        'education_system' => ['icon' => 'fa-map-signs', 'color' => 'primary', 'route' => 'center.settings.index'],
        'instructor' => ['icon' => 'fa-user-tie', 'color' => 'info', 'route' => 'center.instructors.create'],
        'course' => ['icon' => 'fa-book-open', 'color' => 'warning', 'route' => 'center.courses.create'],
        'student' => ['icon' => 'fa-user-graduate', 'color' => 'success', 'route' => 'center.students.create'],
        'attendance' => ['icon' => 'fa-clipboard-check', 'color' => 'danger', 'route' => 'center.attendance.index'],
    ];

    // Find first incomplete step to highlight it
    $highlightStep = null;
    foreach($launchpadSteps as $key => $isDone) {
        if(!$isDone) {
            $highlightStep = $key;
            break;
        }
    }
?>

<div class="card glass-card border-0 rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(248,249,250,0.8));">
    <!-- Decorative background elements -->
    <div class="position-absolute top-0 end-0 p-3 opacity-10">
        <i class="fas fa-rocket fa-7x transform-rotate-15"></i>
    </div>

    <div class="card-body p-4 position-relative">
        <div class="row align-items-center mb-4">
            <div class="col-lg-7">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <h5 class="fw-bold mb-1 text-dark">🚀 <?php echo e(__('center::dashboard.launchpad.title', ['name' => auth()->user()->name])); ?></h5>
                    <?php
                        $hasDemoData = \App\Models\Instructor::where('tenant_id', app('tenant')->id)->where('email', 'like', '%.demo@%')->exists();
                    ?>

                    <?php if($hasDemoData): ?>
                        <form action="<?php echo e(route('center.demo.reset')); ?>" method="POST" id="demoDataResetForm" onsubmit="return confirm('<?php echo e(__('center::launchpad.confirm_reset')); ?>');">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 border-dotted" 
                                    style="border-style: dashed !important; font-size: 0.7rem;"
                                    data-bs-toggle="tooltip" 
                                    title="<?php echo e(__('center::dashboard.launchpad.reset_demo_desc')); ?>">
                                <i class="fas fa-trash-alt me-1"></i> <?php echo e(__('center::dashboard.launchpad.reset_demo')); ?>

                            </button>
                        </form>
                    <?php else: ?>
                        <form action="<?php echo e(route('center.demo.seed')); ?>" method="POST" id="demoDataForm">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 border-dotted" 
                                    style="border-style: dashed !important; font-size: 0.7rem;"
                                    data-bs-toggle="tooltip" 
                                    title="<?php echo e(__('center::dashboard.launchpad.explore_demo_desc')); ?>">
                                <i class="fas fa-magic me-1"></i> <?php echo e(__('center::dashboard.launchpad.explore_demo')); ?>

                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                <p class="text-muted small mb-0"><?php echo e(__('center::dashboard.launchpad.subtitle')); ?></p>
            </div>
            <div class="col-lg-5">
                <div class="mt-3 mt-lg-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-primary"><?php echo e(__('center::dashboard.launchpad.progress')); ?></span>
                        <span class="badge bg-primary rounded-pill px-3"><?php echo e($launchpadProgress); ?>%</span>
                    </div>
                    <div class="progress shadow-sm" style="height: 12px; border-radius: 10px; background-color: rgba(0,0,0,0.05);">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                             role="progressbar" 
                             style="width: <?php echo e($launchpadProgress); ?>%; border-radius: 10px;" 
                             aria-valuenow="<?php echo e($launchpadProgress); ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isCompleted = $launchpadSteps[$key] ?? false;
                    $isCurrent = ($key === $highlightStep);
                ?>
                <div class="col-md-6 col-xl">
                    <div class="card h-100 border-0 shadow-sm rounded-4 transition-all hover-translate-y-n3 <?php echo e($isCurrent ? 'border-primary border-2' : ''); ?> <?php echo e($isCompleted ? 'bg-success bg-opacity-10' : 'bg-white'); ?>"
                         style="<?php echo e($isCurrent ? 'box-shadow: 0 10px 25px rgba(13, 110, 253, 0.15) !important;' : ''); ?>">
                        <div class="card-body p-4 d-flex flex-column text-center">
                            <!-- Icon and Status Circle -->
                            <div class="position-relative mb-3 mx-auto">
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-<?php echo e($isCompleted ? 'success' : ($isCurrent ? $data['color'] : 'light')); ?> text-<?php echo e($isCompleted || $isCurrent ? 'white' : 'muted'); ?>" 
                                     style="width: 65px; height: 65px; font-size: 1.5rem; transition: all 0.3s ease;">
                                    <i class="fas <?php echo e($isCompleted ? 'fa-check' : $data['icon']); ?>"></i>
                                </div>
                                <?php if($isCurrent): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary border border-white">
                                        <?php echo e(__('center::dashboard.launchpad.action')); ?>

                                        <span class="visually-hidden">current step</span>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Content -->
                            <h6 class="fw-bold mb-1 <?php echo e($isCompleted ? 'text-success' : 'text-dark'); ?>">
                                <?php echo e(__('center::dashboard.launchpad.steps.'.$key.'.title')); ?>

                            </h6>
                            
                            <?php if($isCurrent): ?>
                                <p class="text-muted small mb-3 flex-grow-1">
                                    <?php echo e(__('center::dashboard.launchpad.steps.'.$key.'.desc')); ?>

                                </p>
                            <?php else: ?>
                                <div class="mb-3 flex-grow-1"></div>
                            <?php endif; ?>

                            <!-- Button -->
                            <?php if($isCompleted): ?>
                                <div class="text-success fw-bold x-small">
                                    <i class="fas fa-check-circle me-1"></i><?php echo e(__('center::launchpad.demo_data_ready')); ?></div>
                            <?php elseif($key === 'education_system'): ?>
                                <button type="button" 
                                        class="btn <?php echo e($isCurrent ? 'btn-'.$data['color'] : 'btn-outline-light text-muted border-0'); ?> rounded-pill btn-sm fw-bold px-3 py-1 mt-auto"
                                        style="font-size: 0.75rem;"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#educationSystemModal">
                                   <?php echo e(__('center::dashboard.launchpad.action')); ?>

                                </button>
                            <?php else: ?>
                                <a href="<?php echo e(route($data['route'])); ?>" 
                                   class="btn <?php echo e($isCurrent ? 'btn-'.$data['color'] : 'btn-outline-light text-muted border-0'); ?> rounded-pill btn-sm fw-bold px-3 py-1 mt-auto"
                                   style="font-size: 0.75rem;">
                                   <?php echo e(__('center::dashboard.launchpad.action')); ?>

                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<!-- Quick Education System Setup Modal -->
<div class="modal fade" id="educationSystemModal" tabindex="-1" aria-labelledby="educationSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="educationSystemModalLabel">
                    <i class="fas fa-map-signs text-primary me-2"></i> <?php echo e(__('center::dashboard.launchpad.steps.education_system.title')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('center.settings.apply-template', ['tenant' => app('tenant')->domain])); ?>" method="POST" id="educationSystemForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body px-4 pb-4">
                    <p class="text-muted small mb-4">
                        <?php echo e(__('center::dashboard.launchpad.steps.education_system.desc')); ?>

                    </p>
                    
                    <label class="form-label fw-bold small text-muted mb-2"><?php echo e(__('center::launchpad.select_data_type')); ?></label>
                    <select name="template_key" class="form-select rounded-pill mb-3" required>
                        <option value=""><?php echo e(__('center::launchpad.select_placeholder')); ?></option>
                        <?php $__currentLoopData = config('academic.templates', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tKey => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tKey); ?>"><?php echo e(__($template['name'])); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <div class="alert alert-soft-primary border-0 rounded-3 small py-2 px-3 mb-0">
                        <i class="fas fa-info-circle me-1"></i><?php echo e(__('center::launchpad.demo_data_hint')); ?></div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal"><?php echo e(__('center::launchpad.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" id="submitTemplateBtn">
                        <span class="normal-state">
                            <i class="fas fa-check-circle me-1"></i><?php echo e(__('center::launchpad.start_generation')); ?></span>
                        <span class="loading-state d-none">
                            <i class="fas fa-spinner fa-spin me-1"></i><?php echo e(__('center::launchpad.generating')); ?></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('educationSystemForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitTemplateBtn');
        btn.disabled = true;
        btn.querySelector('.normal-state').classList.add('d-none');
        btn.querySelector('.loading-state').classList.remove('d-none');
    });
</script>

<style>
    .hover-translate-y-n3:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
    }
    .transform-rotate-15 {
        transform: rotate(-15deg);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>

<?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\partials\launchpad.blade.php ENDPATH**/ ?>