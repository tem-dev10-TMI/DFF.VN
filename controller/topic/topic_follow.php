<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/topic/TopicFollowModel.php';

$db = new connect();
$pdo = $db->db;
$model = new TopicFollowModel($pdo);

header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user']['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Bạn cần đăng nhập để theo dõi chủ đề'
    ]);
    exit;
}

$userId  = $_SESSION['user']['id'];
$topicId = intval($_POST['topic_id'] ?? 0);

if ($topicId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Chủ đề không hợp lệ'
    ]);
    exit;
}

if ($model->isFollowing($userId, $topicId)) {
    // Đang follow -> hủy
    $model->unfollow($userId, $topicId);
    $followersCount = $model->countFollowers($topicId);

    echo json_encode([
        'success' => true,
        'following' => false,
        'followers_count' => $followersCount
    ]);
} else {
    // Chưa follow -> thêm mới
    $model->follow($userId, $topicId);
    $followersCount = $model->countFollowers($topicId);

    echo json_encode([
        'success' => true,
        'following' => true,
        'followers_count' => $followersCount
    ]);
}
