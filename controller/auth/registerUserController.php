<?php
class registerUserController
{
    public static function index()
    {
        require_once __DIR__.'/../../model/user/userModel.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'] ?? 'user';

            $name = trim($_POST['name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? ''; // FIXED
            $phone = trim($_POST['phone'] ?? '');
            $password_confirm = $_POST['password_confirm'] ?? '';
            $avatar_url = '';
            $cover_photo = '';
            $description = trim($_POST['description'] ?? '');

            // Kiểm tra mật khẩu
            if ($password !== $password_confirm) {
                $_SESSION['error'] = "Mật khẩu xác nhận không khớp.";
                header('Location: ' . BASE_URL . '/register');
                exit;
            }

            // Kiểm tra tồn tại username/email
            if (UserModel::checkUsernameOrEmailExists($username, $email)) {
                $_SESSION['error'] = "Email hoặc username đã tồn tại.";
                header('Location: ' . BASE_URL . '/register');
                exit;
            }

            // Đăng ký user
            $user = UserModel::registerUser($name, $username, $email, $password, $phone, $role, $avatar_url, $cover_photo, $description);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                echo "<script>
                    alert('Đăng ký thành công!');
                    window.location.href = '" . BASE_URL . "/';
                </script>";
                exit;
            } else {
                $_SESSION['error'] = "Đăng ký thất bại.";
                header('Location: ' . BASE_URL . '/register');
                exit;
            }
        }

        //Load view
        ob_start();
        require_once __DIR__.'/../../view/layout/header.php';
        $content = ob_get_clean();
        require_once __DIR__.'/../../view/layout/main.php';
    }
}
