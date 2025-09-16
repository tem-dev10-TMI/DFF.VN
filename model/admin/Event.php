<?php
class Event {
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

    // Tìm 1 sự kiện theo ID
    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mới
    public function create($data) {
        $sql = "INSERT INTO events (title, event_url, event_date, stock_ticker, description, created_at) 
                VALUES (:title, :event_url, :event_date, :stock_ticker, :description, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title'        => $data['title'] ?? '',
            ':event_url'    => $data['event_url'] ?? '',
            ':event_date'   => $data['event_date'] ?? null,
            ':stock_ticker' => $data['stock_ticker'] ?? '',
            ':description'  => $data['description'] ?? ''
        ]);
    }

    // Cập nhật
    public function update($id, $data) {
        $sql = "UPDATE events 
                SET title=:title, event_url=:event_url, event_date=:event_date, 
                    stock_ticker=:stock_ticker, description=:description 
                WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'           => $id,
            ':title'        => $data['title'] ?? '',
            ':event_url'    => $data['event_url'] ?? '',
            ':event_date'   => $data['event_date'] ?? null,
            ':stock_ticker' => $data['stock_ticker'] ?? '',
            ':description'  => $data['description'] ?? ''
        ]);
    }

    // Xóa
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM events WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
