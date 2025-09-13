<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/config.php';

// autoload
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/model/admin' . $class . '.php',
        __DIR__ . '/controller/admin' . $class . '.php'
    ];
    foreach ($paths as $p) if (file_exists($p)) require_once $p;
});

// Nếu url rỗng → home
$url = $_GET['url'] ?? '';

if (empty($url)) {
    $ctrl = new homeController();
    $ctrl->index();
    exit;
}

switch ($url) {
    case 'home':

        $ctrl = new homeController();
        $ctrl->index();
        break;

    case 'profile':
        $ctrl = new homeController();
        $ctrl->profile();
        break;
    case 'admin':
        $ctrl = new homeController();
        $ctrl->profile();
        require_once 'controller/homeController.php'; // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new homeController();
        $controller->index();
        break;
    case 'login':
        require_once 'controller/auth/loginController.php';
        $controller = new loginController();
        $controller->index();
        break;
    case 'logout':
        session_destroy();
        header("Location: " . BASE_URL . "");
        break;
    case 'profile':
        require_once 'controller/homeController.php'; // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new homeController();
        $controller->profile();

        break;
    case 'trends':
        require_once 'controller/homeController.php';  // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new homeController();
        $controller->trends();
        break;
    case 'about':
        require_once 'controller/homeController.php';  // tui required home để test giao diện á, nên gắn backend sửa lại chỗ này nha
        $controller = new homeController();
        $controller->about();
        break;
    default:
        echo "404 Not Found";
}