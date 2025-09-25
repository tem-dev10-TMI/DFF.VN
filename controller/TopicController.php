<?php
require_once __DIR__ . '/../model/TopicModel.php';
require_once __DIR__ . '/../model/article/articlesmodel.php';
class TopicController
{
    // Hiển thị danh sách tất cả chủ đề (ví dụ trang chính hoặc sidebar)
    public static function index()
    {
        $topicModel = new TopicModel();
        $topics = $topicModel->getAll(); // lấy tất cả chủ đề
        $articles = ArticlesModel::getLatestArticles($limit = 6);
        // Load view
        ob_start();
        require_once __DIR__ . '/../view/layout/sidebarLeft.php'; // file view hiển thị danh sách chủ đề
        $content = ob_get_clean();

        // Load layout
        $profile = false;
        require_once __DIR__ . '/../view/layout/main.php';
    }
    public function loadMoreArticlesBySlug()
    {
        header('Content-Type: application/json');

        $slug = $_GET['slug'] ?? null;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

        if (!$slug) {
            echo json_encode(['success' => false, 'message' => 'Thiếu slug chủ đề.']);
            return;
        }

        // Lấy bài viết từ DB (tương tự như trong hàm details_topic)
        $articles = articlesmodel::getArticlesByTopicSlug($slug, $limit, $offset);

        // Trả về dữ liệu dưới dạng JSON
        echo json_encode([
            'success' => true,
            'items' => $articles,
            'count' => count($articles),
            'nextOffset' => $offset + count($articles)
        ]);
        exit;
    }
    // Lấy top N chủ đề (ví dụ sidebar menu)
    public static function top($limit = 5)
    {
        $topicModel = new TopicModel();
        $topics = $topicModel->getTop($limit);

        // Load view sidebar
        ob_start();
        require_once __DIR__ . '/../view/layout/sidebarLeft.php';
        $content = ob_get_clean();

        // Load layout
        $profile = false;
        require_once __DIR__ . '/../view/layout/main.php';
    }
    public function details_topic($slug)
    {
        $topicModel = new TopicModel();
        $topic = $topicModel->getBySlug($slug);

        if (!$topic) {
            echo "Chủ đề không tồn tại!";
            return;
        }

        require_once __DIR__ . '/../model/topic/TopicFollowModel.php';
        require_once __DIR__ . '/../config/db.php';
        $db = new connect();
        $pdo = $db->db;
        $topicFollowModel = new TopicFollowModel($pdo);

        $topic['follower_count'] = $topicFollowModel->countFollowers($topic['id']);

        $isFollowing = false;
        if (isset($_SESSION['user']['id'])) {
            $userId = $_SESSION['user']['id'];
            $isFollowing = $topicFollowModel->isFollowing($userId, $topic['id']);
        }

        require_once __DIR__ . '/../model/rss/RssModel.php';
        $initial_limit = 5; // Chỉ tải 5 bài cho mỗi nguồn RSS ban đầu

        // Luôn lấy bài viết từ CSDL của người dùng trước
        $dbArticles = articlesmodel::getArticlesByTopicSlug($slug, 5, 0);
        $articles = $dbArticles ?: []; // Khởi tạo mảng articles với bài viết từ CSDL (nếu có)

        // Định nghĩa các nguồn RSS cho các chủ đề cụ thể
        $rssFeedMap = [
            'tai-chinh' => [
                "https://baochinhphu.vn/kinh-te.rss",
                "https://thanhnien.vn/rss/kinh-te.rss"
            ],
            'vi-mo' => [
                "https://baochinhphu.vn/kinh-te.rss",
                "https://vietnamnet.vn/rss/kinh-doanh.rss"
            ],
            'thi-truong' => [
                "https://bnews.vn/rss/thi-truong-4.rss",
                "https://vietnamnet.vn/rss/kinh-doanh.rss"
            ],
            'quoc-te' => [
                "https://tuoitre.vn/rss/the-gioi.rss",
                "https://vnexpress.net/rss/the-gioi.rss"
            ],
            'nha-dat' => [
                "https://thanhnien.vn/rss/bat-dong-san.rss",
                "https://vnexpress.net/rss/bat-dong-san.rss"
            ],
            '360-doanh-nghiep' => [
                "https://vnexpress.net/rss/kinh-doanh.rss",
                "https://dantri.com.vn/kinh-doanh.rss"
            ]
        ];

        // Nếu slug hiện tại có trong map, lấy thêm bài từ RSS và gộp vào
        if (isset($rssFeedMap[$slug])) {
            $rssArticles = [];
            foreach ($rssFeedMap[$slug] as $feedUrl) {
                $rssItems = RssModel::getFeedItems($feedUrl, $initial_limit, 15);
                $rssArticles = array_merge($rssArticles, $rssItems);
            }
            // Gộp bài từ CSDL và RSS
            $articles = array_merge($articles, $rssArticles);
        }

        usort($articles, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        ob_start();
        require_once __DIR__ . '/../view/page/DetailsTopic.php';
        $content = ob_get_clean();

        $profile = false;
        require_once __DIR__ . '/../view/layout/main.php';
    }
    // Chi tiết 1 chủ đề (ví dụ hiển thị bài viết theo topic)
    public static function details($id)
    {
        $topicModel = new TopicModel();
        $topic = $topicModel->getById($id);

        if (!$topic) {
            die('Chủ đề không tồn tại.');
        }

        // Load view chi tiết topic
        ob_start();
        require __DIR__ . '/../view/page/DetailsTopic.php'; // bạn tạo view này để hiển thị bài viết theo topic
        $content = ob_get_clean();

        $profile = false;
        require __DIR__ . '/../view/layout/main.php';
    }
}
