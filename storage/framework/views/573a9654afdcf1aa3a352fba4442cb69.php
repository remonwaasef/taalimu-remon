<section id="faq" class="py-24 bg-white relative overflow-hidden">
    <!-- Subtle Decor -->
    <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-slate-50 rounded-full blur-[100px] -z-10 opacity-60"></div>

    <div class="container mx-auto px-4 lg:px-12">
        <!-- Section Header -->
        <div class="text-center mb-16" data-animate>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#f0f9ff] border border-blue-50 mb-6">
                <span class="text-xs font-black text-[#0ea5e9] uppercase tracking-[0.2em]"><?php echo e(__('landing.faq.badge')); ?></span>
            </div>
            <h2 class="text-3xl md:text-5xl font-black text-[#0f172a] mb-6 tracking-tight leading-tight">
                <?php echo e(__('landing.faq.title_prefix')); ?> <span class="text-[#22c55e]"><?php echo e(__('landing.faq.title_highlight')); ?></span>
            </h2>
            <?php $siteName = \App\Models\SiteSetting::get('site_name', 'Taalimu'); ?>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">
                <?php echo e(str_replace(config('app.name'), $siteName, __('landing.faq.subtitle'))); ?>

            </p>
        </div>

        <!-- FAQ Accordion -->
        <div class="max-w-3xl mx-auto space-y-4" x-data="{ active: null }" data-stagger>
            <?php for($index = 0; $index < 8; $index++): ?>
                <?php
                    $question = str_replace(config('app.name'), $siteName, __("landing.faq.items.$index.question"));
                    $answer = str_replace(config('app.name'), $siteName, __("landing.faq.items.$index.answer"));
                    if ($question === "landing.faq.items.$index.question") break;
                ?>
                <div class="group bg-white rounded-3xl border border-slate-100 shadow-soft transition-all duration-500 overflow-hidden" 
                     :class="{ 'border-[#22c55e]/30 shadow-premium': active === <?php echo e($index); ?> }">
                    <button 
                        @click="active = (active === <?php echo e($index); ?> ? null : <?php echo e($index); ?>)" 
                        class="flex items-center justify-between w-full text-left px-8 py-6 transition-colors"
                    >
                        <span class="text-lg font-black text-[#0f172a] group-hover:text-[#22c55e] transition-colors" :class="{ 'text-[#22c55e]': active === <?php echo e($index); ?> }">
                            <?php echo e($question); ?>

                        </span>
                        <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center transition-all duration-300"
                             :class="{ 'rotate-180 bg-[#22c55e] text-white': active === <?php echo e($index); ?> }">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </button>
                    <div 
                        x-show="active === <?php echo e($index); ?>" 
                        x-collapse 
                        class="px-8 pb-6 text-slate-500 font-medium leading-relaxed"
                    >
                        <?php echo e($answer); ?>

                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views/landing/partials/faq.blade.php ENDPATH**/ ?>