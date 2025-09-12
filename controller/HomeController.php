<?php

class homeController {
    public static function index() {
        //Load model
               
        //Load view
        ob_start();
        

        //require_once 'view/home/home.php';

        $content = ob_get_clean();
        
        //Load layout
        require_once 'view/layout/main.php';
    }
}
?>
