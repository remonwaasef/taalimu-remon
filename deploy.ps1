# Deployment Script for Edu SaaS
# This script pushes the current branch to 'main' on GitHub and updates the production server.

$currentBranch = git branch --show-current

$confirm = Read-Host "Are you sure you want to COMMIT and DEPLOY these changes to the LIVE server? (y/n)"
if ($confirm -ne "y") {
    Write-Host "Deployment cancelled." -ForegroundColor Yellow
    exit 0
}

Write-Host "--- 1. Committing local changes ---" -ForegroundColor Cyan
git add .
$commitMsg = "Auto-deploy $(Get-Date -Format 'yyyy-MM-dd HH:mm')"
git commit -m $commitMsg

Write-Host "--- 2. Pushing to GitHub (main) ---" -ForegroundColor Cyan
git push origin ${currentBranch}:main

Write-Host "--- 3. Updating Production Server ---" -ForegroundColor Cyan
# Configuration
$userToken = "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"
$repoName = "remonwaasef/taalimu-remon"
$remotePath = "/home/taalimu/htdocs/taalimu.com"
$gitUrl = "https://x-access-token:${userToken}@github.com/${repoName}.git"

# Remote Command: Force Pull using direct URL to bypass any server-side credential issues
$remoteCmd = "bash -lc 'git config --global --add safe.directory $remotePath && cd $remotePath && export GIT_TERMINAL_PROMPT=0 && cp -f .env .env.deploy_backup && git fetch $gitUrl main && git reset --hard FETCH_HEAD && cp -f .env.deploy_backup .env && export COMPOSER_ALLOW_SUPERUSER=1 && composer install --no-dev --optimize-autoloader && npm run build && php artisan migrate --force && php artisan storage:link && php artisan optimize:clear && chown -R taalimu:taalimu storage bootstrap/cache && chmod -R 775 storage bootstrap/cache && php artisan queue:restart'"

ssh root@46.202.155.30 $remoteCmd

if ($LASTEXITCODE -ne 0) {
    Write-Host "--- ERROR: Deployment failed on server! ---" -ForegroundColor Red
    exit $LASTEXITCODE
}

Write-Host "--- Done! Your changes are live. ---" -ForegroundColor Green

