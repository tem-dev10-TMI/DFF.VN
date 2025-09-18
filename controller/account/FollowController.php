<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/user/UserFollowModel.php';

$db = new connect();
$pdo = $db->db;
$followModel = new UserFollowModel();

header('Content-Type: application/json');

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để theo dõi']);
    exit;
}

$followerId = $_SESSION['user']['id'];
$followingId = intval($_POST['following_id'] ?? 0);

if ($followingId <= 0 || $followingId == $followerId) {
    echo json_encode(['success' => false, 'message' => 'Người theo dõi không hợp lệ']);
    exit;
}

if ($model->isFollowing($followerId, $followingId)) {
    $model->unfollow($followerId, $followingId);
    echo json_encode(['success' => true, 'action' => 'unfollow']);
} else {
    $model->follow($followerId, $followingId);
    echo json_encode(['success' => true, 'action' => 'follow']);
}
