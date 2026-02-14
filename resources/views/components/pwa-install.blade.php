@props(['buttonClass' => ''])

<div x-data="pwaInstall()" x-cloak x-show="canInstall" class="inline-block">
    <button 
        @click="installApp()"
        class="{{ $buttonClass ?: 'inline-flex items-center justify-center rounded-lg text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-accent text-accent-foreground shadow hover:bg-accent/90 h-9 px-4 py-2' }}"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/>
            <line x1="12" x2="12" y1="15" y2="3"/>
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
                window.addEventListener('beforeinstallprompt', (e) => {
                    // Prevent the mini-infobar from appearing on mobile
                    e.preventDefault();
                    // Stash the event so it can be triggered later.
                    this.deferredPrompt = e;
                    // Update UI notify the user they can install the PWA
                    this.canInstall = true;
                });

                window.addEventListener('appinstalled', () => {
                    // Clear the deferredPrompt so it can be garbage collected
                    this.deferredPrompt = null;
                    this.canInstall = false;
                    console.log('PWA was installed');
                });
            },
            async installApp() {
                if (!this.deferredPrompt) return;
                
                // Show the install prompt
                this.deferredPrompt.prompt();
                
                // Wait for the user to respond to the prompt
                const { outcome } = await this.deferredPrompt.userChoice;
                console.log(`User response to the install prompt: ${outcome}`);
                
                // We've used the prompt, and can't use it again, throw it away
                this.deferredPrompt = null;
                this.canInstall = false;
            }
        }
    }
</script>
@endpush
@endonce
