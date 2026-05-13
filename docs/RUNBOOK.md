# 🛟 Runbook — إجراءات الطوارئ والتشغيل

> آخر تحديث: مايو 2026
> هذا الملف يحتوي الحل لكل سيناريو طوارئ. اقرأه **قبل** أن تحتاجه.

---

## 📋 جدول المحتويات

1. [السيرفر وقع](#1-السيرفر-وقع)
2. [قاعدة البيانات تعطلت](#2-قاعدة-البيانات-تعطلت)
3. [استعادة نسخة احتياطية](#3-استعادة-نسخة-احتياطية)
4. [تسريب مفتاح API](#4-تسريب-مفتاح-api)
5. [شهادة SSL انتهت](#5-شهادة-ssl-انتهت)
6. [WhatsApp Token انتهى](#6-whatsapp-token-انتهى)
7. [Telegram Bot لا يعمل](#7-telegram-bot-لا-يعمل)
8. [عميل يشتكي من الفوترة](#8-عميل-يشتكي-من-الفوترة)
9. [اشتراك مركز انتهى](#9-اشتراك-مركز-انتهى)
10. [هجوم إلكتروني / محاولة اختراق](#10-هجوم-إلكتروني)
11. [الموقع بطيء جداً](#11-الموقع-بطيء-جداً)
12. [المؤسس غير متاح](#12-المؤسس-غير-متاح)
13. [ضياع اللابتوب](#13-ضياع-اللابتوب)

---

## 1. السيرفر وقع

### الأعراض:
- الموقع لا يفتح (502 / 503 / Connection Refused)
- Telegram يرسل تنبيهات خطأ متكررة

### الخطوات:

```bash
# 1. تحقق من حالة السيرفر
ssh user@server-ip

# 2. تحقق من Nginx/Apache
sudo systemctl status nginx
sudo systemctl restart nginx

# 3. تحقق من PHP-FPM
sudo systemctl status php8.2-fpm
sudo systemctl restart php8.2-fpm

# 4. تحقق من MySQL
sudo systemctl status mysql
sudo systemctl restart mysql

# 5. تحقق من مساحة القرص
df -h

# 6. تحقق من الذاكرة
free -m

# 7. تحقق من Laravel Logs
tail -100 /path/to/edu/storage/logs/laravel.log
```

### لو كل شيء طبيعي لكن الموقع لا يعمل:
```bash
cd /path/to/edu
php artisan down    # ضع الموقع في وضع الصيانة
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up      # أعد تشغيل الموقع
```

### التصعيد:
- لو لم تنحل → تواصل مع شركة الاستضافة
- وثّق المشكلة في Issue على GitHub

---

## 2. قاعدة البيانات تعطلت

### الأعراض:
- صفحات الموقع تعطي خطأ 500
- Laravel Log يحتوي: `SQLSTATE[HY000] [2002] Connection refused`

### الخطوات:
```bash
# 1. أعد تشغيل MySQL
sudo systemctl restart mysql

# 2. تحقق من الاتصال
mysql -u root -p -e "SELECT 1;"

# 3. لو فشل — تحقق من المساحة
df -h /var/lib/mysql

# 4. لو المساحة ممتلئة — احذف logs قديمة
sudo find /var/log/mysql -name "*.log" -mtime +30 -delete

# 5. لو قاعدة البيانات تالفة
mysqlcheck --repair --all-databases -u root -p
```

### التصعيد:
- لو لم تنحل → استعد من النسخة الاحتياطية (انظر القسم 3)

---

## 3. استعادة نسخة احتياطية

### أين تُخزّن النسخ؟
| المكان | التكرار | الملاحظة |
|:---|:---|:---|
| السيرفر `/backups/` | يومياً | آخر 7 نسخ |
| Cloud Storage | أسبوعياً | آخر 4 نسخ |

### خطوات الاستعادة:
```bash
# 1. ضع الموقع في وضع الصيانة
cd /path/to/edu
php artisan down

# 2. استعد قاعدة البيانات
mysql -u root -p edu_central < /backups/edu_central_YYYY-MM-DD.sql

# 3. تحقق من سلامة البيانات
php artisan tinker
> \App\Models\Tenant::count();  // يجب أن يعطي عدد المراكز المتوقع

# 4. أعد بناء الكاش
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. أعد تشغيل الموقع
php artisan up
```

### ⚠️ تحذير:
- الاستعادة تمسح البيانات المُدخلة بعد وقت النسخة
- أبلغ العملاء المتأثرين

---

## 4. تسريب مفتاح API

### متى يُعتبر مفتاح "مسرّب"؟
- تم عمل commit له على GitHub (حتى لو حُذف لاحقاً)
- تم مشاركته في رسالة عادية (واتساب، إيميل)
- تم عرضه على شاشة في اجتماع

### خطوات فورية:
```
⏰ يجب التنفيذ خلال ساعة واحدة من الاكتشاف

1. غيّر المفتاح فوراً من لوحة التحكم الخارجية:
   - Stripe: dashboard.stripe.com → Developers → API Keys → Roll Key
   - PayPal: developer.paypal.com → App → Generate New Secret
   - Paymob: dashboard.paymob.com → Settings → API Key → Regenerate
   - Telegram: @BotFather → /revoke
   - Google: console.cloud.google.com → Credentials → Reset

2. حدّث .env على السيرفر بالمفتاح الجديد

3. أعد بناء الكاش:
   php artisan config:cache

4. راجع الـ Logs بحثاً عن استخدام غير مصرّح:
   grep "API" storage/logs/laravel.log | tail -50

5. وثّق الحادثة
```

---

## 5. شهادة SSL انتهت

### الأعراض:
- المتصفح يعرض "Your connection is not private"
- الموقع يعمل لكن بتحذير أمني

### الحل (Cloudflare):
```
1. سجّل دخول: dash.cloudflare.com
2. اختر الدومين
3. SSL/TLS → Edge Certificates
4. تأكد أن Universal SSL مفعّل
5. لو منتهي → اضغط "Order Advanced Certificate"
```

### الحل (Let's Encrypt على السيرفر):
```bash
# تجديد يدوي
sudo certbot renew

# تجديد تلقائي (يجب أن يكون مفعّل)
sudo crontab -l | grep certbot
# لو غير موجود، أضفه:
# 0 0 1 * * certbot renew --quiet && systemctl reload nginx
```

---

## 6. WhatsApp Token انتهى

### الأعراض:
- الطلاب لا يستلمون إشعارات WhatsApp
- Log يحتوي: `WhatsApp API Error: 401 Unauthorized`

### الحل:
```
1. سجّل دخول: business.facebook.com
2. System Users → اختر المستخدم
3. Generate New Token
4. حدّث في إعدادات المركز (Settings → WhatsApp)
   أو في .env لو كان System Token:
   WHATSAPP_SYSTEM_TOKEN=new_token_here
5. php artisan config:cache
```

### ملاحظة:
- System Tokens صلاحيتها 60 يوم
- ضع تذكير في التقويم قبل 7 أيام من الانتهاء

---

## 7. Telegram Bot لا يعمل

### الأعراض:
- لا تصلك إشعارات التسجيل أو الأخطاء

### الحل:
```bash
# 1. تحقق من الاتصال
curl "https://api.telegram.org/bot<TOKEN>/getMe"

# لو أعطى 401 → التوكن خاطئ
# اذهب لـ @BotFather → /token → انسخ التوكن الصحيح

# 2. تحقق من chat_id
curl "https://api.telegram.org/bot<TOKEN>/getUpdates"
# ابحث عن chat.id في النتائج

# 3. حدّث .env
TELEGRAM_BOT_TOKEN=new_token
TELEGRAM_ADMIN_CHAT_ID=correct_chat_id

# 4. أعد بناء الكاش
php artisan config:cache

# 5. اختبر
php artisan tinker
> app(\App\Services\TelegramService::class)->sendAdminNotification("Test ✅");
```

---

## 8. عميل يشتكي من الفوترة

### السيناريو: "دفعت لكن حسابي لسه مش مفعّل"

### الخطوات:
```bash
# 1. تحقق من حالة الاشتراك
php artisan tinker
> $t = \App\Models\Tenant::where('domain', 'center-name')->first();
> $t->subscriptions()->latest()->first();
# انظر status, ends_at, stripe_status

# 2. لو الدفع تم فعلاً لكن الاشتراك لم يُفعّل → فعّله يدوياً
> $sub = $t->subscriptions()->latest()->first();
> $sub->update(['status' => 'active', 'stripe_status' => 'active', 'ends_at' => now()->addMonth()]);

# 3. امسح كاش المركز
> \App\Queries\CenterAnalyticsQuery::clearCacheForTenant($t->id);
```

### لو العميل يقول "خصمتوا مرتين":
```
1. تحقق من بوابة الدفع (PayPal/Paymob Dashboard) مباشرة
2. لو فعلاً تم الخصم مرتين → ابدأ Refund من البوابة
3. وثّق الحالة في Ticket
```

---

## 9. اشتراك مركز انتهى

### ماذا يحدث تلقائياً:
1. النظام يرسل تنبيه قبل 7 أيام (Email + Telegram)
2. بعد الانتهاء → الحالة تتحول لـ `expired`
3. المركز يرى صفحة "جدد اشتراكك"

### لو طلب المركز تمديد مؤقت:
```bash
php artisan tinker
> $sub = \App\Models\Subscription::where('tenant_id', TENANT_ID)->latest()->first();
> $sub->update(['ends_at' => now()->addDays(7), 'status' => 'active']);
```

### لو طلب إلغاء نهائي:
```
1. أوقف الاشتراك من لوحة الإدارة
2. اعرض عليه تصدير بياناته (GDPR)
3. لا تحذف البيانات فوراً — انتظر 30 يوم
```

---

## 10. هجوم إلكتروني

### الأعراض:
- طلبات كثيرة غير عادية في الـ Logs
- BasicWAF يحظر عدد كبير من الطلبات
- الموقع بطيء فجأة

### خطوات فورية:
```bash
# 1. تحقق من الطلبات الأكثر تكراراً
tail -5000 /var/log/nginx/access.log | awk '{print $1}' | sort | uniq -c | sort -rn | head -20

# 2. احظر الـ IP المهاجم
# في Cloudflare: Security → WAF → Create Rule → Block IP
# أو على السيرفر:
sudo iptables -A INPUT -s ATTACKER_IP -j DROP

# 3. لو الهجوم من عدة IPs (DDoS):
# فعّل "Under Attack Mode" في Cloudflare
# Cloudflare → Overview → Under Attack Mode → ON

# 4. تحقق من عدم حدوث اختراق
grep "login" storage/logs/laravel.log | tail -50
# ابحث عن محاولات دخول ناجحة مشبوهة

# 5. لو تم اختراق حساب:
# غيّر كل كلمات المرور فوراً
# راجع activity_log في قاعدة البيانات
```

---

## 11. الموقع بطيء جداً

### التشخيص:
```bash
# 1. تحقق من حمل السيرفر
top     # أو htop

# 2. تحقق من الاستعلامات البطيئة
# في MySQL:
SHOW PROCESSLIST;

# 3. تحقق من Queue Workers
php artisan queue:monitor

# 4. تحقق من حجم الكاش
php artisan tinker
> \Illuminate\Support\Facades\Cache::getStore();
```

### الحلول السريعة:
```bash
# 1. امسح الكاش وأعد بناءه
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. أعد تشغيل Queue Workers
php artisan queue:restart

# 3. لو MySQL هي المشكلة — تحقق من الـ Slow Query Log
sudo tail -50 /var/log/mysql/slow.log
```

### الحل طويل المدى:
- تفعيل Redis كـ Cache Driver
- إضافة Indexes لاستعلامات بطيئة
- ترقية السيرفر (RAM/CPU)

---

## 12. المؤسس غير متاح

### ماذا يحتاج الشخص البديل؟

| العنصر | المكان |
|:---|:---|
| كلمات المرور | Bitwarden (Organization Vault) |
| وصول السيرفر (SSH) | في Password Manager |
| كود المصدر | GitHub Private Repository |
| هذا الملف (Runbook) | `docs/RUNBOOK.md` في المشروع |
| بنية النظام | `docs/ARCHITECTURE.md` |
| دليل المطور | `docs/DEVELOPER_GUIDE.md` |
| دليل الأمان | `docs/SECURITY_GUIDE.md` |

### العمليات اليومية التي يجب أن تستمر:
```
□ مراقبة إشعارات Telegram (أخطاء + تسجيلات)
□ الرد على تذاكر الدعم
□ مراقبة الاشتراكات المنتهية
□ التأكد من عمل الـ Queue Workers
```

---

## 13. ضياع اللابتوب

### ماذا تفعل فوراً (خلال ساعة):

```
⏰ أولوية قصوى — افعل بالترتيب:

1. غيّر كلمة مرور GitHub من جهاز آخر
2. غيّر كلمة مرور cPanel / SSH
3. غيّر APP_KEY (⚠️ هذا يُبطل كل الجلسات — العملاء سيحتاجون إعادة تسجيل الدخول)
4. غيّر DB_PASSWORD
5. غيّر مفاتيح كل APIs (Stripe, PayPal, Paymob, Telegram)
6. راجع Activity Log بحثاً عن نشاط مشبوه
7. أبلغ الفريق
```

### الوقاية:
- **لا تحفظ** كلمات المرور في ملفات نصية على اللابتوب
- **استخدم** Password Manager (Bitwarden)
- **فعّل** Full Disk Encryption
- **فعّل** 2FA على كل الحسابات

---

## 📞 جهات الاتصال للطوارئ

| المسؤولية | الشخص | طريقة التواصل |
|:---|:---|:---|
| المؤسس / CTO | [الاسم] | [الرقم] |
| Secondary Admin | [الاسم] | [الرقم] |
| شركة الاستضافة | [الاسم] | [رابط الدعم] |
| Cloudflare Support | — | dash.cloudflare.com |
| Paymob Support | — | support@paymob.com |

> ⚠️ **مهم:** املأ هذا الجدول الآن — لا تنتظر حتى تحتاجه.
