<?php
// model/admin/Topic.php
class Topic extends BaseModel
{
    protected $table = 'topics';
    public function __construct($pdo)
    {
        parent::__construct($pdo);
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO {$this->table}
                (name, slug, description, icon_url, created_at)
                VALUES (:name, :slug, :description, :icon_url, NOW())";
        $st = $this->pdo->prepare($sql);
        return $st->execute([
            ':name' => $data['name'],
            ':slug' => $data['slug'],
            ':description' => $data['description'] ?? null,
            ':icon_url' => $data['icon_url'] ?? null,
        ]);
    }

    public function update(int $id, array $data)
    {
        $allowed = ['name', 'slug', 'description', 'icon_url'];
        $set = [];
        $params = [':id' => $id];
        foreach ($allowed as $col) {
            if (array_key_exists($col, $data)) {
                $set[] = "$col = :$col";
                $params[":$col"] = $data[$col] !== '' ? $data[$col] : null;
            }
        }
        if (!$set)
            return true;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE id = :id";
        $st = $this->pdo->prepare($sql);
        return $st->execute($params);
    }

    public function listPaged(int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT id, name, slug, icon_url FROM {$this->table}
                ORDER BY display_order ASC, id DESC
                LIMIT :lim OFFSET :off";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->bindValue(':off', $offset, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }
}
