# 🏗️ Architecture Overview — منصة Taalimu

> آخر تحديث: يوليو 2026
> هذه الوثيقة تشرح بنية النظام لأي مطور أو CTO جديد يستلم المشروع.

---

## 1. نظرة عامة

**Taalimu** هي منصة SaaS تعليمية متعددة المستأجرين (Multi-Tenant) مبنية على:

| العنصر | التقنية |
|:---|:---|
| Backend | Laravel 12 (PHP 8.4) |
| Frontend | Blade Templates + Tailwind CSS + Bootstrap 5 + React/Inertia لبعض الشاشات |
| Database | MySQL 8 (Shared Database) |
| Caching | Database (قابل للترقية لـ Redis) |
| Queue | Database (قابل للترقية لـ Redis) |
| Auth | Laravel Auth + Spatie Permissions |
| Payments | PayPal + Paymob + Stripe (Factory Pattern) |
| Notifications | Email (SMTP) + Telegram Bot + WhatsApp API |
| Languages | العربية (افتراضي) + English + Français |

---

## 2. Multi-Tenancy Architecture

النظام يعمل بنمط **Shared Database** — كل المستأجرين يتشاركون نفس قاعدة البيانات مع عزل البيانات عبر `tenant_id`.

```
                    ┌────────────────────────┐
                    │     taalimu.com         │ ← الموقع الرئيسي (التسجيل + الأسعار)
                    └────────┬───────────────┘
                             │
              ┌──────────────┼──────────────┐
              ▼              ▼              ▼
    ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
    │ center1      │ │ center2      │ │ instructor1  │
    │ .taalimu.com │ │ .taalimu.com │ │ .taalimu.com │
    └──────┬───────┘ └──────┬───────┘ └──────┬───────┘
           │                │                │
           ▼                ▼                ▼
    ┌──────────────────────────────────────────────┐
    │          قاعدة بيانات مشتركة (edu_central)    │
    │     كل جدول يحتوي tenant_id للعزل           │
    └──────────────────────────────────────────────┘
```

### كيف يتم تحديد المستأجر؟
- **Middleware:** `IdentifyTenant` (يعمل أول شيء في كل طلب)
- **وضعان:**
  - `subdomain` → `center1.taalimu.com` (الافتراضي في الإنتاج)
  - `path` → `taalimu.com/c/center1` (للتطوير المحلي)
- **الإعداد:** `config('app.tenancy_mode')` و `TENANT_DOMAIN` في `.env`

### العزل (Isolation)
- كل Model متعدد المستأجرين يستخدم `BelongsToTenant` Trait → يضيف `where('tenant_id', ...)` تلقائياً
- Global Scope يمنع أي مستأجر من رؤية بيانات آخر
- Spatie Permissions يستخدم `teams_id` = `tenant_id`

---

## 3. Modular Architecture

المشروع مقسم إلى 6 وحدات (Modules) مستقلة:

```
Modules/
├── Center/        ← 🏢 واجهة إدارة المركز التعليمي (الأكبر)
│   ├── Controllers/   (40 controller)
│   ├── routes/web.php
│   └── views/
│
├── Instructor/    ← 👨‍🏫 واجهة المدرّس
│   ├── Controllers/
│   ├── routes/web.php
│   └── views/
│
├── Admin/         ← 🛡️ لوحة Super Admin (إدارة كل المراكز)
│   ├── Controllers/
│   ├── routes/web.php
│   └── views/
│
├── Campus/        ← 🎓 واجهة الطالب
│
├── Api/           ← 📡 API Endpoints
│
└── Tenancy/       ← 🔧 خدمات Multi-Tenancy الأساسية
    └── Services/TenantResolver.php
```

### قاعدة: كل Module مستقل
- لديه `routes/` خاصة
- لديه `views/` خاصة
- لديه `Controllers/` خاصة
- يعتمد على `app/Services/` و `app/Models/` المشتركة

---

## 4. Service Layer

كل Business Logic معزول في خدمات مستقلة:

