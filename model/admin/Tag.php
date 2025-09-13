<?php
class Tag extends BaseModel {
    protected $table = 'tags';
    public function __construct($pdo){ parent::__construct($pdo); }
    public function create($data){
        $stmt = $this->pdo->prepare("
    INSERT INTO tags (name) 
    VALUES (:name)
");
$stmt->execute([':name' => $data['name']]);
    }
    public function update($id,$data){
        $fields=['name','slug'];
        $set=[]; $params=[':id'=>$id];
        foreach($fields as $f){ if(isset($data[$f])){ $set[] = "$f = :$f"; $params[":$f"] = $data[$f]; } }
        if(empty($set)) return false;
        $sql = "UPDATE {$this->table} SET ".implode(',',$set)." WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
