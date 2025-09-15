<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


require_once 'config/db.php';
require_once 'config/config.php';

// autoload
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/model/' . $class . '.php',
        __DIR__ . '/controller/' . $class . '.php'
    ];

    foreach ($paths as $p) if (file_exists($p)) require_once $p;
});


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

    case 'home':

        $ctrl = new homeController();
        $ctrl->index();
        break;

    // case 'profile':
    //     $ctrl = new homeController();
    //     $ctrl->profile();
    //     break;

    case 'login':
        require_once 'controller/auth/loginController.php';
        $controller = new loginController();
        $controller->index();
        break;
    case 'logout':
        session_destroy();
        header("Location: " . BASE_URL . "");
        break;
    case 'profileUser':
        require_once 'controller/account/profileUserController.php'; // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new profileUserController();
        $controller->profileUser();
        break;
    case 'edit_profile':
        require_once 'controller/account/profileUserController.php'; // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new profileUserController();
        $controller->editProfile();
        break;
    case 'profile_business':
        require_once 'controller/homeController.php'; // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new homeController();
        $controller->profile_business();
        break;
    case 'add_article':
        require_once 'controller/account/profileUserController.php'; // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new profileUserController();
        $controller->addArticle();
        break;
    case 'trends':
        require_once 'controller/homeController.php';  // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new homeController();
        $controller->trends();
        break;
    case 'details_topic':
        require_once 'controller/admin/TopicController.php';
        $controller = new TopicController();
        $controller->details_topic();
        break;
    case 'about':
        require_once 'controller/homeController.php';  // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new homeController();
        $controller->about();
        break;
    case 'details_blog':
        require_once 'controller/homeController.php';  // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new homeController();
        $controller->details_blog();
        break;
    case 'crypton':
        require_once 'controller/CryptonController.php';
        $controller = new CryptonController();
        $controller->index();
        break;
    case 'view_profile_user':
        require_once 'controller/account/profileUserController.php';
        $controller = new profileUserController();
        $controller->profileUser();
        break;
    case 'view_profile_business':
        require_once 'controller/account/profileUserController.php';
        $controller = new profileUserController();
        $controller->profileBusiness();
        break;

    // ========== API ROUTES ==========
    case 'api/load-posts':
        require_once 'controller/test-api-profile/loadPosts.php';
        break;
    case 'api/add-post':
        require_once 'controller/test-api-profile/addPost.php';
        break;
    case 'api/toggle-like':
        require_once 'controller/test-api-profile/toggleLike.php';
        break;

    default:
        //404 page
        /*         require_once 'controller/error/404Controller.php';
        $controller = new NotFoundController;
        require_once 'controller/error/404Controller.php';
        //$controller = new NotFoundController;
        $controller->index();
        break; */
}
