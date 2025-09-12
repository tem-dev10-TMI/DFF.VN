<?php

class UserController {
    
    public static function login() {
        // Kiểm tra method POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../index.php');
            exit;
        }
        
        // Lấy dữ liệu từ form
        $username = $_POST['userName'] ?? '';
        $password = $_POST['password'] ?? '';
        $action = $_POST['action'] ?? '';
        
        // Kiểm tra action
        if ($action !== 'login') {
            header('Location: ../index.php');
            exit;
        }
        
        // Validate dữ liệu
        $errors = [];
        
        if (empty($username)) {
            $errors[] = 'Vui lòng nhập tài khoản';
        }
        
        if (empty($password)) {
            $errors[] = 'Vui lòng nhập mật khẩu';
        }
        
        // Nếu có lỗi, hiển thị thông báo
        if (!empty($errors)) {
            session_start();
            $_SESSION['login_errors'] = $errors;
            header('Location: ../index.php');
            exit;
        }
        
        // TODO: Kiểm tra tài khoản với database
        // Tạm thời kiểm tra đơn giản
        if ($username === 'admin' && $password === '123456') {
            // Đăng nhập thành công
            session_start();
            $_SESSION['user_id'] = 1;
            $_SESSION['username'] = $username;
            $_SESSION['login_success'] = 'Đăng nhập thành công!';
            
            // Redirect về trang chủ
            header('Location: ../index.php');
            exit;
        } else {
            // Đăng nhập thất bại
            session_start();
            $_SESSION['login_errors'] = ['Tài khoản hoặc mật khẩu không đúng'];
            header('Location: ../index.php');
            exit;
        }
    }
    
    public static function logout() {
        session_start();
        session_destroy();
        header('Location: ../index.php');
        exit;
    }
}

// Xử lý request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'login':
            UserController::login();
            break;
        default:
            header('Location: ../index.php');
            exit;
    }
} else {
    header('Location: ../index.php');
    exit;
}

?>
