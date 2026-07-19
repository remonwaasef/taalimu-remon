@echo off
chcp 65001 > nul
echo ==========================================
echo       Taalimu Environment Switcher
echo ==========================================
echo [1] Switch to LOCAL Environment (التطوير المحلي)
echo [2] Switch to PRODUCTION Environment (السيرفر الحقيقي)
echo ==========================================
set /p choice="Enter choice [1 or 2]: "

if "%choice%"=="1" (
    if exist .env.local (
        copy /y .env.local .env
        echo.
        echo [OK] Switched to LOCAL environment.
        echo [OK] تم التبديل إلى البيئة المحلية.
    ) else (
        echo [ERROR] .env.local file not found!
        echo [خطأ] لم يتم العثور على ملف .env.local!
    )
)

if "%choice%"=="2" (
    if exist .env.production (
        copy /y .env.production .env
        echo.
        echo [OK] Switched to PRODUCTION environment.
        echo [OK] تم التبديل إلى بيئة السيرفر الحقيقي.
    ) else (
        echo [ERROR] .env.production file not found!
        echo [خطأ] لم يتم العثور على ملف .env.production!
    )
)

echo.
pause
