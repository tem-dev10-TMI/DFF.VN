<?php
require_once 'model/article/articlesmodel.php';
require_once 'model/commentmodel.php';
require_once 'model/user/businessmenModel.php';
require_once 'model/MarketDataModel.php';
require_once 'model/event/Events.php';

class Events {
    protected $pdo;

    public function __construct() {
        // Lấy PDO toàn cục nếu không truyền tham số
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll($limit = 10) {
        $stmt = $this->pdo->prepare("SELECT * FROM events ORDER BY event_date DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class homeController
{
    public static function index()
    {
        // ================= 1. Lấy dữ liệu từ Database =================
        $dbArticles = ArticlesModel::getAllArticles(); // Bài viết DB
        $comments = CommentsModel::getComments();      // Bình luận
        $topBusinessmen = businessmenModel::getAllBusinessmen(); // Doanh nhân
        $marketData = MarketDataModel::getCachedMarketData();    // Thị trường

        // Lấy dữ liệu sự kiện
        $eventsModel = new Events();
        $events = $eventsModel->getAll(10); // Lấy tối đa 10 sự kiện gần nhất

        // ================= 2. Lấy RSS =================
        require_once __DIR__ . '/../model/rss/RssModel.php';
        $rssArticles = [];

        // Danh sách feed RSS
        $feedUrls = [
            'https://baochinhphu.vn/kinh-te.rss',
            'https://doanhnhan.baophapluat.vn/rss/tai-chinh.rss'
        ];

        foreach ($feedUrls as $url) {
            $items = RssModel::getFeedItems($url, 50, 15); // limit 50, cache 15 phút
            if (!empty($items)) {
                $rssArticles = array_merge($rssArticles, $items);
            }
        }

        // ================= 3. Gộp tất cả bài viết =================
        $articles = array_merge($rssArticles, $dbArticles);

        // ================= 4. Sắp xếp theo created_at giảm dần =================
        usort($articles, function ($a, $b) {
            $timeA = isset($a['created_at']) ? strtotime($a['created_at']) : 0;
            $timeB = isset($b['created_at']) ? strtotime($b['created_at']) : 0;
            return $timeB - $timeA;
        });

        // ================= 5. Truyền dữ liệu cho view Home =================
        ob_start();
        require_once 'view/page/Home.php';
        $content = ob_get_clean();

        // ================= 6. Load layout chính =================
        $profile = false; // Biến xác định đang ở Home hay profile
        require_once 'view/layout/main.php';
    }



    public static function profile_business()
    {
        ob_start();
        $profile_category = 'businessmen';
        require_once 'view/layout/Profile.php';
        $content = ob_get_clean();


        $profile = true;
        require_once 'view/layout/main.php';
    }


    public static function profile_user()
    {
        ob_start();
        $profile_category = 'user';
        require_once 'view/layout/Profile.php';
        $content = ob_get_clean();

        $profile = true;
        require_once 'view/layout/main.php';
    }

    public static function trends()
    {
        require_once 'model/TopicModel.php';
        require_once 'model/article/articlesmodel.php';

        $topicModel = new TopicModel();
        $topics = $topicModel->getAll();

        $articlesByTopic = [];
        if (!empty($topics)) {
            foreach ($topics as $tp) {
                $tid = (int)($tp['id'] ?? 0);
                if ($tid > 0) {
                    $articlesByTopic[$tid] = ArticlesModel::getArticlesByTopicId($tid, 10);
                }
            }
        }

        ob_start();
        require_once 'view/page/Trends.php';
        $content = ob_get_clean();

        $profile = false;
        require_once 'view/layout/main.php';
    }

    public static function about()
    {
        ob_start();
        require_once 'view/page/About.php';
        $content = ob_get_clean();

        $profile = true;
        require_once 'view/layout/main.php';
    }
}
