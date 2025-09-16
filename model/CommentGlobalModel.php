<?php
class CommentGlobalModel {

    // Thêm 1 comment mới
   public static function addComment($user_id, $content, $parent_id = null) {
        $db = new connect();

        // Nếu muốn tránh lỗi vì ràng buộc sai -> bỏ parent_id khi insert root comment
        if ($parent_id === null) {
            $sql = "INSERT INTO comment_global (user_id, content, created_at, updated_at)
                    VALUES (:user_id, :content, NOW(), NOW())";
            $stmt = $db->db->prepare($sql);
            return $stmt->execute([
                ':user_id' => $user_id,
                ':content' => $content
            ]);
        } else {
            // nếu có parent_id thì bạn phải đảm bảo parent_id nằm trong bảng comments
            $sql = "INSERT INTO comment_global (user_id, parent_id, content, created_at, updated_at)
                    VALUES (:user_id, :parent_id, :content, NOW(), NOW())";
            $stmt = $db->db->prepare($sql);
            return $stmt->execute([
                ':user_id'   => $user_id,
                ':parent_id' => $parent_id,
                ':content'   => $content
            ]);
        }
    }




    // Xóa comment (kèm comment con nhờ ON DELETE CASCADE)
    public static function deleteComment($id) {
        $db = new connect();
        $sql = "DELETE FROM comment_global WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // ✅ Lấy comment gốc có phân trang
   public static function getRootCommentsPaged($limit = 20, $offset = 0) {
    $db = new connect();
    $sql = "SELECT 
                c.id, c.user_id, c.content, c.upvotes, c.created_at,
                u.username, u.avatar_url
            FROM comment_global c
            LEFT JOIN users u ON c.user_id = u.id
            ORDER BY c.created_at DESC
            LIMIT :limit OFFSET :offset";
    $stmt = $db->db->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Lấy các replies (comment con)
    public static function getReplies($parent_id) {
        $db = new connect();
        $sql = "SELECT 
                    c.id, c.user_id, c.content, c.upvotes, c.created_at,
                    u.username, u.avatar_url
                FROM comment_global c
                LEFT JOIN users u ON c.user_id = u.id
                WHERE c.parent_id = :parent_id
                ORDER BY c.created_at ASC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':parent_id' => $parent_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tăng upvote cho comment
    public static function upvote($id) {
        $db = new connect();
        $sql = "UPDATE comment_global SET upvotes = upvotes + 1 WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public static function getNewComments($last_id, $limit = 20) {
    $db = new connect();
    $sql = "SELECT 
                c.id, c.user_id, c.content, c.upvotes, c.created_at,
                u.username, u.avatar_url
            FROM comment_global c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.id > :last_id
            ORDER BY c.created_at ASC
            LIMIT :limit";
    $stmt = $db->db->prepare($sql);
    $stmt->bindValue(':last_id', (int)$last_id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
