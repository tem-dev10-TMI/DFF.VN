<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/model/CommentGlobalModel.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Bạn phải đăng nhập.']);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$content = trim($_POST['content'] ?? '');
$parent_id = $_POST['parent_id'] ?? null;

if ($content === '') {
    echo json_encode(['success' => false, 'error' => 'Nội dung trống.']);
    exit;
}

if ($parent_id === '' || $parent_id === null) {
    $parent_id = null;
}

// The database connection is needed to get the last insert ID
$db = new connect(); 

$ok = CommentGlobalModel::addComment($user_id, $content, $parent_id);

if ($ok) {
    $lastId = $db->db->lastInsertId();
    $comment = CommentGlobalModel::getCommentById($lastId);
    echo json_encode(['success' => true, 'comment' => $comment]);
} else {
    echo json_encode(['success' => false, 'error' => 'Lỗi: không thể thêm bình luận.']);
}