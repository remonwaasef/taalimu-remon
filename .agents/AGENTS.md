# Taalimu.com — Project Rules for AI Assistants

> **هذا الملف يُقرأ تلقائيًا مع كل محادثة جديدة.**
> جميع القواعد الواردة هنا إلزامية ولا يجوز تجاوزها تحت أي ظرف.

---

## 1. Entry Points — نقاط البداية الإلزامية

قبل تنفيذ أي مهمة، اقرأ بالترتيب:
1. `PROJECT_MANIFEST.md` — النظرة العامة الفنية
2. `docs/00_AI_BOOT.md` — تصنيف المهام وتحديد الملفات المطلوبة
3. `docs/01_MASTER_CONTEXT.md` — البنية المعمارية والموديلات والخدمات
4. `.agents/MASTER_PROMPT.md` — دستور الجودة والرؤية (دائمًا)
5. `.agents/DESIGN_SYSTEM.md` — نظام التصميم (للمهام المتعلقة بالواجهات فقط)

**لا تفحص المشروع بالكامل أبدًا.** افحص فقط الملفات المتعلقة مباشرة بالمهمة المطلوبة.

---

## 2. Multi-Tenancy Rules — قواعد تعدد المستأجرين

هذا المشروع يستخدم **Single-Database Multi-Tenancy** مع تقسيم البيانات عبر `tenant_id`.

### القواعد الإلزامية:
- **كل Model ينتمي لمستأجر** يجب أن يستخدم `use \App\Traits\BelongsToTenant;`
- **كل Query** يجب أن يتضمن فلتر `tenant_id` تلقائيًا عبر الـ Trait أو يدويًا في الـ Raw Queries
- **لا تنشئ Model جديد بدون** تحديد علاقة `belongsTo(Tenant::class)` إذا كان البيانات خاصة بمستأجر
- **الـ Middleware `IdentifyTenant`** (`app/Http/Middleware/IdentifyTenant.php`) يحدد المستأجر من الـ Subdomain — لا تعدل منطقه إلا بموافقة صريحة
- **Helper Functions**: استخدم `tenant_url()`, `current_tenant()`, `tenant_route()` المعرّفة في `app/Helpers/helpers.php`
- **الكاش**: ضمّن `tenant_id` في مفتاح الكاش دائمًا (مثال: `"tenant_{$tenant->id}_dashboard"`)

### أمثلة على الاستخدام الصحيح:
```php
// ✅ صحيح — Model مع BelongsToTenant
class NewModel extends Model
{
    use \App\Traits\BelongsToTenant;

    protected $fillable = ['tenant_id', 'name', /* ... */];

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }
}

// ❌ خطأ — Model بدون BelongsToTenant يمكن أن يسرّب بيانات بين المستأجرين
class NewModel extends Model
{
    // ⚠️ بيانات المستأجرين ستختلط
}
```

---

## 3. Architecture Rules — قواعد البنية المعمارية

### Modular Monolith
المشروع مقسم إلى 6 Modules في `Modules/`:
| Module | المسؤولية |
|---|---|
| `Admin` | لوحة Super Admin، إدارة المستأجرين، الباقات، النسخ الاحتياطي |
| `Center` | لوحة المركز التعليمي (طلاب، كورسات، حضور، مبيعات، اختبارات) |
| `Instructor` | لوحة المدرس (حضور، جدول) |
| `Campus` | إدارة الفروع |
| `Tenancy` | ربط الدومينات وتحديد المستأجر |
| `Api` | واجهات REST API عبر Sanctum |

### قواعد الهيكلة:
- **لا تنقل كود بين الـ Modules** إلا بموافقة صريحة
- **الكود المشترك** يوضع في `app/` (Models, Services, Traits, Helpers)
- **كل Module** له routes و controllers و views مستقلة
- **لا تنشئ Module جديد** بدون الحاجة الفعلية — أعد استخدام الموجود أولاً

