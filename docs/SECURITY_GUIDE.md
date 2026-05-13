# 🔒 دليل الأمان لفريق التطوير — منصة Taalimu

> هذا الدليل إلزامي لكل عضو في الفريق. عدم الالتزام يعرّض المنصة والمستخدمين للخطر.

---

## 1. إدارة كلمات المرور

### قواعد صارمة
- **ممنوع** مشاركة كلمات المرور عبر واتساب أو إيميل عادي
- **ممنوع** حفظ كلمات المرور في ملفات نصية على الجهاز
- **مطلوب** استخدام Password Manager (مثل Bitwarden أو 1Password)
- **مطلوب** تفعيل 2FA على جميع الحسابات (GitHub, cPanel, AWS, إلخ)

### سياسة تغيير كلمات المرور
| المفتاح | دورة التغيير | المسؤول |
|:---|:---|:---|
| كلمة مرور قاعدة البيانات | كل 90 يوم | مدير المشروع |
| مفاتيح API (Stripe, PayPal, إلخ) | كل 90 يوم أو عند تسريب | مدير المشروع |
| كلمات مرور SSH | عند تغيير أي عضو في الفريق | DevOps |
| APP_KEY في Laravel | نادراً (يُبطل جميع الجلسات) | مدير المشروع |

---

## 2. صلاحيات الوصول (Least Privilege)

### مبدأ أقل الصلاحيات
- كل مطور يحصل على **أقل صلاحيات** يحتاجها فقط
- لا يحصل المطور الجديد على وصول SSH للإنتاج
- الوصول لقاعدة بيانات الإنتاج مقيّد بالقراءة فقط (Read-Only)

### مراجعة دورية
```
□ كل شهر: مراجعة قائمة المطورين الذين لديهم وصول SSH
□ كل شهر: مراجعة أذونات Spatie (الأدوار والصلاحيات)
□ فوراً: عند مغادرة أي عضو → إلغاء جميع صلاحياته
```

---

## 3. التعامل مع الحوادث الأمنية

### في حالة اكتشاف ثغرة
1. **لا تنشرها علناً** — أبلغ مدير المشروع فوراً
2. أنشئ PR خاص (Private) لإصلاحها
3. بعد الإصلاح والنشر، وثّق الحادثة

### في حالة تسريب مفتاح API
1. **غيّر المفتاح فوراً** من لوحة التحكم الخارجية
2. حدّث `.env` على السيرفر
3. شغّل `php artisan config:cache`
4. راجع السجلات (Logs) للتأكد من عدم الاستغلال

---

## 4. الخدمات الخارجية ومفاتيحها

| الخدمة | ملف الإعداد | لوحة التحكم |
|:---|:---|:---|
| Stripe | `config/services.php → stripe` | dashboard.stripe.com |
| PayPal | `config/services.php → paypal` | developer.paypal.com |
| Paymob | `config/services.php → paymob` | dashboard.paymob.com |
| Telegram | `config/services.php → telegram` | @BotFather |
| WhatsApp | `config/services.php → whatsapp` | business.facebook.com |
| Google OAuth | `config/services.php → google` | console.cloud.google.com |

---

## 5. أنظمة المراقبة الحالية

### ماذا نراقب تلقائياً؟
| النظام | ماذا يفعل | أين يُبلّغ |
|:---|:---|:---|
| `AuthenticationSubscriber` | يسجّل كل Login/Logout/Failed/Lockout | Activity Log + Telegram |
| `LogsActivity` (Spatie) | يسجّل تغييرات 13 Model حساس | جدول `activity_log` |
| `IssueLogger` | يسجّل أخطاء 500 مع سياق Tenant | جدول Issues + Telegram |
| `BasicWAF` | يحظر طلبات SQL Injection و XSS | يرفض الطلب تلقائياً |
| `SecurityHeaders` | يضيف headers أمنية لكل Response | تلقائي |

### كيف تراجع السجلات؟
```bash
# سجلات Laravel
tail -f storage/logs/laravel.log

# سجلات النشاط من قاعدة البيانات
php artisan tinker
> Activity::latest()->take(20)->get(['description', 'causer_type', 'properties']);
```

---

## 6. قائمة مراجعة أمان شهرية

```
□ مراجعة أذونات المستخدمين على GitHub
□ مراجعة أذونات Spatie (Roles & Permissions) لكل tenant
□ فحص جدول activity_log لنشاط غير عادي
□ التأكد من عمل النسخ الاحتياطي التلقائي
□ فحص حجم ملفات Log (حذف القديم إذا تجاوز 500MB)
□ التأكد من تحديث مكتبات Composer (composer audit)
□ مراجعة قائمة Rate Limiters والتأكد من فعاليتها
```
