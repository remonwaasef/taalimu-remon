<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'لوحة تحكم المعلم'); ?> - <?php echo e(config('app.name', 'Taalimu')); ?></title>
    
    <!-- Google Fonts (Cairo for Arabic) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <?php if(app()->getLocale() == 'ar'): ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <?php else: ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php endif; ?>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --primary-color: #162963; /* Academic Blue */
            --primary-light: #1e3a8a;
            --accent-purple: #3A0CA3;
            --secondary-green: #22C55E;
            --sidebar-width: 280px;
            --sidebar-bg: #0f172a;
            --sidebar-text: rgba(255, 255, 255, 0.7);
            --bg-light: #f1f5f9;
            --radius-lg: 1.25rem;
            --radius-md: 0.85rem;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.02);
            --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.05);
        }

        body { 
            font-family: 'Cairo', sans-serif; 
            background-color: var(--bg-light);
            color: #1e293b;
            min-height: 100vh;
        }

        /* Global Primary Overrides */
        .btn-primary {
            background: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            border-radius: 50px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: var(--accent-purple) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(22, 41, 99, 0.2);
        }
        .btn-outline-primary {
            color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            border-radius: 50px;
        }
        .btn-outline-primary:hover {
            background-color: var(--primary-color) !important;
            color: white !important;
        }
        .text-primary { color: var(--primary-color) !important; }
        .bg-primary { background-color: var(--primary-color) !important; }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            top: 0;
            z-index: 1050;
            transition: all 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        [dir="rtl"] .sidebar { right: 0; border-left: 1px solid rgba(255,255,255,0.05); }
        [dir="ltr"] .sidebar { left: 0; border-right: 1px solid rgba(255,255,255,0.05); }

        .sidebar-brand {
            padding: 1.5rem;
            text-align: center;
        }
        .sidebar-brand img {
            max-width: 160px;
            height: auto;
            background: rgba(255,255,255,0.1);
            padding: 8px 16px;
            border-radius: 12px;
        }

        .main-content {
            transition: all 0.3s ease;
            padding: 2.5rem;
            min-height: 100vh;
        }

        @media (min-width: 992px) {
            [dir="rtl"] .main-content { margin-right: var(--sidebar-width); }
            [dir="ltr"] .main-content { margin-left: var(--sidebar-width); }
        }

        .nav-link {
            color: var(--sidebar-text);
            padding: 0.75rem 1.25rem;
            margin: 0.3rem 1.25rem;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            text-decoration: none;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.05);
            color: white;
            opacity: 1;
        }

        .nav-link.active {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            opacity: 1;
        }

        .nav-link i { font-size: 1.1rem; width: 24px; opacity: 0.8; }
        .nav-link.active i { opacity: 1; }

        .card {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }

        .stats-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(0,0,0,0.03);
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand border-bottom border-white border-opacity-10">
            <img src="<?php echo e(asset('images/brand/logo-full.png')); ?>" alt="Taalimu">
            <div class="text-white opacity-50 x-small mt-2 fw-bold"><?php echo e(__('instructor::sidebar.panel_title')); ?></div>
        </div>

        <nav class="mt-4">
            <a href="<?php echo e(route('instructor.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('instructor.dashboard') ? 'active' : ''); ?>">
                <i class="fas fa-home"></i> <span><?php echo e(__('instructor::sidebar.dashboard')); ?></span>
            </a>
            <a href="<?php echo e(route('instructor.students.list')); ?>" class="nav-link <?php echo e(request()->routeIs('instructor.students.list') ? 'active' : ''); ?>">
                <i class="fas fa-user-graduate"></i> <span><?php echo e(__('instructor::sidebar.students')); ?></span>
            </a>
            <a href="<?php echo e(route('instructor.groups.list')); ?>" class="nav-link <?php echo e(request()->routeIs('instructor.groups.list') ? 'active' : ''); ?>">
                <i class="fas fa-users"></i> <span><?php echo e(__('instructor::sidebar.groups')); ?></span>
            </a>
            <a href="<?php echo e(route('instructor.schedules.index')); ?>" class="nav-link <?php echo e(request()->routeIs('instructor.schedules.*') ? 'active' : ''); ?>">
                <i class="fas fa-calendar-alt"></i> <span><?php echo e(__('instructor::sidebar.schedules')); ?></span>
            </a>
            <a href="<?php echo e(route('instructor.attendance.index')); ?>" class="nav-link <?php echo e(request()->routeIs('instructor.attendance.*') ? 'active' : ''); ?>">
                <i class="fas fa-clipboard-check"></i> <span><?php echo e(__('instructor::sidebar.attendance')); ?></span>
            </a>
            <a href="<?php echo e(route('instructor.billing')); ?>" class="nav-link <?php echo e(request()->routeIs('instructor.billing') ? 'active' : ''); ?>">
                <i class="fas fa-wallet"></i> <span><?php echo e(__('instructor::sidebar.billing')); ?></span>
            </a>
            <a href="<?php echo e(route('instructor.settings')); ?>" class="nav-link <?php echo e(request()->routeIs('instructor.settings') || request()->routeIs('instructor.whatsapp.*') ? 'active' : ''); ?>">
                <i class="fas fa-cog"></i> <span><?php echo e(__('instructor::sidebar.settings')); ?></span>
            </a>
            
            <div class="mt-5 p-3">
                <form action="<?php echo e(route('center.logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-danger w-100 rounded-pill px-4 border-0" style="background: rgba(220, 53, 69, 0.05);">
                        <i class="fas fa-sign-out-alt me-2"></i> <?php echo e(__('instructor::sidebar.logout')); ?>

                    </button>
                </form>
            </div>
        </nav>
    </aside>
    
    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <style>
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1040;
        }
        .sidebar-overlay.active { display: block; }

        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            z-index: 1060;
            width: 45px;
            height: 45px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: none;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        @media (max-width: 991.98px) {
            .mobile-toggle { display: flex; }
            [dir="rtl"] .mobile-toggle { right: 1rem; }
            [dir="ltr"] .mobile-toggle { left: 1rem; }
            
            [dir="rtl"] .sidebar { transform: translateX(100%); }
            [dir="ltr"] .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0) !important; }
            
            .main-content { padding: 1.5rem; padding-top: 5rem; }
        }
        .x-small { font-size: 0.75rem; }
    </style>

    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Main Content -->
    <main class="main-content">
        <header class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-bold mb-0"><?php echo $__env->yieldContent('page-title', __('instructor::sidebar.welcome')); ?></h3>
                <p class="text-muted small mb-0"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Language Switcher -->
                <div class="dropdown">
                    <button class="btn btn-white bg-white shadow-sm rounded-pill px-3 dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                        <i class="fas fa-language me-2 text-primary"></i>
                        <?php echo e(strtoupper(app()->getLocale())); ?>

                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                        <li><a class="dropdown-item" href="<?php echo e(route('instructor.set-locale', 'ar')); ?>">العربية</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('instructor.set-locale', 'en')); ?>">English</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('instructor.set-locale', 'fr')); ?>">Français</a></li>
                    </ul>
                </div>

                <div class="dropdown">
                    <button class="btn btn-white bg-white shadow-sm rounded-pill px-4 dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                        <i class="fas fa-user-circle me-2 text-primary"></i>
                        <?php echo e(auth()->user()->name ?? __('instructor::sidebar.instructor')); ?>

                    </button>
                </div>
            </div>
        </header>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (toggleBtn && sidebar && overlay) {
                const toggleSidebar = () => {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                };

                toggleBtn.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);
            }
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\components\layouts\master.blade.php ENDPATH**/ ?>