<?php
// metrics.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
$db = new connect();
$pdo = $db->db;

// đang trực tuyến = những token đã login (is_logged_in=1)
$online = (int) $pdo->query("
  SELECT COUNT(*)
  FROM visits
  WHERE is_logged_in = 1
")->fetchColumn();

// tổng lượt xem vẫn từ stats
$totalViews = (int) $pdo->query("SELECT views FROM stats WHERE id = 1")->fetchColumn();

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
  'onlineVisitors' => $online,
  'totalViews' => $totalViews
]);
