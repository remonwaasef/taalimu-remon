<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'لوحة تحكم المعلم'); ?> - <?php echo e(config('app.name', 'Taalimu')); ?></title>

    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets/hope-ui/images/favicon.ico')); ?>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Hope UI CSS (from public/assets/hope-ui) -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/hope-ui/css/libs.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/hope-ui/css/hope-ui.css?v=1.1.0')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/hope-ui/css/custom.css?v=1.1.0')); ?>">
    <?php if(app()->getLocale() == 'ar'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/hope-ui/css/rtl.css?v=1.1.0')); ?>">
    <?php endif; ?>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Taalimu Unified Premium Emerald Theme -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/hope-ui/css/taalimu-unified.css?v=' . time())); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : ''); ?>">

    <!-- Sidebar Component -->
    <?php echo $__env->make('instructor::components.layouts.hope-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="main-content">
        <div class="position-relative">
            <!-- Header Component -->
            <?php echo $__env->make('instructor::components.layouts.hope-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <div class="iq-navbar-header" style="height: 260px;">
                <div class="container-fluid iq-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flex-wrap d-flex justify-content-between align-items-center pt-2 pb-5">
                                <?php if (! empty(trim($__env->yieldContent('page-title')))): ?>
                                <div>
                                    <h1 class="text-white display-5 mb-1 fw-bold"><?php echo $__env->yieldContent('page-title'); ?></h1>
                                    <p class="text-white opacity-75 mb-0 fw-medium">
                                        <?php echo $__env->yieldContent('page-subtitle'); ?>
                                    </p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <?php echo $__env->yieldContent('page-actions'); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iq-header-img">
                     <img src="<?php echo e(asset('assets/hope-ui/images/dashboard/top-header.png')); ?>" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">
                </div>
            </div>
        </div>

        <div class="container-fluid content-inner py-0">
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
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-body">
                <ul class="left-panel list-inline mb-0 p-0">
                    <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                </ul>
                <div class="right-panel">
                    ©<script>document.write(new Date().getFullYear())</script> <?php echo e(config('app.name')); ?>, Made with <span class="text-gray border-gray"> Hope UI</span>
                </div>
            </div>
        </footer>
    </main>

    <!-- Backend Bundle JavaScript -->
    <script src="<?php echo e(asset('assets/hope-ui/js/libs.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- app script -->
    <script src="<?php echo e(asset('assets/hope-ui/js/hope-ui.js')); ?>"></script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php echo $__env->yieldPushContent('modals'); ?>
</body>
</html>
<?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\components\layouts\hope-master.blade.php ENDPATH**/ ?>