<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['buttonClass' => '']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['buttonClass' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div 
    x-data="pwaInstall()" 
    x-cloak 
    x-show="canInstall" 
    class="fixed bottom-12 <?php echo e(app()->getLocale() == 'ar' ? 'left-6' : 'right-6'); ?> z-[100]"
    x-transition:enter="transition ease-out duration-500" 
    x-transition:enter-start="opacity-0 <?php echo e(app()->getLocale() == 'ar' ? '-translate-x-full' : 'translate-x-full'); ?>" 
    x-transition:enter-end="opacity-100 translate-x-0"
>
    <!-- Desktop/Tablet Floating Tab -->
    <div class="hidden md:block">
        <button 
            @click="installApp()"
            class="flex items-center gap-3 py-3 px-5 gradient-hero text-white font-bold rounded-<?php echo e(app()->getLocale() == 'ar' ? 'r' : 'l'); ?>-3xl shadow-xl shadow-blue-500/20 hover:scale-105 active:scale-95 transition-all duration-300 group border-y border-<?php echo e(app()->getLocale() == 'ar' ? 'r' : 'l'); ?> border-white/20 backdrop-blur-sm"
        >
            <div class="p-2 bg-white/20 rounded-lg group-hover:bg-white/30 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="14" height="20" x="5" y="2" rx="2" ry="2"/>
                    <path d="M12 18h.01"/>
                    <path d="M12 14v-4"/>
                    <path d="m9 11 3 3 3-3"/>
                </svg>
            </div>
            <span class="text-sm tracking-wide"><?php echo e(__('pwa.install_title')); ?></span>
        </button>
    </div>

    <!-- Mobile Floating Action Button -->
    <div class="md:hidden p-4">
        <button 
            @click="installApp()"
            class="w-14 h-14 flex items-center justify-center gradient-hero text-white rounded-[1.25rem] shadow-xl shadow-blue-500/20 active:scale-90 transition-all border border-white/20"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect width="14" height="20" x="5" y="2" rx="2" ry="2"/>
                <path d="M12 14v-4"/>
                <path d="m9 11 3 3 3-3"/>
            </svg>
        </button>
    </div>
</div>

<?php if (! $__env->hasRenderedOnce('903fd0d2-8b9b-494a-8beb-b92a0699ba1a')): $__env->markAsRenderedOnce('903fd0d2-8b9b-494a-8beb-b92a0699ba1a'); ?>
<?php $__env->startPush('scripts'); ?>
<script>
    function pwaInstall() {
        return {
            deferredPrompt: null,
            canInstall: false,
            init() {
                // Check if prompt was already captured by global listener
                if (window.pwaDeferredPrompt) {
                    this.deferredPrompt = window.pwaDeferredPrompt;
                    this.canInstall = true;
                }

                // Listen for potential new prompts or late-firing events
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    this.deferredPrompt = e;
                    this.canInstall = true;
                });

                // Custom event dispatched by global layout listener
                window.addEventListener('pwa-prompt-available', () => {
                    if (window.pwaDeferredPrompt) {
                        this.deferredPrompt = window.pwaDeferredPrompt;
                        this.canInstall = true;
                    }
                });

                window.addEventListener('appinstalled', () => {
                    this.deferredPrompt = null;
                    window.pwaDeferredPrompt = null;
                    this.canInstall = false;
                });

                if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
                    this.canInstall = false;
                }
            },
            async installApp() {
                const prompt = this.deferredPrompt || window.pwaDeferredPrompt;
                if (!prompt) return;
                
                prompt.prompt();
                const { outcome } = await prompt.userChoice;
                if (outcome === 'accepted') {
                    this.deferredPrompt = null;
                    window.pwaDeferredPrompt = null;
                    this.canInstall = false;
                }
            }
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views/components/pwa-install.blade.php ENDPATH**/ ?>