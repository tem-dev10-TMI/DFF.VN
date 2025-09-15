<?php
require_once 'model/newsmodel.php';

class NewsController
{
    public static function index()
    {
        // Lấy danh sách bài viết
        $articles = NewsModel::getAllArticles();
       

        // Load view
        ob_start();
        require_once 'view/page/News.php';
        $content = ob_get_clean();

        // Load layout
        $profile = false;
        require_once 'view/layout/main.php';
    }

    public static function detail($id)
    {
        // Lấy 1 bài viết chi tiết
       $article   = NewsModel::getArticleById($id);

        // Load view
        ob_start();
        require_once 'view/news/detail.php';
        $content = ob_get_clean();

        // Load layout
        $profile = false;
        require_once 'view/layout/main.php';
    }
}