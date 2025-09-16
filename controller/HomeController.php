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
        // 1. Lấy dữ liệu từ Database
        $dbArticles = ArticlesModel::getAllArticles();

        $comments = CommentsModel::getComments();
        $topBusinessmen = businessmenModel::getAllBusinessmen();
        $marketData = MarketDataModel::getCachedMarketData();

        // Lấy dữ liệu sự kiện
        $eventsModel = new Events();
        $events = $eventsModel->getAll();

        // 2. Lấy RSS
        require_once __DIR__ . '/../model/rss/RssModel.php';

        // RSS Báo Chính phủ
        $feedUrl1 = "https://baochinhphu.vn/kinh-te.rss";
        $rssArticles1 = RssModel::getFeedItems($feedUrl1, 50, 15);

        // RSS Thanh Niên
        $feedUrl2 = "https://thanhnien.vn/rss/kinh-te.rss";
        $rssArticles2 = RssModel::getFeedItems($feedUrl2, 50, 15);

        // Gộp RSS + DB articles
        $articles = array_merge($rssArticles1, $rssArticles2, $dbArticles);

        // Thiết lập avatar riêng theo nguồn RSS
        foreach ($articles as &$art) {
            if (!empty($art['is_rss'])) {
                if (isset($art['link']) && str_contains($art['link'], 'thanhnien')) {
                    $art['avatar_url'] = 'public/img/avatar/thanhnien.png';
                } else {
                    $art['avatar_url'] = 'public/img/avatar/baochinhphu.png';
                }
                $art['author_id'] = 66; // id mặc định cho RSS
            }
        }
        unset($art);

        // 3. Sắp xếp theo created_at giảm dần
        usort($articles, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // 4. Load view Home
        ob_start();
        require_once 'view/page/Home.php';
        $content = ob_get_clean();

        // 5. Load layout chính
        $profile = false;
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
