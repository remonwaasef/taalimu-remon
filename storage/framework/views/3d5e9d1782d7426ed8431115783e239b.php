

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-slate-50/50 mesh-gradient-soft noise-overlay p-4">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-2xl">
        <!-- Demo Badge -->
        <div class="mb-6 text-center space-y-2">
            <span class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wide">
                🧪 <?php echo e(__('Demo Mode')); ?>

            </span>
            
            <?php if(session('error_flash')): ?>
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4 text-right">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="mr-3">
                        <p class="text-sm text-red-700">
                            <?php echo e(session('error_flash')); ?>

                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!env('STRIPE_KEY') && !env('STRIPE_DEMO_MODE')): ?>
            <p class="text-[10px] text-red-500 font-medium">
                <?php echo e(__('Running in fallback mode because Stripe keys are missing')); ?>

            </p>
            <?php endif; ?>
        </div>

        <!-- Payment Info -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-brand-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2"><?php echo e(__('Demo Payment Page')); ?></h2>
            <p class="text-gray-600 text-sm">هذه صفحة دفع تجريبية للاختبار بدون مفاتيح Stripe</p>
        </div>

        <!-- Plan Details -->
        <div class="bg-brand-primary/5 rounded-xl p-6 mb-6">
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm text-gray-600">الخطة المختارة:</span>
                <span class="text-lg font-bold text-brand-primary">
                    <?php echo e(app()->getLocale() == 'ar' ? $package->name : $package->name_en); ?>

                </span>
            </div>
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm text-gray-600">المركز:</span>
                <span class="font-semibold text-gray-900"><?php echo e($tenant->name); ?></span>
            </div>
            <div class="border-t border-brand-primary/20 my-3"></div>
            <div class="space-y-2">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-sm text-gray-600">السعر الأصلي:</span>
                    <span class="font-semibold text-gray-900">
                        <?php echo e(number_format($basePrice, 0)); ?> <?php echo e(\App\Models\SiteSetting::get('currency_symbol', 'جنيه')); ?>

                        <span class="text-[10px] text-gray-400">/ <?php echo e($billingCycle === 'yearly' ? 'سنوياً' : 'شهرياً'); ?></span>
                    </span>
                </div>
                
                <?php if($discountAmount > 0): ?>
                <div class="flex justify-between items-center text-sm text-emerald-600">
                    <span class="font-medium">الخصم (<?php echo e($couponCode); ?>):</span>
                    <span class="font-bold">- <?php echo e(number_format($discountAmount, 0)); ?> <?php echo e(\App\Models\SiteSetting::get('currency_symbol', 'جنيه')); ?></span>
                </div>
                <?php endif; ?>

                <div class="flex justify-between items-center pt-2 border-t border-brand-primary/10">
                    <span class="text-base font-bold text-gray-700">المجموع النهائي:</span>
                    <div class="flex flex-col items-end">
                        <span class="text-2xl font-black text-brand-primary">
                            <?php echo e(number_format($totalAmount, 0)); ?> <?php echo e(\App\Models\SiteSetting::get('currency_symbol', 'جنيه')); ?>

                        </span>
                        <span class="text-[10px] font-bold text-brand-primary/60">خطة <?php echo e($billingCycle === 'yearly' ? 'سنوية' : 'شهرية'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demo Instructions -->
        <div class="bg-brand-secondary/10 border border-brand-secondary/20 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-brand-secondary mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-sm text-brand-secondary font-semibold mb-1">وضع التجربة</p>
                    <p class="text-xs text-brand-secondary/80">
                        في الوضع الحقيقي، ستُحول إلى صفحة Stripe للدفع. هنا يمكنك محاكاة عملية الدفع مباشرة.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <form action="<?php echo e(route('payment.demo.success')); ?>" method="GET">
                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-xl font-bold text-lg hover:from-green-700 hover:to-green-800 transition-all transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                    ✓ محاكاة دفع ناجح
                </button>
            </form>

            <a href="<?php echo e(route('payment.cancel')); ?>" class="block w-full bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-semibold text-center hover:bg-gray-200 transition">
                إلغاء
            </a>
        </div>

        <!-- Footer Note -->
        <p class="text-center text-xs text-gray-500 mt-6">
            لتفعيل الدفع الحقيقي، أضف مفاتيح Stripe في ملف .env وأوقف STRIPE_DEMO_MODE
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views\auth\payment-demo.blade.php ENDPATH**/ ?>