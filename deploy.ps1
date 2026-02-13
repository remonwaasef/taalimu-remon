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

# We use bash -lc to ensure the full environment (composer, npm, etc.) is loaded
$remoteCmd = "bash -lc 'git config --global --add safe.directory /home/taalimu/htdocs/taalimu.com && cd /home/taalimu/htdocs/taalimu.com && git pull origin main && export COMPOSER_ALLOW_SUPERUSER=1 && composer install --no-dev --optimize-autoloader && npm run build && php artisan migrate --force && php artisan optimize:clear'"

ssh root@46.202.155.30 $remoteCmd

if ($LASTEXITCODE -ne 0) {
    Write-Host "--- ERROR: Deployment failed on server! ---" -ForegroundColor Red
    exit $LASTEXITCODE
}

Write-Host "--- Done! Your changes are live. ---" -ForegroundColor Green
