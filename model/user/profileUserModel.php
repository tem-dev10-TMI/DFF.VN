<?php
class profileUserModel
{
    // =============== Lấy thông tin profileUser theo user_id ===============
    public static function getProfileUserByUserId($user_id)
    {
        $db = new connect();

        $sql = "
        SELECT 
            u.id AS user_id,
            u.name,
            u.username,
            u.email,
            u.phone,
            u.avatar_url,
            u.cover_photo,
            u.description AS user_description,
            u.role,
            u.is_email_verified,
            u.created_at AS user_created_at,
            u.updated_at AS user_updated_at,

            p.id AS profile_id,
            p.display_name,
            p.birth_year,
            p.workplace,
            p.studied_at,
            p.live_at,
            p.link_code,
            p.created_at AS profile_created_at,
            p.updated_at AS profile_updated_at
        FROM users u
        LEFT JOIN profile_user p ON u.id = p.user_id
        WHERE u.id = :user_id
        LIMIT 1
    ";

        $stmt = $db->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =============== Thêm thông tin user ===============
    public static function addProfileUser($user_id, $display_name, $birth_year, $workplace, $studied_at, $live_at)
    {
        $db = new connect();
        $link_code = connect::generateArticleCode($user_id);
        $sql = "INSERT INTO profile_user (user_id, display_name, birth_year, workplace, studied_at, live_at, link_code) 
                VALUES (:user_id, :display_name, :birth_year, :workplace, :studied_at, :live_at, :link_code)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':display_name' => $display_name,
            ':birth_year' => $birth_year,
            ':workplace' => $workplace,
            ':studied_at' => $studied_at,
            ':live_at' => $live_at,
            ':link_code' => $link_code
        ]);
    }
    // =============== Cập nhật thông tin user ===============
    public static function updateProfileUser($user_id, $display_name, $birth_year, $workplace, $studied_at, $live_at)
    {
        $db = new connect();
        $sql = "UPDATE profile_user 
            SET 
                display_name = :display_name, 
                birth_year   = :birth_year, 
                workplace    = :workplace, 
                studied_at   = :studied_at, 
                live_at      = :live_at
            WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);

        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->bindValue(':display_name', $display_name, $display_name === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':birth_year', $birth_year, $birth_year === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindValue(':workplace', $workplace, $workplace === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':studied_at', $studied_at, $studied_at === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':live_at', $live_at, $live_at === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        return $stmt->execute();
    }


    // =============== Xóa thông tin user ===============
    public static function deleteProfileUser($user_id)
    {
        $db = new connect();
        $sql = "DELETE FROM profile_user WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':user_id' => $user_id]);
    }

    // Thống kê 
    public static function getUserStats($user_id)
    {
        $db = new connect();

        // Số bài viết
        $sqlArticles = "SELECT COUNT(*) FROM articles WHERE author_id = :user_id";
        $stmt = $db->db->prepare($sqlArticles);
        $stmt->execute([':user_id' => $user_id]);
        $articlesCount = (int)$stmt->fetchColumn();

        // Người theo dõi
        $sqlFollowers = "SELECT COUNT(*) FROM user_follows WHERE following_id = :user_id";
        $stmt = $db->db->prepare($sqlFollowers);
        $stmt->execute([':user_id' => $user_id]);
        $followersCount = (int)$stmt->fetchColumn();

        // Đang theo dõi
        $sqlFollowing = "SELECT COUNT(*) FROM user_follows WHERE follower_id = :user_id";
        $stmt = $db->db->prepare($sqlFollowing);
        $stmt->execute([':user_id' => $user_id]);
        $followingCount = (int)$stmt->fetchColumn();

        // Lượt thích
        $sqlLikes = "
        SELECT COUNT(*) 
        FROM article_likes al
        INNER JOIN articles a ON al.article_id = a.id
        WHERE a.author_id = :user_id
    ";
        $stmt = $db->db->prepare($sqlLikes);
        $stmt->execute([':user_id' => $user_id]);
        $likesCount = (int)$stmt->fetchColumn();

        return [
            'articles' => $articlesCount,
            'followers' => $followersCount,
            'following' => $followingCount,
            'likes' => $likesCount
        ];
    }

    // Đăng kí làm doanh nhân 
    public function createBusinessmen($user_id, $birth_year, $nationality, $education, $position)
    {
        $db = new connect();

        try {
            // Bắt đầu transaction
            $db->db->beginTransaction();

            // 1. Insert vào bảng businessmen
            $stmt = $db->db->prepare("
                INSERT INTO businessmen (user_id, birth_year, nationality, education, position) 
                VALUES (:user_id, :birth_year, :nationality, :education, :position)
            ");
            $stmt->execute([
                ':user_id' => $user_id,
                ':birth_year' => $birth_year,
                ':nationality' => $nationality,
                ':education' => $education,
                ':position' => $position
            ]);

            // 2. Update role trong bảng users
            $stmt2 = $db->db->prepare("UPDATE users SET role = 'businessmen' WHERE id = :user_id");
            $stmt2->execute([':user_id' => $user_id]);

            // Commit
            $db->db->commit();
            return true;
        } catch (Exception $e) {
            $db->db->rollBack();
            return false;
        }
    }
}
