<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>" class="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php echo SEO::generate(); ?>

    
    <!-- Multilingual SEO -->
    <link rel="alternate" hreflang="ar" href="<?php echo e(url()->current()); ?>?hl=ar" />
    <link rel="alternate" hreflang="en" href="<?php echo e(url()->current()); ?>?hl=en" />
    <link rel="alternate" hreflang="fr" href="<?php echo e(url()->current()); ?>?hl=fr" />
    <link rel="alternate" hreflang="x-default" href="<?php echo e(url()->current()); ?>" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/brand/logo-icon.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/brand/logo-icon.png')); ?>">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebApplication",
      "name": "Taalimu",
      "url": "https://taalimu.com",
      "logo": "https://taalimu.com/images/logo.png",
      "description": "<?php echo e(__('landing.hero.subtitle')); ?>",
      "applicationCategory": "EducationalApplication",
      "operatingSystem": "Web",
      "offer": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "USD"
      }
    }
    </script>

    <!-- Fonts - Optimized Loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">    
    <!-- Preload Hero Image for faster LCP -->
    <?php
        $heroImage = match(app()->getLocale()) {
            'en' => 'hero-mockup-en.webp',
            'fr' => 'hero-mockup-fr.webp',
            default => 'hero-mockup-v2.webp',
        };
    ?>
    <link rel="preload" as="image" href="<?php echo e(asset('images/' . $heroImage)); ?>" type="image/webp">
    
    <!-- Resource Hints -->
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- PWA Support -->
    <link rel="manifest" href="/manifest.json?v=3">
    <meta name="theme-color" content="#3A0CA3">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Taalimu">

    <script>
        // Global PWA State Handler
        window.pwaDeferredPrompt = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            window.pwaDeferredPrompt = e;
            // Dispatch custom event for components that are already loaded
            window.dispatchEvent(new CustomEvent('pwa-prompt-available'));
        });
    </script>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/landing-new.css']); ?>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom CSS Variables -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased text-foreground bg-background selection:bg-primary selection:text-white">
    <div class="min-h-screen flex flex-col">
        <?php echo $__env->make('landing.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="flex-grow">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <?php if (isset($component)) { $__componentOriginal40c17993d0c21c560a83b65d062854a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal40c17993d0c21c560a83b65d062854a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pwa-install','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pwa-install'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal40c17993d0c21c560a83b65d062854a8)): ?>
<?php $attributes = $__attributesOriginal40c17993d0c21c560a83b65d062854a8; ?>
<?php unset($__attributesOriginal40c17993d0c21c560a83b65d062854a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal40c17993d0c21c560a83b65d062854a8)): ?>
<?php $component = $__componentOriginal40c17993d0c21c560a83b65d062854a8; ?>
<?php unset($__componentOriginal40c17993d0c21c560a83b65d062854a8); ?>
<?php endif; ?>

        <?php echo $__env->make('landing.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    
    
    <!-- Cookie Consent Banner -->
    <?php echo $__env->make('components.cookie-consent', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Alpine.js with Collapse plugin for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
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
    </script>

    <!-- Service Worker Registration & PWA Redirection -->
    <script>
        // Redirect to login if opened as PWA from home page
        if (window.matchMedia('(display-mode: standalone)').matches && 
            (window.location.pathname === '/' || window.location.pathname === '')) {
            window.location.href = '/login';
        }

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js?v=6')
                    .then((reg) => console.log('SW registered:', reg.scope))
                    .catch((err) => console.log('SW failed:', err));
            });
        }
    </script>

    <!-- V2: Scroll Animation Observer -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

            document.querySelectorAll('[data-animate], [data-stagger]').forEach(el => observer.observe(el));
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views/layouts/landing-new.blade.php ENDPATH**/ ?>