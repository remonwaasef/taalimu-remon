# TAALIMU BUSINESS RULES

## 1. AUTHENTICATION RULES

### Rule A1: Login Flow
| Source | Rule |
|--------|------|
| **Backend** (UnifiedAuthController.php:24-132) | Email+password validated. Rate limit: 5 attempts (100 in dev). Super_admin redirected to admin panel. Tenant user gets SSO token → POST to tenant login/sso |
| **Frontend** (auth/unified-login.blade.php) | Single login form on central domain. No tenant selector visible |
| **DB** (users table) | Users have tenant_id (null for super_admin), role, password (hashed) |

**⚠ Inconsistency Found**: `is_relaxed_throttle_env()` relaxes rate limit to 100 in dev — must never be active in production.

---

### Rule A2: Password Reset
| Source | Rule |
|--------|------|
| **Backend** (ForgotPasswordController.php:33) | Generic response: "If an account exists, a reset link was sent" — no tenant enumeration |
| **Backend** (ResetPasswordController) | Tokens scoped by tenant_id (migration 2026_08_17) |
| **Config** (auth.php:99-105) | Token expiry: 15 minutes. Throttle: 60 seconds |

---

### Rule A3: Two-Factor Authentication
| Source | Rule |
|--------|------|
| **Middleware** (TwoFactorMiddleware) | Enforced on protected routes after onboarding |
| **Controller** (TwoFactorController) | Setup → Confirm → Verify → Disable flow |
| **Middleware** (TwoFactorMiddleware.php:google2fa_bypass) | Users with `google2fa_bypass=1` skip 2FA (for testing) |

**⚠ Risk**: `google2fa_bypass` column exists on users table — ensure it's never settable by users.

---

## 2. TENANT ISOLATION RULES

### Rule T1: Global Scope
| Source | Rule |
|--------|------|
| **Trait** (BelongsToTenant.php:21-30) | `bootBelongsToTenant()` adds `TenantScope` global scope and auto-sets `tenant_id` on creating |
| **Scope** (TenantScope.php:17-33) | Filters by `app('tenant')->id`. Exception: User model allows `tenant_id = null` (global admins) |

### Rule T2: Middleware Identification
| Source | Rule |
|--------|------|
| **Middleware** (IdentifyTenant.php:88-90) | Inactive tenants → 403 "Center is currently inactive" |
| **Middleware** (IdentifyTenant.php:101-107) | Sets `app('tenant')`, Spatie team ID, view share, URL defaults, timezone, log file |
| **Middleware** (IdentifyTenant.php:82-84) | Unknown subdomain → 404 "Center not found" |

### Rule T3: Authorization Check
| Source | Rule |
|--------|------|
| **Spatie Teams** (IdentifyTenant.php:104) | `setPermissionsTeamId($tenant->id)` — all role/permission queries scoped |
| **Policies** (StudentPolicy.php:28) | `$model->tenant_id === $user->tenant_id` |
| **Reserved Roles** (User.php:24-27) | `RESERVED_ROLE_NAMES` prevents escalation: super_admin, admin, center_admin, etc. |

---

## 3. SUBSCRIPTION & FEATURE LIMITS

### Rule S1: Feature Check Flow
| Source | Rule |
|--------|------|
| **Middleware** (CheckSubscription) | Skips in testing env (`SKIP_SUBSCRIPTION_CHECK=true`) |
| **Middleware** (CheckFeature) | `feature:{code}` checks `$tenant->hasFeature($featureCode)` |
| **Service** (SubscriptionService.php:64-107) | `checkLimit()`: No subscription → false. Boolean feature → filter_var. Unlimited (-1 or 'unlimited') → true. Otherwise: usage < limit |

