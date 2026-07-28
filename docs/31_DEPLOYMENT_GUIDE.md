# 31_DEPLOYMENT_GUIDE - Infrastructure & Production Deployment

> **Purpose**: Instructions for deploying Taalimu.com to production KVM VPS / Linux servers, configuring environment variables, running deployment scripts, and rollback procedures.

---

## Production Requirements

- **Operating System**: Ubuntu 22.04 / 24.04 LTS (or Debian 12).
- **Web Server**: Nginx with PHP-FPM 8.4.
- **PHP Extensions**: `pdo_mysql`, `mbstring`, `gd`, `zip`, `xml`, `bcmath`, `curl`, `intl`, `redis`.
- **Database**: MySQL 8.0+ or MariaDB 10.6+.
- **Process Manager**: Supervisor (for `php artisan queue:listen` and `php artisan reverb:start`).

---

## Production Deployment Script (`deploy.sh`)

```bash
#!/bin/bash
set -e

echo "Starting Deployment..."

# 1. Pull latest git code
git pull origin main

# 2. Install PHP production dependencies
composer install --no-dev --optimize-autoloader

# 3. Run Database Migrations
php artisan migrate --force

# 4. Clear and Cache Configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 5. Build Frontend Assets
npm install
npm run build

# 6. Restart Queue Workers & Reverb WebSockets
php artisan queue:restart
supervisorctl restart all

echo "Deployment Successful!"
```

---

## Rollback Protocol

If a production deployment encounters a critical crash:
1. Revert Git repository to last stable commit tag (`git reset --hard v1.0.0`).
2. Run database rollback if migrations were executed (`php artisan migrate:rollback`).
3. Clear caches (`php artisan config:clear && php artisan cache:clear`).
4. Restart FPM and queue workers (`systemctl restart php8.4-fpm`).
