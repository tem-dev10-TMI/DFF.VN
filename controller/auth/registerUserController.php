<?php
class RegisterUserController
{
    public static function index()
    {
        require_once 'model/user/userModel.php';
        $modelUser = new UserModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'] ?? 'user';
            $name = trim($_POST['name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password_hash'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            $phone = trim($_POST['phone'] ?? '');
            $avatar_url = '';
            $cover_photo = '';
            $description = trim($_POST['description'] ?? '');

            // Kiểm tra mật khẩu
            if ($password !== $password_confirm) {
                $_SESSION['error'] = "Mật khẩu xác nhận không khớp.";
                header('Location: ' . BASE_URL . '/register');
                exit;
            }

            // Kiểm tra username/email tồn tại
            if ($modelUser->checkUsernameOrEmailExists($username, $email)) {
                $_SESSION['error'] = "Email hoặc username đã tồn tại.";
                header('Location: ' . BASE_URL . '/register');
                exit;
            }

            // Thực hiện đăng ký
            $user = $modelUser->registerUser($name, $username, $email, $password, $phone, $role, $avatar_url, $cover_photo, $description);

            if ($user) {
                // Lưu session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                header('Location: ' . BASE_URL . '/');
                exit;
            } else {
                $_SESSION['error'] = "Đăng ký thất bại.";
                header('Location: ' . BASE_URL . '/register');
                exit;
            }
        }

        // Load view
        ob_start();
        require_once 'view/page/auth/registerUser.php';
        $content = ob_get_clean();

        require_once 'view/layout/main.php';
    }
}
