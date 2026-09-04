# TAALIMU SECURITY AUDIT

## 1. AUTHENTICATION SECURITY

### 1.1 Login Security

| Check | Status | Evidence | Severity |
|-------|--------|----------|----------|
| Rate limiting on login | ✅ PASS | UnifiedAuthController.php:32-44 — IP+email throttle | - |
| Password hashing | ✅ PASS | User.php:119 — `password => hashed` cast | - |
| Failed login tracking | ✅ PASS | RateLimiter::hit() on failure | - |
| Session fixation prevention | ✅ PASS | `$request->session()->invalidate()` + `regenerateToken()` | - |
| Session timeout | ✅ PASS | config/session.php: SESSION_LIFETIME=120 | - |
| SameSite cookie | ✅ PASS | AppServiceProvider.php:63-77 | - |
| Secure cookie flag | ⚠️ WARNING | .env: SESSION_SECURE_COOKIE=false (dev only, must be true in prod) | P2 |

### 1.2 Password Reset Security

| Check | Status | Evidence | Severity |
|-------|--------|----------|----------|
| Token expiry | ✅ PASS | auth.php:103 — 15 minutes | - |
| Throttle on reset | ✅ PASS | auth.php:104 — 60 seconds | - |
| Tenant-scoped tokens | ✅ PASS | Migration 2026_08_17 — password_reset_tokens scoped by tenant_id | - |
| No tenant enumeration | ✅ PASS | ForgotPasswordController.php:33 — Generic response | - |
| Hashed tokens in DB | ✅ PASS | Uses Str::random(60), hashed via Hash::make | - |

### 1.3 Two-Factor Authentication

| Check | Status | Evidence | Severity |
|-------|--------|----------|----------|
| TOTP implementation | ✅ PASS | TwoFactorController — Google2FA | - |
| Setup/Verify flow | ✅ PASS | /2fa/setup → /2fa/verify → /2fa/enable | - |
| Throttle on verify | ✅ PASS | throttle:5,1 | - |
| google2fa_bypass column | ⚠️ WARNING | User.php:98 — Must not be settable by users | P2 |
| Recovery codes | ❌ MISSING | No recovery code mechanism | P1 |

---

## 2. AUTHORIZATION SECURITY

### 2.1 RBAC (Spatie Permission)

| Check | Status | Evidence | Severity |
|-------|--------|----------|----------|
| Roles defined | ✅ PASS | User::RESERVED_ROLE_NAMES — 10 roles | - |
| Permissions granular | ✅ PASS | Route middleware: `can:view students`, `can:create courses`, etc. | - |
| Team scoping | ✅ PASS | IdentifyTenant.php:104 — setPermissionsTeamId($tenant->id) | - |
| Reserved role names | ✅ PASS | User.php:24-27 — prevents escalation | - |
| Admin panel isolation | ✅ PASS | CheckAdminRole.php:29-38 — requires global tenant_id=null | - |

### 2.2 Policy Checks

| Model | Policy | Checks | Status |
|-------|--------|--------|--------|
| Student | StudentPolicy | tenant_id === user->tenant_id | ✅ PASS |
| Course | CoursePolicy | tenant_id === user->tenant_id | ✅ PASS |
| Sale | SalePolicy | tenant_id === user->tenant_id | ✅ PASS |
| Instructor | InstructorPolicy | tenant_id === user->tenant_id | ✅ PASS |
| Schedule | SchedulePolicy | tenant_id === user->tenant_id | ✅ PASS |
| Attendance | AttendancePolicy | tenant_id === user->tenant_id | ✅ PASS |
| Ticket | TicketPolicy | tenant_id === user->tenant_id | ✅ PASS |
| Branch | BranchPolicy | tenant_id === user->tenant_id | ✅ PASS |
| Classroom | ClassroomPolicy | tenant_id === user->tenant_id | ✅ PASS |
| Asset | AssetPolicy | tenant_id === user->tenant_id | ✅ PASS |
| Expense | ExpensePolicy | tenant_id === user->tenant_id | ✅ PASS |
| Enrollment | EnrollmentPolicy | tenant_id === user->tenant_id | ✅ PASS |
| Quiz | QuizPolicy | tenant_id === user->tenant_id | ✅ PASS |
| Certificate | CertificatePolicy | tenant_id === user->tenant_id | ✅ PASS |
| OnlineClass | OnlineClassPolicy | tenant_id === user->tenant_id | ✅ PASS |
| Booking | BookingPolicy | tenant_id === user->tenant_id | ✅ PASS |
| Role | RolePolicy | Spatie teams scoping | ✅ PASS |

---

## 3. INPUT VALIDATION

### 3.1 Form Request Validation

| Area | Status | Evidence |
|------|--------|----------|
| Student CRUD | ✅ PASS | StoreStudentRequest, UpdateStudentRequest |
| Course CRUD | ✅ PASS | StoreCourseRequest, UpdateCourseRequest |
| Sale/Payment | ✅ PASS | Validation in controllers |
| Registration | ✅ PASS | RegistrationController validation |
| Login | ⚠️ PARTIAL | UnifiedAuthController validates but no dedicated FormRequest |

