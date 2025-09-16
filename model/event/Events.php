<?php
require_once __DIR__ . '/../../config/db.php';

class Events {
    private $db;

    public function __construct() {
        global $conn;
        $this->db = $conn;
    }

    // Thêm sự kiện mới
    public function add($title, $event_url, $event_date, $stock_ticker, $description) {
        $sql = "INSERT INTO events (title, event_url, event_date, stock_ticker, description) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$title, $event_url, $event_date, $stock_ticker, $description]);
    }

    // Sửa sự kiện theo id
    public function update($id, $title, $event_url, $event_date, $stock_ticker, $description) {
        $sql = "UPDATE events 
                SET title = ?, event_url = ?, event_date = ?, stock_ticker = ?, description = ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$title, $event_url, $event_date, $stock_ticker, $description, $id]);
    }

    // Xóa sự kiện theo id
    public function remove($id) {
        $sql = "DELETE FROM events WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Lấy tất cả sự kiện
    public function getAll() {
        $sql = "SELECT * FROM events ORDER BY event_date DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Lấy sự kiện theo id
    public function getById($id) {
        $sql = "SELECT * FROM events WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
