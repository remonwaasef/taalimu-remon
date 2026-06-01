@echo off
echo ==============================================
echo   Edu-SaaS Production Deployment Script (Windows)
echo ==============================================

echo [1/6] Environment Check...
if not exist .env (
    echo Warning: .env file not found! Creating from .env.example...
    copy .env.example .env
    if errorlevel 1 (
        echo Error: Could not create .env file!
        exit /b 1
    )
)

echo [2/6] Enabling Redis in .env...
powershell -Command "(Get-Content .env) -replace 'CACHE_STORE=.*', 'CACHE_STORE=redis' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'SESSION_DRIVER=.*', 'SESSION_DRIVER=redis' | Set-Content .env"

echo [3/6] Clearing Old Caches...
call php artisan optimize:clear

echo [4/6] Caching Configuration for Speed...
call php artisan config:cache
call php artisan route:cache
call php artisan view:cache
call php artisan event:cache

echo [5/6] Updating Database (Force)...
call php artisan migrate --force

echo [6/6] Restarting Queue Workers...
call php artisan queue:restart

echo ==============================================
echo   DEPLOYMENT COMPLETE! 🚀
echo   Please ensure your Redis server is running.
echo ==============================================
pause
