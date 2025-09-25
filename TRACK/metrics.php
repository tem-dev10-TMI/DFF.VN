<?php
// metrics.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
$db = new connect();                // giữ nguyên file db.php như bạn đang có
$pdo = $db->db;

// Tổng visitor duy nhất (distinct token)
$totalVisitors = (int)$pdo->query("SELECT COUNT(*) FROM visits")->fetchColumn();

// Đang online (ví dụ: hoạt động trong 5 phút gần nhất)
$onlineVisitors = (int)$pdo->query("
  SELECT COUNT(*) FROM visits
  WHERE last_seen >= (NOW() - INTERVAL 5 MINUTE)
")->fetchColumn();

// Tổng page views
$totalViews = (int)$pdo->query("SELECT views FROM stats WHERE id = 1")->fetchColumn();

header('Content-Type: application/json');
echo json_encode([
  'totalVisitors'  => $totalVisitors,
  'onlineVisitors' => $onlineVisitors,
  'totalViews'     => $totalViews,
]);
