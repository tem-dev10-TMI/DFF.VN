<?php
class ArticleSavesModel
{

    /**
     * Lưu (bookmark) một bài viết cho người dùng
     * Trả về true/false tùy theo thao tác thành công hay không.
     */
    public static function getArticlesByUserId($userId)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM articles WHERE author_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addSave($article_id, $user_id)
    {
        $db = new connect();
        $sql = "INSERT INTO article_saves (article_id, user_id)
                VALUES (:article_id, :user_id)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':article_id' => $article_id,
            ':user_id'    => $user_id
        ]);
    }

    /**
     * Hủy lưu (gỡ bookmark) một bài viết
     */
    public static function removeSave($article_id, $user_id)
    {
        $db = new connect();
        $sql = "DELETE FROM article_saves
                WHERE article_id = :article_id AND user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':article_id' => $article_id,
            ':user_id'    => $user_id
        ]);
    }

    /**
     * Kiểm tra người dùng đã lưu bài viết này chưa
     * Trả về true nếu đã lưu, false nếu chưa.
     */
    public static function hasSaved($article_id, $user_id)
    {
        $db = new connect();
        $sql = "SELECT 1 FROM article_saves
                WHERE article_id = :article_id AND user_id = :user_id
                LIMIT 1";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([
            ':article_id' => $article_id,
            ':user_id'    => $user_id
        ]);
        return $stmt->fetchColumn() !== false;
    }

    /**
     * Đếm tổng số lần lưu (bookmark) của một bài viết
     */
    public static function countSaves($article_id)
    {
        $db = new connect();
        $sql = "SELECT COUNT(*) FROM article_saves
                WHERE article_id = :article_id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':article_id' => $article_id]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Lấy danh sách tất cả user đã lưu (bookmark) một bài viết
     */
    public static function getUsersSavedArticle($article_id)
    {
        $db = new connect();
        $sql = "SELECT u.* FROM users u
                INNER JOIN article_saves s ON u.id = s.user_id
                WHERE s.article_id = :article_id
                ORDER BY s.created_at DESC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':article_id' => $article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy toàn bộ danh sách bài viết mà 1 user đã lưu
     */
    public static function getArticlesSavedByUser($user_id)
    {
        $db = new connect();
        $sql = "SELECT a.* FROM articles a
                INNER JOIN article_saves s ON a.id = s.article_id
                WHERE s.user_id = :user_id
                ORDER BY s.created_at DESC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /********* PHẦN MỞ RỘNG (Object-Oriented) *********/

    private $db;

    public function __construct()
    {
        $this->db = (new connect())->db; // lấy trực tiếp PDO object
    }

    // Kiểm tra bài viết đã được lưu bởi user chưa
    public function isSaved($article_id, $user_id)
    {
        $stmt = $this->db->prepare("SELECT id FROM article_saves WHERE article_id = :article_id AND user_id = :user_id");
        $stmt->execute(['article_id' => $article_id, 'user_id' => $user_id]);
        return $stmt->rowCount() > 0;
    }
    public function isSavedBySlug($slug, $user_id)
{
    $sql = "SELECT s.id 
            FROM article_saves s
            INNER JOIN articles a ON s.article_id = a.id
            WHERE a.slug = :slug AND s.user_id = :user_id
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':slug'    => $slug,
        ':user_id' => $user_id
    ]);

    return $stmt->fetchColumn() !== false;
}


    // Lưu bài viết
    public function save($article_id, $user_id)
    {
        if ($this->isSaved($article_id, $user_id)) return false;
        $stmt = $this->db->prepare("INSERT INTO article_saves (article_id, user_id) VALUES (:article_id, :user_id)");
        return $stmt->execute(['article_id' => $article_id, 'user_id' => $user_id]);
    }

    // Hủy lưu bài viết
    public function unsave($article_id, $user_id)
    {
        $stmt = $this->db->prepare("DELETE FROM article_saves WHERE article_id = :article_id AND user_id = :user_id");
        return $stmt->execute(['article_id' => $article_id, 'user_id' => $user_id]);
    }

    // Lấy danh sách bài viết đã lưu của user
    public function getSavedArticles($user_id)
    {
        $stmt = $this->db->prepare("SELECT a.* FROM articles a
                                INNER JOIN article_saves s ON a.id = s.article_id
                                WHERE s.user_id = :user_id
                                ORDER BY s.created_at DESC");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Toggle lưu/huỷ bài viết
    public function toggleSave($article_id, $user_id)
    {
        if ($this->isSaved($article_id, $user_id)) {
            $this->unsave($article_id, $user_id);
            return 'unsaved'; // trả về trạng thái bỏ lưu
        } else {
            $this->save($article_id, $user_id);
            return 'saved'; // trả về trạng thái đã lưu
        }
    }
}
