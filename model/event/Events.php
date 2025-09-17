<?php
class EventModels {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lấy danh sách sự kiện
    public function all($limit = 100) {
        $stmt = $this->pdo->prepare("SELECT * FROM events ORDER BY event_date DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết sự kiện theo ID
    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm sự kiện mới
    public function create($data) {
        $sql = "INSERT INTO events (title, description, event_date) 
                VALUES (:title, :description, :event_date)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':event_date' => $data['event_date']
        ]);
    }

    // Cập nhật sự kiện
    public function update($id, $data) {
        $sql = "UPDATE events 
                SET title = :title, description = :description, event_date = :event_date 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':event_date' => $data['event_date']
        ]);
    }

    // Xóa sự kiện
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM events WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
