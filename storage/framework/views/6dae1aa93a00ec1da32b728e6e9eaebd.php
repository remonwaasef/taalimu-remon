

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-white py-12 px-4 pt-32">
    <!-- Hero Orbs Decoration -->
    <div class="hero-orb orb-1 opacity-60"></div>
    <div class="hero-orb orb-2 opacity-40"></div>

    <div class="max-w-2xl w-full relative z-10">
        <!-- Success Icon -->
        <div class="text-center mb-10 animate-fade-in-up">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-emerald-500 rounded-full mb-6 shadow-2xl shadow-emerald-500/20 animate-scale-in">
                <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 mb-4 tracking-tight leading-tight">
                <span class="gradient-text"><?php echo e(__('auth.registration.success_title')); ?></span>
            </h1>
            <p class="text-slate-500 text-lg md:text-xl font-medium max-w-lg mx-auto">
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

        <!-- Center Details Glass Card -->
        <div class="glass-premium rounded-[2.5rem] p-8 md:p-12 space-y-10 animate-scale-in border border-white/60 shadow-2xl shadow-slate-200/50" style="animation-delay: 0.1s;">
            <!-- Center Name -->
            <div class="text-center pb-8 border-b border-slate-100/80">
                <div class="inline-flex flex-col items-center gap-4">
                    <div class="w-16 h-16 rounded-3xl bg-brand-primary/10 flex items-center justify-center text-brand-primary shadow-inner">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">
                        <?php echo e(session('center_name')); ?>

                    </h2>
                </div>
            </div>

            <!-- Access URL -->
            <div class="space-y-4">
                <label class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-1">
                    <?php echo e(__('auth.registration.your_center_url')); ?>

                </label>
                <div class="flex flex-col sm:flex-row items-center gap-3 p-2 bg-white/60 backdrop-blur-md rounded-[2rem] border-2 border-slate-100/80 shadow-inner group-focus-within:border-brand-primary/30 transition-all transition-all duration-500">
                    <input 
                        type="text" 
                        readonly 
                        value="<?php echo e($accessUrl); ?>"
                        class="flex-1 bg-transparent border-0 text-lg font-mono font-bold text-slate-600 px-6 py-3 focus:outline-none select-all w-full sm:w-auto"
                        id="centerUrl"
                        dir="ltr"
                    >
                    <button 
                        onclick="copyUrl()"
                        class="w-full sm:w-auto px-8 py-4 bg-slate-900 text-white rounded-[1.5rem] hover:bg-slate-800 transition-all shadow-xl hover:shadow-slate-900/30 flex items-center justify-center gap-3 font-black text-base"
                        id="copyBtn"
                    >
                        <i class="fas fa-copy"></i>
                        <span><?php echo e(__('auth.registration.copy')); ?></span>
                    </button>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white/50 backdrop-blur-md p-6 rounded-3xl border border-white/80 shadow-sm transition-all hover:shadow-md hover:border-brand-primary/20 group">
                    <label class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 group-hover:text-brand-primary transition-colors">
                        <i class="fas fa-envelope opacity-70"></i>
                        <?php echo e(__('auth.registration.email')); ?>

                    </label>
                    <div class="text-slate-900 font-extrabold break-all text-base tracking-tight">
                        <?php echo e(session('admin_email')); ?>

                    </div>
                </div>
                <div class="bg-white/50 backdrop-blur-md p-6 rounded-3xl border border-white/80 shadow-sm transition-all hover:shadow-md hover:border-brand-primary/20 group">
                    <label class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 group-hover:text-brand-primary transition-colors">
                        <i class="fas fa-key opacity-70"></i>
                        <?php echo e(__('auth.registration.password')); ?>

                    </label>
                    <div class="text-slate-500 font-bold text-sm leading-relaxed">
                        <?php echo e(__('auth.registration.password_hint')); ?>

                    </div>
                </div>
            </div>

            <div class="pt-6">
                <a 
                    href="<?php echo e($accessUrl); ?>"
                    class="group relative flex items-center justify-center gap-4 w-full py-6 px-8 bg-slate-900 text-white rounded-[2rem] font-black text-2xl hover:bg-slate-800 transition-all shadow-2xl hover:shadow-slate-900/40 transform hover:-translate-y-2 overflow-hidden"
                >
                    <span class="absolute inset-0 w-full h-full -mt-1 rounded-2xl opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                    <span class="relative z-10 flex items-center gap-4">
                        <i class="fas fa-rocket text-emerald-400 animate-pulse"></i>
                        <?php echo e(__('auth.registration.access_center')); ?>

                        <i class="fas <?php echo e(app()->getLocale() == 'ar' ? 'fa-arrow-left group-hover:-translate-x-3' : 'fa-arrow-right group-hover:translate-x-3'); ?> transition-transform duration-300"></i>
                    </span>
                </a>
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

<?php echo $__env->make('layouts.landing-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views\auth\registration-success.blade.php ENDPATH**/ ?>