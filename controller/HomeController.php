<?php
require_once 'model/article/articlesmodel.php';
require_once 'model/commentmodel.php';
require_once 'model/user/businessmenModel.php';
require_once 'model/MarketDataModel.php';
require_once 'model/event/Events.php';


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
        $rssArticles1 = RssModel::getFeedItems($feedUrl1, 50, 15); // limit 50, cache 15 phút
        $rssArticles3 = RssModel::getFeedItems($feedUrl1, 6, 15);
        // RSS Doanhnhan.vn - Tài chính
        $feedUrl2 = "https://doanhnhan.baophapluat.vn/rss/tai-chinh.rss";
        $rssArticles2 = RssModel::getFeedItems($feedUrl2, 50, 15); // limit 50, cache 15 phút

        // 3. Gộp tất cả bài viết: RSS + DB
        $articles = array_merge($rssArticles1, $rssArticles2, $dbArticles);

        // 4. Sắp xếp theo created_at giảm dần
        usort($articles, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // 5. Load view Home
        ob_start();
        require_once 'view/page/Home.php';
        $content = ob_get_clean();

        // 6. Load layout chính
        $profile = false; // giữ nguyên
        require_once 'view/layout/main.php';
        }


        public static function profile_business() // test giao diện, ai code backend fix lại đưa sang nơi phù hợp trong controller
        {
                //Load model

                //Load view
                ob_start();
                $profile_category = 'businessmen';
                require_once 'view/layout/Profile.php';
                $content = ob_get_clean();

                //Load layout
                $profile = true; // đừng ai xóa
                require_once 'view/layout/main.php';
        }
        public static function profile_user() // test giao diện, ai code backend fix lại đưa sang nơi phù hợp trong controller
        {
                //Load model

                //Load view
                ob_start();
                $profile_category='user';
                require_once 'view/layout/Profile.php';
                $content = ob_get_clean();

                //Load layout
                $profile = true; // đừng ai xóa
                require_once 'view/layout/main.php';
        }
        public static function trends() // test giao diện, ai code backend fix lại đưa sang nơi phù hợp trong controller
        {
                // Load model
                require_once 'model/TopicModel.php';
                require_once 'model/article/articlesmodel.php';

                $topicModel = new TopicModel();

                // Lấy tất cả chủ đề và map bài viết theo từng chủ đề
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

                //Load view
                ob_start();
                require_once 'view/page/Trends.php';
                $content = ob_get_clean();

                //Load layout
                $profile = false; // đừng ai xóa
                require_once 'view/layout/main.php';
        }
        public static function about() // test giao diện, ai code backend fix lại đưa sang nơi phù hợp trong controller
        {
                //Load model

                //Load view
                ob_start();
                require_once 'view/page/About.php';
                $content = ob_get_clean();

                //Load layout
                $profile = true; // đừng ai xóa
                require_once 'view/layout/main.php';
        }

}