### 3.2 WAF (Web Application Firewall)

| Check | Status | Evidence | Severity |
|-------|--------|----------|----------|
| SQL injection patterns | ✅ PASS | BasicWAF.php:17-31 — Regex patterns | - |
| XSS patterns | ✅ PASS | BasicWAF.php — `<script>` detection | - |
| Path traversal | ✅ PASS | BasicWAF.php — `../` detection | - |
| **Coverage** | ⚠️ PARTIAL | Basic regex only, not comprehensive | P2 |
| **False positives** | ⚠️ RISK | May block legitimate requests | P2 |

### 3.3 Content Security Policy

| Check | Status | Evidence |
|-------|--------|----------|
| CSP headers | ✅ PASS | ContentSecurityPolicy.php middleware |
| Script-src | ✅ PASS | Restricted sources |
| Style-src | ✅ PASS | Restricted sources |
| Image-src | ✅ PASS | Data/https only |

---

## 4. CSRF PROTECTION

| Check | Status | Evidence | Severity |
|-------|--------|----------|----------|
| CSRF token on forms | ✅ PASS | `@csrf` in all Blade forms | - |
| AJAX CSRF | ✅ PASS | Meta tag + axios/fetch headers | - |
| Webhook CSRF bypass | ✅ PASS | WebhookCsrfTest.php exists | - |
| Double-submit prevention | ✅ PASS | Session-based CSRF | - |

---

## 5. RATE LIMITING

| Endpoint | Limit | Window | Evidence |
|----------|-------|--------|----------|
| Login | 5 attempts | 1hr | UnifiedAuthController.php:36 |
| Registration | configurable | - | throttle:registration |
| Password reset | 6 | 1min | throttle:6,1 |
| Phone OTP send | 10 | 5min | throttle:10,5 |
| Phone OTP verify | 20 | 5min | throttle:20,5 |
| QR Attendance | configurable | - | throttle:scanner |
| AI Assistant | configurable | - | throttle:ai |
| Bug Report | 5 | 10min | throttle:5,10 |
| Student Search | 60 | 1min | throttle:60,1 |
| Coupon Validate | configurable | - | throttle:coupons |
| Global | configurable | - | throttle:global |

---

## 6. FILE UPLOAD SECURITY

| Check | Status | Evidence | Severity |
|-------|--------|----------|----------|
| Extension validation | ✅ PASS | HandlesFileUploads trait | - |
| MIME type validation | ✅ PASS | Validates MIME | - |
| File size limits | ✅ PASS | Configurable per upload type | - |
| Path traversal prevention | ⚠️ PARTIAL | Needs verification | P2 |
| Storage location | ⚠️ NOTE | Files stored in storage/app (not tenant-scoped path) | P2 |
| Cross-tenant file access | ❌ NEEDS TEST | No automated test | P1 |

---

## 7. PAYMENT SECURITY

### 7.1 Paymob

| Check | Status | Evidence | Severity |
|-------|--------|----------|----------|
| HMAC verification | ✅ PASS | PaymobWebhookController.php — hash_hmac verification | - |
| Amount verification | ✅ PASS | Line 91: received_cents == expected_cents | - |
| Idempotency | ✅ PASS | Unique index on payments.reference_number | - |
| Row locking | ✅ PASS | FinanceService.php:242 — lockForUpdate() | - |

### 7.2 PayPal

| Check | Status | Evidence | Severity |
|-------|--------|----------|----------|
| Webhook signature | ✅ PASS | PayPalWebhookController.php | - |
| Sandbox/Live mode | ✅ PASS | PAYPAL_MODE config | - |

### 7.3 General Payment

| Check | Status | Evidence | Severity |
|-------|--------|----------|----------|
| Double payment prevention | ✅ PASS | Unique reference_number + lockForUpdate | - |
| Race condition protection | ✅ PASS | DB::transaction + lockForUpdate | - |
| Demo mode restriction | ✅ PASS | RegistrationController.php:41-43 | - |
| Amount overflow | ✅ PASS | CHECK constraint: paid_amount >= 0 | - |

---

## 8. API SECURITY

| Check | Status | Evidence | Severity |
|-------|--------|----------|----------|
| Sanctum tokens | ✅ PASS | Laravel Sanctum for API auth | - |
| Token scoping | ✅ PASS | Tokens tied to tenant_id | - |
| Cross-tenant block | ✅ PASS | ApiTenantMiddleware:58-64 | - |
| CORS configuration | ✅ PASS | config/cors.php | - |
| API rate limiting | ✅ PASS | throttle:global middleware | - |

---

## 9. SESSION SECURITY

| Check | Status | Evidence | Severity |
|-------|--------|----------|----------|
| Session driver | ✅ PASS | file (dev) / redis (prod) | - |
| Session lifetime | ✅ PASS | 120 minutes | - |
| Session encryption | ⚠️ NOTE | SESSION_ENCRYPT=false (dev) | P2 |
| Session cookie name | ✅ PASS | Separate cookie for admin guard | - |
| Session fixation | ✅ PASS | invalidate() + regenerateToken() | - |

