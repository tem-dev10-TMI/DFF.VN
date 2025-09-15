<?php
require_once __DIR__ . '/../config/db.php';

class NewsModel
{
    // Lấy tất cả bài viết (giống Home)
    public static function getAllArticles($limit = 20)
    {
        $db = new connect();
        try {
            $sql = "SELECT a.*, u.name AS author_name, u.avatar_url, t.name AS topic_name
                    FROM articles a
                    LEFT JOIN users u ON a.author_id = u.id
                    LEFT JOIN topics t ON a.topic_id = t.id
                    WHERE a.status = 'public'
                    ORDER BY a.published_at DESC
                    LIMIT :limit";
            $stmt = $db->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("DB error in NewsModel::getAllArticles - " . $e->getMessage());
            return [];
        }
    }

    // Lấy chi tiết 1 bài viết theo ID
    public static function getArticleById($id)
    {
        $db = new connect();
        try {
            $sql = "SELECT a.*, u.name AS author_name, u.avatar_url, t.name AS topic_name
                    FROM articles a
                    LEFT JOIN users u ON a.author_id = u.id
                    LEFT JOIN topics t ON a.topic_id = t.id
                    WHERE a.id = :id AND a.status = 'public'
                    LIMIT 1";
            $stmt = $db->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("DB error in NewsModel::getArticleById - " . $e->getMessage());
            return null;
        }
    }
    public static function getLatestArticles($limit = 6)
{
    $db = new connect();
    $sql = "SELECT a.*, 
                   u.name AS author_name, 
                   u.avatar_url, 
                   t.name AS topic_name
            FROM articles a
            LEFT JOIN users u ON a.author_id = u.id
            LEFT JOIN topics t ON a.topic_id = t.id
            WHERE a.status = 'public'
            ORDER BY a.created_at DESC, a.id DESC
            LIMIT :limit";

    $stmt = $db->db->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
