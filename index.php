<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once __DIR__ . '/helpers.php';
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', 'localhost'); // nếu chạy ở localhost
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

    foreach ($paths as $p)
        if (file_exists($p))
            require_once $p;
});


error_reporting(E_ALL);
ini_set('display_errors', 1);

$url = isset($_GET['url']) ? $_GET['url'] : '';

if (empty($url)) {
    require_once __DIR__ . '/controller/HomeController.php';
    $controller = new homeController();
    $controller->index();
    exit;
}

switch ($url) {

    case 'home':
        require_once __DIR__ . '/controller/HomeController.php';
        $ctrl = new homeController();
        $ctrl->index();
        break;

    // case 'profile':
    //     $ctrl = new homeController();
    //     $ctrl->profile();
    //     break;

    case 'login':
        require_once __DIR__ . '/controller/auth/loginController.php';
        $controller = new loginController();
        $controller->index();
        break;
    case 'register':
        require_once __DIR__ . '/controller/auth/registerUserController.php';
        $controller = new registerUserController();
        $controller->index();
        break;
    case 'logout':
        session_destroy();
        header("Location: " . BASE_URL . "");
        break;
    case 'change_password':
        require_once __DIR__ . '/controller/account/profileUserController.php';
        $controller = new profileUserController();
        $controller->changePassword();
        break;
    case 'profile_user':
        require_once __DIR__ . '/controller/account/profileUserController.php'; // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new profileUserController();
        $controller->index();
        break;
    case 'register_business':
        require_once __DIR__ . '/controller/account/profileUserController.php';
        $controller = new profileUserController();
        $controller->registerBusiness();
        break;

    case 'edit_profile':
        require_once __DIR__ . '/controller/account/profileUserController.php'; // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new profileUserController();
        $controller->editProfile();
        break;
    case 'profile_business':
        require_once __DIR__ . '/controller/account/businessmenController.php'; // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new businessmenController();
        $controller->index();
        break;
    case 'edit_business':
        require_once __DIR__ . '/controller/account/businessmenController.php'; // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new businessmenController();
        $controller->editBusiness();
        break;
    case 'edit_business_career':
        require_once __DIR__ . '/controller/account/businessmenController.php'; // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new businessmenController();
        $controller->editBusinessCareer();
        break;
    case 'add_article':
        require_once __DIR__ . '/controller/account/profileUserController.php'; // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new profileUserController();
        $controller->addArticle();
        break;
    case 'trends':
        require_once __DIR__ . '/controller/HomeController.php';  // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new homeController();
        $controller->trends();
        break;
    /*case 'details_topic':
                 require_once __DIR__ . '/controller/TopicController.php';
        $controller = new TopicController();
        $controller->details_topic();
        break; */
    case (preg_match('/^details_topic\/([^\/]+)$/', $url, $matches) ? true : false):
        require_once __DIR__ . '/controller/TopicController.php';
        $controller = new TopicController();
        $controller->details_topic($matches[1]);
        break;
    case 'about':
        require_once __DIR__ . '/controller/HomeController.php';  // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new homeController();
        $controller->about();
        break;
    case (preg_match('/^details_blog\/([^\/]+)$/', $url, $matches) ? true : false):
        require_once __DIR__ . '/controller/ArticlesController.php';
        $controller = new ArticlesController();

        $id = $_GET['id'] ?? null;
        $controller->details_blog($matches[1]);
        break;
    case 'search':
        require_once __DIR__ . '/controller/SearchController.php';
        $controller = new SearchController();
        $controller->index();
        break;

    case 'news':
        require_once __DIR__ . '/controller/NewsController.php';
        $controller = new NewsController();
        $controller->index();
        break;
    case 'api/loadMoreArticles':
        require_once __DIR__ . '/controller/HomeController.php';
        $controller = new homeController();
        $controller->loadMoreArticles();
        exit;
    case 'crypton':
        require_once __DIR__ . '/controller/CryptonController.php';
        $controller = new CryptonController();
        $controller->index();
        break;
    case 'view_profile':
        require_once __DIR__ . '/controller/account/profileUserController.php';
        $controller = new profileUserController();
        $controller->viewprofileUser();
        break;

    case 'event':
        require_once __DIR__ . '/controller/EventDetailController.php';
        $controller = new EventDetailController($pdo);
        $id = $_GET['id'] ?? null;
        $controller->details($id);
        break;

    case 'comment':
        require_once __DIR__ . '/controller/CommentsController.php';
        break;

    case 'details_blog':
        $id = $_GET['id'] ?? 0;
        require_once __DIR__ . '/controller/BlogController.php';
        $controller = new BlogController();
        $controller->show($id);
        break;


    // ========== API ROUTES ==========
    case 'api/addPost':
        require_once __DIR__ . '/controller/account/profileUserController.php';
        $controller = new profileUserController();
        $controller->addArticle();
        exit;
    case 'api/deletePost':
        require_once __DIR__ . '/controller/account/profileUserController.php';
        $controller = new profileUserController();
        $controller->deleteArticle();
        exit;
    case 'api/loadPost':
        require_once __DIR__ . '/controller/account/profileUserController.php';
        $controller = new profileUserController();
        $controller->loadArticle();
        exit;

    case 'api/toggle-like':
        require_once __DIR__ . '/controller/account/profileUserController.php';
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
