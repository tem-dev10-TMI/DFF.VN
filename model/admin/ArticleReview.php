<?php
class ArticleReview {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Thêm lịch sử duyệt bài
    public function addReview($article_id, $admin_id, $action, $reason = null) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO article_reviews (article_id, admin_id, action, reason, reviewed_at)
             VALUES (:article_id, :admin_id, :action, :reason, NOW())"
        );
        return $stmt->execute([
            ':article_id' => $article_id,
            ':admin_id'   => $admin_id,
            ':action'     => $action,
            ':reason'     => $reason
        ]);
    }

    // Lấy lịch sử duyệt theo bài viết
    public function getByArticle($article_id) {
        $stmt = $this->pdo->prepare(
            "SELECT r.*, u.username AS admin_name
             FROM article_reviews r
             JOIN users u ON r.admin_id = u.id
             WHERE r.article_id = :article_id
             ORDER BY r.reviewed_at DESC"
        );
        $stmt->execute([':article_id' => $article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
