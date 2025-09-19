<?php
require_once __DIR__ . '/../config/db.php'; // chỗ này nhớ sửa lại đường dẫn nếu khác
file_put_contents("debug_log.txt", date("H:i:s") . " " . json_encode($_POST) . "\n", FILE_APPEND);

class CommentsModel
{
    // =====================
    // Thêm comment mới
    // =====================
    public static function insertComment($article_id, $user_id, $content, $parent_id = null)
    {
        $db = new connect();
        $pdo = $db->db;

        $sql = "INSERT INTO comments (article_id, user_id, content, parent_id, created_at) 
                VALUES (:article_id, :user_id, :content, :parent_id, NOW())";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':article_id' => $article_id,
            ':user_id'    => $user_id,
            ':content'    => $content,
            ':parent_id'  => $parent_id
        ]);
    }

    // =====================
    // Lấy comment theo bài viết
    // =====================
    public static function getComments($article_id = null)
{
    $db = new connect();
    $pdo = $db->db;

    $sql = "SELECT c.id, c.content, c.created_at, c.upvotes, 
                   u.username, u.avatar_url
            FROM comments c
            JOIN users u ON c.user_id = u.id";

    // Nếu truyền article_id thì lọc theo bài viết
    if ($article_id !== null) {
        $sql .= " WHERE c.article_id = :article_id AND c.parent_id IS NULL ORDER BY c.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':article_id' => $article_id]);
    } else {
        $sql .= " ORDER BY c.created_at DESC";
        $stmt = $pdo->query($sql);
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // =====================
    // Lấy tất cả comment
    // =====================
    public static function getAllComments()
    {
        $db = new connect();
        $pdo = $db->db;

        $sql = "SELECT c.id, c.article_id, c.content, c.created_at, 
                       u.username, u.avatar_url
                FROM comments c
                JOIN users u ON c.user_id = u.id
                ORDER BY c.created_at DESC";
        $stmt = $pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =====================
    // Lấy reply theo comment cha
    // =====================
    public static function getReplies($parent_id)
    {
        $db = new connect();
        $pdo = $db->db;

        $sql = "SELECT c.id, c.content, c.created_at, c.upvotes, 
                       u.username, u.avatar_url
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.parent_id = :parent_id
                ORDER BY c.created_at ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':parent_id' => $parent_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
error_log("CommentModel loaded from: " . __FILE__);
