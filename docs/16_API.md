# 16_API - REST API Endpoints & Webhooks

### Key Endpoints
1. `POST /api/phone/send-otp`: Pre-registration phone OTP dispatch (`throttle:10,5`).
2. `POST /api/phone/verify-otp`: Verification of OTP code (`throttle:20,5`).
3. `GET /api/validate-subdomain`: Check subdomain availability.
4. `GET /api/coupons/validate`: Validate discount coupon (`throttle:coupons`).
5. `POST /webhooks/paypal` & `POST /webhooks/paymob`: Async payment webhook listeners.
