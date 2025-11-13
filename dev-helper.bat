@echo off
REM Pelangi Weaving - Development Helper Script (Windows)

:menu
cls
echo ========================================
echo 🌈 Pelangi Weaving - Development Helper
echo ========================================
echo.
echo Pilih aksi:
echo 1. Setup Fresh (migrate:fresh + seed)
echo 2. Clear All Cache
echo 3. Run Server
echo 4. Run Tests
echo 5. Check Routes
echo 6. Check Models
echo 7. Storage Link
echo 8. Optimize
echo 9. Show Logs
echo 0. Exit
echo.

set /p choice="Pilihan (0-9): "

if "%choice%"=="1" goto setup
if "%choice%"=="2" goto clear
if "%choice%"=="3" goto serve
if "%choice%"=="4" goto test
if "%choice%"=="5" goto routes
if "%choice%"=="6" goto models
if "%choice%"=="7" goto storage
if "%choice%"=="8" goto optimize
if "%choice%"=="9" goto logs
if "%choice%"=="0" goto exit
goto invalid

:setup
echo.
echo 🔄 Running migrate:fresh --seed...
php artisan migrate:fresh --seed
echo ✅ Done!
pause
goto menu

:clear
echo.
echo 🧹 Clearing all cache...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo ✅ Done!
pause
goto menu

:serve
echo.
echo 🚀 Starting server...
php artisan serve
pause
goto menu

:test
echo.
echo 🧪 Running tests...
php artisan test
pause
goto menu

:routes
echo.
echo 📋 Checking routes...
php artisan route:list
pause
goto menu

:models
echo.
echo 📦 Available models:
echo - Admin
echo - Pelanggan
echo - Category
echo - Product
echo - ProductVariant
echo - ProductImage
echo - Cart
echo - Order
echo - OrderItem
echo - Payment
echo - Pengiriman
echo.
set /p model="Model name to inspect: "
php artisan model:show %model%
pause
goto menu

:storage
echo.
echo 🔗 Creating storage link...
php artisan storage:link
echo ✅ Done!
pause
goto menu

:optimize
echo.
echo ⚡ Optimizing...
php artisan optimize
echo ✅ Done!
pause
goto menu

:logs
echo.
echo 📄 Showing last 50 lines of log...
powershell -Command "Get-Content storage\logs\laravel.log -Tail 50"
pause
goto menu

:invalid
echo.
echo ❌ Invalid choice!
pause
goto menu

:exit
echo.
echo 👋 Goodbye!
exit
