<?php
class loginController
{
    public static function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Lưu lại URL trang trước đó để quay lại sau khi đăng nhập thành công
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];
            // Chỉ lưu nếu trang trước đó không phải là trang login/register để tránh vòng lặp
            if (strpos($referer, 'login') === false && strpos($referer, 'register') === false) {
                $_SESSION['redirect_url'] = $referer;
            }
        }
        // Load model
        require_once __DIR__ . '/../../model/user/userModel.php';
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!$username || !$password) {
                $error = "Vui lòng nhập đầy đủ thông tin.";
            } else {
                // Lấy thông tin user từ DB
                /* $user = UserModel::getUserByUsernameOrEmail($usernameOrEmail); */
                $loginModel = new UserModel();
                $user = $loginModel->verifyUser($username, $password);

                if ($user) {
                    // Tạo và cập nhật session token
                    $token = bin2hex(random_bytes(32));
                    UserModel::updateSessionToken($user['id'], $token);

                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'username' => $user['username'],
                        'password_hash' => $user['password_hash'],
                        'email' => $user['email'],
                        'phone' => $user['phone'],
                        'role' => $user['role'],
                        'avatar_url' => $user['avatar_url'] ?? null,
                        'session_token' => $token // Lưu token vào session
                    ];
                    // Lưu session khi login thành công
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_username'] = $user['username'];
                    $_SESSION['user_password_hash'] = $user['password_hash'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_avatar_url'] = $user['avatar_url'] ?? null;
                    // 1) Đảm bảo có cookie token cho tracking
                    if (empty($_COOKIE['site_token'])) {
                        $siteToken = bin2hex(random_bytes(32)); // 64 ký tự hex
                        // Cookie 1 năm, httponly
                        setcookie('site_token', $siteToken, time() + 365 * 24 * 3600, '/', '', false, true);
                    } else {
                        $siteToken = $_COOKIE['site_token'];
                    }

                    // 2) Ghi nhận trạng thái đăng nhập vào bảng visits
                    require_once __DIR__ . '/../../config/db.php';
                    $db = new connect();
                    $pdo = $db->db;

                    $now = date('Y-m-d H:i:s');
                    $stmt = $pdo->prepare("
    INSERT INTO visits (token, first_seen, last_seen, hits, user_id, is_logged_in, login_at)
    VALUES (:t, :now, :now, 1, :uid, 1, :now)
    ON DUPLICATE KEY UPDATE
        last_seen     = VALUES(last_seen),
        hits          = hits + 1,
        user_id       = VALUES(user_id),
        is_logged_in  = 1,
        login_at      = VALUES(login_at)
");
                    $stmt->execute([
                        ':t' => $siteToken,
                        ':now' => $now,
                        ':uid' => (int) $user['id'],
                    ]);

                    // Điều hướng về trang đã lưu hoặc trang chủ
                    $redirectUrl = $_SESSION['redirect_url'] ?? BASE_URL . '/';
                    unset($_SESSION['redirect_url']); // Xóa URL đã lưu khỏi session
                    header('Location: ' . $redirectUrl);
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
        session_destroy();

        // 3) Chuyển hướng về trang chủ (hoặc trang bạn muốn)
        header('Location: ' . (defined('BASE_URL') ? BASE_URL : '/'));
        exit;
    }

}
