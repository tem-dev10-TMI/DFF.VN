<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để theo dõi']);
    exit;
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/user/UserFollowModel.php';

$db = new connect();
$pdo = $db->db;
$followModel = new UserFollowModel($pdo); // truyền $pdo vào

$followerId  = $_SESSION['user']['id'];
$followingId = intval($_POST['user_id'] ?? 0); // nên dùng user_id cho thống nhất với JS

if ($followingId <= 0 || $followingId == $followerId) {
    echo json_encode(['success' => false, 'message' => 'Người theo dõi không hợp lệ']);
    exit;
}

if ($followModel->isFollowing($followerId, $followingId)) {
    $followModel->unfollow($followerId, $followingId);
    $followers = $followModel->countFollowers($followingId);
    echo json_encode(['success' => true, 'action' => 'unfollow', 'followers' => $followers]);
} else {
    if ($followModel->follow($followerId, $followingId)) {
        $followers = $followModel->countFollowers($followingId);
        echo json_encode(['success' => true, 'action' => 'follow', 'followers' => $followers]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Theo dõi thất bại']);
    }
}

