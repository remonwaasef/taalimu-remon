<aside class="sidebar sidebar-default navs-rounded-all sidebar-base">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
        <a href="<?php echo e(route('instructor.dashboard')); ?>" class="navbar-brand">
            <img src="<?php echo e(asset('images/brand/logo-full.png')); ?>" class="rounded-3 shadow-sm p-1" style="max-height: 45px; max-width: 100%;">
            <h4 class="logo-title ms-2 text-truncate" style="max-width: 150px;">Taalimu</h4>
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </i>
        </div>
    </div>
    
    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list" id="sidebar">
            <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('instructor.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('instructor.dashboard')); ?>">
                        <i class="icon"><i class="fas fa-home"></i></i>
                        <span class="item-name"><?php echo e(__('instructor::sidebar.dashboard')); ?></span>
                    </a>
                </li>
                
                <li><hr class="hr-horizontal"></li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('instructor.students.list') ? 'active' : ''); ?>" href="<?php echo e(route('instructor.students.list')); ?>">
                        <i class="icon"><i class="fas fa-user-graduate"></i></i>
                        <span class="item-name"><?php echo e(__('instructor::sidebar.students')); ?></span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('instructor.groups.list') ? 'active' : ''); ?>" href="<?php echo e(route('instructor.groups.list')); ?>">
                        <i class="icon"><i class="fas fa-users"></i></i>
                        <span class="item-name"><?php echo e(__('instructor::sidebar.groups')); ?></span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('instructor.schedules.*') ? 'active' : ''); ?>" href="<?php echo e(route('instructor.schedules.index')); ?>">
                        <i class="icon"><i class="fas fa-calendar-alt"></i></i>
                        <span class="item-name"><?php echo e(__('instructor::sidebar.schedules')); ?></span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('instructor.online_classes.*') ? 'active' : ''); ?>" href="<?php echo e(route('instructor.online_classes.index')); ?>">
                        <i class="icon"><i class="fas fa-video"></i></i>
                        <span class="item-name"><?php echo e(__('instructor::sidebar.online_classes')); ?></span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('instructor.attendance.*') ? 'active' : ''); ?>" href="<?php echo e(route('instructor.attendance.index')); ?>">
                        <i class="icon"><i class="fas fa-clipboard-check"></i></i>
                        <span class="item-name"><?php echo e(__('instructor::sidebar.attendance')); ?></span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('instructor.billing') ? 'active' : ''); ?>" href="<?php echo e(route('instructor.billing')); ?>">
                        <i class="icon"><i class="fas fa-wallet"></i></i>
                        <span class="item-name"><?php echo e(__('instructor::sidebar.billing')); ?></span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('instructor.reports.*') ? 'active' : ''); ?>" data-bs-toggle="collapse" href="#sidebar-reports" role="button" aria-expanded="<?php echo e(request()->routeIs('instructor.reports.*') ? 'true' : 'false'); ?>" aria-controls="sidebar-reports">
                        <i class="icon"><i class="fas fa-chart-line"></i></i>
                        <span class="item-name"><?php echo e(__('instructor::sidebar.reports')); ?></span>
                        <i class="right-icon"><i class="fas fa-chevron-right"></i></i>
                    </a>
                    <ul class="sub-nav collapse <?php echo e(request()->routeIs('instructor.reports.*') ? 'show' : ''); ?>" id="sidebar-reports" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('instructor.reports.students') ? 'active' : ''); ?>" href="<?php echo e(route('instructor.reports.students')); ?>">
                                <i class="icon"><i class="fas fa-user-graduate"></i></i>
                                <span class="item-name"><?php echo e(__('instructor::reports.student_reports')); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('instructor.reports.payments') ? 'active' : ''); ?>" href="<?php echo e(route('instructor.reports.payments')); ?>">
                                <i class="icon"><i class="fas fa-wallet"></i></i>
                                <span class="item-name"><?php echo e(__('instructor::reports.payment_reports')); ?></span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('instructor.settings') || request()->routeIs('instructor.whatsapp.*') ? 'active' : ''); ?>" href="<?php echo e(route('instructor.settings')); ?>">
                        <i class="icon"><i class="fas fa-cog"></i></i>
                        <span class="item-name"><?php echo e(__('instructor::sidebar.settings')); ?></span>
                    </a>
                </li>

                <li class="nav-item mt-5">
                    <form action="<?php echo e(route('center.logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                            <i class="icon"><i class="fas fa-sign-out-alt"></i></i>
                            <span class="item-name"><?php echo e(__('instructor::sidebar.logout')); ?></span>
                        </button>
                    </form>
                </li>

            </ul>
        </div>
    </div>
</aside>
<?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\components\layouts\hope-sidebar.blade.php ENDPATH**/ ?>