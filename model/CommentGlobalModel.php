<?php
class CommentGlobalModel {

    // Thêm 1 comment mới
    public static function addComment($user_id, $content, $parent_id = null) {
        $db = new connect();
        $sql = "INSERT INTO comment_global (user_id, parent_id, content)
                VALUES (:user_id, :parent_id, :content)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':user_id'   => $user_id,
            ':parent_id' => $parent_id,
            ':content'   => $content
        ]);
    }

    // Xóa 1 comment (kèm các comment con nhờ ON DELETE CASCADE)
    public static function deleteComment($id) {
        $db = new connect();
        $sql = "DELETE FROM comment_global WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Lấy tất cả comment gốc (không có parent) – ví dụ theo bài viết
    // Nếu có bảng articles_comment liên kết, bạn thêm điều kiện article_id
    public static function getRootComments() {
        $db = new connect();
        $sql = "SELECT c.*, u.username
                FROM comment_global c
                INNER JOIN users u ON c.user_id = u.id
                WHERE c.parent_id IS NULL
                ORDER BY c.created_at DESC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy các comment con của 1 comment cha
    public static function getReplies($parent_id) {
        $db = new connect();
        $sql = "SELECT c.*, u.username
                FROM comment_global c
                INNER JOIN users u ON c.user_id = u.id
                WHERE c.parent_id = :parent_id
                ORDER BY c.created_at ASC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':parent_id' => $parent_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tăng số upvotes
    public static function upvote($id) {
        $db = new connect();
        $sql = "UPDATE comment_global SET upvotes = upvotes + 1 WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}