### Thin Controllers → Service Layer:
```php
// ✅ صحيح — Controller رفيع يفوّض للـ Service
public function store(StoreStudentRequest $request)
{
    $student = app(StudentService::class)->create($request->validated());
    return redirect()->route('center.students.index')
        ->with('success', __('messages.student_created'));
}

// ❌ خطأ — Controller سميك يحتوي على Business Logic
public function store(Request $request)
{
    $validated = $request->validate([/* ... */]);
    DB::transaction(function () use ($validated) {
        $user = User::create([/* ... */]);
        Student::create([/* ... */]);
        // 50+ lines of business logic...
    });
}
```

---

## 4. Database Rules — قواعد قاعدة البيانات

### إلزامي:
- **كل تعديل على الـ Schema** يتم عبر Laravel Migrations فقط (`database/migrations/`)
- **لا تعدل الـ Database مباشرة** عبر SQL يدوي أبدًا
- **استخدم `DB::transaction()`** لكل عملية تشمل أكثر من جدول واحد
- **أضف Indexes** على أعمدة `tenant_id`, `created_at`, والأعمدة المستخدمة في `WHERE` و `ORDER BY`
- **أضف Foreign Key Constraints** مع `cascadeOnDelete()` عند الحاجة
- **لا تحذف أعمدة** إلا بعد التأكد من عدم استخدامها في الكود

### قالب Migration:
```php
Schema::create('table_name', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    // ... الأعمدة الأخرى
    $table->timestamps();

    // Indexes
    $table->index(['tenant_id', 'created_at']);
});
```

### N+1 Queries Prevention:
```php
// ✅ صحيح — Eager Loading
$students = Student::with(['user', 'enrollments.course'])->where('tenant_id', $tenantId)->get();

// ❌ خطأ — N+1 Query
$students = Student::where('tenant_id', $tenantId)->get();
foreach ($students as $student) {
    echo $student->user->name; // Query لكل طالب!
}
```

---

## 5. Security Rules — قواعد الأمان

### إلزامي في كل Request:
- **Validation**: استخدم Form Request Classes أو `$request->validate()` — لا تثق بمدخلات المستخدم أبدًا
- **Authorization**: تحقق من الصلاحيات عبر Policies (`$this->authorize()`) أو Middleware (`CheckAdminRole`, `CheckSubscription`)
- **CSRF**: موجود تلقائيًا في Blade Forms — لا تعطّله
- **Rate Limiting**: استخدم `throttle:` middleware على جميع الـ endpoints الحساسة
- **XSS**: استخدم `{{ }}` في Blade (يعمل escape تلقائيًا) — لا تستخدم `{!! !!}` إلا مع `Purifier::clean()`

### ممنوع نهائيًا:
- كتابة كلمات مرور أو مفاتيح API في الكود — استخدم `.env` و `config()`
- تعطيل `BasicWAF` أو `ContentSecurityPolicy` middleware
- استخدام `$request->all()` مباشرة في `create()` أو `update()` — استخدم `$request->validated()` فقط
- كتابة Raw SQL بدون Parameter Binding:
```php
// ✅ صحيح
DB::select('SELECT * FROM users WHERE email = ?', [$email]);

// ❌ خطأ — SQL Injection
DB::select("SELECT * FROM users WHERE email = '$email'");
```

### الأدوار والصلاحيات (Spatie Permission):
| الدور | النطاق | الحماية |
|---|---|---|
| `super_admin` | `/admin` | `CheckAdminRole` middleware |
| `center_owner` / `admin` | Tenant subdomain | `IdentifyTenant` + `CheckSubscription` |
| `instructor` | Tenant subdomain | `IdentifyTenant` + `auth` |
| `student` | Tenant subdomain | `IdentifyTenant` + `auth` |
| `guardian` | Tenant subdomain | `IdentifyTenant` + `auth` |

---

## 6. Code Quality Standards — معايير جودة الكود

