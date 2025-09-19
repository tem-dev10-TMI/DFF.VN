<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để theo dõi']);
    exit;
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/user/UserFollowModel.php';

$db = new connect();
$pdo = $db->db;

$followModel = new UserFollowModel($pdo);

$followerId  = $_SESSION['user']['id'];
$followingId = intval($_POST['user_id'] ?? 0);

if ($followingId <= 0 || $followingId == $followerId) {
    echo json_encode(['success' => false, 'message' => 'Mày tự follow mày hả thằng ngu!!']);
    exit;
}

if ($followModel->isFollowing($followerId, $followingId)) {
    $followModel->unfollow($followerId, $followingId);
    $followersCount = $followModel->countFollowers($followingId);
    echo json_encode([
        'success' => true,
        'action' => 'unfollow',
        'followers' => $followersCount
    ]);
} else {
    $followModel->follow($followerId, $followingId);
    $followersCount = $followModel->countFollowers($followingId);
    echo json_encode([
        'success' => true,
        'action' => 'follow',
        'followers' => $followersCount
    ]);
}
