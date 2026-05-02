<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
    <div class="card-header bg-white border-bottom-0 p-0">
        <ul class="nav nav-tabs nav-fill" id="schoolManagementTabs" role="tablist">
            
            <li class="nav-item">
                <a href="<?php echo e(route('center.settings.index', ['tenant' => app('tenant')->domain ?? 'center', 'tab' => 'academic'])); ?>" 
                   class="nav-link py-3 fw-bold <?php echo e(request()->routeIs('center.settings.index') && request('tab') == 'academic' ? 'active' : ''); ?>">
                   <i class="fas fa-graduation-cap me-2 text-primary"></i> <?php echo e(__('center::sidebar.academic_setup')); ?>

                </a>
            </li>

            
            <li class="nav-item">
                <a href="<?php echo e(route('center.classrooms.index', ['tenant' => app('tenant')->domain ?? 'center'])); ?>" 
                   class="nav-link py-3 fw-bold <?php echo e(request()->routeIs('center.classrooms.*') ? 'active' : ''); ?>">
                   <i class="fas fa-building me-2 text-success"></i> <?php echo e(__('center::sidebar.classrooms')); ?>

                </a>
            </li>

            
            <li class="nav-item">
                <a href="<?php echo e(route('center.instructors.index', ['tenant' => app('tenant')->domain ?? 'center'])); ?>" 
                   class="nav-link py-3 fw-bold <?php echo e(request()->routeIs('center.instructors.*') ? 'active' : ''); ?>">
                   <i class="fas fa-chalkboard-teacher me-2 text-info"></i> <?php echo e(__('center::sidebar.instructors')); ?>

                </a>
            </li>

            
            <li class="nav-item">
                <a href="<?php echo e(route('center.courses.index', ['tenant' => app('tenant')->domain ?? 'center'])); ?>" 
                   class="nav-link py-3 fw-bold <?php echo e(request()->routeIs('center.courses.*') ? 'active' : ''); ?>">
                   <i class="fas fa-book-open me-2 text-warning"></i> <?php echo e(__('center::sidebar.courses')); ?>

                </a>
            </li>

            
            <li class="nav-item">
                <a href="<?php echo e(route('center.schedules.index', ['tenant' => app('tenant')->domain ?? 'center'])); ?>" 
                   class="nav-link py-3 fw-bold <?php echo e(request()->routeIs('center.schedules.*') ? 'active' : ''); ?>">
                   <i class="fas fa-calendar-alt me-2 text-danger"></i> <?php echo e(__('center::sidebar.schedules')); ?>

                </a>
            </li>
        </ul>
    </div>
</div>

<style>
    #schoolManagementTabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        background: #f8f9fa;
        margin: 0 4px;
        border-radius: 8px 8px 0 0;
    }
    
    #schoolManagementTabs .nav-link:hover {
        background: #e9ecef;
        color: var(--bs-primary);
    }

    #schoolManagementTabs .nav-link.active {
        background: #fff;
        color: var(--bs-primary) !important;
        border-bottom: 3px solid var(--bs-primary);
        box-shadow: 0 -4px 10px rgba(0,0,0,0.05);
    }

    #schoolManagementTabs .nav-link.active i {
        color: var(--bs-primary) !important;
    }
</style>
<?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\layouts\setup_tabs.blade.php ENDPATH**/ ?>