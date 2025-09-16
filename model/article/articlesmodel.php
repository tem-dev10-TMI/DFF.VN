<?php
class ArticlesModel
{

    // Thêm bài viết mới -> mặc định pending

    public static function addArticle($title, $summary, $content, $main_image_url, $author_id, $topic_id, $status = 'pending', $is_hot = 0, $is_analysis = 0)
    {
        $db = new connect();
        $slug = connect::createSlug($title);

        $sql = "INSERT INTO articles 
                (title, slug, summary, content, main_image_url, author_id, topic_id, status, published_at, is_hot, is_analysis) 
                VALUES 
                (:title, :slug, :summary, :content, :main_image_url, :author_id, :topic_id, :status, NOW(), :is_hot, :is_analysis)";
        $stmt = $db->db->prepare($sql);

        return $stmt->execute([
            ':title' => $title,
            ':slug' => $slug,
            ':summary' => $summary,
            ':content' => $content,
            ':main_image_url' => $main_image_url,
            ':author_id' => $author_id,
            ':topic_id' => $topic_id,
            ':status' => $status, // mặc định pending
            ':is_hot' => $is_hot,
            ':is_analysis' => $is_analysis
        ]);
    }

    // Lấy tất cả bài viết (chỉ public)
    public static function getAllArticles()
    {
        $db = new connect();
        $sql = "SELECT a.*, u.name AS author_name, u.avatar_url, t.name AS topic_name
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN topics t ON a.topic_id = t.id
                WHERE a.status = 'public'
                ORDER BY a.created_at DESC, a.id DESC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy bài viết theo ID (chỉ public)
    public static function getArticleById($id)
    {
        $db = new connect();
        $sql = "SELECT a.*, u.name AS author_name, u.avatar_url, t.name AS topic_name
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN topics t ON a.topic_id = t.id
                WHERE a.id = :id AND a.status = 'public'";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByUserId($userId)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE author_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }   
    
    // Cập nhật bài viết
    public static function updateArticle($id, $title, $summary, $content, $main_image_url, $topic_id, $status = 'public', $is_hot = 0, $is_analysis = 0)
    {
        $db = new connect();
        $slug = connect::createSlug($title);

        $sql = "UPDATE articles 
                SET title = :title, slug = :slug, summary = :summary, content = :content, 
                    main_image_url = :main_image_url, topic_id = :topic_id, status = :status, 
                    is_hot = :is_hot, is_analysis = :is_analysis, updated_at = NOW()
                WHERE id = :id";
        $stmt = $db->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':slug' => $slug,
            ':summary' => $summary,
            ':content' => $content,
            ':main_image_url' => $main_image_url,
            ':topic_id' => $topic_id,
            ':status' => $status,
            ':is_hot' => $is_hot,
            ':is_analysis' => $is_analysis
        ]);
    }

    // Xóa bài viết
    public static function deleteArticle($id)
    {
        $db = new connect();
        $sql = "DELETE FROM articles WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Tăng view count
    public static function increaseView($id)
    {
        $db = new connect();
        $sql = "UPDATE articles SET view_count = view_count + 1 WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Tăng comment count
    public static function increaseCommentCount($id)
    {
        $db = new connect();
        $sql = "UPDATE articles SET comment_count = comment_count + 1 WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Lấy bài viết theo topic (chỉ public)
    public static function getArticlesByTopicId($topic_id, $limit = 10)
    {
        $db = new connect();
        $sql = "SELECT a.*, u.name AS author_name, u.avatar_url
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                WHERE a.topic_id = :topic_id AND a.status = 'public'
                ORDER BY a.created_at DESC, a.id DESC
                LIMIT :limit";

        $stmt = $db->db->prepare($sql);
        $stmt->bindValue(':topic_id', $topic_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy bài viết theo author (bao gồm mọi trạng thái)
    public static function getArticlesByAuthorId($author_id)
    {
        $db = new connect();
        $sql = "SELECT a.*, u.name AS author_name, u.avatar_url
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                WHERE a.author_id = :author_id
                ORDER BY a.created_at DESC, a.id DESC";

        $stmt = $db->db->prepare($sql);
        $stmt->bindValue(':author_id', $author_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy lý do review mới nhất cho bài viết
    public static function getLatestReviewReasonByArticleId($article_id)
    {
        $db = new connect();
        $sql = "SELECT reason
                FROM article_reviews
                WHERE article_id = :article_id
                ORDER BY id DESC
                LIMIT 1";
        $stmt = $db->db->prepare($sql);
        $stmt->bindValue(':article_id', $article_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? ($row['reason'] ?? null) : null;
    }
    public static function getLatestArticles($limit = 6)
{
    $db = new connect();
    $sql = "SELECT a.*, 
                   u.name AS author_name, 
                   u.avatar_url, 
                   t.name AS topic_name
            FROM articles a
            LEFT JOIN users u ON a.author_id = u.id
            LEFT JOIN topics t ON a.topic_id = t.id
            WHERE a.status = 'public'
            ORDER BY a.created_at DESC, a.id DESC
            LIMIT :limit";

    $stmt = $db->db->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public static function searchArticles($q)
    {
        $db = new connect();
        $sql = "SELECT a.*, u.name AS author_name
                FROM articles a
                LEFT JOIN users u ON a.author_id = u.id
                WHERE a.title LIKE :q OR a.content LIKE :q
                ORDER BY a.created_at DESC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':q' => "%$q%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}