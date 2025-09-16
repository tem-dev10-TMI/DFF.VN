<?php
class AdminEventModel {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($data) {
        $sql = "INSERT INTO events (title, event_url, event_date, stock_ticker, description, created_at) 
                VALUES (:title, :event_url, :event_date, :stock_ticker, :description, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title'       => $data['title'] ?? '',
            ':event_url'   => $data['event_url'] ?? '',
            ':event_date'  => $data['event_date'] ?? null,
            ':stock_ticker'=> $data['stock_ticker'] ?? '',
            ':description' => $data['description'] ?? ''
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE events SET 
                    title=:title, event_url=:event_url, event_date=:event_date,
                    stock_ticker=:stock_ticker, description=:description
                WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':title'       => $data['title'] ?? '',
            ':event_url'   => $data['event_url'] ?? '',
            ':event_date'  => $data['event_date'] ?? null,
            ':stock_ticker'=> $data['stock_ticker'] ?? '',
            ':description' => $data['description'] ?? ''
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM events WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
