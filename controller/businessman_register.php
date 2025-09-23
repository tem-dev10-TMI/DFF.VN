<?php

require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

// Kiểm tra user đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập!']);
    exit;
}

$user_id = $_SESSION['user_id'];
$birth_year = $_POST['birth_year'] ?? null;
$nationality = $_POST['nationality'] ?? null;
$education = $_POST['education'] ?? null;
$position = $_POST['position'] ?? null;

if (!$birth_year || !$nationality) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin bắt buộc!']);
    exit;
}

// Insert vào bảng businessmen (status = pending)
$stmt = $pdo->prepare("INSERT INTO businessmen (user_id, birth_year, nationality, education, position, status, created_at) 
                       VALUES (?, ?, ?, ?, ?, 'pending', NOW())");

if ($stmt->execute([$user_id, $birth_year, $nationality, $education, $position])) {
    echo json_encode(['success' => true, 'message' => 'Đã gửi yêu cầu đăng ký doanh nhân!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Không thể lưu vào CSDL']);
}
