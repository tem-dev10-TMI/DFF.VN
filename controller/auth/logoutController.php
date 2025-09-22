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
