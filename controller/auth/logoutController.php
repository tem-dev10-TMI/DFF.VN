<?php
require_once __DIR__ . '/../../model/user/userModel.php';

class logoutController
{
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Xóa session_token trong DB
        if (isset($_SESSION['user']['id'])) {
            UserModel::clearSessionToken($_SESSION['user']['id']);
        }
        // Bắt đầu session nếu chưa có (để có session_id, biến phiên...)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1) Đánh dấu đã đăng xuất cho token hiện tại (giảm số đang trực tuyến)
        $siteToken = $_COOKIE['site_token'] ?? null;
        if ($siteToken) {
            // Đường dẫn tới db.php tùy cấu trúc của bạn:
            // Nếu file controller đang ở: controller/UserController.php
            // thì ../../config/db.php sẽ ra đúng gốc/config/db.php
            require_once __DIR__ . '/../../config/db.php';
            $db = new connect();
            $pdo = $db->db;

            $stmt = $pdo->prepare("UPDATE visits SET is_logged_in = 0 WHERE token = :t");
            $stmt->execute([':t' => $siteToken]);
        }

        // 2) Dọn session sạch sẽ
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        // Clear session and cookies, then redirect to login
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        header('Location: ' . BASE_URL . '');
        exit;
    }
}
