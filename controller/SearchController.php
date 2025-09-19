<?php

class SearchController
{
    public function index()
    {
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';

        // Khởi tạo biến kết quả
        $articles = [];
        $users = [];
        $tags = [];

        if (!empty($q)) {
            // Gọi các model tương ứng để tìm kiếm
            require_once 'model/article/ArticlesModel.php';
            require_once 'model/user/UserModel.php';
            require_once 'model/article/TagsModel.php';

            $articles = ArticlesModel::searchArticles($q);            
            $users = UserModel::searchUsers($q);         
            $tags = TagsModel::searchTags($q);
            
        }

        // Gửi kết quả qua view
        ob_start();
        require 'view/page/Search.php';
        $content = ob_get_clean();

        $profile = false; // Dùng cho layout
        require 'view/layout/main.php';
    }
}
