
<h1 align="center">Taalimu — منصة SaaS لإدارة المراكز التعليمية</h1>
hhhhhhhhhhhhhhhhhhhhhhhhhhhhh
<p align="center">
  <strong>نظام متكامل متعدد المستأجرين لإدارة المراكز التعليمية والمدرسين المستقلين</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?style=flat-square&logo=php" alt="PHP 8.4">
  <img src="https://img.shields.io/badge/License-Proprietary-blue?style=flat-square" alt="License">
  <img src="https://img.shields.io/badge/Tenancy-Multi--Tenant-green?style=flat-square" alt="Multi-Tenant">
</p>

---

## 📋 نظرة عامة

**Taalimu** هي منصة SaaS تعليمية مبنية بـ Laravel، تمكّن المراكز التعليمية والمدرسين المستقلين من إدارة عملياتهم بالكامل: الطلاب، الدورات، الامتحانات، المالية، الحضور، وأكثر.

### ✨ أبرز الميزات

- 🏢 **Multi-Tenancy** — نظام متعدد المستأجرين (Subdomain & Path modes)
- 👨‍🎓 **إدارة الطلاب** — تسجيل فردي وجماعي (Excel Import)، حضور بـ QR
- 📚 **إدارة الدورات** — مناهج هيكلية (دورات ← وحدات ← دروس ← مواد)
- 📝 **نظام الامتحانات** — أسئلة متعددة الأنواع، بنك أسئلة، تصحيح تلقائي
- 💳 **بوابات دفع متعددة** — Stripe, PayPal, Paymob (مصر)
- 📊 **تحليلات متقدمة** — MRR, Churn Rate, LTV, Engagement Score
- 🌍 **تعدد اللغات** — عربي، إنجليزي، فرنسي (RTL/LTR)
- 🔒 **أمان متقدم** — 2FA, RBAC, WAF, CSP, Activity Logging
- 📱 **PWA** — تطبيق ويب تقدمي يعمل offline
- 🤖 **Telegram Bot** — إشعارات فورية للإدارة

---

## 🛠️ حزمة التقنيات

| الطبقة | التقنية |
|:---|:---|
| **Backend** | Laravel 12, PHP 8.4 |
| **Frontend** | Blade Templates, Tailwind CSS, Alpine.js |
| **Database** | MySQL / MariaDB |
| **Modules** | nwidart/laravel-modules (6 وحدات) |
| **Permissions** | spatie/laravel-permission |
| **Activity Log** | spatie/laravel-activitylog |
| **Payments** | Laravel Cashier (Stripe), PayPal SDK, Paymob |
| **Auth** | Laravel Sanctum, Socialite (Google), 2FA |
| **PDF** | barryvdh/laravel-dompdf |
| **Monitoring** | Sentry |
| **Build Tool** | Vite |
| **Real-time** | Laravel Reverb |

---

## 🏗️ هيكلية المشروع

```
├── app/
│   ├── Console/Commands/     # Artisan commands (18+ أداة)
│   ├── Helpers/              # Helper functions (tenant_url, etc.)
│   ├── Http/
│   │   ├── Controllers/      # Main controllers
│   │   └── Middleware/       # 16 middleware (Tenant, WAF, CSP, etc.)
│   ├── Models/               # 51 Eloquent model
│   └── Services/             # 33 service class (Payment, Finance, etc.)
├── Modules/
│   ├── Admin/                # Super Admin panel
│   ├── Api/                  # RESTful API endpoints
│   ├── Campus/               # Campus management
│   ├── Center/               # Tenant (Center) dashboard
│   ├── Instructor/           # Instructor module
│   └── Tenancy/              # Tenancy infrastructure
├── database/
│   ├── migrations/           # 81 migration files
│   ├── seeders/              # Database seeders
│   └── factories/            # Model factories
├── resources/
│   ├── views/                # 213+ Blade templates
│   └── lang/                 # Translations (ar, en, fr)
├── routes/
│   ├── web.php               # Web routes
│   └── api.php               # API routes
├── tests/
│   ├── Feature/              # 21 feature tests
│   └── Unit/                 # 5 unit tests
└── docs/                     # Project documentation
```

---

## 🚀 إعداد بيئة التطوير

### المتطلبات

- PHP 8.2+ (مع extensions: mbstring, xml, curl, zip, gd, bcmath, intl)
- Composer 2.x
- Node.js 18+ & npm
- MySQL 8.0+ أو MariaDB 10.6+
- Redis (اختياري — مطلوب للإنتاج)

### خطوات التثبيت

