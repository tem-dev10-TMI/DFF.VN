<?php
class NotFoundController{
    public function index()
    {
        
        ob_start();
        require_once __DIR__ . '/../view/page/404.php';
        $content = ob_get_clean();

        $profile = true;
        require_once __DIR__ . '/../view/layout/main.php';
    }
}

?>