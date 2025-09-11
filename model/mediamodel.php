<?php
class MediaModel {
    // Thêm media cho 1 bài viết
    public static function addMedia($article_id, $media_url, $media_type, $caption = null) {
        $db = new connect();
        $sql = "INSERT INTO media (article_id, media_url, media_type, caption) 
                VALUES (:article_id, :media_url, :media_type, :caption)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':article_id' => $article_id,
            ':media_url' => $media_url,
            ':media_type' => $media_type,
            ':caption' => $caption
        ]);
    }

    // Lấy danh sách media của 1 bài viết
    public static function getMediaByArticle($article_id) {
        $db = new connect();
        $sql = "SELECT * FROM media WHERE article_id = :article_id ORDER BY created_at ASC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':article_id' => $article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Xóa 1 media
    public static function deleteMedia($id) {
        $db = new connect();
        $sql = "DELETE FROM media WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Cập nhật caption media
    public static function updateCaption($id, $caption) {
        $db = new connect();
        $sql = "UPDATE media SET caption = :caption WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':caption' => $caption
        ]);
    }
}
