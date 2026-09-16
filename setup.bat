@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul
title Setup Projek Laravel Local

echo ========================================================
echo        SETUP OTOMATIS PROJEK LARAVEL LOCAL
echo ========================================================
echo.

:: ---------------------------------------------------------
:: 1. DETEKSI ENVIRONMENT (PHP, COMPOSER, NODE/NPM)
:: ---------------------------------------------------------
echo [1/6] Memeriksa Environment PHP, Composer, NPM...

:: Cek PHP
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
    if defined PHP_PATH (
        set "PATH=!PHP_PATH!;!PATH!"
        echo [OK] PHP ditemukan di: !PHP_PATH!
    ) else (
        echo [X] ERROR: PHP tidak ditemukan di PATH, Laragon, maupun XAMPP!
        echo     Silakan install Laragon atau tambahkan PHP ke System PATH.
        pause
        exit /b 1
    )
) else (
    echo [OK] PHP terdeteksi di System PATH.
)

:: Cek Composer
where composer >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    if exist "C:\ProgramData\ComposerSetup\bin\composer.bat" (
        set "PATH=C:\ProgramData\ComposerSetup\bin;!PATH!"
        echo [OK] Composer ditemukan di ComposerSetup.
    ) else if exist "C:\laragon\bin\composer\composer.bat" (
        set "PATH=C:\laragon\bin\composer;!PATH!"
        echo [OK] Composer ditemukan di Laragon.
    ) else if exist "%APPDATA%\Composer\vendor\bin" (
        set "PATH=%APPDATA%\Composer\vendor\bin;!PATH!"
        echo [OK] Composer ditemukan di AppData.
    ) else (
        echo [X] ERROR: Composer tidak ditemukan! Silakan install Composer.
        pause
        exit /b 1
    )
) else (
    echo [OK] Composer terdeteksi di System PATH.
)

:: Cek Node.js / NPM
where npm >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    if exist "C:\Program Files\nodejs\npm.cmd" (
        set "PATH=C:\Program Files\nodejs;!PATH!"
        echo [OK] Node.js/NPM ditemukan di Program Files.
    ) else if exist "C:\laragon\bin\nodejs" (
        for /d %%D in ("C:\laragon\bin\nodejs\node*") do (
            if exist "%%D\npm.cmd" set "NODE_PATH=%%D"
        )
        if defined NODE_PATH (
            set "PATH=!NODE_PATH!;!PATH!"
            echo [OK] Node.js/NPM ditemukan di Laragon.
        )
    )
) else (
    echo [OK] NPM terdeteksi di System PATH.
)

echo.

:: ---------------------------------------------------------
:: 2. SETUP KONFIGURASI (.env) & DIREKTORI
:: ---------------------------------------------------------
echo [2/6] Memeriksa File Konfigurasi .env dan Direktori...
if not exist ".env" (
    if exist ".env.example" (
        echo [+] Menyalin .env.example menjadi .env...
        copy ".env.example" ".env" >nul
        echo [OK] File .env berhasil dibuat.
    ) else (
        echo [X] ERROR: File .env.example tidak ditemukan!
        pause
        exit /b 1
    )
) else (
    echo [OK] File .env sudah ada.
)

:: Cek & Siapkan Database MySQL di Laragon jika script ada
if exist "database\prepare_mysql.php" (
    call php database/prepare_mysql.php
)

:: Pastikan fallback SQLite tersedia
if not exist "database\database.sqlite" (
    type nul > "database\database.sqlite"
)

:: Menyiapkan Direktori Storage & Cache serta izin akses tulis
if not exist "bootstrap\cache" mkdir "bootstrap\cache"
if not exist "storage\framework\cache" mkdir "storage\framework\cache"
if not exist "storage\framework\sessions" mkdir "storage\framework\sessions"
if not exist "storage\framework\views" mkdir "storage\framework\views"
if not exist "storage\logs" mkdir "storage\logs"
attrib -r -s "bootstrap\cache" /d >nul 2>&1
attrib -r -s "bootstrap\cache\*.*" /s /d >nul 2>&1
attrib -r -s "storage" /d >nul 2>&1
attrib -r -s "storage\*.*" /s /d >nul 2>&1

echo.

:: ---------------------------------------------------------
:: 3. INSTALASI DEPENDENSI COMPOSER
:: ---------------------------------------------------------
echo [3/6] Menginstall Dependensi PHP via Composer...
echo     (Proses ini memindai paket vendor dan generate autoload...)
call composer install --ansi
if %ERRORLEVEL% NEQ 0 (
    echo [X] ERROR: Gagal menginstall dependensi composer!
    pause
    exit /b 1
)
echo [OK] Composer install selesai.

echo.

:: ---------------------------------------------------------
:: 4. GENERATE KEY & MIGRASI DATABASE
:: ---------------------------------------------------------
echo [4/6] Menyiapkan Application Key, Storage Link, dan Database...
call php artisan key:generate
call php artisan storage:link 2>nul

echo [+] Menjalankan migrasi tabel dan seeding data awal ke Database...
call php artisan migrate --seed --force
if %ERRORLEVEL% NEQ 0 (
    echo [!] Warning: Migrasi database gagal atau tertunda. Pastikan MySQL di Laragon sudah aktif.
) else (
    echo [OK] Migrasi dan seeding database berhasil.
)
echo [OK] Setup Laravel Core selesai.

echo.

:: ---------------------------------------------------------
:: 5. INSTALASI & BUILD DEPENDENSI NPM
:: ---------------------------------------------------------
echo [5/6] Menginstall dan Build Dependensi Frontend via NPM...
where npm >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    call npm install
    if %ERRORLEVEL% NEQ 0 (
        echo [!] Warning: npm install mengalami masalah.
    ) else (
        echo [+] Compiling assets via Vite...
        call npm run build
    )
) else (
    echo [!] NPM tidak tersedia, melewati langkah npm install dan build.
)

echo.

:: ---------------------------------------------------------
:: 6. SELESAI
:: ---------------------------------------------------------
echo ========================================================
echo        SETUP SELESAI! PROJEK SIAP DIGUNAKAN.
echo ========================================================
echo.
echo Anda dapat menjalankan projek kapan saja dengan:
echo  1. Klik 2x file 'start.bat'
echo  2. Atau jalankan perintah: composer run dev
echo.

set "CHOICE="
set /p "CHOICE=Apakah Anda ingin langsung menjalankan projek sekarang? [Y/N]: "
if /i "!CHOICE!"=="Y" (
    start "" start.bat
)

echo.
echo Tekan sembarang tombol untuk keluar...
pause >nul
