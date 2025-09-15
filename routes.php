<?php
require_once __DIR__ . '/middleware.php';

$route  = $_GET['route'] ?? 'dashboard';
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
        include __DIR__ . 'view/admin/views/dashboard.php';
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

    case 'crypton':
        require_login();
        $ctrl = new CryptonController($pdo);
        break;
    // thêm các route khác: topics, tags, media...
    
    default:
        require_login();
        require_role('admin');
        include __DIR__ . 'view/admin/views/dashboard.php';
        break;
}

// Nếu controller có action thì gọi
if (!empty($ctrl)) {
    if (method_exists($ctrl, $action)) {
        $id !== null ? $ctrl->{$action}($id) : $ctrl->{$action}();
    }
}