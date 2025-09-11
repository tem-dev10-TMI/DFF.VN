<?php
require_once __DIR__ . '/../config/db.php';

class TopicFollows {
    private $db;

    public function __construct() {
        global $conn;
        $this->db = $conn;
    }

    // Thêm follow topic
    public function add($user_id, $topic_id) {
        $sql = "INSERT INTO topic_follows (user_id, topic_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$user_id, $topic_id]);
    }

    // Hủy follow topic
    public function remove($user_id, $topic_id) {
        $sql = "DELETE FROM topic_follows WHERE user_id = ? AND topic_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$user_id, $topic_id]);
    }

    // Lấy danh sách topic đang theo dõi
    public function getFollowedTopics($user_id) {
        $sql = "SELECT topic_id, created_at FROM topic_follows WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    // Lấy danh sách user theo dõi topic
    public function getFollowers($topic_id) {
        $sql = "SELECT user_id, created_at FROM topic_follows WHERE topic_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$topic_id]);
        return $stmt->fetchAll();
    }
}