---

## 10. SENSITIVE DATA EXPOSURE

| Check | Status | Evidence | Severity |
|-------|--------|----------|----------|
| APP_DEBUG in production | ⚠️ WARNING | .env: APP_DEBUG=true (must be false) | P1 |
| APP_KEY in repo | ❌ CRITICAL | .env file with APP_KEY committed | P0 |
| Google OAuth secret | ❌ CRITICAL | .env: GOOGLE_CLIENT_SECRET committed | P0 |
| Database credentials | ⚠️ WARNING | .env: DB_PASSWORD empty (dev) | P1 |
| Stripe keys empty | ✅ OK | .env: STRIPE_KEY/SECRET empty | - |
| PayPal keys empty | ✅ OK | .env: PAYPAL keys empty | - |
| Paymob keys empty | ✅ OK | .env: PAYMOB keys empty | - |
| Telegram bot token | ✅ OK | .env: TELEGRAM_BOT_TOKEN empty | - |
| Settings encryption | ✅ PASS | EncryptedSettings cast on tenant.settings | - |

---

## 11. DEPENDENCY VULNERABILITIES

| Package | Version | Known Vulnerabilities |
|---------|---------|----------------------|
| laravel/framework | ^12.0 | Check `composer audit` |
| laravel/sanctum | ^4.2 | Check `composer audit` |
| spatie/laravel-permission | ^6.23 | Check `composer audit` |
| nwidart/laravel-modules | ^12.0 | Check `composer audit` |
| predis/predis | 3.5 | Check `composer audit` |

---

## 12. INFORMATION LEAKAGE

| Check | Status | Evidence | Severity |
|-------|--------|----------|----------|
| Error messages in production | ⚠️ PARTIAL | APP_DEBUG controls stack traces | P1 |
| Debug routes | ✅ PASS | "Debug routes removed for security" (web.php:485) | - |
| SQL errors exposed | ⚠️ PARTIAL | APP_DEBUG=true shows SQL errors | P1 |
| Version disclosure | ⚠️ PARTIAL | X-Powered-By header | P2 |
| Exception handler | ⚠️ PARTIAL | No custom ExceptionHandler registered | P1 |

---

## 13. SIGNED URLS

| Check | Status | Evidence |
|-------|--------|----------|
| Magic login URLs | ✅ PASS | `middleware('signed')` on magic-login routes |
| Online payment URLs | ✅ PASS | `middleware('signed')` on pay routes |
| Student registration | ✅ PASS | Token-based, throttled |

---

## 14. REDIS SECURITY

| Check | Status | Evidence |
|-------|--------|----------|
| Redis password | ⚠️ NOTE | REDIS_PASSWORD=null (dev) |
| Redis persistent | ✅ OK | REDIS_PERSISTENT=false |
| Cache prefix | ✅ PASS | taalimu_cache_ prefix |

---

## 15. SECURITY FINDINGS SUMMARY

| # | Finding | Severity | Location | Recommendation |
|---|---------|----------|----------|----------------|
| SEC-01 | APP_KEY committed in .env | **P0 CRITICAL** | .env:13 | Rotate key immediately, add to .gitignore |
| SEC-02 | Google OAuth secret committed | **P0 CRITICAL** | .env:162 | Rotate secret immediately |
| SEC-03 | APP_DEBUG=true in .env | P1 | .env:14 | Must be false in production |
| SEC-04 | No custom ExceptionHandler | P1 | Missing | Add handler to prevent stack traces |
| SEC-05 | google2fa_bypass column exists | P2 | User.php:98 | Ensure not settable by users |
| SEC-06 | Basic WAF patterns | P2 | BasicWAF.php | Consider ModSecurity or WAF service |
| SEC-07 | File storage not tenant-scoped | P2 | Filesystem config | Consider tenant-prefixed paths |
| SEC-08 | No recovery codes for 2FA | P1 | TwoFactorController | Add recovery code mechanism |
| SEC-09 | SESSION_SECURE_COOKIE=false | P2 | .env:59 | Must be true in production |
| SEC-10 | SESSION_ENCRYPT=false | P2 | .env:54 | Consider enabling in production |

---

## 16. CONCLUSION

**Security: GOOD (with critical findings)**

| Category | Score | Notes |
|----------|-------|-------|
| Authentication | 9/10 | Strong login, 2FA, password reset |
| Authorization | 9/10 | RBAC + policies + team scoping |
| Input Validation | 7/10 | Form requests exist but not universal |
| CSRF | 9/10 | Comprehensive coverage |
| Rate Limiting | 9/10 | Well-configured per endpoint |
| File Upload | 7/10 | Validation exists, storage isolation needed |
| Payment | 9/10 | HMAC, idempotency, row locking |
| API | 8/10 | Sanctum + tenant isolation |
| Secrets Management | 4/10 | Critical secrets in .env committed to repo |

**P0 Issues**: 2 (APP_KEY and OAuth secret in repo — immediate rotation required)

---

*Generated during Pass 1 Discovery & Audit*
*Last Updated: 2026-09-02*