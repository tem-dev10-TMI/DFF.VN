<?php
class registerUserController
{
    public static function index()
    {
        //Load model
        require_once 'model/user/userModel.php';
        $modelUser = new userModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'] ?? 'user';

            $name = trim($_POST['name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password_hash'] ?? '';
            $phone = trim($_POST['phone'] ?? '');
            $password_confirm = $_POST['password_confirm'] ?? '';
            $avatar_url = ''; // hoặc xử lý upload file nếu có
            $cover_photo = ''; // hoặc xử lý upload file nếu có
            $description = trim($_POST['description'] ?? '');

            // Kiểm tra mật khẩu
            if ($password !== $password_confirm) {
                $_SESSION['error'] = "Mật khẩu xác nhận không khớp.";
                header('Location: ' . BASE_URL . '/register');
                exit;
            }

            if (UserModel::checkUsernameOrEmailExists($username, $email)) {
                $_SESSION['error'] = "Email hoặc username đã tồn tại.";
                header('Location: ' . BASE_URL . '/register');
                exit;
            }
            $success = UserModel::registerUser($name, $username, $email, $password, $phone, $role, $avatar_url, $cover_photo, $description);

            if ($success) {
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
            // Đăng ký thành công, lưu thông tin người dùng vào session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_username'] = $user['username'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
        }

        //Load view
        ob_start();
        //Cần chỉnh sửa lại đường dẫn
        require_once 'view/layout/header.php';
        $content = ob_get_clean();

        //Load layout
        require_once 'view/layout/main.php';
    }
}
