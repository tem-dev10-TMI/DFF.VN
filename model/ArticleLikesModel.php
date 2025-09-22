<?php
require_once __DIR__ . '/../config/db.php';

class ArticleLikesModel
{
    private static function getPDO()
    {
        $db = new connect();
        return $db->db;
    }

    /**
     * Kiểm tra user đã like bài viết chưa
     */
    public static function getUserLike($articleId, $userId)
    {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM article_likes WHERE article_id = ? AND user_id = ?");
        $stmt->execute([$articleId, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm like
     */
    public static function addLike($articleId, $userId)
    {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("INSERT INTO article_likes (article_id, user_id, created_at) VALUES (?, ?, NOW())");
        return $stmt->execute([$articleId, $userId]);
    }

    /**
     * Xóa like
     */
    public static function removeLike($articleId, $userId)
    {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("DELETE FROM article_likes WHERE article_id = ? AND user_id = ?");
        return $stmt->execute([$articleId, $userId]);
    }

    /**
     * Toggle like / unlike
     * Nếu chưa like thì thêm, nếu đã like thì xóa
     */
    public static function toggleLike($articleId, $userId, $type)
    {
        // type chỉ hỗ trợ 'like' hoặc 'dislike' (dislike sẽ thành xóa like)

        $existing = self::getUserLike($articleId, $userId);

        if ($type === 'like') {
            if (!$existing) {
                self::addLike($articleId, $userId);
                return 'liked';
            } else {
                // đã like rồi, bỏ like
                self::removeLike($articleId, $userId);
                return 'removed';
            }
        } elseif ($type === 'dislike') {
            // dislike = bỏ like
            if ($existing) {
                self::removeLike($articleId, $userId);
                return 'removed';
            }
            // nếu chưa like thì không làm gì
            return 'none';
        }
    }

    /**
     * Đếm số like của 1 bài viết
     */
    public static function getCounts($articleId)
    {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("SELECT COUNT(*) as like_count FROM article_likes WHERE article_id = ?");
        $stmt->execute([$articleId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result['like_count'] ?? 0);
    }
}
