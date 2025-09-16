<?php
require_once __DIR__ . '/../../model/user/userModel.php';


class viewUserController
{
    public function detail()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header("Location: index.php");
            exit;
        }

        $user_id = intval($_GET['id']);

        // Lấy thông tin user
        $user = UserModel::getUserById($user_id);

        if (!$user) {
            echo "Không tìm thấy người dùng!";
            exit;
        }

        // Lấy bài viết
        $articles = UserModel::getArticlesByAuthorId($user_id);

        // Gọi view + truyền biến
        include 'view/page/viewProfileuser.php';
    }
}

// Chạy controller
$controller = new viewUserController();
$controller->detail();
