<?php
require_once __DIR__ . '/../model/article/articlesmodel.php';
require_once __DIR__ . '/../model/commentmodel.php';
require_once __DIR__ . '/../model/user/businessmenModel.php';
require_once __DIR__ . '/../model/MarketDataModel.php';
require_once __DIR__ . '/../model/event/Events.php';

require_once __DIR__ . '/../model/TopicModel.php';


class Events
{
    protected $pdo;

    public function __construct()
    {
        // Lấy PDO toàn cục nếu không truyền tham số
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll($limit = 10)
    {
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
        // 1. Lấy dữ liệu từ Database (giảm tải: chỉ lấy 8 cho slider và 5 cho danh sách ban đầu)
        $dbArticlesForSlider = ArticlesModel::getArticlesPaged(0, 8);
        $dbArticlesInitial = ArticlesModel::getArticlesPaged(0, 5);

        $comments = CommentsModel::getComments();
        $topBusinessmen = businessmenModel::getAllBusinessmen();
        $marketData = MarketDataModel::getCachedMarketData();

        // Lấy dữ liệu sự kiện
        $eventsModel = new Events();
        $events = $eventsModel->getAll();

        // 2. Lấy RSS (giới hạn ngay từ nguồn)
        require_once __DIR__ . '/../model/rss/RssModel.php';

        // RSS Báo Chính phủ
        $feedUrl1 = "https://baochinhphu.vn/kinh-te.rss";
        $rssArticles1 = RssModel::getFeedItems($feedUrl1, 12, 15);

        // RSS Thanh Niên
        $feedUrl2 = "https://thanhnien.vn/rss/kinh-te.rss";
        $rssArticles2 = RssModel::getFeedItems($feedUrl2, 12, 15);

        // Gộp RSS + DB (dành cho slider: lấy vừa đủ 8 sau khi trộn theo thời gian)
        $allForSlider = array_merge($rssArticles1, $rssArticles2, $dbArticlesForSlider);

        // Thiết lập avatar riêng theo nguồn RSS
        foreach ($allForSlider as &$art) {
            if (!empty($art['is_rss'])) {
                if (isset($art['link']) && str_contains($art['link'], 'thanhnien')) {
                    $art['avatar_url'] = 'public/img/avatar/thanhnien.png';
                    $art['author_id'] = 67; // id cho RSS Thanh Niên
                } else {
                    $art['avatar_url'] = 'public/img/avatar/baochinhphu.png';
                    $art['author_id'] = 66; // id cho RSS Báo Chính phủ
                }
            }
        }
        unset($art);

        // 3. Sắp xếp theo created_at giảm dần
        usort($allForSlider, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // Giới hạn: 8 bài nổi bật cho slider
        $featuredArticles = array_slice($allForSlider, 0, 8);

        // Danh sách khởi tạo 5 bài: trộn RSS ít + DB 5 bài
        $rssForInitial = array_merge(
            array_slice($rssArticles1, 0, 4),
            array_slice($rssArticles2, 0, 4)
        );
        $articlesInitialCombined = array_merge($rssForInitial, $dbArticlesInitial);
        foreach ($articlesInitialCombined as &$art2) {
            if (!empty($art2['is_rss'])) {
                if (isset($art2['link']) && str_contains($art2['link'], 'thanhnien')) {
                    $art2['avatar_url'] = 'public/img/avatar/thanhnien.png';
                    $art2['author_id'] = 67; // id cho RSS Thanh Niên
                } else {
                    $art2['avatar_url'] = 'public/img/avatar/baochinhphu.png';
                    $art2['author_id'] = 66; // id cho RSS Báo Chính phủ
                }
            }
        }
        unset($art2);
        usort($articlesInitialCombined, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        $articlesInitial = array_slice($articlesInitialCombined, 0, 5);

        // Tạo nhóm RSS cho các box HOT và ANALYSIS (từ nguồn RSS trong mảng tổng)
        $onlyRss = array_values(array_filter(array_merge($rssArticles1, $rssArticles2), function ($it) {
            return !empty($it['is_rss']);
        }));
        $rssArticles3 = array_slice($onlyRss, 0, 6);
        $rssArticles4 = array_slice($onlyRss, 6, 6);

        if ($_SESSION['user']['role'] == 'user') {
            $profile_category = "user";
        } else {
            $profile_category = "businessmen";
        }
        $topicModel = new TopicModel();
        $allTopics = $topicModel->getAll();

        // 4. Load view Home
        ob_start();
        require_once __DIR__ . '/../view/page/Home.php';
        $content = ob_get_clean();

        // 5. Load layout chính
        $profile = false;
        require_once __DIR__ . '/../view/layout/main.php';
    }

    // API: Load thêm bài viết (lazy load)
    public function loadMoreArticles()
    {
        header('Content-Type: application/json');
        try {
            require_once __DIR__ . '/../model/article/articlesmodel.php';
            require_once __DIR__ . '/../model/MarketDataModel.php';
            require_once __DIR__ . '/../model/commentmodel.php';
            require_once __DIR__ . '/../model/user/businessmenModel.php';

            $offset = isset($_GET['offset']) ? max(0, intval($_GET['offset'])) : 0;
            $limit = isset($_GET['limit']) ? min(20, max(1, intval($_GET['limit']))) : 5;

            // Lấy lại nguồn dữ liệu giống trang chủ để đảm bảo đồng nhất
            // Giảm tải DB: chỉ lấy theo offset/limit
            $dbArticles = ArticlesModel::getArticlesPaged($offset, $limit);

            require_once __DIR__ . '/../model/rss/RssModel.php';
            $feedUrl1 = "https://baochinhphu.vn/kinh-te.rss";
            $rssArticles1 = RssModel::getFeedItems($feedUrl1, 6, 15);
            $feedUrl2 = "https://thanhnien.vn/rss/kinh-te.rss";
            $rssArticles2 = RssModel::getFeedItems($feedUrl2, 6, 15);

            // Trộn vừa đủ cho một "trang" lazy-load
            $articles = array_merge($rssArticles1, $rssArticles2, $dbArticles);
            foreach ($articles as &$art) {
                if (!empty($art['is_rss'])) {
                    if (isset($art['link']) && str_contains($art['link'], 'thanhnien')) {
                        $art['avatar_url'] = 'public/img/avatar/thanhnien.png';
                        $art['author_id'] = 67; // id cho RSS Thanh Niên
                    } else {
                        $art['avatar_url'] = 'public/img/avatar/baochinhphu.png';
                        $art['author_id'] = 66; // id cho RSS Báo Chính phủ
                    }
                }
            }
            unset($art);
            usort($articles, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            $slice = array_slice($articles, $offset, $limit);

            echo json_encode([
                'success' => true,
                'items' => $slice,
                'count' => count($slice),
                'nextOffset' => $offset + count($slice)
            ]);
        } catch (Throwable $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }


    public static function profile_business()
    {
        ob_start();
        $profile_category = 'businessmen';
        require_once __DIR__ . '/../view/layout/Profile.php';
        $content = ob_get_clean();

        $profile = true;
        require_once __DIR__ . '/../view/layout/main.php';
    }

    public static function profile_user()
    {
        ob_start();
        $profile_category = 'user';
        require_once __DIR__ . '/../view/layout/Profile.php';
        $content = ob_get_clean();

        $profile = true;
        require_once __DIR__ . '/../view/layout/main.php';
    }

    public static function trends()
    {
        require_once __DIR__ . '/../model/TopicModel.php';
        require_once __DIR__ . '/../model/article/articlesmodel.php';

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
        require_once __DIR__ . '/../view/page/trends.php';
        $content = ob_get_clean();

        $profile = false;
        require_once __DIR__ . '/../view/layout/main.php';
    }

    public static function about()
    {
        ob_start();
        require_once __DIR__ . '/../view/page/About.php';
        $content = ob_get_clean();

        $profile = true;
        require_once __DIR__ . '/../view/layout/main.php';
    }
}
