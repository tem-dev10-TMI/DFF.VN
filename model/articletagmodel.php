<?php
class ArticleTagModel {
    public static function addArticleTag($article_id, $tag_id)
    {
        $db = new connect();

        $sql = "INSERT INTO article_tags (article_id, tag_id) 
                VALUES (:article_id, :tag_id)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':article_id' => $article_id,
            ':tag_id' => $tag_id
        ]);
    }
}
