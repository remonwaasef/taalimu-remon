@props(['buttonClass' => ''])

<div x-data="pwaInstall()" x-cloak x-show="canInstall" class="inline-block" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
    <button 
        @click="installApp()"
        class="{{ $buttonClass ?: 'inline-flex items-center justify-center rounded-lg text-sm font-bold transition-all hover:scale-105 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 h-10 px-5 py-2 whitespace-nowrap' }}"
        id="pwa-install-btn"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <rect width="14" height="20" x="5" y="2" rx="2" ry="2"/>
            <path d="M12 18h.01"/>
        </svg>
        <span>{{ __('pwa.install_title') }}</span>
    </button>
</div>

@once
@push('scripts')
<script>
    function pwaInstall() {
        return {
            deferredPrompt: null,
            canInstall: false,
            init() {
                console.log('PWA Install component initialized');
                
                window.addEventListener('beforeinstallprompt', (e) => {
                    console.log('beforeinstallprompt event fired');
                    // Prevent the mini-infobar from appearing on mobile
                    e.preventDefault();
                    // Stash the event so it can be triggered later.
                    this.deferredPrompt = e;
                    // Update UI notify the user they can install the PWA
                    this.canInstall = true;
                });

                window.addEventListener('appinstalled', () => {
                    console.log('PWA was installed successfully');
                    // Clear the deferredPrompt so it can be garbage collected
                    this.deferredPrompt = null;
                    this.canInstall = false;
                });

                // Check if already in standalone mode
                if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
                    console.log('App is already running in standalone mode');
                    this.canInstall = false;
                }
            },
            async installApp() {
                console.log('Install button clicked');
                if (!this.deferredPrompt) {
                    console.warn('No deferredPrompt available');
                    return;
                }
                
                // Show the install prompt
                this.deferredPrompt.prompt();
                
                // Wait for the user to respond to the prompt
                const { outcome } = await this.deferredPrompt.userChoice;
                console.log(`User response to the install prompt: ${outcome}`);
                
                // We've used the prompt, and can't use it again, throw it away
                if (outcome === 'accepted') {
                    this.deferredPrompt = null;
                    this.canInstall = false;
                }
            }
        }
    }
</script>
@endpush
@endonce
