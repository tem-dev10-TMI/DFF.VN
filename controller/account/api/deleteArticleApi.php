<?php
// Bật thông báo lỗi để debug (tắt trên production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Giả sử bạn có file connect.php để kết nối CSDL
require_once __DIR__ . '/../../../model/article/articlesmodel.php';

// --- BẢO MẬT: BẮT BUỘC PHẢI KHỞI TẠO SESSION VÀ KIỂM TRA ĐĂNG NHẬP ---
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thực hiện hành động này.']);
    exit;
}

// 2. Kiểm tra phương thức request phải là POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ.']);
    exit;
}

// 3. Lấy và xác thực dữ liệu đầu vào
$postId = $_POST['post_id'] ?? null;
if (empty($postId) || !is_numeric($postId)) {
    echo json_encode(['success' => false, 'message' => 'ID bài viết không hợp lệ.']);
    exit;
}

$currentUserId = $_SESSION['user']['id'];

try {
    $db = new connect();
        $sql = "DELETE FROM articles WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':id' => $currentUserId]);

    // 4. Kiểm tra xem có dòng nào thực sự bị xóa không
    if ($stmt->rowCount() > 0) {
        // Xóa thành công
        echo json_encode(['success' => true, 'message' => 'Đã xóa bài viết thành công.']);
    } else {
        // Không có dòng nào bị xóa, có thể do:
        // - Bài viết không tồn tại.
        // - Người dùng không phải là tác giả của bài viết.
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy bài viết hoặc bạn không có quyền xóa.']);
    }

} catch (PDOException $e) {
    // Ghi lại lỗi thực tế vào file log trên server thay vì hiển thị cho người dùng
    // error_log($e->getMessage()); 
    echo json_encode(['success' => false, 'message' => 'Lỗi cơ sở dữ liệu. Vui lòng thử lại sau.']);
}
?>