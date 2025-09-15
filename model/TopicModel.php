<?php
require_once __DIR__ . '/../config/db.php';

class TopicModel
{
    private $db;

    public function __construct()
    {
        $this->db = new connect(); // tạo instance connect
    }

    // Lấy tất cả chủ đề
    public function getAll()
    {
        try {
            $sql = "SELECT * FROM topics ORDER BY display_order ASC, created_at DESC";
            $stmt = $this->db->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("DB error in TopicModel::getAll - " . $e->getMessage());
            return [];
        }
    }

    // Lấy chủ đề theo ID
    public function getById($id)
    {
        try {
            $sql = "SELECT * FROM topics WHERE id = :id";
            $stmt = $this->db->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("DB error in TopicModel::getById - " . $e->getMessage());
            return null;
        }
    }

    // Lấy chủ đề giới hạn số lượng (ví dụ sidebar)
    public function getTop($limit = 5)
    {
        try {
            $sql = "SELECT * FROM topics ORDER BY display_order ASC, created_at DESC LIMIT :limit";
            $stmt = $this->db->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("DB error in TopicModel::getTop - " . $e->getMessage());
            return [];
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

    // Lấy ID chủ đề theo tên (khớp tương đối, không phân biệt hoa thường)
    public function getIdByName($name)
    {
        try {
            $sql = "SELECT id FROM topics WHERE LOWER(name) LIKE LOWER(:name) ORDER BY display_order ASC, id ASC LIMIT 1";
            $stmt = $this->db->db->prepare($sql);
            $stmt->bindValue(':name', '%' . $name . '%', PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (int)$row['id'] : null;
        } catch (PDOException $e) {
            error_log("DB error in TopicModel::getIdByName - " . $e->getMessage());
            return null;
        }
    }
}
