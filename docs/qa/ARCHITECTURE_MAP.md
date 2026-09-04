# TAALIMU ARCHITECTURE MAP

## 1. SYSTEM OVERVIEW

**Platform**: Multi-tenant SaaS Educational Management Platform (LMS)
**Stack**: Laravel 12, PHP 8.4, MySQL, React 19 (Inertia.js), Tailwind CSS 3.4, Redis, Queues, PWA
**Architecture**: Modular Monolith (nwidart/laravel-modules)
**Tenancy**: Single-database with `tenant_id` partitioning (hybrid subdomain + path)

---

## 2. DIRECTORY STRUCTURE

```
taalimu.com/
├── app/
│   ├── Casts/                 # Custom Eloquent casts (EncryptedSettings)
│   ├── Console/Commands/      # Artisan commands
│   ├── Constants/             # Application constants
│   ├── DTOs/                  # Data Transfer Objects
│   ├── Events/                # Event classes
│   ├── Exceptions/            # Custom exceptions
│   ├── Helpers/               # Global helper functions
│   ├── Http/
│   │   ├── Controllers/       # Global controllers (Auth, Landing, Payment, Admin)
│   │   ├── Middleware/        # 16+ middleware (WAF, CSP, Tenant, Auth, etc.)
│   │   └── Requests/          # Form Requests
│   ├── Interfaces/            # Service contracts
│   ├── Jobs/                  # Queue jobs (ImportStudentsJob, SendWhatsAppNotification, etc.)
│   ├── Listeners/             # Event listeners
│   ├── Mail/                  # Mailables
│   ├── Models/                # 51+ Eloquent models (core + tenant-scoped)
│   ├── Notifications/         # Notification classes
│   ├── Observers/             # Model observers
│   ├── Policies/              # 17+ authorization policies
│   ├── Providers/             # Service providers
│   ├── Queries/               # Query builders
│   ├── Repositories/          # Repository pattern
│   ├── Scopes/                # TenantScope (global scope)
│   ├── Services/              # 33+ service classes (business logic)
│   ├── Support/               # Support classes
│   └── Traits/                # BelongsToTenant, ClearsDashboardCache, etc.
├── bootstrap/                 # Framework bootstrap
├── config/                    # 25+ config files
├   ├── auth.php              # Guards: web, admin
├   ├── database.php          # SQLite (dev), MySQL (prod), Redis
├   ├── modules.php           # nwidart/laravel-modules config
├   ├── tenancy.php           # Tenancy config (if exists)
├   └── ...                   # backup, cache, queue, mail, etc.
├── database/
│   ├── factories/             # Model factories
│   ├── migrations/            # 81+ migrations (2025-11 to 2026-09)
│   └── seeders/               # Database seeders
├── deployment_scripts/        # Deployment automation
├── docker/                    # Docker configuration
├── docs/                      # Documentation
├   └── qa/                   # QA documentation (this directory)
├── Modules/                   # 6 Laravel Modules
│   ├── Admin/                # Super admin panel
│   ├── Api/                  # RESTful API (Sanctum)
│   ├── Campus/               # Multi-branch management
│   ├── Center/               # Main tenant portal (30+ controllers)
│   ├── Instructor/           # Teacher workspace
│   └── Tenancy/              # Domain resolution, middleware
├── node_modules/              # NPM dependencies
├── playwright-report/         # Playwright test reports
├── public/                    # Public assets
├── resources/
│   ├── css/                  # Tailwind + custom CSS
│   ├── js/
│   │   ├── Pages/            # Inertia React pages (DemoDashboard, StudentPortal)
│   │   ├── app.jsx           # Inertia app entry
│   │   ├── bootstrap.js      # Frontend bootstrap
│   │   ├── bs-compat.js      # Bootstrap compatibility
│   │   └── taalimu-global.js # Global Alpine.js interactions
│   ├── lang/                 # Translations (ar, en, fr)
│   └── views/                # Blade views (global + module views)
├   ├── layouts/              # app-next.blade.php (main layout)
│   ├── components/           # Blade components (ui.dropdown, ui.avatar, etc.)
│   └── ...                   # Admin, auth, emails, errors, policies
├── routes/
│   ├── web.php               # Central + tenant routes (210 lines)
│   ├── api.php               # API routes
│   ├── channels.php          # Broadcasting channels
│   └── console.php           # Artisan commands
├── scripts/                   # Utility scripts
├── storage/                   # Logs, framework, app files
├── stubs/                     # Module stubs
├── test-results/              # Test artifacts
├── tests/                     # PHPUnit tests (Feature, Unit, E2E, Load)
│   ├── Feature/              # 35+ feature tests
│   ├── Unit/                 # 12+ unit tests
│   ├── E2E/                  # 5 Playwright tests
│   └── Load/                 # k6 load tests
├── vendor/                    # Composer dependencies
├── .env                       # Environment config
├── artisan                    # Laravel CLI
├── composer.json              # PHP dependencies
├── package.json               # NPM dependencies
├── phpunit.xml               # PHPUnit config
├── playwright.config.js      # Playwright config
├── tailwind.config.js        # Tailwind config
├── vite.config.js            # Vite config
└── README.md
```

