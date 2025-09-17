<?php
class registerUserController
{
    public static function index()
    {
        require_once __DIR__ . '/../../model/user/userModel.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'] ?? 'user';

            $name = trim($_POST['name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? ''; // FIXED
            $phone = trim($_POST['phone'] ?? '');
            $password_confirm = $_POST['password_confirm'] ?? '';

            // Kiểm tra mật khẩu
            if ($password !== $password_confirm) {
                echo "<script>
                    alert('Mật khẩu không khớp!');
                    window.location.href = '" . BASE_URL . "/';
                </script>";
                exit;
            }

            // Kiểm tra tồn tại username/email
            if (UserModel::checkUsernameOrEmailExists($username, $email)) {
                echo "<script>
                    alert('Email hoặc username đã tồn tại!');
                    window.location.href = '" . BASE_URL . "/';
                </script>";
                exit;
            }

            // Đăng ký user
            if ($role === 'user') {
                $success = UserModel::registerUser($name, $username, $email, $password, $phone, $role);
            }
            if ($success) {
                echo "<script>
                    alert('Đăng ký thành công!');
                    window.location.href = '" . BASE_URL . "/';
                </script>";
                exit;
            } else {
                echo "<script>
                    alert('Đăng ký thất bại!');
                    window.location.href = '" . BASE_URL . "/';
                </script>";
                exit;
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_username'] = $user['username'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
        }

        //Load view
        require_once __DIR__ . '/../../view/layout/header.php';
    }
}
