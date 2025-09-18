<?php
class TopicsModel
{
    // ===== CRUD =====

    // Lấy tất cả topics
    public function all($limit = 200)
    {
        $db = new connect();
        $sql = "SELECT * FROM topics ORDER BY display_order ASC, created_at DESC LIMIT :lim";
        $stmt = $db->db->prepare($sql);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllTopics()
    {
        $db = new connect();
        $sql = "SELECT * FROM topics ORDER BY display_order ASC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm topic mới
    public function create($data)
    {
        $db = new connect();
        $slug = connect::createSlug($data['name']);

        $sql = "INSERT INTO topics (name, slug, icon_url, description, display_order) 
                VALUES (:name, :slug, :icon_url, :description, :display_order)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':slug' => $slug,
            ':icon_url' => $data['icon_url'] ?? null,
            ':description' => $data['description'] ?? null,
            ':display_order' => $data['display_order'] ?? 0
        ]);
    }

    // Lấy 1 topic theo id
    public function find($id)
    {
        $db = new connect();
        $sql = "SELECT * FROM topics WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật topic
    public function update($id, $data)
    {
        $db = new connect();
        $slug = connect::createSlug($data['name']);

        $sql = "UPDATE topics 
                SET name = :name, slug = :slug, icon_url = :icon_url, description = :description, display_order = :display_order
                WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':slug' => $slug,
            ':icon_url' => $data['icon_url'] ?? null,
            ':description' => $data['description'] ?? null,
            ':display_order' => $data['display_order'] ?? 0
        ]);
    }

    // Xóa topic
    public function delete($id)
    {
        $db = new connect();
        $sql = "DELETE FROM topics WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // ===== Các tiện ích bổ sung =====

    // Lấy topic theo slug (dùng cho frontend URL)
    public function findBySlug($slug)
    {
        $db = new connect();
        $sql = "SELECT * FROM topics WHERE slug = :slug";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Đếm tổng số topic
    public function countTopics()
    {
        $db = new connect();
        $sql = "SELECT COUNT(*) as total FROM topics";
        $stmt = $db->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    // Tìm kiếm topic theo từ khóa
    public function searchTopics($keyword, $limit = 50)
    {
        $db = new connect();
        $sql = "SELECT * FROM topics 
                WHERE name LIKE :kw OR description LIKE :kw 
                ORDER BY created_at DESC 
                LIMIT :lim";
        $stmt = $db->db->prepare($sql);
        $stmt->bindValue(':kw', '%' . $keyword . '%', PDO::PARAM_STR);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
