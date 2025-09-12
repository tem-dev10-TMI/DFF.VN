<?php
class loginController
{
    public static function index()
    {
        // Load model
        require_once 'model/user/UserModel.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usernameOrEmail = trim($_POST['username'] ?? '');
            $password        = $_POST['password'] ?? '';

            // Lấy thông tin user từ DB
            $user = UserModel::getUserByUsernameOrEmail($usernameOrEmail);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Lưu session khi login thành công
                $_SESSION['user_id']       = $user['id'];
                $_SESSION['user_name']     = $user['name'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_email']    = $user['email'];
                $_SESSION['user_role']     = $user['role'];

                // Điều hướng về trang main
                header('Location: ' . BASE_URL . '/main');
                exit;
            } else {
                // Sai mật khẩu hoặc không tồn tại user
                $_SESSION['error'] = "Sai tài khoản hoặc mật khẩu!";
                header('Location: ' . BASE_URL . '/login');
                exit;
            }
        } else {
            // Nếu không phải POST => load form login
            ob_start();
            require_once 'view/page/login.php'; // view login form
            $content = ob_get_clean();

            // Load layout chính
            require_once 'view/layout/main.php';
        }
    }

    public static function logout()
    {
        // Xóa session và logout
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
