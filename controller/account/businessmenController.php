<?php
require_once __DIR__ . '/../models/user/businessmenModel.php';

class BusinessmenController
{
    // Danh sách tất cả doanh nhân
    public static function index()
    {
        $businessmen = businessmenModel::getAllBusinessmen();

        // Load view
        require __DIR__ . '/../views/page/home.php';
    }

    // Chi tiết 1 doanh nhân theo user_id
    public static function show($user_id)
    {
        $biz = businessmenModel::getBusinessByUserId($user_id);

        if (!$biz) {
            // Nếu không có dữ liệu → load 404
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        require __DIR__ . '/../views/businessmen/show.php';
    }

    // Form tạo doanh nhân
    public static function create()
    {
        require __DIR__ . '/../views/businessmen/create.php';
    }

    // Xử lý lưu doanh nhân mới
    public static function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'] ?? '';
            $birth_year = $_POST['birth_year'] ?? '';
            $nationality = $_POST['nationality'] ?? '';
            $education = $_POST['education'] ?? '';
            $position = $_POST['position'] ?? '';

            businessmenModel::registerBusiness($user_id, $birth_year, $nationality, $education, $position);

            header('Location: index.php?controller=businessmen&action=index');
            exit;
        }
    }

    // Form sửa doanh nhân
    public static function edit($user_id)
    {
        $biz = businessmenModel::getBusinessByUserId($user_id);
        require __DIR__ . '/../views/businessmen/edit.php';
    }

    // Xử lý update doanh nhân
    public static function update($user_id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $birth_year = $_POST['birth_year'] ?? '';
            $nationality = $_POST['nationality'] ?? '';
            $education = $_POST['education'] ?? '';
            $position = $_POST['position'] ?? '';

            businessmenModel::updateBusiness($user_id, $birth_year, $nationality, $education, $position);

            header('Location: index.php?controller=businessmen&action=index');
            exit;
        }
    }

    // Xóa doanh nhân
    public static function destroy($user_id)
    {
        businessmenModel::deleteBusiness($user_id);
        header('Location: index.php?controller=businessmen&action=index');
        exit;
    }
}
