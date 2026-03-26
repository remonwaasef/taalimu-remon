<section class="py-24 bg-[#f8fafc] relative overflow-hidden section-wave section-wave-white"
    x-data="{ 
        showMsg1: false, showMsg2: false, showMsg3: false, showTyping: false,
        startChat() {
            this.showTyping = true;
            setTimeout(() => { this.showTyping = false; this.showMsg1 = true; }, 1200);
            setTimeout(() => { this.showMsg2 = true; }, 2400);
            setTimeout(() => { this.showTyping = true; }, 3200);
            setTimeout(() => { this.showTyping = false; this.showMsg3 = true; }, 4400);
        }
    }"
    x-intersect.once="startChat()"
>
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-white/50 rounded-full blur-[120px] -z-10"></div>
    
    <div class="container mx-auto px-4 lg:px-12">
        <!-- Section Header -->
        <div class="text-center mb-20" data-animate>
            <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-slate-100 mb-6 shadow-sm">
                <i class="fab fa-whatsapp text-[#25D366] text-sm"></i>
                <span class="text-xs font-extrabold text-[#0ea5e9] uppercase tracking-[0.2em]"><?php echo e(__('landing.automation.badge')); ?></span>
            </div>
            <h2 class="text-3xl md:text-5xl font-black text-[#0f172a] mb-6 tracking-tight leading-tight">
                <?php echo e(__('landing.automation.title_prefix')); ?> <span class="text-[#22c55e]"><?php echo e(\App\Models\SiteSetting::get('site_name', 'Taalimu')); ?></span>
            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">
                <?php echo e(__('landing.automation.subtitle')); ?>

            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <!-- Left: Step-by-Step Flow -->
            <div class="space-y-12" data-stagger>
                <?php $__currentLoopData = [
                    ['num' => '01', 'icon' => 'fa-user-plus', 'color' => '#22c55e', 'bg' => '#f0fdf4'],
                    ['num' => '02', 'icon' => 'fa-comment-alt-check', 'color' => '#0ea5e9', 'bg' => '#f0f9ff'],
                    ['num' => '03', 'icon' => 'fa-wallet', 'color' => '#8b5cf6', 'bg' => '#f5f3ff']
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group flex gap-6">
                    <div class="relative flex-shrink-0">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform duration-500" style="background-color: <?php echo e($step['bg']); ?>; color: <?php echo e($step['color']); ?>;">
                            <i class="fas <?php echo e($step['icon']); ?>"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-[10px] font-black text-slate-800 border border-slate-100">
                            <?php echo e($step['num']); ?>

                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-[#0f172a] mb-2 group-hover:text-[#22c55e] transition-colors">
                            <?php echo e(__("landing.automation.step" . ($index + 1) . ".title")); ?>

                        </h3>
                        <p class="text-slate-500 font-medium leading-relaxed">
                            <?php echo e(__("landing.automation.step" . ($index + 1) . ".description")); ?>

                        </p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Right: Interactive WhatsApp Mockup with Typing Indicator -->
            <div class="relative lg:pl-12" data-animate="fade-right">
                <div class="relative z-10 mx-auto max-w-[340px] rounded-[3rem] border-[10px] border-[#1e293b] shadow-[0_50px_100px_-20px_rgba(15,23,42,0.3)] overflow-hidden aspect-[9/18.5] bg-[#ece5dd]">
                    <!-- WhatsApp Header -->
                    <div class="bg-[#075e54] p-5 pt-10 text-white flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-school"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold"><?php echo e(\App\Models\SiteSetting::get('site_name', 'Taalimu')); ?></div>
                            <div class="text-[10px] opacity-70">Online</div>
                        </div>
                    </div>

                    <!-- Chat Content with Staggered Reveal -->
                    <div class="p-4 space-y-4">
                        <!-- Typing Indicator (shows before messages) -->
                        <div x-show="showTyping" x-transition.opacity class="typing-indicator">
                            <span></span><span></span><span></span>
                        </div>

                        <!-- Message 1 (Bot) -->
                        <div x-show="showMsg1" x-transition.opacity.duration.500ms class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm max-w-[85%]">
                            <p class="text-[13px] text-slate-800 font-medium">Hello! Welcome to <?php echo e(\App\Models\SiteSetting::get('site_name', 'Taalimu')); ?>. How can I help you today?</p>
                            <span class="text-[9px] text-slate-400 float-right mt-1">10:00 AM</span>
                        </div>

                        <!-- Message 2 (Parent) -->
                        <div x-show="showMsg2" x-transition.opacity.duration.500ms class="bg-[#dcf8c6] p-3 rounded-2xl rounded-tr-none shadow-sm max-w-[85%] ml-auto">
                            <p class="text-[13px] text-slate-800 font-medium">I'd like to register my son for the Math course.</p>
                            <span class="text-[9px] text-slate-400 float-right mt-1">10:01 AM</span>
                        </div>

                        <!-- Message 3 (Bot/Automated) -->
                        <div x-show="showMsg3" x-transition.opacity.duration.500ms class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm max-w-[85%]">
                            <p class="text-[13px] text-slate-800 font-medium">Great! Please click the link below to complete the registration:</p>
                            <div class="mt-2 p-2 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-[#22c55e]/10 flex items-center justify-center text-[#22c55e]">
                                    <i class="fas fa-link"></i>
                                </div>
                                <div class="text-[11px] font-extrabold text-[#22c55e]">Registration Form</div>
                            </div>
                            <span class="text-[9px] text-slate-400 float-right mt-1">10:01 AM</span>
                        </div>
                    </div>
                </div>

                <!-- Floating Accents -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#22c55e]/10 rounded-full blur-2xl animate-pulse"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-[#0ea5e9]/10 rounded-full blur-3xl"></div>
            </div>
        </div>

        <!-- High-Impact Result -->
        <div class="mt-24 max-w-4xl mx-auto" data-animate="scale">
            <div class="relative animated-gradient-bg rounded-[3rem] p-12 text-center overflow-hidden border border-white/10">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-[#22c55e]/20 rounded-full blur-[100px]"></div>
                
                <div class="relative z-10">
                    <div class="text-6xl md:text-7xl font-black text-white mb-4 tracking-tighter drop-shadow-2xl">
                        98%
                    </div>
                    <div class="text-xl font-bold text-[#22c55e] mb-4 uppercase tracking-[0.2em]">
                        <?php echo e(__('landing.automation.result.rate')); ?>

                    </div>
                    <p class="text-slate-400 text-lg md:text-xl font-medium max-w-2xl mx-auto leading-relaxed">
                        <?php echo e(__('landing.automation.result.text')); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views/landing/partials/automation.blade.php ENDPATH**/ ?>