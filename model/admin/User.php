<?php
class User extends BaseModel
{
    protected $table = 'users';
    public function __construct($pdo)
    {
        parent::__construct($pdo);
    }

    /* ====== CREATE ====== */
    public function create($data)
    {
        // check trùng email
        $st = $this->pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $st->execute([':email' => $data['email']]);
        if ($st->fetch())
            throw new Exception("Email đã tồn tại, vui lòng chọn email khác");

        // check trùng username
        $st = $this->pdo->prepare("SELECT id FROM users WHERE username = :u LIMIT 1");
        $st->execute([':u' => $data['username']]);
        if ($st->fetch())
            throw new Exception("Username đã tồn tại, vui lòng chọn username khác");

        $st = $this->pdo->prepare(
            "INSERT INTO users
           (name, username, email, password_hash, role, phone, avatar_url, created_at)
           VALUES (:name, :username, :email, :pwd, :role, :phone, :avatar, NOW())"
        );

        return $st->execute([
            ':name' => $data['name'],
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':pwd' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role' => $data['role'] ?? 'user',
            ':phone' => $data['phone'] ?? null,
            ':avatar' => $data['avatar_url'] ?? null
        ]);
    }

    /* ====== UPDATE ====== */
    public function update($id, $data)
    {
        // nếu đổi email -> kiểm tra trùng
        if (!empty($data['email'])) {
            $st = $this->pdo->prepare("SELECT id FROM users WHERE email = :email AND id <> :id LIMIT 1");
            $st->execute([':email' => $data['email'], ':id' => $id]);
            if ($st->fetch())
                throw new Exception("Email đã tồn tại, vui lòng chọn email khác");
        }
        // nếu đổi username -> kiểm tra trùng
        if (!empty($data['username'])) {
            $st = $this->pdo->prepare("SELECT id FROM users WHERE username = :u AND id <> :id LIMIT 1");
            $st->execute([':u' => $data['username'], ':id' => $id]);
            if ($st->fetch())
                throw new Exception("Username đã tồn tại, vui lòng chọn username khác");
        }

        $fields = ['name', 'username', 'email', 'role', 'phone', 'avatar_url', 'description'];
        $set = [];
        $params = [':id' => $id];
        foreach ($fields as $f) {
            if (array_key_exists($f, $data)) {
                $set[] = "$f = :$f";
                $params[":$f"] = $data[$f];
            }
        }
        if (!empty($data['password'])) {
            $set[] = "password_hash = :pwd";
            $params[':pwd'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        if (empty($set))
            return false;

        $sql = "UPDATE {$this->table} SET " . implode(',', $set) . " WHERE id = :id";
        $st = $this->pdo->prepare($sql);
        return $st->execute($params);
    }

    /* ====== SEARCH + PAGINATION ====== */
    public function search(array $filters, int $limit = 10, int $offset = 0): array
    {
        [$where, $params] = $this->buildWhere($filters);
        $sql = "SELECT id, name, username, email, role, phone, avatar_url, created_at
                FROM {$this->table}
                $where
                ORDER BY created_at DESC
                LIMIT :lim OFFSET :off";
        $st = $this->pdo->prepare($sql);
        foreach ($params as $k => $v)
            $st->bindValue($k, $v);
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->bindValue(':off', $offset, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(array $filters): int
    {
        [$where, $params] = $this->buildWhere($filters);
        $st = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->table} $where");
        foreach ($params as $k => $v)
            $st->bindValue($k, $v);
        $st->execute();
        return (int) $st->fetchColumn();
    }

    private function buildWhere(array $filters): array
    {
        $w = [];
        $p = [];

        if (!empty($filters['name'])) {
            $w[] = "name LIKE :name";
            $p[':name'] = '%' . $filters['name'] . '%';
        }
        if (!empty($filters['email'])) {
            $w[] = "email LIKE :email";
            $p[':email'] = '%' . $filters['email'] . '%';
        }
        if (!empty($filters['role']) && in_array($filters['role'], ['user', 'businessmen', 'admin'], true)) {
            $w[] = "role = :role";
            $p[':role'] = $filters['role'];
        }

        $where = $w ? 'WHERE ' . implode(' AND ', $w) : '';
        return [$where, $p];
    }
}