```bash
# 1. استنساخ المشروع
git clone https://github.com/remonwaasef/taalimu-remon.git
cd taalimu-remon

# 2. تثبيت المكتبات
composer install
npm install

# 3. إعداد ملف البيئة
cp .env.example .env
# عدّل إعدادات قاعدة البيانات في .env

# 4. توليد مفتاح التطبيق
php artisan key:generate

# 5. إعداد قاعدة البيانات
php artisan migrate
php artisan db:seed

# 6. إنشاء رابط التخزين
php artisan storage:link

# 7. بناء الأصول
npm run build
```

### التشغيل (وضع التطوير)

```bash
# تشغيل جميع الخدمات معاً (Server + Queue + Logs + Vite)
composer dev

# أو تشغيل كل خدمة بشكل منفصل:
php artisan serve          # http://localhost:8000
npm run dev                # Vite dev server
php artisan queue:listen   # Queue worker
```

### الوصول المحلي

| الصفحة | الرابط |
|:---|:---|
| الصفحة الرئيسية | `http://localhost:8000` |
| تسجيل مركز جديد | `http://my.localhost:8000/register` |
| دخول مستأجر | `http://{tenant}.localhost:8000/login` |

---

## 🏢 نظام Multi-Tenancy

يدعم النظام وضعين للمستأجرين (يتم التبديل عبر `TENANCY_MODE` في `.env`):

| الوضع | المثال | الاستخدام |
|:---|:---|:---|
| **Subdomain** | `center1.taalimu.com` | VPS / Cloud hosting |
| **Path** | `taalimu.com/c/center1` | Shared hosting |

**الملفات المهمة:**
- Middleware: `app/Http/Middleware/IdentifyTenant.php`
- Helpers: `app/Helpers/helpers.php` (`tenant_url()`, `current_tenant()`)
- Config: `config/app.php` → `tenant_domain`

---

## 💳 بوابات الدفع

| البوابة | المنطقة | الحالة |
|:---|:---|:---|
| **Stripe** | عالمي | ✅ يعمل (اشتراكات متكررة) |
| **PayPal** | عالمي | ✅ يعمل |
| **Paymob** | مصر | ✅ يعمل (بطاقات + محافظ) |

---

## 🧪 الاختبارات

```bash
# تشغيل جميع الاختبارات
composer test

# أو مباشرة
php artisan test

# تشغيل اختبار محدد
php artisan test --filter=RegistrationFlowTest
```

**تغطية الاختبارات:** 29 ملف اختبار يغطي Registration, Subscription, Quiz, Sales, Security, Import/Export وأكثر.

---

## 🚢 النشر على الإنتاج

راجع أدلة النشر التفصيلية:
- 📖 [دليل الإعدادات الموحدة](DEPLOYMENT_GUIDE.md) — إعدادات التطوير والإنتاج
- 📖 [دليل النشر على KVM2](DEPLOYMENT_GUIDE_KVM2.md) — خطوات النشر الكاملة

### أوامر النشر السريعة

```bash
composer install --optimize-autoloader --no-dev
npm install && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

---

## 📚 التوثيق الإضافي

| الملف | الوصف |
|:---|:---|
| [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) | إعدادات التطوير والإنتاج |
| [DEPLOYMENT_GUIDE_KVM2.md](DEPLOYMENT_GUIDE_KVM2.md) | دليل النشر الكامل على KVM2 |
| [MULTI_TENANCY.md](MULTI_TENANCY.md) | توثيق نظام Multi-Tenancy |
| [docs/saas_plan_final.md](docs/saas_plan_final.md) | خطة المشروع التفصيلية |
| [docs/roadmap_detailed.txt](docs/roadmap_detailed.txt) | خارطة الطريق المفصلة |

---

## 👥 الأدوار في النظام

| الدور | الوصف |
|:---|:---|
| **Super Admin** | إدارة كل المراكز والاشتراكات والنظام |
| **Center Admin** | إدارة المركز التعليمي (الطلاب، المدرسين، الدورات) |
| **Instructor** | إدارة الدورات والمحتوى والامتحانات |
| **Student** | الوصول للدورات والاختبارات ومتابعة التقدم |
| **Secretary** | إدارة التسجيلات والحضور |
| **Accountant** | إدارة المالية والفواتير |
| **Guardian** | متابعة تقدم الطالب |

---

## 📄 الترخيص

هذا المشروع مملوك ولا يخضع لترخيص مفتوح المصدر. جميع الحقوق محفوظة.

---

<p align="center">
  صُنع بـ ❤️ بواسطة فريق Taalimu
</p>


+ ## 🚢 أمر النشر السريع (Deployment)
+ إذا كنت تريد تشغيل سكربت النشر التلقائي، قم بتنفيذ الأمر التالي في PowerShell:
+ ```powershell
+ powershell -ExecutionPolicy Bypass -File ./deployment_scripts/deploy.ps1
+ ```
