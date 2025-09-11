<?php
class UserConsentModel {
    // Thêm mới consent
    public static function addConsent($user_id, $consent_type, $is_granted) {
        $db = new connect();
        $sql = "INSERT INTO user_consents (user_id, consent_type, is_granted, last_updated) 
                VALUES (:user_id, :consent_type, :is_granted, NOW())";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':consent_type' => $consent_type,
            ':is_granted' => $is_granted
        ]);
    }

    // Cập nhật consent
    public static function updateConsent($user_id, $consent_type, $is_granted) {
        $db = new connect();
        $sql = "UPDATE user_consents 
                SET is_granted = :is_granted, last_updated = NOW() 
                WHERE user_id = :user_id AND consent_type = :consent_type";
        $stmt = $db->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':consent_type' => $consent_type,
            ':is_granted' => $is_granted
        ]);
    }

    // Lấy consent theo loại
    public static function getConsent($user_id, $consent_type) {
        $db = new connect();
        $sql = "SELECT * FROM user_consents WHERE user_id = :user_id AND consent_type = :consent_type";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':consent_type' => $consent_type
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả consent của user
    public static function getAllConsents($user_id) {
        $db = new connect();
        $sql = "SELECT * FROM user_consents WHERE user_id = :user_id";
        $stmt = $db->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
