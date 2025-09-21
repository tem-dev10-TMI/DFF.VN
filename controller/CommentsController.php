
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Chỉ start session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../model/CommentsModel.php';

$action = $_GET['action'] ?? '';
$model = new CommentsModel();

header('Content-Type: application/json');

if ($action === "getComments") {
    $article_id = intval($_GET['article_id'] ?? 0);
    if ($article_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Thiếu article_id"]);
        exit;
    }

    $comments = $model->getByArticle($article_id);
    echo json_encode(["status" => "success", "comments" => $comments]);
    exit;
}

if ($action === "addComment") {
    $article_id = intval($_POST['article_id'] ?? 0);
    $user_id    = intval($_POST['user_id'] ?? 0);
    $content    = trim($_POST['content'] ?? '');

    if ($article_id <= 0 || $user_id <= 0 || $content === '') {
        echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu"]);
        exit;
    }

    $ok = $model->insert($article_id, $user_id, $content);
    if ($ok) {
        echo json_encode(["status" => "success", "message" => "Đã thêm bình luận"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Lỗi khi thêm bình luận"]);
    }
    exit;
}

echo json_encode(["status" => "error", "message" => "Action không hợp lệ"]);
