<?php

/**
 * Script pembantu otomatis untuk memastikan Database MySQL di Laragon / local server sudah terbuat.
 */

$envFile = __DIR__ . '/../.env';
if (!file_exists($envFile)) {
    exit(0);
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$env = [];
foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) {
        continue;
    }
    if (str_contains($line, '=')) {
        [$key, $val] = explode('=', $line, 2);
        $env[trim($key)] = trim($val, " \t\n\r\0\x0B\"'");
    }
}

$dbConnection = $env['DB_CONNECTION'] ?? 'mysql';
if (!in_array($dbConnection, ['mysql', 'mariadb'])) {
    exit(0);
}

$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = $env['DB_PORT'] ?? '3306';
$database = $env['DB_DATABASE'] ?? 'web_wann';
$username = $env['DB_USERNAME'] ?? 'root';
$password = $env['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO("mysql:host={$host};port={$port}", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5,
    ]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "[OK] Database MySQL '{$database}' berhasil disiapkan di Laragon/MySQL." . PHP_EOL;
} catch (Throwable $e) {
    echo "[!] Catatan MySQL: " . $e->getMessage() . PHP_EOL;
    echo "    (Pastikan service MySQL di Laragon / XAMPP sudah dinyalakan / Start All)" . PHP_EOL;
}

