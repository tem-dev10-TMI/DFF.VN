<?php
class UserFollowModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function follow($followerId, $followingId) {
        $stmt = $this->db->prepare("INSERT INTO user_follows (follower_id, following_id) VALUES (?, ?)");
        return $stmt->execute([$followerId, $followingId]);
    }

    public function unfollow($followerId, $followingId) {
        $stmt = $this->db->prepare("DELETE FROM user_follows WHERE follower_id = ? AND following_id = ?");
        return $stmt->execute([$followerId, $followingId]);
    }

    public function isFollowing($followerId, $followingId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM user_follows WHERE follower_id = ? AND following_id = ?");
        $stmt->execute([$followerId, $followingId]);
        return $stmt->fetchColumn() > 0;
    }

    public function countFollowers($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM user_follows WHERE following_id = ?");
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }
}
