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

// Lấy dữ liệu từ form
$content = isset($_POST['content']) ? trim($_POST['content']) : '';
$profile_category = isset($_POST['profile_category']) ? $_POST['profile_category'] : '';
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$image = null;

// Xử lý upload ảnh nếu có
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'public/img/posts/';
    
    // Tạo thư mục nếu chưa có
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $file_name = 'post_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
    $file_path = $upload_dir . $file_name;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
        $image = $file_path;
    }
}

// Validation
if (empty($content)) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng nhập nội dung bài viết!'
    ]);
    exit;
}

if (strlen($content) < 10) {
    echo json_encode([
        'success' => false,
        'message' => 'Nội dung bài viết phải có ít nhất 10 ký tự!'
    ]);
    exit;
}

// Simulate delay để test loading
sleep(2);

// Tạo ID bài viết mới (trong thực tế sẽ lấy từ database)
$new_post_id = rand(1000, 9999);

// Tạo title từ content (50 ký tự đầu)
$title = strlen($content) > 50 ? substr($content, 0, 50) . '...' : $content;

// Trả về kết quả thành công
echo json_encode([
    'success' => true,
    'message' => 'Đăng bài thành công!',
    'post' => [
        'id' => $new_post_id,
        'title' => $title,
        'content' => $content,
        'author_name' => $profile_category == 'businessmen' ? 'CTCP Tài chính số' : 'Nguyễn Văn A',
        'author_id' => $user_id,
        'avatar' => 'https://i.pinimg.com/1200x/83/0e/ea/830eea38f7a5d3d8e390ba560d14f39c.jpg',
        'time_ago' => 'Vừa xong',
        'image' => $image,
        'likes_count' => 0,
        'comments_count' => 0
    ],
    'profile_category' => $profile_category,
    'user_id' => $user_id
]);
?>
