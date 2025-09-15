<?php
class AuthController {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Hiển thị form đăng nhập
    public function loginForm() {
        include __DIR__ . '/../../view/admin/views/auth/login.php';
    }

    // Xử lý đăng nhập
    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :u LIMIT 1");
        $stmt->execute([':u' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Lưu thông tin vào session
            $_SESSION['user'] = [
                'id'       => $user['id'],
                'username' => $user['username'],
                'name'     => $user['name'],   // ✅ lấy cột name để hiển thị "Xin chào"
                'role'     => $user['role']
            ];

            header("Location: " . BASE_URL . "/admin.php?route=dashboard");
            exit;
        } else {
            // Sai tài khoản hoặc mật khẩu
            $_SESSION['error'] = "Sai tên đăng nhập hoặc mật khẩu";
            header("Location: " . BASE_URL . "/admin.php?route=login");
            exit;
        }
    }

    // Đăng xuất
    public function logout() {
        unset($_SESSION['user']);
        session_destroy();
        header("Location: " . BASE_URL . "/admin.php?route=login");
        exit;
    }
}
