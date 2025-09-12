<?php
class TagsModel
{
    // Thêm tag mới
    public static function addTag($name)
    {
        $db = new connect();
        $slug = connect::createSlug($name); // tạo slug tự động

        $sql = "INSERT INTO tags (name, slug) VALUES (:name, :slug)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':slug' => $slug
        ]);
    }

    // Lấy tất cả tags
    public static function getAllTags()
    {
        $db = new connect();
        $sql = "SELECT * FROM tags ORDER BY id DESC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy 1 tag theo id
    public static function getTagById($id)
    {
        $db = new connect();
        $sql = "SELECT * FROM tags WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật tag
    public static function updateTag($id, $name)
    {
        $db = new connect();
        $slug = connect::createSlug($name);

        $sql = "UPDATE tags SET name = :name, slug = :slug WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':slug' => $slug
        ]);
    }

    // Xóa tag
    public static function deleteTag($id)
    {
        $db = new connect();
        $sql = "DELETE FROM tags WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
