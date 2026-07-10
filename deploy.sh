#!/bin/bash
# سكريبت لتحديث السيرفر بسهولة (تحديث الكود، الدوكر، وقواعد البيانات)

set -e

echo "بدء عملية التحديث..."

# Maintenance mode
echo "وضع الموقع في وضع الصيانة..."
docker compose exec app php artisan down --retry=60 --secret="$(openssl rand -hex 12)" 2>/dev/null || true

# Backup
echo "إنشاء نسخة احتياطية..."
docker compose exec app php artisan backup:run --only-db 2>/dev/null || echo "تحذير: فشل النسخ الاحتياطي"

echo "1. جاري سحب أحدث التعديلات..."
git fetch origin main
git stash 2>/dev/null || true
git reset --hard origin/main

echo "2. جاري إعادة بناء الحاويات..."
docker compose build --pull
docker compose up -d --wait

echo "3. جاري تحديث قواعد البيانات..."
docker compose exec -T app php artisan migrate --force

echo "4. جاري تحسين الأداء..."
docker compose exec -T app php artisan optimize
docker compose exec -T app php artisan storage:link || true

echo "5. جاري بناء ملفات الواجهة..."
docker compose exec -T app npm ci --no-audit --no-fund
docker compose exec -T app npm run build

echo "6. جاري إعادة تشغيل العمال..."
docker compose exec -T app php artisan queue:restart

# Exit maintenance mode
echo "رفع الموقع من الصيانة..."
docker compose exec -T app php artisan up

echo "اكتمل التحديث بنجاح!"