### Rule S2: Usage Counters
| Source | Rule |
|--------|------|
| **Service** (SubscriptionService.php:142-170) | `getUsage()`: Redis atomic counter if available, else DB count fallback with Cache::remember(3600) |
| **Service** (SubscriptionService.php:175-192) | `incrementUsage()`: Redis increment + 90% threshold warning |
| **Service** (SubscriptionService.php:238-253) | `decrementUsage()`: Redis decrement (never below 0) |
| **User Model** (User.php:40-43, 56-59) | Student created → increment. Student deleted → decrement |
| **Service** (SubscriptionService.php:261-279) | `forgetUsage()`: Called on subscription change to reset stale counters |

### Rule S3: Feature Codes
| Code | Type | Purpose |
|------|------|---------|
| `max_students` | numeric | Student limit per tenant |
| `max_instructors` | numeric | Instructor limit |
| `max_courses` | numeric | Course limit |
| `max_classrooms` | numeric | Classroom limit |
| `max_branches` | numeric | Branch limit |
| `attendance_tracking` | boolean | Attendance module |
| `daily_schedules` | boolean | Schedule module |
| `financial_reports` | boolean | Advanced finance reports |
| `manage_exams` | boolean | Quiz system |
| `advanced_roles` | boolean | Custom role management |
| `multi_branch` | boolean | Branch management |
| `whatsapp` | boolean | WhatsApp notifications |
| `online_payments` | boolean | Paymob/PayPal checkout |
| `student_portal` | boolean | Student self-service |
| `parent_portal` | boolean | Guardian portal |
| `offline_attendance` | boolean | Offline QR sync |
| `ai_content` | boolean | AI assistant |

---

## 4. FINANCE RULES

### Rule F1: Sale Creation
| Source | Rule |
|--------|------|
| **Service** (FinanceService.php) | Creates sale + sale_items + enrollment + invoice in `DB::transaction()` |
| **Model** (Sale.php) | Fields: `total_amount`, `paid_amount`, `discount_amount`, `tax_amount`, `payment_method`, `status` |
| **DB Check** (2025_11_29_111952) | `CHECK (total_amount >= 0)`, `CHECK (paid_amount >= 0)` |

### Rule F2: Payment Processing
| Source | Rule |
|--------|------|
| **Service** (FinanceService.php:242) | `lockForUpdate()` on sale row to prevent race conditions |
| **Controller** (SaleController.php:addPayment) | Validates payment amount ≤ remaining balance |
| **Model** (Payment.php) | Unique index on `reference_number` for idempotency |
| **Migration** (2026_08_10) | Unique reference_number for double-payment prevention |

### Rule F3: Invoice Status
| Source | Rule |
|--------|------|
| **Service** (FinanceService.php) | paid_amount = 0 → "unpaid". paid_amount < total → "partial". paid_amount >= total → "paid" |
| **Migration** | CHECK constraint: `paid_amount >= 0 AND paid_amount <= total_amount` (implied) |

### Rule F4: Race Condition Protection
| Source | Rule |
|--------|------|
| **Service** (FinanceService.php:242) | `DB::transaction()` + `lockForUpdate()` on sale row |
| **Payment Webhook** (PaymobWebhookController.php:91) | Amount verification: received amount == expected amount (in cents) |
| **Unique Index** (payments.reference_number) | Prevents duplicate payments from webhook replay |

---

## 5. ATTENDANCE RULES

### Rule AT1: Marking
| Source | Rule |
|--------|------|
| **Service** (AttendanceService.php) | Validates schedule exists, student enrolled, no duplicate |
| **Controller** (AttendanceController.php) | Status: present, absent, late, excused |
| **DB** (attendances table) | Unique constraint: `student_id + schedule_id + session_date` |

### Rule AT2: QR Attendance
| Source | Rule |
|--------|------|
| **Route** (web.php:107) | `attendance/mark/{schedule}` — Public (signed URL, no auth required) |
| **Middleware** (web.php:108) | `throttle:scanner` — Rate limited |
| **Controller** (AttendanceController::markByQr) | Verifies signature, validates student enrollment |

