<?php
// config.php
session_start();

// Thông tin kết nối Aiven
define('DB_HOST', 'mysql-37b8db9-nguyenquangtrung13062005-8a5b.d.aivencloud.com');
define('DB_PORT', '27925');
define('DB_NAME', 'dff_db');
define('DB_USER', 'avnadmin');
define('DB_PASS', 'AVNS_brCrqfhHuYX88MkBMqS');

define('BASE_URL', '/startmin_full_admin_final');
define('UPLOADS_DIR', __DIR__ . '/uploads');

if (!is_dir(UPLOADS_DIR)) mkdir(UPLOADS_DIR, 0755, true);

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die('DB Connect error: ' . $e->getMessage());
}

// autoload models/controllers
spl_autoload_register(function($class){
    $paths = [
        __DIR__ . '/models/' . $class . '.php',
        __DIR__ . '/controllers/' . $class . '.php'
    ];
    foreach ($paths as $p) if (file_exists($p)) require_once $p;
});

require_once __DIR__ . '/helpers.php';
