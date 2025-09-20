<?php
require_once __DIR__ . '/../model/BlogModel.php';

class BlogController {
    private $model;

    public function __construct() {
        $this->model = new BlogModel();
    }

    // Hiển thị chi tiết bài viết theo id
public function show($id) {
    $article = $this->model->getById($id);
    if (!$article) {
        die("Bài viết không tồn tại!");
    }

    $relatedArticles = $this->model->getRelated($article['topic_id'], $article['id']);

    // Tạo content từ view detail_blog.php
    ob_start();
    require __DIR__ . '/../view/page/details_Blog.php';
    $content = ob_get_clean();

    // Gọi layout chính
    require __DIR__ . '/../view/layout/main.php';
}

    // Hiển thị chi tiết bài viết theo slug (nếu bạn muốn SEO)
    public function showBySlug($slug) {
        $article = $this->model->getBySlug($slug);
        if (!$article) {
            die("Bài viết không tồn tại!");
        }

        $relatedArticles = $this->model->getRelated($article['topic_id'], $article['id']);
        
        require __DIR__ . '/../view/page/details_Blog.php';
    }
}
