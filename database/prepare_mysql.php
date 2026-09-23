<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';

try {
    $app = require __DIR__.'/../bootstrap/app.php';
    $app->make(Kernel::class)->bootstrap();

    if (config('database.default') !== 'mysql') {
        throw new RuntimeException('DB_CONNECTION harus mysql.');
    }

    $config = $app['db']->connection('mysql')->getConfig();
    $database = in_array('--testing', $argv, true)
        ? 'stockbarang_testing'
        : $config['database'];

    if (!is_string($database) || !preg_match('/^[a-zA-Z0-9_]+$/D', $database)) {
        throw new RuntimeException('Nama database tidak valid.');
    }

    // Connect without selecting a database so a fresh installation works.
    $config['database'] = null;
    $pdo = (new Illuminate\Database\Connectors\MySqlConnector)->connect($config);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "[OK] Database MySQL '{$database}' siap digunakan.".PHP_EOL;
} catch (Throwable $e) {
    fwrite(STDERR, '[ERROR] Gagal menyiapkan MySQL: '.$e->getMessage().PHP_EOL);
    fwrite(STDERR, 'Pastikan MySQL aktif, pdo_mysql tersedia, dan konfigurasi .env benar.'.PHP_EOL);
    exit(1);
}
