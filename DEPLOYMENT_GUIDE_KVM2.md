# 🚀 دليل النشر الكامل - Edu SaaS على KVM 2

> **آخر تحديث:** 24 يناير 2026  
> **المتطلبات:** KVM 2 VPS + GitHub Account + Domain Name

---

## 📋 جدول المحتويات

1. [المتطلبات الأساسية](#-المتطلبات-الأساسية)
2. [المرحلة 1: إعداد GitHub من جهازك](#-المرحلة-1-إعداد-github-من-جهازك)
3. [المرحلة 2: إعداد السيرفر KVM 2](#-المرحلة-2-إعداد-السيرفر-kvm-2)
4. [المرحلة 3: استنساخ المشروع على السيرفر](#-المرحلة-3-استنساخ-المشروع-على-السيرفر)
5. [المرحلة 4: إعداد قاعدة البيانات](#-المرحلة-4-إعداد-قاعدة-البيانات)
6. [المرحلة 5: إعداد ملف البيئة](#-المرحلة-5-إعداد-ملف-البيئة-env)
7. [المرحلة 6: إعداد Nginx](#-المرحلة-6-إعداد-nginx)
8. [المرحلة 7: إعداد SSL](#-المرحلة-7-إعداد-ssl-مجاني)
9. [المرحلة 8: إعداد Queue Worker](#-المرحلة-8-إعداد-queue-worker)
10. [كيفية المزامنة والنشر](#-كيفية-المزامنة-والنشر)
11. [حل المشاكل الشائعة](#-حل-المشاكل-الشائعة)

---

## 📦 المتطلبات الأساسية

### على جهازك (Windows):
- [x] Git مثبت
- [x] المشروع متصل بـ GitHub: `remonwaasef/Edu-saas`

### على السيرفر:
- [ ] نظام Ubuntu 22.04 أو أحدث
- [ ] SSH Access (root أو sudo user)
- [ ] Domain مُوَجَّه للسيرفر

### معلومات السيرفر (احتفظ بها):
```
IP Address: _______________________
SSH Username: _____________________
SSH Password/Key: _________________
Domain: ___________________________
```

---

## 🖥️ المرحلة 1: إعداد GitHub من جهازك

### الخطوة 1.1: تأكد من أن كل شيء محفوظ

افتح **PowerShell** في مجلد المشروع:

```powershell
cd "d:\new project\antigravty\edu\edu"
```

### الخطوة 1.2: تحقق من حالة Git

```powershell
git status
```

### الخطوة 1.3: احفظ جميع التعديلات

```powershell
git add .
git commit -m "Prepare for production deployment"
git push origin main
```

> ✅ **نتيجة متوقعة:** رسالة نجاح الرفع إلى GitHub

---

## 🔧 المرحلة 2: إعداد السيرفر KVM 2

### الخطوة 2.1: الاتصال بالسيرفر

```bash
ssh root@YOUR_SERVER_IP
```

أو استخدم **PuTTY** على Windows.

### الخطوة 2.2: تحديث النظام

```bash
apt update && apt upgrade -y
```

⏱️ **الوقت المتوقع:** 2-5 دقائق

### الخطوة 2.3: تثبيت PHP 8.2 والإضافات

```bash
# إضافة مستودع PHP
apt install software-properties-common -y
add-apt-repository ppa:ondrej/php -y
apt update

# تثبيت PHP والإضافات
apt install php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-redis php8.2-bcmath php8.2-intl -y
```

### الخطوة 2.4: تثبيت Composer

```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# تحقق من التثبيت
composer --version
```

> ✅ **نتيجة متوقعة:** `Composer version 2.x.x`

### الخطوة 2.5: تثبيت Node.js 18

```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt install nodejs -y

# تحقق من التثبيت
node --version
npm --version
```

> ✅ **نتيجة متوقعة:** `v18.x.x`

### الخطوة 2.6: تثبيت Redis

```bash
apt install redis-server -y
systemctl enable redis-server
systemctl start redis-server

# تحقق من التشغيل
redis-cli ping
```

> ✅ **نتيجة متوقعة:** `PONG`

### الخطوة 2.7: تثبيت MariaDB

```bash
apt install mariadb-server mariadb-client -y
systemctl enable mariadb
systemctl start mariadb

# تأمين قاعدة البيانات
mysql_secure_installation
```

**أجب على الأسئلة:**
- Enter current password: (اضغط Enter - فارغ)
- Switch to unix_socket: **n**
- Change root password: **Y** → أدخل كلمة مرور قوية
- Remove anonymous users: **Y**
- Disallow root login remotely: **Y**
- Remove test database: **Y**
- Reload privilege tables: **Y**

### الخطوة 2.8: تثبيت Nginx

```bash
apt install nginx -y
systemctl enable nginx
systemctl start nginx

# تحقق من التشغيل
systemctl status nginx
```

### الخطوة 2.9: تثبيت Git

```bash
apt install git -y
git --version
```

---

## 📥 المرحلة 3: استنساخ المشروع على السيرفر

### الخطوة 3.1: إنشاء مجلد التطبيق

```bash
mkdir -p /var/www
cd /var/www
```

### الخطوة 3.2: استنساخ المشروع من GitHub

```bash
git clone https://github.com/remonwaasef/Edu-saas.git edu
cd edu
```

### الخطوة 3.3: تثبيت PHP Dependencies

```bash
composer install --optimize-autoloader --no-dev
```

⏱️ **الوقت المتوقع:** 3-5 دقائق

### الخطوة 3.4: تثبيت Node Dependencies وبناء الأصول

```bash
npm install
npm run build
```

⏱️ **الوقت المتوقع:** 2-4 دقائق

### الخطوة 3.5: ضبط الصلاحيات

```bash
chown -R www-data:www-data /var/www/edu
chmod -R 755 /var/www/edu
chmod -R 775 /var/www/edu/storage
chmod -R 775 /var/www/edu/bootstrap/cache
```

---

## 🗄️ المرحلة 4: إعداد قاعدة البيانات

### الخطوة 4.1: إنشاء قاعدة البيانات والمستخدم

```bash
mysql -u root -p
```

أدخل كلمة المرور، ثم نفذ:

```sql
CREATE DATABASE edu_saas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'edu_user'@'localhost' IDENTIFIED BY 'YOUR_STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON edu_saas.* TO 'edu_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

> ⚠️ **مهم:** استبدل `YOUR_STRONG_PASSWORD_HERE` بكلمة مرور قوية واحتفظ بها!

---

## ⚙️ المرحلة 5: إعداد ملف البيئة (.env)

### الخطوة 5.1: نسخ ملف البيئة

```bash
cd /var/www/edu
cp .env.example .env
```

### الخطوة 5.2: تعديل ملف البيئة

```bash
nano .env
```

**عدّل هذه القيم:**

```env
APP_NAME="Edu SaaS"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Domain للـ Multi-tenancy
TENANT_DOMAIN=yourdomain.com

# قاعدة البيانات
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edu_saas
DB_USERNAME=edu_user
DB_PASSWORD=YOUR_STRONG_PASSWORD_HERE

# Redis (مهم جداً للأداء!)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# استخدام Redis للأداء
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# الأمان
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true

# البريد (اختياري - عدّل حسب مزودك)
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**للحفظ:** `Ctrl + X` → `Y` → `Enter`

### الخطوة 5.3: توليد مفتاح التطبيق

```bash
php artisan key:generate
```

### الخطوة 5.4: تشغيل Migrations

```bash
php artisan migrate --force
```

### الخطوة 5.5: إنشاء الـ Storage Link

```bash
php artisan storage:link
```

### الخطوة 5.6: تحسين الأداء

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache
```

---

## 🌐 المرحلة 6: إعداد Nginx

### الخطوة 6.1: إنشاء ملف إعدادات الموقع

```bash
nano /etc/nginx/sites-available/edu
```

**الصق هذا المحتوى:**

```nginx
server {
    listen 80;
    listen [::]:80;
    
    # الدومين الرئيسي والـ Subdomains للـ Tenants
    server_name yourdomain.com *.yourdomain.com;
    
    root /var/www/edu/public;
    index index.php index.html;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Gzip Compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml;

    # Max upload size
    client_max_body_size 50M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
```

> ⚠️ **مهم:** استبدل `yourdomain.com` بدومينك الفعلي!

### الخطوة 6.2: تفعيل الموقع

```bash
ln -s /etc/nginx/sites-available/edu /etc/nginx/sites-enabled/
```

### الخطوة 6.3: إزالة الموقع الافتراضي

```bash
rm /etc/nginx/sites-enabled/default
```

### الخطوة 6.4: اختبار الإعدادات

```bash
nginx -t
```

> ✅ **نتيجة متوقعة:** `syntax is ok` و `test is successful`

### الخطوة 6.5: إعادة تشغيل Nginx

```bash
systemctl restart nginx
```

---

## 🔒 المرحلة 7: إعداد SSL (مجاني)

### الخطوة 7.1: تثبيت Certbot

```bash
apt install certbot python3-certbot-nginx -y
```

### الخطوة 7.2: الحصول على شهادة SSL

```bash
certbot --nginx -d yourdomain.com -d "*.yourdomain.com"
```

**اتبع التعليمات:**
- أدخل بريدك الإلكتروني
- وافق على الشروط (Y)
- اختر Redirect HTTP to HTTPS (2)

### الخطوة 7.3: التجديد التلقائي

```bash
# اختبار التجديد
certbot renew --dry-run
```

---

## ⚡ المرحلة 8: إعداد Queue Worker

### الخطوة 8.1: إنشاء خدمة Supervisor

```bash
apt install supervisor -y
```

### الخطوة 8.2: إنشاء إعدادات Worker

```bash
nano /etc/supervisor/conf.d/edu-worker.conf
```

**الصق هذا المحتوى:**

```ini
[program:edu-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/edu/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/edu/storage/logs/worker.log
stopwaitsecs=3600
```

### الخطوة 8.3: تشغيل Worker

```bash
supervisorctl reread
supervisorctl update
supervisorctl start edu-worker:*
```

### الخطوة 8.4: التحقق من التشغيل

```bash
supervisorctl status
```

> ✅ **نتيجة متوقعة:** `edu-worker:edu-worker_00  RUNNING`

---

## 🔄 كيفية المزامنة والنشر

### من جهازك (Windows) - بعد أي تعديل:

```powershell
cd "d:\new project\antigravty\edu\edu"
git add .
git commit -m "وصف التعديل هنا"
git push origin main
```

### على السيرفر - لسحب التحديثات:

**الطريقة السريعة (سكريبت):**

أنشئ ملف النشر:
```bash
nano /var/www/edu/deploy.sh
```

```bash
#!/bin/bash
echo "========================================"
echo "🚀 بدء عملية النشر..."
echo "========================================"

cd /var/www/edu

echo ""
echo "📥 سحب التحديثات من GitHub..."
git pull origin main

echo ""
echo "📦 تثبيت PHP Dependencies..."
composer install --optimize-autoloader --no-dev

echo ""
echo "📦 تثبيت وبناء الأصول..."
npm install
npm run build

echo ""
echo "🗃️ تشغيل Migrations..."
php artisan migrate --force

echo ""
echo "⚡ مسح وإعادة بناء الـ Cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache

echo ""
echo "🔄 إعادة تشغيل Queue Workers..."
php artisan queue:restart

echo ""
echo "🔧 ضبط الصلاحيات..."
chown -R www-data:www-data /var/www/edu
chmod -R 775 /var/www/edu/storage

echo ""
echo "========================================"
echo "✅ تم النشر بنجاح!"
echo "========================================"
```

```bash
chmod +x /var/www/edu/deploy.sh
```

**الآن للنشر فقط نفذ:**
```bash
/var/www/edu/deploy.sh
```

---

## 🔧 حل المشاكل الشائعة

### مشكلة: 500 Internal Server Error

```bash
# تحقق من الـ Logs
tail -f /var/www/edu/storage/logs/laravel.log

# تأكد من الصلاحيات
chown -R www-data:www-data /var/www/edu
chmod -R 775 /var/www/edu/storage
```

### مشكلة: صفحة بيضاء

```bash
# مسح كل الـ Cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### مشكلة: CSRF Token Mismatch

```bash
# تأكد من SESSION_DOMAIN في .env
nano /var/www/edu/.env

# أضف:
SESSION_DOMAIN=.yourdomain.com
```

### مشكلة: Redis لا يعمل

```bash
# تحقق من حالة Redis
systemctl status redis-server

# أعد التشغيل
systemctl restart redis-server
```

### مشكلة: Queue لا يعمل

```bash
# تحقق من Supervisor
supervisorctl status

# أعد التشغيل
supervisorctl restart edu-worker:*
```

---

## ✅ قائمة التحقق النهائية

- [ ] السيرفر يعمل ويمكن الوصول إليه عبر SSH
- [ ] PHP 8.2 مثبت ويعمل
- [ ] Composer مثبت
- [ ] Node.js مثبت
- [ ] Redis يعمل (`redis-cli ping` = PONG)
- [ ] MariaDB يعمل وقاعدة البيانات منشأة
- [ ] المشروع مستنسخ في `/var/www/edu`
- [ ] ملف `.env` معدّل بالقيم الصحيحة
- [ ] Nginx يعمل والموقع متاح
- [ ] SSL مفعّل (HTTPS)
- [ ] Queue Worker يعمل
- [ ] الموقع يفتح بدون أخطاء

---

## 📞 الدعم

إذا واجهت أي مشكلة، تحقق من:
1. **Nginx Logs:** `tail -f /var/log/nginx/error.log`
2. **Laravel Logs:** `tail -f /var/www/edu/storage/logs/laravel.log`
3. **PHP Logs:** `tail -f /var/log/php8.2-fpm.log`

---

> 💡 **نصيحة:** احتفظ بنسخة من هذا الملف وملف `.env` للإنتاج في مكان آمن!
