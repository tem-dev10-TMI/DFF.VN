<?php
class loginController
{
    public static function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Load model
        require_once __DIR__ . '/../../model/user/userModel.php';
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password        = $_POST['password'] ?? '';

            if (!$username || !$password) {
                $error = "Vui lòng nhập đầy đủ thông tin.";
            } else {
                // Lấy thông tin user từ DB
                /* $user = UserModel::getUserByUsernameOrEmail($usernameOrEmail); */
                $loginModel = new UserModel();
                $user = $loginModel->verifyUser($username, $password);

                if ($user) {
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'username' => $user['username'],
                        'password_hash' => $user['password_hash'],
                        'email' => $user['email'],
                        'phone' => $user['phone'],
                        'role' => $user['role'],
                        'avatar_url' => $user['avatar_url'] ?? null,
                    ];
                    // Lưu session khi login thành công
                    $_SESSION['user_id']           = $user['id'];
                    $_SESSION['user_name']         = $user['name'];
                    $_SESSION['user_username']     = $user['username'];
                    $_SESSION['user_password_hash'] = $user['password_hash'];
                    $_SESSION['user_email']        = $user['email'];
                    $_SESSION['user_role']         = $user['role'];
                    $_SESSION['user_avatar_url']   = $user['avatar_url'] ?? null;

                    // Điều hướng về trang main
                    header('Location: ' . BASE_URL . '/');
                    exit;
                } else {
                    // Sai mật khẩu hoặc không tồn tại user
                    $_SESSION['login_error'] = "Sai tài khoản hoặc mật khẩu!";
                    header("Location: " . BASE_URL . "/");
                    exit;
                }
            }
        }
        //Load view
        ob_start();
        require_once __DIR__ . '/../../view/layout/header.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../view/layout/main.php';
    }

    public static function logout()
    {
        // Xóa session và logout
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
