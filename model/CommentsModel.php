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
        $sql = "SELECT c.*, u.name, u.avatar_url,
                       c.ai_violation, c.ai_checked, c.ai_details
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.article_id = :article_id 
                ORDER BY c.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':article_id' => $article_id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse AI details từ JSON
        foreach ($comments as &$comment) {
            if ($comment['ai_details']) {
                $comment['ai_details'] = json_decode($comment['ai_details'], true);
            }
        }
        
        return $comments;
    }

    // Thêm bình luận mới
    public function insert($article_id, $user_id, $content) {
        $sql = "INSERT INTO comments(article_id, user_id, content, created_at) 
                VALUES(:article_id, :user_id, :content, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':article_id' => $article_id,
            ':user_id'    => $user_id,
            ':content'    => $content
        ]);
        return $result;
    }

    // Lấy ID của comment vừa thêm
    public function getLastInsertId() {
        return $this->pdo->lastInsertId();
    }

    // Cập nhật kết quả AI check cho comment
    public function updateAIResult($comment_id, $isViolation, $aiDetails = null) {
        $sql = "UPDATE comments 
                SET ai_violation = :ai_violation, 
                    ai_checked = 1, 
                    ai_details = :ai_details 
                WHERE id = :comment_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':comment_id' => $comment_id,
            ':ai_violation' => $isViolation ? 1 : 0,
            ':ai_details' => $aiDetails ? json_encode($aiDetails) : null
        ]);
    }

    // Lấy comment theo ID
    public function getById($comment_id) {
        $sql = "SELECT c.*, u.name, u.avatar_url,
                       c.ai_violation, c.ai_checked, c.ai_details
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.id = :comment_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':comment_id' => $comment_id]);
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($comment && $comment['ai_details']) {
            $comment['ai_details'] = json_decode($comment['ai_details'], true);
        }
        
        return $comment;
    }

    // Lấy comment mới nhất của user cho một article
    public function getLatestCommentByUser($user_id, $article_id) {
        $sql = "SELECT c.*, u.name, u.avatar_url,
                       c.ai_violation, c.ai_checked, c.ai_details
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.user_id = :user_id AND c.article_id = :article_id 
                ORDER BY c.created_at DESC 
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':article_id' => $article_id
        ]);
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($comment && $comment['ai_details']) {
            $comment['ai_details'] = json_decode($comment['ai_details'], true);
        }
        
        return $comment;
    }
}
