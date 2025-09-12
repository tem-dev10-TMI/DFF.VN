<?php

class homeController
{
        public static function index()
        {
                //Load model

                //Load view
                ob_start();

                require_once 'view/page/home.php';
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
                require_once 'view/page/Profileuse.php';
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
}
