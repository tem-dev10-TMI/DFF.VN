<?php
class UserModel
{
    // Đăng ký user mới
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
}
