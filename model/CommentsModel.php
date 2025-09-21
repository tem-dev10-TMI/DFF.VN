<?php
require_once __DIR__ . '/../config/db.php';

class CommentsModel {
    private $pdo;

    public function __construct() {
        $db = new connect();
        $this->pdo = $db->db;
    }

    // Lấy tất cả bình luận của 1 bài viết
    public function getByArticle($article_id) {
        $sql = "SELECT c.*, u.name, u.avatar_url 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.article_id = :article_id 
                ORDER BY c.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':article_id' => $article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm bình luận mới
    public function insert($article_id, $user_id, $content) {
        $sql = "INSERT INTO comments(article_id, user_id, content, created_at) 
                VALUES(:article_id, :user_id, :content, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':article_id' => $article_id,
            ':user_id'    => $user_id,
            ':content'    => $content
        ]);
    }
}
