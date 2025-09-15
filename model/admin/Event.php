<?php
class Event extends BaseModel {
    protected $table = 'events';
    public function __construct($pdo){ parent::__construct($pdo); }
    public function create($data){
        $stmt = $this->pdo->prepare("INSERT INTO events (title, description, start_date, end_date, created_at) VALUES (:title,:desc,:start,:end,NOW())");
        return $stmt->execute([':title'=>$data['title'],':desc'=>$data['description'] ?? null,':start'=>$data['start_date'] ?? null,':end'=>$data['end_date'] ?? null]);
    }
    public function update($id,$data){
        $fields=['title','description','start_date','end_date'];
        $set=[]; $params=[':id'=>$id];
        foreach($fields as $f){ if(isset($data[$f])){ $set[] = "$f = :$f"; $params[":$f"] = $data[$f]; } }
        if(empty($set)) return false;
        $sql = "UPDATE {$this->table} SET ".implode(',',$set)." WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
