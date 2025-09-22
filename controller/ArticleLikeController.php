<?php
require_once __DIR__ . '/../model/ArticleLikesModel.php';

class ArticleLikeController
{
    public static function toggle()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'msg' => 'Invalid request']);
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId    = $_SESSION['user']['id'] ?? 0;
        $articleId = isset($_POST['article_id']) ? intval($_POST['article_id']) : 0;
        $type      = $_POST['type'] ?? ''; // 'like' hoặc 'dislike'

        if (!$userId) {
            echo json_encode(['status'=>'error','msg'=>'Bạn cần đăng nhập']);
            return;
        }

        if (!$articleId || !in_array($type, ['like','dislike'])) {
            echo json_encode(['status' => 'error', 'msg' => 'Dữ liệu không hợp lệ']);
            return;
        }

        $result = ArticleLikesModel::toggleLike($articleId, $userId, $type);
        $likeCount = ArticleLikesModel::getCounts($articleId);

        echo json_encode([
            'status'     => 'success',
            'action'     => $result,   // liked / removed / none
            'like'       => $likeCount,
            'user_vote'  => $result === 'liked' ? 'like' : '',
        ]);
    }

    public static function get()
    {
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $articleId = intval($_GET['article_id'] ?? 0);
        $userId    = $_SESSION['user']['id'] ?? 0;

        if (!$articleId) {
            echo json_encode(['status' => 'error', 'msg' => 'Thiếu ID bài viết']);
            return;
        }

        $likeCount = ArticleLikesModel::getCounts($articleId);
        $userVote = '';

        if ($userId) {
            $userLike = ArticleLikesModel::getUserLike($articleId, $userId);
            $userVote = $userLike ? 'like' : '';
        }

        echo json_encode([
            'status'    => 'success',
            'like'      => $likeCount,
            'user_vote' => $userVote
        ]);
    }
}
