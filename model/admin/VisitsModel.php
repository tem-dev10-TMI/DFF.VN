<?php
class VisitsModel
{
    // Đếm phiên đang đăng nhập: is_logged_in = 1
    public static function onlineCount(PDO $pdo): int
    {
        $sql = "SELECT COUNT(*) FROM visits WHERE is_logged_in = 1";
        return (int) $pdo->query($sql)->fetchColumn();
    }

    // (tuỳ chọn) Nếu sau này muốn lọc theo thời gian:
    public static function onlineCountWithWindow(PDO $pdo, int $minutes): int
    {
        $minutes = max(1, (int) $minutes);
        $sql = "SELECT COUNT(*) 
                FROM visits 
                WHERE is_logged_in = 1 
                  AND last_seen >= (NOW() - INTERVAL $minutes MINUTE)";
        return (int) $pdo->query($sql)->fetchColumn();
    }
}
