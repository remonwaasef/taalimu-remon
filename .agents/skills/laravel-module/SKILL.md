---
name: Laravel Module Creator
description: Skill for creating new Laravel modules following the Taalimu Modular Monolith architecture using nwidart/laravel-modules, with proper tenancy integration, routing, and directory structure.
---

# Laravel Module Creator — مهارة إنشاء Module جديد

## متى تُستخدم هذه المهارة؟
عند طلب إنشاء Module جديد أو توسيع وظائف الـ Modules الموجودة في `Modules/`.

---

## قبل البدء — تحقق أولاً

1. **هل الوظيفة موجودة فعلاً؟** تحقق من الـ Modules الحالية:
   - `Admin` — إدارة مركزية
   - `Center` — عمليات المركز التعليمي
   - `Instructor` — لوحة المدرس
   - `Campus` — إدارة الفروع
   - `Tenancy` — ربط الدومينات
   - `Api` — واجهات REST API

2. **هل يمكن إضافة الميزة لـ Module موجود؟** لا تنشئ Module جديد إلا إذا كانت الوظيفة مختلفة تمامًا عن الموجود.

---

## الهيكل المطلوب لكل Module جديد

```
Modules/NewModule/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── NewModuleBaseController.php
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   ├── Providers/
│   │   ├── NewModuleServiceProvider.php
│   │   └── RouteServiceProvider.php
│   └── Services/
├── config/
│   └── config.php
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── lang/
│   ├── ar/
│   ├── en/
│   └── fr/
├── resources/
│   └── views/
│       └── layouts/
├── routes/
│   ├── web.php
│   └── api.php
├── tests/
│   ├── Feature/
│   └── Unit/
├── composer.json
├── module.json
├── package.json
└── vite.config.js
```

---

## خطوات الإنشاء

### الخطوة 1: إنشاء الـ Module
```bash
php artisan module:make NewModule
```

### الخطوة 2: تحديد module.json
```json
{
    "name": "NewModule",
    "alias": "newmodule",
    "description": "وصف واضح للـ Module",
    "keywords": [],
    "priority": 0,
    "providers": [
        "Modules\\NewModule\\Providers\\NewModuleServiceProvider"
    ],
    "files": []
}
```

### الخطوة 3: إعداد الـ Routes مع Tenancy

#### routes/web.php — مثال مع حماية المستأجر:
```php
<?php

use Illuminate\Support\Facades\Route;
use Modules\NewModule\Http\Controllers\NewModuleController;

Route::middleware(['web', 'auth', 'identify.tenant', 'ensure.onboarding', 'check.subscription'])
    ->group(function () {
        Route::get('/', [NewModuleController::class, 'index'])->name('newmodule.index');
        // ... المزيد من الروابط
    });
```

### الخطوة 4: Base Controller مع Tenant Context
```php
<?php

namespace Modules\NewModule\Http\Controllers;

use App\Http\Controllers\Controller;

class NewModuleBaseController extends Controller
{
    protected $tenant;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->tenant = current_tenant();
            return $next($request);
        });
    }
}
```

### الخطوة 5: Vite Config
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        outDir: '../../public/build-newmodule',
        emptyOutDir: true,
        manifest: true,
    },
    plugins: [
        laravel({
            publicDirectory: '../../public',
            buildDirectory: 'build-newmodule',
            input: [
                __dirname + '/resources/css/app.css',
                __dirname + '/resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
```

---

## قائمة التحقق بعد الإنشاء

- [ ] الـ Module مسجل في `modules_statuses.json`
- [ ] الـ ServiceProvider يعمل بشكل صحيح
- [ ] الـ Routes محمية بـ Middleware المناسبة
- [ ] الـ Views تستخدم Layout مناسب
- [ ] الـ Translations موجودة في 3 لغات (ar, en, fr)
- [ ] تم تحديث `docs/10_MODULES.md`
- [ ] تم تحديث `docs/34_CHANGELOG.md`

---

## مرجع — بنية Module Center كنموذج

ارجع لـ `Modules/Center/` كنموذج مثالي:
- Controllers في `app/Http/Controllers/` (40+ controller)
- Base Controller: `CenterBaseController.php`
- Routes في `routes/web.php`
- Views في `resources/views/`
- Vite config مستقل
