<!-- Cookie Consent Banner -->
<div id="cookieConsentBanner" class="fixed bottom-0 left-0 right-0 bg-card border-t-2 border-primary shadow-2xl z-50" style="display: none;">
    <div class="container mx-auto px-4 py-5">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex-1 text-center md:text-left">
                <h3 class="font-bold text-foreground mb-2">{{ __('gdpr.banner.title') }}</h3>
                <p class="text-sm text-muted-foreground">
                    {{ __('gdpr.banner.message') }}
                    <a href="{{ route('cookies') }}" class="text-primary hover:underline">{{ __('gdpr.banner.learn_more') }}</a>
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0">
                <button 
                    onclick="CookieConsent.openSettings()"
                    class="px-4 py-2 border border-border rounded-lg text-sm font-medium hover:bg-muted transition-colors whitespace-nowrap"
                >
                    {{ __('gdpr.banner.settings') }}
                </button>
                <button 
                    onclick="CookieConsent.acceptAll()"
                    class="px-5 py-2 bg-primary text-primary-foreground rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors whitespace-nowrap"
                >
                    {{ __('gdpr.banner.accept_all') }}
                </button>
                <button 
                    onclick="CookieConsent.acceptEssential()"
                    class="px-4 py-2 border border-border rounded-lg text-sm font-medium hover:bg-muted transition-colors whitespace-nowrap"
                >
                    {{ __('gdpr.banner.essential_only') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Settings Modal -->
<div id="cookieSettingsModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50" style="display: none;">
    <div class="bg-card rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-border">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-foreground">{{ __('gdpr.settings.title') }}</h2>
                <button onclick="CookieConsent.closeSettings()" class="text-muted-foreground hover:text-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
        </div>
        
        <div class="p-6 space-y-6">
            <!-- Essential Cookies -->
            <div class="flex items-start gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="font-semibold text-foreground">{{ __('gdpr.settings.essential.title') }}</h3>
                        <span class="text-xs bg-muted px-2 py-1 rounded">{{ __('gdpr.settings.always_active') }}</span>
                    </div>
                    <p class="text-sm text-muted-foreground">{{ __('gdpr.settings.essential.description') }}</p>
                </div>
                <div class="w-12 h-6 bg-primary rounded-full flex items-center px-1">
                    <div class="w-4 h-4 bg-white rounded-full ml-auto"></div>
                </div>
            </div>

            <!-- Analytics Cookies -->
            <div class="flex items-start gap-4">
                <div class="flex-1">
                    <h3 class="font-semibold text-foreground mb-2">{{ __('gdpr.settings.analytics.title') }}</h3>
                    <p class="text-sm text-muted-foreground">{{ __('gdpr.settings.analytics.description') }}</p>
                </div>
                <button 
                    id="analyticsToggle"
                    onclick="CookieConsent.togglePreference('analytics')"
                    class="w-12 h-6 rounded-full flex items-center px-1 transition-colors bg-muted"
                >
                    <div class="w-4 h-4 bg-white rounded-full transition-all"></div>
                </button>
            </div>

            <!-- Marketing Cookies -->
            <div class="flex items-start gap-4">
                <div class="flex-1">
                    <h3 class="font-semibold text-foreground mb-2">{{ __('gdpr.settings.marketing.title') }}</h3>
                    <p class="text-sm text-muted-foreground">{{ __('gdpr.settings.marketing.description') }}</p>
                </div>
                <button 
                    id="marketingToggle"
                    onclick="CookieConsent.togglePreference('marketing')"
                    class="w-12 h-6 rounded-full flex items-center px-1 transition-colors bg-muted"
                >
                    <div class="w-4 h-4 bg-white rounded-full transition-all"></div>
                </button>
            </div>
        </div>

        <div class="p-6 border-t border-border flex gap-3">
            <button 
                onclick="CookieConsent.savePreferences()"
                class="flex-1 px-6 py-3 bg-primary text-primary-foreground rounded-lg font-medium hover:bg-primary/90 transition-colors"
            >
                {{ __('gdpr.settings.save') }}
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
        document.getElementById('cookieConsentBanner').style.display = 'block';
    },

    hideBanner() {
        document.getElementById('cookieConsentBanner').style.display = 'none';
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
        const analyticsCircle = analyticsToggle.querySelector('div');
        if (this.preferences.analytics) {
            analyticsToggle.classList.remove('bg-muted');
            analyticsToggle.classList.add('bg-primary');
            analyticsCircle.classList.remove('mr-auto');
            analyticsCircle.classList.add('ml-auto');
        } else {
            analyticsToggle.classList.remove('bg-primary');
            analyticsToggle.classList.add('bg-muted');
            analyticsCircle.classList.remove('ml-auto');
            analyticsCircle.classList.add('mr-auto');
        }

        // Update Marketing Toggle
        const marketingToggle = document.getElementById('marketingToggle');
        const marketingCircle = marketingToggle.querySelector('div');
        if (this.preferences.marketing) {
            marketingToggle.classList.remove('bg-muted');
            marketingToggle.classList.add('bg-primary');
            marketingCircle.classList.remove('mr-auto');
            marketingCircle.classList.add('ml-auto');
        } else {
            marketingToggle.classList.remove('bg-primary');
            marketingToggle.classList.add('bg-muted');
            marketingCircle.classList.remove('ml-auto');
            marketingCircle.classList.add('mr-auto');
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
