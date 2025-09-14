<?php
class Media extends BaseModel {
    protected $table = 'media';
    public function __construct($pdo){ parent::__construct($pdo); }
    public function create($data){
        $stmt = $this->pdo->prepare("INSERT INTO media (filename, url, mime_type, size, created_at) VALUES (:fn,:url,:mime,:size,NOW())");
        return $stmt->execute([':fn'=>$data['filename'],':url'=>$data['url'],':mime'=>$data['mime_type'],':size'=>$data['size']]);
    }
}
