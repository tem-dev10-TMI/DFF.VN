<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/config.php';

function require_login() {
    $currentRoute = $_GET['route'] ?? 'dashboard';
    if (empty($_SESSION['user']) && $currentRoute !== 'login') {
        header("Location: " . BASE_URL . "/admin.php?route=login");
        exit;
    }
}

function require_role($role) {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== $role) {
        die("Bạn không có quyền truy cập trang này!");
    }
}