<?php
class viewUserController
{
    public function detail()
    {
        if (!isset($_GET['id'])) {
            header("Location: index.php");
            exit();
        }

        $user_id = intval($_GET['id']);

        $user = UserModel::getUserById($user_id);
        if (!$user) {
            echo "Không tìm thấy người dùng!";
            exit();
        }

        $articles = UserModel::getArticlesByAuthorId($user_id);

        require_once "view/users/viewUser.php";
    }
}
