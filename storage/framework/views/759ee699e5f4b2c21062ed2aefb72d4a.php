<!-- Cookie Consent Banner -->
<div id="cookieConsentBanner" class="fixed bottom-6 start-6 end-6 md:start-auto md:end-6 md:max-w-md bg-card/95 backdrop-blur-md border border-border shadow-premium rounded-2xl z-[100] transition-all duration-500 transform translate-y-full opacity-0" style="display: none;">
    <div class="p-5 md:p-6">
        <div class="flex flex-col gap-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"/><path d="M8.5 8.5v.01"/><path d="M16 15.5v.01"/><path d="M12 12v.01"/><path d="M11 17v.01"/><path d="M7 14v.01"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-foreground text-lg mb-1"><?php echo e(__('gdpr.banner.title')); ?></h3>
                    <p class="text-sm text-muted-foreground leading-relaxed">
                        <?php echo e(__('gdpr.banner.message')); ?>

                        <a href="<?php echo e(route('cookies')); ?>" class="text-primary font-semibold hover:underline"><?php echo e(__('gdpr.banner.learn_more')); ?></a>
                    </p>
                </div>
            </div>
            
            <div class="flex flex-col gap-3">
                <button 
                    onclick="CookieConsent.acceptAll()"
                    class="w-full px-5 py-3 bg-secondary text-white rounded-xl text-sm font-bold hover:bg-secondary/90 transition-all shadow-sm active:scale-95 flex items-center justify-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    <?php echo e(__('gdpr.banner.accept_all')); ?>

                </button>
                <div class="grid grid-cols-2 gap-3">
                    <button 
                        onclick="CookieConsent.openSettings()"
                        class="px-3 py-2.5 border border-border rounded-xl text-xs font-semibold hover:bg-muted transition-all text-foreground whitespace-nowrap active:scale-95 flex items-center justify-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-1-1.72v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                        <?php echo e(__('gdpr.banner.settings')); ?>

                    </button>
                    <button 
                        onclick="CookieConsent.acceptEssential()"
                        class="px-3 py-2.5 border border-border rounded-xl text-xs font-semibold hover:bg-muted transition-all text-foreground whitespace-nowrap active:scale-95"
                    >
                        <?php echo e(__('gdpr.banner.essential_only')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Settings Modal -->
<div id="cookieSettingsModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-[110]" style="display: none;">
    <div class="bg-card rounded-3xl max-w-2xl w-full shadow-premium border border-border overflow-hidden animate-fade-in-up">
        <div class="p-6 md:p-8 border-b border-border flex items-center justify-between bg-muted/30">
            <div>
                <h2 class="text-2xl font-black text-foreground tracking-tight"><?php echo e(__('gdpr.settings.title')); ?></h2>
                <p class="text-sm text-muted-foreground mt-1"><?php echo e(__('gdpr.banner.message')); ?></p>
            </div>
            <button onclick="CookieConsent.closeSettings()" class="w-10 h-10 rounded-full bg-muted flex items-center justify-center text-muted-foreground hover:text-foreground transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        
        <div class="p-6 md:p-8 space-y-8">
            <!-- Essential Cookies -->
            <div class="flex items-start gap-6">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center flex-shrink-0 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="font-bold text-foreground text-lg"><?php echo e(__('gdpr.settings.essential.title')); ?></h3>
                        <span class="text-[10px] font-bold uppercase tracking-widest bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full"><?php echo e(__('gdpr.settings.always_active')); ?></span>
                    </div>
                    <p class="text-sm text-muted-foreground leading-relaxed"><?php echo e(__('gdpr.settings.essential.description')); ?></p>
                </div>
            </div>

            <div class="h-px bg-border/50"></div>

            <!-- Analytics Cookies -->
            <div class="flex items-start gap-6">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center flex-shrink-0 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-foreground text-lg mb-2"><?php echo e(__('gdpr.settings.analytics.title')); ?></h3>
                    <p class="text-sm text-muted-foreground leading-relaxed"><?php echo e(__('gdpr.settings.analytics.description')); ?></p>
                </div>
                <button 
                    id="analyticsToggle"
                    onclick="CookieConsent.togglePreference('analytics')"
                    class="w-14 h-7 rounded-full flex items-center px-1 transition-all duration-300 bg-muted relative"
                >
                    <div class="w-5 h-5 bg-white rounded-full shadow-sm transition-all duration-300 transform"></div>
                </button>
            </div>

            <div class="h-px bg-border/50"></div>

            <!-- Marketing Cookies -->
            <div class="flex items-start gap-6">
                <div class="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center flex-shrink-0 text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 1 1-7.6-11.7 8.38 8.38 0 0 1 3.8.9"/><path d="M11 3a13 13 0 0 0 9 9"/><path d="m15 5 4 4"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-foreground text-lg mb-2"><?php echo e(__('gdpr.settings.marketing.title')); ?></h3>
                    <p class="text-sm text-muted-foreground leading-relaxed"><?php echo e(__('gdpr.settings.marketing.description')); ?></p>
                </div>
                <button 
                    id="marketingToggle"
                    onclick="CookieConsent.togglePreference('marketing')"
                    class="w-14 h-7 rounded-full flex items-center px-1 transition-all duration-300 bg-muted relative"
                >
                    <div class="w-5 h-5 bg-white rounded-full shadow-sm transition-all duration-300 transform"></div>
                </button>
            </div>
        </div>

        <div class="p-6 md:p-8 bg-muted/30 border-t border-border">
            <button 
                onclick="CookieConsent.savePreferences()"
                class="w-full py-4 bg-primary text-primary-foreground rounded-2xl font-bold text-lg hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 active:scale-[0.98]"
            >
                <?php echo e(__('gdpr.settings.save')); ?>

            </button>
        </div>
    </div>
</div>

<script>
// Cookie Consent Manager - Pure JavaScript
const CookieConsent = {
    preferences: {
        essential: true,
        analytics: false,
        marketing: false
    },

    init() {
        const consent = localStorage.getItem('cookie_consent');
        if (!consent) {
            this.showBanner();
        } else {
            this.preferences = JSON.parse(consent);
            this.loadScripts();
        }
        this.updateTogglesUI();
    },

    showBanner() {
        const banner = document.getElementById('cookieConsentBanner');
        banner.style.display = 'block';
        setTimeout(() => {
            banner.classList.remove('translate-y-full', 'opacity-0');
            banner.classList.add('translate-y-0', 'opacity-100');
        }, 100);
        document.body.classList.add('cookie-banner-active');
    },

    hideBanner() {
        const banner = document.getElementById('cookieConsentBanner');
        banner.classList.remove('translate-y-0', 'opacity-100');
        banner.classList.add('translate-y-full', 'opacity-0');
        setTimeout(() => {
            banner.style.display = 'none';
        }, 500);
        document.body.classList.remove('cookie-banner-active');
    },

    openSettings() {
        this.hideBanner();
        document.getElementById('cookieSettingsModal').style.display = 'flex';
        this.updateTogglesUI();
    },

    closeSettings() {
        document.getElementById('cookieSettingsModal').style.display = 'none';
    },

    togglePreference(type) {
        this.preferences[type] = !this.preferences[type];
        this.updateTogglesUI();
    },

    updateTogglesUI() {
        // Update Analytics Toggle
        const analyticsToggle = document.getElementById('analyticsToggle');
        const analyticsCircle = analyticsToggle?.querySelector('div');
        if (analyticsToggle && analyticsCircle) {
            if (this.preferences.analytics) {
                analyticsToggle.classList.replace('bg-muted', 'bg-primary');
                analyticsCircle.style.transform = 'translateX(28px)';
            } else {
                analyticsToggle.classList.replace('bg-primary', 'bg-muted');
                analyticsCircle.style.transform = 'translateX(0)';
            }
        }

        // Update Marketing Toggle
        const marketingToggle = document.getElementById('marketingToggle');
        const marketingCircle = marketingToggle?.querySelector('div');
        if (marketingToggle && marketingCircle) {
            if (this.preferences.marketing) {
                marketingToggle.classList.replace('bg-muted', 'bg-primary');
                marketingCircle.style.transform = 'translateX(28px)';
            } else {
                marketingToggle.classList.replace('bg-primary', 'bg-muted');
                marketingCircle.style.transform = 'translateX(0)';
            }
        }
    },

    acceptAll() {
        this.preferences = {
            essential: true,
            analytics: true,
            marketing: true
        };
        this.savePreferences();
    },

    acceptEssential() {
        this.preferences = {
            essential: true,
            analytics: false,
            marketing: false
        };
        this.savePreferences();
    },

    savePreferences() {
        localStorage.setItem('cookie_consent', JSON.stringify(this.preferences));
        localStorage.setItem('cookie_consent_date', new Date().toISOString());
        this.hideBanner();
        this.closeSettings();
        this.loadScripts();
        
        // Optional: Send to backend
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            fetch('/api/cookie-consent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content
                },
                body: JSON.stringify(this.preferences)
            }).catch(e => console.log('Consent logging failed:', e));
        }
    },

    loadScripts() {
        // Load analytics if consented
        if (this.preferences.analytics && !window.analyticsLoaded) {
            // Example: Google Analytics
            // window.dataLayer = window.dataLayer || [];
            // Add your analytics code here
            console.log('Analytics scripts loaded');
            window.analyticsLoaded = true;
        }
        
        // Load marketing if consented
        if (this.preferences.marketing && !window.marketingLoaded) {
            // Add marketing scripts here
            console.log('Marketing scripts loaded');
            window.marketingLoaded = true;
        }
    }
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    CookieConsent.init();
});

// Global function for footer button
window.openCookieSettings = function() {
    CookieConsent.openSettings();
};

// Close modal when clicking outside
document.getElementById('cookieSettingsModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        CookieConsent.closeSettings();
    }
});
</script>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views\components\cookie-consent.blade.php ENDPATH**/ ?>