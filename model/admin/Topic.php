<?php
class Topic extends BaseModel {
    protected $table = 'topics';
    public function __construct($pdo){ parent::__construct($pdo); }
    public function create($data){
        $stmt = $this->pdo->prepare("INSERT INTO topics (name, slug, description, created_at) VALUES (:name,:slug,:desc,NOW())");
        return $stmt->execute([':name'=>$data['name'], ':slug'=>$data['slug'], ':desc'=>$data['description'] ?? null]);
    }
   public function update($id, $data) {
    $allowed = ['name','slug','description','icon_url'];
    $sets = [];
    $params = [':id' => $id];
    foreach ($allowed as $col) {
        if (array_key_exists($col, $data)) {
            $sets[] = "$col = :$col";
            $params[":$col"] = $data[$col];
        }
    }
    if (!$sets) return true; // không có gì để update
    $sql = "UPDATE topics SET " . implode(', ', $sets) . " WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($params);
}

}
