<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../model/CommentsModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['comment_id']) || !isset($data['ai_result'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

$comment_id = (int)$data['comment_id'];
$ai_result = $data['ai_result'];

if ($comment_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid comment ID']);
    exit;
}

try {
    $model = new CommentsModel();
    $isViolation = isset($ai_result['isViolation']) ? (bool)$ai_result['isViolation'] : false;
    
    $success = $model->updateAIResult($comment_id, $isViolation, $ai_result);
    
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'AI result updated successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update AI result']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
