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
}
