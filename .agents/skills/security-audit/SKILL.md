---
name: Security Audit Checklist
description: Comprehensive security audit skill for the Taalimu platform covering OWASP Top 10, tenant isolation verification, authentication flows, API security, rate limiting, and sensitive data protection.
---

# Security Audit Checklist — مهارة الفحص الأمني الشامل

## متى تُستخدم هذه المهارة؟
عند طلب فحص أمني، مراجعة كود جديد، أو قبل نشر إصدار جديد.

---

## 1. فحص عزل المستأجرين (Tenant Isolation)

### الأكثر أهمية — تسريب البيانات بين المستأجرين:

- [ ] **كل Model خاص بمستأجر** يستخدم `BelongsToTenant` trait
- [ ] **كل Query** يتضمن فلتر `tenant_id` (تلقائيًا عبر Trait أو يدويًا)
- [ ] **Raw Queries** تتضمن `WHERE tenant_id = ?` مع Parameter Binding
- [ ] **Cache Keys** تتضمن `tenant_id` (مثال: `"tenant_{id}_data"`)
- [ ] **File Uploads** محفوظة في مجلدات منفصلة لكل مستأجر
- [ ] **الـ `IdentifyTenant` middleware** يعمل على جميع الـ tenant routes

### كيفية الفحص:
```bash
# ابحث عن Models بدون BelongsToTenant
grep -rL "BelongsToTenant" app/Models/ --include="*.php" | grep -v "User.php\|Tenant.php\|Role.php\|Feature.php\|Package.php\|PersonalAccessToken.php"

# ابحث عن Raw Queries بدون tenant_id
grep -rn "DB::select\|DB::raw\|DB::statement" app/ Modules/ --include="*.php"

# ابحث عن Cache بدون tenant_id في المفتاح
grep -rn "Cache::remember\|Cache::put\|Cache::get" app/ Modules/ --include="*.php" | grep -v "tenant"
```

---

## 2. فحص المصادقة (Authentication)

