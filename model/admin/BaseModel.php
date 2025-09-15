<?php
class BaseModel {
    protected $pdo;
    protected $table;
    public function __construct($pdo){ $this->pdo = $pdo; }
   public function all($limit=50, $offset=0) {
    // Kiểm tra bảng có cột created_at không
    $columns = $this->pdo->query("SHOW COLUMNS FROM {$this->table}")->fetchAll(PDO::FETCH_COLUMN);
    $orderCol = in_array('created_at', $columns) ? 'created_at' : 'id';

    $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} ORDER BY {$orderCol} DESC LIMIT :l OFFSET :o");
    $stmt->bindValue(':l',(int)$limit,PDO::PARAM_INT);
    $stmt->bindValue(':o',(int)$offset,PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

    public function find($id){
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch();
    }
    public function delete($id){
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id'=>$id]);
    }
}