### Rule AT3: Offline Sync
| Source | Rule |
|--------|------|
| **Route** (web.php:415-417) | `attendance/offline-sync` — feature:offline_attendance |
| **Controller** (AttendanceController::offlineSync) | Batch processing, conflict resolution |

---

## 6. COURSE & ENROLLMENT RULES

### Rule C1: Course Creation
| Source | Rule |
|--------|------|
| **Model** (Course.php) | Fields: name, description, price, status, tenant_id |
| **Policy** (CoursePolicy.php:26) | `$model->tenant_id === $user->tenant_id` |
| **Middleware** | `can:create courses` on create, `can:edit courses` on update |

### Rule C2: Enrollment
| Source | Rule |
|--------|------|
| **Controller** (CourseController::enroll) | Requires student_id, validates enrollment limit |
| **Service** (FinanceService.php) | Auto-creates sale + invoice on enrollment |
| **Model** (Enrollment.php) | user_id (student), course_id, progress, remaining_sessions |
| **Migration** | Unique constraint: unique enrollment per student per course |

---

## 7. SCHEDULE CONFLICT RULES

### Rule SC1: Time Validation
| Source | Rule |
|--------|------|
| **Service** (ScheduleConflictService.php) | Validates start_time < end_time |
| **Controller** (ScheduleApiController::checkConflict) | AJAX real-time validation |

### Rule SC2: Conflict Detection
| Source | Rule |
|--------|------|
| **Service** (ScheduleConflictService.php) | Checks: same instructor at same time, same classroom at same time, same student group at same time |
| **Controller** | Returns conflict details with suggested alternatives |

---

## 8. PAYMENT GATEWAY RULES

### Rule PG1: Paymob (Primary)
| Source | Rule |
|--------|------|
| **Controller** (PaymobWebhookController.php) | HMAC verification via `PAYMOB_HMAC_SECRET` |
| **Controller** (line 91) | Amount comparison in cents (integer) |
| **Model** (OnlineCheckout) | Maps Paymob order_id → internal sale |

### Rule PG2: PayPal (Secondary)
| Source | Rule |
|--------|------|
| **Controller** (PayPalWebhookController.php) | Webhook signature verification |
| **Service** (PayPalService.php) | Sandbox/Live mode switch via `PAYPAL_MODE` |

### Rule PG3: Demo Mode
| Source | Rule |
|--------|------|
| **Controller** (RegistrationController.php:41-43) | Demo payment restricted in production |
| **Config** (STRIPE_DEMO_MODE=true) | Bypasses real Stripe calls |

---

## 9. NOTIFICATION RULES

### Rule N1: WhatsApp
| Source | Rule |
|--------|------|
| **Service** (WhatsAppService.php) | Tenant-specific credentials from `tenant.settings.whatsapp_*` |
| **Middleware** (CheckFeature) | `feature:whatsapp` required |
| **Queue** | Dispatched as `SendWhatsAppNotification` job |

### Rule N2: Email
| Source | Rule |
|--------|------|
| **Config** (mail.php) | SMTP from env |
| **Service** | Queue-based sending with retry |

---

## 10. DATA INTEGRITY RULES

### Rule D1: Cascade Behavior
| Source | Rule |
|--------|------|
| **Migration** (2026_07_10) | Financial tables: `restrictOnDelete` (no cascade delete on payments, invoices) |
| **Migration** (2026_06_02) | Students/Courses: `restrictOnDelete` on enrollments |
| **Migration** (2026_06_07) | Fix financial cascades: prevent orphan financial records |

### Rule D2: Soft Deletes
| Source | Rule |
|--------|------|
| **Model** (Student.php) | `use SoftDeletes` — `deleted_at` column |
| **Model** (Course.php) | `use SoftDeletes` |
| **Model** (Sale.php) | `use SoftDeletes` |
| **Model** (Enrollment.php) | `use SoftDeletes` |
| **Migration** (2026_05_11) | Added soft_deletes to students, sales |
| **Migration** (2026_07_10) | Added soft_deletes to invoices, payments, refunds, expenses |

