<?php
require_once 'models/userModel.php';

class viewUserController
{
    public function detail()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header("Location: index.php");
            exit;
        }

        $user_id = intval($_GET['id']);
        $user = UserModel::getUserById($user_id);

        if (!$user) {
            echo "Không tìm thấy người dùng!";
            exit;
        }

        // Lấy bài viết của user
        $articles = UserModel::getArticlesByAuthorId($user_id);

        // Gọi view
        include 'view/page/viewProfileuser.php';
    }
}

// Gọi controller
$controller = new viewUserController();
$controller->detail();
