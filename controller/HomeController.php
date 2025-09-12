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
        public static function profile()
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
}