### Rule D3: Unique Constraints
| Source | Rule |
|--------|------|
| **Migration** (2026_05_02) | `users.email` unique per tenant (scoped) |
| **Migration** (2026_06_07) | `students.code` unique per tenant |
| **Migration** (2026_08_10) | `payments.reference_number` unique globally |
| **Migration** (2026_09_01) | Unique enrollment constraint |

---

## 11. SEARCH RULES

### Rule SR1: Tenant Isolation in Search
| Source | Rule |
|--------|------|
| **Service** (SearchService.php:53-54) | Explicit `tenant_id` filter on all search queries |
| **Model** (Student.php:54-67) | `toSearchableArray()` includes `tenant_id` |
| **Config** (scout.php) | `SCOUT_DRIVER=database` (dev) / `meilisearch` (prod) |

---

## 12. FILE UPLOAD RULES

### Rule FU1: Validation
| Source | Rule |
|--------|------|
| **Trait** (HandlesFileUploads.php) | Extension whitelist, MIME type validation, size limits |
| **Controller** | Arabic filenames supported, duplicate names handled |

### Rule FU2: Storage
| Source | Rule |
|--------|------|
| **Config** (filesystems.php) | Local disk: `storage/app`. Public disk: `storage/app/public` |
| **Config** (uploads.php) | Upload-specific settings |

---

## 13. RTL & LOCALIZATION RULES

### Rule L1: Locale
| Source | Rule |
|--------|------|
| **Route** (web.php:198-210) | `/lang/{locale}` — only `ar` in active locales |
| **Middleware** (SetLocale.php) | Reads from session or user locale |
| **Layout** (app-next.blade.php:2) | `dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"` |

### Rule L2: Translations
| Source | Rule |
|--------|------|
| **Directory** (resources/lang/) | ar, en, fr (3 locales) |
| **Module** (Modules/Center/resources/lang/) | ar, en, fr per module |
| **Blade** | `{{ __('key') }}` pattern |

---

## 14. REGISTRATION RULES

### Rule R1: Tenant Registration
| Source | Rule |
|--------|------|
| **Controller** (RegistrationController.php) | Multi-step: phone OTP → email+password → package → payment |
| **Service** (TenantRegistrationService.php) | DB::transaction for atomic creation |
| **Controller** (line 173) | Session HMAC prevents payment bypass |

### Rule R2: Phone Verification
| Source | Rule |
|--------|------|
| **Route** (web.php:135-140) | `/api/phone/send-otp` → `/api/phone/verify-otp` |
| **Throttle** | 10 per 5 min (send), 20 per 5 min (verify) |
| **Model** (User.php:169-178) | 6-digit code, expires in 15 minutes, encrypted |

---

## 15. CRITICAL INCONSISTENCIES

| # | Rule | Backend | Frontend | DB | Risk | Severity |
|---|------|---------|----------|-----|------|----------|
| 1 | Subscription skip in testing | `SKIP_SUBSCRIPTION_CHECK` bypasses limits | UI still shows limits | Cache may be stale | Dev-only, safe | LOW |
| 2 | google2fa_bypass column | Exists on users table | No UI to set | Migratable | Could be abused if exposed | MEDIUM |
| 3 | Rate limit relaxation | `is_relaxed_throttle_env()` → 100 | N/A | N/A | Must not be in production | MEDIUM |
| 4 | Bootstrap + Tailwind | Mixed in layout | Inconsistent styling | N/A | UX degradation | LOW |
| 5 | Activity log retention | No retention policy | N/A | Table grows forever | Performance over time | MEDIUM |

---

*Generated during Pass 1 Discovery & Audit*
*Last Updated: 2026-09-02*