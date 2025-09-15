<?php
header('Content-Type: application/json');

// Kiểm tra method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức không được hỗ trợ!'
    ]);
    exit;
}

// Lấy dữ liệu từ request
$input = json_decode(file_get_contents('php://input'), true);
$post_id = isset($input['post_id']) ? intval($input['post_id']) : 0;
$user_id = isset($input['user_id']) ? intval($input['user_id']) : 0;
$action = isset($input['action']) ? $input['action'] : 'like';

// Validation
if ($post_id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'ID bài viết không hợp lệ!'
    ]);
    exit;
}

if ($user_id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng đăng nhập để thực hiện hành động này!'
    ]);
    exit;
}

if (!in_array($action, ['like', 'dislike'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Hành động không hợp lệ!'
    ]);
    exit;
}

// Simulate delay để test loading
sleep(1);

// Simulate like/dislike logic
// Trong thực tế sẽ query database để kiểm tra và cập nhật
$current_likes = rand(10, 50); // Simulate current likes count
$new_likes = $action === 'like' ? $current_likes + 1 : max(0, $current_likes - 1);

// Trả về kết quả
echo json_encode([
    'success' => true,
    'message' => $action === 'like' ? 'Đã thích bài viết!' : 'Đã bỏ thích bài viết!',
    'post_id' => $post_id,
    'user_id' => $user_id,
    'action' => $action,
    'likes_count' => $new_likes,
    'previous_likes' => $current_likes
]);
?>
