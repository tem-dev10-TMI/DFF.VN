<?php


// Database Configuration
$useConfig1 = false; // true dùng config 1, false dùng config 2

if ($useConfig1) {
    // Cấu hình 1 triển khai cho server nhưng database trỏ tại server
    define('DB_HOST', 'localhost');
    define('DB_USER', 'nguonnh1_tmi_mxh');
    define('DB_PASS', '9@hvPBN9yZrXwjH}');
    define('DB_NAME', 'nguonnh1_tmi_mxh');
    define('DB_PORT', '3306');
    define('BASE_URL', 'https://mxh.org.vn');
    define('SITE_NAME', 'MXH.ORG.VN');
    define('SITE_DESCRIPTION', 'Diễn đàn Tài chính Việt Nam');
} else {
    // Cấu hình 2 triển khai cho server hoặc local nhưng trỏ database aiven
    define('DB_HOST', 'mysql-37b8db9-nguyenquangtrung13062005-8a5b.d.aivencloud.com');
    define('DB_USER', 'avnadmin');
    define('DB_PASS', 'AVNS_brCrqfhHuYX88MkBMqS');
    define('DB_NAME', 'dff_db');
    define('DB_PORT', '27925');
    $use="local"; // mặc định server
    if ($use == "server") {
        define('BASE_URL', 'https://mxh.org.vn');
        define('SITE_NAME', 'MXH.ORG.VN');
        define('SITE_DESCRIPTION', 'Diễn đàn Tài chính Việt Nam');
    } else {
        define('BASE_URL', 'http://localhost/DFF.VN');
        define('SITE_NAME', 'DFF.VN');
        define('SITE_DESCRIPTION', 'Diễn đàn Tài chính Việt Nam');
    }
}

// Google OAuth2
define('GOOGLE_CLIENT_ID', '883631790996-ivg36caiogkoqbaptil3k9l7jumg1aif.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', BASE_URL . '/public/callback.php');
// Facebook API
define('FACEBOOK_APP_ID', '813811857990201');
define('FACEBOOK_APP_SECRET', '87d73962040afdd642d7419686017f68');
define('FACEBOOK_REDIRECT_URI', BASE_URL . '/public/facebook-callback.php');
define('FACEBOOK_SCOPE', 'public_profile,email');

// Uploads
define('UPLOADS_DIR', __DIR__ . '/../uploads');
define('UPLOADS_URL', BASE_URL . '/uploads');

// Autoload models/controllers
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../model/admin/' . $class . '.php',
        __DIR__ . '/../controller/admin/' . $class . '.php'
    ];
    foreach ($paths as $p) {
        if (file_exists($p)) {
            require_once $p;
            return;
        }
    }
});

// Helpers
require_once __DIR__ . '/../helpers.php';
