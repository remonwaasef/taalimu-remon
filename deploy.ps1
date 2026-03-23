# Deployment Script for Edu SaaS
# This script MUST be run from the 'main' branch.
# It merges 'dev' into 'main', pushes to GitHub, and updates the production server.

$currentBranch = git branch --show-current
if ($currentBranch -ne "main") {
    Write-Host "!!! ERROR: You are on branch [$currentBranch]. Deployment is only allowed from [main]. !!!" -ForegroundColor Red
    Write-Host "Please switch to main: git checkout main"
    exit 1
}

Write-Host "--- 1. Syncing with dev branch ---" -ForegroundColor Cyan
git merge dev --no-edit

$confirm = Read-Host "Are you sure you want to DEPLOY these changes to the LIVE server? (y/n)"
if ($confirm -ne "y") {
    Write-Host "Deployment cancelled." -ForegroundColor Yellow
    exit 0
}

Write-Host "--- 2. Pushing to GitHub (main) ---" -ForegroundColor Cyan
git push origin main

Write-Host "--- 3. Updating Production Server ---" -ForegroundColor Cyan
# We use bash -lc to ensure the full environment (composer, npm, etc.) is loaded
$remoteCmd = "bash -lc 'git config --global --add safe.directory /home/taalimu/htdocs/taalimu.com && cd /home/taalimu/htdocs/taalimu.com && export GIT_TERMINAL_PROMPT=0 && git pull origin main && export COMPOSER_ALLOW_SUPERUSER=1 && composer install --no-dev --optimize-autoloader && npm run build && php artisan migrate --force && php artisan storage:link && php artisan optimize:clear && chown -R taalimu:taalimu storage bootstrap/cache && chmod -R 775 storage bootstrap/cache'"

ssh root@46.202.155.30 $remoteCmd

if ($LASTEXITCODE -ne 0) {
    Write-Host "--- ERROR: Deployment failed on server! ---" -ForegroundColor Red
    exit $LASTEXITCODE
}

Write-Host "--- Done! Your changes are live. ---" -ForegroundColor Green

