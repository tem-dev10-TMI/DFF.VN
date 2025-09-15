<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/middleware.php';

// autoload cho model + controller
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/model/admin/' . $class . '.php',
        __DIR__ . '/controller/admin/' . $class . '.php',
    ];
    foreach ($paths as $p) {
        if (file_exists($p)) {
            require_once $p;
            return;
        }
    }
});

// Lấy tham số route/action/id
$route  = $_GET['route'] ?? 'dashboard';
$action = $_GET['action'] ?? 'admin';
$id     = $_GET['id'] ?? null;

$ctrl = null;

switch ($route) {
    case 'login':
        $ctrl = new AuthController($pdo);
        if ($action == 'do') {
            $ctrl->login();
        } else {
            $ctrl->loginForm();
        }
        break;

    case 'logout':
        $ctrl = new AuthController($pdo);
        $ctrl->logout();
        break;

    case 'dashboard':
        require_login();
        require_role('admin');
        include __DIR__ . '/view/admin/views/dashboard.php';
        break;

    case 'users':
        require_login();
        require_role('admin');
        $ctrl = new UserController($pdo);
        break;

    case 'articles':
        require_login();
        require_role('admin');
        $ctrl = new ArticleController($pdo);
        break;
         case 'topics':
        require_login();
        require_role('admin');
        $ctrl = new TopicController($pdo);
        break;

    case 'tags':
        require_login();
        require_role('admin');
        $ctrl = new TagController($pdo);
        break;

    case 'media':
        require_login();
        require_role('admin');
        $ctrl = new MediaController($pdo);
        break;

    case 'comments':
        require_login();
        require_role('admin');
        $ctrl = new CommentController($pdo);
        break;

    case 'events':
        require_login();
        require_role('admin');
        $ctrl = new EventController($pdo);
        break;
    // có thể thêm topics, tags, media...
    default:
        require_login();
        require_role('admin');
        include __DIR__ . '/view/admin/views/dashboard.php';
        break;

        case 'article':
            $controller = new ArticleController($pdo);
        
            if ($action === 'list') {
                $controller->list();
            } elseif ($action === 'form') {
                $controller->form();
            } elseif ($action === 'reviewList') {
                $controller->reviewList();
            } elseif ($action === 'reviewAction') {
                $controller->reviewAction();
            }
            break;
           
}

// Nếu controller có action thì gọi
if (!empty($ctrl)) {
    if (method_exists($ctrl, $action)) {
        $id !== null ? $ctrl->{$action}($id) : $ctrl->{$action}();
    }
}
