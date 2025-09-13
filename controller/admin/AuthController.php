<?php
class AuthController {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function loginForm() {
        include __DIR__ . '/../../view/admin/views/auth/login.php';
    }

    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :u LIMIT 1");
        $stmt->execute([':u' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];
            header("Location: " . BASE_URL . "/admin.php?route=dashboard");
        } else {
            flash('error', 'Sai tên đăng nhập hoặc mật khẩu');
            header("Location: " . BASE_URL . "/admin.php?route=login");
        }
    }

    public function logout() {
        unset($_SESSION['user']);
        session_destroy();
        header("Location: " . BASE_URL . "/admin.php?route=login");
    }
}
