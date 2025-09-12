<?php
require_once 'models/businessmenModel.php';
require_once 'models/userModel.php'; 

class RegisterBusinessmenController
{
    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $user_id     = $_POST['user_id'] ?? null;
            $birth_year  = $_POST['birth_year'] ?? null;
            $nationality = $_POST['nationality'] ?? null;
            $education   = $_POST['education'] ?? null;
            $position    = $_POST['position'] ?? null;

            // Kiểm tra dữ liệu cơ bản
            if (empty($user_id) || empty($birth_year) || empty($nationality) || empty($education) || empty($position)) {
                $error = "Vui lòng điền đầy đủ thông tin.";
                include 'views/registerBusinessmen.php';
                exit;
            }

            // Gọi model để insert vào DB
            $result = businessmenModel::registerBusiness($user_id, $birth_year, $nationality, $education, $position);

            if ($result) {
                // Nếu thêm thành công
                $success = "Đăng ký doanh nhân thành công!";
                include 'views/registerBusinessmen.php';
            } else {
                $error = "Có lỗi xảy ra khi đăng ký!";
                include 'views/registerBusinessmen.php';
            }
        } else {
            // Nếu là GET request => hiển thị form
            include 'views/registerBusinessmen.php';
        }
    }
}

// Chạy controller
$controller = new RegisterBusinessmenController();
$controller->handleRequest();
