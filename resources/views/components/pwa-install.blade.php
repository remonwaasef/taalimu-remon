@props(['buttonClass' => ''])

<div 
    x-data="pwaInstall()" 
    x-cloak 
    x-show="canInstall" 
    class="fixed bottom-24 {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} z-[100]"
    x-transition:enter="transition ease-out duration-500" 
    x-transition:enter-start="opacity-0 {{ app()->getLocale() == 'ar' ? '-translate-x-full' : 'translate-x-full' }}" 
    x-transition:enter-end="opacity-100 translate-x-0"
>
    <!-- Desktop/Tablet Floating Tab -->
    <div class="hidden md:block">
        <button 
            @click="installApp()"
            class="flex items-center gap-3 py-3 px-5 bg-gradient-to-r from-indigo-600 to-violet-700 text-white font-bold rounded-{{ app()->getLocale() == 'ar' ? 'r' : 'l' }}-2xl shadow-[0_10px_30px_rgba(79,70,229,0.4)] hover:scale-105 active:scale-95 transition-all duration-300 group border-y border-{{ app()->getLocale() == 'ar' ? 'r' : 'l' }} border-white/20 backdrop-blur-sm"
        >
            <div class="p-2 bg-white/20 rounded-lg group-hover:bg-white/30 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="14" height="20" x="5" y="2" rx="2" ry="2"/>
                    <path d="M12 18h.01"/>
                    <path d="M12 14v-4"/>
                    <path d="m9 11 3 3 3-3"/>
                </svg>
            </div>
            <span class="text-sm tracking-wide">{{ __('pwa.install_title') }}</span>
        </button>
    </div>

    <!-- Mobile Floating Action Button -->
    <div class="md:hidden p-4">
        <button 
            @click="installApp()"
            class="w-14 h-14 flex items-center justify-center bg-gradient-to-tr from-indigo-600 to-violet-600 text-white rounded-2xl shadow-2xl shadow-indigo-500/50 active:scale-90 transition-all border border-white/20"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect width="14" height="20" x="5" y="2" rx="2" ry="2"/>
                <path d="M12 14v-4"/>
                <path d="m9 11 3 3 3-3"/>
            </svg>
        </button>
    </div>
</div>

@once
@push('scripts')
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
@endpush
@endonce