- [ ] **كل route محمي** بـ `auth` middleware (ما عدا الصفحات العامة)
- [ ] **Rate Limiting** مفعّل على `/login` (`throttle:login`)
- [ ] **Rate Limiting** مفعّل على `/register` (`throttle:registration`)
- [ ] **Rate Limiting** مفعّل على OTP endpoints (`throttle:10,5`)
- [ ] **Password Hashing** يستخدم `bcrypt` أو `argon2` (عبر Laravel's `hashed` cast)
- [ ] **2FA (Google Authenticator)** يعمل بشكل صحيح عبر `TwoFactorMiddleware`
- [ ] **Force Password Change** يعمل عبر `ForcePasswordChange` middleware
- [ ] **Session Timeout** مفعّل لتسجيل الخروج عند الخمول
- [ ] **`google2fa_secret`** مشفر عبر `encrypted` cast
- [ ] **`phone_verification_code`** مشفر عبر `encrypted` cast

---

## 3. فحص التفويض (Authorization)

- [ ] **كل Controller action** يستخدم `$this->authorize()` أو Policy
- [ ] **Super Admin routes** محمية بـ `CheckAdminRole` middleware
- [ ] **Tenant routes** محمية بـ `IdentifyTenant` + `CheckSubscription`
- [ ] **Feature-gated routes** تستخدم `CheckFeature` middleware
- [ ] **لا يوجد** تجاوز للصلاحيات عبر manipulating `tenant_id` في الـ request

### الأدوار والصلاحيات:
| الدور | Middleware المطلوب |
|---|---|
| `super_admin` | `auth` + `CheckAdminRole` |
| `center_owner` | `auth` + `IdentifyTenant` + `CheckSubscription` |
| `instructor` | `auth` + `IdentifyTenant` |
| `student` | `auth` + `IdentifyTenant` |
| `guardian` | `auth` + `IdentifyTenant` |

---

## 4. فحص المدخلات (Input Validation)

- [ ] **كل Controller** يستخدم Form Request أو `$request->validate()`
- [ ] **لا يوجد** `$request->all()` في `create()` أو `update()` — استخدم `$request->validated()`
- [ ] **File Uploads**: يتم فحص الامتداد والحجم والنوع
- [ ] **HTML Input**: يُنظّف عبر `Purifier::clean()` قبل الحفظ
- [ ] **Numeric Input**: يُتحقق منه عبر `numeric`, `integer`, `min`, `max`

### كيفية الفحص:
```bash
# ابحث عن استخدام $request->all() الخطير
grep -rn "\->all()" app/Http/Controllers/ Modules/*/app/Http/Controllers/ --include="*.php" | grep -v "validated\|only\|except"

# ابحث عن {!! !!} بدون Purifier
grep -rn "{!!" Modules/*/resources/views/ resources/views/ --include="*.blade.php" | grep -v "Purifier\|@vite\|@livewire\|@inertia\|csrf\|method"
```

---

## 5. فحص SQL Injection

- [ ] **لا يوجد** string concatenation في SQL queries
- [ ] **كل Raw Query** يستخدم Parameter Binding (`?` أو named parameters)
- [ ] **`DB::raw()`** لا يتضمن مدخلات مستخدم غير مفلترة

### كيفية الفحص:
```bash
# ابحث عن SQL injection محتمل
grep -rn "DB::raw\|whereRaw\|selectRaw\|orderByRaw" app/ Modules/ --include="*.php"
```

### أمثلة:
```php
// ✅ آمن
DB::select('SELECT * FROM users WHERE tenant_id = ? AND email = ?', [$tenantId, $email]);
User::whereRaw('LOWER(email) = ?', [strtolower($email)])->first();

// ❌ خطير — SQL Injection
DB::select("SELECT * FROM users WHERE email = '$email'");
User::whereRaw("name LIKE '%$search%'")->get();
```

---

## 6. فحص XSS (Cross-Site Scripting)

- [ ] **Blade Views** تستخدم `{{ }}` (escaped) وليس `{!! !!}` (unescaped)
- [ ] **كل محتوى HTML** يُنظّف عبر `mews/purifier` قبل العرض
- [ ] **ContentSecurityPolicy middleware** مفعّل
- [ ] **JavaScript inline** لا يحتوي على بيانات مستخدم غير مفلترة

---

## 7. فحص CSRF

- [ ] **كل Form** يتضمن `@csrf` directive
- [ ] **AJAX requests** ترسل `X-CSRF-TOKEN` header
- [ ] **Webhook endpoints** (PayPal, Paymob) مستثناة بشكل صحيح مع تحقق بديل

---

## 8. فحص ملف .env والمفاتيح السرية

- [ ] **`.env` في `.gitignore`** — لا يُرفع للـ Repository
- [ ] **لا توجد مفاتيح API أو كلمات مرور** مكتوبة مباشرة في الكود
- [ ] **`APP_DEBUG=false`** في الإنتاج
- [ ] **`APP_ENV=production`** في الإنتاج
- [ ] **Tenant Settings** مشفرة عبر `EncryptedSettings` cast
- [ ] **Session driver** ليس `file` في الإنتاج (استخدم `database` أو `redis`)

### كيفية الفحص:
```bash
# ابحث عن مفاتيح مكتوبة في الكود
grep -rn "sk_live\|sk_test\|api_key\|api_secret\|password.*=.*['\"]" app/ Modules/ config/ --include="*.php" | grep -v "env(\|config(\|\.env\|validation\|password_confirmation\|password_reset\|fillable\|rules"
```

---

## 9. فحص Rate Limiting

- [ ] **Login**: `throttle:login` (5 محاولات / دقيقة)
- [ ] **Registration**: `throttle:registration`
- [ ] **API endpoints**: `throttle:api` أو محدد
- [ ] **OTP endpoints**: `throttle:10,5`
- [ ] **Coupon validation**: `throttle:coupons`
- [ ] **Global routes**: `throttle:global`
- [ ] **`BasicWAF` middleware** مفعّل لفلترة الطلبات الخبيثة

---

## 10. فحص الملفات المرفوعة

- [ ] **Allowed extensions**: يتم تحديد الامتدادات المسموحة (jpg, png, pdf, etc.)
- [ ] **Max file size**: محدد بحد أقصى معقول
- [ ] **MIME type validation**: يتم التحقق من النوع الفعلي وليس الامتداد فقط
- [ ] **Storage path**: خارج `public/` مع access control
- [ ] **Trait `HandlesFileUploads`**: يُستخدم لتوحيد عملية الرفع

---

## 11. فحص الـ Headers الأمنية

- [ ] **HTTPS فقط** في الإنتاج
- [ ] **Content-Security-Policy** عبر `ContentSecurityPolicy` middleware
- [ ] **X-Content-Type-Options: nosniff**
- [ ] **X-Frame-Options: DENY** أو **SAMEORIGIN**
- [ ] **Strict-Transport-Security** (HSTS)

---

## 12. فحص الـ Logging والمراقبة

- [ ] **Sentry** مفعّل لتسجيل الأخطاء في الإنتاج (`sentry/sentry-laravel`)
- [ ] **Activity Log** يسجل العمليات الحساسة (`spatie/laravel-activitylog`)
- [ ] **لا يتم تسجيل** بيانات حساسة (كلمات مرور، tokens) في الـ logs
- [ ] **OperationIssueService** يلتقط الأخطاء القاتلة تلقائيًا

---

## تقرير الفحص الأمني — القالب

```markdown
# Security Audit Report — [التاريخ]

## ملخص
- إجمالي الفحوصات: X
- ناجح: X
- يحتاج إصلاح: X
- حرج: X

## النتائج الحرجة
1. [وصف المشكلة] — [الملف] — [الحل المقترح]

## النتائج المتوسطة
1. [وصف المشكلة] — [الملف] — [الحل المقترح]

## التوصيات
1. [توصية]
```
