<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', __('center::dashboard.header.dashboard_title')); ?></title>
    
    <?php if($tenant->favicon): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('storage/' . $tenant->favicon)); ?>">
    <?php endif; ?>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <?php if(app()->getLocale() == 'ar'): ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <?php else: ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php endif; ?>
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- PWA Support -->
    <link rel="manifest" href="/manifest.json?v=1.1">
    <meta name="theme-color" content="#3A0CA3">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Taalimu">
    
    
    
    <style>
        :root {
            /* Power Palette - Academic Blue Theme */
            --primary-color: #162963;       /* Academic Blue */
            --primary-light: #1e3a8a;      /* Royal Blue */
            --primary-gradient: linear-gradient(135deg, #162963 0%, #1e3a8a 100%);
            
            /* Sidebar - Luxurious Dark Aesthetic */
            --sidebar-width: 280px;
            --sidebar-bg: #0f172a;          /* Slate 900 */
            --sidebar-text: #e2e8f0;
            --sidebar-text-muted: #94a3b8;
            --sidebar-hover-bg: rgba(255, 255, 255, 0.05);
            --sidebar-active-bg: rgba(22, 41, 99, 0.2);
            --sidebar-border: rgba(255, 255, 255, 0.05);
            
            /* UI Elements & Spacing */
            --bg-light: #F7F9FC;
            --bg-white: #FFFFFF;
            --text-dark: #1C1C28;
            --text-muted: #64748b;
            
            /* Shadows & Radius */
            --shadow-sm: 0 2px 8px rgba(58, 12, 163, 0.08);
            --shadow-md: 0 4px 16px rgba(58, 12, 163, 0.1);
            --shadow-lg: 0 8px 32px rgba(58, 12, 163, 0.15);
            --radius-md: 1rem;
            --radius-sm: 0.5rem;
        }

        .dark-mode {
            --bg-light: #111827;
            --bg-white: #1f2937;
            --text-dark: #f3f4f6;
            --text-muted: #d1d5db; /* Lighter gray for better visibility */
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.5);
            
            /* Sidebar adjustments for dark mode if needed */
            --sidebar-bg: #0f172a;
        }
        
        body { 
            font-family: 'Cairo', sans-serif; 
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        /* Fix text cursor and typing direction for Arabic inputs */
        [dir="rtl"] .form-control, 
        [dir="rtl"] .form-select, 
        [dir="rtl"] textarea {
            direction: rtl !important;
            text-align: right !important;
        }

        /* Fix for action dropdowns in responsive tables */
        .table-responsive {
            min-height: 300px;
        }
        
        /* Sidebar Luxury Design */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            top: 0;
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--sidebar-text);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--sidebar-border) transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background-color: var(--sidebar-border);
            border-radius: 20px;
        }

        /* DEFAULT / DESKTOP STYLES (>= 993px) */
        @media (min-width: 993px) {
            [dir="rtl"] .sidebar {
                right: 0;
                border-left: 1px solid var(--sidebar-border);
            }
            [dir="ltr"] .sidebar {
                left: 0;
                border-right: 1px solid var(--sidebar-border);
            }
            [dir="rtl"] .main-content {
                margin-right: var(--sidebar-width) !important;
                margin-left: 0 !important;
            }
            [dir="ltr"] .main-content {
                margin-left: var(--sidebar-width) !important;
                margin-right: 0 !important;
            }
            [dir="rtl"] .admin-footer {
                right: var(--sidebar-width) !important;
                left: 0 !important;
            }
            [dir="ltr"] .admin-footer {
                left: var(--sidebar-width) !important;
                right: 0 !important;
            }
        }

        /* GUEST / NO SIDEBAR STYLES */
        @media (min-width: 993px) {
            body.no-sidebar [dir="rtl"] .main-content {
                margin-right: 0 !important;
            }
            body.no-sidebar [dir="ltr"] .main-content {
                margin-left: 0 !important;
            }
            body.no-sidebar .admin-footer {
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
            }
        }

        /* MOBILE STYLES (< 992px) */
        @media (max-width: 992px) {
            html, body {
                overflow-x: hidden !important;
                width: 100%;
                position: relative;
            }

            body .sidebar {
                z-index: 2000 !important;
                box-shadow: none;
                width: 280px !important;
                max-width: 85% !important;
                position: fixed;
                top: 0;
                bottom: 0;
                transition: transform 0.3s ease-in-out;
            }

            [dir="rtl"] body .sidebar {
                right: 0 !important;
                left: auto !important;
                transform: translateX(100%) !important;
            }

            [dir="ltr"] body .sidebar {
                left: 0 !important;
                right: auto !important;
                transform: translateX(-100%) !important;
            }

            body .sidebar.active {
                transform: translateX(0) !important;
                box-shadow: 0 0 15px rgba(0,0,0,0.5) !important;
            }

            body .main-content {
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 1.5rem !important; 
                padding-bottom: 5rem !important;
                overflow-x: hidden !important;
            }

            body .admin-footer {
                width: 100% !important;
                left: 0 !important;
                right: 0 !important;
            }
        }

        /* Common Decorative Styles */
        .sidebar .border-bottom {
            border-bottom-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        .sidebar h5 {
            color: #fff !important;
        }
        
        .sidebar small.text-muted {
            color: var(--sidebar-text-muted) !important;
        }
        
        .sidebar .nav-link {
            color: var(--sidebar-text-muted);
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0.25rem 1rem;
            border-radius: var(--radius-sm);
            position: relative;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: var(--sidebar-hover-bg);
            transform: translateX(-5px);
        }

        [dir="ltr"] .sidebar .nav-link:hover {
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background-color: var(--sidebar-active-bg);
            color: white;
            box-shadow: inset 0 0 10px rgba(22, 41, 99, 0.1);
            border: none !important;
        }

        [dir="rtl"] .sidebar .nav-link.active::after {
            content: ''; position: absolute; right: -1rem; top: 20%; height: 60%; width: 3px;
            background: var(--primary-light); border-radius: 4px 0 0 4px;
        }

        [dir="ltr"] .sidebar .nav-link.active::after {
            content: ''; position: absolute; left: -1rem; top: 20%; height: 60%; width: 3px;
            background: var(--primary-light); border-radius: 0 4px 4px 0;
        }
        
        .sidebar .nav-link.active i {
            color: var(--primary-light);
        }

        .main-content {
            padding: 2rem;
            padding-bottom: 80px;
            min-height: 100vh;
            transition: margin 0.3s ease;
        }
        
        .card {
            border: none;
            box-shadow: var(--shadow-sm);
            border-radius: var(--radius-md);
            background: var(--bg-white);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            box-shadow: var(--shadow-sm);
            padding: 0.5rem 1.5rem;
            border-radius: var(--radius-sm);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #4F46E5 100%);
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }
        
        .admin-footer {
            position: fixed;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
            z-index: 990;
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1999;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Modal & Scroll Fixes */
        body.modal-open { overflow: hidden !important; padding-right: 0 !important; }
        .modal { background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); }
        .modal-backdrop { display: none !important; }
        
        /* Force CSS Version 1.1 */
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="<?php echo e(($tenant->settings['appearance']['dark_mode'] ?? false) ? 'dark-mode' : ''); ?> <?php echo e(!auth()->check() ? 'no-sidebar' : ''); ?>">

    <?php if(session()->has('impersonator_id')): ?>
        <div class="alert alert-warning mb-0 rounded-0 border-0 p-2 d-flex justify-content-between align-items-center" style="z-index: 1050; position: relative;">
            <div>
                <i class="fas fa-user-secret me-2"></i><?php echo e(__('center::dashboard.header.browsing_as')); ?><strong><?php echo e(auth()->user()->name); ?></strong>
            </div>
            <a href="<?php echo e(route('admin.impersonate.stop')); ?>" class="btn btn-dark btn-sm rounded-pill px-3">
                <i class="fas fa-sign-out-alt me-1"></i><?php echo e(__('center::dashboard.header.logout')); ?></a>
        </div>
    <?php endif; ?>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <?php if(auth()->guard()->check()): ?>
    <aside class="sidebar" id="sidebar">
        <div class="p-3 d-flex align-items-center justify-content-center" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
            <div class="text-center">
                <?php if($tenant->logo): ?>
                    <div class="mb-2">
                        <img src="<?php echo e(asset('storage/' . $tenant->logo)); ?>" class="rounded-3 shadow-sm p-1" style="max-height: 45px; max-width: 100%; height: auto; background: white;">
                    </div>
                <?php else: ?>
                    <div class="mb-2 mx-auto bg-white rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 45px; height: 45px; font-size: 1.2rem;">
                        <?php echo e(substr($tenant->name ?? 'T', 0, 1)); ?>

                    </div>
                <?php endif; ?>
                <div class="px-2">
                    <h6 class="fw-bold text-white mb-0 text-truncate" style="font-size: 0.9rem;"><?php echo e($tenant->name ?? __('sidebar.center_name')); ?></h6>
                    <small class="text-muted" style="font-size: 0.7rem; opacity: 0.6;"><?php echo e(__('center::sidebar.panel')); ?></small>
                </div>
            </div>
            <button type="button" class="btn btn-link text-white p-0 d-lg-none" id="sidebarClose">
                <i class="fas fa-times fs-4"></i>
            </button>
        </div>
        
        <style>
            /* Professional Dark Sidebar Styles */
            .sidebar-nav-link {
                color: rgba(255, 255, 255, 0.7); /* Light text for dark bg */
                padding: 12px 15px;
                border-radius: 8px;
                transition: all 0.2s ease;
                font-weight: 500;
                font-size: 0.9rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                text-decoration: none;
                margin-bottom: 4px;
            }
            .sidebar-nav-link:hover {
                color: #fff;
                background: rgba(255, 255, 255, 0.1);
            }
            .sidebar-nav-link.active {
                background: var(--primary-color); /* Primary Theme Color */
                color: white;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            }
            .sidebar-nav-link.active i {
                color: white !important;
            }
            .sidebar-nav-link i {
                width: 25px;
                text-align: center;
                transition: all 0.2s;
            }
            
            /* Submenu Styles */
            .sidebar-submenu {
                list-style: none;
                padding-left: 0;
                margin-left: 12px;
                border-left: 1px solid rgba(255, 255, 255, 0.15); /* Subtle light border */
                margin-top: 5px;
                margin-bottom: 15px;
            }
            .sidebar-sub-link {
                display: flex;
                align-items: center;
                padding: 8px 15px;
                font-size: 0.85rem;
                color: rgba(255, 255, 255, 0.6); /* Slightly dimmer than parents */
                text-decoration: none;
                transition: all 0.2s;
                position: relative;
                border-radius: 4px;
                margin-left: 4px;
            }
            .sidebar-sub-link:hover {
                color: #fff;
                background: rgba(255, 255, 255, 0.05);
                transform: translateX(5px);
            }
            .sidebar-sub-link.active {
                color: #fff;
                font-weight: 600;
                background: rgba(255, 255, 255, 0.1);
            }
            
            /* Chevron Rotation */
            .collapse.show ~ .sidebar-nav-link .fa-chevron-down,
            .sidebar-nav-link[aria-expanded="true"] .fa-chevron-down {
                transform: rotate(180deg);
            }
            .fa-chevron-down {
                transition: transform 0.3s ease;
            }
            
            /* Section Headers helper */
            .sidebar-section-active {
                background: rgba(255, 255, 255, 0.05);
                color: white !important;
            }
        </style>

        <nav class="mt-4 px-3">
            <!-- 1. DASHBOARD -->
            <a href="<?php echo e(route('center.dashboard', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-nav-link mb-3 <?php echo e(request()->routeIs('center.dashboard') ? 'active' : ''); ?>">
                <span><i class="fas fa-home me-2"></i> <?php echo e(__('center::sidebar.dashboard')); ?></span>
            </a>

            <!-- SCHOOL MANAGEMENT (Collapsible Section) -->
            <?php 
                $canInstructors = ($tenant->getFeatureValue('max_instructors') != '0' && $tenant->getFeatureValue('max_instructors') !== false) && auth()->user()->can('view instructors');
                $canCourses = ($tenant->getFeatureValue('max_courses') != '0' && $tenant->getFeatureValue('max_courses') !== false) && auth()->user()->can('view courses');
                $canClassrooms = ($tenant->getFeatureValue('max_classrooms') != '0' && $tenant->getFeatureValue('max_classrooms') !== false) && auth()->user()->can('view schedule');
                $canSchedules = $tenant->getFeatureValue('daily_schedules') === true && auth()->user()->can('view schedule');
                $canOnlineClasses = auth()->user()->can('view schedule');

                $isSchoolMgmtActive = request()->routeIs('center.classrooms.*') || 
                                      request()->routeIs('center.instructors.*') || 
                                      request()->routeIs('center.courses.*') || 
                                      request()->routeIs('center.online_classes.*') || 
                                      request()->routeIs('center.schedules.*');
                
                $showSchoolMgmt = ($canInstructors || $canCourses || $canClassrooms || $canSchedules || $canOnlineClasses) && ($tenant->type !== 'instructor');
            ?>
            
            <?php if($showSchoolMgmt): ?>
            <a href="#schoolMgmtCollapse" data-bs-toggle="collapse" class="sidebar-nav-link mb-1 <?php echo e($isSchoolMgmtActive ? 'sidebar-section-active' : ''); ?>" role="button" aria-expanded="<?php echo e($isSchoolMgmtActive ? 'true' : 'false'); ?>">
                <span><i class="fas fa-university me-2 <?php echo e($isSchoolMgmtActive ? 'text-warning' : 'opacity-75'); ?>"></i> <?php echo e(__('center::sidebar.school_management')); ?></span>
                <i class="fas fa-chevron-down fa-xs opacity-50"></i>
            </a>
            <div class="collapse <?php echo e($isSchoolMgmtActive ? 'show' : ''); ?>" id="schoolMgmtCollapse">
                <div class="sidebar-submenu">
                    <?php if($canInstructors): ?>
                    <a href="<?php echo e(route('center.instructors.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.instructors.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.instructors')); ?>

                    </a>
                    <?php endif; ?>
                    <?php if($canCourses): ?>
                    <a href="<?php echo e(route('center.courses.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.courses.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.courses')); ?>

                    </a>
                    <?php endif; ?>
                    <?php if($canClassrooms): ?>
                    <a href="<?php echo e(route('center.classrooms.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.classrooms.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.classrooms')); ?>

                    </a>
                    <?php endif; ?>
                    <?php if($canSchedules): ?>
                    <a href="<?php echo e(route('center.schedules.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.schedules.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.schedules')); ?>

                    </a>
                    <?php endif; ?>
                    <?php if($canOnlineClasses): ?>
                    <a href="<?php echo e(route('center.online_classes.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.online_classes.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <span class="text-success fw-bold"><?php echo e(__('center::sidebar.online_classes')); ?></span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- 2. STUDENTS (Includes Attendance) -->
            <?php 
                $canStudents = ($tenant->getFeatureValue('max_students') != '0' && $tenant->getFeatureValue('max_students') !== false) && auth()->user()->can('view students');
                $canAttendance = $tenant->getFeatureValue('attendance_tracking') && auth()->user()->can('view attendance');
                $isStudentsActive = request()->routeIs('center.students.*') || request()->routeIs('center.attendance.*'); 
                $showStudents = $canStudents || $canAttendance;
            ?>

            <?php if($showStudents): ?>
            <a href="#studentsCollapse" data-bs-toggle="collapse" class="sidebar-nav-link mb-1 <?php echo e($isStudentsActive ? 'sidebar-section-active' : ''); ?>" role="button" aria-expanded="<?php echo e($isStudentsActive ? 'true' : 'false'); ?>">
                <span><i class="fas fa-user-graduate me-2 <?php echo e($isStudentsActive ? 'text-info' : 'opacity-75'); ?>"></i> <?php echo e(__('center::sidebar.students')); ?></span>
                <i class="fas fa-chevron-down fa-xs opacity-50"></i>
            </a>
            <div class="collapse <?php echo e($isStudentsActive ? 'show' : ''); ?>" id="studentsCollapse">
                <div class="sidebar-submenu">
                    <?php if($canStudents): ?>
                    <a href="<?php echo e(route('center.students.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.students.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.students')); ?> (<?php echo e(__('center::sidebar.list')); ?>)
                    </a>
                    <?php endif; ?>
                    <?php if($canAttendance): ?>
                    <a href="<?php echo e(route('center.attendance.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.attendance.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.attendance')); ?>

                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            

            <?php 
                $hasFinancialReports = $tenant->getFeatureValue('financial_reports') && auth()->user()->canAny(['view sales', 'view expenses']);
                $hasAdvancedReports = $tenant->getFeatureValue('advanced_reports') && auth()->user()->can('view reports');
                $isFinanceActive = request()->routeIs('center.sales.*') || request()->routeIs('center.expenses.*') || request()->routeIs('center.analytics.*'); 
            ?>

            <?php if($hasFinancialReports || $hasAdvancedReports): ?>
            <a href="#financeCollapse" data-bs-toggle="collapse" class="sidebar-nav-link mb-1 <?php echo e($isFinanceActive ? 'sidebar-section-active' : ''); ?>" role="button" aria-expanded="<?php echo e($isFinanceActive ? 'true' : 'false'); ?>">
                <span><i class="fas fa-chart-line me-2 <?php echo e($isFinanceActive ? 'text-success' : 'opacity-75'); ?>"></i> <?php echo e(__('center::sidebar.financial')); ?></span>
                <i class="fas fa-chevron-down fa-xs opacity-50"></i>
            </a>
            <div class="collapse <?php echo e($isFinanceActive ? 'show' : ''); ?>" id="financeCollapse">
                <div class="sidebar-submenu">
                    <?php if($hasFinancialReports): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view sales')): ?>
                    <a href="<?php echo e(route('center.sales.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.sales.index') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.sales')); ?>

                    </a>
                    <a href="<?php echo e(route('center.sales.account', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.sales.account') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.student_accounts')); ?>

                    </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view expenses')): ?>
                    <a href="<?php echo e(route('center.expenses.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.expenses.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.expenses')); ?>

                    </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view reports', 'view analytics'])): ?>
                    <a href="<?php echo e(route('center.analytics.finance')); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.analytics.finance') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.financial_analytics')); ?>

                    </a>
                    <a href="<?php echo e(route('center.analytics.commissions')); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.analytics.commissions') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.financial_commissions')); ?>

                    </a>
                    <a href="<?php echo e(route('center.analytics.taxes')); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.analytics.taxes') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.financial_taxes')); ?>

                    </a>
                    <a href="<?php echo e(route('center.analytics.discounts')); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.analytics.discounts') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.financial_discounts')); ?>

                    </a>
                    <?php endif; ?>
                    <?php endif; ?>

                    <?php if($hasAdvancedReports): ?>
                    <a href="<?php echo e(route('center.analytics.index')); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.analytics.index') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::analytics.general')); ?>

                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['manage users', 'manage settings', 'manage billing'])): ?>
            <?php 
                $isSettingsActive = request()->routeIs('center.assets.*') || 
                                    request()->routeIs('center.settings.*') || 
                                    request()->routeIs('center.users.*') || 
                                    request()->routeIs('center.roles.*') || 
                                    request()->routeIs('center.tickets.*') ||
                                    request()->routeIs('center.subscription.*'); 
            ?>
            <a href="#settingsCollapse" data-bs-toggle="collapse" class="sidebar-nav-link mb-1 <?php echo e($isSettingsActive ? 'sidebar-section-active' : ''); ?>" role="button" aria-expanded="<?php echo e($isSettingsActive ? 'true' : 'false'); ?>">
                <span><i class="fas fa-cogs me-2 <?php echo e($isSettingsActive ? 'text-secondary' : 'opacity-75'); ?>"></i> <?php echo e(__('center::sidebar.settings')); ?></span>
                <i class="fas fa-chevron-down fa-xs opacity-50"></i>
            </a>
            <div class="collapse <?php echo e($isSettingsActive ? 'show' : ''); ?>" id="settingsCollapse">
                <div class="sidebar-submenu">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage billing')): ?>
                    <a href="<?php echo e(route('center.subscription.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.subscription.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.subscription')); ?>

                        <?php
                            $subEndsAt = app('tenant')->subscriptions?->last()?->ends_at;
                            $daysLeft  = $subEndsAt ? max(0, now()->diffInDays($subEndsAt, false)) : null;
                        ?>
                        <?php if($daysLeft !== null && $daysLeft <= 7): ?>
                            <span class="badge bg-danger rounded-pill ms-2" style="font-size:0.65rem;"><?php echo e($daysLeft); ?>d</span>
                        <?php endif; ?>
                    </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage settings')): ?>
                    <a href="<?php echo e(route('center.settings.index', ['tenant' => $tenant->domain ?? 'center', 'tab' => 'general'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.settings.index') && (request('tab') == 'general' || !request('tab')) ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::settings.tabs.general')); ?>

                    </a>
                    <a href="<?php echo e(route('center.settings.index', ['tenant' => $tenant->domain ?? 'center', 'tab' => 'academic'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.settings.index') && request('tab') == 'academic' ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::settings.tabs.academic')); ?>

                    </a>
                    <a href="<?php echo e(route('center.settings.index', ['tenant' => $tenant->domain ?? 'center', 'tab' => 'financial'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.settings.index') && request('tab') == 'financial' ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::settings.tabs.financial')); ?>

                    </a>
                    <a href="<?php echo e(route('center.settings.index', ['tenant' => $tenant->domain ?? 'center', 'tab' => 'appearance'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.settings.index') && request('tab') == 'appearance' ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::settings.tabs.appearance')); ?>

                    </a>
                    <?php if($tenant->getFeatureValue('whatsapp_alerts')): ?>
                    <a href="<?php echo e(route('center.settings.index', ['tenant' => $tenant->domain ?? 'center', 'tab' => 'whatsapp'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.settings.index') && request('tab') == 'whatsapp' ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::settings.tabs.whatsapp')); ?>

                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('center.settings.index', ['tenant' => $tenant->domain ?? 'center', 'tab' => 'privacy'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.settings.index') && request('tab') == 'privacy' ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::settings.tabs.privacy')); ?>

                    </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage users')): ?>
                    <a href="<?php echo e(route('center.users.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.users.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.users')); ?>

                    </a>
                    <?php endif; ?>
                    <?php if($tenant->getFeatureValue('advanced_roles')): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage users')): ?>
                    <a href="<?php echo e(route('center.roles.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.roles.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.permissions')); ?>

                    </a>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php if($tenant->getFeatureValue('multi_branch')): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage settings')): ?>
                    <a href="<?php echo e(route('center.branches.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.branches.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.branches')); ?>

                    </a>
                    <?php endif; ?>
                    <?php endif; ?>
                    <a href="<?php echo e(route('center.tickets.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.tickets.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.support')); ?>

                    </a>
                </div>
            </div>
            <?php endif; ?>
        </nav>
    </aside>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="d-flex justify-content-between align-items-center mb-4 gap-2 flex-nowrap">
            <div class="d-flex align-items-center gap-2 min-w-0">
                <?php if(auth()->guard()->check()): ?>
                <button class="btn btn-white bg-white border shadow-sm rounded-circle d-lg-none p-0 d-flex align-items-center justify-content-center flex-shrink-0" id="sidebarToggle" style="width: 40px; height: 40px;">
                    <i class="fas fa-bars text-primary"></i>
                </button>
                <?php endif; ?>
                <h2 class="fw-bold mb-0 text-truncate" style="font-size: 1.15rem; max-width: 250px;"><?php echo $__env->yieldContent('page-title', __('sidebar.overview')); ?></h2>
            </div>

            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <?php if(auth()->guard()->check()): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view sales')): ?>
                <?php
                    $overdueCount = $tenant->getOverdueStudentsCount();
                ?>
                <?php if($overdueCount > 0): ?>
                <a href="<?php echo e(route('center.sales.overdue', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="btn btn-white bg-white border shadow-sm rounded-pill px-3 d-none d-md-flex align-items-center gap-2" title="<?php echo e(__('center::dashboard.header.overdue_invoices')); ?>">
                    <i class="fas fa-wallet text-danger"></i>
                    <span class="badge rounded-pill bg-danger" style="font-size: 0.6rem;"><?php echo e($overdueCount); ?></span>
                </a>
                <?php endif; ?>
                <?php endif; ?>

                <!-- Notifications Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-white bg-white border shadow-sm rounded-circle p-0 d-flex align-items-center justify-content-center position-relative" type="button" data-bs-toggle="dropdown" style="width: 40px; height: 40px;">
                        <i class="fas fa-bell text-muted"></i>
                        <?php if(auth()->user()->unreadNotifications->count() > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white p-1">
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-0 shadow-lg border-0 rounded-4 overflow-hidden mt-2" style="width: 320px;">
                        <li class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                            <h6 class="mb-0 fw-bold small"><?php echo e(__('center::sidebar.notifications')); ?></h6>
                            <?php if(auth()->user()->unreadNotifications->count() > 0): ?>
                                <form action="<?php echo e(route('center.notifications.readAll')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0 text-primary" style="font-size: 0.75rem;">
                                        <?php echo e(__('center::sidebar.mark_all_read')); ?>

                                    </button>
                                </form>
                            <?php endif; ?>
                        </li>
                        <div style="max-height: 350px; overflow-y: auto;">
                            <?php $__empty_1 = true; $__currentLoopData = auth()->user()->notifications->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li>
                                    <a class="dropdown-item p-3 border-bottom d-flex gap-3 <?php echo e($notification->read_at ? '' : 'bg-light'); ?>" href="<?php echo e(route('center.notifications.read', $notification->id)); ?>">
                                        <?php
                                            $titleKey = $notification->data['title'] ?? 'notification';
                                            $iconColor = 'primary';
                                            $iconClass = $notification->data['icon'] ?? 'fas fa-bell';
                                            
                                            if (str_contains($titleKey, 'registered') || str_contains($titleKey, 'created')) $iconColor = 'success';
                                            elseif (str_contains($titleKey, 'updated') || str_contains($titleKey, 'edited')) $iconColor = 'info';
                                            elseif (str_contains($titleKey, 'deleted') || str_contains($titleKey, 'removed')) $iconColor = 'danger';
                                        ?>
                                        <div class="rounded-circle bg-<?php echo e($iconColor); ?> bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 35px; height: 35px;">
                                            <i class="<?php echo e($iconClass); ?> text-<?php echo e($iconColor); ?>" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <p class="mb-0 text-dark small fw-bold text-truncate">
                                                <?php echo e(__('center::sidebar.' . $titleKey) != 'center::sidebar.' . $titleKey ? __('center::sidebar.' . $titleKey) : $titleKey); ?>

                                            </p>
                                            <p class="mb-0 text-muted x-small text-truncate"><?php echo e($notification->data['message'] ?? ''); ?></p>
                                            <small class="text-muted" style="font-size: 0.65rem;"><?php echo e($notification->created_at->diffForHumans()); ?></small>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li class="p-4 text-center text-muted">
                                    <i class="fas fa-bell-slash fa-2x mb-2 opacity-20"></i>
                                    <p class="mb-0 small"><?php echo e(__('center::sidebar.no_notifications')); ?></p>
                                </li>
                            <?php endif; ?>
                        </div>
                        <li class="bg-light border-top">
                            <a href="<?php echo e(route('center.notifications.index')); ?>" class="dropdown-item text-center py-2 small fw-bold text-primary">
                                <?php echo e(__('center::sidebar.view_all')); ?>

                            </a>
                        </li>
                    </ul>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-white bg-white border shadow-sm rounded-pill px-2 px-md-3 dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.75rem;">
                            <?php echo e(substr(auth()->user()->name ?? 'A', 0, 1)); ?>

                        </div>
                        <span class="d-none d-md-inline-block small fw-bold"><?php echo e(auth()->user()->name ?? 'Admin'); ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2 mt-2">
                        <li><a class="dropdown-item rounded-3" href="<?php echo e(route('center.settings.index')); ?>"><i class="fas fa-cog me-2"></i><?php echo e(__('sidebar.settings')); ?></a></li>
                        <li><hr class="dropdown-divider mx-2"></li>
                        <li>
                            <form action="<?php echo e(route('logout')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-item rounded-3 text-danger"><i class="fas fa-sign-out-alt me-2"></i><?php echo e(__('auth.logout')); ?></button>
                            </form>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </header>

        <!-- Flash Messages (Handled by SweetAlert2) -->
        <!-- Old alerts removed -->

        <!-- Page Content -->
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="admin-footer">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-12 text-center">
                    <p class="mb-0 text-muted">
                        &copy; <?php echo e(date('Y')); ?> <?php echo e($tenant->name ?? __('center::sidebar.center_name')); ?>. <?php echo e(__('center::sidebar.rights_reserved')); ?>.
                        <span class="pulse"></span> 
                <?php echo e(__('center::dashboard.center_status')); ?>: <span class="text-success fw-semibold"><?php echo e(__('center::dashboard.healthy')); ?></span>
                        <span class="mx-2">|</span>
                        <?php echo e(__('center::sidebar.developed_by')); ?> <a href="#" class="text-primary text-decoration-none fw-bold">Remon Wasef</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Service Worker Registration -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('sidebarToggle');
        const close = document.getElementById('sidebarClose');

        function toggleSidebar() {
            if (sidebar) sidebar.classList.toggle('active');
            if (overlay) overlay.classList.toggle('active');
        }

        if (toggle) toggle.addEventListener('click', function(e) {
            e.preventDefault();
            toggleSidebar();
        });
        
        if (close) close.addEventListener('click', function(e) {
            e.preventDefault();
            toggleSidebar();
        });

        if (overlay) overlay.addEventListener('click', function(e) {
            e.preventDefault();
            toggleSidebar();
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                // Add timestamp to bust CDN cache
                navigator.serviceWorker.register('/service-worker.js?v=5')
                    .then(reg => console.log('SW registered:', reg.scope))
                    .catch(err => console.log('SW failed:', err));
            });
        }

        // SweetAlert2 Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: '<?php echo e(app()->getLocale() == "ar" ? "top-start" : "top-end"); ?>',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        <?php if(session('success')): ?>
            Toast.fire({
                icon: 'success',
                title: "<?php echo e(session('success')); ?>"
            });
        <?php endif; ?>

        <?php if(session('error')): ?>
            Toast.fire({
                icon: 'error',
                title: "<?php echo e(session('error')); ?>"
            });
        <?php endif; ?>

        document.addEventListener('DOMContentLoaded', function() {
            if (window.Echo) {
                console.log('Echo initialized, subscribing to channel: center.<?php echo e($tenant->id); ?>');
                window.Echo.channel('center.<?php echo e($tenant->id); ?>')
                    .listen('.risk.detected', (e) => {
                        console.log('Risk Event Received:', e);
                        
                        Toast.fire({
                            icon: 'warning',
                            title: '⚠️ ' + (e.message || 'Risk Detected'),
                            text: 'Student: ' + e.studentName + ' (' + e.riskType + ')',
                            timer: 5000
                        });
                    });
            } else {
                console.log('Real-time notifications not enabled (Echo not loaded).');
            }
        });
    </script>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.scss', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\layouts\master.blade.php ENDPATH**/ ?>