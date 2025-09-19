@ -0,0 +1,43 @@
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
require_once __DIR__ . '/../../model/user/UserFollows.php';

$followModel = new UserFollows();

$followerId = $_SESSION['user']['id'];
$followingId = intval($_POST['user_id'] ?? 0);

if ($followingId <= 0 || $followingId == $followerId) {
    echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
    exit;
}

// Kiểm tra trạng thái hiện tại
if ($followModel->isFollowing($followerId, $followingId)) {
    $followModel->remove($followerId, $followingId);
    $followersCount = $followModel->countFollowers($followingId);
    echo json_encode([
        'success' => true,
        'action' => 'unfollow',
        'followers' => $followersCount
    ]);
} else {
    $followModel->add($followerId, $followingId);
    $followersCount = $followModel->countFollowers($followingId);
    echo json_encode([
        'success' => true,
        'action' => 'follow',
        'followers' => $followersCount
    ]);
}