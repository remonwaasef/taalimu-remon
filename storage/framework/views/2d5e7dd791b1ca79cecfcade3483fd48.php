

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center mesh-gradient-soft noise-overlay py-2 px-4 pt-32">
    <div class="max-w-2xl w-full">
        <!-- Success Icon -->
        <div class="text-center mb-3 animate-fade-in">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-success-green rounded-full mb-2 shadow-lg animate-scale-in">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-foreground mb-1">
                <?php echo e(__('auth.registration.success_title')); ?>

            </h1>
            <p class="text-muted-foreground text-sm">
                <?php echo e(__('auth.registration.success_subtitle')); ?>

            </p>
        </div>

        <?php
            $mode = config('app.tenancy_mode', 'subdomain');
            $protocol = request()->isSecure() ? 'https://' : 'http://';
            $port = (request()->getPort() && !in_array(request()->getPort(), [80, 443])) ? ':' . request()->getPort() : '';
            $domain = config('app.tenant_domain', 'localhost');
            
            if ($mode === 'path') {
                $accessUrl = url('/c/' . session('tenant_domain'));
            } else {
                $accessUrl = $protocol . session('tenant_domain') . '.' . $domain . $port;
            }
        ?>

        <!-- Center Details Card -->
        <div class="bg-card border-2 border-primary/20 rounded-2xl shadow-2xl p-4 space-y-3 animate-scale-in" style="animation-delay: 0.1s;">
            <!-- Center Name with Icon -->
            <div class="text-center pb-3 border-b-2 border-border">
                <div class="inline-flex items-center gap-2 bg-primary/10 px-4 py-2 rounded-xl">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h2 class="text-xl font-bold text-primary">
                        <?php echo e(session('center_name')); ?>

                    </h2>
                </div>
            </div>

            <!-- Access URL with Icon -->
            <div>
                <label class="flex items-center gap-2 text-xs font-semibold text-foreground mb-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                    <?php echo e(__('auth.registration.your_center_url')); ?>

                </label>
                <div class="flex items-center gap-2 p-3 bg-gradient-to-r from-primary/5 to-accent/5 rounded-xl border-2 border-primary/20">
                    <input 
                        type="text" 
                        readonly 
                        value="<?php echo e($accessUrl); ?>"
                        class="flex-1 bg-transparent border-0 text-sm font-mono font-semibold text-primary focus:outline-none select-all"
                        id="centerUrl"
                        dir="ltr"
                    >
                    <button 
                        onclick="copyUrl()"
                        class="px-3 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-all shadow-md hover:shadow-lg flex items-center gap-1 font-semibold text-sm"
                        id="copyBtn"
                    >
                        <i class="fas fa-copy"></i>
                        <span class="text-sm"><?php echo e(__('auth.registration.copy')); ?></span>
                    </button>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-3">
                <div class="bg-muted/50 p-3 rounded-xl border border-border">
                    <label class="flex items-center gap-2 text-sm font-medium text-muted-foreground mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                        <?php echo e(__('auth.registration.email')); ?>

                    </label>
                    <div class="text-foreground font-semibold truncate">
                        <?php echo e(session('admin_email')); ?>

                    </div>
                </div>
                <div class="bg-muted/50 p-3 rounded-xl border border-border">
                    <label class="flex items-center gap-2 text-sm font-medium text-muted-foreground mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        <?php echo e(__('auth.registration.password')); ?>

                    </label>
                    <div class="text-muted-foreground text-sm">
                        <?php echo e(__('auth.registration.password_hint')); ?>

                    </div>
                </div>
            </div>

            <!-- Access Button - More Prominent -->
            <div class="pt-1">
                <a 
                    href="<?php echo e($accessUrl); ?>"
                    class="group block w-full text-center py-3 bg-primary text-white font-bold text-base rounded-xl hover:shadow-2xl transition-all transform hover:scale-[1.02]"
                >
                    <span class="flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <?php echo e(__('auth.registration.access_center')); ?>

                        <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </span>
                </a>
            </div>
        </div>

        </div>
    </div>
</div>

<script>
function copyUrl() {
    const urlInput = document.getElementById('centerUrl');
    const copyBtn = document.getElementById('copyBtn');
    
    urlInput.select();
    urlInput.setSelectionRange(0, 99999);
    
    navigator.clipboard.writeText(urlInput.value).then(() => {
        const originalHTML = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="fas fa-check"></i> <span class="text-sm"><?php echo e(__('auth.registration.copied')); ?></span>';
        copyBtn.classList.add('bg-success-green', 'scale-110');
        
        setTimeout(() => {
            copyBtn.innerHTML = originalHTML;
            copyBtn.classList.remove('bg-success-green', 'scale-110');
        }, 2000);
    }).catch(() => {
        document.execCommand('copy');
        alert('<?php echo e(__('auth.registration.url_copied')); ?>');
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views/auth/registration-success.blade.php ENDPATH**/ ?>