<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Chỉ start session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../model/CommentGlobalModel.php';

// Chỉ cho phép POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Lấy dữ liệu từ request body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON data']);
    exit;
}

// Validate required fields
$required_fields = ['comment_id', 'isViolation'];
foreach ($required_fields as $field) {
    if (!isset($data[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit;
    }
}

$comment_id = (int)$data['comment_id'];
$isViolation = (bool)$data['isViolation'];
$aiDetails = $data['aiDetails'] ?? null;

try {
    // Cập nhật kết quả AI check vào database
    $result = CommentGlobalModel::updateAIResult($comment_id, $isViolation, $aiDetails);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'AI result updated successfully',
            'comment_id' => $comment_id,
            'isViolation' => $isViolation
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update AI result']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
