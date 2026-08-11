# دليل الإعدادات الموحدة للتطوير والإنتاج

## نظرة عامة
هذا الدليل يوضح كيفية إعداد التطبيق للعمل في بيئة التطوير المحلية وعند الرفع للسيرفر (الإنتاج) بطريقة موحدة.

---

## الإعدادات في ملف `.env`

### 1. بيئة التطوير (Development - على جهازك المحلي)

عند استخدام `php artisan serve` على المنفذ 8000، استخدم الإعدادات التالية:

```env
APP_NAME="Edu SaaS"
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_URL=http://localhost:8000

# إعدادات قاعدة البيانات
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edu_saas
DB_USERNAME=root
DB_PASSWORD=

# إعدادات الجلسات (Sessions)
SESSION_DRIVER=redis
SESSION_CONNECTION=session
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

# إعدادات الكاش والجوهرة (Cache & Queue)
CACHE_STORE=redis
QUEUE_CONNECTION=redis

# إعدادات Redis
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
REDIS_CACHE_DB=1
REDIS_SESSION_DB=2

# إعدادات البحث النصي (محلياً: database/LIKE — إنتاجياً: meilisearch)
SCOUT_DRIVER=database
SCOUT_QUEUE=true
SCOUT_SOFT_DELETE=true
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=null

# دومين المستأجرين الأساسي
TENANT_DOMAIN=localhost
```

**ملاحظات مهمة للتطوير:**
- `APP_URL` هو الرابط الأساسي (مثلاً `http://localhost:8000`).
- `TENANT_DOMAIN` يجب أن يكون `localhost` في جهازك المحلي.
- **تحديث جديد**: النظام الآن يكتشف المنفذ (Port) تلقائياً، لذا لا داعي للقلق بشأن المنفذ في الروابط.
- `SESSION_DOMAIN` يُفضل تركه `null` في البيئة المحلية.

---

### 2. بيئة الإنتاج (Production - على السيرفر الحقيقي)

عند رفع التطبيق على سيرفر حقيقي (مثل Hostinger أو AWS)، استخدم:

```env
APP_URL=https://yourdomain.com

# إعدادات قاعدة البيانات
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_production_database
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# إعدادات الجلسات
SESSION_DRIVER=redis
SESSION_CONNECTION=session
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.yourdomain.com

# إعدادات الكاش والجوهرة (Cache & Queue)
CACHE_STORE=redis
QUEUE_CONNECTION=redis

# إعدادات Redis (phpredis على السيرفر)
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
REDIS_CACHE_DB=1
REDIS_SESSION_DB=2

# إعدادات البحث النصي (Meilisearch)
SCOUT_DRIVER=meilisearch
SCOUT_QUEUE=true
SCOUT_SOFT_DELETE=true
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=your_master_key

# دومين المستأجرين الأساسي (النطاق الرئيسي)
TENANT_DOMAIN=yourdomain.com
```

**ملاحظات مهمة للإنتاج:**
- `APP_URL` يجب أن يكون الرابط الرئيسي (مثلاً `https://yourdomain.com`).
- `TENANT_DOMAIN` يجب أن يكون النطاق الخاص بك (مثلاً `yourdomain.com`).
- `SESSION_DOMAIN` يجب أن يكون `.yourdomain.com` (بدءاً بنقطة) ليعمل تسجيل الدخول على جميع النطاقات الفرعية.
- **تنبيه**: تم إلغاء الحاجة لمتغير `TENANT_ROUTE_PATTERN` يدوياً، حيث أصبح النظام يستنتجه تلقائياً.

---

## كيف يعمل النظام الموحد؟

### الكود في `Modules/Center/routes/web.php`:
Route::domain(config('app.tenant_domain') == 'localhost' ? '{tenant}.localhost' : '{tenant}.' . config('app.tenant_domain'))
    ->middleware([\App\Http\Middleware\IdentifyTenant::class])
    ->group(function () {
        // مسارات المستأجرين هنا
    });
```

هذا الكود يعتمد كلياً على `TENANT_DOMAIN` من ملف `.env`:
- في التطوير: يُنتج `{tenant}.localhost`.
- في الإنتاج: يُنتج `{tenant}.yourdomain.com`.
- لم يعد هناك حاجة لكتابة رقم المنفذ `:8000` يدوياً في أي مكان.

---

## خطوات النشر على السيرفر

### 1. رفع الملفات
```bash
# قم برفع جميع ملفات المشروع عدا:
# - .env (سيتم إنشاؤه يدوياً)
# - /vendor (سيتم تثبيته على السيرفر)
# - /node_modules (سيتم تثبيته على السيرفر)
# - /storage/logs/* (سيتم إنشاؤها تلقائياً)
```

### 2. إعداد ملف `.env` على السيرفر
```bash
# انسخ محتوى .env.example
cp .env.example .env

