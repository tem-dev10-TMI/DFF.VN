<?php
require_once __DIR__ . '/../model/article/articlesmodel.php';
require_once __DIR__ . '/../model/commentmodel.php';
require_once __DIR__ . '/../model/user/businessmenModel.php';
require_once __DIR__ . '/../model/MarketDataModel.php';
require_once __DIR__ . '/../model/event/Events.php';
require_once __DIR__ . '/../model/TopicModel.php';
require_once __DIR__ . '/../model/rss/RssModel.php'; // Đưa lên đầu cho gọn

class Events
{
    protected $pdo;

    public function __construct()
    {
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
    /**
     * Phương thức private để lấy, trộn và sắp xếp tất cả bài viết.
     * Dùng chung cho cả trang chủ và lazy-load để tránh lặp code.
     * @return array
     */
    private static function _getAllArticles(): array
    {
        // 1. Cấu hình tất cả các nguồn RSS ở một nơi duy nhất
        $feedUrls = [
            "https://baochinhphu.vn/kinh-te.rss",
            "https://thanhnien.vn/rss/kinh-te.rss",
            "https://vnexpress.net/rss/kinh-doanh.rss",
            "https://tuoitre.vn/rss/kinh-doanh.rss",
            "https://cafef.vn/trang-chu.rss",
            "https://vietnamnet.vn/rss/kinh-doanh.rss",
            "https://dantri.com.vn/kinh-doanh.rss",
            "https://znews.vn/kinh-doanh-tai-chinh.rss",
            "https://tuoitre.vn/rss/the-gioi.rss",
            "https://bnews.vn/rss/thi-truong-4.rss"
        ];
        
        // 2. Lấy bài viết từ RSS (1 lần gọi duy nhất) và từ Database
        $rssArticles = RssModel::getMultipleFeeds($feedUrls, 25, 15);
        $dbArticles = ArticlesModel::getArticlesPaged(0, 15); // Lấy 1 lượng lớn từ DB để trộn

        // 3. Trộn tất cả lại
        $allArticles = array_merge($rssArticles, $dbArticles);

        // 4. Sắp xếp 1 lần duy nhất
        usort($allArticles, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $allArticles;
    }

    /**
     * Hiển thị trang chủ
     */
    public static function index()
    {
        // 1. Lấy tất cả bài viết đã được trộn và sắp xếp
        $allArticles = self::_getAllArticles();

        // 2. Lấy dữ liệu phụ
        $comments = CommentsModel::getComments();
        $topBusinessmen = businessmenModel::getAllBusinessmen();
        $marketData = MarketDataModel::getCachedMarketData();
        $eventsModel = new Events();
        $events = $eventsModel->getAll();
        $topicModel = new TopicModel();
        $allTopics = $topicModel->getAll();

        // 3. Chia mảng lớn thành các phần nhỏ cho view
        // - 8 bài nổi bật cho slider
        $featuredArticles = array_slice($allArticles, 0, 8);
        
        // - 5 bài cho danh sách hiển thị ban đầu
        $articlesInitial = array_slice($allArticles, 0, 5);

        // - Lọc ra các bài RSS để hiển thị ở các box khác
        $onlyRss = array_values(array_filter($allArticles, function ($it) {
            return !empty($it['is_rss']);
        }));
        
        // - 6 bài cho box HOT
        $rssArticles3 = array_slice($onlyRss, 0, 6);
        // - 6 bài cho box ANALYSIS
        $rssArticles4 = array_slice($onlyRss, 6, 6);

        // Xác định profile category
        if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 'user') {
            $profile_category = "user";
        } else {
            $profile_category = "businessmen";
        }
        
        // 4. Load view Home
        ob_start();
        require_once __DIR__ . '/../view/page/Home.php';
        $content = ob_get_clean();

        // 5. Load layout chính
        $profile = false;
        require_once __DIR__ . '/../view/layout/main.php';
    }

    /**
     * API: Load thêm bài viết (lazy load)
     */
    public function loadMoreArticles()
    {
        header('Content-Type: application/json');
        try {
            $offset = isset($_GET['offset']) ? max(0, intval($_GET['offset'])) : 0;
            $limit = isset($_GET['limit']) ? min(20, max(1, intval($_GET['limit']))) : 5;

            // 1. Lấy tất cả bài viết đã được trộn và sắp xếp (gọi lại hàm dùng chung)
            $allArticles = self::_getAllArticles();

            // 2. Cắt lát theo offset và limit được yêu cầu
            $slice = array_slice($allArticles, $offset, $limit);

            // 3. Chuẩn hóa dữ liệu trả về (thêm slug cho bài viết từ DB)
            $normalized = [];
            foreach ($slice as $art) {
                 if (!empty($art['is_rss'])) {
                    $normalized[] = $art; // Dữ liệu từ RSS đã đầy đủ
                } else {
                    // Bài trong DB cần thêm slug để tạo link
                    $art['slug'] = $art['slug'] ?? ''; 
                    $normalized[] = $art;
                }
            }
            
            echo json_encode([
                'success' => true,
                'items' => $normalized, // Trả về mảng đã được chuẩn hóa
                'count' => count($normalized),
                'nextOffset' => $offset + count($normalized)
            ]);

        } catch (Throwable $e) {
            http_response_code(500);
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
