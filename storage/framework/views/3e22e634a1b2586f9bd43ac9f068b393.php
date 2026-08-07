<?php
    use Illuminate\Support\Facades\Route;

    $tenant = app('tenant');
    $domain = $tenant->domain ?? 'center';

    $canInstructors = ($tenant->getFeatureValue('max_instructors') != '0' && $tenant->getFeatureValue('max_instructors') !== false) && auth()->user()->can('view instructors');
    $canCourses = ($tenant->getFeatureValue('max_courses') != '0' && $tenant->getFeatureValue('max_courses') !== false) && auth()->user()->can('view courses');
    $canClassrooms = ($tenant->getFeatureValue('max_classrooms') != '0' && $tenant->getFeatureValue('max_classrooms') !== false) && auth()->user()->can('view schedule');
    $canSchedules = $tenant->getFeatureValue('daily_schedules') === true && auth()->user()->can('view schedule');
    $canStudents = ($tenant->getFeatureValue('max_students') != '0' && $tenant->getFeatureValue('max_students') !== false) && auth()->user()->can('view students');
    $canAttendance = $tenant->getFeatureValue('attendance_tracking') && auth()->user()->can('view attendance');
    $hasFinancialReports = $tenant->getFeatureValue('financial_reports') && auth()->user()->canAny(['view sales', 'view expenses']);
    $hasAdvancedReports = $tenant->getFeatureValue('advanced_reports') && auth()->user()->can('view reports');

    $isClassesActive = Route::currentRouteNamed(['center.classrooms.*', 'center.instructors.*', 'center.courses.*', 'center.online_classes.*', 'center.schedules.*']);
    $isReportsActive = Route::currentRouteNamed(['center.analytics.*', 'center.expenses.*']);
    $isSettingsActive = Route::currentRouteNamed(['center.assets.*', 'center.settings.*', 'center.users.*', 'center.roles.*', 'center.branches.*', 'center.tickets.*', 'center.subscription.*']);

    $showClasses = ($canInstructors || $canCourses || $canClassrooms || $canSchedules) && ($tenant->type !== 'instructor');
?>

