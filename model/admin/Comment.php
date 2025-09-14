<?php
class Comment extends BaseModel {
    protected $table = 'comments';
    public function __construct($pdo){ parent::__construct($pdo); }
    public function approve($id){ $stmt = $this->pdo->prepare("UPDATE {$this->table} SET approved = 1 WHERE id = :id"); return $stmt->execute([':id'=>$id]); }
}
