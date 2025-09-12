<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';
require_once 'config/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$url = isset($_GET['url']) ? $_GET['url'] : '';

if (empty($url)) {
    require_once 'controller/homeController.php';
    $controller = new homeController();
    $controller->index();
    exit;
}

switch ($url) {
    /*     case 'home':
switch($url){
     case 'home':
        require_once 'controller/HomeController.php';
        $controller = new homeController();
        $controller->index();
        break; 
        */

    default:
        //404 page
        /*         require_once 'controller/error/404Controller.php';
        $controller = new NotFoundController;
        require_once 'controller/error/404Controller.php';
        //$controller = new NotFoundController;
        $controller->index();
        break; */
}
