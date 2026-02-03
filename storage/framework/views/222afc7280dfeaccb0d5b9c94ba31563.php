<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'لوحة التحكم'); ?></title>
    
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
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.scss', 'resources/js/app.js']); ?>
    
    <style>
        :root {
            /* Power Palette - Aligning with Admin and Landing */
            --primary-color: #3A0CA3;       /* Deep Indigo */
            --primary-light: #2A4DFF;      /* Royal Blue */
            --primary-gradient: linear-gradient(135deg, #3A0CA3 0%, #2A4DFF 100%);
            
            /* Sidebar - Luxurious Dark Aesthetic */
            --sidebar-width: 280px;
            --sidebar-bg: #0a0a0c;          /* Deep Obsidian */
            --sidebar-text: #e2e8f0;
            --sidebar-text-muted: #94a3b8;
            --sidebar-hover-bg: rgba(255, 255, 255, 0.03);
            --sidebar-active-bg: rgba(67, 97, 238, 0.1);
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

        /* RTL Sidebar */
        [dir="rtl"] .sidebar {
            right: 0;
            border-left: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* LTR Sidebar */
        [dir="ltr"] .sidebar {
            left: 0;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
        
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
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: var(--sidebar-hover-bg);
            transform: translateX(-5px);
        }

        /* LTR Hover Transform Flip */
        [dir="ltr"] .sidebar .nav-link:hover {
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background-color: var(--sidebar-active-bg);
            color: white;
            box-shadow: inset 0 0 10px rgba(67, 97, 238, 0.05);
            border: none !important;
        }

        [dir="rtl"] .sidebar .nav-link.active::after {
            content: '';
            position: absolute;
            right: -1rem;
            top: 20%;
            height: 60%;
            width: 3px;
            background: var(--primary-light);
            border-radius: 4px 0 0 4px;
        }

        [dir="ltr"] .sidebar .nav-link.active::after {
            content: '';
            position: absolute;
            left: -1rem;
            top: 20%;
            height: 60%;
            width: 3px;
            background: var(--primary-light);
            border-radius: 0 4px 4px 0;
        }
        
        .sidebar .nav-link.active i {
            color: var(--primary-light);
        }

        /* Main Content Area */
        .main-content {
            padding: 2rem;
            padding-bottom: 80px;
            min-height: 100vh;
        }

        [dir="rtl"] .main-content {
            margin-right: var(--sidebar-width);
        }

        [dir="ltr"] .main-content {
            margin-left: var(--sidebar-width);
        }
        
        /* Cards */
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
        
        /* Buttons */
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
        
        /* Footer */
        .admin-footer {
            position: fixed;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
            z-index: 990;
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        [dir="rtl"] .admin-footer {
            right: var(--sidebar-width);
            left: 0;
        }

        [dir="ltr"] .admin-footer {
            left: var(--sidebar-width);
            right: 0;
        }

        /* Mobile Responsiveness */
        @media (max-width: 992px) {
            .sidebar {
                [dir="rtl"] & { right: calc(-1 * var(--sidebar-width)) !important; }
                [dir="ltr"] & { left: calc(-1 * var(--sidebar-width)) !important; }
                box-shadow: none;
            }
            
            .sidebar.active {
                [dir="rtl"] & { right: 0 !important; }
                [dir="ltr"] & { left: 0 !important; }
                box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
            }
            
            .main-content {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            
            .admin-footer {
                left: 0 !important;
                right: 0 !important;
            }
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 999;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Dark Mode Bootstrap Overrides */
        .dark-mode .dropdown-menu {
            background-color: var(--bg-white);
            border-color: rgba(255,255,255,0.1);
        }
        .dark-mode .dropdown-item {
            color: var(--text-dark);
        }
        .dark-mode .dropdown-item:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .dark-mode .form-control, .dark-mode .form-select {
            background-color: #1f2937; /* Slightly lighter than bg-light */
            border-color: rgba(255,255,255,0.1);
            color: var(--text-dark);
        }
        .dark-mode .form-control:focus, .dark-mode .form-select:focus {
            background-color: #1f2937;
            color: var(--text-dark);
        }
        .dark-mode .bg-white {
            background-color: var(--bg-white) !important;
            color: var(--text-dark) !important; /* Ensure text on white bg is visible in dark mode */
        }
        .dark-mode .bg-light {
            background-color: var(--bg-light) !important;
        }
        .dark-mode .text-muted {
            color: #d1d5db !important;
        }
        .dark-mode .form-label {
            color: #e5e7eb !important; /* Make labels very light */
        }
        .dark-mode input::placeholder, .dark-mode textarea::placeholder {
            color: #9ca3af !important;
        }
        .dark-mode select option {
             background-color: #1f2937;
             color: #f3f4f6;
        }

        /* Modal Stability Fixes */
        body.modal-open {
            overflow: hidden !important;
            padding-right: 0 !important;
        }
        .modal {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
        }
        .modal-backdrop {
            display: none !important;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="<?php echo e(($tenant->settings['appearance']['dark_mode'] ?? false) ? 'dark-mode' : ''); ?>">

    <?php if(session()->has('impersonator_id')): ?>
        <div class="alert alert-warning mb-0 rounded-0 border-0 p-2 d-flex justify-content-between align-items-center" style="z-index: 1050; position: relative;">
            <div>
                <i class="fas fa-user-secret me-2"></i>
                أنت الآن تتصفح النظام بصفتك <strong><?php echo e(auth()->user()->name); ?></strong>
            </div>
            <a href="<?php echo e(route('admin.impersonate.stop')); ?>" class="btn btn-dark btn-sm rounded-pill px-3">
                <i class="fas fa-sign-out-alt me-1"></i> العودة للوحة تحكم المشرف
            </a>
        </div>
    <?php endif; ?>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="p-4 border-bottom d-flex align-items-center justify-content-between gap-3" style="border-bottom-color: var(--sidebar-border) !important;">
            <div class="d-flex align-items-center gap-3">
                <?php if($tenant->logo): ?>
                    <img src="<?php echo e(asset('storage/' . $tenant->logo)); ?>" class="rounded-circle shadow-sm" style="width: 40px; height: 40px; object-fit: contain; background: white; padding: 2px;">
                <?php endif; ?>
                <div class="overflow-hidden">
                    <h5 class="fw-bold text-white mb-0 lh-1 text-truncate" style="font-size: 1rem;"><?php echo e($tenant->name ?? __('sidebar.center_name')); ?></h5>
                    <small class="text-muted" style="font-size: 0.7rem;"><?php echo e(__('center::sidebar.panel')); ?></small>
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
                background: #435ebe; /* Primary Theme Color */
                color: white;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
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
            <!-- Dashboard (Always Visible) -->
            <a href="<?php echo e(route('center.dashboard', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-nav-link mb-3 <?php echo e(request()->routeIs('center.dashboard') ? 'active' : ''); ?>">
                <span><i class="fas fa-home me-2"></i> <?php echo e(__('center::sidebar.dashboard')); ?></span>
            </a>

            <!-- 1. ACADEMIC STRUCTURE SETUP -->
            <?php $isAcademicSetupActive = request()->routeIs('center.classrooms.*') || (request()->routeIs('center.settings.index') && request('tab') == 'academic'); ?>
            <a href="#academicSetupCollapse" data-bs-toggle="collapse" class="sidebar-nav-link mb-1 <?php echo e($isAcademicSetupActive ? 'sidebar-section-active' : ''); ?>" role="button" aria-expanded="<?php echo e($isAcademicSetupActive ? 'true' : 'false'); ?>">
                <span><i class="fas fa-layer-group me-2 <?php echo e($isAcademicSetupActive ? 'text-info' : 'opacity-75'); ?>"></i> <?php echo e(__('center::sidebar.academic_setup')); ?></span>
                <i class="fas fa-chevron-down fa-xs opacity-50"></i>
            </a>
            <div class="collapse <?php echo e($isAcademicSetupActive ? 'show' : ''); ?>" id="academicSetupCollapse">
                <div class="sidebar-submenu">
                    <a href="<?php echo e(route('center.classrooms.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.classrooms.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.classrooms')); ?>

                    </a>
                    <a href="<?php echo e(route('center.settings.index', ['tenant' => $tenant->domain ?? 'center', 'tab' => 'academic'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.settings.index') && request('tab') == 'academic' ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> المراحل والصفوف الدراسية
                    </a>
                </div>
            </div>

            <!-- 2. INSTRUCTORS -->
            <a href="<?php echo e(route('center.instructors.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-nav-link mb-1 <?php echo e(request()->routeIs('center.instructors.*') ? 'active' : ''); ?>">
                <span><i class="fas fa-user-tie me-2"></i> <?php echo e(__('center::sidebar.instructors')); ?></span>
            </a>

            <!-- 3. COURSES & GROUPS -->
            <?php $isCoursesActive = request()->routeIs('center.courses.*') || request()->routeIs('center.schedules.*'); ?>
            <a href="#coursesCollapse" data-bs-toggle="collapse" class="sidebar-nav-link mb-1 <?php echo e($isCoursesActive ? 'sidebar-section-active' : ''); ?>" role="button" aria-expanded="<?php echo e($isCoursesActive ? 'true' : 'false'); ?>">
                <span><i class="fas fa-book-reader me-2 <?php echo e($isCoursesActive ? 'text-primary' : 'opacity-75'); ?>"></i> <?php echo e(__('center::sidebar.courses_groups')); ?></span>
                <i class="fas fa-chevron-down fa-xs opacity-50"></i>
            </a>
            <div class="collapse <?php echo e($isCoursesActive ? 'show' : ''); ?>" id="coursesCollapse">
                <div class="sidebar-submenu">
                    <a href="<?php echo e(route('center.courses.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.courses.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.courses')); ?>

                    </a>
                    <?php if($tenant->hasFeature('daily_schedules')): ?>
                    <a href="<?php echo e(route('center.schedules.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.schedules.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.schedules')); ?>

                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 4. STUDENTS -->
            <a href="<?php echo e(route('center.students.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-nav-link mb-3 <?php echo e(request()->routeIs('center.students.*') ? 'active' : ''); ?>">
                <span><i class="fas fa-user-graduate me-2"></i> <?php echo e(__('center::sidebar.students')); ?></span>
            </a>

            <!-- 5. FINANCIAL & ANALYTICS -->
            <?php $isFinanceActive = request()->routeIs('center.sales.*') || request()->routeIs('center.expenses.*') || request()->routeIs('center.analytics.*'); ?>
            <a href="#financeCollapse" data-bs-toggle="collapse" class="sidebar-nav-link mb-1 <?php echo e($isFinanceActive ? 'sidebar-section-active' : ''); ?>" role="button" aria-expanded="<?php echo e($isFinanceActive ? 'true' : 'false'); ?>">
                <span><i class="fas fa-chart-line me-2 <?php echo e($isFinanceActive ? 'text-success' : 'opacity-75'); ?>"></i> <?php echo e(__('center::sidebar.financial')); ?></span>
                <i class="fas fa-chevron-down fa-xs opacity-50"></i>
            </a>
            <div class="collapse <?php echo e($isFinanceActive ? 'show' : ''); ?>" id="financeCollapse">
                <div class="sidebar-submenu">
                    <?php if($tenant->hasFeature('financial_reports')): ?>
                    <a href="<?php echo e(route('center.sales.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.sales.index') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.sales')); ?>

                    </a>
                    <a href="<?php echo e(route('center.sales.account', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.sales.account') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.student_accounts')); ?>

                    </a>
                    <a href="<?php echo e(route('center.expenses.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.expenses.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.expenses')); ?>

                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('center.analytics.index')); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.analytics.index') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::analytics.general')); ?>

                    </a>
                </div>
            </div>

            <!-- 6. DAILY OPERATIONS -->
            <?php $isOpsActive = request()->routeIs('center.attendance.*') || request()->routeIs('center.quizzes.*') || request()->routeIs('center.questions.*'); ?>
            <a href="#opsCollapse" data-bs-toggle="collapse" class="sidebar-nav-link mb-1 <?php echo e($isOpsActive ? 'sidebar-section-active' : ''); ?>" role="button" aria-expanded="<?php echo e($isOpsActive ? 'true' : 'false'); ?>">
                <span><i class="fas fa-tasks me-2 <?php echo e($isOpsActive ? 'text-warning' : 'opacity-75'); ?>"></i> <?php echo e(__('center::sidebar.operations')); ?></span>
                <i class="fas fa-chevron-down fa-xs opacity-50"></i>
            </a>
            <div class="collapse <?php echo e($isOpsActive ? 'show' : ''); ?>" id="opsCollapse">
                <div class="sidebar-submenu">
                    <?php if($tenant->hasFeature('attendance_tracking')): ?>
                    <a href="<?php echo e(route('center.attendance.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.attendance.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.attendance')); ?>

                    </a>
                    <?php endif; ?>
                    <?php if($tenant->hasFeature('manage_exams')): ?>
                    <a href="<?php echo e(route('center.quizzes.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.quizzes.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.exams_results')); ?>

                    </a>
                    <a href="<?php echo e(route('center.questions.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.questions.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.questions_bank')); ?>

                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 7. ADMINISTRATION -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['manage users', 'manage settings'])): ?>
            <?php $isAdminActive = request()->routeIs('center.users.*') || request()->routeIs('center.roles.*') || request()->routeIs('center.branches.*') || request()->routeIs('center.settings.*') || request()->routeIs('center.tickets.*'); ?>
            <a href="#adminCollapse" data-bs-toggle="collapse" class="sidebar-nav-link mb-1 <?php echo e($isAdminActive ? 'sidebar-section-active' : ''); ?>" role="button" aria-expanded="<?php echo e($isAdminActive ? 'true' : 'false'); ?>">
                <span><i class="fas fa-cogs me-2 <?php echo e($isAdminActive ? 'text-secondary' : 'opacity-75'); ?>"></i> <?php echo e(__('center::sidebar.administration')); ?></span>
                <i class="fas fa-chevron-down fa-xs opacity-50"></i>
            </a>
            <div class="collapse <?php echo e($isAdminActive ? 'show' : ''); ?>" id="adminCollapse">
                <div class="sidebar-submenu">
                    <a href="<?php echo e(route('center.users.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.users.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.users')); ?>

                    </a>
                    <?php if($tenant->hasFeature('advanced_roles')): ?>
                    <a href="<?php echo e(route('center.roles.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.roles.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.permissions')); ?>

                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('center.settings.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.settings.index') && !request('tab') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.settings')); ?>

                    </a>
                    <a href="<?php echo e(route('center.tickets.index', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="sidebar-sub-link <?php echo e(request()->routeIs('center.tickets.*') ? 'active' : ''); ?>">
                        <i class="fas fa-circle fa-2xs me-2 opacity-50" style="font-size: 6px;"></i> <?php echo e(__('center::sidebar.support')); ?>

                    </a>
                </div>
            </div>
            <?php endif; ?>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-white bg-white border shadow-sm rounded-circle d-lg-none p-0 d-flex align-items-center justify-content-center" id="sidebarToggle" style="width: 40px; height: 40px;">
                    <i class="fas fa-bars text-primary"></i>
                </button>
                <h2 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $__env->yieldContent('page-title', __('sidebar.overview')); ?></h2>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Notifications Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-white bg-white border shadow-sm rounded-pill px-3 dropdown-toggle no-caret position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell text-primary"></i>
                        <?php if(auth()->user()->unreadNotifications->count() > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                <?php echo e(auth()->user()->unreadNotifications->count()); ?>

                            </span>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-0 shadow-lg border-0" style="width: 320px; max-height: 400px; overflow-y: auto;">
                        <li class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                            <h6 class="mb-0 fw-bold"><?php echo e(__('center::sidebar.notifications')); ?></h6>
                            <?php if(auth()->user()->unreadNotifications->count() > 0): ?>
                                <form action="<?php echo e(route('center.notifications.readAll')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0" style="font-size: 0.8rem;">
                                        <?php echo e(__('center::sidebar.mark_all_read')); ?>

                                    </button>
                                </form>
                            <?php endif; ?>
                        </li>


                        <li class="p-2 text-center bg-light border-bottom">
                            <a href="<?php echo e(route('center.notifications.index')); ?>" class="text-decoration-none small fw-bold text-primary">
                                <?php echo e(__('center::sidebar.view_all')); ?>

                            </a>
                        </li>
                        <?php $__empty_1 = true; $__currentLoopData = auth()->user()->notifications->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li>
                                <a class="dropdown-item p-3 border-bottom d-flex gap-3 <?php echo e($notification->read_at ? '' : 'bg-light'); ?>" href="<?php echo e(route('center.notifications.read', $notification->id)); ?>">
                                    <?php
                                        $titleKey = $notification->data['title'];
                                        $iconColor = 'primary';
                                        $iconClass = $notification->data['icon'] ?? 'fas fa-bell';
                                        
                                        // Custom logic for colors and translations
                                        if (str_contains($titleKey, 'registered') || str_contains($titleKey, 'created')) {
                                            $iconColor = 'success'; // Green for addition
                                        } elseif (str_contains($titleKey, 'updated') || str_contains($titleKey, 'edited')) {
                                            $iconColor = 'info';    // Blue/Info for updates
                                        } elseif (str_contains($titleKey, 'deleted') || str_contains($titleKey, 'removed')) {
                                            $iconColor = 'danger';  // Red for deletion
                                        }
                                        
                                        // Translation lookup with fallback
                                        $translatedTitle = __('center::sidebar.' . $titleKey);
                                        if ($translatedTitle === 'center::sidebar.' . $titleKey) {
                                            $translatedTitle = $titleKey;
                                        }
                                    ?>
                                    
                                    <div class="rounded-circle bg-<?php echo e($iconColor); ?> bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="<?php echo e($iconClass); ?> text-<?php echo e($iconColor); ?>"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-1 fw-bold text-dark" style="font-size: 0.9rem;">
                                            <?php echo e($translatedTitle); ?>

                                        </p>
                                        <p class="mb-1 text-muted text-truncate" style="font-size: 0.8rem; max-width: 200px;"><?php echo e($notification->data['message'] ?? ''); ?></p>
                                        <small class="text-muted" style="font-size: 0.7rem;"><?php echo e($notification->created_at->diffForHumans()); ?></small>
                                    </div>
                                    <?php if(!$notification->read_at): ?>
                                        <span class="d-inline-block rounded-circle bg-primary" style="width: 8px; height: 8px;"></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="p-4 text-center text-muted">
                                <i class="fas fa-bell-slash fa-2x mb-2 text-secondary"></i>
                                <p class="mb-0 small"><?php echo e(__('center::sidebar.no_notifications')); ?></p>
                            </li>
                        <?php endif; ?>

                    </ul>
                </div>

                <!-- Language Switcher -->
                <div class="dropdown">
                    <button class="btn btn-white bg-white border shadow-sm rounded-pill px-3 dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">
                        <?php if(app()->getLocale() == 'ar'): ?>
                            <i class="fas fa-globe me-1"></i> العربية
                        <?php elseif(app()->getLocale() == 'fr'): ?>
                            <i class="fas fa-globe me-1"></i> Français
                        <?php else: ?>
                            <i class="fas fa-globe me-1"></i> English
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item <?php echo e(app()->getLocale() == 'ar' ? 'active' : ''); ?>" href="<?php echo e(route('lang.switch', 'ar')); ?>">العربية</a></li>
                        <li><a class="dropdown-item <?php echo e(app()->getLocale() == 'en' ? 'active' : ''); ?>" href="<?php echo e(route('lang.switch', 'en')); ?>">English</a></li>
                        <li><a class="dropdown-item <?php echo e(app()->getLocale() == 'fr' ? 'active' : ''); ?>" href="<?php echo e(route('lang.switch', 'fr')); ?>">Français</a></li>
                    </ul>
                </div>

                <div class="dropdown">
                <button class="btn btn-white bg-white border shadow-sm rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-2"></i>
                    <?php echo e(auth()->user()->name ?? 'Admin'); ?>

                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> <?php echo e(__('center::sidebar.profile')); ?></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="<?php echo e(route('center.logout', ['tenant' => $tenant->domain ?? 'center'])); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt"></i> <?php echo e(__('center::sidebar.logout')); ?>

                            </button>
                        </form>
                    </li>
                </ul>
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
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        toggle?.addEventListener('click', toggleSidebar);
        close?.addEventListener('click', toggleSidebar);
        overlay?.addEventListener('click', toggleSidebar);

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    })
                    .catch((err) => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
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

        // Real-time Risk Detection Listener
        document.addEventListener('DOMContentLoaded', function() {
            if (window.Echo) {
                console.log('Echo initialized, subscribing to channel: center.<?php echo e($tenant->id); ?>');
                window.Echo.channel('center.<?php echo e($tenant->id); ?>')
                    .listen('.risk.detected', (e) => {
                        console.log('Risk Event Received:', e);
                        
                        // Play a sound (optional, but good for "Engine" feel)
                        // const audio = new Audio('/sounds/alert.mp3'); 
                        // audio.play().catch(e => console.log('Audio blocked'));

                        Toast.fire({
                            icon: 'warning',
                            title: '⚠️ ' + (e.message || 'Risk Detected'),
                            text: 'Student: ' + e.studentName + ' (' + e.riskType + ')',
                            timer: 5000
                        });
                    });
            } else {
                console.error('Laravel Echo not loaded');
            }
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\new project\antigravty\edu\edu\Modules/Center\resources/views/layouts/master.blade.php ENDPATH**/ ?>