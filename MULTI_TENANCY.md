# نظام Multi-Tenancy المزدوج

## نظرة سريعة

هذا المشروع يدعم طريقتين لتحديد المستأجرين:

```
✅ Path Mode:      yourdomain.com/c/center1/dashboard
✅ Subdomain Mode: center1.yourdomain.com/dashboard
```

## التبديل بين الأوضاع

في ملف `.env`:
```env
TENANCY_MODE=path        # للاستضافة المشتركة
# أو
TENANCY_MODE=subdomain   # لـ VPS/Cloud
```

ثم:
```bash
php artisan config:clear
```

## استخدام Helper Functions

```php
// توليد رابط للمستأجر
$url = tenant_url('dashboard', $tenant);

// توليد route اسمي
$url = tenant_route('center.login', [], $tenant);

// الحصول على المستأجر الحالي
$tenant = current_tenant();
```

## الملفات المهمة

- **Middleware:** `app/Http/Middleware/IdentifyTenant.php`
- **Helpers:** `app/Helpers/helpers.php`
- **Routes:** `Modules/Center/routes/web.php`
- **Config:** `config/app.php`

## التوثيق الكامل

راجع [`walkthrough.md`](file:///C:/Users/remon/.gemini/antigravity/brain/105aa06b-a2bd-475f-a0c8-ebfc528b6777/walkthrough.md) للدليل الشامل.

## الاختبار

```bash
# Path mode
http://localhost:8000/c/tenant1/login

# Subdomain mode (يحتاج إعداد hosts)
http://tenant1.localhost:8000/login
```

---

**الحالة:** ✅ جاهز للإنتاج  
**آخر تحديث:** 2026-01-22
