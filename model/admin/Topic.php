<?php
class Topic extends BaseModel {
    protected $table = 'topics';
    public function __construct($pdo){ parent::__construct($pdo); }
    public function create($data){
        $stmt = $this->pdo->prepare("INSERT INTO topics (name, slug, description, created_at) VALUES (:name,:slug,:desc,NOW())");
        return $stmt->execute([':name'=>$data['name'], ':slug'=>$data['slug'], ':desc'=>$data['description'] ?? null]);
    }
    public function update($id,$data){
        $fields = ['name','slug','description'];
        $set=[]; $params=[':id'=>$id];
        foreach($fields as $f){ if(isset($data[$f])){ $set[] = "$f = :$f"; $params[":$f"] = $data[$f]; } }
        if(empty($set)) return false;
        $sql = "UPDATE {$this->table} SET ".implode(',',$set)." WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
