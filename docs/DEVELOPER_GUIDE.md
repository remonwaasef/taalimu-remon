# 📘 دليل فريق التطوير — منصة Taalimu

> آخر تحديث: مايو 2026

---

## 📋 جدول المحتويات

1. [بيئة التطوير المحلية](#1-بيئة-التطوير-المحلية)
2. [هيكل المشروع](#2-هيكل-المشروع)
3. [معايير كتابة الكود](#3-معايير-كتابة-الكود)
4. [مراجعة الكود (Code Review)](#4-مراجعة-الكود)
5. [قواعد الأمان الإلزامية](#5-قواعد-الأمان-الإلزامية)
6. [قواعد قاعدة البيانات](#6-قواعد-قاعدة-البيانات)
7. [الاختبارات (Testing)](#7-الاختبارات)
8. [النشر (Deployment)](#8-النشر)
9. [قائمة المراجعة قبل الـ PR](#9-قائمة-المراجعة-قبل-الـ-pr)

---

## 1. بيئة التطوير المحلية

### المتطلبات
- PHP 8.2+
- MySQL 8.0+ أو MariaDB 10.6+
- Composer 2.x
- Node.js 18+ و npm 9+
- Redis (اختياري محلياً)

### الإعداد الأولي
```bash
# 1. استنساخ المشروع
git clone <repo-url>
cd edu

# 2. تثبيت المكتبات
composer install
npm install

# 3. إعداد البيئة
cp .env.example .env
php artisan key:generate

# 4. قاعدة البيانات
php artisan migrate --seed

# 5. تشغيل السيرفر
php artisan serve
npm run dev
```

### ⚠️ قواعد مهمة للبيئة المحلية
- **ممنوع** نسخ `.env` الإنتاج واستخدامه محلياً
- **ممنوع** استخدام بيانات حقيقية في التطوير — استخدم `php artisan db:seed`
- **APP_DEBUG=true** فقط في بيئة `local`

---

## 2. هيكل المشروع

```
edu/
├── app/
│   ├── Http/Controllers/     ← Controllers رئيسية (Payment, Registration, Admin)
│   ├── Models/                ← جميع الـ Models مع LogsActivity
│   ├── Services/              ← Business Logic (PaymentProcessingService, TelegramService)
│   ├── Traits/                ← Traits مشتركة (IdentifyTenant, ClearsDashboardCache)
│   ├── Queries/               ← استعلامات تحليلية ثقيلة (CenterAnalyticsQuery)
│   └── Observers/             ← Model Observers
├── Modules/
│   ├── Center/                ← واجهة المركز التعليمي (الأكبر)
│   ├── Instructor/            ← واجهة المدرّس
│   ├── Admin/                 ← لوحة الإدارة
│   └── Tenancy/               ← نظام Multi-Tenancy
├── config/
│   └── services.php           ← جميع مفاتيح الخدمات الخارجية (env() فقط هنا)
├── resources/lang/
│   ├── ar/                    ← العربية
│   ├── en/                    ← الإنجليزية
│   └── fr/                    ← الفرنسية
└── docs/
    ├── COMMIT_STANDARDS.md    ← معايير رسائل الـ Commits
    └── DEVELOPER_GUIDE.md     ← هذا الملف
```

---

## 3. معايير كتابة الكود

### 3.1 التسمية
| العنصر | القاعدة | مثال صحيح ❌→✅ |
|:---|:---|:---|
| متغيرات | camelCase وصفية | `$data` → `$totalUserOrders` |
| Functions | camelCase تبدأ بفعل | `process()` → `processPayment()` |
| Classes | PascalCase | `PaymentProcessingService` |
| جداول DB | snake_case جمع | `quiz_attempts` |
| Routes | kebab-case | `center.students.index` |

### 3.2 Single Responsibility
```php
// ❌ خطأ — دالة تعمل أكثر من شيء واحد
public function store(Request $request) {
    // validation + create user + send email + log + redirect
    // كل هذا في 100 سطر!
}

// ✅ صحيح — كل دالة لها مسؤولية واحدة
public function store(StoreStudentRequest $request) {
    $student = $this->studentService->create($request->validated());
    return redirect()->route('center.students.show', $student);
}
```

### 3.3 ممنوعات صارمة
| الممنوع | البديل |
|:---|:---|
| `env()` خارج ملفات `config/` | `config('services.xxx')` |
| `bcrypt('password')` أو أي كلمة مرور ثابتة | `Str::random(12)` |
| `dd()` أو `dump()` في كود الإنتاج | `Log::debug()` |
| `Artisan::call('migrate')` في Controllers | تشغيل CLI فقط |
| `::all()` بدون تحديد أعمدة | `::select(['id', 'name'])` |
| نصوص عربية/إنجليزية في الكود | `__('file.key')` ملفات الترجمة |

### 3.4 الـ Traits والـ Services
- **الكود المشترك بين Controllers** ← ينتقل لـ Service Class
- **الكود المشترك بين Models** ← ينتقل لـ Trait
- **الاستعلامات المعقدة** ← تنتقل لـ Query Class في `app/Queries/`

---

## 4. مراجعة الكود (Code Review)

### من يراجع؟
- كل PR يجب أن يُراجع من شخص واحد على الأقل قبل الدمج
- أي PR يحتوي `security` في العنوان يحتاج **مراجعتين**

### ماذا نراجع؟
| النقطة | السؤال |
|:---|:---|
| الأمان | هل يوجد SQL Injection؟ هل CSRF Token موجود؟ |
| الأداء | هل يوجد N+1 Query؟ هل يوجد `::all()` غير ضروري؟ |
| الترجمة | هل كل النصوص في ملفات `lang/`؟ |
| الـ Tests | هل يوجد اختبار للمسار الحرج؟ |
| التوثيق | هل الكود المعقد عليه تعليقات؟ |

### نموذج تعليق المراجعة
```
✅ LGTM (Looks Good To Me) - جاهز للدمج
⚠️ Needs Changes - يحتاج تعديلات (مع شرح)
🔴 Security Issue - مشكلة أمنية يجب حلها فوراً
```

---

## 5. قواعد الأمان الإلزامية

### 5.1 المدخلات (Input Validation)
```php
// ✅ دائماً استخدم Form Request
class StoreStudentRequest extends FormRequest {
    public function rules(): array {
        return [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|regex:/^[0-9]{10,15}$/',
            'file'  => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ];
    }
}
```

### 5.2 الاستعلامات
```php
// ❌ ممنوع — SQL Injection
DB::select("SELECT * FROM students WHERE name = '$name'");

// ✅ صحيح — Parameterized Query
Student::where('name', $name)->get();
DB::select("SELECT * FROM students WHERE name = ?", [$name]);
```

### 5.3 الملفات المرفوعة
- كل ملف مرفوع يمر عبر `HandlesFileUploads` Trait الذي يفحص MIME Type والحجم
- **ممنوع** تخزين ملفات في `public/` مباشرة — استخدم `storage/app/`
- **ممنوع** الوثوق بامتداد الملف — الـ Trait يفحص المحتوى الفعلي

### 5.4 مفاتيح API
- **ممنوع** كتابة أي مفتاح سري في الكود — يجب أن يكون في `.env`
- **ممنوع** عمل commit لملف `.env`
- عند الشك أن مفتاح تسرّب → **غيّره فوراً** من لوحة التحكم الخارجية

---

## 6. قواعد قاعدة البيانات

### 6.1 الـ Migrations
```bash
# ✅ دائماً أنشئ migration لأي تغيير في البنية
php artisan make:migration add_grade_level_to_students_table

# ❌ ممنوع تعديل migration قديمة بعد تشغيلها على السيرفر
# ❌ ممنوع استخدام Artisan::call('migrate') في Controllers
```

### 6.2 النسخ الاحتياطي
- **قبل** أي migration جديدة على الإنتاج → خذ نسخة احتياطية
- اختبر الـ migration في بيئة التطوير أولاً

### 6.3 الفهارس (Indexes)
```php
// ✅ أضف Index لأي عمود يُستخدم في WHERE أو JOIN بكثرة
$table->index(['tenant_id', 'student_id']);
$table->index('created_at');
```

---

## 7. الاختبارات (Testing)

### 7.1 المسارات الحرجة (يجب أن يكون لها اختبار)
- تسجيل الدخول / إنشاء حساب
- عمليات الدفع (PayPal, Paymob)
- تفعيل / تجديد الاشتراكات
- رفع الملفات

### 7.2 القواعد
```php
// ✅ استخدم Factories — ممنوع استخدام بيانات حقيقية
$student = Student::factory()->create();

// ✅ استخدم RefreshDatabase أو DatabaseTransactions
use RefreshDatabase;

// ❌ ممنوع الاتصال بخدمات خارجية حقيقية في الاختبارات
Http::fake([
    'api.telegram.org/*' => Http::response(['ok' => true]),
]);
```

---

## 8. النشر (Deployment)

### قائمة أوامر النشر
```bash
# 1. سحب آخر التحديثات
git pull origin main

# 2. تحديث المكتبات
composer install --no-dev --optimize-autoloader

# 3. تشغيل Migrations
php artisan migrate --force

# 4. تفعيل الكاش
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. إعادة تشغيل Queue Workers
php artisan queue:restart

# 6. ضغط الـ Assets
npm run build
```

### ⚠️ قبل كل نشر
- [ ] تم اختبار الكود محلياً
- [ ] تم أخذ نسخة احتياطية من قاعدة البيانات
- [ ] لا يوجد `dd()` أو `dump()` في الكود
- [ ] `APP_DEBUG=false` في `.env` الإنتاج
- [ ] تم مراجعة الـ PR من شخص آخر

---

## 9. قائمة المراجعة قبل الـ PR

```
□ الكود يتبع معايير التسمية المذكورة أعلاه
□ لا يوجد env() خارج ملفات config/
□ لا يوجد كلمات مرور أو مفاتيح مكتوبة في الكود
□ جميع النصوص في ملفات الترجمة (lang/)
□ لا يوجد dd() أو dump() أو var_dump()
□ كل Function لها مسؤولية واحدة فقط
□ الاستعلامات لا تحتوي على ::all() بدون select
□ تم كتابة تعليق للكود المعقد
□ تم اختبار المسار الحرج
□ رسالة الـ Commit تتبع المعايير في COMMIT_STANDARDS.md
```

---

## 10. جهات الاتصال والمسؤوليات

| المسؤولية | الشخص | ملاحظة |
|:---|:---|:---|
| مراجعة الأمان | مدير المشروع | أي PR أمني يحتاج موافقته |
| النشر للإنتاج | DevOps / مدير المشروع | ممنوع النشر المباشر بدون مراجعة |
| تدوير المفاتيح | مدير المشروع | يتم كل 90 يوم أو عند أي شك |
| مراجعة الصلاحيات | مدير المشروع | تتم شهرياً عبر لوحة Spatie |
