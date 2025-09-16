<?php
class profileUserModel
{
    // =============== Lấy thông tin profileUser theo user_id ===============
    public static function getProfileUserByUserId($user_id)
    {
        $db = new connect();
        $sql = "SELECT * FROM profile_user WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetch();
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
                SET display_name = :display_name, birth_year = :birth_year, workplace = :workplace, studied_at = :studied_at, live_at = :live_at 
                WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':display_name' => $display_name,
            ':birth_year' => $birth_year,
            ':workplace' => $workplace,
            ':studied_at' => $studied_at,
            ':live_at' => $live_at
        ]);
    }
    // =============== Xóa thông tin user ===============
    public static function deleteProfileUser($user_id)
    {
        $db = new connect();
        $sql = "DELETE FROM profile_user WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':user_id' => $user_id]);
    }
}
