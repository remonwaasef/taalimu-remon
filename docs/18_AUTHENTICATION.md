# 18_AUTHENTICATION - Authentication Sub-system

- **Unified Login (`UnifiedAuthController`)**: Accepts Email/Phone + Password, inspects user role and `tenant_id`, and redirects to corresponding dashboard.
- **Social Auth (`SocialAuthController`)**: Google OAuth integration via `laravel/socialite`.
- **Phone OTP Verification (`PhoneVerificationController`)**: Pre-registration phone verification.
- **2FA TOTP (`TwoFactorController`)**: Google Authenticator 2FA (`pragmarx/google2fa-laravel`).
- **Sanctum Tokens**: Bearer tokens (`auth:sanctum`) for mobile applications.
