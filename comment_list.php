<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/model/CommentGlobalModel.php';

$last_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;

// Nếu có last_id thì lấy comment mới hơn
$comments = CommentGlobalModel::getNewComments($last_id, 20);

foreach ($comments as &$c) {
    // Đổi tên key để nhất quán với JS render
    if (isset($c['name'])) {
        $c['username'] = $c['name'];
        unset($c['name']);
    }
    $c['time_ago'] = timeAgo($c['created_at']);
}

echo json_encode([
    'success' => true,
    'comments' => $comments
]);

function timeAgo($datetime) {
    date_default_timezone_set('Asia/Ho_Chi_Minh'); // VN time
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return "Vừa xong";
    if ($diff < 3600) return floor($diff/60) . " phút trước";
    if ($diff < 86400) return floor($diff/3600) . " giờ trước";
    return date("d/m/Y H:i", $time);
}
