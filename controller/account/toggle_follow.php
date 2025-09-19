<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để theo dõi']);
    exit;
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/user/UserFollowModel.php';


$followModel = new UserFollowModel();

$followerId = $_SESSION['user']['id'];
$followingId = intval($_POST['user_id'] ?? 0);

if ($followingId <= 0 || $followingId == $followerId) {
    echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
    exit;
}

if ($followModel->isFollowing($followerId, $followingId)) {
    // Hủy theo dõi
    $followModel->remove($followerId, $followingId);
    $followersCount = $followModel->countFollowers($followingId);
    echo json_encode([
        'success' => true,
        'action' => 'unfollow',
        'followers' => $followersCount
    ]);
} else {
    // Thêm theo dõi
    $followModel->add($followerId, $followingId);
    $followersCount = $followModel->countFollowers($followingId);
    echo json_encode([
        'success' => true,
        'action' => 'follow',
        'followers' => $followersCount
    ]);
}
