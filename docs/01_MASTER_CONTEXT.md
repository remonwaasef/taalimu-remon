# 01_MASTER_CONTEXT - Master Context & Single Source of Truth

> [!IMPORTANT]
> **READ-FIRST MANDATE FOR AI ASSISTANTS**: This single file provides a complete, high-density synthesis of the Taalimu.com architecture, domain model, database schema, business logic, routing, and developer conventions. **Do NOT scan the entire repository.** Everything required to understand and work on Taalimu.com is contained herein.

---

## 1. Project Overview & Business Core

**Taalimu.com** is a multi-tenant Educational Management SaaS & LMS platform built on **Laravel 12.x** and **PHP 8.4**. It is designed to digitize and automate the operations of educational centers, academies, campuses, and independent tutors.

### Primary Capabilities
- **Multi-Tenant SaaS Onboarding**: Automated creation of center academies on subdomains (e.g., `center.taalimu.com`) or custom domains with localized settings.
- **Academic Hierarchy**: Support for academic stages (Primary, Secondary), grades, classrooms, courses, sections, and lessons.
- **Student & Parent Portals**: Enrollment tracking, QR code identification, student ledger balance tracking, and linked parent profiles.
- **Attendance & WhatsApp Alerts**: Class schedule conflict checking, real-time attendance marking (Present/Absent/Late with minutes), and instant WhatsApp notifications to parents.
- **Financial POS & Invoicing**: Billing management, discounts, coupon validation, POS sales creation, partial payments, instructor commission calculations, and payout execution.
- **Quizzes & Question Bank**: Online assessment creation, categorised question banks, automated grading, and assignment submissions.
- **Production Exception Triage**: Internal support tickets and an automated `OperationIssue` tracker capturing production stack traces with timeline logs.

---

## 2. System Architecture & Tech Stack

### Architecture Style: Modular Monolith (`nwidart/laravel-modules`)
The system isolates domain responsibilities into 6 core modules in `Modules/`:
1. `Admin`: Central Super Admin dashboard, tenant provision, SaaS packages, backups, operation issue tracker.
2. `Center`: Main operational center portal (courses, students, sales, expenses, attendance, quizzes).
3. `Instructor`: Dedicated workspace for teachers to take attendance and view timetables.
4. `Campus`: Multi-branch campus location management.
5. `Tenancy`: Domain resolution (`IdentifyTenant` middleware) and subdomain bootstrapping.
6. `Api`: Sanctum-authenticated RESTful API endpoints.

### Tech Stack Details
- **Backend Core**: PHP 8.4 / Laravel 12.x
- **Multi-Tenancy Engine**: Single-database multi-tenancy partitioned via `tenant_id` foreign keys and the `BelongsToTenant` Eloquent trait (`TenantScope`).
- **Frontend Hybrid**: Server-rendered Blade templates + Alpine.js micro-interactions + Inertia.js React 19 interactive dashboards.
- **Styling**: TailwindCSS 3.4 + Bootstrap 5.3 + Custom CSS design tokens (`resources/css/design-tokens.css`).
- **Realtime & Queue**: Laravel Reverb (WebSockets), database/redis Laravel Queues (`queue:listen`).
- **Integrations**: WhatsApp API, Telegram Bot API, PayPal REST SDK, Paymob Gateway, Sentry, Spatie Activitylog & Backup, Barryvdh DomPDF.

---

## 3. Key Model & Directory Reference

```
app/
├── Models/
│   ├── Tenant.php            # Center entity (subdomain, domain, type, status)
│   ├── User.php              # Auth model (roles: super_admin, center_owner, instructor, student, guardian)
│   ├── Student.php           # Student profile (BelongsToTenant, SoftDeletes, code, debt)
│   ├── Guardian.php          # Parent profile (linked via guardian_student pivot)
│   ├── Instructor.php        # Teacher profile & commission rules
│   ├── Course.php            # Academic course (belongsTo Stage/Grade, hasMany Sections)
│   ├── Classroom.php         # Physical/virtual room (capacity, image)
│   ├── Schedule.php          # Timetable slot (day_of_week, start_time, end_time)
│   ├── Attendance.php        # Attendance record (status: present, absent, late, late_minutes)
│   ├── Sale.php              # Financial order (subtotal, discount, tax, total, payment_status)
│   ├── Invoice.php & Payment # Invoice generation and transaction payments
│   ├── Subscription.php      # Center SaaS package subscription
│   ├── OperationIssue.php    # Production crash triage record
│   └── SiteSetting.php       # Dynamic key-value tenant configuration
│
├── Services/
│   ├── TenantRegistrationService.php # Onboarding orchestration
│   ├── FinanceService.php             # Billing, sales, invoices, ledgers
│   ├── AttendanceService.php          # Attendance marking & parent alert trigger
│   ├── SubscriptionService.php        # SaaS plan management & feature checks
│   ├── PayoutService.php              # Commission calculations & payout execution
│   ├── WhatsAppService.php            # WhatsApp debt/attendance reminders
│   ├── TelegramService.php            # Daily/weekly admin reports
│   └── OperationIssueService.php      # Exception capturing & triage
│
└── Http/Middleware/
    ├── IdentifyTenant.php    # Resolves tenant from host domain/subdomain
    ├── CheckAdminRole.php    # Enforces Super Admin access
    ├── CheckSubscription.php # Verifies active SaaS plan
    └── BasicWAF.php          # Request sanitization & security
```

---

## 4. Routing & Authentication Structure

### Web Routes & Domain Scoping
- **Central Domain (`routes/web.php`)**: Filtered by `config('app.tenant_domain')`. Handles landing page (`/`), center registration (`/register`), unified login (`/login`), social auth (`/auth/google`), payment webhooks (`/webhooks/paypal`, `/webhooks/paymob`), legal pages, and PWA offline view (`/offline`).
- **Admin Module (`Modules/Admin/routes/web.php`)**: Prefixed `/admin`, protected by `auth` and `CheckAdminRole`.
- **Center Module (`Modules/Center/routes/web.php`)**: Tenant subdomain scope, protected by `auth`, `EnsureOnboardingCompleted`, and `CheckSubscription`.

### Authentication Methods
- Unified multi-role login (`UnifiedAuthController`).
- Google OAuth Socialite (`SocialAuthController`).
- Pre-registration Phone OTP verification (`PhoneVerificationController`).
- 2FA TOTP Google Authenticator (`TwoFactorController`).
- Sanctum bearer tokens (`auth:sanctum`).

---

## 5. Security & Role Permissions Matrix

- **`super_admin`**: Full system control (`/admin`), tenant management, backups, package setup, issue triage.
- **`center_owner`** / **`admin`**: Full operational control over their specific tenant center.
- **`instructor`**: View assigned courses, timetables, mark attendance, track commissions.
- **`student`**: View enrolled courses, submit assignments, take quizzes, view attendance.
- **`guardian`**: View linked children's attendance, payment balances, and academic reports.

---

## 6. Development & AI Execution Rules

1. **Do NOT Modify Working Code Unnecessarily**: Adhere strictly to the requested task scope.
2. **Preserve Single-Database Multi-Tenancy**: Always include `BelongsToTenant` trait on tenant-isolated models.
3. **Use Service Layer**: Never write heavy database or multi-step logic directly in controllers. Keep controllers thin and delegate to `app/Services/`.
4. **Database Changes via Migrations Only**: Create migrations for any database modification and wrap operations in `DB::transaction()`.
5. **Keep Docs Synchronized**: When making structural updates, update `docs/34_CHANGELOG.md` and relevant docs in `docs/`.
