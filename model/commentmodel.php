<?php
class CommentModel {
    public static function addComment($article_id, $user_id, $content, $parent_id = null)
    {
        $db = new connect();

        $sql = "INSERT INTO comments (article_id, user_id, parent_id, content) 
                VALUES (:article_id, :user_id, :parent_id, :content)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':article_id' => $article_id,
            ':user_id' => $user_id,
            ':parent_id' => $parent_id,
            ':content' => $content
        ]);
    }
}
