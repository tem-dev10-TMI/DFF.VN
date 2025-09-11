<?php
class MediaModel {
    public static function addMedia($article_id, $media_url, $media_type, $caption = null)
    {
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
}