<?php if (isset($component)) { $__componentOriginal724cca1d6cfbd0d9b219a0d1bdb2d9a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal724cca1d6cfbd0d9b219a0d1bdb2d9a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.sidebar','data' => ['brandName' => ''.e($tenant->name ?? 'Taalimu Center').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['brandName' => ''.e($tenant->name ?? 'Taalimu Center').'']); ?>
    <div class="space-y-1">
        <a href="<?php echo e(route('center.dashboard', ['tenant' => $domain])); ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  <?php echo e(Route::currentRouteNamed('center.dashboard') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800'); ?>">
            <i class="fas fa-home w-4 text-center"></i>
            <span><?php echo e(__('center::sidebar.dashboard')); ?></span>
        </a>

        <?php if($showClasses): ?>
        <div x-data="{ open: <?php echo e($isClassesActive ? 'true' : 'false'); ?> }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                           <?php echo e($isClassesActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800'); ?>">
                <span class="flex items-center gap-3">
                    <i class="fas fa-university w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.classes_structure') ?? 'Classes & Structure'); ?></span>
                </span>
                <i class="fas fa-chevron-right text-[10px] transition-transform" :class="{ 'rotate-90': open }"></i>
            </button>
            <div x-show="open" class="mt-1 space-y-1 ms-4">
                <?php if($canClassrooms): ?>
                <a href="<?php echo e(route('center.classrooms.index', ['tenant' => $domain])); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          <?php echo e(Route::currentRouteNamed('center.classrooms.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'); ?>">
                    <i class="fas fa-door-open w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.classrooms')); ?></span>
                </a>
                <?php endif; ?>
                <?php if($canSchedules): ?>
                <a href="<?php echo e(route('center.schedules.index', ['tenant' => $domain])); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          <?php echo e(Route::currentRouteNamed('center.schedules.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'); ?>">
                    <i class="fas fa-calendar-alt w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.schedules')); ?></span>
                </a>
                <?php endif; ?>
                <?php if($canCourses): ?>
                <a href="<?php echo e(route('center.courses.index', ['tenant' => $domain])); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          <?php echo e(Route::currentRouteNamed('center.courses.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'); ?>">
                    <i class="fas fa-book w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.courses')); ?></span>
                </a>
                <?php endif; ?>
                <?php if($canInstructors): ?>
                <a href="<?php echo e(route('center.instructors.index', ['tenant' => $domain])); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          <?php echo e(Route::currentRouteNamed('center.instructors.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'); ?>">
                    <i class="fas fa-chalkboard-teacher w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.instructors')); ?></span>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if($canStudents): ?>
        <a href="<?php echo e(route('center.students.index', ['tenant' => $domain])); ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  <?php echo e(Route::currentRouteNamed('center.students.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800'); ?>">
            <i class="fas fa-user-graduate w-4 text-center"></i>
            <span><?php echo e(__('center::sidebar.students') ?? 'Students'); ?></span>
        </a>
        <?php endif; ?>

        <?php if($canAttendance): ?>
        <a href="<?php echo e(route('center.attendance.index', ['tenant' => $domain])); ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  <?php echo e(Route::currentRouteNamed('center.attendance.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800'); ?>">
            <i class="fas fa-clipboard-check w-4 text-center"></i>
            <span><?php echo e(__('center::sidebar.attendance') ?? 'Attendance'); ?></span>
        </a>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view sales')): ?>
        <a href="<?php echo e(route('center.sales.account', ['tenant' => $domain])); ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  <?php echo e(Route::currentRouteNamed('center.sales.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800'); ?>">
            <i class="fas fa-wallet w-4 text-center"></i>
            <span><?php echo e(__('center::sidebar.payments') ?? 'Payments & Invoices'); ?></span>
        </a>
        <?php endif; ?>

        <?php if($hasFinancialReports || $hasAdvancedReports): ?>
        <div x-data="{ open: <?php echo e($isReportsActive ? 'true' : 'false'); ?> }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                           <?php echo e($isReportsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800'); ?>">
                <span class="flex items-center gap-3">
                    <i class="fas fa-chart-bar w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.reports') ?? 'Reports & Expenses'); ?></span>
                </span>
                <i class="fas fa-chevron-right text-[10px] transition-transform" :class="{ 'rotate-90': open }"></i>
            </button>
            <div x-show="open" class="mt-1 space-y-1 ms-4">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view expenses')): ?>
                <a href="<?php echo e(route('center.expenses.index', ['tenant' => $domain])); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          <?php echo e(Route::currentRouteNamed('center.expenses.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'); ?>">
                    <i class="fas fa-file-invoice w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.expenses')); ?></span>
                </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view reports', 'view analytics'])): ?>
                <a href="<?php echo e(route('center.analytics.finance')); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          <?php echo e(Route::currentRouteNamed('center.analytics.finance') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'); ?>">
                    <i class="fas fa-chart-line w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.financial_analytics')); ?></span>
                </a>
                <a href="<?php echo e(route('center.analytics.commissions')); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          <?php echo e(Route::currentRouteNamed('center.analytics.commissions') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'); ?>">
                    <i class="fas fa-percentage w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.financial_commissions')); ?></span>
                </a>
                <?php endif; ?>
                <?php if($hasAdvancedReports): ?>
                <a href="<?php echo e(route('center.analytics.index')); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          <?php echo e(Route::currentRouteNamed('center.analytics.index') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'); ?>">
                    <i class="fas fa-chart-pie w-4 text-center"></i>
                    <span><?php echo e(__('center::analytics.general')); ?></span>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['manage users', 'manage settings', 'manage billing'])): ?>
        <div x-data="{ open: <?php echo e($isSettingsActive ? 'true' : 'false'); ?> }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                           <?php echo e($isSettingsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800'); ?>">
                <span class="flex items-center gap-3">
                    <i class="fas fa-cogs w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.settings')); ?></span>
                </span>
                <i class="fas fa-chevron-right text-[10px] transition-transform" :class="{ 'rotate-90': open }"></i>
            </button>
            <div x-show="open" class="mt-1 space-y-1 ms-4">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage billing')): ?>
                <a href="<?php echo e(route('center.subscription.index', ['tenant' => $domain])); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          <?php echo e(Route::currentRouteNamed('center.subscription.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'); ?>">
                    <i class="fas fa-credit-card w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.subscription')); ?></span>
                    <?php
                        $subEndsAt = app('tenant')->subscriptions?->last()?->ends_at;
                        $daysLeft = $subEndsAt ? max(0, now()->diffInDays($subEndsAt, false)) : null;
                    ?>
                    <?php if($daysLeft !== null && $daysLeft <= 7): ?>
                        <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full ms-auto"><?php echo e($daysLeft); ?>d</span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage settings')): ?>
                <a href="<?php echo e(route('center.settings.index', ['tenant' => $domain, 'tab' => 'general'])); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          <?php echo e(Route::currentRouteNamed('center.settings.index') && (request('tab') == 'general' || !request('tab')) ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'); ?>">
                    <i class="fas fa-sliders-h w-4 text-center"></i>
                    <span><?php echo e(__('center::settings.tabs.general')); ?></span>
                </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage users')): ?>
                <a href="<?php echo e(route('center.users.index', ['tenant' => $domain])); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          <?php echo e(Route::currentRouteNamed('center.users.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'); ?>">
                    <i class="fas fa-users w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.users')); ?></span>
                </a>
                <?php if($tenant->getFeatureValue('advanced_roles')): ?>
                <a href="<?php echo e(route('center.roles.index', ['tenant' => $domain])); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          <?php echo e(Route::currentRouteNamed('center.roles.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'); ?>">
                    <i class="fas fa-shield-alt w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.permissions')); ?></span>
                </a>
                <?php endif; ?>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage settings')): ?>
                <?php if($tenant->getFeatureValue('multi_branch')): ?>
                <a href="<?php echo e(route('center.branches.index', ['tenant' => $domain])); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          <?php echo e(Route::currentRouteNamed('center.branches.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'); ?>">
                    <i class="fas fa-code-branch w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.branches')); ?></span>
                </a>
                <?php endif; ?>
                <?php endif; ?>
                <a href="<?php echo e(route('center.tickets.index', ['tenant' => $domain])); ?>"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          <?php echo e(Route::currentRouteNamed('center.tickets.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'); ?>">
                    <i class="fas fa-headset w-4 text-center"></i>
                    <span><?php echo e(__('center::sidebar.support')); ?></span>
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>

     <?php $__env->slot('footer', null, []); ?> 
        <form method="POST" action="<?php echo e(route('center.logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors">
                <i class="fas fa-sign-out-alt w-4 text-center"></i>
                <span><?php echo e(__('center::sidebar.logout') ?? 'Sign Out'); ?></span>
            </button>
        </form>
     <?php $__env->endSlot(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal724cca1d6cfbd0d9b219a0d1bdb2d9a8)): ?>
<?php $attributes = $__attributesOriginal724cca1d6cfbd0d9b219a0d1bdb2d9a8; ?>
<?php unset($__attributesOriginal724cca1d6cfbd0d9b219a0d1bdb2d9a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal724cca1d6cfbd0d9b219a0d1bdb2d9a8)): ?>
<?php $component = $__componentOriginal724cca1d6cfbd0d9b219a0d1bdb2d9a8; ?>
<?php unset($__componentOriginal724cca1d6cfbd0d9b219a0d1bdb2d9a8); ?>
<?php endif; ?>
<?php /**PATH D:\new project\antigravty\taalimu.com\taalimu.com\Modules/Center\resources/views/partials/_sidebar-next.blade.php ENDPATH**/ ?>