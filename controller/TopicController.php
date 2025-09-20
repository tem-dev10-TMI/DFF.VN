<?php
require_once __DIR__ . '/../model/TopicModel.php';
require_once __DIR__ . '/../model/article/articlesmodel.php';
require_once __DIR__ . '/../model/topic/TopicFollowModel.php';

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

    // thêm đoạn này 👇
    require_once __DIR__ . '/../model/topic/TopicFollowModel.php';
    $topicFollowModel = new TopicFollowModel((new connect())->db);

    // Lấy số follower
    $followerCount = $topicFollowModel->countFollowers($topic['id']);
    $topic['follower_count'] = $followerCount;

    // Check user đã follow chưa
    $isFollowing = false;
    if (isset($_SESSION['user']['id'])) {
        $isFollowing = $topicFollowModel->isFollowing($_SESSION['user']['id'], $topic['id']);
    }
    // hết đoạn thêm 👆

    require_once __DIR__ . '/../model/rss/RssModel.php';
    if ($slug == 'tai-chinh') {
        $feedUrl1 = "https://baochinhphu.vn/kinh-te.rss";
        $rssArticles1 = RssModel::getFeedItems($feedUrl1, 12, 15);

        $feedUrl2 = "https://thanhnien.vn/rss/kinh-te.rss";
        $rssArticles2 = RssModel::getFeedItems($feedUrl2, 12, 15);

        $articles = array_merge($rssArticles1, $rssArticles2);
    } else {
        $articles = ArticlesModel::getArticlesByTopicSlug($slug, 10);
    }

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