---

## 3. MODULE ARCHITECTURE

### 3.1 Core Modules (nwidart/laravel-modules)

| Module | Purpose | Controllers | Models | Routes | Views |
|--------|---------|-------------|--------|--------|-------|
| **Admin** | Super admin dashboard, tenant management, packages, features, backups, operation issues | ~15 | Uses core models | admin.* | resources/views/admin/ |
| **Center** | Main tenant portal (student mgmt, courses, attendance, sales, schedules, quizzes, settings) | 30+ | Uses core models + Branch | center.* (subdomain) | Modules/Center/resources/views/ |
| **Instructor** | Teacher workspace (schedule, attendance, quizzes, grades) | ~10 | Uses core models | instructor.* | resources/views/instructor/ |
| **Campus** | Multi-branch management | ~5 | Uses core models + Branch | campus.* | resources/views/campus/ |
| **Tenancy** | Domain resolution, tenant middleware, registration flow | ~3 | Tenant, User | tenancy.* | resources/views/tenancy/ |
| **Api** | RESTful API with Sanctum tokens | ~8 | Uses core models | api.* | N/A |

### 3.2 Module Dependencies

```
Admin (independent)
    ↓
Tenancy (provides IdentifyTenant middleware, tenant resolution)
    ↓
Center ← Campus ← Instructor
    ↓
Api (uses Center services)
```

---

## 4. ROUTING ARCHITECTURE

### 4.1 Central Domain Routes (routes/web.php)
- **Landing page** (`/`) → `LandingController@index`
- **Registration** (`/register`) → `RegistrationController`
- **Unified Login** (`/login`) → `UnifiedAuthController` (SSO flow to tenant)
- **Password Reset** → Laravel standard + tenant-scoped tokens
- **Payment callbacks** (PayPal, Paymob, Zoom webhooks)
- **Policy pages** (privacy, terms, cookies, GDPR)
- **Admin routes** (`/admin/*`) → super_admin only

### 4.2 Tenant Routes (Modules/Center/routes/web.php)
Registered via `IdentifyTenant` middleware on:
- **Subdomain**: `{tenant}.{domain}` (e.g., `center1.localhost`)
- **Path**: `/c/{tenant}/...` (hybrid mode)

**Route Groups by Middleware**:
1. **Guest routes** (`guest`, `prevent-back-history`): login, magic login, registration
2. **Auth only** (`auth`, `force_password_change`): logout, 2FA, subscription, onboarding
3. **Full protection** (`auth`, `subscription`, `force_password_change`, `onboarding.completed`, `2fa`, `prevent-back-history`):
   - Dashboard, students, instructors, courses, billing, attendance, analytics, settings, etc.
