# Deployment Script for Edu SaaS
# This script commits changes locally, pushes to GitHub, 
# and then tells the production server to pull and rebuild.

$commitMsg = Read-Host "Enter commit message (default: 'Auto-deploy')"
if (-not $commitMsg) { $commitMsg = "Auto-deploy $(Get-Date -Format 'yyyy-MM-dd HH:mm')" }

Write-Host "--- 1. Saving changes locally ---" -ForegroundColor Cyan
git add .
git commit -m $commitMsg

Write-Host "--- 2. Pushing to GitHub ---" -ForegroundColor Cyan
git push origin main

Write-Host "--- 3. Updating Production Server ---" -ForegroundColor Cyan
ssh root@46.202.155.30 "cd /home/taalimu/htdocs/taalimu.com && \
    git pull origin main && \
    export PATH=/usr/local/bin:/usr/bin:/bin:/usr/local/games:/usr/games && \
    composer install --no-dev --optimize-autoloader && \
    npm run build && \
    php artisan migrate --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache"

Write-Host "--- Done! Your changes are live. ---" -ForegroundColor Green
