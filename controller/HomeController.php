<?php
require_once 'model/article/articlesmodel.php';
require_once 'model/commentmodel.php';
require_once 'model/user/businessmenModel.php';
require_once 'model/MarketDataModel.php';
require_once 'model/TopicModel.php';

class homeController
{
        public static function index()
        {
                //Load model
                //require_once '/../../config/db.php';

                // Fetch data from database
                $articles = ArticlesModel::getAllArticles();
                $comments = CommentsModel::getComments();
                $topBusinessmen = businessmenModel::getAllBusinessmen(10);

                $marketData = MarketDataModel::getCachedMarketData();
                // Lấy chủ đề cho sidebar
                $topicModel = new TopicModel();
                $allTopics = $topicModel->getAll(); // tất cả chủ đề
                $topTopics = array_slice($allTopics, 0, 5); // 5 chủ đề đầu
                $moreTopics = array_slice($allTopics, 5);   // còn lại
                //Load view
                ob_start();

                require_once 'view/page/Home.php';

                $content = ob_get_clean();

                //Load layout
                $profile = false; // đừng ai xóa
                require_once 'view/layout/main.php';
        }
        public static function profile() // test giao diện, ai code backend fix lại đưa sang nơi phù hợp trong controller
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
        public static function trends() // test giao diện, ai code backend fix lại đưa sang nơi phù hợp trong controller
        {
                //Load model

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

        public static function details_blog() // test giao diện, ai code backend fix lại đưa sang nơi phù hợp trong controller
        {
                //Load model
// chưa có dữ liệu ní ơi. mấy ní load dữ liệu lên
                //Load view
                ob_start();
                require_once 'view/page/detail_block.php';
                $content = ob_get_clean();

                //Load layout
                $profile = false; // đừng ai xóa
                require_once 'view/layout/main.php';
        }

}
