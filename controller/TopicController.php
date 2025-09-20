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

        if ($slug == 'tai-chinh') {
            $feedUrl1 = "https://baochinhphu.vn/kinh-te.rss";
            $rssArticles1 = RssModel::getFeedItems($feedUrl1, $initial_limit, 15);
            $feedUrl2 = "https://thanhnien.vn/rss/kinh-te.rss";
            $rssArticles2 = RssModel::getFeedItems($feedUrl2, $initial_limit, 15);
            $articles = array_merge($rssArticles1, $rssArticles2);
        } else if ($slug == 'vi-mo') {
            $feedUrl1 = "https://baochinhphu.vn/kinh-te.rss";
            $rssArticles1 = RssModel::getFeedItems($feedUrl1, $initial_limit, 15);
            $feedUrl2 = "https://vietnamnet.vn/rss/kinh-doanh.rss";
            $rssArticles2 = RssModel::getFeedItems($feedUrl2, $initial_limit, 15);
            $articles = array_merge($rssArticles1, $rssArticles2);
        } else if ($slug == 'thi-truong') {
            $feedUrl1 = "https://bnews.vn/rss/thi-truong-4.rss";
            $rssArticles1 = RssModel::getFeedItems($feedUrl1, $initial_limit, 15);
            $feedUrl2 = "https://vietnamnet.vn/rss/kinh-doanh.rss";
            $rssArticles2 = RssModel::getFeedItems($feedUrl2, $initial_limit, 15);
            $articles = array_merge($rssArticles1, $rssArticles2);
        } else if ($slug == 'quoc-te') {
            $feedUrl1 = "https://tuoitre.vn/rss/the-gioi.rss";
            $rssArticles1 = RssModel::getFeedItems($feedUrl1, $initial_limit, 15);
            $feedUrl2 = "https://vnexpress.net/rss/the-gioi.rss";
            $rssArticles2 = RssModel::getFeedItems($feedUrl2, $initial_limit, 15);
            $articles = array_merge($rssArticles1, $rssArticles2);
        } else if ($slug == 'nha-dat') {
            $feedUrl1 = "https://thanhnien.vn/rss/bat-dong-san.rss";
            $rssArticles1 = RssModel::getFeedItems($feedUrl1, $initial_limit, 15);
            $feedUrl2 = "https://vnexpress.net/rss/bat-dong-san.rss";
            $rssArticles2 = RssModel::getFeedItems($feedUrl2, $initial_limit, 15);
            $articles = array_merge($rssArticles1, $rssArticles2);
        } else if ($slug == '360-doanh-nghiep') {
            $feedUrl1 = "https://vnexpress.net/rss/kinh-doanh.rss";
            $rssArticles1 = RssModel::getFeedItems($feedUrl1, $initial_limit, 15);
            $feedUrl2 = "https://dantri.com.vn/kinh-doanh.rss";
            $rssArticles2 = RssModel::getFeedItems($feedUrl2, $initial_limit, 15);
            $articles = array_merge($rssArticles1, $rssArticles2);
        } else {
            $articles = articlesmodel::getArticlesByTopicSlug($slug, 5, 0); // Lấy 10 bài đầu tiên
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
