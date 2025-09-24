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
    public static function loadMore()
    {
        if(session_status() == PHP_SESSION_NONE) session_start();

        // Chỉ trả JSON, tắt mọi output thừa
        ob_clean();
        ini_set('display_errors', 0);
        error_reporting(0);
        header('Content-Type: application/json');

        try {
            require_once __DIR__ . '/../model/NewsModel.php';

            $offset = isset($_GET['offset']) ? max(0, intval($_GET['offset'])) : 0;
            $limit  = isset($_GET['limit']) ? min(20, max(1, intval($_GET['limit']))) : 5;

            $articles = NewsModel::getAllArticles($offset, $limit);
            if (!is_array($articles)) $articles = [];


            // Hàm tính time ago
            function timeAgo($datetime)
            {
                $time = time() - strtotime($datetime);
                if ($time < 60) return 'vừa xong';
                if ($time < 3600) return floor($time / 60) . ' phút trước';
                if ($time < 86400) return floor($time / 3600) . ' giờ trước';
                if ($time < 2592000) return floor($time / 86400) . ' ngày trước';
                return date('d/m/Y', strtotime($datetime));
            }

            // Thêm avatar mặc định, status và time_ago
            foreach ($articles as &$art) {
                $art['avatar_url'] = $art['avatar_url'] ?? 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg';
                $art['status'] = $art['status'] ?? 'public';
                $art['time_ago'] = timeAgo($art['created_at']);
            }
            unset($art);

            echo json_encode([
                'success' => true,
                'items' => $articles,
                'count' => count($articles),
                'nextOffset' => $offset + count($articles)
            ]);
        } catch (Throwable $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
}
