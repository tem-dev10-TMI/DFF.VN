<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');
// [ADD] tránh cache — để số đếm luôn mới
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// [ADD] chỉ chấp nhận POST
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    exit;
}

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để theo dõi']);
    exit;
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/user/UserFollows.php';

// [ADD] nếu UserFollows cần PDO, truyền vào; nếu không, giữ như cũ
$pdo = (new connect())->db;
$followModel = class_exists('UserFollows') 
    ? (new UserFollows($pdo))   // nếu constructor nhận PDO
    : (new UserFollows());       // tương thích với code sếp

$followerId  = (int)($_SESSION['user']['id']);
$followingId = (int)($_POST['user_id'] ?? 0);

// [ADD] (tuỳ chọn) CSRF/session token kiểm tra nếu có gửi theo form
// $token = $_POST['session_token'] ?? '';
// if (empty($token) || $token !== ($_SESSION['user']['session_token'] ?? null)) {
//     echo json_encode(['success' => false, 'message' => 'Token không hợp lệ']); exit;
// }

if ($followingId <= 0 || $followingId === $followerId) {
    echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
    exit;
}

try {
    // Kiểm tra trạng thái hiện tại
    if ($followModel->isFollowing($followerId, $followingId)) {
        // Unfollow
        $followModel->remove($followerId, $followingId);
        $followersCount = (int)$followModel->countFollowers($followingId);

        // [ADD] tính số thông báo chưa đọc (bell)
        $bellCount = 0;
        try {
            // sếp đổi tên bảng/điều kiện cho khớp thực tế:
            // ví dụ: header_events(user_id, is_read) hoặc notifications(user_id, is_read)
            $stm = $pdo->prepare("SELECT COUNT(*) FROM header_events WHERE user_id = ? AND is_read = 0");
            $stm->execute([$followerId]);
            $bellCount = (int)$stm->fetchColumn();
        } catch (\Throwable $e) {
            $bellCount = 0; // fallback an toàn
        }

        echo json_encode([
            'success'    => true,
            'action'     => 'unfollow',
            'followers'  => $followersCount,
            'bell_count' => $bellCount,   // [ADD] trả về số thông báo mới
        ]);
    } else {
        // Follow — idempotent: nếu bảng đã có UNIQUE(follower_id, following_id) thì không lo trùng
        $followModel->add($followerId, $followingId);
        $followersCount = (int)$followModel->countFollowers($followingId);

        // [ADD] số thông báo chưa đọc (bell)
        $bellCount = 0;
        try {
            $stm = $pdo->prepare("SELECT COUNT(*) FROM header_events WHERE user_id = ? AND is_read = 0");
            $stm->execute([$followerId]);
            $bellCount = (int)$stm->fetchColumn();
        } catch (\Throwable $e) {
            $bellCount = 0;
        }

        echo json_encode([
            'success'    => true,
            'action'     => 'follow',
            'followers'  => $followersCount,
            'bell_count' => $bellCount,   // [ADD]
        ]);
    }
} catch (\PDOException $e) {
    // [ADD] Nếu dính duplicate key (user bấm 2 lần nhanh), coi như đã follow thành công
    if ($e->getCode() === '23000') {
        $followersCount = (int)$followModel->countFollowers($followingId);
        echo json_encode([
            'success'    => true,
            'action'     => 'follow',
            'followers'  => $followersCount,
            'bell_count' => 0
        ]);
        exit;
    }
    echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống']);
}
