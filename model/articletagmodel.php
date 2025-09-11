<?php
class ArticleTagsModel {
    // Gắn 1 tag vào 1 bài viết
    public static function addTagToArticle($article_id, $tag_id) {
        $db = new connect();
        $sql = "INSERT INTO article_tags (article_id, tag_id) VALUES (:article_id, :tag_id)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':article_id' => $article_id,
            ':tag_id' => $tag_id
        ]);
    }

    // Xóa 1 tag khỏi 1 bài viết
    public static function removeTagFromArticle($article_id, $tag_id) {
        $db = new connect();
        $sql = "DELETE FROM article_tags WHERE article_id = :article_id AND tag_id = :tag_id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':article_id' => $article_id,
            ':tag_id' => $tag_id
        ]);
    }

    // Lấy tất cả tag của 1 bài viết
    public static function getTagsByArticle($article_id) {
        $db = new connect();
        $sql = "SELECT t.* FROM tags t
                INNER JOIN article_tags at ON t.id = at.tag_id
                WHERE at.article_id = :article_id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':article_id' => $article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả bài viết theo tag
    public static function getArticlesByTag($tag_id) {
        $db = new connect();
        $sql = "SELECT a.* FROM articles a
                INNER JOIN article_tags at ON a.id = at.article_id
                WHERE at.tag_id = :tag_id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':tag_id' => $tag_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
