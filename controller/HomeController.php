<?php
require_once 'model/article/articlesmodel.php';
require_once 'model/commentmodel.php';
require_once 'model/user/businessmenModel.php';
require_once 'model/MarketDataModel.php';


class homeController
{
        public static function index()
        {
                //Load model
                //require_once '/../../config/db.php';

                // Fetch data from database
                // Lấy toàn bộ bài viết public (không giới hạn)
                $articles = ArticlesModel::getAllArticles();
                $comments = CommentsModel::getComments();
                $topBusinessmen = businessmenModel::getAllBusinessmen(10);

                $marketData = MarketDataModel::getCachedMarketData();
                
                //Load view
                ob_start();

                require_once 'view/page/Home.php';

                $content = ob_get_clean();

                //Load layout
                $profile = false; // đừng ai xóa
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
