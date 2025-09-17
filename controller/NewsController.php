<?php
require_once __DIR__ . '/../model/NewsModel.php';

class NewsController
{
    public static function index()
    {
        // Lấy danh sách bài viết
        $articles = NewsModel::getAllArticles();
        $articles = NewsModel::getLatestArticles($limit = 6);

        // Load view
        ob_start();
        require_once __DIR__ . '/../view/page/News.php';
        $content = ob_get_clean();

        // Load layout
        $profile = false;
        require_once __DIR__ . '/../view/layout/main.php';
    }

    public static function detail($id)
    {
        // Lấy 1 bài viết chi tiết
        $article   = NewsModel::getArticleById($id);

        // Load view
        ob_start();
        require_once __DIR__ . '/../view/news/detail.php';
        $content = ob_get_clean();

        // Load layout
        $profile = false;
        require_once __DIR__ . '/../view/layout/main.php';
    }
}