### التنسيق:
- **PSR-12** مع **Laravel Pint** (`vendor/bin/pint`)
- **Single Responsibility**: كل Function تؤدي وظيفة واحدة فقط
- **Early Returns**: استخدم guard clauses لتقليل التداخل
- **Descriptive Names**: `$totalStudentEnrollments` وليس `$data` أو `$x`
- **Comments**: اشرح "لماذا" وليس "ماذا" — الكود الواضح لا يحتاج تعليقات

### أمثلة:
```php
// ✅ صحيح — Early Return
public function enroll(Student $student, Course $course): bool
{
    if ($student->isAlreadyEnrolled($course)) {
        return false;
    }

    if (! $this->tenant->hasFeature('max_students')) {
        throw new SubscriptionLimitException('Student limit reached');
    }

    return DB::transaction(function () use ($student, $course) {
        // enrollment logic...
    });
}

// ❌ خطأ — Nested Conditions
public function enroll($student, $course)
{
    if (! $student->isAlreadyEnrolled($course)) {
        if ($this->tenant->hasFeature('max_students')) {
            // deep nesting...
        }
    }
}
```

---

## 7. Localization Rules — قواعد الترجمة

المشروع يدعم **3 لغات**: العربية (`ar`), الإنجليزية (`en`), الفرنسية (`fr`).

### القواعد:
- **لا تكتب نصوصًا مباشرة في الكود أو الـ Views** — استخدم ملفات الترجمة:
  - `resources/lang/ar/`
  - `resources/lang/en/`
  - `resources/lang/fr/`
- **في Blade**: استخدم `{{ __('messages.key') }}` أو `@lang('messages.key')`
- **في PHP**: استخدم `__('messages.key')` أو `trans('messages.key')`
- **عند إضافة نص جديد**: أضفه في الـ 3 لغات معًا
- **الاتجاه**: الواجهات يجب أن تدعم RTL (العربية) و LTR (الإنجليزية والفرنسية)

---

## 8. Frontend Rules — قواعد الواجهات

### نظام التصميم:
- **ارجع دائمًا لـ** `.agents/DESIGN_SYSTEM.md` قبل أي تعديل على الواجهة
- **استخدم Design Tokens** من `resources/css/design-tokens.css` — لا تكتب ألوانًا يدوية
- **اللون الأساسي**: `#2E8B83` — لا تغيره أبدًا
- **الخطوط**: Cairo (عربي) و Inter (إنجليزي)
- **ادعم Dark Mode** عبر CSS variables المعرّفة في `design-tokens.css`

### التقنيات:
- **Blade + Alpine.js**: للصفحات العادية (معظم المشروع)
- **Inertia.js + React 19**: للداشبوردات التفاعلية المتقدمة
- **TailwindCSS 3.4 + Bootstrap 5.3**: يعملان معًا — لا تحذف أيًا منهما
- **Vite 7**: للـ Build — لا تغير إعدادات `vite.config.js` بدون سبب

### ممنوع:
- تغيير الألوان أو التصميم عند تعديل الأسعار أو الاشتراكات
- إضافة مكتبات CSS أو JS جديدة بدون مراجعة
- كتابة Inline Styles — استخدم CSS classes

---

## 9. Performance Rules — قواعد الأداء

- **Eager Loading**: استخدم `with()` دائمًا لتجنب N+1 Queries
- **Cache**: استخدم `Cache::remember()` للبيانات الثقيلة — ضمّن `tenant_id` في المفتاح
- **Pagination**: لا تستخدم `get()` على جداول كبيرة — استخدم `paginate()` أو `simplePaginate()`
- **Queue Jobs**: العمليات الثقيلة (إرسال إشعارات، تقارير PDF) يجب أن تُنفذ عبر Jobs
- **Assets**: تأكد من Minification لملفات JS و CSS عبر Vite قبل النشر

---

## 10. Documentation Sync — مزامنة التوثيق

بعد **كل تغيير مكتمل**، يجب تحديث:

