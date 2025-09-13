<?php
// Database Configuration
define('DB_HOST', 'mysql-37b8db9-nguyenquangtrung13062005-8a5b.d.aivencloud.com');
define('DB_USER', 'avnadmin');
define('DB_PASS', 'AVNS_brCrqfhHuYX88MkBMqS');
define('DB_NAME', 'dff_db');
define('DB_PORT', '27925');
define('BASE_URL', 'http://localhost/DFF.VN');

// Site Configuration
define('SITE_NAME', 'DFF.VN');
define('SITE_DESCRIPTION', 'Diễn đàn Tài chính Việt Nam');

// dinh nghia image
define('UPLOADS_DIR', __DIR__ . '/../uploads');
define('UPLOADS_URL', BASE_URL . '/uploads');
// autoload models/controllers
spl_autoload_register(function($class){
    $paths = [
        __DIR__ . '../model/admin' . $class . '.php',
        __DIR__ . '../model/admin' . $class . '.php'
    ];
    foreach ($paths as $p) if (file_exists($p)) require_once $p;
});

require_once __DIR__ . '/../helpers.php';
