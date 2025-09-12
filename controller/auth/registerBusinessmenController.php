<?php
require_once 'models/businessmenModel.php';

class BusinessmenController {
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId      = $_POST['user_id'];
            $birth_year  = $_POST['birth_year'];
            $nationality = $_POST['nationality'];
            $education   = $_POST['education'];
            $position    = $_POST['position'];

            $db = new connect();

            // ==============================
            // Lấy số followers từ bảng user_follows
            // ==============================
            $sqlFollowers = "SELECT COUNT(*) AS total_followers 
                             FROM user_follows 
                             WHERE following_id = ?";
            $stmt = $db->db->prepare($sqlFollowers);
            $stmt->execute([$userId]);
            $followers = $stmt->fetch(PDO::FETCH_ASSOC)['total_followers'] ?? 0;

            // ==============================
            // Lấy số likes từ bảng article_likes (theo các bài viết của user)
            // ==============================
            $sqlLikes = "SELECT COUNT(*) AS total_likes 
                         FROM article_likes 
                         WHERE article_id IN (
                             SELECT id FROM articles WHERE user_id = ?
                         )";
            $stmt = $db->db->prepare($sqlLikes);
            $stmt->execute([$userId]);
            $likes = $stmt->fetch(PDO::FETCH_ASSOC)['total_likes'] ?? 0;

            // ==============================
            // Kiểm tra điều kiện
            // ==============================
            if ($followers < 100 || $likes < 2000) {
                $error = "Bạn cần có ít nhất 100 lượt theo dõi và 2000 lượt like để đăng ký doanh nhân.";
                include 'views/registerBusinessmen.php';
                exit;
            }

            // ==============================
            // Nếu đủ điều kiện → Lưu vào bảng businessmen
            // ==============================
            $success = businessmenModel::registerBusiness(
                $userId,
                $birth_year,
                $nationality,
                $education,
                $position
            );

            if ($success) {
                $message = "Đăng ký doanh nhân thành công!";
                include 'views/registerSuccess.php';
            } else {
                $error = "Có lỗi xảy ra, vui lòng thử lại.";
                include 'views/registerBusinessmen.php';
            }
        } else {
            include 'views/registerBusinessmen.php';
        }
    }
}
