<?php
require_once __DIR__ . '/../models/ArticlesModel.php';

class ArticlesController
{
    // Danh sách bài viết
    public static function index()
    {
        $articles = ArticlesModel::getAllArticles();
        // nạp view
        require __DIR__ . '/../views/articles/index.php';
    }

    // Chi tiết 1 bài viết
    public static function show($id)
    {
        $article = ArticlesModel::getArticleById($id);
        if (!$article) {
            // có thể chuyển hướng hoặc load trang 404
            require __DIR__ . '/../views/errors/404.php';
            return;
        }
        require __DIR__ . '/../views/articles/show.php';
    }

    // Form tạo bài viết
    public static function create()
    {
        require __DIR__ . '/../views/articles/create.php';
    }

    // Xử lý lưu bài viết
    public static function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title   = $_POST['title']   ?? '';
            $summary = $_POST['summary'] ?? '';
            $content = $_POST['content'] ?? '';
            $image   = $_POST['main_image_url'] ?? '';
            $author  = $_POST['author_id'] ?? '';
            $topic   = $_POST['topic_id'] ?? '';

            ArticlesModel::addArticle($title,$summary,$content,$image,$author,$topic);
            header('Location: index.php?controller=articles&action=index');
            exit;
        }
    }

    // Form sửa
    public static function edit($id)
    {
        $article = ArticlesModel::getArticleById($id);
        require __DIR__ . '/../views/articles/edit.php';
    }

    // Xử lý update
    public static function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            ArticlesModel::updateArticle(
                $id,
                $_POST['title'],
                $_POST['summary'],
                $_POST['content'],
                $_POST['main_image_url'],
                $_POST['topic_id'],
                $_POST['status'] ?? 'public',
                $_POST['is_hot'] ?? 0,
                $_POST['is_analysis'] ?? 0
            );
            header('Location: index.php?controller=articles&action=index');
            exit;
        }
    }

    // Xoá bài viết
    public static function destroy($id)
    {
        ArticlesModel::deleteArticle($id);
        header('Location: index.php?controller=articles&action=index');
        exit;
    }
}
