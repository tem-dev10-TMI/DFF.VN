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
        __DIR__ . '/model/' . $class . '.php',              // thêm model thường
        __DIR__ . '/model/event/' . $class . '.php',
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
        exit;

    case 'logout':
        $ctrl = new AuthController($pdo);
        $ctrl->logout();
        exit;

    case 'dashboard':
        require_login();
        require_role('admin');
        include __DIR__ . '/view/admin/views/dashboard.php';
        exit;

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

    case 'article': // riêng cho bài viết (review, form, list)
        $ctrl = new ArticleController($pdo);
        if ($action === 'list') {
            $ctrl->list();
        } elseif ($action === 'form') {
            $ctrl->form();
        } elseif ($action === 'reviewList') {
            $ctrl->reviewList();
        } elseif ($action === 'reviewAction') {
            $ctrl->reviewAction();
        }
        exit;

    case 'events':  
        require_login();
        require_role('admin');
        $ctrl = new EventController($pdo);
        break;

   case 'accounts': // ✅ duyệt tài khoản doanh nhân
    require_login();
    require_role('admin');

    if ($action === 'reviewList') {
        include __DIR__ . '/view/admin/views/accounts/review_accounts.php';
        exit;
    }

    if ($action === 'approve') {
        $id = intval($_GET['id']);
        $stmt = $pdo->prepare("SELECT user_id FROM businessmen WHERE id = ?");
        $stmt->execute([$id]);
        $user_id = $stmt->fetchColumn();

        if ($user_id) {
            // Cập nhật status = approved + đổi role
            $pdo->prepare("UPDATE businessmen SET status = 'approved', updated_at = NOW() WHERE id = ?")
                ->execute([$id]);
            $pdo->prepare("UPDATE users SET role = 'businessmen' WHERE id = ?")
                ->execute([$user_id]);
        }

        header("Location: admin.php?route=accounts&action=reviewList");
        exit;
    }

    if ($action === 'reject') {
        $id = intval($_GET['id']);
        $pdo->prepare("DELETE FROM businessmen WHERE id=?")->execute([$id]);
        header("Location: admin.php?route=accounts&action=reviewList");
        exit;
    }
    break;

    default:
        require_login();
        require_role('admin');
        include __DIR__ . '/view/admin/views/dashboard.php';
        exit;
}

// Nếu có controller & action -> gọi
if (!empty($ctrl)) {
    if (method_exists($ctrl, $action)) {
        $id !== null ? $ctrl->{$action}($id) : $ctrl->{$action}();
    } else {
        // fallback: gọi admin() nếu action không tồn tại
        if (method_exists($ctrl, 'admin')) {
            $ctrl->admin();
        }
    }
}
