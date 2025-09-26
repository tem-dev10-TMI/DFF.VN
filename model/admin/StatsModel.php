<?php
class StatsModel
{
    public static function totalViews(PDO $pdo): int
    {
        // Nếu bảng stats của bạn chỉ có 1 dòng thì có thể đổi thành: WHERE id=1
        $sql = "SELECT COALESCE(SUM(views),0) FROM stats";
        return (int) $pdo->query($sql)->fetchColumn();
    }
}

