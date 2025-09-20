<?php
require_once __DIR__ . '/../model/article/articlesmodel.php';

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
        // 1. Lấy dữ liệu từ Database (giữ nguyên logic của bạn)
        $dbArticlesForSlider = ArticlesModel::getArticlesPaged(0, 6);
        $dbArticlesInitial = ArticlesModel::getArticlesPaged(0, 5);

        $topBusinessmen = businessmenModel::getAllBusinessmen(6, $_SESSION['user']['id']);
        $marketData = MarketDataModel::getCachedMarketData();
        
        // Lấy dữ liệu sự kiện
        $eventsModel = new Events();
        $events = $eventsModel->getAll();

        // 2. Lấy RSS (giữ nguyên 2 nguồn cũ và thêm 6 nguồn mới)
        require_once __DIR__ . '/../model/rss/RssModel.php';

        // RSS Báo Chính phủ (cũ)
        $feedUrl1 = "https://baochinhphu.vn/kinh-te.rss";
        $rssArticles1 = RssModel::getFeedItems($feedUrl1, 12, 15);

        // RSS Thanh Niên (cũ)
        $feedUrl2 = "https://thanhnien.vn/rss/kinh-te.rss";
        $rssArticles2 = RssModel::getFeedItems($feedUrl2, 12, 15);

        // --- BẮT ĐẦU THÊM 6 NGUỒN MỚI ---
        $feedUrl3 = "https://vnexpress.net/rss/kinh-doanh.rss";
        $rssArticles3 = RssModel::getFeedItems($feedUrl3, 12, 15);

        $feedUrl4 = "https://tuoitre.vn/rss/kinh-doanh.rss";
        $rssArticles4 = RssModel::getFeedItems($feedUrl4, 12, 15);

        $feedUrl5 = "https://cafef.vn/trang-chu.rss";
        $rssArticles5 = RssModel::getFeedItems($feedUrl5, 12, 15);

        $feedUrl6 = "https://vietnamnet.vn/rss/kinh-doanh.rss";
        $rssArticles6 = RssModel::getFeedItems($feedUrl6, 12, 15);

        $feedUrl7 = "https://dantri.com.vn/kinh-doanh.rss";
        $rssArticles7 = RssModel::getFeedItems($feedUrl7, 12, 15);

        $feedUrl8 = "https://znews.vn/kinh-doanh-tai-chinh.rss";
        $rssArticles8 = RssModel::getFeedItems($feedUrl8, 12, 15);
        // --- KẾT THÚC THÊM 6 NGUỒN MỚI ---

        // Gộp TẤT CẢ RSS + DB (dành cho slider: lấy vừa đủ 8 sau khi trộn theo thời gian)
        $allForSlider = array_merge(
            $rssArticles1, $rssArticles2, $rssArticles3, $rssArticles4,
            $rssArticles5, $rssArticles6, $rssArticles7, $rssArticles8,
            $dbArticlesForSlider
        );

        // Thiết lập avatar riêng theo nguồn RSS (thêm logic cho các nguồn mới)
        foreach ($allForSlider as &$art) {
            if (!empty($art['is_rss'])) {
                if (isset($art['link']) && str_contains($art['link'], 'thanhnien')) {
                    $art['avatar_url'] = 'public/img/avatar/thanhnien.png';
                    $art['author_id'] = 67; // id cho RSS Thanh Niên
                } elseif (isset($art['link']) && str_contains($art['link'], 'vnexpress')) {
                    $art['avatar_url'] = 'public/img/avatar/vnexpress.png';
                    $art['author_id'] = 68; // id cho RSS VnExpress
                } elseif (isset($art['link']) && str_contains($art['link'], 'tuoitre')) {
                    $art['avatar_url'] = 'public/img/avatar/tuoitre.png';
                    $art['author_id'] = 69; // id cho RSS Tuổi Trẻ
                } elseif (isset($art['link']) && str_contains($art['link'], 'cafef')) {
                    $art['avatar_url'] = 'public/img/avatar/cafef.png';
                    $art['author_id'] = 70; // id cho RSS CafeF
                } elseif (isset($art['link']) && str_contains($art['link'], 'vietnamnet')) {
                    $art['avatar_url'] = 'public/img/avatar/vietnamnet.png';
                    $art['author_id'] = 71; // id cho RSS Vietnamnet
                } elseif (isset($art['link']) && str_contains($art['link'], 'dantri')) {
                    $art['avatar_url'] = 'public/img/avatar/dantri.png';
                    $art['author_id'] = 72; // id cho RSS Dân trí
                } elseif (isset($art['link']) && str_contains($art['link'], 'znews')) {
                    $art['avatar_url'] = 'public/img/avatar/znews.png';
                    $art['author_id'] = 73; // id cho RSS Znews
                } else {
                    // Mặc định là Báo Chính phủ
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

        // Điều chỉnh lại logic lấy 8 bài nổi bật: lấy 1 bài từ mỗi nguồn RSS
        $article_nb = array_merge(
            array_slice($rssArticles1, 0, 1),
            array_slice($rssArticles2, 0, 1),
            array_slice($rssArticles3, 0, 1),
            array_slice($rssArticles4, 0, 1),
            array_slice($rssArticles5, 0, 1),
            array_slice($rssArticles6, 0, 1),
            array_slice($rssArticles7, 0, 1),
            array_slice($rssArticles8, 0, 1)
        );
        // Giới hạn: 8 bài nổi bật cho slider
        $featuredArticles = array_slice($article_nb, 0, 8);


        // Danh sách khởi tạo 5 bài: trộn RSS ít + DB 5 bài (lấy 1 bài từ 4 nguồn đầu)
        $rssForInitial = array_merge(
             array_slice($rssArticles1, 0, 1),
             array_slice($rssArticles2, 0, 1),
             array_slice($rssArticles3, 0, 1),
             array_slice($rssArticles4, 0, 1)
        );
        $articlesInitialCombined = array_merge($rssForInitial, $dbArticlesInitial);
        // Logic gán avatar cho danh sách này (thêm các nguồn mới)
        foreach ($articlesInitialCombined as &$art2) {
            if (!empty($art2['is_rss'])) {
                 if (isset($art2['link']) && str_contains($art2['link'], 'thanhnien')) {
                    $art2['avatar_url'] = 'public/img/avatar/thanhnien.png';
                    $art2['author_id'] = 67;
                } elseif (isset($art2['link']) && str_contains($art2['link'], 'vnexpress')) {
                    $art2['avatar_url'] = 'public/img/avatar/vnexpress.png';
                    $art2['author_id'] = 68;
                } elseif (isset($art2['link']) && str_contains($art2['link'], 'tuoitre')) {
                    $art2['avatar_url'] = 'public/img/avatar/tuoitre.png';
                    $art2['author_id'] = 69;
                } else {
                    $art2['avatar_url'] = 'public/img/avatar/baochinhphu.png';
                    $art2['author_id'] = 66;
                }
            }
        }
        unset($art2);
        usort($articlesInitialCombined, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        $articlesInitial = array_slice($articlesInitialCombined, 0, 5);

        // Tạo nhóm RSS cho các box HOT và ANALYSIS (gộp tất cả các nguồn RSS)
        $allRssArticles = array_merge(
            $rssArticles1, $rssArticles2, $rssArticles3, $rssArticles4,
            $rssArticles5, $rssArticles6, $rssArticles7, $rssArticles8
        );
        $onlyRss = array_values(array_filter($allRssArticles, function ($it) {
            return !empty($it['is_rss']);
        }));
        $rssArticlesForBox1 = array_slice($onlyRss, 0, 6);
        $rssArticlesForBox2 = array_slice($onlyRss, 6, 6);

        if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 'user') {
            $profile_category = "user";
        } else {
            $profile_category = "businessmen";
        }
        $topicModel = new TopicModel();
        $allTopics = $topicModel->getAll();

        // 4. Load view Home
        ob_start();
        // Đổi tên biến để view dễ hiểu hơn
        $rssArticles3 = $rssArticlesForBox1;
        $rssArticles4 = $rssArticlesForBox2;
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
          require_once __DIR__ . '/../model/CommentsModel.php';   // Đúng tên file

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

            $normalized = [];
            foreach ($slice as $art) {
                if (!empty($art['is_rss'])) {
                    // RSS giữ nguyên
                    $normalized[] = [
                        'title' => $art['title'],
                        'summary' => $art['summary'] ?? '',
                        'link' => $art['link'],
                        'created_at' => $art['created_at'],
                        'author_name' => $art['author_name'] ?? '',
                        'avatar_url' => $art['avatar_url'] ?? '',
                        'main_image_url' => $art['main_image_url'] ?? '',
                        'is_rss' => true
                    ];
                } else {
                    // Bài trong DB → thêm slug
                    $normalized[] = [
                        'id' => $art['id'],
                        'slug' => $art['slug'], // 👈 thêm slug
                        'title' => $art['title'],
                        'summary' => $art['summary'] ?? '',
                        'created_at' => $art['created_at'],
                        'author_name' => $art['author_name'] ?? '',
                        'avatar_url' => $art['avatar_url'] ?? '',
                        'main_image_url' => $art['main_image_url'] ?? '',
                        'comment_count' => $art['comment_count'] ?? 0,
                        'upvotes' => $art['upvotes'] ?? 0,
                        'is_rss' => false
                    ];
                }
            }

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