# عدّل الملف بالإعدادات الصحيحة للإنتاج (كما هو موضح أعلاه)
nano .env
```

### 3. تثبيت المكتبات
```bash
# تثبيت مكتبات PHP
composer install --optimize-autoloader --no-dev

# تثبيت مكتبات JavaScript (إذا لزم الأمر)
npm install --production
npm run build
```

### 4. إعداد قاعدة البيانات
```bash
# توليد مفتاح التطبيق
php artisan key:generate

# تشغيل الـ migrations
php artisan migrate --force

# إنشاء جدول الجلسات
php artisan session:table
php artisan migrate --force

# تشغيل الـ seeders (إذا لزم الأمر)
php artisan db:seed --force
```

### 5. تحسين الأداء
```bash
# تخزين الإعدادات مؤقتاً
php artisan config:cache

# تخزين المسارات مؤقتاً
php artisan route:cache

# تخزين الـ Views مؤقتاً
php artisan view:cache
```

### 6. ضبط الصلاحيات
```bash
# إعطاء صلاحيات الكتابة لمجلدات التخزين
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## إعداد DNS للنطاقات الفرعية

### على السيرفر (Hostinger/cPanel):
1. اذهب إلى **DNS Zone Editor**
2. أضف سجل **A Record** جديد:
   - **Name:** `*` (نجمة للسماح بجميع النطاقات الفرعية)
   - **Type:** A
   - **Points to:** عنوان IP الخاص بالسيرفر
   - **TTL:** 14400

هذا يسمح لأي نطاق فرعي (مثل `tenant1.yourdomain.com`, `tenant2.yourdomain.com`) بالعمل تلقائياً.

---

## اختبار النظام

### في بيئة التطوير:
```bash
# تشغيل السيرفر
php artisan serve

# الوصول للتطبيق:
# - الصفحة الرئيسية: http://localhost:8000
# - صفحة التسجيل: http://my.localhost:8000/register
# - تسجيل دخول مستأجر: http://tenant-name.localhost:8000/login
```

### في بيئة الإنتاج:
```
# الوصول للتطبيق:
# - الصفحة الرئيسية: https://yourdomain.com
# - صفحة التسجيل: https://my.yourdomain.com/register
# - تسجيل دخول مستأجر: https://tenant-name.yourdomain.com/login
```

---

## استكشاف الأخطاء

### المشكلة: 419 Page Expired
**الحل:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan session:table
php artisan migrate
```

### المشكلة: تسجيل الدخول لا يعمل (Silent Failure)
**الحل:**
1. تأكد من `APP_URL` صحيح ويحتوي على المنفذ في التطوير
2. تأكد من `SESSION_DOMAIN` مضبوط بشكل صحيح
3. تأكد من `TENANT_ROUTE_PATTERN` يطابق الدومين المستخدم
4. امسح الكاش:
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### المشكلة: Tenant Not Found (404)
**الحل:**
1. تأكد من أن المستأجر موجود في قاعدة البيانات
2. تأكد من أن حقل `status` للمستأجر = `active`
3. تأكد من أن حقل `domain` يطابق النطاق الفرعي المستخدم

---

## ملخص الفروقات الأساسية

| الإعداد | التطوير (Development) | الإنتاج (Production) |
|---------|----------------------|---------------------|
| `APP_URL` | `http://localhost:8000` | `https://yourdomain.com` |
| `TENANT_DOMAIN` | `localhost` | `yourdomain.com` |
| `SESSION_DOMAIN` | `null` | `.yourdomain.com` |
| `APP_DEBUG` | `true` | `false` |

> [!TIP]
> **نصيحة ذهبية:** النظام الآن ذكي بما يكفي ليتعامل مع المنافذ المختلفة. إذا كنت تستخدم `php artisan serve` على منفذ 8000 أو غيره، ستعمل الروابط تلقائياً دون أي تعديل.

---

## ملاحظات أمنية

1. **لا تشارك ملف `.env` أبداً** - يحتوي على معلومات حساسة
2. **استخدم HTTPS في الإنتاج** - للأمان وحماية البيانات
3. **غيّر `APP_KEY` بين التطوير والإنتاج** - لا تستخدم نفس المفتاح
4. **استخدم كلمات مرور قوية** لقاعدة البيانات في الإنتاج
5. **فعّل `APP_DEBUG=false`** في الإنتاج لإخفاء تفاصيل الأخطاء

---

**تاريخ آخر تحديث:** 2025-12-06
**الإصدار:** 1.0
