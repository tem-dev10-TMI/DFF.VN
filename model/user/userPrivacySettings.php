<?php
class UserPrivacySettingsModel {
    // Tạo cài đặt riêng tư mặc định cho user
    public static function createDefaultSettings($user_id) {
        $db = new connect();
        $sql = "INSERT INTO user_privacy_settings (user_id, profile_visibility, activity_visibility) 
                VALUES (:user_id, 'public', 'public')";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([':user_id' => $user_id]);
    }

    // Cập nhật cài đặt riêng tư
    public static function updateSettings($user_id, $profile_visibility, $activity_visibility) {
        $db = new connect();
        $sql = "UPDATE user_privacy_settings 
                SET profile_visibility = :profile_visibility, activity_visibility = :activity_visibility 
                WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':profile_visibility' => $profile_visibility,
            ':activity_visibility' => $activity_visibility
        ]);
    }

    // Lấy cài đặt riêng tư của user
    public static function getSettings($user_id) {
        $db = new connect();
        $sql = "SELECT * FROM user_privacy_settings WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
