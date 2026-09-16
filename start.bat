@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul
title Jalankan Projek Laravel Local

echo ========================================================
echo           MENJALANKAN PROJEK LARAVEL LOCAL
echo ========================================================
echo.

:: ---------------------------------------------------------
:: DETEKSI ENVIRONMENT (PHP, COMPOSER, NODE/NPM)
:: ---------------------------------------------------------
where php >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    if exist "C:\laragon\bin\php" (
        for /d %%D in ("C:\laragon\bin\php\php*") do (
            if exist "%%D\php.exe" set "PHP_PATH=%%D"
        )
    )
    if not defined PHP_PATH (
        if exist "C:\laragon\bin\php\php.exe" set "PHP_PATH=C:\laragon\bin\php"
    )
    if not defined PHP_PATH (
        if exist "C:\xampp\php\php.exe" set "PHP_PATH=C:\xampp\php"
    )
    if defined PHP_PATH set "PATH=!PHP_PATH!;!PATH!"
)

where composer >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    if exist "C:\ProgramData\ComposerSetup\bin\composer.bat" (
        set "PATH=C:\ProgramData\ComposerSetup\bin;!PATH!"
    ) else if exist "C:\laragon\bin\composer\composer.bat" (
        set "PATH=C:\laragon\bin\composer;!PATH!"
    ) else if exist "%APPDATA%\Composer\vendor\bin" (
        set "PATH=%APPDATA%\Composer\vendor\bin;!PATH!"
    )
)

where npm >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    if exist "C:\Program Files\nodejs\npm.cmd" (
        set "PATH=C:\Program Files\nodejs;!PATH!"
    ) else if exist "C:\laragon\bin\nodejs" (
        for /d %%D in ("C:\laragon\bin\nodejs\node*") do (
            if exist "%%D\npm.cmd" set "NODE_PATH=%%D"
        )
        if defined NODE_PATH set "PATH=!NODE_PATH!;!PATH!"
    )
)

echo [+] Membuka aplikasi...
echo [+] Server lokal akan dimulai.
echo [+] Tekan Ctrl+C di jendela ini untuk menghentikan server.
echo.

:: Menjalankan script dev dari composer
call composer run dev

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [!] 'composer run dev' tidak berhasil, mencoba 'php artisan serve'...
    call php artisan serve
)

pause