4. **Feature-gated** (`feature:*`): quizzes, financial_reports, attendance_tracking, daily_schedules, multi_branch, advanced_roles, offline_attendance

### 4.3 Route Model Binding
- Implicit binding with global `TenantScope` ensures automatic tenant filtering
- Explicit `{tenant}` parameter in subdomain/path routes

---

## 5. MULTI-TENANCY ARCHITECTURE

### 5.1 Tenant Identification (`IdentifyTenant` middleware)
```php
// Subdomain mode
$host = $request->getHost();
$subdomain = extract from host
$tenant = Tenant::with(['currentSubscription.package.features'])
    ->where('domain', $subdomain)
    ->first();

// Path mode
/c/{tenant}/...
$tenant = resolve by domain from path segment
```

### 5.2 Tenant Binding
```php
app()->instance('tenant', $tenant);
app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
view()->share('tenant', $tenant);
URL::defaults(['tenant' => $tenant->domain]);
config(['app.timezone' => $tenant->timezone]);
config(['logging.channels.single.path' => storage_path("logs/tenant_{$tenant->id}.log")]);
```

### 5.3 Global Scope (`TenantScope`)
```php
// Applied automatically via BelongsToTenant trait
$builder->where($model->getTable().'.tenant_id', app('tenant')->id);

// Exception: User model allows global users (tenant_id = null)
```

### 5.4 Trait: `BelongsToTenant`
```php
static::addGlobalScope(new TenantScope);
static::creating(function ($model) {
    if (app()->bound('tenant')) {
        $model->tenant_id = app('tenant')->id;
    }
});
```

### 5.5 Spatie Permission Teams
- `setPermissionsTeamId($tenant->id)` ensures roles/permissions scoped per tenant
- Super admins have `tenant_id = null` and `role = super_admin`

---

## 6. SERVICE LAYER ARCHITECTURE

### 6.1 Core Services (app/Services/)
| Service | Responsibility | Key Patterns |
|---------|---------------|--------------|
| **SubscriptionService** | Feature limits, usage counters, Redis atomic ops | Redis increment/decrement, cache remember, threshold alerts |
| **FinanceService** | Sales, payments, invoices, commissions, payouts | DB::transaction, lockForUpdate, bulk inserts |
| **AttendanceService** | Attendance marking, QR, WhatsApp notifications, gamification | Queue jobs, offline sync |
| **StudentService** | Student CRUD, import/export, risk scoring | LazyCollection, chunked processing |
| **CourseService** | Course CRUD, curriculum, enrollment | Transactions, eager loading |
| **TenantRegistrationService** | Tenant onboarding, coupon validation, demo data | DB::transaction, rollback on failure |
| **WhatsAppService** | Template messages, rate limiting, tenant credentials | Queue, retry logic |
| **SearchService** | Meilisearch + LIKE fallback, tenant isolation | Scout, explicit tenant_id filter |
| **ScheduleConflictService** | Real-time conflict detection | AJAX API, time overlap logic |
| **StudentRiskService** | Risk scoring engine | Deterministic rules, cache |

### 6.2 Service Patterns
- **Dependency Injection**: Constructor injection, interfaces where applicable
- **Transactions**: `DB::transaction()` for multi-table operations
- **Concurrency**: `lockForUpdate()` on financial records
- **Queue**: WhatsApp, Email, Import jobs dispatched to queue
- **Caching**: Redis with DB fallback, tag-based invalidation
- **Observers**: Model observers for cache clearing, activity logging

---

## 7. AUTHENTICATION & AUTHORIZATION

### 7.1 Guards
| Guard | Provider | Cookie | Purpose |
|-------|----------|--------|---------|
| `web` | users (eloquent) | session | Tenant users |
| `admin` | users (eloquent) | taalimu_admin_session | Super admins |

