<?php
class ArticleLikesModel {

    // Thêm 1 lượt like cho bài viết
    public static function addLike($article_id, $user_id) {
        $db = new connect();
        $sql = "INSERT INTO article_likes (article_id, user_id)
                VALUES (:article_id, :user_id)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':article_id' => $article_id,
            ':user_id'    => $user_id
        ]);
    }

    // Xóa 1 lượt like của user khỏi bài viết
    public static function removeLike($article_id, $user_id) {
        $db = new connect();
        $sql = "DELETE FROM article_likes
                WHERE article_id = :article_id AND user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':article_id' => $article_id,
            ':user_id'    => $user_id
        ]);
    }

    // Kiểm tra user đã like bài viết chưa
    public static function hasLiked($article_id, $user_id) {
        $db = new connect();
        $sql = "SELECT 1 FROM article_likes
                WHERE article_id = :article_id AND user_id = :user_id
                LIMIT 1";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([
            ':article_id' => $article_id,
            ':user_id'    => $user_id
        ]);
        return $stmt->fetchColumn() !== false;
    }

    // Đếm tổng lượt like của 1 bài viết
    public static function countLikes($article_id) {
        $db = new connect();
        $sql = "SELECT COUNT(*) FROM article_likes
                WHERE article_id = :article_id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':article_id' => $article_id]);
        return (int) $stmt->fetchColumn();
    }

    // Lấy danh sách user đã like 1 bài viết
    public static function getUsersLikedArticle($article_id) {
        $db = new connect();
        $sql = "SELECT u.* FROM users u
                INNER JOIN article_likes al ON u.id = al.user_id
                WHERE al.article_id = :article_id
                ORDER BY al.created_at DESC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':article_id' => $article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
