<?php
class UserModel
{

    // Đăng ký user mới
    public static function registerUserNew($name, $username, $email, $password, $phone, $role = 'user')
    {
        $db = new connect();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, username, email, password_hash, phone, role) 
                VALUES (:name, :username, :email, :password_hash, :phone, 'user')";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':username' => $username,
            ':email' => $email,
            ':password_hash' => $hashedPassword,
            ':phone' => $phone
        ]);
    }

    public static function registerUser($name, $username, $email, $password, $phone, $role = 'user', $avatar_url = null, $cover_photo = null, $description = null)
    {
        $db = new connect();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, username, email, password_hash, phone, role, avatar_url, cover_photo, description) 
                VALUES (:name, :username, :email, :password_hash, :phone, 'user', :avatar_url, :cover_photo, :description)";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':username' => $username,
            ':email' => $email,
            ':password_hash' => $hashedPassword,
            ':phone' => $phone,
            ':avatar_url' => $avatar_url,
            ':cover_photo' => $cover_photo,
            ':description' => $description
        ]);
    }

    // Lấy thông tin user theo ID
    public static function getUserById($id)
    {
        $db = new connect();
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật thông tin user
    public static function updateUser($user_id, $name, $username, $email, $phone, $avatar_url, $cover_photo, $description)
    {
        $db = new connect();
        $sql = "UPDATE users SET 
                name = :name, 
                username = :username, 
                email = :email, 
                phone = :phone, 
                avatar_url = :avatar_url,
                cover_photo = :cover_photo,
                description = :description
            WHERE id = :user_id";
        $stmt = $db->db->prepare($sql);

        // Bind thủ công để hỗ trợ NULL
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->bindValue(':name', $name, $name === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':username', $username, $username === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, $email === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':phone', $phone, $phone === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':avatar_url', $avatar_url, $avatar_url === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':cover_photo', $cover_photo, $cover_photo === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, $description === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        return $stmt->execute();
    }

    // Lấy thông tin user theo username/email (phục vụ login)
    public static function getUserByUsernameOrEmail($usernameOrEmail)
    {
        $db = new connect();
        $sql = "SELECT * FROM users WHERE username = :ue OR email = :ue";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':ue' => $usernameOrEmail]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kiểm tra username hoặc email đã tồn tại chưa
    public static function checkUsernameOrEmailExists($username, $email)
    {
        $db = new connect();
        $sql = "SELECT id FROM users WHERE username = :username OR email = :email";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':username' => $username, ':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    // Xóa user theo ID
    public static function deleteUser($id)
    {
        $db = new connect();
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Đổi mật khẩu
    public static function updatePassword($user_id, $new_password_hash)
    {
        $db = new connect();

        $sql = "UPDATE users SET password_hash = :password_hash WHERE id = :user_id";

        $stmt = $db->db->prepare($sql);

        return $stmt->execute([
            ':password_hash' => $new_password_hash,
            ':user_id' => $user_id
        ]);
    }

    //Xác thực người dùng bằng username và mât khẩu
    public static function verifyUser($username, $password)
    {
        $db = new connect();
        try {
            $stmt = $db->db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                return $user;
            }
        } catch (PDOException $e) {
            error_log("Lỗi khi xác thực người dùng: " . $e->getMessage());
        }

        return false;
    }
    // =============== Lấy tất cả bài viết theo author_id (id user) ===============
    public static function getArticlesByAuthorId($author_id)
    {
        $db = new connect();
        $sql = "SELECT a.*, u.name AS author_name, u.avatar_url 
                FROM articles a
                JOIN users u ON a.author_id = u.id
                WHERE a.author_id = :author_id
                ORDER BY a.published_at DESC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':author_id' => $author_id]);
        return $stmt->fetchAll();
    }
    // ===================== Google login =====================
    // Kiểm tra user theo email
    public static function getUserByEmail($email)
    {
        $db = new connect();
        $stmt = $db->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function loginOrRegisterGoogleUser($name, $email, $avatar_url = null)
    {
        $user = self::getUserByEmail($email);

        if ($user) {
            // Cập nhật avatar nếu chưa có hoặc khác
            if ($avatar_url && $user['avatar_url'] !== $avatar_url) {
                $db = new connect();
                $stmt = $db->db->prepare("UPDATE users SET avatar_url = :avatar_url WHERE id = :id");
                $stmt->execute([
                    ':avatar_url' => $avatar_url,
                    ':id' => $user['id']
                ]);
            }
            // Fetch lại user từ DB để chắc chắn avatar_url là link
            return self::getUserByEmail($email);
        } else {
            // Nếu chưa có user -> tạo mới
            $db = new connect();

            // Tạo username từ name, nếu trùng thì thêm số random
            $baseUsername = preg_replace('/\s+/', '', strtolower($name));
            $username = $baseUsername;
            $i = 1;
            while (self::checkUsernameOrEmailExists($username, $email)) {
                $username = $baseUsername . rand(100, 999);
                $i++;
                if ($i > 10)
                    break; // phòng lặp vô hạn
            }

            $sql = "INSERT INTO users (name, username, email, avatar_url, role) 
                VALUES (:name, :username, :email, :avatar_url, 'user')";
            $stmt = $db->db->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':username' => $username,
                ':email' => $email,
                ':avatar_url' => $avatar_url
            ]);

            return self::getUserByEmail($email);
        }
    }
    public static function loginOrRegisterFacebookUser($name, $avatarUrl = null)
    {
        $db = new connect();

        // Kiểm tra user đã tồn tại theo name
        $stmt = $db->prepare("SELECT * FROM users WHERE name = :name LIMIT 1");
        $stmt->execute(['name' => $name]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return $user; // user đã tồn tại
        }

        // Nếu chưa có, tạo mới
        $stmt = $db->prepare("
        INSERT INTO users 
        (name, avatar_url, role, created_at, updated_at) 
        VALUES 
        (:name, :avatar, 'user', NOW(), NOW())
    ");
        $stmt->execute([
            'name' => $name,
            'avatar' => $avatarUrl
        ]);

        // Lấy ID vừa tạo
        $stmt = $db->prepare("SELECT id FROM users WHERE name = :name LIMIT 1");
        $stmt->execute(['name' => $name]);
        $id = $stmt->fetchColumn();

        return self::getUserById($id);
    }
    public static function searchUsers($q)
    {
        $db = new connect();
        $sql = "SELECT id, name, username, email, avatar_url 
            FROM users 
            WHERE name COLLATE utf8mb4_general_ci LIKE :q 
               OR username COLLATE utf8mb4_general_ci LIKE :q 
            ORDER BY name ASC";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':q' => "%$q%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function clearSessionToken($userId)
    {
        $db = new connect();
        $sql = "UPDATE users SET session_token = NULL WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $userId]);
    }

    public static function updateSessionToken($userId, $token)
    {
        $db = new connect();
        $sql = "UPDATE users SET session_token = :token WHERE id = :id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':id' => $userId, ':token' => $token]);
    }

    public static function isTokenValid($userId, $token)
    {
        if (empty($userId) || empty($token)) {
            return false;
        }
        $db = new connect();
        $sql = "SELECT COUNT(*) FROM users WHERE id = :id AND session_token = :token";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':id' => $userId, ':token' => $token]);
        return $stmt->fetchColumn() > 0;
    }
}