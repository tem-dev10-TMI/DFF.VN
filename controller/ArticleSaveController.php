<?php
require_once __DIR__ . '/../model/ArticleSavesModel.php';

class ArticleSaveController {

    private $model;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->model = new ArticleSavesModel();
    }

    // Toggle save/unsave
    public function toggle() {
        header('Content-Type: application/json');
    
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'msg' => 'Invalid request']);
            return;
        }
    
        $userId    = $_SESSION['user']['id'] ?? 0;
        $articleId = $_POST['article_id'] ?? 0;
    
        if (!$userId) {
            echo json_encode(['status' => 'error', 'msg' => 'Bạn cần đăng nhập']);
            return;
        }
    
        if (!$articleId) {
            echo json_encode(['status' => 'error', 'msg' => 'Bài viết không tồn tại']);
            return;
        }
    
        // Dùng luôn method toggleSave mới
        $status = $this->model->toggleSave($articleId, $userId);
    
        echo json_encode([
            'status' => 'success',
            'msg'    => $status === 'saved' ? 'Đã lưu bài viết' : 'Đã bỏ lưu'
        ]);
    }
    

    // Lấy danh sách bài viết đã lưu
    public function listSaved() {
        header('Content-Type: application/json');

        $userId = $_SESSION['user']['id'] ?? 0;
        if (!$userId) {
            echo json_encode(['status' => 'error', 'msg' => 'Bạn cần đăng nhập']);
            return;
        }

        $articles = $this->model->getSavedArticles($userId);
        echo json_encode(['status' => 'success', 'data' => $articles]);
    }
}
