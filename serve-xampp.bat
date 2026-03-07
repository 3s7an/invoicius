@echo off
REM Spustenie Laravel servera s XAMPP PHP (ma GD extension pre PDF)
set PHP_XAMPP=C:\xampp\php\php.exe
if not exist "%PHP_XAMPP%" (
    echo Chyba: XAMPP PHP nebolo najdene na %PHP_XAMPP%
    pause
    exit /b 1
)
"%PHP_XAMPP%" artisan serve
