<?php
function require_login() {
    $currentRoute = $_GET['route'] ?? 'dashboard';
    if (empty($_SESSION['user']) && $currentRoute !== 'login') {
        header("Location: " . BASE_URL . "/index.php?route=login");
        exit;
    }
}

function require_role($role) {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== $role) {
        // Có thể redirect về login hoặc báo lỗi
        die("Bạn không có quyền truy cập trang này!");
    }
}