### 7.2 Unified Login Flow
1. User submits credentials on central domain (`/login`)
2. `UnifiedAuthController::login()` validates via `UnifiedAuthService`
3. If super_admin → redirect to admin panel
4. If tenant user → generate SSO token → POST to tenant `login/sso`
5. Tenant `AuthController::ssoLogin()` validates HMAC signature → logs in

### 7.3 Password Reset
- Tokens stored in `password_reset_tokens` table **scoped by tenant_id** (migration 2026_08_17)
- `ForgotPasswordController` uses generic responses (no tenant enumeration)

### 7.4 Two-Factor Authentication
- TOTP via `pragmarx/google2fa-laravel`
- `TwoFactorMiddleware` enforces 2FA on protected routes
- Setup/verify/disable routes in tenant area

### 7.5 Authorization (Spatie Permission)
- **Roles**: super_admin, center_admin, center_owner, instructor, student, parent, guardian, secretary, accountant, staff
- **Permissions**: Fine-grained (view students, create courses, manage billing, etc.)
- **Teams**: Team ID = tenant_id (via `setPermissionsTeamId`)
- **Policies**: 17+ model policies checking `tenant_id` ownership
- **Reserved Roles**: `User::RESERVED_ROLE_NAMES` prevents escalation

---

## 8. FRONTEND ARCHITECTURE

### 8.1 Technology Stack
| Layer | Technology | Status |
|-------|------------|--------|
| Primary | Blade + Alpine.js | Production |
| Secondary | Inertia.js + React 19 | Demo only (/inertia-demo) |
| CSS | Tailwind CSS 3.4 + Bootstrap 5.3 | Mixed (conflict risk) |
| RTL | CSS logical properties + rtl.css | Partial |
| PWA | Service Worker + offline route | Partial |
| Icons | Font Awesome 6.5 | Full |

### 8.2 Layout System
- **Main Layout**: `resources/views/layouts/app-next.blade.php` (353 lines)
- **Sidebar**: `hope-sidebar.blade.php` (role-based navigation)
- **Header**: `hope-header.blade.php` (theme, lang, notifications, profile)
- **Components**: `resources/views/components/ui/` (dropdown, avatar, toast, command-palette, page-header)

### 8.3 JavaScript
- **Global**: `taalimu-global.js` (18KB - Alpine.js interactions, toasts, shortcuts)
- **Module JS**: Minimal (Center has empty app.js)
- **Vite**: Builds `resources/css/tailwind.css`, `resources/css/app.scss`, `resources/js/app.js`, `resources/js/taalimu-global.js`

---

## 9. DATABASE ARCHITECTURE

### 9.1 Connection
- **Default**: `mysql` (configurable via `DB_CONNECTION`)
- **Dev fallback**: `sqlite` (database/database.sqlite)
- **Foreign Keys**: Enabled (`DB_FOREIGN_KEYS=true`)

### 9.2 Key Tables (81 migrations)
| Domain | Tables |
|--------|--------|
| Tenancy | tenants, subscriptions, packages, features, package_features, subscription_logs |
| Users | users, roles, permissions, model_has_roles, model_has_permissions, role_has_permissions |
| Academic | stages, grades, courses, sections, lessons, schedules, classrooms, enrollments |
| People | students, instructors, guardians, guardian_student |
| Finance | sales, sale_items, invoices, payments, refunds, expenses, commissions, payouts, coupons |
| Attendance | attendances |
| Assessment | quizzes, questions, question_options, quiz_attempts, assignments, assignment_submissions |
| Content | course_resources, certificates, assets, bookings |
| Communication | notifications, tickets, ticket_messages, operation_issues, issue_timeline, issue_attachments |
| Settings | site_settings, user_consents, point_logs |
| Online | online_classes, online_class_participants, class_recordings, video_progress, video_access_logs, online_checkouts |

