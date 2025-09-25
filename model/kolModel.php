<?php
require_once __DIR__ . '/../config/db.php';

class KOLModel
{
    public static function getTopUsersByFollowersAndLikes($limit = 10)
    {
        $db = new connect();
        $limit = max(1, (int)$limit);

        $sql = "
            SELECT 
                u.id            AS user_id,
                u.name,
                u.username,
                u.avatar_url,
                f.followers     AS followers,
                COALESCE(l.likes, 0) AS likes
            FROM (
                SELECT following_id AS user_id, COUNT(*) AS followers
                FROM user_follows
                GROUP BY following_id
            ) AS f
            JOIN users u ON u.id = f.user_id               -- chỉ user có follower
            LEFT JOIN (
                SELECT a.author_id AS user_id, COUNT(al.id) AS likes
                FROM articles a
                LEFT JOIN article_likes al ON al.article_id = a.id
                GROUP BY a.author_id
            ) AS l ON l.user_id = u.id
            ORDER BY f.followers DESC, likes DESC, u.id ASC
            LIMIT $limit
        ";

        $stmt = $db->db->query($sql);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }
}
