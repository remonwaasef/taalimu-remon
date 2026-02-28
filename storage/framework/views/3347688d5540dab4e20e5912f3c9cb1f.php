<section id="features" class="py-16 lg:py-24 bg-muted/30">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12 lg:mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-light-purple/10 border border-light-purple/20 mb-6">
                <span class="text-sm font-medium text-light-purple"><?php echo e(__('landing.features.badge')); ?></span>
            </div>
            <h2 class="text-3xl lg:text-4xl font-bold text-foreground mb-4">
                <?php echo __('landing.features.title'); ?>

            </h2>
            <p class="text-muted-foreground text-lg max-w-2xl mx-auto">
                <?php echo e(__('landing.features.subtitle')); ?>

            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            <?php
                $featuresData = [
                    [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
                        'color' => 'slate',
                        'text_color' => 'text-primary',
                        'delay' => '0s'
                    ],
                    [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>',
                        'color' => 'slate',
                        'text_color' => 'text-primary',
                        'delay' => '0.1s'
                    ],
                    [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/><path d="M7 15h.01"/><path d="M11 15h.01"/></svg>',
                        'color' => 'slate',
                        'text_color' => 'text-primary',
                        'delay' => '0.2s'
                    ],
                    [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 1 1-7.6-11.7 8.38 8.38 0 0 1 3.8.9L21 3z"/></svg>',
                        'color' => 'slate',
                        'text_color' => 'text-primary',
                        'delay' => '0.3s'
                    ],
                    [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
                        'color' => 'slate',
                        'text_color' => 'text-primary',
                        'delay' => '0.4s'
                    ],
                    [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>',
                        'color' => 'slate',
                        'text_color' => 'text-primary',
                        'delay' => '0.5s'
                    ],
                    [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>',
                        'color' => 'slate',
                        'text_color' => 'text-primary',
                        'delay' => '0.6s'
                    ],
                    [
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
                        'color' => 'slate',
                        'text_color' => 'text-primary',
                        'delay' => '0.7s'
                    ]
                ];
            ?>

            <?php $__currentLoopData = __('landing.features.items'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $data = $featuresData[$index] ?? $featuresData[0]; ?>
                <div
                    class="group bg-card rounded-[40px] p-6 lg:p-8 border border-border hover:border-light-purple/30 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl animate-fade-in"
                    style="animation-delay: <?php echo e($data['delay']); ?>;"
                >
                    <!-- Icon -->
                    <div class="w-14 h-14 rounded-[20px] bg-slate-100 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <div class="text-primary">
                            <?php echo $data['icon']; ?>

                        </div>
                    </div>

                    <!-- Title -->
                    <h3 class="text-xl font-semibold text-foreground mb-3">
                        <?php echo e($item['title']); ?>

                    </h3>

                    <!-- Description -->
                    <p class="text-muted-foreground leading-relaxed">
                        <?php echo e($item['description']); ?>

                    </p>

                    <!-- Hover indicator -->
                    <div class="mt-6 flex items-center gap-2 <?php echo e($data['text_color']); ?> opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-sm font-medium"><?php echo e(__('landing.features.learn_more')); ?></span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views/landing/partials/features.blade.php ENDPATH**/ ?>