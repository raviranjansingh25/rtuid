@echo off
REM Run Laravel with PHP 8.2 (does not change system/XAMPP default PHP 7.4)
cd /d "%~dp0"
"C:\xampp\php82\php.exe" artisan serve --host=127.0.0.1 --port=8000
