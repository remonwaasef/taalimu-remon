

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-slate-50/50 flex justify-center items-center p-4 lg:p-8" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
    <div class="w-full max-w-md bg-white rounded-[40px] shadow-2xl shadow-slate-200/60 p-8 lg:p-10 border border-slate-100 animate-fade-in-up">
        
        <div class="text-center mb-8">
            <div class="d-inline-flex justify-center items-center rounded-full bg-indigo-50 text-indigo-600 w-20 h-20 mb-4 mx-auto text-3xl font-bold shadow-sm">
                <i class="fas fa-key"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2 font-arabic tracking-tight">
                <?php echo e(__('Change Password')); ?>

            </h1>
            <p class="text-slate-500 text-sm font-arabic font-light">
                <?php echo e(__('Please update your password to continue.')); ?>

            </p>
        </div>

        <?php if($errors->any()): ?>
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                         <h3 class="text-sm font-bold text-red-800 font-arabic mb-1">
                            <?php echo e(__('Alert')); ?>

                        </h3>
                        <p class="text-sm text-red-600 font-arabic leading-relaxed">
                            <?php echo e($errors->first()); ?>

                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('center.password.change.submit')); ?>" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>
            
            <div class="space-y-1.5">
                <label class="text-[11px] font-bold text-slate-500 px-1 font-arabic uppercase tracking-wider"><?php echo e(__('New Password')); ?></label>
                <input type="password" name="password" 
                    class="w-full h-12 input-compact px-4 text-sm font-medium text-slate-900 placeholder:text-slate-400 border rounded-xl"
                    placeholder="••••••••" required>
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-bold text-slate-500 px-1 font-arabic uppercase tracking-wider"><?php echo e(__('Confirm Password')); ?></label>
                <input type="password" name="password_confirmation" 
                    class="w-full h-12 input-compact px-4 text-sm font-medium text-slate-900 placeholder:text-slate-400 border rounded-xl"
                    placeholder="••••••••" required>
            </div>

            <div class="pt-4">
                <button type="submit" 
                    class="w-full h-14 btn-submit-compact bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-base flex justify-center items-center gap-2 group rounded-full transition-all">
                    <span class="font-arabic"><?php echo e(__('Update Password')); ?></span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth-minimal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\auth\passwords\change.blade.php ENDPATH**/ ?>