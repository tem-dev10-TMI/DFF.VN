<?php
class User extends BaseModel {
    protected $table = 'users';
    public function __construct($pdo){ parent::__construct($pdo); }
    public function create($data){
    // check trùng email
    $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $data['email']]);
    if ($stmt->fetch()) {
        throw new Exception("Email đã tồn tại, vui lòng chọn email khác");
    }

    $stmt = $this->pdo->prepare("INSERT INTO users 
        (name, username, email, password_hash, role, phone, avatar_url, created_at) 
        VALUES (:name, :username, :email, :pwd, :role, :phone, :avatar, NOW())");

    return $stmt->execute([
        ':name'=>$data['name'],
        ':username'=>$data['username'],
        ':email'=>$data['email'],
        ':pwd'=>password_hash($data['password'], PASSWORD_DEFAULT),
        ':role'=>$data['role'] ?? 'user',
        ':phone'=>$data['phone'] ?? null,
        ':avatar'=>$data['avatar_url'] ?? null
    ]);
}
    public function update($id,$data){
        $fields = ['name','username','email','role','phone','avatar_url','description'];
        $set = []; $params = [':id'=>$id];
        foreach($fields as $f){ if(isset($data[$f])){ $set[] = "$f = :$f"; $params[":$f"] = $data[$f]; } }
        if(!empty($data['password'])){ $set[] = "password_hash = :pwd"; $params[':pwd'] = password_hash($data['password'], PASSWORD_DEFAULT); }
        if(empty($set)) return false;
        $sql = "UPDATE {$this->table} SET ".implode(',', $set)." WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
