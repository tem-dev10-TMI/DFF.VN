<?php

class SearchController
{
    public function index()
    {
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';

        // TODO: Gọi model để tìm kiếm bài viết/người dùng/tags theo $q khi sẵn sàng
        // Ví dụ:
        // require_once 'model/article/articlesmodel.php';
        // $articles = ArticlesModel::searchArticles($q);

        ob_start();
        require_once __DIR__ . '/../view/page/Search.php';
        $content = ob_get_clean();
        $profile = false; // dùng cho layout
        require_once __DIR__ . '/../view/layout/main.php';
    }
}