| إذا عدّلت... | حدّث... |
|---|---|
| Database Schema / Migrations | `docs/11_DATABASE.md` + `docs/12_MODELS.md` |
| Routes | `docs/15_ROUTES.md` |
| APIs / Webhooks | `docs/16_API.md` |
| Business Logic | `docs/17_BUSINESS_LOGIC.md` |
| UI / Views | `docs/23_UI_GUIDE.md` + `docs/21_FRONTEND.md` |
| Authentication | `docs/18_AUTHENTICATION.md` |
| Permissions | `docs/19_AUTHORIZATION.md` + `docs/20_PERMISSIONS.md` |
| Dependencies | `docs/25_DEPENDENCIES.md` |
| Architecture / Modules | `docs/07_ARCHITECTURE.md` + `docs/10_MODULES.md` |

**دائمًا** حدّث:
1. `docs/34_CHANGELOG.md` — أضف سطرًا تحت `[Unreleased]`
2. `docs/01_MASTER_CONTEXT.md` — إذا تأثرت النظرة العامة
3. `docs/02_CURRENT_STATE.md` — إذا تغيرت حالة المشروع

---

## 11. Git & Deployment Rules — قواعد Git والنشر

- **رسائل Commit**: اكتبها باللغة الإنجليزية بصيغة واضحة:
  - `feat: add student export to PDF`
  - `fix: resolve N+1 query in attendance list`
  - `refactor: move billing logic to FinanceService`
- **لا ترفع ملفات مؤقتة**: `.env`, `scratch_*`, `*.log`, `node_modules/`, `vendor/`
- **لا ترفع ملفات كبيرة** (صور، PDF، فيديو) للـ Repository — استخدم Storage
- **اختبر محليًا** قبل أي نشر: `php artisan test` و `npx playwright test`

---

## 12. Existing Patterns Reference — أنماط موجودة للاستخدام

### Traits المتوفرة (`app/Traits/`):
| Trait | الوظيفة |
|---|---|
| `BelongsToTenant` | عزل بيانات المستأجر تلقائيًا |
| `ClearsDashboardCache` | مسح كاش الداشبورد عند التحديث |
| `HandlesFileUploads` | رفع الملفات بشكل آمن |
| `HasLocaleResolution` | تحديد لغة المستخدم |
| `HasRoleCheck` | فحص دور المستخدم |
| `IsImmutable` | منع التعديل على Records معينة |
| `ManagesTokens` | إدارة Sanctum tokens |

### Services المتوفرة (`app/Services/`):
قبل إنشاء Service جديد، تحقق من وجود وظيفة مشابهة:
- `FinanceService` — المبيعات والفواتير والمحاسبة
- `AttendanceService` — الحضور وتنبيهات الأهل
- `SubscriptionService` — إدارة الباقات والميزات
- `CourseService` — إدارة الكورسات
- `StudentService` — إدارة الطلاب
- `WhatsAppService` — إرسال رسائل WhatsApp
- `TelegramService` — تقارير Telegram
- `SettingsService` — إعدادات المستأجر
- `TenantRegistrationService` — تسجيل مستأجر جديد
- `PayoutService` — حساب وتنفيذ مدفوعات المدرسين
- `OperationIssueService` — تسجيل أخطاء الإنتاج

### Middleware المتوفرة (`app/Http/Middleware/`):
| Middleware | الوظيفة |
|---|---|
| `IdentifyTenant` | تحديد المستأجر من الـ Subdomain |
| `CheckAdminRole` | التحقق من صلاحية Super Admin |
| `CheckSubscription` | التحقق من وجود اشتراك فعّال |
| `CheckFeature` | التحقق من توفر ميزة في الباقة |
| `BasicWAF` | جدار حماية أساسي للطلبات |
| `ContentSecurityPolicy` | حماية CSP Headers |
| `SetLocale` | تحديد لغة الواجهة |
| `ForcePasswordChange` | إجبار تغيير كلمة المرور |
| `EnsureOnboardingCompleted` | التأكد من إكمال الإعداد الأولي |
| `TwoFactorMiddleware` | التحقق بخطوتين |

---

*Last updated: 2026-08-07*