### 9.3 Indexing Strategy
- 50+ composite indexes on hot paths
- `tenant_id` leading column on all tenant-scoped tables
- Unique constraints: `users.email` (per tenant), `students.code`, `payments.reference_number`
- Check constraints: `monthly_fee >= 0`, `price >= 0`

### 9.4 Soft Deletes
- Applied to: students, courses, sales, enrollments, quiz_attempts, assignments, instructors, attendance, etc.
- Financial tables: soft deletes added in migration 2026_07_10 for audit trail

---

## 10. QUEUE & JOBS

### 10.1 Queue Configuration
- **Driver**: `sync` (dev), `redis` (prod)
- **Connections**: database, redis
- **Failed Jobs**: `failed_jobs` table

### 10.2 Key Jobs
| Job | Queue | Purpose |
|-----|-------|---------|
| `ImportStudentsJob` | default | Chunked CSV import (LazyCollection) |
| `SendWhatsAppNotification` | notifications | Template messages with rate limiting |
| `SendEmailNotification` | emails | Transactional emails |
| `ProcessSubscriptionWebhook` | webhooks | Paymob/PayPal webhook processing |

---

## 11. CACHING ARCHITECTURE

### 11.1 Cache Stores
| Store | Driver | Database | Purpose |
|-------|--------|----------|---------|
| default | file (dev) / redis (prod) | 0 | General caching |
| cache | redis | 1 | Application cache |
| session | redis | 2 | Session storage |

### 11.2 Cache Keys Pattern
- `taalimu:tenancy:domain:{domain}` - Tenant resolution (1hr TTL)
- `tenant_{id}_usage_{feature}` - Usage counters (Redis atomic)
- `tenant_{id}:student_profile_{id}` - Student profiles (6hr TTL)
- `tenant_ltv_{id}` - Lifetime value (1hr TTL)
- `tenant_overdue_{id}` - Overdue count (1hr TTL)

---

## 12. FILE STORAGE

### 12.1 Disks (config/filesystems.php)
- **local**: `storage/app` (private)
- **public**: `storage/app/public` (linked to public/storage)
- **s3**: AWS S3 (optional, for production)

### 12.2 Upload Handling
- `HandlesFileUploads` trait on controllers
- Validation: extension, MIME, size
- Arabic filenames supported
- Tenant-isolated paths

---

## 13. NOTIFICATIONS & COMMUNICATION

### 13.1 Channels
| Channel | Implementation | Queue |
|---------|---------------|-------|
| Email | Laravel Mail (SMTP) | emails |
| WhatsApp | WhatsAppService (WhatsApp Business API) | notifications |
| In-App | Database notifications table | sync |
| Telegram | TelegramService (Bot API) | notifications |
| SMS | (Not implemented) | - |

### 13.2 Key Notifications
- Attendance alerts (absent/late)
- Payment reminders (overdue)
- Invoice created
- Password reset
- Welcome/onboarding
- Quiz/assignment due

---

## 14. SCHEDULED TASKS

### 14.1 Kernel Schedule (app/Console/Kernel.php)
- Daily: Subscription expiry checks
- Daily: Payment reminders
- Daily: Risk score recalculation
- Weekly: Analytics cache refresh
- Monthly: Invoice generation

---

## 15. TESTING ARCHITECTURE

### 15.1 Test Structure
```
tests/
├── TestCase.php                 # Base test case (RefreshDatabase, tenant helpers)
├── Feature/                     # 35+ tests
│   ├── Api/                    # API auth, roles, tenant isolation
│   ├── Security/               # IDOR, Model Isolation, Tenant Identity
│   ├── Validation/             # Student validation
│   └── *.php                   # Registration, Students, Courses, Sales, etc.
├── Unit/                       # 12+ tests
│   ├── Helpers/                # QueryHelper
│   ├── Middleware/             # BasicWAF
│   ├── Models/                 # Course, Quiz, Sale, Tenant, User
│   ├── Services/               # DemoPayment, Finance, StudentRisk, Student
│   └── Traits/                 # HandlesFileUploads
├── E2E/                        # Playwright (5 specs)
│   ├── AttendanceTest.spec.js
│   ├── EnrollmentTest.spec.js
│   ├── LoginTest.spec.js
│   ├── PaymentFlowTest.spec.js
│   └── ReportExportTest.spec.js
└── Load/                       # k6 load test
```

