<?php
require_once __DIR__ . '/../model/article/articlesmodel.php';

require_once __DIR__ . '/../model/user/businessmenModel.php';
require_once __DIR__ . '/../model/MarketDataModel.php';
require_once __DIR__ . '/../model/event/Events.php';

require_once __DIR__ . '/../model/TopicModel.php';

require_once __DIR__ . '/../model/kolModel.php';


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
        // --- START: Performance Measurement ---
        $log_file = __DIR__ . '/../debug_log.txt';
        if (file_exists($log_file)) {
            unlink($log_file);
        }
        $start_total_time = microtime(true);
        $last_checkpoint_time = $start_total_time;

        function log_time($label, &$last_checkpoint, $log_file)
        {
            $now = microtime(true);
            $execution_time = round(($now - $last_checkpoint) * 1000);
            file_put_contents($log_file, "[{$label}] Execution Time: {$execution_time}ms\n", FILE_APPEND);
            $last_checkpoint = $now;
        }
        // --- END: Performance Measurement ---

        // 0. Include a
        require_once __DIR__ . '/../helpers/cache_helper.php';

        // 1. Lấy dữ liệu từ Database
        $cache_key_slider = 'articles_slider';
        $dbArticlesForSlider = get_cache($cache_key_slider, 5);
        if ($dbArticlesForSlider === false) {
            $dbArticlesForSlider = ArticlesModel::getArticlesPaged(0, 12); // Tăng lên 12 để có đủ cho cả 2 box
            set_cache($cache_key_slider, $dbArticlesForSlider);
        }

        $cache_key_initial = 'articles_initial';
        $dbArticlesInitial = get_cache($cache_key_initial, 5);
        if ($dbArticlesInitial === false) {
            $dbArticlesInitial = ArticlesModel::getArticlesPaged(0, 5);
            set_cache($cache_key_initial, $dbArticlesInitial);
        }

        $iduser = $_SESSION['user']['id'] ?? null;

        $cache_key_businessmen = 'top_businessmen';
        $topBusinessmen = get_cache($cache_key_businessmen, 600);
        if ($topBusinessmen === false) {
            $topBusinessmen = businessmenModel::getAllBusinessmen(6, $iduser);
            set_cache($cache_key_businessmen, $topBusinessmen);
        }

        $marketData = MarketDataModel::getCachedMarketData();
        // $eventsModel = new Events();
        // $events = $eventsModel->getAll();

        log_time('DB Queries', $last_checkpoint_time, $log_file);

        // Dữ liệu cho slider và danh sách ban đầu giờ chỉ lấy từ DB
        $featuredArticles = array_slice($dbArticlesForSlider, 0, 8);
        $articlesInitial = $dbArticlesInitial;

        // Các box HOT và ANALYSIS sẽ cần logic mới hoặc tạm thời để trống
        // Ở đây, chúng ta sẽ dùng tạm bài viết từ DB
        $rssArticlesForBox1 = array_slice($dbArticlesForSlider, 0, 6);
        $rssArticlesForBox2 = array_slice($dbArticlesForSlider, 6, 6);
        
        // Nếu không đủ dữ liệu cho box 2, lấy lại từ đầu với offset khác
        if (empty($rssArticlesForBox2) && count($dbArticlesForSlider) > 0) {
            $rssArticlesForBox2 = array_slice($dbArticlesForSlider, 0, 6);
        }

        if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 'user') {
            $profile_category = "user";
        } else {
            $profile_category = "businessmen";
        }

        $topKOLs = KOLModel::getTopUsersByFollowersAndLikes(10);

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
    
                $offset = isset($_GET['offset']) ? max(0, intval($_GET['offset'])) : 0;
                $limit = isset($_GET['limit']) ? min(20, max(1, intval($_GET['limit']))) : 5;
    
                // Chỉ lấy thêm bài viết từ database
                $articles = ArticlesModel::getArticlesPaged($offset, $limit);
    
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
        require_once __DIR__ . '/../view/page/Trends.php';
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

    public static function video()
    {
        ob_start();
        require_once __DIR__ . '/../view/page/video.php';
        $content = ob_get_clean();
        $profile = false;
        require_once __DIR__ . '/../view/layout/main.php';
    }
}