```
app/Services/
├── PaymentFactory.php           ← Factory Pattern لبوابات الدفع
├── PaymentProcessingService.php ← منطق تفعيل الاشتراكات (موحّد)
├── PaymentGateways/
│   ├── PayPalGateway.php
│   ├── PaymobGateway.php
│   └── MockGateway.php
│
├── SubscriptionService.php      ← تجديد / إيقاف / ترقية الاشتراكات
├── FinanceService.php           ← التقارير المالية والإحصائيات
├── AttendanceService.php        ← منطق الحضور والغياب
├── CourseService.php            ← إدارة الدورات
├── StudentService.php           ← إدارة الطلاب
│
├── TelegramService.php          ← إشعارات Telegram للمدير
├── WhatsAppService.php          ← رسائل WhatsApp للطلاب
├── IssueLogger.php              ← تسجيل أخطاء 500
│
├── DemoDataService.php          ← بيانات تجريبية للمراكز الجديدة
├── GeoIPService.php             ← كشف الموقع الجغرافي
└── GdprService.php              ← حذف البيانات الشخصية (GDPR)
```

---

## 5. Middleware Pipeline

ترتيب الـ Middleware في كل طلب HTTP:

```
Request
  │
  ▼
┌─────────────────────┐
│ 1. IdentifyTenant   │ ← يحدد المستأجر من الـ URL (أول شيء)
├─────────────────────┤
│ 2. SetLocale        │ ← يحدد اللغة (ar/en/fr)
├─────────────────────┤
│ 3. ContentSecurityPolicy │ ← يمنع inline scripts خبيثة
├─────────────────────┤
│ 4. SecurityHeaders  │ ← يضيف X-Frame-Options, etc.
├─────────────────────┤
│ 5. BasicWAF         │ ← يحظر محاولات SQL Injection/XSS
├─────────────────────┤
│ 6. PaginationLimit  │ ← يمنع طلب 10000 سجل في صفحة واحدة
├─────────────────────┤
│ 7. throttle:300,1   │ ← Rate Limiting (300 طلب/دقيقة)
└─────────────────────┘
  │
  ▼
Controller → Service → Model → Response
```

### Middleware إضافية (Route-specific):
| Middleware | الاستخدام |
|:---|:---|
| `CheckSubscription` | يتأكد أن المركز له اشتراك فعّال |
| `CheckFeature` | يتأكد أن الباقة تسمح بالميزة المطلوبة |
| `CheckAdminRole` | لوحة Super Admin فقط |
| `ForcePasswordChange` | يُجبر العضو الجديد على تغيير كلمة المرور |
| `EnsureOnboardingCompleted` | يتأكد من إتمام خطوات الإعداد الأولي |

---

## 6. Database Schema (Core Tables)

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│   tenants    │────▶│    users      │────▶│  instructors │
│              │     │  tenant_id   │     │  tenant_id   │
│  id          │     │  role        │     │  user_id     │
│  name        │     └──────┬───────┘     └──────────────┘
│  domain      │            │
│  status      │     ┌──────▼───────┐     ┌──────────────┐
│  settings    │     │  students    │────▶│  enrollments │
└──────┬───────┘     │  tenant_id   │     │  student_id  │
       │             └──────────────┘     │  course_id   │
       │                                  └──────────────┘
       │
