<?php
class MetricsController
{
    public static function online()
    {
        global $pdo;
        require_once __DIR__ . '/../../model/admin/VisitsModel.php';
        $online = VisitsModel::onlineCount($pdo); // chỉ đếm is_logged_in = 1
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['online' => $online], JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function totalViews()
    {
        global $pdo;
        require_once __DIR__ . '/../../model/admin/StatsModel.php';
        $total = StatsModel::totalViews($pdo);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['totalViews' => $total], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
