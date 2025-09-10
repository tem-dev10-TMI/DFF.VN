<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dff_vn');
define('DB_PORT', '3306');

// Detect BASE_URL automatically
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

define('BASE_URL', $protocol . "://" . $host . ($basePath ? $basePath : ''));

// Site Configuration
define('SITE_NAME', 'DFF.VN');
define('SITE_DESCRIPTION', 'Diễn đàn Tài chính Việt Nam');

// Security
define('SECRET_KEY', 'dff_vn_secret_key_2024');

// Upload Configuration
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Pagination
define('ITEMS_PER_PAGE', 10);

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');