┌──────▼───────┐     ┌──────────────┐     ┌──────────────┐
│ subscriptions│     │   courses    │     │   sales      │
│  tenant_id   │     │  tenant_id   │     │  tenant_id   │
│  package_id  │     │  instructor  │     │  student_id  │
│  status      │     └──────────────┘     │  amount      │
│  ends_at     │                          └──────────────┘
└──────────────┘
```

### Key Models (50 model):
- **Core:** Tenant, User, Student, Instructor, Course, Enrollment
- **Finance:** Sale, SaleItem, Subscription, Payment, Expense, Coupon, Refund
- **Academic:** Quiz, Question, Assignment, Schedule, Grade, Stage
- **System:** OperationIssue, BugReport, Ticket, SiteSetting

---

## 7. Payment Architecture

```
                    ┌──────────────┐
                    │ Registration │
                    │  Controller  │
                    └──────┬───────┘
                           │
                    ┌──────▼───────┐
                    │ PaymentFactory│ ← Factory Pattern
                    │  ::make()    │
                    └──────┬───────┘
                           │
              ┌────────────┼────────────┐
              ▼            ▼            ▼
    ┌──────────────┐ ┌──────────┐ ┌──────────┐
    │ PayPalGateway│ │ Paymob   │ │ Demo     │
    │ (USD/EUR)    │ │ (EGP)    │ │ (Testing)│
    └──────┬───────┘ └────┬─────┘ └────┬─────┘
           │              │            │
           └──────────────┼────────────┘
                          ▼
              ┌────────────────────┐
              │ PaymentProcessing  │ ← منطق موحّد
              │    Service         │
              │ - تفعيل الاشتراك  │
              │ - معالجة الكوبون  │
              │ - إشعار Telegram  │
              │ - Activity Log    │
              └────────────────────┘
```

---

## 8. Notification System

```
                    ┌────────────────────┐
                    │   Event Triggers   │
                    │ (Registration,     │
                    │  Payment, Error)   │
                    └────────┬───────────┘
                             │
              ┌──────────────┼──────────────┐
              ▼              ▼              ▼
    ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
    │ Email (SMTP) │ │ Telegram Bot │ │ WhatsApp API │
    │ - Welcome    │ │ - Admin Only │ │ - Per Tenant │
    │ - Onboarding │ │ - Alerts     │ │ - Attendance │
    │ - Team       │ │ - Errors     │ │ - Payments   │
    │              │ │ - Logins     │ │ - Debts      │
    └──────────────┘ └──────────────┘ └──────────────┘
```

---

## 9. Security Layers

```
Internet → Cloudflare (DDoS) → Server
  │
  ├── BasicWAF Middleware        (حظر SQL Injection / XSS patterns)
  ├── SecurityHeaders Middleware (X-Frame-Options, MIME sniffing)
  ├── CSP Middleware             (Content Security Policy)
  ├── Rate Limiting              (300 req/min global, 5/min login)
  ├── CSRF Token                 (كل POST form)
  ├── HTTPS Force                (forceScheme)
  ├── Session Encryption         (SESSION_ENCRYPT=true)
  ├── Spatie Permissions         (RBAC per tenant)
  ├── ForcePasswordChange        (أعضاء الفريق الجدد)
  └── Activity Logging           (13 model tracked)
```

---

## 10. Scheduled Tasks

| المهمة | التوقيت | الوظيفة |
|:---|:---|:---|
| `subscription:check-expiring` | يومياً 8 صباحاً | تنبيه الاشتراكات المنتهية |
| `subscription:auto-renew` | يومياً 9 صباحاً | تجديد تلقائي |
| `finance:daily-report` | يومياً 11 مساءً | تقرير مالي يومي |
| `attendance:reminder` | يومياً 7 صباحاً | تذكير الحضور |

> كل المهام تعمل بـ `withoutOverlapping()` لمنع التداخل.

---

## 11. Environment Variables (Critical)

| المتغير | الوظيفة | ملاحظة |
|:---|:---|:---|
| `APP_KEY` | تشفير كل البيانات | **لا تغيّره** بعد الإنتاج |
| `TENANT_DOMAIN` | الدومين الأساسي | `taalimu.com` |
| `TENANCY_MODE` | طريقة تحديد المستأجر | `subdomain` أو `path` |
| `DB_PASSWORD` | كلمة مرور قاعدة البيانات | يدوّر كل 90 يوم |
| `PAYMOB_API_KEY` | بوابة الدفع المصرية | |
| `PAYPAL_CLIENT_SECRET` | بوابة PayPal | |
| `TELEGRAM_BOT_TOKEN` | إشعارات المدير | |
| `CACHE_STORE` | نوع الكاش | `database` (يمكن `redis`) |
| `QUEUE_CONNECTION` | نوع الـ Queue | `database` (يمكن `redis`) |
