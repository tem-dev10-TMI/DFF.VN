<?php
class CommentsModel {
    // Thêm comment mới
    public static function addComment($article_id, $user_id, $content, $parent_id = null) {
        $db = new connect();
        $sql = "INSERT INTO comments (article_id, user_id, parent_id, content) 
                VALUES (:article_id, :user_id, :parent_id, :content)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':article_id' => $article_id,
            ':user_id' => $user_id,
            ':parent_id' => $parent_id,
            ':content' => $content
        ]);
    }

    // Lấy tất cả comment của một bài viết (kể cả trả lời)
    public static function getCommentsByArticle($article_id) {
        $db = new connect();
        $sql = "SELECT c.*, u.name AS user_name, u.avatar_url
                FROM comments c
                INNER JOIN users u ON c.user_id = u.id
                WHERE c.article_id = :article_id
                ORDER BY c.created_at ASC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':article_id' => $article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy trả lời theo comment cha
    public static function getReplies($parent_id) {
        $db = new connect();
        $sql = "SELECT c.*, u.name AS user_name, u.avatar_url
                FROM comments c
                INNER JOIN users u ON c.user_id = u.id
                WHERE c.parent_id = :parent_id
                ORDER BY c.created_at ASC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':parent_id' => $parent_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật nội dung comment
    public static function updateComment($id, $content) {
        $db = new connect();
        $sql = "UPDATE comments SET content = :content WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':content' => $content
        ]);
    }

    // Xóa comment (kể cả reply nhờ ON DELETE CASCADE)
    public static function deleteComment($id) {
        $db = new connect();
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Tăng số lượt upvote
    public static function upvoteComment($id) {
        $db = new connect();
        $sql = "UPDATE comments SET upvotes = upvotes + 1 WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    // Lấy tất cả comment (không phân theo bài viết)
public static function getComments() {
    $db = new connect();
    $sql = "SELECT c.id, c.user_id, c.content, c.upvotes, c.created_at,
                   u.name AS user_name, u.avatar_url
            FROM comment_global c
            INNER JOIN users u ON c.user_id = u.id
            ORDER BY c.created_at ASC";
    $stmt = $db->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
