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


    public static function getAllBusinessmen($limit = 10, $currentUserId = null)
    {
        global $pdo;

        // 1. Xây dựng câu SQL cơ bản
        $sql = "
            SELECT 
                b.id, 
                u.id AS user_id, 
                u.username, 
                u.name, 
                u.avatar_url,
                b.position,
                (SELECT COUNT(*) FROM user_follows f WHERE f.following_id = u.id) AS followers
            FROM businessmen b
            JOIN users u ON b.user_id = u.id
        ";

        // 2. Thêm điều kiện WHERE nếu có currentUserId được truyền vào
        if ($currentUserId !== null) {
            $sql .= " WHERE u.id != :currentUserId ";
        }

        // 3. Thêm phần còn lại của câu lệnh
        $sql .= "
            ORDER BY followers DESC
            LIMIT :limit
        ";

        // 4. Chuẩn bị và thực thi câu lệnh
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        // Chỉ bind giá trị :currentUserId nếu nó tồn tại
        if ($currentUserId !== null) {
            $stmt->bindValue(':currentUserId', (int)$currentUserId, PDO::PARAM_INT);
        }

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
}
