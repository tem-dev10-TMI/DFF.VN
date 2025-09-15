<?php
require_once 'model/TopicModel.php';

class topicController
{
    // Hiển thị danh sách tất cả chủ đề (ví dụ trang chính hoặc sidebar)
    public static function index()
    {
        $topicModel = new TopicModel();
        $topics = $topicModel->getAll(); // lấy tất cả chủ đề

        // Load view
        ob_start();
        require_once 'view/layout/sidebarLeft.php'; // file view hiển thị danh sách chủ đề
        $content = ob_get_clean();

        // Load layout
        $profile = false;
        require_once 'view/layout/main.php';
    }

    // Lấy top N chủ đề (ví dụ sidebar menu)
    public static function top($limit = 5)
    {
        $topicModel = new TopicModel();
        $topics = $topicModel->getTop($limit);

        // Load view sidebar
        ob_start();
        require 'view/layout/sidebarLeft.php';
        $content = ob_get_clean();

        // Load layout
        $profile = false;
        require 'view/layout/main.php';
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
        require 'view/page/TopicDetails.php'; // bạn tạo view này để hiển thị bài viết theo topic
        $content = ob_get_clean();

        $profile = false;
        require 'view/layout/main.php';
    }
}
