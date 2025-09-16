<?php
class EventModel {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function all($limit = 100) {
        $stmt = $this->pdo->prepare("SELECT * FROM events ORDER BY event_date DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
