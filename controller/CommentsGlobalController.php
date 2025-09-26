<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Chỉ start session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../model/CommentGlobalModel.php';

$action = $_GET['action'] ?? '';
header('Content-Type: application/json');

if ($action === "getComments") {
    $last_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;
    
    if ($last_id > 0) {
        // Lấy comment mới hơn last_id
        $comments = CommentGlobalModel::getNewComments($last_id, 20);
    } else {
        // Lấy tất cả comment global
        $comments = CommentGlobalModel::getRootCommentsPaged(50, 0);
    }
    
    // Thêm time_ago cho mỗi comment
    foreach ($comments as &$c) {
        $c['time_ago'] = timeAgo($c['created_at']);
    }
    
    echo json_encode(["status" => "success", "comments" => $comments]);
    exit;
}

if ($action === "addComment") {
    $user_id    = intval($_POST['user_id'] ?? 0);
    $content    = trim($_POST['content'] ?? '');
    $parent_id  = $_POST['parent_id'] ?? null;

    if ($user_id <= 0 || $content === '') {
        echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu"]);
        exit;
    }

    if ($parent_id === '' || $parent_id === null) {
        $parent_id = null;
    }

    $ok = CommentGlobalModel::addComment($user_id, $content, $parent_id);
    if ($ok) {
        // Lấy comment ID vừa thêm bằng cách lấy comment mới nhất của user
        $latestComment = CommentGlobalModel::getLatestCommentByUser($user_id);
        $comment_id = $latestComment ? $latestComment['id'] : 0;
        
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
    
    if ($user_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu"]);
        exit;
    }

    $latestComment = CommentGlobalModel::getLatestCommentByUser($user_id);
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
    
    if ($comment_id <= 0 || $user_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu"]);
        exit;
    }

    // Kiểm tra quyền: chỉ cho phép xóa comment của chính mình
    $comment = CommentGlobalModel::getById($comment_id);
    if (!$comment || $comment['user_id'] != $user_id) {
        echo json_encode(["status" => "error", "message" => "Không có quyền xóa comment này"]);
        exit;
    }

    $ok = CommentGlobalModel::deleteComment($comment_id);
    if ($ok) {
        echo json_encode(["status" => "success", "message" => "Đã xóa bình luận"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Lỗi khi xóa bình luận"]);
    }
    exit;
}

echo json_encode(["status" => "error", "message" => "Action không hợp lệ"]);

function timeAgo($datetime) {
    date_default_timezone_set('Asia/Ho_Chi_Minh'); // VN time
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return "Vừa xong";
    if ($diff < 3600) return floor($diff/60) . " phút trước";
    if ($diff < 86400) return floor($diff/3600) . " giờ trước";
    return date("d/m/Y H:i", $time);
}
?>
