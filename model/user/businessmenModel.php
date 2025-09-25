<?php

class businessmenModel
{
    // =============== Lấy thông tin doanh nhân theo user_id ===============
    public static function getBusinessByUserId($user_id)
    {
        $db = new connect();
        $sql = "SELECT u.id AS user_id, u.name, u.email, u.phone, u.avatar_url, u.cover_photo, u.description AS user_description,
                       b.id AS businessman_id, b.birth_year, b.nationality, b.education, b.position AS current_position
                FROM users u
                LEFT JOIN businessmen b ON u.id = b.user_id
                WHERE u.id = :user_id
                LIMIT 1";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =============== Đăng kí doanh nhân dựa trên users ===============
    public static function registerBusiness($user_id, $birth_year, $nationality, $education, $position)
    {
        $db = new connect();
        $sql = "INSERT INTO businessmen (user_id, birth_year, nationality, education, position) 
                VALUES (:user_id, :birth_year, :nationality, :education, :position)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':birth_year' => $birth_year,
            ':nationality' => $nationality,
            ':education' => $education,
            ':position' => $position
        ]);
    }
    // =============== Cập nhật thông tin doanh nhân ===============
    public static function updateBusiness($user_id, $birth_year, $nationality, $education, $position)
    {
        $db = new connect();
        $sql = "UPDATE businessmen 
                SET birth_year = :birth, nationality = :nationality, education = :education, position = :position 
                WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':birth' => $birth_year,
            ':nationality' => $nationality,
            ':education' => $education,
            ':position' => $position
        ]);
    }

    // =============== Xóa thông tin doanh nhân ===============
    public static function deleteBusiness($user_id)
    {
        $db = new connect();
        $sql = "DELETE FROM businessmen WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':user_id' => $user_id]);
    }

    //==========================================================================================
    //================================ Quá trình công tác ======================================
    //==========================================================================================

    // =============== Lấy danh sách quá trình công tác của doanh nhân ===============
    public static function getCareersByBusinessmenId($businessmen_id)
    {
        $db = new connect();
        $sql = "SELECT * FROM businessmen_careers WHERE businessmen_id = :businessmen_id ORDER BY start_year DESC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':businessmen_id' => $businessmen_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =============== Thêm quá trình công tác của doanh nhân ===============
    public static function addBusinessmenCareers($businessmen_id, $start_year, $end_year, $position, $company, $description)
    {
        $db = new connect();
        $sql = "INSERT INTO businessmen_careers (businessmen_id, start_year, end_year, position, company, description) 
                VALUES (:businessmen_id, :start_year, :end_year, :position, :company, :description)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':businessmen_id' => $businessmen_id,
            ':start_year' => $start_year,
            ':end_year' => $end_year,
            ':position' => $position,
            ':company' => $company,
            ':description' => $description
        ]);
    }

    // =============== Cập nhật quá trình công tác của doanh nhân ===============
    public static function updateBusinessmenCareers($id, $start_year, $end_year, $position, $company, $description)
    {
        $db = new connect();
        $sql = "UPDATE businessmen_careers 
                SET start_year = :start_year, end_year = :end_year, position = :position, company = :company, description = :description 
                WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':start_year' => $start_year,
            ':end_year' => $end_year,
            ':position' => $position,
            ':company' => $company,
            ':description' => $description
        ]);
    }

    // =============== Xóa quá trình công tác của doanh nhân ===============
    public static function deleteBusinessmenCareers($id)
    {
        $db = new connect();
        $sql = "DELETE FROM businessmen_careers WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    // ========== Lấy số follower của user ==========
    public static function getFollowersCount($user_id)
    {
        $db = new connect();
        $sql = "SELECT COUNT(*) FROM followers WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return (int) $stmt->fetchColumn();
    }

    // ========== Lấy số like của user ==========
    public static function getLikesCount($user_id)
    {
        $db = new connect();
        $sql = "SELECT COUNT(*) FROM likes WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return (int) $stmt->fetchColumn();
    }


    public static function getAllBusinessmen($limit = 6)
{
    $db = new connect();
    $pdo = $db->db;

    $sql = "
        SELECT 
            u.id AS user_id,
            u.name,
            u.username,
            u.avatar_url,
            b.position,
            COUNT(f.follower_id) AS followers
        FROM businessmen b
        INNER JOIN users u ON b.user_id = u.id
        LEFT JOIN user_follows f ON u.id = f.following_id
        WHERE b.status = 'approved'
        GROUP BY u.id, u.name, u.username, u.avatar_url, b.position
        ORDER BY followers DESC
        LIMIT :limit
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Tạo dữ liệu mẫu cho businessmen
    public static function createSampleBusinessmen()
    {
        $db = new connect();

        // Lấy một số users để tạo businessmen
        $sql = "SELECT id, name, username FROM users LIMIT 3";
        $stmt = $db->db->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($users)) {
            return false;
        }

        $sampleData = [
            ['position' => 'CEO', 'nationality' => 'Việt Nam', 'education' => 'MBA Harvard'],
            ['position' => 'CTO', 'nationality' => 'Việt Nam', 'education' => 'PhD Computer Science'],
            ['position' => 'CFO', 'nationality' => 'Việt Nam', 'education' => 'CPA']
        ];

        foreach ($users as $index => $user) {
            if (isset($sampleData[$index])) {
                $sql = "INSERT IGNORE INTO businessmen (user_id, position, nationality, education, birth_year) 
                    VALUES (:user_id, :position, :nationality, :education, :birth_year)";
                $stmt = $db->db->prepare($sql);
                $stmt->execute([
                    ':user_id' => $user['id'],
                    ':position' => $sampleData[$index]['position'],
                    ':nationality' => $sampleData[$index]['nationality'],
                    ':education' => $sampleData[$index]['education'],
                    ':birth_year' => 1980 + $index
                ]);
            }
        }

        return true;
    }

    //==========================================================================================
    //======== Lấy thông tin đầy đủ của doanh nhân theo user_id, bao gồm cả sự nghiệp ==========
    //==========================================================================================
    public function getFullBusinessmanProfile($user_id)
    {
        $info = $this->getBusinessByUserId($user_id);
        if ($info) {
            $info['careers'] = $this->getCareersByBusinessmenId($info['businessman_id']);
        }
        return $info;
    }

    // =============== Kiểm tra trạng thái doanh nhân đang chờ duyệt ===============
    public static function isPendingBusiness($id)
    {
        $db = new connect();
        $sql = "SELECT status FROM businessmen WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':user_id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['status'] === 'pending') {
            return true;
        }

        return false;
    }

    // Thống kê 
    public static function getBusinessStats($user_id)
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

    public static function getTopBusinessmen($limit = 10)
{
    $db = new connect();
    $limit = max(1, (int)$limit);

    $sql = "
        SELECT 
            u.id AS user_id,
            u.name,
            u.avatar_url,
            COALESCE(f.followers, 0) AS followers,
            COALESCE(l.likes, 0) AS likes
        FROM businessmen b
        JOIN users u ON u.id = b.user_id
        LEFT JOIN (
            SELECT following_id AS user_id, COUNT(*) AS followers
            FROM user_follows
            GROUP BY following_id
        ) f ON f.user_id = u.id
        LEFT JOIN (
            SELECT a.author_id AS user_id, COUNT(al.id) AS likes
            FROM articles a
            LEFT JOIN article_likes al ON al.article_id = a.id
            GROUP BY a.author_id
        ) l ON l.user_id = u.id
        ORDER BY followers DESC, likes DESC
        LIMIT :limit
    ";

    $stmt = $db->db->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
