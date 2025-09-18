<?php
class UserFollowModel {
    private $db;

    public function __construct() {
        $db = new connect();
        $this->db = $db->db;
    }

    public function isFollowing($follower_id, $following_id) {
        $sql = "SELECT 1 FROM user_follows WHERE follower_id = ? AND following_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$follower_id, $following_id]);
        return $stmt->fetchColumn() ? true : false;
    }

    public function add($follower_id, $following_id) {
        $sql = "INSERT IGNORE INTO user_follows (follower_id, following_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$follower_id, $following_id]);
    }

    public function remove($follower_id, $following_id) {
        $sql = "DELETE FROM user_follows WHERE follower_id = ? AND following_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$follower_id, $following_id]);
    }

    public function countFollowers($user_id) {
        $sql = "SELECT COUNT(*) FROM user_follows WHERE following_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }
}
