#!/bin/bash

echo "=============================================="
echo "  Edu-SaaS Production Deployment Script (Linux)"
echo "=============================================="

# 1. Check Env
if [ ! -f .env ]; then
    echo "Error: .env file not found!"
    exit 1
fi

# 2. Update .env for Redis (using sed)
echo "[1/6] Configuring Redis..."
sed -i 's/^CACHE_STORE=.*/CACHE_STORE=redis/' .env
sed -i 's/^SESSION_DRIVER=.*/SESSION_DRIVER=redis/' .env

# 3. Optimize
echo "[2/6] Clearing Caches..."
php artisan optimize:clear

echo "[3/6] Caching Configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 4. Migrate
echo "[4/6] Migrating Database..."
php artisan migrate --force

# 5. Workers
echo "[5/6] Restarting Queue Workers..."
php artisan queue:restart

# 6. Permissions (Optional, common issue)
# chown -R www-data:www-data storage bootstrap/cache

echo "=============================================="
echo "  DEPLOYMENT COMPLETE! 🚀"
echo "=============================================="
