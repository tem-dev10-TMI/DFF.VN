<?php
function touchVisit(PDO $pdo): void
{
    // Lấy/đặt token trình duyệt
    $token = $_COOKIE['v_token'] ?? bin2hex(random_bytes(16));
    if (!isset($_COOKIE['v_token'])) {
        setcookie('v_token', $token, time() + 60 * 60 * 24 * 365, '/', '', false, true);
    }

    $userId = $_SESSION['user']['id'] ?? null;
    $isLogged = isset($_SESSION['user']) ? 1 : 0;

    // Cần UNIQUE KEY(token) trong bảng visits để ON DUPLICATE KEY chạy được
    $sql = "INSERT INTO visits(token, first_seen, last_seen, hits, user_id, is_logged_in, login_at)
            VALUES(:t, NOW(), NOW(), 1, :uid, :islog, NULL)
            ON DUPLICATE KEY UPDATE last_seen = NOW(), hits = hits + 1, user_id = VALUES(user_id), is_logged_in = VALUES(is_logged_in)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':t' => $token,
        ':uid' => $userId,
        ':islog' => $isLogged
    ]);
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/config.php';

function require_login()
{
    $currentRoute = $_GET['route'] ?? 'dashboard';
    if (empty($_SESSION['user']) && $currentRoute !== 'login') {
        header("Location: " . BASE_URL . "/admin.php?route=login");
        exit;
    }
}

function require_role($role)
{
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== $role) {
        die("Bạn không có quyền truy cập trang này!");
    }
}