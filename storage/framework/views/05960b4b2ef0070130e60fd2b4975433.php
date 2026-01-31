<div class="card glass-card border-0 rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h4 class="fw-bold mb-2">🚀 <?php echo e(__('center::dashboard.launchpad.title', ['name' => auth()->user()->name])); ?></h4>
                <p class="text-muted mb-4"><?php echo e(__('center::dashboard.launchpad.subtitle')); ?></p>

                <div class="progress mb-3" style="height: 10px; border-radius: 10px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo e($launchpadProgress); ?>%" aria-valuenow="<?php echo e($launchpadProgress); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between text-muted small fw-bold mb-4">
                    <span><?php echo e(__('center::dashboard.launchpad.progress')); ?></span>
                    <span><?php echo e($launchpadProgress); ?>%</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 rounded-3 <?php echo e($launchpadSteps['profile'] ? 'bg-success bg-opacity-10 text-success' : 'bg-light text-muted'); ?>">
                            <div class="flex-shrink-0">
                                <i class="fas <?php echo e($launchpadSteps['profile'] ? 'fa-check-circle' : 'fa-circle'); ?> fa-lg"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold"><?php echo e(__('center::dashboard.launchpad.steps.profile')); ?></h6>
                            </div>
                            <?php if(!$launchpadSteps['profile']): ?>
                                <a href="<?php echo e(route('center.settings.index')); ?>" class="btn btn-sm btn-white shadow-sm rounded-pill fw-bold"><?php echo e(__('center::dashboard.launchpad.action')); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 rounded-3 <?php echo e($launchpadSteps['instructor'] ? 'bg-success bg-opacity-10 text-success' : 'bg-light text-muted'); ?>">
                            <div class="flex-shrink-0">
                                <i class="fas <?php echo e($launchpadSteps['instructor'] ? 'fa-check-circle' : 'fa-circle'); ?> fa-lg"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold"><?php echo e(__('center::dashboard.launchpad.steps.instructor')); ?></h6>
                            </div>
                            <?php if(!$launchpadSteps['instructor']): ?>
                                <a href="<?php echo e(route('center.instructors.create')); ?>" class="btn btn-sm btn-white shadow-sm rounded-pill fw-bold"><?php echo e(__('center::dashboard.launchpad.action')); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 rounded-3 <?php echo e($launchpadSteps['course'] ? 'bg-success bg-opacity-10 text-success' : 'bg-light text-muted'); ?>">
                            <div class="flex-shrink-0">
                                <i class="fas <?php echo e($launchpadSteps['course'] ? 'fa-check-circle' : 'fa-circle'); ?> fa-lg"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold"><?php echo e(__('center::dashboard.launchpad.steps.course')); ?></h6>
                            </div>
                            <?php if(!$launchpadSteps['course']): ?>
                                <a href="<?php echo e(route('center.courses.create')); ?>" class="btn btn-sm btn-white shadow-sm rounded-pill fw-bold"><?php echo e(__('center::dashboard.launchpad.action')); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 rounded-3 <?php echo e($launchpadSteps['student'] ? 'bg-success bg-opacity-10 text-success' : 'bg-light text-muted'); ?>">
                            <div class="flex-shrink-0">
                                <i class="fas <?php echo e($launchpadSteps['student'] ? 'fa-check-circle' : 'fa-circle'); ?> fa-lg"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold"><?php echo e(__('center::dashboard.launchpad.steps.student')); ?></h6>
                            </div>
                            <?php if(!$launchpadSteps['student']): ?>
                                <a href="<?php echo e(route('center.students.create')); ?>" class="btn btn-sm btn-white shadow-sm rounded-pill fw-bold"><?php echo e(__('center::dashboard.launchpad.action')); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-4 d-none d-lg-block text-center">
                <i class="fas fa-rocket text-primary opacity-25" style="font-size: 10rem;"></i>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\new project\antigravty\edu\edu\Modules/Center\resources/views/partials/launchpad.blade.php ENDPATH**/ ?>