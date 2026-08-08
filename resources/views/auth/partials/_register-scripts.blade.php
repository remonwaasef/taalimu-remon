<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('registrationForm', (config) => ({
        selectedPlan: config.selectedPlan,
        billingCycle: config.billingCycle || 'monthly',
        packages: config.packages,
        centerName: config.centerName,
        subdomain: config.subdomain,
        manuallyEditedSubdomain: config.manuallyEditedSubdomain,
        name: '{{ old('name', request('name')) }}',
        email: '{{ old('email', request('email')) }}',
        phone: '{{ old('phone', request('phone')) }}',
        currentStep: {{ $errors->hasAny(['name', 'email', 'phone', 'password']) ? 2 : 1 }},
        showPassword: false,
        password: '',
        password_confirmation: '',
        couponCode: '',
        showCouponInput: false,
        couponStatus: 'none',
        couponMessage: '',
        discountValue: 0,
        discountType: 'percentage',
        discountText: '',
        isApplyingCoupon: false,
        selectedCurrency: config.selectedCurrency || 'EGP',
        accountType: config.accountType || 'center',
        showPlanModal: false,
        formSubmitted: false,
        phoneVerified: false,
        countryCode: '{{ old("country_code", app()->getLocale() === "fr" ? "33" : "20") }}',
        otpSent: false,
        otpCode: '',
        otpStatus: 'idle',
        otpMessage: '',
        otpCountdown: 0,
        otpTimer: null,
        isSendingOtp: false,
        isVerifyingOtp: false,

        async init() {
            // Default select first plan if requested plan is invalid or missing
            if (!this.packages.find || !this.packages.find(p => p.slug === this.selectedPlan)) {
                this.selectedPlan = (this.packages && this.packages.length > 0) ? this.packages[0].slug : '';
            }

            // Smart IP Auto-Detection for Currency and Country Code
            if (!{{ Js::from(session()->has('suggested_currency') || request()->has('currency')) }}) {
                try {
                    const res = await fetch('https://ipapi.co/json/');
                    if(res.ok) {
                        const data = await res.json();
                        const country = data.country_code;
                        if (country === 'EG') this.selectedCurrency = 'EGP';
                        else if (country === 'SA') this.selectedCurrency = 'SAR';
                        else if (country === 'AE') this.selectedCurrency = 'AED';
                        else if (['FR', 'DE', 'IT', 'ES', 'NL', 'BE', 'AT', 'GR', 'PT', 'FI', 'IE'].includes(country)) this.selectedCurrency = 'EUR';
                        else this.selectedCurrency = 'USD';
                        
                        // Auto-set phone code if it exists in the list
                        if (data.country_calling_code) {
                            let phoneCode = data.country_calling_code.replace('+', '');
                            this.countryCode = phoneCode;
                        }
                    }
                } catch(e) {
                    console.warn('IP detection failed, using defaults.');
                }
            }


        },

        get currentPlan() {
            const plan = this.packages.find(p => p.slug === this.selectedPlan);
            if (!plan) return {name: 'Plan not found', price: '0', price_raw: 0, currency: '$', yearly_price_raw: 0};
            return plan;
        },

        getPriceData(pkg) {
             if(!pkg) return { amount: 0, currency: '$', term: 0, yearly: 0, old: 0, discount_label: '' };
             
             let prices = pkg.regional_prices || {};
             // Default from PHP data
             let data = {
                 amount: parseFloat(pkg.price_raw),
                 term: parseFloat(pkg.term_price_raw || (pkg.price_raw * 4)),
                 yearly: parseFloat(pkg.yearly_price_raw),
                 currency: pkg.currency || '$',
                 old: parseFloat(pkg.old_price_raw || 0),
                 discount_label: pkg.discount_label
             };

             const currencyToRegionKey = {
                 'EGP': 'EG',
                 'EUR': 'FR',
                 'USD': 'default',
                 'SAR': 'SA',
                 'AED': 'AE'
             };
             const regionKey = currencyToRegionKey[this.selectedCurrency] || 'default';

             if (prices[regionKey]) {
                 let r = prices[regionKey];
                 data.currency = r.currency || data.currency;
                 data.amount = parseFloat(r.amount || data.amount);
                 data.term = parseFloat(r.term_price || (data.amount * 4));
                 data.yearly = parseFloat(r.yearly_price || (data.amount * 10));
                 data.old = parseFloat(r.old_price || 0);
                 data.discount_label = r.discount_label || data.discount_label;
             } else if (prices['default']) {
                 let r = prices['default'];
                 data.currency = r.currency || data.currency;
                 data.amount = parseFloat(r.amount || data.amount);
                 data.yearly = parseFloat(r.yearly_price || (data.amount * 10));
                 data.term = parseFloat(r.term_price || (data.amount * 4));
                 data.old = parseFloat(r.old_price || 0);
                 data.discount_label = r.discount_label || data.discount_label;
             }
             return data;
        },

        get currentPriceData() {
             return this.getPriceData(this.currentPlan);
        },

        get activePriceRaw() {
            const plan = this.currentPlan;
            const priceData = this.getPriceData(plan);
            if (this.billingCycle === 'yearly') return (priceData.yearly || 0);
            if (this.billingCycle === 'term') return (priceData.term || 0);
            return (priceData.amount || 0);
        },
        
        get activePriceValue() {
             return this.activePriceRaw.toLocaleString();
        },

        generateSlug(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        },

        cleanSlug(text) {
            return text.toString().toLowerCase().replace(/[^a-z0-9\-]/g, '');
        },

        get isPasswordMatch() {
            return this.password === this.password_confirmation && this.password.length > 0;
        },

        get passwordCriteria() {
            return {
                length: this.password.length >= 8,
                upper: /[A-Z]/.test(this.password),
                lower: /[a-z]/.test(this.password),
                number: /[0-9]/.test(this.password),
                symbol: /[!@#$%^&*(),.?{}:|<>]/.test(this.password)
            }
        },

        async validateCoupon() {
            if (!this.couponCode) return;
            this.isApplyingCoupon = true;
            this.couponStatus = 'loading';
            try {
                const response = await fetch(`/api/coupons/validate?code=${this.couponCode}&plan=${this.selectedPlan}`);
                const data = await response.json();
                if (data.valid) {
                    this.couponStatus = 'valid';
                    this.couponMessage = data.message;
                    this.discountText = data.discount_text;
                    this.discountType = data.type;
                    this.discountValue = data.value;
                } else {
                    this.couponStatus = 'invalid';
                    this.couponMessage = data.message;
                    this.discountValue = 0;
                }
            } catch (e) {
                this.couponStatus = 'invalid';
                this.couponMessage = '{{ __('auth.coupon_error') }}';
            } finally {
                this.isApplyingCoupon = false;
            }
        },

        subdomainStatus: 'idle', // idle, loading, valid, invalid
        subdomainMessage: '',
        
        async checkSubdomain() {
            if (!this.subdomain) {
                this.subdomainStatus = 'idle';
                this.subdomainMessage = '';
                return;
            }
            
            // Auto clean
            this.subdomain = this.cleanSlug(this.subdomain);
            
            this.subdomainStatus = 'loading';
            try {
                const response = await fetch(`/api/validate-subdomain?subdomain=${this.subdomain}`);
                const data = await response.json();
                this.subdomainStatus = data.available ? 'valid' : 'invalid';
                this.subdomainMessage = data.message;
            } catch (e) {
                this.subdomainStatus = 'idle';
            }
        },

        async sendPhoneOtp() {
            if (!this.phone || this.phone.length < 10) {
                this.otpMessage = {{ Js::from(app()->isLocale('ar') ? 'يرجى إدخال رقم هاتف صحيح' : 'Please enter a valid phone number') }};
                this.otpStatus = 'error';
                return;
            }
            this.isSendingOtp = true;
            this.otpStatus = 'sending';
            this.otpMessage = '';
            try {
                const response = await fetch('/api/phone/send-otp', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ phone: this.phone, country_code: this.countryCode })
                });
                const data = await response.json();
                if (data.success) {
                    this.otpSent = true;
                    this.otpStatus = 'sent';
                    this.otpMessage = data.message;
                    this.startOtpCountdown(120);
                } else {
                    this.otpStatus = 'error';
                    this.otpMessage = data.message;
                }
            } catch (e) {
                this.otpStatus = 'error';
                this.otpMessage = {{ Js::from(app()->isLocale('ar') ? 'حدث خطأ. حاول مرة أخرى.' : 'An error occurred. Please try again.') }};
            } finally {
                this.isSendingOtp = false;
            }
        },

        async verifyPhoneOtp() {
            if (!this.otpCode || this.otpCode.length !== 6) return;
            this.isVerifyingOtp = true;
            this.otpStatus = 'verifying';
            try {
                const response = await fetch('/api/phone/verify-otp', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ phone: this.phone, otp: this.otpCode })
                });
                const data = await response.json();
                if (data.success) {
                    this.phoneVerified = true;
                    this.otpStatus = 'verified';
                    this.otpMessage = data.message;
                    if (this.otpTimer) { clearInterval(this.otpTimer); this.otpTimer = null; }
                } else {
                    this.otpStatus = 'error';
                    this.otpMessage = data.message;
                }
            } catch (e) {
                this.otpStatus = 'error';
                this.otpMessage = {{ Js::from(app()->isLocale('ar') ? 'حدث خطأ. حاول مرة أخرى.' : 'An error occurred. Please try again.') }};
            } finally {
                this.isVerifyingOtp = false;
            }
        },

        startOtpCountdown(seconds) {
            this.otpCountdown = seconds;
            if (this.otpTimer) clearInterval(this.otpTimer);
            this.otpTimer = setInterval(() => {
                this.otpCountdown--;
                if (this.otpCountdown <= 0) {
                    clearInterval(this.otpTimer);
                    this.otpTimer = null;
                }
            }, 1000);
        },

        get otpFormattedCountdown() {
            const m = Math.floor(this.otpCountdown / 60);
            const s = this.otpCountdown % 60;
            return `${m}:${s.toString().padStart(2, '0')}`;
        },

        nextStep() {
            // New logic: Step 1 is Center Details
            if (this.currentStep === 1) {
                if (!this.centerName || !this.subdomain) {
                    alert('{{ app()->getLocale() == 'ar' ? 'يرجى إدخال اسم المركز والرابط' : 'Please enter center name and subdomain' }}');
                    return;
                }
                if (this.subdomainStatus === 'invalid') {
                    alert('{{ app()->getLocale() == 'ar' ? 'هذا الرابط مستخدم بالفعل' : 'This subdomain is already taken' }}');
                    return;
                }
                this.currentStep = 2;
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        
        prevStep() {
            this.currentStep = 1;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        get couponDiscountAmount() {
            if (this.couponStatus !== 'valid') return 0;
            const price = this.activePriceRaw || 0;
            if (this.discountType === 'percentage') {
                return (price * (this.discountValue / 100));
            }
            return Math.min(this.discountValue, price);
        },

        get finalPrice() {
            const price = this.activePriceRaw || 0;
            return Math.max(0, price - this.couponDiscountAmount);
        }
    }))
})
</script>
