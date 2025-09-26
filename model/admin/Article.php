<?php
class Article extends BaseModel
{
    protected $table = 'articles';
    public function __construct($pdo)
    {
        parent::__construct($pdo);
    }
    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO articles (title, slug, content, main_image_url, topic_id, author_id, status, created_at) VALUES (:title,:slug,:content,:img,:topic,:author,:status,NOW())");
        return $stmt->execute([
            ':title' => $data['title'] ?? null,
            ':slug' => $data['slug'] ?? null,
            ':content' => $data['content'] ?? null,
            ':img' => $data['main_image_url'] ?? null,
            ':topic' => $data['topic_id'] ?? null,
            ':author' => $data['author_id'] ?? null,
            ':status' => $data['status'] ?? 'draft'
        ]);
    }
    public function update($id, $data)
    {
        $fields = ['title', 'slug', 'content', 'main_image_url', 'topic_id', 'status', 'author_id'];
        $set = [];
        $params = [':id' => $id];
        foreach ($fields as $f) {
            if (isset($data[$f])) {
                $set[] = "$f = :$f";
                $params[":$f"] = $data[$f];
            }
        }
        if (empty($set))
            return false;
        $sql = "UPDATE {$this->table} SET " . implode(',', $set) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    public function listWithJoin($limit = 100)
    {
        $sql = "SELECT a.*, u.name AS author_name, t.name AS topic_name FROM articles a LEFT JOIN users u ON a.author_id = u.id LEFT JOIN topics t ON a.topic_id = t.id ORDER BY a.created_at DESC LIMIT :l";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':l', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