### 15.2 Test Helpers
- `createTenant()` - Creates tenant with subscription
- `actingAsTenantAdmin()` - Auth as tenant admin
- `refreshTenant()` - Re-binds tenant in container

---

## 16. DEPLOYMENT ARCHITECTURE

### 16.1 Docker
- `Dockerfile` - PHP 8.4 + Nginx + Supervisor
- `docker-compose.yml` - App, MySQL, Redis, Meilisearch

### 16.2 Deployment Scripts
- `deploy.sh` - Production deployment
- `DEPLOYMENT_GUIDE.md` - Manual steps
- `DEPLOYMENT_GUIDE_KVM2.md` - KVM2 specific

### 16.3 Environment Files
- `.env.example` - Template
- `.env.local` - Local development
- `.env.production` - Production template
- `.env.deploy_backup` - Backup

---

## 17. KEY INTEGRATION POINTS

| Integration | Service | Auth Method |
|-------------|---------|-------------|
| **Paymob** | Payment gateway (Egypt) | HMAC secret, iframe |
| **PayPal** | Payment gateway (Global) | Webhook signature |
| **Stripe** | Deprecated | - |
| **Meilisearch** | Full-text search | API key |
| **WhatsApp** | Business API | Bearer token (per tenant) |
| **Telegram** | Bot API | Bot token |
| **Google OAuth** | Social login | OAuth 2.0 |
| **Zoom** | Online classes | Webhook signature |
| **Sentry** | Error tracking | DSN |

---

## 18. SECURITY LAYERS

1. **WAF Middleware** (`BasicWAF`) - Basic regex patterns for SQLi, XSS
2. **CSP Middleware** (`ContentSecurityPolicy`) - Strict CSP headers
3. **Tenant Isolation** - Global scope + policies + middleware
4. **Rate Limiting** - Per-route throttle middleware
5. **CSRF** - Laravel built-in + API token (Sanctum)
6. **2FA** - TOTP mandatory for tenant admins
7. **Encrypted Settings** - WhatsApp tokens encrypted at rest
8. **Signed URLs** - Magic login, online payments, QR attendance

---

## 19. OBSERVABILITY

| Tool | Purpose |
|------|---------|
| **Sentry** | Error tracking (sentry/sentry-laravel) |
| **Activity Log** | Spatie Activitylog on critical models |
| **Tenant Logs** | Per-tenant log files (`tenant_{id}.log`) |
| **Telescope** | Not installed |
| **Horizon** | Not installed (Redis queue monitoring) |

---

## 20. CONFIGURATION MATRIX

| Config | File | Key Settings |
|--------|------|--------------|
| App | config/app.php | locale, timezone, tenant_domain, tenancy_mode |
| Auth | config/auth.php | guards (web, admin), providers, passwords |
| Database | config/database.php | connections (sqlite, mysql), redis |
| Cache | config/cache.php | stores (file, redis) |
| Queue | config/queue.php | connections (sync, redis, database) |
| Session | config/session.php | driver, lifetime, secure_cookie |
| Mail | config/mail.php | smtp, from, encryption |
| Permission | config/permission.php | teams, models |
| Scout | config/scout.php | driver (database/meilisearch) |
| Backup | config/backup.php | spatie/laravel-backup |
| Reverb | config/reverb.php | WebSocket server |

---

*Generated during Pass 1 Discovery & Audit*
*Last Updated: 2026-09-02*