<?php
class TopicFollowModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function isFollowing($userId, $topicId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM topic_follows WHERE user_id = ? AND topic_id = ?");
        $stmt->execute([$userId, $topicId]);
        return $stmt->fetchColumn() > 0;
    }

    public function follow($userId, $topicId) {
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO topic_follows (user_id, topic_id, created_at) VALUES (?, ?, NOW())");
        return $stmt->execute([$userId, $topicId]);
    }

    public function unfollow($userId, $topicId) {
        $stmt = $this->pdo->prepare("DELETE FROM topic_follows WHERE user_id = ? AND topic_id = ?");
        return $stmt->execute([$userId, $topicId]);
    }

    public function countFollowers($topicId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM topic_follows WHERE topic_id = ?");
        $stmt->execute([$topicId]);
        return $stmt->fetchColumn();
    }
}
