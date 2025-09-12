<?php
require_once __DIR__ . '/middleware.php';

$route = $_GET['route'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';
$id     = $_GET['id'] ?? null;

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
        include __DIR__ . '/views/dashboard.php';
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

    default:
        // route không khớp → về dashboard
        require_login();
        require_role('admin');
        include __DIR__ . '/views/dashboard.php';
        break;
}

// Nếu controller tồn tại thì chạy action
if (!empty($ctrl)) {
    if (method_exists($ctrl, $action)) {
        if ($id !== null) {
            $ctrl->{$action}($id);
        } else {
            $ctrl->{$action}();
        }
        exit;
    }
}
