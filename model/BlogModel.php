<?php
require_once __DIR__ . '/../config/db.php';

class BlogModel {
    private $pdo;

    public function __construct() {
        $db = new connect();
        $this->pdo = $db->db;
    }

    // Lấy bài viết theo id
    public function getById($id) {
        $sql = "SELECT a.*, u.name as author_name, u.avatar_url 
                FROM articles a
                JOIN users u ON a.author_id = u.id
                WHERE a.id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy bài viết theo slug
    public function getBySlug($slug) {
        $sql = "SELECT a.*, u.username as author_name, u.avatar_url 
                FROM articles a
                JOIN users u ON a.author_id = u.id
                WHERE a.slug = :slug LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy các bài viết liên quan (cùng topic)
    public function getRelated($topic_id, $exceptId, $limit = 5) {
        $sql = "SELECT id, title, slug 
                FROM articles 
                WHERE topic_id = :topic_id AND id != :id 
                ORDER BY created_at DESC 
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':topic_id', $topic_id, PDO::PARAM_INT);
        $stmt->bindValue(':id', $exceptId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
