<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../model/commentmodel.php';

class CommentController {
    // =====================
    // Thêm comment mới
    // =====================
    public function addComment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $article_id = $_POST['article_id'] ?? null;
            $user_id    = $_POST['user_id'] ?? null;
            $content    = trim($_POST['content'] ?? '');
            $parent_id  = $_POST['parent_id'] ?? null;

            if ($article_id && $user_id && $content) {
                try {
                    $result = CommentsModel::insertComment($article_id, $user_id, $content, $parent_id);
                    echo json_encode([
                        'status'  => $result ? 'success' : 'error',
                        'message' => $result ? 'Comment added successfully' : 'Failed to add comment'
                    ]);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Request method must be POST']);
        }
    }

    // =====================
    // Lấy comment theo bài viết
    // =====================
    public function getComments($article_id) {
        if (!$article_id) {
            echo json_encode(['status' => 'error', 'message' => 'article_id is required']);
            return;
        }
        $comments = CommentsModel::getComments($article_id);
        echo json_encode(['status' => 'success', 'comments' => $comments]);
    }

    // =====================
    // Lấy tất cả comment
    // =====================
    public function getAllComments() {
        $comments = CommentsModel::getAllComments();
        echo json_encode(['status' => 'success', 'comments' => $comments]);
    }

    // =====================
    // Lấy reply theo comment cha
    // =====================
    public function getReplies($parent_id) {
        if (!$parent_id) {
            echo json_encode(['status' => 'error', 'message' => 'parent_id is required']);
            return;
        }
        $replies = CommentsModel::getReplies($parent_id);
        echo json_encode(['status' => 'success', 'replies' => $replies]);
    }
}

// =====================
// Router cho AJAX
// =====================
$controller = new CommentController();
$action = $_GET['action'] ?? null;

switch ($action) {
    case 'addComment':
        $controller->addComment();
        break;

    case 'getComments':
        $article_id = $_GET['article_id'] ?? null;
        $controller->getComments($article_id);
        break;

    case 'getAllComments':
        $controller->getAllComments();
        break;

    case 'getReplies':
        $parent_id = $_GET['parent_id'] ?? null;
        $controller->getReplies($parent_id);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'No action specified']);
        break;
}
