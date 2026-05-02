

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 bg-slate-50/50 mesh-gradient-soft noise-overlay">
    <div class="max-w-xl mx-auto w-full animate-fade-in-up">
        <div class="bg-white border border-slate-100/50 rounded-[2.5rem] shadow-2xl shadow-blue-900/5 overflow-hidden backdrop-blur-xl relative">
            <!-- Decorative glow -->
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-brand-secondary/10 blur-[80px] rounded-full pointer-events-none"></div>
            <div class="p-8 lg:p-10">
                <div class="text-center mb-10 relative z-10">
                    <h3 class="text-3xl font-bold gradient-hero bg-clip-text text-transparent mb-2 font-arabic tracking-tight">بوابة الدخول الموحدة</h3>
                    <p class="text-slate-500 font-arabic"><?php echo e(__('auth.login.subtitle')); ?></p>
                </div>

                <div class="space-y-6">
                    <!-- Tenant Login -->
                    <div class="p-8 bg-slate-50/50 rounded-3xl border border-slate-100/50 relative z-10 shadow-sm">
                        <div class="flex items-center mb-6">
                            <div class="bg-brand-primary/10 text-brand-primary rounded-full flex items-center justify-center w-12 h-12 me-4">
                                <span class="text-2xl">🏢</span>
                            </div>
                            <div>
                                <h5 class="font-bold text-foreground mb-1">دخول المراكز والطلاب</h5>
                                <p class="text-sm text-muted-foreground">اختر مركزك التعليمي للدخول</p>
                            </div>
                        </div>
                        
                        <!-- Tenant List -->
                        <div class="space-y-4">
                            <?php $__empty_1 = true; $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                 <a href="<?php echo e(tenant_url('login', $tenant)); ?>" class="flex items-center justify-between w-full px-5 py-4 bg-white border border-slate-100 rounded-2xl hover:border-brand-secondary hover:shadow-lg hover:shadow-brand-secondary/5 transition-all group">
                                    <span class="font-bold text-slate-700"><?php echo e($tenant->name); ?></span>
                                    <span class="text-brand-secondary opacity-0 group-hover:opacity-100 -translate-x-4 group-hover:translate-x-0 transition-all font-bold">
                                        <i class="fas fa-arrow-left rtl:rotate-180"></i>
                                    </span>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-slate-400 text-center text-sm py-4 font-arabic">لا توجد مراكز مسجلة بعد</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Admin Login -->
                    <a href="<?php echo e(route('admin.login')); ?>" class="block group relative z-10">
                        <div class="p-8 bg-slate-50/50 rounded-3xl border border-slate-100/50 group-hover:border-yellow-500/50 group-hover:bg-white group-hover:shadow-lg group-hover:shadow-yellow-500/5 transition-all">
                            <div class="flex items-center">
                                <div class="bg-yellow-500/10 text-yellow-600 rounded-2xl flex items-center justify-center w-14 h-14 me-4 shadow-inner">
                                    <span class="text-2xl">🛡️</span>
                                </div>
                                <div class="flex-1">
                                    <h5 class="font-bold text-slate-800 mb-1 font-arabic"><?php echo e(__('auth.login.admin_login')); ?></h5>
                                    <p class="text-sm text-slate-500 font-arabic">لوحة تحكم إدارة النظام</p>
                                </div>
                                <div class="text-yellow-500 opacity-0 group-hover:opacity-100 translate-x-4 group-hover:translate-x-0 transition-all">
                                    <i class="fas fa-arrow-left rtl:rotate-180"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views\auth\login-portal.blade.php ENDPATH**/ ?>