<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/model/CommentGlobalModel.php';

// 🔥 Debug log ra file để kiểm tra có chạy không
file_put_contents(__DIR__ . "/comment_debug.log", date("Y-m-d H:i:s") . " - POST: " . json_encode($_POST) . PHP_EOL, FILE_APPEND);

// Kiểm tra login
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

// Lưu vào DB
$parent_id = $_POST['parent_id'] ?? null;

// Nếu không có parent_id thì gán NULL
if ($parent_id === '' || $parent_id === null) {
    $parent_id = null;
}

$ok = CommentGlobalModel::addComment($user_id, $content, $parent_id);
