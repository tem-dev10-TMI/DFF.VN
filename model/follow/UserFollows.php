<?php
require_once __DIR__ . '/../../config/db.php';

class UserFollows {
    private $db;

    public function __construct() {
        $db = new connect();       // ✅ dùng class connect
        $this->db = $db->db;       // ✅ lấy PDO instance
    }

    // Thêm follow
    public function add($follower_id, $following_id) {
        $sql = "INSERT IGNORE INTO user_follows (follower_id, following_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$follower_id, $following_id]);
    }

    // Hủy follow
    public function remove($follower_id, $following_id) {
        $sql = "DELETE FROM user_follows WHERE follower_id = ? AND following_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$follower_id, $following_id]);
    }

    // Kiểm tra có đang follow không
    public function isFollowing($follower_id, $following_id) {
        $sql = "SELECT COUNT(*) FROM user_follows WHERE follower_id = ? AND following_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$follower_id, $following_id]);
        return $stmt->fetchColumn() > 0;
    }

    // Đếm số followers của 1 user
    public function countFollowers($following_id) {
        $sql = "SELECT COUNT(*) FROM user_follows WHERE following_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$following_id]);
        return (int) $stmt->fetchColumn();
    }

    // Lấy danh sách đang theo dõi
    public function getFollowing($follower_id) {
        $sql = "SELECT following_id, created_at FROM user_follows WHERE follower_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$follower_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách người theo dõi
    public function getFollowers($following_id) {
        $sql = "SELECT follower_id, created_at FROM user_follows WHERE following_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$following_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
