<?php
class UserFollowModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function follow($followerId, $followingId) {
        // [ADD] chặn tự follow chính mình
        if ($followerId == $followingId) return false;

        // [ADD] idempotent: nếu đã follow thì coi như thành công
        if ($this->isFollowing($followerId, $followingId)) return true;

        try {
            $stmt = $this->db->prepare("INSERT INTO user_follows (follower_id, following_id) VALUES (?, ?)");
            return $stmt->execute([$followerId, $followingId]);
        } catch (\PDOException $e) {
            // [ADD] duplicate key -> coi như đã follow (trường hợp user bấm 2 lần)
            if ($e->getCode() === '23000') return true;
            throw $e;
        }
    }

    public function unfollow($followerId, $followingId) {
        $stmt = $this->db->prepare("DELETE FROM user_follows WHERE follower_id = ? AND following_id = ?");
        return $stmt->execute([$followerId, $followingId]);
    }

    public function isFollowing($followerId, $followingId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM user_follows WHERE follower_id = ? AND following_id = ?");
        $stmt->execute([$followerId, $followingId]);
        return $stmt->fetchColumn() > 0;
    }

    public function countFollowers($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM user_follows WHERE following_id = ?");
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }

    /* ===================== [ADD] TIỆN ÍCH HIỂN THỊ ===================== */

    // [ADD] Lấy danh sách user_id mà $followerId đang follow (để view biết đã follow)
    public function getFollowingIds(int $followerId): array {
        $stmt = $this->db->prepare("SELECT following_id FROM user_follows WHERE follower_id = ?");
        $stmt->execute([$followerId]);
        return array_map('intval', $stmt->fetchAll(\PDO::FETCH_COLUMN));
    }

    // [ADD] (tuỳ chọn) Đếm follower hàng loạt cho danh sách user — giúp render nhanh
    public function countFollowersBulk(array $userIds): array {
        if (empty($userIds)) return [];
        // tạo placeholders ?,?,?
        $placeholders = implode(',', array_fill(0, count($userIds), '?'));
        $sql = "SELECT following_id AS user_id, COUNT(*) AS cnt 
                FROM user_follows 
                WHERE following_id IN ($placeholders)
                GROUP BY following_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($userIds);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $map  = array_fill_keys($userIds, 0);
        foreach ($rows as $r) $map[(int)$r['user_id']] = (int)$r['cnt'];
        return $map;
    }

    // [ADD] Toggle follow/unfollow + trả về action & followers để UI cập nhật đồng bộ
    public function toggleFollow(int $followerId, int $followingId): array {
        if ($followerId == $followingId) {
            return ['success' => false, 'message' => 'Không thể tự theo dõi chính mình'];
        }

        if ($this->isFollowing($followerId, $followingId)) {
            $ok = $this->unfollow($followerId, $followingId);
            $action = 'unfollow';
        } else {
            $ok = $this->follow($followerId, $followingId);
            $action = 'follow';
        }
        if (!$ok) {
            return ['success' => false, 'message' => 'Không thể cập nhật theo dõi lúc này'];
        }
        return [
            'success'   => true,
            'action'    => $action,
            'followers' => $this->countFollowers($followingId)
        ];
    }
    // === [ADD] CHỈ LÀM VIỆC VỚI DOANH NHÂN ==============================

// Lấy danh sách user_id DOANH NHÂN mà $followerId đang follow
public function getFollowingBusinessmenIds(int $followerId): array {
    $sql = "SELECT uf.following_id
            FROM user_follows uf
            JOIN businessmen b ON b.user_id = uf.following_id
            WHERE uf.follower_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$followerId]);
    return array_map('intval', $stmt->fetchAll(\PDO::FETCH_COLUMN));
}

// Đếm follower cho N DOANH NHÂN trong 1 query (truyền mảng user_id doanh nhân)
public function countFollowersForBusinessmenBulk(array $businessUserIds): array {
    if (empty($businessUserIds)) return [];
    $placeholders = implode(',', array_fill(0, count($businessUserIds), '?'));
    $sql = "SELECT following_id, COUNT(*) AS cnt
            FROM user_follows
            WHERE following_id IN ($placeholders)
            GROUP BY following_id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute($businessUserIds);
    $map = array_fill_keys(array_map('intval', $businessUserIds), 0);
    foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $r) {
        $map[(int)$r['following_id']] = (int)$r['cnt'];
    }
    return $map;
}

}
