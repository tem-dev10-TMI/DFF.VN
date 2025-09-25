<?php
class CommentGlobalModel
{

    // Thêm 1 comment mới
    public static function addComment($user_id, $content, $parent_id = null)
    {
        $db = new connect();

        // Nếu muốn tránh lỗi vì ràng buộc sai -> bỏ parent_id khi insert root comment
        if ($parent_id === null) {
            $sql = "INSERT INTO comment_global (user_id, content, created_at, updated_at)
                VALUES (:user_id, :content, DATE_ADD(NOW(), INTERVAL 7 HOUR), DATE_ADD(NOW(), INTERVAL 7 HOUR))";
            $stmt = $db->db->prepare($sql);
            return $stmt->execute([
                ':user_id' => $user_id,
                ':content' => $content
            ]);
        } else {
            // nếu có parent_id thì bạn phải đảm bảo parent_id nằm trong bảng comments
            $sql = "INSERT INTO comment_global (user_id, parent_id, content, created_at, updated_at)
                VALUES (:user_id, :parent_id, :content, DATE_ADD(NOW(), INTERVAL 7 HOUR), DATE_ADD(NOW(), INTERVAL 7 HOUR))";
            $stmt = $db->db->prepare($sql);
            return $stmt->execute([
                ':user_id'   => $user_id,
                ':parent_id' => $parent_id,
                ':content'   => $content
            ]);
        }
    }





    // Xóa comment (kèm comment con nhờ ON DELETE CASCADE)
    public static function deleteComment($id)
    {
        $db = new connect();
        $sql = "DELETE FROM comment_global WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // ✅ Lấy comment gốc có phân trang
    public static function getRootCommentsPaged($limit = 20, $offset = 0)
    {
        $db = new connect();
        $sql = "SELECT 
                c.id, c.user_id, c.content, c.upvotes, c.created_at,
                c.ai_violation, c.ai_checked, c.ai_details,
                u.name AS username, u.avatar_url
            FROM comment_global c
            LEFT JOIN users u ON c.user_id = u.id
            ORDER BY c.created_at DESC
            LIMIT :limit OFFSET :offset";
        $stmt = $db->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse AI details từ JSON
        foreach ($comments as &$comment) {
            if ($comment['ai_details']) {
                $comment['ai_details'] = json_decode($comment['ai_details'], true);
            }
        }
        
        return $comments;
    }


    // Lấy các replies (comment con)
    public static function getReplies($parent_id)
    {
        $db = new connect();
        $sql = "SELECT 
                    c.id, c.user_id, c.content, c.upvotes, c.created_at,
                    c.ai_violation, c.ai_checked, c.ai_details,
                    u.name AS username, u.avatar_url
                FROM comment_global c
                LEFT JOIN users u ON c.user_id = u.id
                WHERE c.parent_id = :parent_id
                ORDER BY c.created_at ASC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':parent_id' => $parent_id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse AI details từ JSON
        foreach ($comments as &$comment) {
            if ($comment['ai_details']) {
                $comment['ai_details'] = json_decode($comment['ai_details'], true);
            }
        }
        
        return $comments;
    }

    // Tăng upvote cho comment
    public static function upvote($id)
    {
        $db = new connect();
        $sql = "UPDATE comment_global SET upvotes = upvotes + 1 WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Lấy 1 comment theo ID
    public static function getCommentById($id)
    {
        $db = new connect();
        $sql = "SELECT 
            c.id, c.user_id, c.parent_id, c.content, c.upvotes, c.created_at,
            c.ai_violation, c.ai_checked, c.ai_details,
            u.name AS username, u.avatar_url
        FROM comment_global c
        LEFT JOIN users u ON c.user_id = u.id
        WHERE c.id = :id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($comment && $comment['ai_details']) {
            $comment['ai_details'] = json_decode($comment['ai_details'], true);
        }
        
        return $comment;
    }

    public static function getNewComments($last_id, $limit = 20)
    {
        $db = new connect();
        $sql = "SELECT 
                c.id, c.user_id, c.parent_id, c.content, c.upvotes, c.created_at,
                c.ai_violation, c.ai_checked, c.ai_details,
                u.name AS username, u.avatar_url
            FROM comment_global c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.id > :last_id
            ORDER BY c.created_at ASC
            LIMIT :limit";
        $stmt = $db->db->prepare($sql);
        $stmt->bindValue(':last_id', (int)$last_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse AI details từ JSON
        foreach ($comments as &$comment) {
            if ($comment['ai_details']) {
                $comment['ai_details'] = json_decode($comment['ai_details'], true);
            }
        }
        
        return $comments;
    }

    // Lấy ID của comment vừa thêm
    public static function getLastInsertId()
    {
        $db = new connect();
        return $db->db->lastInsertId();
    }

    // Cập nhật kết quả AI check cho comment
    public static function updateAIResult($comment_id, $isViolation, $aiDetails = null)
    {
        $db = new connect();
        $sql = "UPDATE comment_global 
                SET ai_violation = :ai_violation, 
                    ai_checked = 1, 
                    ai_details = :ai_details 
                WHERE id = :comment_id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':comment_id' => $comment_id,
            ':ai_violation' => $isViolation ? 1 : 0,
            ':ai_details' => $aiDetails ? json_encode($aiDetails) : null
        ]);
    }

    // Lấy comment theo ID (alias cho getCommentById)
    public static function getById($comment_id)
    {
        return self::getCommentById($comment_id);
    }

    // Lấy comment mới nhất của user (cho global comments)
    public static function getLatestCommentByUser($user_id)
    {
        $db = new connect();
        $sql = "SELECT c.*, u.name AS username, u.avatar_url,
                       c.ai_violation, c.ai_checked, c.ai_details
                FROM comment_global c 
                LEFT JOIN users u ON c.user_id = u.id 
                WHERE c.user_id = :user_id 
                ORDER BY c.created_at DESC 
                LIMIT 1";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($comment && $comment['ai_details']) {
            $comment['ai_details'] = json_decode($comment['ai_details'], true);
        }
        
        return $comment;
    }

    // Thêm bình luận mới (alias cho addComment)
    public static function insert($user_id, $content, $parent_id = null)
    {
        return self::addComment($user_id, $content, $parent_id);
    }
}
