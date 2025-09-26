
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
        // Lấy comment ID vừa thêm
        $comment_id = $model->getLastInsertId();
        echo json_encode([
            "status" => "success", 
            "message" => "Đã thêm bình luận",
            "comment_id" => $comment_id
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Lỗi khi thêm bình luận"]);
    }
    exit;
}

if ($action === "getLatestComment") {
    $user_id = intval($_GET['user_id'] ?? 0);
    $article_id = intval($_GET['article_id'] ?? 0);
    
    if ($user_id <= 0 || $article_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu"]);
        exit;
    }

    $latestComment = $model->getLatestCommentByUser($user_id, $article_id);
    if ($latestComment) {
        echo json_encode(["status" => "success", "comment_id" => $latestComment['id']]);
    } else {
        echo json_encode(["status" => "error", "message" => "Không tìm thấy comment"]);
    }
    exit;
}

if ($action === "deleteComment") {
    $comment_id = intval($_POST['comment_id'] ?? 0);
    $user_id = intval($_POST['user_id'] ?? 0);
    
    // Debug logs
    error_log("DEBUG deleteComment: comment_id=$comment_id, user_id=$user_id");
    
    if ($comment_id <= 0 || $user_id <= 0) {
        error_log("DEBUG deleteComment: Missing data - comment_id=$comment_id, user_id=$user_id");
        echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu"]);
        exit;
    }

    // Kiểm tra quyền: chỉ cho phép xóa comment của chính mình
    $comment = $model->getById($comment_id);
    error_log("DEBUG deleteComment: Found comment: " . json_encode($comment));
    
    if (!$comment) {
        error_log("DEBUG deleteComment: Comment not found with ID: $comment_id");
        echo json_encode(["status" => "error", "message" => "Không tìm thấy comment này"]);
        exit;
    }
    
    if ($comment['user_id'] != $user_id) {
        error_log("DEBUG deleteComment: Permission denied - comment user_id={$comment['user_id']}, request user_id=$user_id");
        echo json_encode(["status" => "error", "message" => "Không có quyền xóa comment này"]);
        exit;
    }

    $ok = $model->delete($comment_id);
    if ($ok) {
        error_log("DEBUG deleteComment: Successfully deleted comment ID: $comment_id");
        echo json_encode(["status" => "success", "message" => "Đã xóa bình luận"]);
    } else {
        error_log("DEBUG deleteComment: Failed to delete comment ID: $comment_id");
        echo json_encode(["status" => "error", "message" => "Lỗi khi xóa bình luận"]);
    }
    exit;
}

echo json_encode(["status" => "error", "message" => "Action không hợp lệ"]);
