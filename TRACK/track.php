<?php
// track.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';

$db = new connect();                // giữ nguyên file db.php như bạn đang có
$pdo = $db->db;

// 1) Lấy token từ cookie, nếu chưa có thì tạo
if (empty($_COOKIE['site_token'])) {
  // Tạo token ngẫu nhiên 32 bytes -> hex 64 ký tự
  $token = bin2hex(random_bytes(32));
  // Lưu cookie 1 năm, HTTP only
  setcookie('site_token', $token, time() + 365*24*3600, '/', '', false, true);
} else {
  $token = $_COOKIE['site_token'];
}

// 2) Ghi nhận vào DB (upsert) + tăng page view
$now = date('Y-m-d H:i:s');
$pdo->beginTransaction();
try {
  // Nếu có rồi thì cập nhật last_seen, tăng hits; nếu chưa có thì chèn mới
  $stmt = $pdo->prepare("
    INSERT INTO visits (token, first_seen, last_seen, hits)
    VALUES (:t, :now, :now, 1)
    ON DUPLICATE KEY UPDATE last_seen = VALUES(last_seen), hits = hits + 1
  ");
  $stmt->execute([':t' => $token, ':now' => $now]);

  // Tăng tổng page views
  $pdo->exec("UPDATE stats SET views = views + 1 WHERE id = 1");

  $pdo->commit();
} catch (Throwable $e) {
  $pdo->rollBack();
  // tuỳ chọn: ghi log
}
