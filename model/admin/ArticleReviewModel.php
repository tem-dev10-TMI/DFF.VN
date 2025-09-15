<?php
class ArticleReviewModel {

    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /* ===========================
       STATIC METHODS (dùng connect)
       =========================== */
    
    // Thêm lịch sử duyệt (static)
    public static function addReviewStatic($article_id, $admin_id, $action, $reason = null) {
        $db = new connect();
        $sql = "INSERT INTO article_reviews (article_id, admin_id, action, reason, reviewed_at)
                VALUES (:article_id, :admin_id, :action, :reason, NOW())";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':article_id' => $article_id,
            ':admin_id'   => $admin_id,
            ':action'     => $action,
            ':reason'     => $reason
        ]);
    }

    // Lấy lịch sử duyệt theo 1 bài viết (static)
    public static function getReviewsByArticleStatic($article_id) {
        $db = new connect();
        $sql = "SELECT ar.*, u.name as admin_name
                FROM article_reviews ar
                JOIN users u ON ar.admin_id = u.id
                WHERE ar.article_id = :article_id
                ORDER BY ar.reviewed_at DESC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':article_id' => $article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===========================
       INSTANCE METHODS (dùng $this->pdo)
       =========================== */

    // Thêm lịch sử duyệt
    public function addReview($article_id, $admin_id, $action, $reason = null) {
        $stmt = $this->pdo->prepare("
            INSERT INTO article_reviews (article_id, admin_id, action, reason, reviewed_at)
            VALUES (:article_id, :admin_id, :action, :reason, NOW())
        ");
        return $stmt->execute([
            ':article_id' => $article_id,
            ':admin_id'   => $admin_id,
            ':action'     => $action,
            ':reason'     => $reason
        ]);
    }

    // Lấy lịch sử duyệt theo 1 bài viết
    public function getReviewsByArticle($article_id) {
        $stmt = $this->pdo->prepare("
            SELECT ar.*, u.name as admin_name
            FROM article_reviews ar
            JOIN users u ON ar.admin_id = u.id
            WHERE ar.article_id = :article_id
            ORDER BY ar.reviewed_at DESC
        ");
        $stmt->execute([':article_id' => $article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả bài viết đã duyệt hoặc từ chối
    public function getReviewedArticles() {
        $stmt = $this->pdo->query("
            SELECT a.id, a.title, a.status, a.published_at, 
                   u.name AS author_name, 
                   admin.name AS admin_name,
                   r.action, r.reviewed_at, r.reason
            FROM article_reviews r
            JOIN articles a ON r.article_id = a.id
            JOIN users u ON a.author_id = u.id
            JOIN users admin ON r.admin_id = admin.id
            ORDER BY r.reviewed_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
