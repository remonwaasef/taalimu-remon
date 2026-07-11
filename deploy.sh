#!/bin/bash
# سكريبت لتحديث السيرفر بسهولة (تحديث الكود، الدوكر، وقواعد البيانات)

# إيقاف السكريبت في حال حدوث أي خطأ
set -e

echo "🚀 بدء عملية التحديث..."

echo "📥 1. جاري سحب أحدث التعديلات من مستودع الأكواد (GitHub/GitLab)..."
git pull origin main

echo "🐳 2. جاري إعادة بناء وتحديث حاويات الدوكر (Containers)..."
# تأكد من أن اسم الحاويات يتطابق مع ملف docker-compose.yml الخاص بك
docker compose build
docker compose up -d

echo "🔄 3. جاري تحديث قواعد البيانات (Migrations)..."
# افترضنا أن حاوية تطبيق لارافيل اسمها app
docker compose exec app php artisan migrate --force

echo "🧹 4. جاري تنظيف الكاش وتحسين الأداء (Optimization)..."
docker compose exec app php artisan optimize:clear
# إعادة بناء كاش الإعدادات والمسارات والقوالب — بدون هذه الخطوة يعمل الإنتاج بلا أي كاش
docker compose exec app php artisan optimize

echo "🎨 5. جاري بناء ملفات الواجهة الأمامية (Vite Assets)..."
docker compose exec app npm run build

echo "📬 6. جاري إعادة تشغيل عامل الطابور (Queue Worker) لالتقاط الكود الجديد..."
docker compose exec app php artisan queue:restart

echo "✅ اكتمل التحديث بنجاح! السيرفر الآن يعمل بآخر التعديلات."
