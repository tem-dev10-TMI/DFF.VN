<?php
class TopicsModel
{
    // Thêm topic mới
    public static function addTopic($name, $icon_url = null, $description = null, $display_order = 0)
    {
        $db = new connect();
        $slug = connect::createSlug($name); // tự tạo slug từ name

        $sql = "INSERT INTO topics (name, slug, icon_url, description, display_order) 
                VALUES (:name, :slug, :icon_url, :description, :display_order)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':slug' => $slug,
            ':icon_url' => $icon_url,
            ':description' => $description,
            ':display_order' => $display_order
        ]);
    }

    // Lấy tất cả topics
    public static function getAllTopics()
    {
        $db = new connect();
        $sql = "SELECT * FROM topics ORDER BY display_order ASC, created_at DESC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy 1 topic theo id
    public static function getTopicById($id)
    {
        $db = new connect();
        $sql = "SELECT * FROM topics WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật topic (slug cũng tự cập nhật lại nếu đổi name)
    public static function updateTopic($id, $name, $icon_url, $description, $display_order)
    {
        $db = new connect();
        $slug = connect::createSlug($name); // cập nhật slug theo name mới

        $sql = "UPDATE topics 
                SET name = :name, slug = :slug, icon_url = :icon_url, description = :description, display_order = :display_order
                WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':slug' => $slug,
            ':icon_url' => $icon_url,
            ':description' => $description,
            ':display_order' => $display_order
        ]);
    }

    // Xóa topic
    public static function deleteTopic($id)
    {
        $db = new connect();
        $sql = "DELETE FROM topics WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
